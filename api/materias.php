<?php
// api/materias.php — GET ?estudiante_id=N
// Devuelve SOLO las materias del semestre actual del estudiante.
// Estado: disponible | inscrita | sin_cupos | cruce_horario | limite_creditos | prerequisito_pendiente
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
require_once dirname(__DIR__) . '/config/database.php';

$est_id = isset($_GET['estudiante_id']) ? (int)$_GET['estudiante_id'] : 0;
if ($est_id <= 0) { http_response_code(400); echo json_encode(['success'=>false,'error'=>'estudiante_id requerido']); exit; }

$conn = conectar();

// Datos del estudiante
$s = $conn->prepare("SELECT id,semestre,creditos_max FROM estudiantes WHERE id=?");
$s->bind_param('i',$est_id); $s->execute();
$est = $s->get_result()->fetch_assoc(); $s->close();
if (!$est) { echo json_encode(['success'=>false,'error'=>'Estudiante no encontrado']); $conn->close(); exit; }

// Créditos ya inscritos este semestre
$s = $conn->prepare("SELECT COALESCE(SUM(m.creditos),0) AS total FROM inscripciones i JOIN materias m ON i.materia_id=m.id WHERE i.estudiante_id=? AND i.estado='inscrita'");
$s->bind_param('i',$est_id); $s->execute();
$cr_inscritos = (int)$s->get_result()->fetch_assoc()['total']; $s->close();

// IDs de materias ya inscritas
$s = $conn->prepare("SELECT materia_id FROM inscripciones WHERE estudiante_id=? AND estado='inscrita'");
$s->bind_param('i',$est_id); $s->execute();
$rins = $s->get_result(); $inscritas_ids = [];
while ($r = $rins->fetch_assoc()) $inscritas_ids[] = (int)$r['materia_id'];
$s->close();

// Horarios ya inscritos (para cruce)
$horarios_est = [];
if ($inscritas_ids) {
    $ph = implode(',', array_fill(0, count($inscritas_ids), '?'));
    $s = $conn->prepare("SELECT dia,hora_inicio,hora_fin FROM horarios WHERE materia_id IN ($ph)");
    $s->bind_param(str_repeat('i',count($inscritas_ids)), ...$inscritas_ids);
    $s->execute(); $rh = $s->get_result();
    while ($h = $rh->fetch_assoc()) $horarios_est[] = $h;
    $s->close();
}

// Materias aprobadas por el estudiante (para prerequisitos)
$s = $conn->prepare("SELECT materia_id FROM notas WHERE estudiante_id=? AND aprobada=1");
$s->bind_param('i',$est_id); $s->execute();
$rn = $s->get_result(); $aprobadas_ids = [];
while ($r = $rn->fetch_assoc()) $aprobadas_ids[] = (int)$r['materia_id'];
$s->close();

// Prerequisitos de cada materia (agrupar)
$prereqs = [];
$rp = $conn->query("SELECT materia_id, materia_previa_id FROM prerequisitos");
while ($p = $rp->fetch_assoc()) {
    $prereqs[(int)$p['materia_id']][] = (int)$p['materia_previa_id'];
}

function hay_cruce($h_nuevos, $h_est) {
    foreach ($h_nuevos as $hn) {
        foreach ($h_est as $he) {
            if ($hn['dia'] === $he['dia'] && $hn['hora_inicio'] < $he['hora_fin'] && $hn['hora_fin'] > $he['hora_inicio']) return true;
        }
    }
    return false;
}

// Materias del semestre actual del estudiante
$sem = (int)$est['semestre'];
$res = $conn->query("SELECT id,codigo,nombre,creditos,semestre_plan,cupos_total,cupos_restantes,docente,salon FROM materias WHERE semestre_plan=$sem ORDER BY nombre");

$materias = [];
while ($m = $res->fetch_assoc()) {
    // Horarios de la materia
    $sh = $conn->prepare("SELECT dia,hora_inicio,hora_fin FROM horarios WHERE materia_id=?");
    $sh->bind_param('i',$m['id']); $sh->execute();
    $hs = []; $rh = $sh->get_result();
    while ($h = $rh->fetch_assoc()) {
        $hs[] = ['dia'=>$h['dia'],'hora_inicio'=>substr($h['hora_inicio'],0,5),'hora_fin'=>substr($h['hora_fin'],0,5)];
    }
    $sh->close();

    // Verificar prerequisitos
    $prereqs_pendientes = [];
    if (isset($prereqs[(int)$m['id']])) {
        foreach ($prereqs[(int)$m['id']] as $prev_id) {
            if (!in_array($prev_id, $aprobadas_ids)) {
                // Obtener nombre de la materia previa
                $sp = $conn->prepare("SELECT nombre FROM materias WHERE id=?");
                $sp->bind_param('i',$prev_id); $sp->execute();
                $mn = $sp->get_result()->fetch_assoc(); $sp->close();
                $prereqs_pendientes[] = $mn['nombre'] ?? "Materia #$prev_id";
            }
        }
    }

    // Determinar estado_ux
    if (in_array((int)$m['id'], $inscritas_ids)) {
        $estado = 'inscrita';
    } elseif (!empty($prereqs_pendientes)) {
        $estado = 'prerequisito_pendiente';
    } elseif ((int)$m['cupos_restantes'] <= 0) {
        $estado = 'sin_cupos';
    } elseif ($cr_inscritos + (int)$m['creditos'] > (int)$est['creditos_max']) {
        $estado = 'limite_creditos';
    } elseif (hay_cruce($hs, $horarios_est)) {
        $estado = 'cruce_horario';
    } else {
        $estado = 'disponible';
    }

    $materias[] = [
        'id'                   => (int)$m['id'],
        'codigo'               => $m['codigo'],
        'nombre'               => $m['nombre'],
        'creditos'             => (int)$m['creditos'],
        'semestre_plan'        => (int)$m['semestre_plan'],
        'cupos_total'          => (int)$m['cupos_total'],
        'cupos_restantes'      => (int)$m['cupos_restantes'],
        'docente'              => $m['docente'],
        'salon'                => $m['salon'],
        'horarios'             => $hs,
        'estado_ux'            => $estado,
        'prereqs_pendientes'   => $prereqs_pendientes,
    ];
}
$conn->close();

echo json_encode([
    'success'    => true,
    'semestre'   => $sem,
    'estudiante' => ['id'=>(int)$est['id'],'creditos_max'=>(int)$est['creditos_max'],'creditos_inscritos'=>$cr_inscritos],
    'materias'   => $materias
], JSON_UNESCAPED_UNICODE);

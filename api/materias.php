<?php
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');

require_once dirname(__DIR__) . '/config/database.php';

$estudiante_id = isset($_GET['estudiante_id']) ? (int)$_GET['estudiante_id'] : 0;
if ($estudiante_id <= 0) {
    echo json_encode(['success'=>false,'error'=>'estudiante_id requerido']); exit;
}

$conn = conectar();

$stmt = $conn->prepare("SELECT id,codigo,nombre,programa,semestre,promedio,creditos_max FROM estudiantes WHERE id=?");
$stmt->bind_param('i',$estudiante_id);
$stmt->execute();
$est = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$est) { echo json_encode(['success'=>false,'error'=>'Estudiante no encontrado']); $conn->close(); exit; }

// Créditos ya inscritos
$stmt = $conn->prepare("SELECT COALESCE(SUM(m.creditos),0) AS total FROM inscripciones i JOIN materias m ON i.materia_id=m.id WHERE i.estudiante_id=? AND i.estado='inscrita'");
$stmt->bind_param('i',$estudiante_id); $stmt->execute();
$creditos_inscritos = (int)$stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

// Horarios ya inscritos (para detectar cruce)
$stmt = $conn->prepare("SELECT h.dia,h.hora_inicio,h.hora_fin FROM horarios h JOIN inscripciones i ON h.materia_id=i.materia_id WHERE i.estudiante_id=? AND i.estado='inscrita'");
$stmt->bind_param('i',$estudiante_id); $stmt->execute();
$rh = $stmt->get_result(); $horarios_inscritos=[];
while ($h=$rh->fetch_assoc()) $horarios_inscritos[]=$h;
$stmt->close();

// Todas las materias
$res = $conn->query("SELECT id,codigo,nombre,creditos,docente,salon,cupos_total,cupos_restantes FROM materias ORDER BY nombre");
$materias=[];
while ($m=$res->fetch_assoc()) {
    // Horarios de esta materia
    $sh=$conn->prepare("SELECT dia,hora_inicio,hora_fin FROM horarios WHERE materia_id=?");
    $sh->bind_param('i',$m['id']); $sh->execute();
    $rh2=$sh->get_result(); $hs=[];
    while ($h=$rh2->fetch_assoc()) $hs[]=['dia'=>$h['dia'],'hora_inicio'=>substr($h['hora_inicio'],0,5),'hora_fin'=>substr($h['hora_fin'],0,5)];
    $sh->close();

    // ¿Ya inscrita?
    $si=$conn->prepare("SELECT id FROM inscripciones WHERE estudiante_id=? AND materia_id=? AND estado='inscrita'");
    $si->bind_param('ii',$estudiante_id,$m['id']); $si->execute();
    $ya = $si->get_result()->num_rows > 0; $si->close();

    // Estado UX
    if ($ya) { $estado='inscrita'; }
    elseif ((int)$m['cupos_restantes']<=0) { $estado='sin_cupos'; }
    elseif ($creditos_inscritos + (int)$m['creditos'] > (int)$est['creditos_max']) { $estado='limite_creditos'; }
    else {
        $cruce=false;
        foreach ($hs as $hn) {
            foreach ($horarios_inscritos as $hi) {
                if ($hn['dia']===$hi['dia'] && $hn['hora_inicio'] < $hi['hora_fin'] && $hn['hora_fin'] > $hi['hora_inicio']) { $cruce=true; break 2; }
            }
        }
        $estado = $cruce ? 'cruce_horario' : 'disponible';
    }

    $materias[]=[
        'id'=>(int)$m['id'],'codigo'=>$m['codigo'],'nombre'=>$m['nombre'],
        'creditos'=>(int)$m['creditos'],'docente'=>$m['docente'],'salon'=>$m['salon'],
        'cupos_total'=>(int)$m['cupos_total'],'cupos_restantes'=>(int)$m['cupos_restantes'],
        'horarios'=>$hs,'estado_ux'=>$estado
    ];
}
$conn->close();

echo json_encode([
    'success'=>true,
    'estudiante'=>['creditos_inscritos'=>$creditos_inscritos,'creditos_max'=>(int)$est['creditos_max']],
    'materias'=>$materias
], JSON_UNESCAPED_UNICODE);

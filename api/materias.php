<?php
// ============================================================
// api/materias.php — GET ?estudiante_id=N
// Devuelve todas las materias con su estado_ux para el estudiante.
// ============================================================

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/database.php';

$estudiante_id = isset($_GET['estudiante_id']) ? (int)$_GET['estudiante_id'] : 0;

if ($estudiante_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'estudiante_id requerido']);
    exit;
}

$conn = conectar();

// Datos del estudiante
$stmt = $conn->prepare(
    "SELECT id, creditos_max FROM estudiantes WHERE id = ?"
);
$stmt->bind_param('i', $estudiante_id);
$stmt->execute();
$est = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$est) {
    echo json_encode(['success' => false, 'error' => 'Estudiante no encontrado']);
    $conn->close();
    exit;
}

// Créditos ya inscritos
$stmt = $conn->prepare(
    "SELECT COALESCE(SUM(m.creditos), 0) AS total
     FROM inscripciones ins
     JOIN materias m ON ins.materia_id = m.id
     WHERE ins.estudiante_id = ? AND ins.estado = 'inscrita'"
);
$stmt->bind_param('i', $estudiante_id);
$stmt->execute();
$cr_row = $stmt->get_result()->fetch_assoc();
$stmt->close();
$creditos_inscritos = (int)$cr_row['total'];

// Materias ya inscritas por este estudiante
$stmt = $conn->prepare(
    "SELECT materia_id FROM inscripciones WHERE estudiante_id = ? AND estado = 'inscrita'"
);
$stmt->bind_param('i', $estudiante_id);
$stmt->execute();
$rins = $stmt->get_result();
$inscritas_ids = [];
while ($r = $rins->fetch_assoc()) $inscritas_ids[] = (int)$r['materia_id'];
$stmt->close();

// Horarios del estudiante (para detectar cruces)
$horarios_est = [];
if ($inscritas_ids) {
    $placeholders = implode(',', array_fill(0, count($inscritas_ids), '?'));
    $stmt = $conn->prepare(
        "SELECT dia, hora_inicio, hora_fin FROM horarios WHERE materia_id IN ($placeholders)"
    );
    $stmt->bind_param(str_repeat('i', count($inscritas_ids)), ...$inscritas_ids);
    $stmt->execute();
    $rh = $stmt->get_result();
    while ($h = $rh->fetch_assoc()) $horarios_est[] = $h;
    $stmt->close();
}

function hay_cruce(array $h_nuevos, array $h_est): bool {
    foreach ($h_nuevos as $hn) {
        foreach ($h_est as $he) {
            if ($hn['dia'] !== $he['dia']) continue;
            // Solapamiento de intervalos
            if ($hn['hora_inicio'] < $he['hora_fin'] && $hn['hora_fin'] > $he['hora_inicio']) {
                return true;
            }
        }
    }
    return false;
}

// Todas las materias
$res = $conn->query(
    "SELECT id, codigo, nombre, creditos, docente, salon, cupos_total, cupos_restantes
     FROM materias ORDER BY nombre"
);

$materias = [];
while ($m = $res->fetch_assoc()) {
    // Horarios de la materia
    $sh = $conn->prepare("SELECT dia, hora_inicio, hora_fin FROM horarios WHERE materia_id = ?");
    $sh->bind_param('i', $m['id']);
    $sh->execute();
    $rh = $sh->get_result();
    $hs = [];
    while ($h = $rh->fetch_assoc()) {
        $hs[] = [
            'dia'        => $h['dia'],
            'hora_inicio'=> substr($h['hora_inicio'], 0, 5),
            'hora_fin'   => substr($h['hora_fin'],    0, 5)
        ];
    }
    $sh->close();

    // Determinar estado_ux
    if (in_array((int)$m['id'], $inscritas_ids)) {
        $estado = 'inscrita';
    } elseif ((int)$m['cupos_restantes'] <= 0) {
        $estado = 'sin_cupos';
    } elseif ($creditos_inscritos + (int)$m['creditos'] > (int)$est['creditos_max']) {
        $estado = 'limite_creditos';
    } elseif (hay_cruce($hs, $horarios_est)) {
        $estado = 'cruce_horario';
    } else {
        $estado = 'disponible';
    }

    $materias[] = [
        'id'              => (int)$m['id'],
        'codigo'          => $m['codigo'],
        'nombre'          => $m['nombre'],
        'creditos'        => (int)$m['creditos'],
        'docente'         => $m['docente'],
        'salon'           => $m['salon'],
        'cupos_total'     => (int)$m['cupos_total'],
        'cupos_restantes' => (int)$m['cupos_restantes'],
        'horarios'        => $hs,
        'estado_ux'       => $estado
    ];
}

$conn->close();

echo json_encode([
    'success'    => true,
    'estudiante' => [
        'id'                => (int)$est['id'],
        'creditos_max'      => (int)$est['creditos_max'],
        'creditos_inscritos'=> $creditos_inscritos
    ],
    'materias' => $materias
], JSON_UNESCAPED_UNICODE);

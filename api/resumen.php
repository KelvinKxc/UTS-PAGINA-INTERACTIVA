<?php
// ============================================================
// api/resumen.php — GET ?estudiante_id=N
// Devuelve JSON con materias inscritas y resumen de créditos.
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
    "SELECT id, codigo, nombre, programa, semestre, promedio, creditos_max
     FROM estudiantes WHERE id = ?"
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

// Materias inscritas
$stmt = $conn->prepare(
    "SELECT m.id, m.codigo, m.nombre, m.creditos, m.docente, m.salon, ins.fecha_inscripcion
     FROM inscripciones ins
     JOIN materias m ON ins.materia_id = m.id
     WHERE ins.estudiante_id = ? AND ins.estado = 'inscrita'
     ORDER BY m.nombre"
);
$stmt->bind_param('i', $estudiante_id);
$stmt->execute();
$res = $stmt->get_result();
$stmt->close();

$mats     = [];
$total_cr = 0;

while ($row = $res->fetch_assoc()) {
    // Horarios de cada materia
    $sh = $conn->prepare("SELECT dia, hora_inicio, hora_fin FROM horarios WHERE materia_id = ?");
    $sh->bind_param('i', $row['id']);
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

    $total_cr += (int)$row['creditos'];
    $mats[] = [
        'id'                => (int)$row['id'],
        'codigo'            => $row['codigo'],
        'nombre'            => $row['nombre'],
        'creditos'          => (int)$row['creditos'],
        'docente'           => $row['docente'],
        'salon'             => $row['salon'],
        'horarios'          => $hs,
        'fecha_inscripcion' => $row['fecha_inscripcion']
    ];
}

$conn->close();

$max = (int)$est['creditos_max'];

echo json_encode([
    'success'    => true,
    'estudiante' => [
        'id'          => (int)$est['id'],
        'codigo'      => $est['codigo'],
        'nombre'      => $est['nombre'],
        'programa'    => $est['programa'],
        'semestre'    => (int)$est['semestre'],
        'promedio'    => (float)$est['promedio'],
        'creditos_max'=> $max
    ],
    'resumen_creditos' => [
        'inscritos'   => $total_cr,
        'disponibles' => $max - $total_cr,
        'maximo'      => $max,
        'porcentaje'  => $max > 0 ? round(($total_cr / $max) * 100, 1) : 0
    ],
    'materias_inscritas' => $mats,
    'total_materias'     => count($mats)
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

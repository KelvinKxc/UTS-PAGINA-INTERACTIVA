<?php
// ============================================================
// materias.php — GET ?estudiante_id=N
// ============================================================
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/database.php';

$estudiante_id = intval($_GET['estudiante_id'] ?? 0);

if (!$estudiante_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'estudiante_id requerido.']);
    exit;
}

$conn = conectar();

// ── Datos del estudiante ──────────────────────────────────
$stmt = $conn->prepare(
    "SELECT id, codigo, nombre, programa, semestre, creditos_max
     FROM estudiantes WHERE id = ?"
);
$stmt->bind_param('i', $estudiante_id);
$stmt->execute();
$est = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$est) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Estudiante no encontrado.']);
    $conn->close();
    exit;
}

// ── Créditos ya inscritos ─────────────────────────────────
$stmt = $conn->prepare(
    "SELECT COALESCE(SUM(m.creditos), 0) AS total
     FROM inscripciones i
     JOIN materias m ON m.id = i.materia_id
     WHERE i.estudiante_id = ?"
);
$stmt->bind_param('i', $estudiante_id);
$stmt->execute();
$creditos_inscritos = (int)$stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

// ── Materias ya inscritas por este estudiante ─────────────
$stmt = $conn->prepare(
    "SELECT materia_id FROM inscripciones WHERE estudiante_id = ?"
);
$stmt->bind_param('i', $estudiante_id);
$stmt->execute();
$res = $stmt->get_result();
$inscritas = [];
while ($row = $res->fetch_assoc()) {
    $inscritas[] = (int)$row['materia_id'];
}
$stmt->close();

// ── Horarios de materias ya inscritas (para detectar cruces) ─
$horarios_inscritos = [];
if (!empty($inscritas)) {
    $placeholders = implode(',', array_fill(0, count($inscritas), '?'));
    $tipos = str_repeat('i', count($inscritas));
    $stmt = $conn->prepare(
        "SELECT dia, hora_inicio, hora_fin
         FROM horarios WHERE materia_id IN ($placeholders)"
    );
    $stmt->bind_param($tipos, ...$inscritas);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $horarios_inscritos[] = $row;
    }
    $stmt->close();
}

// ── Todas las materias ────────────────────────────────────
$result = $conn->query(
    "SELECT id, codigo, nombre, creditos, docente, salon, cupos_total, cupos_restantes
     FROM materias ORDER BY nombre"
);

$materias = [];

while ($m = $result->fetch_assoc()) {
    $mid = (int)$m['id'];

    // Horarios de esta materia
    $stmt2 = $conn->prepare(
        "SELECT dia, hora_inicio, hora_fin FROM horarios WHERE materia_id = ?"
    );
    $stmt2->bind_param('i', $mid);
    $stmt2->execute();
    $hres = $stmt2->get_result();
    $horarios = [];
    while ($h = $hres->fetch_assoc()) {
        $horarios[] = $h;
    }
    $stmt2->close();

    // ── Determinar estado_ux ──────────────────────────────
    if (in_array($mid, $inscritas)) {
        $estado = 'inscrita';
    } elseif ((int)$m['cupos_restantes'] <= 0) {
        $estado = 'sin_cupos';
    } elseif ($creditos_inscritos + (int)$m['creditos'] > (int)$est['creditos_max']) {
        $estado = 'limite_creditos';
    } else {
        // Verificar cruce de horario
        $hay_cruce = false;
        foreach ($horarios as $h) {
            foreach ($horarios_inscritos as $hi) {
                if ($h['dia'] === $hi['dia'] &&
                    $h['hora_inicio'] < $hi['hora_fin'] &&
                    $h['hora_fin']    > $hi['hora_inicio']) {
                    $hay_cruce = true;
                    break 2;
                }
            }
        }
        $estado = $hay_cruce ? 'cruce_horario' : 'disponible';
    }

    $materias[] = [
        'id'              => $mid,
        'codigo'          => $m['codigo'],
        'nombre'          => $m['nombre'],
        'creditos'        => (int)$m['creditos'],
        'docente'         => $m['docente'],
        'salon'           => $m['salon'],
        'cupos_total'     => (int)$m['cupos_total'],
        'cupos_restantes' => (int)$m['cupos_restantes'],
        'horarios'        => $horarios,
        'estado_ux'       => $estado,
    ];
}

$conn->close();

echo json_encode([
    'success'  => true,
    'estudiante' => [
        'id'                => (int)$est['id'],
        'codigo'            => $est['codigo'],
        'nombre'            => $est['nombre'],
        'programa'          => $est['programa'],
        'semestre'          => (int)$est['semestre'],
        'creditos_inscritos'=> $creditos_inscritos,
        'creditos_max'      => (int)$est['creditos_max'],
    ],
    'materias' => $materias,
], JSON_UNESCAPED_UNICODE);

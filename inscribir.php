<?php
// ============================================================
// inscribir.php — POST { estudiante_id, materia_id }
// ============================================================
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método no permitido. Use POST.']);
    exit;
}

require_once __DIR__ . '/database.php';

$body        = json_decode(file_get_contents('php://input'), true);
$est_id      = intval($body['estudiante_id'] ?? 0);
$materia_id  = intval($body['materia_id']    ?? 0);

if (!$est_id || !$materia_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'estudiante_id y materia_id requeridos.']);
    exit;
}

$conn = conectar();

// ── Verificar que el estudiante exista ────────────────────
$stmt = $conn->prepare("SELECT id, creditos_max FROM estudiantes WHERE id = ?");
$stmt->bind_param('i', $est_id);
$stmt->execute();
$est = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$est) {
    echo json_encode(['success' => false, 'error' => 'Estudiante no encontrado.']);
    $conn->close(); exit;
}

// ── Verificar que la materia exista y tenga cupos ─────────
$stmt = $conn->prepare(
    "SELECT id, nombre, creditos, cupos_restantes FROM materias WHERE id = ?"
);
$stmt->bind_param('i', $materia_id);
$stmt->execute();
$mat = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$mat) {
    echo json_encode(['success' => false, 'error' => 'Materia no encontrada.']);
    $conn->close(); exit;
}

if ((int)$mat['cupos_restantes'] <= 0) {
    echo json_encode(['success' => false, 'error' => 'La materia no tiene cupos disponibles.']);
    $conn->close(); exit;
}

// ── Verificar que no esté ya inscrita ─────────────────────
$stmt = $conn->prepare(
    "SELECT id FROM inscripciones WHERE estudiante_id = ? AND materia_id = ?"
);
$stmt->bind_param('ii', $est_id, $materia_id);
$stmt->execute();
$ya = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($ya) {
    echo json_encode(['success' => false, 'error' => 'Ya tienes esta materia inscrita.']);
    $conn->close(); exit;
}

// ── Verificar límite de créditos ──────────────────────────
$stmt = $conn->prepare(
    "SELECT COALESCE(SUM(m.creditos), 0) AS total
     FROM inscripciones i JOIN materias m ON m.id = i.materia_id
     WHERE i.estudiante_id = ?"
);
$stmt->bind_param('i', $est_id);
$stmt->execute();
$creditos_actuales = (int)$stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

if ($creditos_actuales + (int)$mat['creditos'] > (int)$est['creditos_max']) {
    echo json_encode(['success' => false, 'error' => 'Superarías el límite de créditos del semestre.']);
    $conn->close(); exit;
}

// ── Verificar cruce de horario ────────────────────────────
$stmt = $conn->prepare(
    "SELECT h.dia, h.hora_inicio, h.hora_fin
     FROM horarios h
     JOIN inscripciones i ON i.materia_id = h.materia_id
     WHERE i.estudiante_id = ?"
);
$stmt->bind_param('i', $est_id);
$stmt->execute();
$res = $stmt->get_result();
$horarios_inscritos = [];
while ($row = $res->fetch_assoc()) {
    $horarios_inscritos[] = $row;
}
$stmt->close();

$stmt = $conn->prepare(
    "SELECT dia, hora_inicio, hora_fin FROM horarios WHERE materia_id = ?"
);
$stmt->bind_param('i', $materia_id);
$stmt->execute();
$res = $stmt->get_result();
$horarios_nueva = [];
while ($row = $res->fetch_assoc()) {
    $horarios_nueva[] = $row;
}
$stmt->close();

foreach ($horarios_nueva as $h) {
    foreach ($horarios_inscritos as $hi) {
        if ($h['dia'] === $hi['dia'] &&
            $h['hora_inicio'] < $hi['hora_fin'] &&
            $h['hora_fin']    > $hi['hora_inicio']) {
            echo json_encode(['success' => false, 'error' => 'Hay un cruce de horario con otra materia inscrita.']);
            $conn->close(); exit;
        }
    }
}

// ── Inscribir: insertar y descontar cupo ──────────────────
$conn->begin_transaction();

try {
    $stmt = $conn->prepare(
        "INSERT INTO inscripciones (estudiante_id, materia_id) VALUES (?, ?)"
    );
    $stmt->bind_param('ii', $est_id, $materia_id);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare(
        "UPDATE materias SET cupos_restantes = cupos_restantes - 1 WHERE id = ? AND cupos_restantes > 0"
    );
    $stmt->bind_param('i', $materia_id);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();

    if ($affected === 0) {
        throw new Exception('No quedaban cupos al momento de inscribir.');
    }

    $conn->commit();

    echo json_encode([
        'success' => true,
        'mensaje' => '✅ ' . $mat['nombre'] . ' inscrita correctamente.',
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();

<?php
// ============================================================
// api/inscribir.php — POST { estudiante_id, materia_id }
// Inscribe al estudiante en la materia si no hay impedimentos.
// ============================================================

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
    exit;
}

require_once __DIR__ . '/database.php';

$body          = json_decode(file_get_contents('php://input'), true);
$estudiante_id = (int)($body['estudiante_id'] ?? 0);
$materia_id    = (int)($body['materia_id']    ?? 0);

if (!$estudiante_id || !$materia_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Parámetros requeridos: estudiante_id, materia_id.']);
    exit;
}

$conn = conectar();

// Verificar que el estudiante existe
$stmt = $conn->prepare("SELECT id, creditos_max FROM estudiantes WHERE id = ?");
$stmt->bind_param('i', $estudiante_id);
$stmt->execute();
$est = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$est) {
    echo json_encode(['success' => false, 'error' => 'Estudiante no encontrado.']);
    $conn->close(); exit;
}

// Verificar que la materia existe y tiene cupos
$stmt = $conn->prepare("SELECT id, nombre, creditos, cupos_restantes FROM materias WHERE id = ?");
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

// Verificar que no esté ya inscrita
$stmt = $conn->prepare(
    "SELECT id FROM inscripciones WHERE estudiante_id = ? AND materia_id = ? AND estado = 'inscrita'"
);
$stmt->bind_param('ii', $estudiante_id, $materia_id);
$stmt->execute();
$ya = $stmt->get_result()->fetch_assoc();
$stmt->close();
if ($ya) {
    echo json_encode(['success' => false, 'error' => 'Ya estás inscrito en esta materia.']);
    $conn->close(); exit;
}

// Verificar límite de créditos
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
$cr_actuales = (int)$cr_row['total'];

if ($cr_actuales + (int)$mat['creditos'] > (int)$est['creditos_max']) {
    echo json_encode(['success' => false, 'error' => 'Superarías el límite de créditos permitido.']);
    $conn->close(); exit;
}

// Insertar inscripción y descontar cupo (transacción)
$conn->begin_transaction();
try {
    $stmt = $conn->prepare(
        "INSERT INTO inscripciones (estudiante_id, materia_id, estado, fecha_inscripcion)
         VALUES (?, ?, 'inscrita', NOW())"
    );
    $stmt->bind_param('ii', $estudiante_id, $materia_id);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare(
        "UPDATE materias SET cupos_restantes = cupos_restantes - 1 WHERE id = ? AND cupos_restantes > 0"
    );
    $stmt->bind_param('i', $materia_id);
    $stmt->execute();
    $afectadas = $stmt->affected_rows;
    $stmt->close();

    if ($afectadas === 0) {
        throw new Exception('No hay cupos disponibles (condición de carrera).');
    }

    $conn->commit();
    echo json_encode([
        'success' => true,
        'mensaje' => "✅ Te inscribiste exitosamente en «{$mat['nombre']}»."
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();

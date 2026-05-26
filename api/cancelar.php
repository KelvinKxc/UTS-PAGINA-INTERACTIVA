<?php
// api/cancelar.php — POST { estudiante_id, materia_id }
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
    exit;
}
require_once dirname(__DIR__) . '/config/database.php';
$body          = json_decode(file_get_contents('php://input'), true);
$estudiante_id = (int)($body['estudiante_id'] ?? 0);
$materia_id    = (int)($body['materia_id']    ?? 0);
if (!$estudiante_id || !$materia_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Parámetros requeridos.']);
    exit;
}
$conn = conectar();
$stmt = $conn->prepare(
    "SELECT ins.id, m.nombre FROM inscripciones ins
     JOIN materias m ON ins.materia_id = m.id
     WHERE ins.estudiante_id = ? AND ins.materia_id = ? AND ins.estado = 'inscrita'"
);
$stmt->bind_param('ii', $estudiante_id, $materia_id);
$stmt->execute();
$ins = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$ins) {
    echo json_encode(['success' => false, 'error' => 'No tienes esa materia inscrita.']);
    $conn->close(); exit;
}
$conn->begin_transaction();
try {
    $stmt = $conn->prepare("UPDATE inscripciones SET estado='cancelada' WHERE estudiante_id=? AND materia_id=?");
    $stmt->bind_param('ii', $estudiante_id, $materia_id);
    $stmt->execute(); $stmt->close();
    $stmt = $conn->prepare("UPDATE materias SET cupos_restantes = cupos_restantes + 1 WHERE id = ?");
    $stmt->bind_param('i', $materia_id);
    $stmt->execute(); $stmt->close();
    $conn->commit();
    echo json_encode(['success' => true, 'mensaje' => "Cancelaste la inscripción de «{$ins['nombre']}»."], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
$conn->close();

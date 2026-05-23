<?php
// ============================================================
// api/login.php — POST { codigo, password }
// Devuelve JSON con los datos del estudiante autenticado.
// ============================================================

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método no permitido. Use POST.']);
    exit;
}

require_once __DIR__ . '/database.php';

$body     = json_decode(file_get_contents('php://input'), true);
$codigo   = trim($body['codigo']   ?? '');
$password = trim($body['password'] ?? '');

if (!$codigo || !$password) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Código y contraseña requeridos.']);
    exit;
}

$conn = conectar();

$stmt = $conn->prepare(
    "SELECT id, codigo, nombre, programa, semestre, promedio, creditos_max
     FROM estudiantes WHERE codigo = ?"
);
$stmt->bind_param('s', $codigo);
$stmt->execute();
$est = $stmt->get_result()->fetch_assoc();
$stmt->close();
$conn->close();

if (!$est) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Código estudiantil no encontrado.']);
    exit;
}

// Contraseña demo: '1234' para todos los estudiantes
// En producción usar password_verify() con hashes almacenados
if ($password !== '1234') {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Contraseña incorrecta.']);
    exit;
}

echo json_encode([
    'success'    => true,
    'estudiante' => [
        'id'       => (int)$est['id'],
        'codigo'   => $est['codigo'],
        'nombre'   => $est['nombre'],
        'programa' => $est['programa'],
        'semestre' => (int)$est['semestre'],
        'promedio' => (float)$est['promedio'],
    ]
], JSON_UNESCAPED_UNICODE);

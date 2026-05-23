<?php
// ============================================================
//  endpoints/inscribir.php
//  POST /endpoints/inscribir.php
//
//  Body JSON: { "estudiante_id": 1, "materia_id": 1 }
//
//  Valida cupos, cruces, límite de créditos y duplicados
//  antes de inscribir. Retorna el nuevo resumen de créditos.
// ============================================================

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Preflight CORS
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

// ── Leer body JSON ─────────────────────────────────────────
$body = json_decode(file_get_contents('php://input'), true);

$estudiante_id = isset($body['estudiante_id']) ? (int)$body['estudiante_id'] : 0;
$materia_id    = isset($body['materia_id'])    ? (int)$body['materia_id']    : 0;

if ($estudiante_id <= 0 || $materia_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Parámetros estudiante_id y materia_id son requeridos']);
    exit;
}

$conn = conectar();

// ── 1. Verificar que el estudiante exista ──────────────────
$stmt = $conn->prepare("SELECT id, creditos_max FROM estudiantes WHERE id = ?");
$stmt->bind_param('i', $estudiante_id);
$stmt->execute();
$estudiante = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$estudiante) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Estudiante no encontrado']);
    $conn->close(); exit;
}

// ── 2. Verificar que la materia exista y tenga cupos ───────
$stmt = $conn->prepare("SELECT id, nombre, creditos, cupos_total, cupos_usados
                        FROM materias WHERE id = ?");
$stmt->bind_param('i', $materia_id);
$stmt->execute();
$materia = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$materia) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Materia no encontrada']);
    $conn->close(); exit;
}

$cupos_restantes = (int)$materia['cupos_total'] - (int)$materia['cupos_usados'];
if ($cupos_restantes <= 0) {
    http_response_code(409);
    echo json_encode([
        'success' => false,
        'error'   => 'No hay cupos disponibles para esta materia',
        'codigo'  => 'SIN_CUPOS'
    ]);
    $conn->close(); exit;
}

// ── 3. Verificar que no esté ya inscrita ──────────────────
$stmt = $conn->prepare("SELECT id FROM inscripciones
                        WHERE estudiante_id = ? AND materia_id = ? AND estado = 'inscrita'");
$stmt->bind_param('ii', $estudiante_id, $materia_id);
$stmt->execute();
$ya_inscrita = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($ya_inscrita) {
    http_response_code(409);
    echo json_encode([
        'success' => false,
        'error'   => 'Ya tienes esta materia inscrita',
        'codigo'  => 'YA_INSCRITA'
    ]);
    $conn->close(); exit;
}

// ── 4. Verificar límite de créditos ───────────────────────
$stmt = $conn->prepare("SELECT SUM(mat.creditos) AS total
                        FROM inscripciones ins
                        JOIN materias mat ON ins.materia_id = mat.id
                        WHERE ins.estudiante_id = ? AND ins.estado = 'inscrita'");
$stmt->bind_param('i', $estudiante_id);
$stmt->execute();
$res_cr = $stmt->get_result()->fetch_assoc();
$stmt->close();

$creditos_actuales = (int)($res_cr['total'] ?? 0);
$creditos_nuevos   = $creditos_actuales + (int)$materia['creditos'];

if ($creditos_nuevos > (int)$estudiante['creditos_max']) {
    http_response_code(409);
    echo json_encode([
        'success' => false,
        'error'   => "Supera el límite de créditos. Tienes {$creditos_actuales} de {$estudiante['creditos_max']} permitidos.",
        'codigo'  => 'LIMITE_CREDITOS'
    ]);
    $conn->close(); exit;
}

// ── 5. Verificar cruce de horario ─────────────────────────
// Horarios de la materia a inscribir
$stmt = $conn->prepare("SELECT dia, hora_inicio, hora_fin FROM horarios WHERE materia_id = ?");
$stmt->bind_param('i', $materia_id);
$stmt->execute();
$horarios_nueva = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Horarios de materias ya inscritas
$stmt = $conn->prepare("SELECT h.dia, h.hora_inicio, h.hora_fin
                        FROM horarios h
                        JOIN inscripciones ins ON h.materia_id = ins.materia_id
                        WHERE ins.estudiante_id = ? AND ins.estado = 'inscrita'");
$stmt->bind_param('i', $estudiante_id);
$stmt->execute();
$horarios_existentes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

foreach ($horarios_nueva as $hn) {
    foreach ($horarios_existentes as $he) {
        if ($hn['dia'] === $he['dia']) {
            if ($hn['hora_inicio'] < $he['hora_fin'] && $hn['hora_fin'] > $he['hora_inicio']) {
                http_response_code(409);
                echo json_encode([
                    'success' => false,
                    'error'   => "Cruce de horario el día {$hn['dia']} entre {$hn['hora_inicio']} y {$hn['hora_fin']}",
                    'codigo'  => 'CRUCE_HORARIO'
                ]);
                $conn->close(); exit;
            }
        }
    }
}

// ── 6. Inscribir (transacción atómica) ────────────────────
$conn->begin_transaction();
try {
    // Insertar inscripción
    $stmt = $conn->prepare("INSERT INTO inscripciones (estudiante_id, materia_id, estado)
                            VALUES (?, ?, 'inscrita')");
    $stmt->bind_param('ii', $estudiante_id, $materia_id);
    $stmt->execute();
    $stmt->close();

    // Actualizar cupos_usados
    $stmt = $conn->prepare("UPDATE materias SET cupos_usados = cupos_usados + 1 WHERE id = ?");
    $stmt->bind_param('i', $materia_id);
    $stmt->execute();
    $stmt->close();

    $conn->commit();

    // Nuevo resumen de créditos
    $creditos_inscritos_final = $creditos_actuales + (int)$materia['creditos'];

    echo json_encode([
        'success' => true,
        'mensaje' => "¡Materia '{$materia['nombre']}' inscrita exitosamente!",
        'resumen' => [
            'creditos_inscritos'    => $creditos_inscritos_final,
            'creditos_disponibles'  => (int)$estudiante['creditos_max'] - $creditos_inscritos_final,
            'creditos_max'          => (int)$estudiante['creditos_max']
        ]
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'Error al inscribir la materia: ' . $e->getMessage()
    ]);
}

$conn->close();

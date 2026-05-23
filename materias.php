<?php
// ============================================================
//  endpoints/materias.php
//  GET /endpoints/materias.php?estudiante_id=1
//
//  Retorna: lista de materias disponibles con:
//    - cupos restantes (crítico para UX)
//    - bandera si ya fue inscrita por el estudiante
//    - bandera de cruce de horario con sus materias actuales
// ============================================================

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once __DIR__ . '/database.php';

// ── Parámetro obligatorio ───────────────────────────────────
$estudiante_id = isset($_GET['estudiante_id']) ? (int)$_GET['estudiante_id'] : 0;

if ($estudiante_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Parámetro estudiante_id requerido']);
    exit;
}

$conn = conectar();

// ── 1. Obtener datos del estudiante ────────────────────────
$sql_est = "SELECT id, codigo, nombre, programa, semestre, promedio, creditos_max
            FROM estudiantes WHERE id = ?";
$stmt = $conn->prepare($sql_est);
$stmt->bind_param('i', $estudiante_id);
$stmt->execute();
$estudiante = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$estudiante) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Estudiante no encontrado']);
    $conn->close();
    exit;
}

// ── 2. Materias ya inscritas por el estudiante ─────────────
$sql_inscritas = "SELECT materia_id FROM inscripciones
                  WHERE estudiante_id = ? AND estado = 'inscrita'";
$stmt = $conn->prepare($sql_inscritas);
$stmt->bind_param('i', $estudiante_id);
$stmt->execute();
$res = $stmt->get_result();
$ids_inscritas = [];
while ($row = $res->fetch_assoc()) {
    $ids_inscritas[] = $row['materia_id'];
}
$stmt->close();

// ── 3. Horarios de las materias YA inscritas ───────────────
$horarios_ocupados = [];   // [['dia'=>'LUN','inicio'=>'06:00','fin'=>'08:00'], ...]
if (!empty($ids_inscritas)) {
    $placeholders = implode(',', array_fill(0, count($ids_inscritas), '?'));
    $types        = str_repeat('i', count($ids_inscritas));
    $sql_h = "SELECT dia, hora_inicio, hora_fin FROM horarios
              WHERE materia_id IN ($placeholders)";
    $stmt = $conn->prepare($sql_h);
    $stmt->bind_param($types, ...$ids_inscritas);
    $stmt->execute();
    $res_h = $stmt->get_result();
    while ($h = $res_h->fetch_assoc()) {
        $horarios_ocupados[] = $h;
    }
    $stmt->close();
}

// ── 4. Calcular créditos ya inscritos ──────────────────────
$creditos_inscritos = 0;
if (!empty($ids_inscritas)) {
    $placeholders = implode(',', array_fill(0, count($ids_inscritas), '?'));
    $types        = str_repeat('i', count($ids_inscritas));
    $sql_cr = "SELECT SUM(creditos) AS total FROM materias
               WHERE id IN ($placeholders)";
    $stmt = $conn->prepare($sql_cr);
    $stmt->bind_param($types, ...$ids_inscritas);
    $stmt->execute();
    $res_cr = $stmt->get_result()->fetch_assoc();
    $creditos_inscritos = (int)($res_cr['total'] ?? 0);
    $stmt->close();
}

// ── 5. Obtener todas las materias con sus horarios ─────────
$sql_mat = "SELECT m.id, m.codigo, m.nombre, m.creditos, m.semestre_plan,
                   m.cupos_total, m.cupos_usados,
                   (m.cupos_total - m.cupos_usados) AS cupos_restantes,
                   m.docente, m.salon
            FROM materias m
            ORDER BY m.semestre_plan, m.nombre";
$res_mat = $conn->query($sql_mat);

$materias = [];
while ($m = $res_mat->fetch_assoc()) {
    $mat_id = (int)$m['id'];

    // Horarios de ESTA materia
    $sql_hm = "SELECT dia, hora_inicio, hora_fin FROM horarios WHERE materia_id = ?";
    $stmt = $conn->prepare($sql_hm);
    $stmt->bind_param('i', $mat_id);
    $stmt->execute();
    $res_hm    = $stmt->get_result();
    $horarios_materia = [];
    while ($hm = $res_hm->fetch_assoc()) {
        $horarios_materia[] = [
            'dia'        => $hm['dia'],
            'hora_inicio'=> substr($hm['hora_inicio'], 0, 5),
            'hora_fin'   => substr($hm['hora_fin'],    0, 5)
        ];
    }
    $stmt->close();

    // ── Detectar cruce de horario ──────────────────────────
    $tiene_cruce = false;
    if (!in_array($mat_id, $ids_inscritas)) {   // No revisar las ya inscritas
        foreach ($horarios_materia as $hm) {
            foreach ($horarios_ocupados as $ho) {
                if ($hm['dia'] === $ho['dia']) {
                    // Cruce si los rangos se solapan
                    if ($hm['hora_inicio'] < substr($ho['hora_fin'],0,5) &&
                        $hm['hora_fin']    > substr($ho['hora_inicio'],0,5)) {
                        $tiene_cruce = true;
                        break 2;
                    }
                }
            }
        }
    }

    // ── Estado UX para el botón de la interfaz ─────────────
    $cupos_restantes = (int)$m['cupos_restantes'];
    $ya_inscrita     = in_array($mat_id, $ids_inscritas);

    if ($ya_inscrita) {
        $estado_ux = 'inscrita';         // Botón: "Cancelar inscripción"
    } elseif ($cupos_restantes === 0) {
        $estado_ux = 'sin_cupos';        // Botón: deshabilitado "Sin cupos"
    } elseif ($tiene_cruce) {
        $estado_ux = 'cruce_horario';    // Botón: deshabilitado "Cruce de horario"
    } elseif (($creditos_inscritos + (int)$m['creditos']) > $estudiante['creditos_max']) {
        $estado_ux = 'limite_creditos';  // Botón: deshabilitado "Límite de créditos"
    } else {
        $estado_ux = 'disponible';       // Botón: "Inscribir"
    }

    $materias[] = [
        'id'              => $mat_id,
        'codigo'          => $m['codigo'],
        'nombre'          => $m['nombre'],
        'creditos'        => (int)$m['creditos'],
        'semestre_plan'   => (int)$m['semestre_plan'],
        'docente'         => $m['docente'],
        'salon'           => $m['salon'],
        'horarios'        => $horarios_materia,
        // ── Datos críticos para la UX ──────────────────
        'cupos_total'     => (int)$m['cupos_total'],
        'cupos_usados'    => (int)$m['cupos_usados'],
        'cupos_restantes' => $cupos_restantes,        // Mostrar visualmente
        'sin_cupos'       => $cupos_restantes === 0,  // Bandera para deshabilitar botón
        'ya_inscrita'     => $ya_inscrita,
        'tiene_cruce'     => $tiene_cruce,
        'estado_ux'       => $estado_ux              // Estado final para la UI
    ];
}

$conn->close();

// ── 6. Respuesta JSON ──────────────────────────────────────
echo json_encode([
    'success'    => true,
    'estudiante' => [
        'id'                => (int)$estudiante['id'],
        'codigo'            => $estudiante['codigo'],
        'nombre'            => $estudiante['nombre'],
        'programa'          => $estudiante['programa'],
        'semestre'          => (int)$estudiante['semestre'],
        'promedio'          => (float)$estudiante['promedio'],
        'creditos_max'      => (int)$estudiante['creditos_max'],
        'creditos_inscritos'=> $creditos_inscritos,
        'creditos_disponibles' => $estudiante['creditos_max'] - $creditos_inscritos
    ],
    'materias'   => $materias,
    'total_materias' => count($materias)
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

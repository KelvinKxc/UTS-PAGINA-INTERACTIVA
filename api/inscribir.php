<?php
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
if ($_SERVER['REQUEST_METHOD']==='OPTIONS') { http_response_code(200); exit; }
if ($_SERVER['REQUEST_METHOD']!=='POST') { echo json_encode(['success'=>false,'error'=>'Método no permitido']); exit; }

require_once dirname(__DIR__) . '/config/database.php';

$body        = json_decode(file_get_contents('php://input'), true);
$estudiante_id = (int)($body['estudiante_id'] ?? 0);
$materia_id    = (int)($body['materia_id']    ?? 0);

if (!$estudiante_id || !$materia_id) {
    echo json_encode(['success'=>false,'error'=>'Faltan campos requeridos.']); exit;
}

$conn = conectar();

// Datos del estudiante
$s=$conn->prepare("SELECT creditos_max FROM estudiantes WHERE id=?");
$s->bind_param('i',$estudiante_id); $s->execute();
$est=$s->get_result()->fetch_assoc(); $s->close();
if (!$est) { echo json_encode(['success'=>false,'error'=>'Estudiante no encontrado.']); $conn->close(); exit; }

// Datos de la materia
$s=$conn->prepare("SELECT creditos,cupos_restantes FROM materias WHERE id=?");
$s->bind_param('i',$materia_id); $s->execute();
$mat=$s->get_result()->fetch_assoc(); $s->close();
if (!$mat) { echo json_encode(['success'=>false,'error'=>'Materia no encontrada.']); $conn->close(); exit; }

// ¿Ya inscrita?
$s=$conn->prepare("SELECT id FROM inscripciones WHERE estudiante_id=? AND materia_id=? AND estado='inscrita'");
$s->bind_param('ii',$estudiante_id,$materia_id); $s->execute();
if ($s->get_result()->num_rows>0) { $s->close(); echo json_encode(['success'=>false,'error'=>'Ya estás inscrito en esta materia.']); $conn->close(); exit; }
$s->close();

// Cupos
if ((int)$mat['cupos_restantes']<=0) { echo json_encode(['success'=>false,'error'=>'Sin cupos disponibles.']); $conn->close(); exit; }

// Límite créditos
$s=$conn->prepare("SELECT COALESCE(SUM(m.creditos),0) AS total FROM inscripciones i JOIN materias m ON i.materia_id=m.id WHERE i.estudiante_id=? AND i.estado='inscrita'");
$s->bind_param('i',$estudiante_id); $s->execute();
$cr_inscritos=(int)$s->get_result()->fetch_assoc()['total']; $s->close();
if ($cr_inscritos+(int)$mat['creditos']>(int)$est['creditos_max']) {
    echo json_encode(['success'=>false,'error'=>'Superarías el límite de créditos.']); $conn->close(); exit;
}

// Cruce de horario
$s=$conn->prepare("SELECT h.dia,h.hora_inicio,h.hora_fin FROM horarios h JOIN inscripciones i ON h.materia_id=i.materia_id WHERE i.estudiante_id=? AND i.estado='inscrita'");
$s->bind_param('i',$estudiante_id); $s->execute();
$rh=$s->get_result(); $horarios_ins=[]; while ($h=$rh->fetch_assoc()) $horarios_ins[]=$h; $s->close();

$sn=$conn->prepare("SELECT dia,hora_inicio,hora_fin FROM horarios WHERE materia_id=?");
$sn->bind_param('i',$materia_id); $sn->execute();
$rn=$sn->get_result(); $horarios_nueva=[]; while ($h=$rn->fetch_assoc()) $horarios_nueva[]=$h; $sn->close();

foreach ($horarios_nueva as $hn) {
    foreach ($horarios_ins as $hi) {
        if ($hn['dia']===$hi['dia'] && $hn['hora_inicio'] < $hi['hora_fin'] && $hn['hora_fin'] > $hi['hora_inicio']) {
            echo json_encode(['success'=>false,'error'=>'Cruce de horario con otra materia.']); $conn->close(); exit;
        }
    }
}

// Insertar inscripción (o reactivar si fue cancelada antes)
$conn->begin_transaction();
try {
    $ins=$conn->prepare("INSERT INTO inscripciones (estudiante_id,materia_id,estado,fecha_inscripcion) VALUES (?,?,'inscrita',NOW()) ON DUPLICATE KEY UPDATE estado='inscrita', fecha_inscripcion=NOW()");
    $ins->bind_param('ii',$estudiante_id,$materia_id); $ins->execute(); $ins->close();

    $upd=$conn->prepare("UPDATE materias SET cupos_restantes=cupos_restantes-1 WHERE id=? AND cupos_restantes>0");
    $upd->bind_param('i',$materia_id); $upd->execute();
    if ($upd->affected_rows===0) throw new Exception('Sin cupos al momento de inscribir.');
    $upd->close();

    $conn->commit();
    echo json_encode(['success'=>true,'mensaje'=>'¡Materia inscrita correctamente!'], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success'=>false,'error'=>$e->getMessage()]);
}
$conn->close();

<?php
// Si se llama como API (?api=1), devuelve JSON puro
$es_api = isset($_GET['api']) && $_GET['api'] === '1';

if ($es_api) {
    header('Content-Type: application/json; charset=UTF-8');
    header('Access-Control-Allow-Origin: *');

    require_once __DIR__ . '/database.php';

    $estudiante_id = isset($_GET['estudiante_id']) ? (int)$_GET['estudiante_id'] : 0;
    if ($estudiante_id <= 0) {
        echo json_encode(['success'=>false,'error'=>'estudiante_id requerido']);
        exit;
    }

    $conn = conectar();

    $stmt = $conn->prepare("SELECT id,codigo,nombre,programa,semestre,promedio,creditos_max FROM estudiantes WHERE id=?");
    $stmt->bind_param('i', $estudiante_id);
    $stmt->execute();
    $est = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$est) { echo json_encode(['success'=>false,'error'=>'Estudiante no encontrado']); $conn->close(); exit; }

    $stmt = $conn->prepare("SELECT m.id,m.codigo,m.nombre,m.creditos,m.docente,m.salon,ins.fecha_inscripcion
                            FROM inscripciones ins JOIN materias m ON ins.materia_id=m.id
                            WHERE ins.estudiante_id=? AND ins.estado='inscrita' ORDER BY m.nombre");
    $stmt->bind_param('i', $estudiante_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();

    $mats = []; $total_cr = 0;
    while ($row = $res->fetch_assoc()) {
        $sh = $conn->prepare("SELECT dia,hora_inicio,hora_fin FROM horarios WHERE materia_id=?");
        $sh->bind_param('i',$row['id']); $sh->execute();
        $rh = $sh->get_result(); $hs=[];
        while ($h=$rh->fetch_assoc()) $hs[]=['dia'=>$h['dia'],'hora_inicio'=>substr($h['hora_inicio'],0,5),'hora_fin'=>substr($h['hora_fin'],0,5)];
        $sh->close();
        $total_cr += (int)$row['creditos'];
        $mats[] = ['id'=>(int)$row['id'],'codigo'=>$row['codigo'],'nombre'=>$row['nombre'],
                   'creditos'=>(int)$row['creditos'],'docente'=>$row['docente'],'salon'=>$row['salon'],
                   'horarios'=>$hs,'fecha_inscripcion'=>$row['fecha_inscripcion']];
    }
    $conn->close();

    echo json_encode([
        'success'=>true,
        'estudiante'=>['id'=>(int)$est['id'],'codigo'=>$est['codigo'],'nombre'=>$est['nombre'],
                       'programa'=>$est['programa'],'semestre'=>(int)$est['semestre'],'promedio'=>(float)$est['promedio'],
                       'creditos_max'=>(int)$est['creditos_max']],
        'resumen_creditos'=>['inscritos'=>$total_cr,'disponibles'=>(int)$est['creditos_max']-$total_cr,
                             'maximo'=>(int)$est['creditos_max'],
                             'porcentaje'=>$est['creditos_max']>0?round(($total_cr/(int)$est['creditos_max'])*100,1):0],
        'materias_inscritas'=>$mats,
        'total_materias'=>count($mats)
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="pagina" content="resumen">
  <title>UTS — Resumen de Inscripción</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
  <style>
    :root {
      --verde:#4a7c25; --verde-dark:#3a5a1e; --verde-light:#a8d060;
      --verde-bg:#f0f7e8; --gris-bg:#f4f6f8; --gris-borde:#e0e4ea;
      --texto:#1a2530; --texto-sub:#5a6a78; --sombra:0 2px 12px rgba(0,0,0,.07);
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'DM Sans', sans-serif; background: var(--gris-bg); color: var(--texto); }

    .page { max-width: 900px; margin: 28px auto; padding: 0 20px 40px; }

    /* Resumen banner */
    .resumen-banner {
      background: linear-gradient(135deg, var(--verde) 0%, var(--verde-dark) 100%);
      border-radius: 18px;
      padding: 28px 32px;
      color: white;
      margin-bottom: 24px;
      box-shadow: 0 6px 24px rgba(74,124,37,.3);
      min-height: 80px;
    }
    .res-titulo { font-size: 20px; font-weight: 700; margin-bottom: 20px; }

    .res-stats { display: flex; gap: 32px; flex-wrap: wrap; margin-bottom: 20px; }
    .res-stat-num   { font-size: 38px; font-weight: 700; line-height: 1; }
    .res-stat-label { font-size: 11px; opacity: .7; margin-top: 4px; text-transform: uppercase; letter-spacing: .5px; }

    .res-barra-bg   { background: rgba(255,255,255,.25); border-radius: 99px; height: 12px; }
    .res-barra-fill {
      background: var(--verde-light); height: 12px; border-radius: 99px;
      transition: width .7s cubic-bezier(.4,0,.2,1);
      box-shadow: 0 0 10px rgba(168,208,96,.5);
    }
    .res-barra-labels {
      display: flex; justify-content: space-between;
      font-size: 11px; opacity: .75; margin-top: 6px;
    }

    /* Tabla materias */
    .seccion-titulo {
      font-size: 13px; font-weight: 700; color: var(--texto-sub);
      text-transform: uppercase; letter-spacing: .5px; margin-bottom: 14px;
    }
    .tabla-wrap {
      background: white; border-radius: 16px;
      box-shadow: var(--sombra); overflow: hidden;
    }
    table { width: 100%; border-collapse: collapse; }
    thead th {
      background: var(--verde-bg);
      padding: 13px 16px;
      text-align: left;
      font-size: 11px; font-weight: 700;
      color: var(--verde-dark);
      text-transform: uppercase; letter-spacing: .4px;
    }
    tbody tr { border-bottom: 1px solid var(--gris-borde); transition: background .1s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: var(--verde-bg); }
    tbody td { padding: 14px 16px; font-size: 13px; vertical-align: middle; }

    .td-nombre { font-weight: 600; }
    .td-codigo { font-family: 'DM Mono',monospace; font-size: 11px; color: var(--texto-sub); }
    .td-cred {
      background: var(--verde-bg); color: var(--verde);
      font-weight: 700; font-size: 12px;
      padding: 3px 10px; border-radius: 99px;
      display: inline-block;
    }
    .td-horario {
      font-size: 11px; color: var(--texto-sub);
      font-family: 'DM Mono', monospace;
    }
    .inscrita-tag {
      background: #e3f2fd; color: #1565c0;
      font-size: 10px; font-weight: 700;
      padding: 2px 8px; border-radius: 99px;
    }

    /* Empty */
    .empty-state {
      text-align: center; padding: 60px 20px;
      color: var(--texto-sub);
    }
    .empty-state .icon { font-size: 40px; margin-bottom: 12px; }
    .empty-state a { color: var(--verde); font-weight: 600; }

    /* FIX: error-msg visible */
    .error-api {
      padding: 20px 24px;
      font-size: 13px; color: #bf360c;
      background: #fff3e0;
      line-height: 1.6;
    }
    .error-api strong { display: block; margin-bottom: 4px; }

    .spinner { width:28px; height:28px; border:3px solid #e0e4ea; border-top-color:var(--verde); border-radius:50%; animation:spin .7s linear infinite; margin:40px auto; }
    @keyframes spin { to { transform:rotate(360deg); } }

    @media(max-width:600px) {
      table { font-size: 12px; }
      thead th, tbody td { padding: 10px 10px; }
      .res-stats { gap: 18px; }
    }
  </style>
</head>
<body>
<?php include 'nav.php'; ?>

<div class="page">

  <!-- Banner resumen -->
  <div class="resumen-banner" id="banner">
    <div class="spinner" style="margin:0 auto"></div>
  </div>

  <p class="seccion-titulo">Materias inscritas</p>
  <div class="tabla-wrap">
    <div id="tabla-contenido"><div class="spinner"></div></div>
  </div>

</div>

<script>
  const estId = sessionStorage.getItem('estudiante_id');
  if (!estId) window.location.replace('index.php');

  fetch('resumen.php?estudiante_id=' + estId + '&api=1')
    .then(r => {
      if (!r.ok) throw new Error('HTTP ' + r.status);
      return r.json();
    })
    .then(data => {
      if (!data.success) throw new Error(data.error || 'Error desconocido');

      const cr  = data.resumen_creditos;
      const pct = cr.porcentaje;

      // Banner
      document.getElementById('banner').innerHTML = `
        <div class="res-titulo">📊 Resumen de Inscripción — ${data.estudiante.nombre}</div>
        <div class="res-stats">
          <div>
            <div class="res-stat-num">${data.total_materias}</div>
            <div class="res-stat-label">Materias inscritas</div>
          </div>
          <div>
            <div class="res-stat-num">${cr.inscritos}</div>
            <div class="res-stat-label">Créditos inscritos</div>
          </div>
          <div>
            <div class="res-stat-num">${cr.disponibles}</div>
            <div class="res-stat-label">Créditos disponibles</div>
          </div>
          <div>
            <div class="res-stat-num">${cr.maximo}</div>
            <div class="res-stat-label">Máximo permitido</div>
          </div>
        </div>
        <div class="res-barra-bg">
          <div class="res-barra-fill" style="width:${pct}%"></div>
        </div>
        <div class="res-barra-labels">
          <span>${cr.inscritos} créditos inscritos</span>
          <span>${pct}% del máximo</span>
        </div>
      `;

      // Tabla
      if (!data.materias_inscritas.length) {
        document.getElementById('tabla-contenido').innerHTML = `
          <div class="empty-state">
            <div class="icon">📭</div>
            <div>No tienes materias inscritas aún.</div>
            <div style="margin-top:10px"><a href="inscripcion.php">Ir a inscripción de materias →</a></div>
          </div>`;
        return;
      }

      const filas = data.materias_inscritas.map(m => {
        const hor = m.horarios.map(h => h.dia + ' ' + h.hora_inicio + '–' + h.hora_fin).join('<br>');
        const fecha = m.fecha_inscripcion
          ? new Date(m.fecha_inscripcion).toLocaleDateString('es-CO', {day:'2-digit',month:'short',year:'numeric'})
          : '—';
        return `<tr>
          <td>
            <div class="td-nombre">${m.nombre}</div>
            <div class="td-codigo">${m.codigo}</div>
          </td>
          <td><span class="td-cred">${m.creditos} cr.</span></td>
          <td>${m.docente}</td>
          <td class="td-horario">${hor || '—'}</td>
          <td>${m.salon}</td>
          <td>${fecha}</td>
          <td><span class="inscrita-tag">Inscrita</span></td>
        </tr>`;
      }).join('');

      document.getElementById('tabla-contenido').innerHTML = `
        <table>
          <thead>
            <tr>
              <th>Materia</th>
              <th>Créditos</th>
              <th>Docente</th>
              <th>Horario</th>
              <th>Salón</th>
              <th>Fecha inscripción</th>
              <th>Estado</th>
            </tr>
          </thead>
          <tbody>${filas}</tbody>
        </table>`;
    })
    .catch(err => {
      // FIX: ambos elementos muestran el error, no quedan en spinner
      const msg = err.message || 'No se pudo conectar al servidor.';
      document.getElementById('banner').innerHTML =
        `<strong style="color:white;display:block;margin-bottom:6px">⚠️ Error al cargar el resumen</strong>
         <span style="opacity:.85;font-size:13px">${msg}. Verifica que MySQL esté activo.</span>`;
      document.getElementById('tabla-contenido').innerHTML =
        `<div class="error-api">
           <strong>⚠️ No se pudieron cargar las materias</strong>
           ${msg}. Verifica que Laragon/XAMPP esté activo y la base de datos <code>uts_matriculas</code> exista.
           <br><br><a href="home.php" style="color:#bf360c;font-weight:600">← Volver al inicio</a>
         </div>`;
    });
</script>
</body>
</html>

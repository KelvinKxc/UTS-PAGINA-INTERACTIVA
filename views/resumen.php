<?php
require_once dirname(__DIR__) . '/config/config.php';
$titulo        = 'Resumen de Inscripción';
$pagina_actual = 'resumen';
?>
<!DOCTYPE html>
<html lang="es">
<head><?php require_once __DIR__ . '/partials/head.php'; ?></head>
<body>
<?php require_once __DIR__ . '/partials/nav.php'; ?>

<div class="page" style="max-width:900px">
  <div class="resumen-banner" id="banner"><div class="spinner" style="margin:0 auto"></div></div>
  <p class="seccion-titulo">Materias inscritas</p>
  <div class="tabla-wrap"><div id="tabla-contenido"><div class="spinner"></div></div></div>
</div>

<style>
.resumen-banner {
  background:linear-gradient(135deg, var(--verde) 0%, var(--verde-dark) 100%);
  border-radius:18px; padding:28px 32px; color:white;
  margin-bottom:24px; box-shadow:0 6px 24px rgba(74,124,37,.3); min-height:80px;
}
.res-titulo { font-size:20px; font-weight:700; margin-bottom:20px; }
.res-stats  { display:flex; gap:32px; flex-wrap:wrap; margin-bottom:20px; }
.res-stat-num   { font-size:38px; font-weight:700; line-height:1; }
.res-stat-label { font-size:11px; opacity:.7; margin-top:4px; text-transform:uppercase; letter-spacing:.5px; }
.res-barra-bg   { background:rgba(255,255,255,.25); border-radius:99px; height:12px; }
.res-barra-fill { background:var(--verde-light); height:12px; border-radius:99px; transition:width .7s; box-shadow:0 0 10px rgba(168,208,96,.5); }
.res-barra-labels{ display:flex; justify-content:space-between; font-size:11px; opacity:.75; margin-top:6px; }
.tabla-wrap  { background:white; border-radius:16px; box-shadow:var(--sombra); overflow:hidden; }
table        { width:100%; border-collapse:collapse; }
thead th     { background:var(--verde-bg); padding:13px 16px; text-align:left; font-size:11px; font-weight:700; color:var(--verde-dark); text-transform:uppercase; letter-spacing:.4px; }
tbody tr     { border-bottom:1px solid var(--gris-borde); transition:background .1s; }
tbody tr:last-child { border-bottom:none; }
tbody tr:hover { background:var(--verde-bg); }
tbody td     { padding:14px 16px; font-size:13px; vertical-align:middle; }
.td-nombre   { font-weight:600; }
.td-codigo   { font-family:'DM Mono',monospace; font-size:11px; color:var(--texto-sub); }
.td-cred     { background:var(--verde-bg); color:var(--verde); font-weight:700; font-size:12px; padding:3px 10px; border-radius:99px; display:inline-block; }
.td-horario  { font-size:11px; color:var(--texto-sub); font-family:'DM Mono',monospace; }
.inscrita-tag{ background:#e3f2fd; color:var(--azul); font-size:10px; font-weight:700; padding:2px 8px; border-radius:99px; }
.empty-state { text-align:center; padding:60px 20px; color:var(--texto-sub); }
.empty-state .icon { font-size:40px; margin-bottom:12px; }
.empty-state a { color:var(--verde); font-weight:600; }
@media(max-width:600px) { table { font-size:12px; } thead th, tbody td { padding:10px; } }
</style>

<script>
const API = '<?= BASE_URL ?>/api';

fetch(API + '/resumen.php?estudiante_id=' + Auth.id)
  .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
  .then(data => {
    if (!data.success) throw new Error(data.error || 'Error desconocido');
    const cr = data.resumen_creditos, pct = cr.porcentaje;
    document.getElementById('banner').innerHTML = `
      <div class="res-titulo">📊 Resumen de Inscripción — ${data.estudiante.nombre}</div>
      <div class="res-stats">
        <div><div class="res-stat-num">${data.total_materias}</div><div class="res-stat-label">Materias inscritas</div></div>
        <div><div class="res-stat-num">${cr.inscritos}</div><div class="res-stat-label">Créditos inscritos</div></div>
        <div><div class="res-stat-num">${cr.disponibles}</div><div class="res-stat-label">Créditos disponibles</div></div>
        <div><div class="res-stat-num">${cr.maximo}</div><div class="res-stat-label">Máximo permitido</div></div>
      </div>
      <div class="res-barra-bg"><div class="res-barra-fill" style="width:${pct}%"></div></div>
      <div class="res-barra-labels"><span>${cr.inscritos} créditos inscritos</span><span>${pct}% del máximo</span></div>`;

    if (!data.materias_inscritas.length) {
      document.getElementById('tabla-contenido').innerHTML = `<div class="empty-state"><div class="icon">📭</div><div>No tienes materias inscritas aún.</div><div style="margin-top:10px"><a href="inscripcion.php">Ir a inscripción →</a></div></div>`; return;
    }
    const filas = data.materias_inscritas.map(m => {
      const hor   = m.horarios.map(h => h.dia+' '+h.hora_inicio+'–'+h.hora_fin).join('<br>');
      const fecha = m.fecha_inscripcion ? new Date(m.fecha_inscripcion).toLocaleDateString('es-CO',{day:'2-digit',month:'short',year:'numeric'}) : '—';
      return `<tr><td><div class="td-nombre">${m.nombre}</div><div class="td-codigo">${m.codigo}</div></td><td><span class="td-cred">${m.creditos} cr.</span></td><td>${m.docente}</td><td class="td-horario">${hor||'—'}</td><td>${m.salon}</td><td>${fecha}</td><td><span class="inscrita-tag">Inscrita</span></td></tr>`;
    }).join('');
    document.getElementById('tabla-contenido').innerHTML = `<table><thead><tr><th>Materia</th><th>Créditos</th><th>Docente</th><th>Horario</th><th>Salón</th><th>Fecha inscripción</th><th>Estado</th></tr></thead><tbody>${filas}</tbody></table>`;
  })
  .catch(err => {
    const msg = err.message || 'No se pudo conectar.';
    document.getElementById('banner').innerHTML = `<strong style="color:white;display:block;margin-bottom:6px">⚠️ Error al cargar el resumen</strong><span style="opacity:.85;font-size:13px">${msg}</span>`;
    document.getElementById('tabla-contenido').innerHTML = `<div class="error-api"><strong>⚠️ No se pudieron cargar las materias</strong>${msg}<br><br><a href="home.php" style="color:#bf360c;font-weight:600">← Volver al inicio</a></div>`;
  });
</script>
</body>
</html>

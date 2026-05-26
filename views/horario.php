<?php
require_once dirname(__DIR__) . '/config/config.php';
$pagina_actual = 'horario';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UTS — Mi Horario</title>
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/styles.css">
<script src="<?= BASE_URL ?>/public/js/auth.js"></script>
<style>
.page-header{background:linear-gradient(135deg,var(--verde) 0%,var(--verde-dark) 100%);padding:24px 28px;color:white;margin-bottom:24px;border-radius:18px;box-shadow:0 6px 24px rgba(74,124,37,.3)}
.page-header h1{font-size:22px;font-weight:700;margin-bottom:4px}
.page-header p{font-size:13px;opacity:.75}

/* Grid horario */
.horario-grid{background:white;border-radius:16px;box-shadow:var(--sombra);overflow:auto}
.horario-table{width:100%;border-collapse:collapse;min-width:600px}
.horario-table th{background:var(--verde-bg);color:var(--verde-dark);font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.4px;padding:12px 10px;text-align:center;border-right:1px solid var(--gris-borde)}
.horario-table th:first-child{text-align:left;padding-left:14px;min-width:70px}
.horario-table td{border:1px solid var(--gris-borde);padding:4px;vertical-align:top;height:52px;font-size:11px}
.horario-table td:first-child{background:var(--verde-bg);font-size:11px;font-weight:600;color:var(--verde-dark);text-align:center;padding:8px 6px;white-space:nowrap}

/* Bloque de materia en el horario */
.bloque{
  background:var(--verde);
  color:white;
  border-radius:8px;
  padding:5px 8px;
  font-size:10px;
  line-height:1.3;
  height:100%;
  display:flex;
  flex-direction:column;
  justify-content:center;
  cursor:default;
  transition:opacity .15s;
}
.bloque:hover{opacity:.85}
.bloque-nombre{font-weight:700;font-size:11px;margin-bottom:1px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.bloque-salon{opacity:.8;font-size:9px}

/* Colores por materia */
.c0{background:#4a7c25}.c1{background:#1565c0}.c2{background:#6a1b9a}
.c3{background:#e65100}.c4{background:#c62828}.c5{background:#00695c}

/* Lista resumen */
.materias-lista{display:flex;flex-direction:column;gap:10px;margin-top:20px}
.materia-row{
  background:white;border-radius:12px;padding:14px 18px;
  box-shadow:var(--sombra);
  display:flex;align-items:center;gap:14px;
}
.materia-color{width:12px;height:40px;border-radius:4px;flex-shrink:0}
.materia-info{flex:1}
.materia-nombre{font-weight:600;font-size:14px}
.materia-meta{font-size:12px;color:var(--texto-sub);margin-top:2px}
.horario-chips{display:flex;flex-wrap:wrap;gap:4px;margin-top:6px}
.horario-chip{background:var(--verde-bg);color:var(--verde-dark);font-size:10px;font-family:'DM Mono',monospace;padding:2px 8px;border-radius:4px;font-weight:500}
.cred-badge{background:var(--verde-bg);color:var(--verde);font-weight:700;font-size:12px;padding:3px 10px;border-radius:99px;white-space:nowrap;margin-left:auto}

/* Btn cancelar */
.btn-cancelar{
  background:#ffebee;color:#c62828;border:1px solid #ffcdd2;
  padding:6px 14px;border-radius:8px;font-size:12px;font-weight:600;
  cursor:pointer;font-family:'DM Sans',sans-serif;transition:all .15s;
  white-space:nowrap;
}
.btn-cancelar:hover{background:#c62828;color:white}
.btn-cancelar:disabled{opacity:.5;cursor:not-allowed}

.empty-state{text-align:center;padding:60px 20px;color:var(--texto-sub)}
.empty-state .icon{font-size:40px;margin-bottom:12px}
.empty-state a{color:var(--verde);font-weight:600}

/* Modal confirmación */
.overlay{position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:900;display:none;align-items:center;justify-content:center}
.overlay.show{display:flex}
.modal{background:white;border-radius:16px;padding:28px;max-width:380px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,.2)}
.modal h3{font-size:16px;font-weight:700;margin-bottom:8px}
.modal p{font-size:13px;color:var(--texto-sub);line-height:1.5;margin-bottom:20px}
.modal-btns{display:flex;gap:10px}
.modal-btns button{flex:1;padding:10px;border-radius:10px;font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;cursor:pointer;border:none;transition:all .15s}
.btn-confirmar{background:#c62828;color:white}.btn-confirmar:hover{background:#b71c1c}
.btn-modal-cancel{background:#f4f6f8;color:var(--texto)}.btn-modal-cancel:hover{background:#e0e4ea}
</style>
</head>
<body>
<?php require_once __DIR__ . '/partials/nav.php'; ?>

<div class="page">
  <div class="page-header">
    <h1>📅 Mi Horario</h1>
    <p id="header-sub">Cargando...</p>
  </div>

  <!-- Tabla de horario -->
  <p class="seccion-titulo">Vista semanal</p>
  <div class="horario-grid" id="horario-grid">
    <div style="text-align:center;padding:40px"><div class="spinner"></div></div>
  </div>

  <!-- Lista con opción cancelar -->
  <p class="seccion-titulo" style="margin-top:24px">Mis materias inscritas</p>
  <div id="materias-lista">
    <div style="text-align:center;padding:30px"><div class="spinner"></div></div>
  </div>
</div>

<!-- Modal confirmación cancelar -->
<div class="overlay" id="overlay">
  <div class="modal">
    <h3>⚠️ Cancelar inscripción</h3>
    <p id="modal-texto">¿Estás seguro de que quieres cancelar la inscripción de esta materia? El cupo quedará disponible para otros estudiantes.</p>
    <div class="modal-btns">
      <button class="btn-modal-cancel" onclick="cerrarModal()">No, mantener</button>
      <button class="btn-confirmar" id="btn-confirmar" onclick="confirmarCancelar()">Sí, cancelar</button>
    </div>
  </div>
</div>

<div class="toast" id="toast"></div>

<script>
const API = '<?= BASE_URL ?>/api';
const DIAS   = ['LUN','MAR','MIE','JUE','VIE','SAB'];
const DIAS_LABEL = {'LUN':'Lunes','MAR':'Martes','MIE':'Miércoles','JUE':'Jueves','VIE':'Viernes','SAB':'Sábado'};
const HORAS  = ['06:00','07:00','08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00'];
const COLORES = ['c0','c1','c2','c3','c4','c5'];
let materiasPendiente = null;

function toast(msg, tipo='ok') {
  const t = document.getElementById('toast');
  t.textContent = msg; t.className = 'toast '+tipo; t.style.display='block';
  clearTimeout(t._t); t._t = setTimeout(()=>t.style.display='none', 4000);
}

function abrirModal(materia) {
  materiasPendiente = materia;
  document.getElementById('modal-texto').textContent =
    `¿Estás seguro de que quieres cancelar la inscripción de "${materia.nombre}"? El cupo quedará disponible para otros estudiantes.`;
  document.getElementById('overlay').classList.add('show');
}
function cerrarModal() {
  document.getElementById('overlay').classList.remove('show');
  materiasPendiente = null;
}

async function confirmarCancelar() {
  if (!materiasPendiente) return;
  const btn = document.getElementById('btn-confirmar');
  btn.disabled = true; btn.textContent = 'Cancelando...';
  try {
    const r = await fetch(API + '/cancelar.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ estudiante_id: parseInt(Auth.id), materia_id: materiasPendiente.id })
    });
    const data = await r.json();
    cerrarModal();
    if (data.success) { toast(data.mensaje, 'ok'); cargar(); }
    else { toast(data.error, 'err'); }
  } catch { toast('Error de red.', 'err'); cerrarModal(); }
  finally { btn.disabled = false; btn.textContent = 'Sí, cancelar'; }
}

// Cerrar modal al click fuera
document.getElementById('overlay').addEventListener('click', e => {
  if (e.target === document.getElementById('overlay')) cerrarModal();
});

function construirTabla(materias) {
  // Construir mapa: dia+hora → materia
  const celdas = {};
  materias.forEach((m, idx) => {
    const color = COLORES[idx % COLORES.length];
    m.horarios.forEach(h => {
      const hi = parseInt(h.hora_inicio.split(':')[0]);
      const hf = parseInt(h.hora_fin.split(':')[0]);
      for (let hora = hi; hora < hf; hora++) {
        const key = `${h.dia}_${hora}:00`;
        celdas[key] = { m, color, inicio: hora === hi, duracion: hf - hi };
      }
    });
  });

  let html = '<table class="horario-table"><thead><tr><th>Hora</th>';
  DIAS.forEach(d => html += `<th>${DIAS_LABEL[d]}</th>`);
  html += '</tr></thead><tbody>';

  HORAS.forEach(hora => {
    html += `<tr><td>${hora}</td>`;
    DIAS.forEach(dia => {
      const key = `${dia}_${hora}`;
      const celda = celdas[key];
      if (celda && celda.inicio) {
        html += `<td rowspan="${celda.duracion}" style="padding:4px;border-right:1px solid var(--gris-borde)">
          <div class="bloque ${celda.color}" title="${celda.m.nombre} · ${celda.m.salon}">
            <div class="bloque-nombre">${celda.m.nombre}</div>
            <div class="bloque-salon">📍 ${celda.m.salon}</div>
          </div></td>`;
      } else if (!celda) {
        html += '<td></td>';
      }
      // celdas ocupadas por rowspan no se emiten
    });
    html += '</tr>';
  });

  html += '</tbody></table>';
  return html;
}

function construirLista(materias) {
  if (!materias.length) {
    return `<div class="empty-state">
      <div class="icon">📭</div>
      <div>No tienes materias inscritas aún.</div>
      <div style="margin-top:10px"><a href="<?= BASE_URL ?>/views/inscripcion.php">Ir a inscripción →</a></div>
    </div>`;
  }
  return '<div class="materias-lista">' + materias.map((m, idx) => {
    const color = COLORES[idx % COLORES.length];
    const bgColor = ['#4a7c25','#1565c0','#6a1b9a','#e65100','#c62828','#00695c'][idx % 6];
    const chips = m.horarios.map(h =>
      `<span class="horario-chip">${h.dia} ${h.hora_inicio}–${h.hora_fin}</span>`
    ).join('');
    return `<div class="materia-row" id="fila-${m.id}">
      <div class="materia-color" style="background:${bgColor}"></div>
      <div class="materia-info">
        <div class="materia-nombre">${m.nombre}</div>
        <div class="materia-meta">👤 ${m.docente} &nbsp;·&nbsp; 📍 ${m.salon}</div>
        <div class="horario-chips">${chips || '<span style="font-size:11px;color:#bbb">Sin horario asignado</span>'}</div>
      </div>
      <span class="cred-badge">${m.creditos} cr.</span>
      <button class="btn-cancelar" onclick='abrirModal(${JSON.stringify({id:m.id, nombre:m.nombre})})'>Cancelar</button>
    </div>`;
  }).join('') + '</div>';
}

async function cargar() {
  try {
    const r = await fetch(API + '/resumen.php?estudiante_id=' + Auth.id);
    const data = await r.json();
    if (!data.success) throw new Error(data.error);

    const mats = data.materias_inscritas;
    document.getElementById('header-sub').textContent =
      `${mats.length} materia${mats.length !== 1 ? 's' : ''} inscrita${mats.length !== 1 ? 's' : ''} · ${data.resumen_creditos.inscritos} créditos`;

    document.getElementById('horario-grid').innerHTML =
      mats.length ? construirTabla(mats)
                  : '<div class="empty-state"><div class="icon">📅</div><div>No hay materias para mostrar en el horario.</div></div>';

    document.getElementById('materias-lista').innerHTML = construirLista(mats);
  } catch(e) {
    document.getElementById('horario-grid').innerHTML =
      `<div class="empty-state"><div class="icon">⚠️</div><div>${e.message || 'No se pudo cargar.'}</div></div>`;
  }
}

cargar();
</script>
</body>
</html>

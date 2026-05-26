<?php
require_once dirname(__DIR__) . '/config/config.php';
$titulo        = 'Resumen de Inscripción';
$pagina_actual = 'resumen';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UTS — Resumen de Inscripción</title>
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/styles.css">
<script src="<?= BASE_URL ?>/public/js/auth.js"></script>
<style>
.resumen-banner{background:linear-gradient(135deg,var(--verde) 0%,var(--verde-dark) 100%);border-radius:18px;padding:28px 32px;color:white;margin-bottom:24px;box-shadow:0 6px 24px rgba(74,124,37,.3);min-height:80px}
.res-titulo{font-size:20px;font-weight:700;margin-bottom:20px}
.res-stats{display:flex;gap:32px;flex-wrap:wrap;margin-bottom:20px}
.res-stat-num{font-size:38px;font-weight:700;line-height:1}.res-stat-label{font-size:11px;opacity:.7;margin-top:4px;text-transform:uppercase;letter-spacing:.5px}
.res-barra-bg{background:rgba(255,255,255,.25);border-radius:99px;height:12px}
.res-barra-fill{background:var(--verde-light);height:12px;border-radius:99px;transition:width .7s;box-shadow:0 0 10px rgba(168,208,96,.5)}
.res-barra-labels{display:flex;justify-content:space-between;font-size:11px;opacity:.75;margin-top:6px}
.tabla-wrap{background:white;border-radius:16px;box-shadow:var(--sombra);overflow:hidden}
table{width:100%;border-collapse:collapse}
thead th{background:var(--verde-bg);padding:13px 16px;text-align:left;font-size:11px;font-weight:700;color:var(--verde-dark);text-transform:uppercase;letter-spacing:.4px}
tbody tr{border-bottom:1px solid var(--gris-borde);transition:background .1s}
tbody tr:last-child{border-bottom:none}
tbody tr:hover{background:var(--verde-bg)}
tbody td{padding:14px 16px;font-size:13px;vertical-align:middle}
.td-nombre{font-weight:600}.td-codigo{font-family:'DM Mono',monospace;font-size:11px;color:var(--texto-sub)}
.td-cred{background:var(--verde-bg);color:var(--verde);font-weight:700;font-size:12px;padding:3px 10px;border-radius:99px;display:inline-block}
.td-horario{font-size:11px;color:var(--texto-sub);font-family:'DM Mono',monospace}
.inscrita-tag{background:#e3f2fd;color:var(--azul);font-size:10px;font-weight:700;padding:2px 8px;border-radius:99px}
.empty-state{text-align:center;padding:60px 20px;color:var(--texto-sub)}
.empty-state .icon{font-size:40px;margin-bottom:12px}
.empty-state a{color:var(--verde);font-weight:600}
.btn-cancelar{background:#ffebee;color:#c62828;border:1px solid #ffcdd2;padding:5px 12px;border-radius:8px;font-size:11px;font-weight:600;cursor:pointer;font-family:"DM Sans",sans-serif;transition:all .15s;white-space:nowrap}.btn-cancelar:hover{background:#c62828;color:white}.overlay{position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:900;display:none;align-items:center;justify-content:center}.overlay.show{display:flex}.modal{background:white;border-radius:16px;padding:28px;max-width:380px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,.2)}.modal h3{font-size:16px;font-weight:700;margin-bottom:8px}.modal p{font-size:13px;color:var(--texto-sub);line-height:1.5;margin-bottom:20px}.modal-btns{display:flex;gap:10px}.modal-btns button{flex:1;padding:10px;border-radius:10px;font-family:"DM Sans",sans-serif;font-size:13px;font-weight:600;cursor:pointer;border:none}.btn-confirmar{background:#c62828;color:white}.btn-modal-cancel{background:#f4f6f8;color:var(--texto)}
@media(max-width:600px){table{font-size:12px}thead th,tbody td{padding:10px}}
</style>
</head>
<body>
<?php require_once __DIR__ . '/partials/nav.php'; ?>

<div class="page" style="max-width:900px">
  <div class="resumen-banner" id="banner"><div class="spinner" style="margin:0 auto"></div></div>
  <p class="seccion-titulo">Materias inscritas</p>
  <div class="tabla-wrap"><div id="tabla-contenido"><div class="spinner"></div></div></div>
</div>

<script>
const API = '<?= BASE_URL ?>/api';
fetch(API+'/resumen.php?estudiante_id='+Auth.id)
  .then(r=>r.json())
  .then(data=>{
    if(!data.success) throw new Error(data.error||'Error');
    const cr=data.resumen_creditos,pct=cr.porcentaje;
    document.getElementById('banner').innerHTML=`
      <div class="res-titulo">📊 Resumen de Inscripción — ${data.estudiante.nombre}</div>
      <div class="res-stats">
        <div><div class="res-stat-num">${data.total_materias}</div><div class="res-stat-label">Materias inscritas</div></div>
        <div><div class="res-stat-num">${cr.inscritos}</div><div class="res-stat-label">Créditos inscritos</div></div>
        <div><div class="res-stat-num">${cr.disponibles}</div><div class="res-stat-label">Créditos disponibles</div></div>
        <div><div class="res-stat-num">${cr.maximo}</div><div class="res-stat-label">Máximo permitido</div></div>
      </div>
      <div class="res-barra-bg"><div class="res-barra-fill" style="width:${pct}%"></div></div>
      <div class="res-barra-labels"><span>${cr.inscritos} créditos inscritos</span><span>${pct}% del máximo</span></div>`;
    if(!data.materias_inscritas.length){
      document.getElementById('tabla-contenido').innerHTML=`<div class="empty-state"><div class="icon">📭</div><div>No tienes materias inscritas aún.</div><div style="margin-top:10px"><a href="<?= BASE_URL ?>/views/inscripcion.php">Ir a inscripción →</a></div></div>`;return;
    }
    const filas=data.materias_inscritas.map(m=>{
      const hor=m.horarios.map(h=>h.dia+' '+h.hora_inicio+'–'+h.hora_fin).join('<br>');
      const fecha=m.fecha_inscripcion?new Date(m.fecha_inscripcion).toLocaleDateString('es-CO',{day:'2-digit',month:'short',year:'numeric'}):'—';
      return `<tr><td><div class="td-nombre">${m.nombre}</div><div class="td-codigo">${m.codigo}</div></td><td><span class="td-cred">${m.creditos} cr.</span></td><td>${m.docente}</td><td class="td-horario">${hor||'—'}</td><td>${m.salon}</td><td>${fecha}</td><td><span class="inscrita-tag">Inscrita</span></td><td><button class="btn-cancelar" onclick="abrirModal(${m.id},'${m.nombre.replace(/'/g,'\'')}')">Cancelar</button></td></tr>`;
    }).join('');
    document.getElementById('tabla-contenido').innerHTML=`<table><thead><tr><th>Materia</th><th>Créditos</th><th>Docente</th><th>Horario</th><th>Salón</th><th>Fecha</th><th>Estado</th><th></th></tr></thead><tbody>${filas}</tbody></table>`;
  })
  .catch(err=>{
    document.getElementById('banner').innerHTML=`<strong style="color:white">⚠️ Error al cargar el resumen:</strong> <span style="opacity:.85;font-size:13px">${err.message}</span>`;
    document.getElementById('tabla-contenido').innerHTML=`<div class="error-api"><strong>⚠️ No se pudieron cargar las materias</strong>${err.message}<br><br><a href="<?= BASE_URL ?>/views/home.php" style="color:#bf360c;font-weight:600">← Volver al inicio</a></div>`;
  });
</script>

<!-- Modal confirmación cancelar -->
<div class="overlay" id="overlay">
  <div class="modal">
    <h3>⚠️ Cancelar inscripción</h3>
    <p id="modal-texto">¿Seguro que quieres cancelar esta materia?</p>
    <div class="modal-btns">
      <button class="btn-modal-cancel" onclick="cerrarModal()">No, mantener</button>
      <button class="btn-confirmar" id="btn-confirmar" onclick="confirmarCancelar()">Sí, cancelar</button>
    </div>
  </div>
</div>
<div class="toast" id="toast"></div>
<script>
const API_RES = '<?= BASE_URL ?>/api';
let _cancelId = null, _cancelNombre = '';
function toast(msg,tipo='ok'){const t=document.getElementById('toast');t.textContent=msg;t.className='toast '+tipo;t.style.display='block';clearTimeout(t._t);t._t=setTimeout(()=>t.style.display='none',4000)}
function abrirModal(id, nombre){_cancelId=id;_cancelNombre=nombre;document.getElementById('modal-texto').textContent=`¿Seguro que quieres cancelar la inscripción de "${nombre}"?`;document.getElementById('overlay').classList.add('show')}
function cerrarModal(){document.getElementById('overlay').classList.remove('show');_cancelId=null}
async function confirmarCancelar(){
  if(!_cancelId)return;
  const btn=document.getElementById('btn-confirmar');
  btn.disabled=true;btn.textContent='Cancelando...';
  try{
    const r=await fetch(API_RES+'/cancelar.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({estudiante_id:parseInt(Auth.id),materia_id:_cancelId})});
    const data=await r.json();
    cerrarModal();
    if(data.success){toast(data.mensaje,'ok');setTimeout(()=>location.reload(),1200)}
    else{toast(data.error,'err')}
  }catch{toast('Error de red.','err');cerrarModal()}
  finally{btn.disabled=false;btn.textContent='Sí, cancelar'}
}
document.getElementById('overlay').addEventListener('click',e=>{if(e.target===document.getElementById('overlay'))cerrarModal()});
</script>
</body>
</html>

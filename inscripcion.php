<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="pagina" content="inscripcion">
  <title>UTS — Inscripción de Materias</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
  <style>
    :root {
      --verde:#4a7c25; --verde-dark:#3a5a1e; --verde-light:#a8d060;
      --verde-bg:#f0f7e8; --rojo:#c62828; --naranja:#e65100;
      --azul:#1565c0; --morado:#6a1b9a;
      --gris-bg:#f4f6f8; --gris-borde:#e0e4ea;
      --texto:#1a2530; --texto-sub:#5a6a78;
      --sombra: 0 2px 12px rgba(0,0,0,.07);
      --sombra-h: 0 8px 24px rgba(74,124,37,.18);
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'DM Sans', sans-serif; background: var(--gris-bg); color: var(--texto); }

    /* Panel créditos */
    .creditos-panel {
      background: linear-gradient(135deg, var(--verde) 0%, var(--verde-dark) 100%);
      margin: 20px 20px 0;
      border-radius: 16px;
      padding: 20px 28px;
      display: flex;
      align-items: center;
      gap: 28px;
      color: white;
      box-shadow: 0 4px 20px rgba(74,124,37,.3);
    }
    .cr-bloque { text-align: center; min-width: 56px; }
    .cr-num    { font-size: 34px; font-weight: 700; line-height: 1; }
    .cr-label  { font-size: 10px; opacity: .7; margin-top: 3px; text-transform: uppercase; letter-spacing: .5px; }
    .cr-barra-wrap { flex: 1; }
    .cr-barra-titulo { display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 8px; opacity: .85; }
    .cr-barra-bg   { background: rgba(255,255,255,.25); border-radius: 99px; height: 10px; }
    .cr-barra-fill {
      background: var(--verde-light); height: 10px; border-radius: 99px;
      transition: width .6s cubic-bezier(.4,0,.2,1);
      box-shadow: 0 0 8px rgba(168,208,96,.5);
    }

    /* Controles */
    .controles {
      display: flex; gap: 8px; padding: 16px 20px 8px;
      flex-wrap: wrap; align-items: center;
    }
    .filtros-lbl { font-size: 12px; font-weight: 600; color: var(--texto-sub); margin-right: 2px; }
    .filtro-btn {
      padding: 6px 14px; border-radius: 99px;
      border: 1.5px solid var(--gris-borde);
      background: white; font-family: 'DM Sans',sans-serif;
      font-size: 12px; font-weight: 500; color: var(--texto-sub); cursor: pointer;
      transition: all .15s;
    }
    .filtro-btn.active, .filtro-btn:hover { background: var(--verde); color: white; border-color: var(--verde); }
    .search-wrap { margin-left: auto; }
    .search-input {
      padding: 7px 14px 7px 32px; border-radius: 99px;
      border: 1.5px solid var(--gris-borde);
      font-family: 'DM Sans',sans-serif; font-size: 13px;
      background: white url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%239aa' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='M21 21l-4.35-4.35'/%3E%3C/svg%3E") no-repeat 10px center;
      outline: none; width: 200px; transition: border-color .15s;
    }
    .search-input:focus { border-color: var(--verde); }

    .seccion-titulo {
      padding: 4px 20px 10px;
      font-size: 13px; font-weight: 600; color: var(--texto-sub);
    }

    /* Grid */
    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
      gap: 14px;
      padding: 0 20px 32px;
    }

    /* Tarjeta */
    .card {
      background: white; border-radius: 14px; padding: 18px;
      box-shadow: var(--sombra);
      border-top: 4px solid var(--gris-borde);
      transition: transform .18s, box-shadow .18s;
    }
    .card:hover { transform: translateY(-3px); box-shadow: var(--sombra-h); }
    .card.disponible    { border-top-color: var(--verde); }
    .card.inscrita      { border-top-color: var(--azul); }
    .card.sin_cupos     { border-top-color: var(--rojo); }
    .card.cruce_horario { border-top-color: var(--naranja); }
    .card.limite_creditos { border-top-color: var(--morado); }

    .card-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; }
    .card-nombre { font-size: 15px; font-weight: 700; line-height: 1.3; }
    .card-codigo { font-family: 'DM Mono',monospace; font-size: 11px; color: var(--texto-sub); margin-top: 3px; }
    .badge-cred {
      background: var(--verde-bg); color: var(--verde);
      font-size: 12px; font-weight: 700;
      padding: 4px 10px; border-radius: 99px; white-space: nowrap;
      border: 1px solid rgba(74,124,37,.2);
    }

    .estado-chip {
      display: inline-flex; align-items: center; gap: 4px;
      font-size: 10px; font-weight: 700; text-transform: uppercase;
      letter-spacing: .4px; padding: 3px 9px; border-radius: 99px; margin-bottom: 10px;
    }
    .chip-disponible     { background: #e8f5e9; color: #2e7d32; }
    .chip-inscrita       { background: #e3f2fd; color: #1565c0; }
    .chip-sin_cupos      { background: #ffebee; color: #c62828; }
    .chip-cruce_horario  { background: #fff3e0; color: #bf360c; }
    .chip-limite_creditos{ background: #f3e5f5; color: #6a1b9a; }

    .info-row { font-size: 12px; color: var(--texto-sub); display: flex; align-items: center; gap: 6px; margin-bottom: 5px; }

    /* Barra de cupos */
    .cupos-wrap  { margin: 10px 0 6px; }
    .cupos-header { display: flex; justify-content: space-between; font-size: 11px; font-weight: 600; margin-bottom: 5px; }
    .cupos-bg    { background: #f0f0f0; border-radius: 99px; height: 6px; }
    .cupos-fill  { height: 6px; border-radius: 99px; transition: width .5s; }
    .cupos-fill.ok   { background: var(--verde-light); }
    .cupos-fill.bajo { background: #ffb74d; }
    .cupos-fill.cero { background: #ef9a9a; }
    .lbl-ok    { color: var(--verde); }
    .lbl-bajo  { color: var(--naranja); }
    .lbl-cero  { color: var(--rojo); }

    .horarios-wrap { display: flex; flex-wrap: wrap; gap: 4px; margin: 8px 0; }
    .horario-chip  {
      background: #f5f5f5; border-radius: 6px;
      font-size: 11px; font-family: 'DM Mono',monospace;
      padding: 3px 8px; color: var(--texto-sub);
    }

    /* Botones */
    .btn {
      width: 100%; padding: 10px 16px; border-radius: 10px; border: none;
      font-family: 'DM Sans',sans-serif; font-size: 13px; font-weight: 600;
      cursor: pointer; margin-top: 10px; transition: all .18s;
      display: flex; align-items: center; justify-content: center; gap: 6px;
    }
    .btn:disabled { cursor: not-allowed; opacity: .65; }
    .btn-inscribir { background: var(--verde); color: white; }
    .btn-inscribir:hover:not(:disabled) { background: var(--verde-dark); }
    .btn-inscrita  { background: #e3f2fd; color: var(--azul); }
    .btn-bloqueado { background: #f5f5f5; color: #bbb; }

    /* States */
    .loading { grid-column: 1/-1; text-align: center; padding: 60px 20px; color: var(--texto-sub); font-size: 14px; }
    .spinner { width: 32px; height: 32px; border: 3px solid var(--gris-borde); border-top-color: var(--verde); border-radius: 50%; animation: spin .7s linear infinite; margin: 0 auto 12px; }
    @keyframes spin { to { transform: rotate(360deg); } }
    .empty { grid-column: 1/-1; text-align: center; padding: 50px; color: var(--texto-sub); }

    /* Toast */
    .toast {
      position: fixed; bottom: 24px; right: 24px;
      padding: 13px 20px; border-radius: 12px;
      font-size: 14px; font-weight: 500; color: white;
      display: none; z-index: 9999;
      box-shadow: 0 6px 20px rgba(0,0,0,.2);
      max-width: 340px;
    }
    .toast.ok  { background: var(--verde); }
    .toast.err { background: var(--rojo); }
  </style>
</head>
<body>
<?php include 'nav.php'; ?>

<!-- Panel créditos -->
<div class="creditos-panel">
  <div class="cr-bloque">
    <div class="cr-num"  id="cr-ins">—</div>
    <div class="cr-label">Inscritos</div>
  </div>
  <div class="cr-barra-wrap">
    <div class="cr-barra-titulo">
      <span>Créditos del semestre</span>
      <span id="cr-pct">0%</span>
    </div>
    <div class="cr-barra-bg">
      <div class="cr-barra-fill" id="cr-barra" style="width:0%"></div>
    </div>
  </div>
  <div class="cr-bloque">
    <div class="cr-num" id="cr-max">—</div>
    <div class="cr-label">Máximo</div>
  </div>
</div>

<!-- Controles -->
<div class="controles">
  <span class="filtros-lbl">Filtrar:</span>
  <button class="filtro-btn active" onclick="filtrar('todos',this)">Todos</button>
  <button class="filtro-btn" onclick="filtrar('disponible',this)">Disponibles</button>
  <button class="filtro-btn" onclick="filtrar('inscrita',this)">Inscritas</button>
  <button class="filtro-btn" onclick="filtrar('sin_cupos',this)">Sin cupos</button>
  <div class="search-wrap">
    <input class="search-input" type="text" placeholder="Buscar materia..." id="buscador" oninput="renderGrid()">
  </div>
</div>

<p class="seccion-titulo" id="sec-titulo">Cargando...</p>
<div class="grid" id="grid"></div>
<div class="toast" id="toast"></div>

<script>
  const estId = sessionStorage.getItem('estudiante_id');
  if (!estId) window.location.href = 'index.php';

  let materias = [];
  let filtroActivo = 'todos';

  const ESTADO_LABELS = {
    disponible:     '● Disponible',
    inscrita:       '✓ Inscrita',
    sin_cupos:      '✕ Sin cupos',
    cruce_horario:  '⚠ Cruce horario',
    limite_creditos:'⛔ Límite créditos'
  };

  function toast(msg, tipo='ok') {
    const t = document.getElementById('toast');
    t.textContent = msg; t.className = 'toast ' + tipo;
    t.style.display = 'block';
    clearTimeout(t._t);
    t._t = setTimeout(() => t.style.display='none', 4000);
  }

  function actualizarCreditos(e) {
    const pct = Math.round((e.creditos_inscritos / e.creditos_max) * 100);
    document.getElementById('cr-ins').textContent   = e.creditos_inscritos;
    document.getElementById('cr-max').textContent   = e.creditos_max;
    document.getElementById('cr-pct').textContent   = pct + '%';
    document.getElementById('cr-barra').style.width = pct + '%';
  }

  function cuposHTML(m) {
    const r = m.cupos_restantes, t = m.cupos_total;
    const pct = t > 0 ? Math.round((r/t)*100) : 0;
    const cls = r===0 ? 'cero' : r<=5 ? 'bajo' : 'ok';
    const txt = r===0 ? '🔴 Sin cupos'
              : r<=5  ? `⚠️ Últimos ${r} de ${t}`
              :          `✅ ${r} / ${t} disponibles`;
    return `<div class="cupos-wrap">
      <div class="cupos-header">
        <span class="lbl-${cls}">${txt}</span>
        <span style="color:#bbb">${pct}%</span>
      </div>
      <div class="cupos-bg"><div class="cupos-fill ${cls}" style="width:${pct}%"></div></div>
    </div>`;
  }

  function botonHTML(m) {
    switch(m.estado_ux) {
      case 'inscrita':       return `<button class="btn btn-inscrita"  disabled>✓ Ya inscrita</button>`;
      case 'sin_cupos':      return `<button class="btn btn-bloqueado" disabled>🔴 Sin cupos</button>`;
      case 'cruce_horario':  return `<button class="btn btn-bloqueado" disabled>⚠ Cruce de horario</button>`;
      case 'limite_creditos':return `<button class="btn btn-bloqueado" disabled>⛔ Límite de créditos</button>`;
      default: return `<button class="btn btn-inscribir" onclick="inscribir(${m.id})">+ Inscribir materia</button>`;
    }
  }

  function cardHTML(m) {
    const hor = m.horarios.length
      ? m.horarios.map(h=>`<span class="horario-chip">${h.dia} ${h.hora_inicio}–${h.hora_fin}</span>`).join('')
      : '<span style="font-size:11px;color:#bbb">Sin horario</span>';
    return `<div class="card ${m.estado_ux}" id="card-${m.id}">
      <div class="card-top">
        <div>
          <div class="card-nombre">${m.nombre}</div>
          <div class="card-codigo">${m.codigo}</div>
        </div>
        <div class="badge-cred">${m.creditos} cr.</div>
      </div>
      <span class="estado-chip chip-${m.estado_ux}">${ESTADO_LABELS[m.estado_ux]||m.estado_ux}</span>
      ${cuposHTML(m)}
      <div class="info-row">👤 ${m.docente}</div>
      <div class="info-row">📍 ${m.salon}</div>
      <div class="horarios-wrap">${hor}</div>
      ${botonHTML(m)}
    </div>`;
  }

  function filtrar(estado, btn) {
    filtroActivo = estado;
    document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');
    renderGrid();
  }

  function renderGrid() {
    const q = document.getElementById('buscador').value.toLowerCase();
    const filtradas = materias.filter(m => {
      const ok1 = filtroActivo==='todos' || m.estado_ux===filtroActivo;
      const ok2 = m.nombre.toLowerCase().includes(q) || m.codigo.toLowerCase().includes(q) || m.docente.toLowerCase().includes(q);
      return ok1 && ok2;
    });
    document.getElementById('sec-titulo').textContent = `${filtradas.length} materia${filtradas.length!==1?'s':''} encontrada${filtradas.length!==1?'s':''}`;
    const grid = document.getElementById('grid');
    grid.innerHTML = filtradas.length
      ? filtradas.map(cardHTML).join('')
      : '<div class="empty">🔍 No se encontraron materias con ese filtro.</div>';
  }

  async function cargar() {
    document.getElementById('grid').innerHTML = '<div class="loading"><div class="spinner"></div>Cargando materias...</div>';
    try {
      const r    = await fetch('materias.php?estudiante_id=' + estId);
      const data = await r.json();
      if (!data.success) { toast(data.error,'err'); return; }
      materias = data.materias;
      actualizarCreditos(data.estudiante);
      renderGrid();
    } catch(e) {
      document.getElementById('grid').innerHTML =
        '<div class="empty">⚠️ No se pudo conectar. Verifica que Laragon esté activo.</div>';
    }
  }

  async function inscribir(materia_id) {
    const btn = document.querySelector(`#card-${materia_id} .btn`);
    if (btn) { btn.disabled=true; btn.textContent='Inscribiendo...'; }
    try {
      const r = await fetch('inscribir.php', {
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({ estudiante_id: parseInt(estId), materia_id })
      });
      const data = await r.json();
      if (data.success) { toast(data.mensaje,'ok'); await cargar(); }
      else {
        toast(data.error,'err');
        if (btn) { btn.disabled=false; btn.textContent='+ Inscribir materia'; }
      }
    } catch(e) {
      toast('Error de red.','err');
      if (btn) { btn.disabled=false; btn.textContent='+ Inscribir materia'; }
    }
  }

  cargar();
</script>
</body>
</html>

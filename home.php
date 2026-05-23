<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="pagina" content="home">
  <title>UTS — Inicio</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
  <style>
    :root {
      --verde:#4a7c25; --verde-dark:#3a5a1e; --verde-light:#a8d060;
      --verde-bg:#f0f7e8; --gris-bg:#f4f6f8; --gris-borde:#e0e4ea;
      --texto:#1a2530; --texto-sub:#5a6a78; --blanco:#ffffff;
      --sombra: 0 2px 12px rgba(0,0,0,.07);
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'DM Sans', sans-serif; background: var(--gris-bg); color: var(--texto); }

    .page { max-width: 1000px; margin: 28px auto; padding: 0 20px 40px; }

    /* Bienvenida */
    .bienvenida {
      background: linear-gradient(135deg, var(--verde) 0%, var(--verde-dark) 100%);
      border-radius: 18px;
      padding: 28px 32px;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 24px;
      box-shadow: 0 6px 24px rgba(74,124,37,.3);
    }
    .bien-titulo { font-size: 26px; font-weight: 700; margin-bottom: 4px; }
    .bien-sub    { font-size: 14px; opacity: .75; }
    .bien-badges { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 16px; }
    .badge {
      background: rgba(255,255,255,.15);
      border: 1px solid rgba(255,255,255,.2);
      border-radius: 99px;
      padding: 6px 14px;
      font-size: 12px;
      font-weight: 500;
    }
    .promedio-circulo {
      width: 88px; height: 88px;
      background: rgba(255,255,255,.15);
      border-radius: 50%;
      display: flex; flex-direction: column;
      align-items: center; justify-content: center;
      border: 2px solid rgba(255,255,255,.3);
      flex-shrink: 0;
    }
    .promedio-num { font-size: 26px; font-weight: 700; line-height: 1; }
    .promedio-lbl { font-size: 10px; opacity: .7; }

    /* Grid de stats */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 14px;
      margin-bottom: 24px;
    }
    .stat-card {
      background: white;
      border-radius: 14px;
      padding: 20px;
      box-shadow: var(--sombra);
      border-top: 3px solid var(--verde-light);
    }
    .stat-num   { font-size: 30px; font-weight: 700; color: var(--verde); }
    .stat-label { font-size: 12px; color: var(--texto-sub); margin-top: 4px; }

    /* Accesos rápidos */
    .seccion-titulo {
      font-size: 13px; font-weight: 700;
      color: var(--texto-sub); text-transform: uppercase;
      letter-spacing: .5px; margin-bottom: 14px;
    }
    .accesos {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
      gap: 14px;
      margin-bottom: 28px;
    }
    .acceso-card {
      background: white;
      border-radius: 14px;
      padding: 22px;
      box-shadow: var(--sombra);
      text-decoration: none;
      color: var(--texto);
      display: flex;
      align-items: center;
      gap: 16px;
      transition: transform .15s, box-shadow .15s;
      border: 1.5px solid transparent;
    }
    .acceso-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 24px rgba(74,124,37,.15);
      border-color: var(--verde-light);
    }
    .acceso-icon {
      width: 50px; height: 50px;
      background: var(--verde-bg);
      border-radius: 14px;
      display: flex; align-items: center; justify-content: center;
      font-size: 22px;
      flex-shrink: 0;
    }
    .acceso-nombre { font-size: 15px; font-weight: 600; }
    .acceso-desc   { font-size: 12px; color: var(--texto-sub); margin-top: 3px; }
    .acceso-arrow  { margin-left: auto; color: var(--verde); font-size: 18px; opacity: .6; }

    /* Materias inscritas */
    .mat-lista { display: flex; flex-direction: column; gap: 10px; }
    .mat-item {
      background: white;
      border-radius: 12px;
      padding: 14px 18px;
      box-shadow: var(--sombra);
      display: flex;
      align-items: center;
      gap: 14px;
    }
    .mat-dot  { width: 10px; height: 10px; border-radius: 50%; background: var(--verde); flex-shrink: 0; }
    .mat-nombre { font-weight: 600; font-size: 14px; }
    .mat-info   { font-size: 12px; color: var(--texto-sub); margin-top: 2px; }
    .mat-cred   {
      margin-left: auto;
      background: var(--verde-bg);
      color: var(--verde);
      font-size: 12px; font-weight: 700;
      padding: 3px 10px; border-radius: 99px;
      white-space: nowrap;
    }
    .empty-msg { text-align:center; padding: 32px; color: var(--texto-sub); font-size: 13px; }
    .empty-msg a { color: var(--verde); font-weight: 600; }

    /* FIX: error-msg para cuando falla la API */
    .error-api {
      background: #fff3e0;
      color: #bf360c;
      border: 1px solid #ffcc80;
      border-radius: 10px;
      padding: 14px 18px;
      font-size: 13px;
      line-height: 1.5;
    }
    .error-api strong { display: block; margin-bottom: 4px; }

    .spinner {
      width: 24px; height: 24px; border: 3px solid #e0e4ea;
      border-top-color: var(--verde); border-radius: 50%;
      animation: spin .7s linear infinite; margin: 24px auto;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
  </style>
</head>
<body>
<?php include 'nav.php'; ?>

<div class="page">

  <!-- Bienvenida -->
  <div class="bienvenida">
    <div>
      <div class="bien-titulo" id="bien-titulo">¡Hola!</div>
      <div class="bien-sub"   id="bien-sub">Bienvenido al sistema estudiantil UTS</div>
      <div class="bien-badges">
        <span class="badge" id="badge-programa">Cargando...</span>
        <span class="badge" id="badge-semestre"></span>
        <span class="badge" id="badge-id"></span>
      </div>
    </div>
    <div class="promedio-circulo" id="promedio-wrap">
      <div class="promedio-num" id="promedio-num">—</div>
      <div class="promedio-lbl">Promedio</div>
    </div>
  </div>

  <!-- Stats -->
  <div class="stats-grid" id="stats-grid">
    <div class="stat-card">
      <div class="spinner"></div>
    </div>
  </div>

  <!-- Accesos rápidos -->
  <p class="seccion-titulo">Accesos rápidos</p>
  <div class="accesos">
    <a href="inscripcion.php" class="acceso-card">
      <div class="acceso-icon">📋</div>
      <div>
        <div class="acceso-nombre">Inscripción de materias</div>
        <div class="acceso-desc">Ver materias disponibles e inscribirte</div>
      </div>
      <div class="acceso-arrow">›</div>
    </a>
    <a href="resumen.php" class="acceso-card">
      <div class="acceso-icon">📊</div>
      <div>
        <div class="acceso-nombre">Resumen de inscripción</div>
        <div class="acceso-desc">Ver tus créditos y materias inscritas</div>
      </div>
      <div class="acceso-arrow">›</div>
    </a>
  </div>

  <!-- Materias inscritas -->
  <p class="seccion-titulo">Mis materias inscritas</p>
  <div id="mat-lista"><div class="spinner"></div></div>

</div>

<script>
  // ── FIX PRINCIPAL: verificar sesión antes de cualquier otra cosa ──
  const estId = sessionStorage.getItem('estudiante_id');
  if (!estId) {
    window.location.replace('index.php');
  }

  // Leer datos de sessionStorage (guardados en index.php mejorado)
  const nombre   = sessionStorage.getItem('estudiante_nombre')   || '';
  const codigo   = sessionStorage.getItem('estudiante_codigo')   || '';
  const programa = sessionStorage.getItem('estudiante_programa') || '';
  const semestre = sessionStorage.getItem('estudiante_semestre') || '';
  const promedio = sessionStorage.getItem('estudiante_promedio') || '—';

  // Poblar bienvenida inmediatamente con datos de sesión (sin esperar API)
  document.getElementById('bien-titulo').textContent   = '¡Hola, ' + (nombre.split(' ')[0] || nombre) + '!';
  document.getElementById('bien-sub').textContent      = nombre + (programa ? ' · ' + programa : '');
  document.getElementById('badge-programa').textContent = programa || 'Sin programa';
  document.getElementById('badge-id').textContent      = 'Código: ' + codigo;
  document.getElementById('promedio-num').textContent  = promedio || '—';
  if (semestre) {
    document.getElementById('badge-semestre').textContent = 'Semestre ' + semestre;
  }

  // ── Función de error reutilizable ──
  function mostrarErrorStats(msg) {
    document.getElementById('stats-grid').innerHTML =
      `<div class="error-api"><strong>⚠️ No se pudieron cargar las estadísticas</strong>${msg}</div>`;
  }

  function mostrarErrorMaterias(msg) {
    document.getElementById('mat-lista').innerHTML =
      `<div class="error-api"><strong>⚠️ No se pudieron cargar las materias</strong>${msg}
       <br><a href="index.php" style="color:#bf360c;font-weight:600">Volver al inicio →</a></div>`;
  }

  // ── Cargar datos desde la API ──
  fetch('resumen.php?estudiante_id=' + estId + '&api=1')
    .then(r => {
      if (!r.ok) throw new Error('HTTP ' + r.status);
      return r.json();
    })
    .then(data => {
      if (!data.success) {
        mostrarErrorStats(data.error || 'Error desconocido.');
        mostrarErrorMaterias(data.error || 'Error desconocido.');
        return;
      }

      const e  = data.estudiante;
      const cr = data.resumen_creditos;

      // Actualizar promedio y semestre con datos frescos de BD
      document.getElementById('promedio-num').textContent    = e.promedio;
      document.getElementById('badge-semestre').textContent  = 'Semestre ' + e.semestre;

      // Stats cards
      document.getElementById('stats-grid').innerHTML = `
        <div class="stat-card">
          <div class="stat-num">${data.total_materias}</div>
          <div class="stat-label">Materias inscritas</div>
        </div>
        <div class="stat-card">
          <div class="stat-num">${cr.inscritos}</div>
          <div class="stat-label">Créditos inscritos</div>
        </div>
        <div class="stat-card">
          <div class="stat-num">${cr.disponibles}</div>
          <div class="stat-label">Créditos disponibles</div>
        </div>
        <div class="stat-card">
          <div class="stat-num">${e.promedio}</div>
          <div class="stat-label">Promedio acumulado</div>
        </div>
      `;

      // Lista de materias
      const lista = document.getElementById('mat-lista');
      if (!data.materias_inscritas.length) {
        lista.innerHTML = '<div class="empty-msg">Aún no tienes materias inscritas. <a href="inscripcion.php">Ir a inscripción →</a></div>';
        return;
      }
      lista.innerHTML = '<div class="mat-lista">' +
        data.materias_inscritas.map(m => {
          const hor = m.horarios.map(h => h.dia + ' ' + h.hora_inicio + '–' + h.hora_fin).join(' | ');
          return `<div class="mat-item">
            <div class="mat-dot"></div>
            <div>
              <div class="mat-nombre">${m.nombre}</div>
              <div class="mat-info">${m.docente} · ${hor || 'Sin horario'}</div>
            </div>
            <div class="mat-cred">${m.creditos} cr.</div>
          </div>`;
        }).join('') + '</div>';
    })
    .catch(err => {
      // FIX: ambos spinners se reemplazan con mensaje de error
      const esRed = err.message && err.message.includes('fetch');
      const msg = esRed
        ? 'No se pudo conectar. Verifica que Laragon/XAMPP esté activo y MySQL encendido.'
        : 'Error al procesar la respuesta del servidor. Revisa que la base de datos <code>uts_matriculas</code> exista.';
      mostrarErrorStats(msg);
      mostrarErrorMaterias(msg);
    });
</script>
</body>
</html>

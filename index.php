<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UTS — Bienvenido</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
  <style>
    :root {
      --verde:       #4a7c25;
      --verde-dark:  #3a5a1e;
      --verde-light: #a8d060;
      --verde-bg:    #f0f7e8;
      --gris-bg:     #f4f6f8;
      --gris-borde:  #e0e4ea;
      --texto:       #1a2530;
      --texto-sub:   #5a6a78;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--gris-bg);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* ── NAV ── */
    .topbar {
      background: var(--verde-dark);
      padding: 0 32px;
      height: 58px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .topbar-brand {
      display: flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
    }
    .topbar-logo {
      width: 36px; height: 36px;
      background: var(--verde-light);
      border-radius: 9px;
      display: flex; align-items: center; justify-content: center;
      font-weight: 700; font-size: 13px; color: var(--verde-dark);
    }
    .topbar-name { color: white; font-size: 15px; font-weight: 600; }
    .topbar-btn {
      background: var(--verde-light);
      color: var(--verde-dark);
      border: none; border-radius: 8px;
      padding: 8px 18px;
      font-family: 'DM Sans', sans-serif;
      font-size: 13px; font-weight: 700;
      cursor: pointer;
      text-decoration: none;
      transition: opacity .15s;
    }
    .topbar-btn:hover { opacity: .85; }

    /* ── HERO ── */
    .hero {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 60px 24px;
    }
    .hero-badge {
      background: var(--verde-bg);
      color: var(--verde);
      border: 1px solid rgba(74,124,37,.2);
      border-radius: 99px;
      padding: 5px 16px;
      font-size: 12px; font-weight: 600;
      letter-spacing: .3px;
      margin-bottom: 28px;
    }
    .hero-titulo {
      font-size: clamp(28px, 5vw, 52px);
      font-weight: 700;
      color: var(--texto);
      line-height: 1.15;
      max-width: 640px;
      margin-bottom: 18px;
    }
    .hero-titulo span { color: var(--verde); }
    .hero-sub {
      font-size: 16px;
      color: var(--texto-sub);
      max-width: 480px;
      line-height: 1.6;
      margin-bottom: 40px;
    }
    .hero-btns { display: flex; gap: 14px; flex-wrap: wrap; justify-content: center; }
    .btn-primary {
      background: var(--verde);
      color: white;
      border: none; border-radius: 12px;
      padding: 15px 36px;
      font-family: 'DM Sans', sans-serif;
      font-size: 16px; font-weight: 700;
      cursor: pointer;
      text-decoration: none;
      transition: background .15s, transform .1s;
      box-shadow: 0 6px 20px rgba(74,124,37,.3);
    }
    .btn-primary:hover { background: var(--verde-dark); transform: translateY(-2px); }
    .btn-primary:active { transform: scale(.97); }
    .btn-secondary {
      background: white;
      color: var(--verde);
      border: 1.5px solid var(--verde-light);
      border-radius: 12px;
      padding: 15px 36px;
      font-family: 'DM Sans', sans-serif;
      font-size: 16px; font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      transition: background .15s;
    }
    .btn-secondary:hover { background: var(--verde-bg); }

    /* ── FEATURES ── */
    .features {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
      gap: 16px;
      max-width: 860px;
      width: 100%;
      margin-top: 64px;
      padding: 0 8px;
    }
    .feat-card {
      background: white;
      border-radius: 16px;
      padding: 24px;
      box-shadow: 0 2px 12px rgba(0,0,0,.07);
      text-align: left;
    }
    .feat-icon {
      font-size: 28px;
      margin-bottom: 12px;
    }
    .feat-titulo { font-size: 15px; font-weight: 700; margin-bottom: 6px; color: var(--texto); }
    .feat-desc { font-size: 13px; color: var(--texto-sub); line-height: 1.5; }

    /* ── FOOTER ── */
    footer {
      text-align: center;
      padding: 20px;
      font-size: 12px;
      color: var(--texto-sub);
      border-top: 1px solid var(--gris-borde);
    }
  </style>
</head>
<body>

  <!-- Barra superior -->
  <nav class="topbar">
    <a class="topbar-brand" href="index.php">
      <div class="topbar-logo">UTS</div>
      <span class="topbar-name">Sistema Estudiantil</span>
    </a>
    <a class="topbar-btn" href="login.php">Iniciar sesión →</a>
  </nav>

  <!-- Hero -->
  <main class="hero">
    <div class="hero-badge">📚 Semestre 2026-1 · Sistema de Matrículas</div>

    <h1 class="hero-titulo">
      Bienvenido al portal de<br>
      <span>Unidades Tecnológicas<br>de Santander</span>
    </h1>

    <p class="hero-sub">
      Gestiona tu inscripción de materias, consulta tu horario y revisa tu resumen académico desde un solo lugar.
    </p>

    <div class="hero-btns">
      <a class="btn-primary" href="login.php">🔐 Iniciar sesión</a>
      <a class="btn-secondary" href="#features">Ver funciones ↓</a>
    </div>

    <!-- Features -->
    <div class="features" id="features">
      <div class="feat-card">
        <div class="feat-icon">🔐</div>
        <div class="feat-titulo">Acceso seguro</div>
        <div class="feat-desc">Ingresa con tu código estudiantil y contraseña asignada por la institución.</div>
      </div>
      <div class="feat-card">
        <div class="feat-icon">📋</div>
        <div class="feat-titulo">Inscripción de materias</div>
        <div class="feat-desc">Consulta las materias disponibles, revisa cupos y horarios, e inscríbete fácilmente.</div>
      </div>
      <div class="feat-card">
        <div class="feat-icon">📊</div>
        <div class="feat-titulo">Resumen académico</div>
        <div class="feat-desc">Ve tus materias inscritas, créditos acumulados y promedio en tiempo real.</div>
      </div>
      <div class="feat-card">
        <div class="feat-icon">⚡</div>
        <div class="feat-titulo">Rápido y sin papel</div>
        <div class="feat-desc">Todo el proceso de matrícula desde tu computador o celular, sin filas.</div>
      </div>
    </div>
  </main>

  <footer>
    © 2026 Unidades Tecnológicas de Santander — Sistema Estudiantil
  </footer>

  <script>
    // Si ya hay sesión activa, redirigir directamente a home
    if (sessionStorage.getItem('estudiante_id')) {
      window.location.replace('home.php');
    }
  </script>
</body>
</html>

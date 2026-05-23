<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UTS — Iniciar Sesión</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
  <style>
    :root {
      --verde:      #4a7c25;
      --verde-dark: #3a5a1e;
      --verde-light:#a8d060;
      --verde-bg:   #f0f7e8;
      --gris-bg:    #f4f6f8;
      --gris-borde: #e0e4ea;
      --texto:      #1a2530;
      --texto-sub:  #5a6a78;
      --error:      #c62828;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--gris-bg);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-wrap {
      width: 100%;
      max-width: 420px;
      padding: 16px;
    }

    .logo-area {
      text-align: center;
      margin-bottom: 32px;
    }
    .logo-circle {
      width: 64px; height: 64px;
      background: var(--verde);
      border-radius: 18px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      font-weight: 700;
      color: white;
      margin-bottom: 14px;
      box-shadow: 0 8px 24px rgba(74,124,37,.3);
    }
    .logo-nombre { font-size: 22px; font-weight: 700; color: var(--texto); }
    .logo-sub    { font-size: 13px; color: var(--texto-sub); margin-top: 4px; }

    .card {
      background: white;
      border-radius: 20px;
      padding: 32px;
      box-shadow: 0 4px 24px rgba(0,0,0,.08);
    }
    .card-titulo {
      font-size: 18px;
      font-weight: 700;
      color: var(--texto);
      margin-bottom: 6px;
    }
    .card-sub {
      font-size: 13px;
      color: var(--texto-sub);
      margin-bottom: 24px;
    }

    .form-group { margin-bottom: 18px; }
    .form-label {
      display: block;
      font-size: 12px;
      font-weight: 600;
      color: var(--texto-sub);
      text-transform: uppercase;
      letter-spacing: .5px;
      margin-bottom: 7px;
    }
    .form-input {
      width: 100%;
      padding: 12px 14px;
      border: 1.5px solid var(--gris-borde);
      border-radius: 10px;
      font-family: 'DM Sans', sans-serif;
      font-size: 14px;
      color: var(--texto);
      outline: none;
      transition: border-color .15s;
    }
    .form-input:focus { border-color: var(--verde); }

    .btn-login {
      width: 100%;
      padding: 13px;
      background: var(--verde);
      color: white;
      border: none;
      border-radius: 10px;
      font-family: 'DM Sans', sans-serif;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      transition: background .15s, transform .1s;
      margin-top: 4px;
    }
    .btn-login:hover  { background: var(--verde-dark); }
    .btn-login:active { transform: scale(.98); }
    .btn-login:disabled { opacity: .6; cursor: not-allowed; }

    .error-msg {
      background: #ffebee;
      color: var(--error);
      border-radius: 8px;
      padding: 10px 14px;
      font-size: 13px;
      margin-bottom: 16px;
      display: none;
    }

    .demo-hint {
      background: var(--verde-bg);
      border-radius: 10px;
      padding: 12px 14px;
      margin-top: 20px;
      font-size: 12px;
      color: var(--verde-dark);
    }
    .demo-hint strong { display: block; margin-bottom: 4px; }
    .demo-hint code {
      font-family: 'DM Mono', monospace;
      background: white;
      padding: 1px 5px;
      border-radius: 4px;
    }

    .footer-uts {
      text-align: center;
      font-size: 11px;
      color: var(--texto-sub);
      margin-top: 20px;
    }
  </style>
</head>
<body>

<div class="login-wrap">
  <div class="logo-area">
    <div class="logo-circle">UTS</div>
    <div class="logo-nombre">Sistema Estudiantil</div>
    <div class="logo-sub">Unidades Tecnológicas de Santander</div>
  </div>

  <div class="card">
    <div class="card-titulo">Iniciar sesión</div>
    <div class="card-sub">Ingresa tu código y contraseña para continuar</div>

    <div class="error-msg" id="error-msg">Usuario o contraseña incorrectos.</div>

    <div class="form-group">
      <label class="form-label" for="codigo">Código estudiantil</label>
      <input class="form-input" id="codigo" type="text" placeholder="Ej: 1005678" autocomplete="username">
    </div>
    <div class="form-group">
      <label class="form-label" for="password">Contraseña</label>
      <input class="form-input" id="password" type="password" placeholder="••••••••" autocomplete="current-password">
    </div>

    <button class="btn-login" id="btn-login" onclick="login()">Ingresar</button>

    <div class="demo-hint">
      <strong>🧪 Datos de prueba:</strong>
      Código: <code>1005678</code> &nbsp;|&nbsp; Contraseña: <code>1234</code>
    </div>
  </div>

  <div class="footer-uts">© 2026 Unidades Tecnológicas de Santander</div>
</div>

<script>
  // Si ya hay sesión, ir directo al inicio
  if (sessionStorage.getItem('estudiante_id')) {
    window.location.href = 'home.php';
  }

  function login() {
    const codigo = document.getElementById('codigo').value.trim();
    const pass   = document.getElementById('password').value.trim();
    const errDiv = document.getElementById('error-msg');
    const btn    = document.getElementById('btn-login');

    errDiv.style.display = 'none';

    if (!codigo || !pass) {
      errDiv.textContent = 'Por favor ingresa tu código y contraseña.';
      errDiv.style.display = 'block';
      return;
    }

    btn.disabled    = true;
    btn.textContent = 'Verificando...';

    fetch('login.php', {
      method : 'POST',
      headers: { 'Content-Type': 'application/json' },
      body   : JSON.stringify({ codigo, password: pass })
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        sessionStorage.setItem('estudiante_id',  data.estudiante.id);
        sessionStorage.setItem('estudiante_nombre', data.estudiante.nombre);
        sessionStorage.setItem('estudiante_codigo', data.estudiante.codigo);
        sessionStorage.setItem('estudiante_programa', data.estudiante.programa);
        window.location.href = 'home.php';
      } else {
        errDiv.textContent   = data.error || 'Credenciales incorrectas.';
        errDiv.style.display = 'block';
        btn.disabled         = false;
        btn.textContent      = 'Ingresar';
      }
    })
    .catch(() => {
      errDiv.textContent   = 'Error conectando con el servidor.';
      errDiv.style.display = 'block';
      btn.disabled         = false;
      btn.textContent      = 'Ingresar';
    });
  }

  // Enter key
  document.addEventListener('keydown', e => { if (e.key === 'Enter') login(); });
</script>
</body>
</html>

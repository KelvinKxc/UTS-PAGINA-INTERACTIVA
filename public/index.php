<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UTS — Iniciar Sesión</title>
  <link rel="stylesheet" href="css/styles.css">
  <style>
    body { display:flex; align-items:center; justify-content:center; min-height:100vh; background:var(--gris-bg); }
    .login-card {
      background:white; border-radius:20px; padding:40px 36px;
      width:100%; max-width:380px;
      box-shadow:0 8px 32px rgba(0,0,0,.1);
    }
    .login-logo {
      width:52px; height:52px; background:var(--verde); border-radius:14px;
      display:flex; align-items:center; justify-content:center;
      font-weight:700; color:white; font-size:16px; margin-bottom:20px;
    }
    h1 { font-size:22px; font-weight:700; margin-bottom:4px; }
    .sub { font-size:13px; color:var(--texto-sub); margin-bottom:28px; }
    label { display:block; font-size:12px; font-weight:600; color:var(--texto-sub); margin-bottom:6px; }
    input[type=text], input[type=password] {
      width:100%; padding:11px 14px; border:1.5px solid var(--gris-borde);
      border-radius:10px; font-family:'DM Sans',sans-serif; font-size:14px;
      outline:none; transition:border-color .15s; margin-bottom:16px;
    }
    input:focus { border-color:var(--verde); }
    .btn-login {
      width:100%; padding:12px; background:var(--verde); color:white;
      border:none; border-radius:10px; font-family:'DM Sans',sans-serif;
      font-size:15px; font-weight:600; cursor:pointer; transition:background .15s;
    }
    .btn-login:hover:not(:disabled) { background:var(--verde-dark); }
    .btn-login:disabled { opacity:.6; cursor:not-allowed; }
    .msg-error {
      background:#ffebee; color:var(--rojo); border-radius:8px;
      padding:10px 14px; font-size:13px; margin-bottom:16px; display:none;
    }
  </style>
</head>
<body>
<div class="login-card">
  <div class="login-logo">UTS</div>
  <h1>Bienvenido</h1>
  <p class="sub">Sistema Estudiantil — Ingresa con tu código y contraseña</p>

  <div class="msg-error" id="error"></div>

  <label for="codigo">Código estudiantil</label>
  <input type="text" id="codigo" placeholder="Ej. EST001" autocomplete="username">

  <label for="pass">Contraseña</label>
  <input type="password" id="pass" placeholder="••••••" autocomplete="current-password">

  <button class="btn-login" id="btn" onclick="login()">Ingresar</button>
</div>

<script src="js/auth.js"></script>
<script>
  // Si ya hay sesión, ir directo al home
  if (Auth.id) window.location.replace('../views/home.php');

  async function login() {
    const codigo = document.getElementById('codigo').value.trim();
    const pass   = document.getElementById('pass').value.trim();
    const err    = document.getElementById('error');
    const btn    = document.getElementById('btn');

    err.style.display = 'none';
    if (!codigo || !pass) { err.textContent = 'Completa todos los campos.'; err.style.display='block'; return; }

    btn.disabled = true; btn.textContent = 'Verificando...';
    try {
      const r    = await fetch('../api/login.php', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({codigo, password: pass}) });
      const data = await r.json();
      if (data.success) {
        Auth.guardar(data.estudiante);
        window.location.replace('../views/home.php');
      } else {
        err.textContent    = data.error || 'Credenciales incorrectas.';
        err.style.display  = 'block';
        btn.disabled       = false;
        btn.textContent    = 'Ingresar';
      }
    } catch(e) {
      err.textContent   = 'Error de red. Verifica que el servidor esté activo.';
      err.style.display = 'block';
      btn.disabled      = false;
      btn.textContent   = 'Ingresar';
    }
  }

  document.addEventListener('keydown', e => { if (e.key==='Enter') login(); });
</script>
</body>
</html>

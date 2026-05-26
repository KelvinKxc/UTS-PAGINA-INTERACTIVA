<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UTS — Iniciar Sesión</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
:root {
  --verde: #4a7c25;
  --verde-dark: #3a5a1e;
  --verde-light:#a8d060;
  --verde-bg: #f0f7e8;
  --gris-bg: #f4f6f8;
  --gris-borde: #e0e4ea;
  --texto: #1a2530;
  --texto-sub: #5a6a78;
  --error: #c62828;
}
* { box-sizing: border-box; margin: 0; padding: 0; }
body {
  font-family: 'DM Sans', sans-serif;
  background: var(--gris-bg);
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.back-link {
  align-self: flex-start;
  margin: 16px 16px 0;
  font-size: 13px;
  color: var(--texto-sub);
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 5px;
  transition: color .15s;
}
.back-link:hover { color: var(--verde); }

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
.logo-sub { font-size: 13px; color: var(--texto-sub); margin-top: 4px; }

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
.form-input.input-error { border-color: var(--error); }

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
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}
.btn-login:hover { background: var(--verde-dark); }
.btn-login:active { transform: scale(.98); }
.btn-login:disabled { opacity: .6; cursor: not-allowed; }

.spinner-btn {
  width: 16px; height: 16px;
  border: 2px solid rgba(255,255,255,.4);
  border-top-color: white;
  border-radius: 50%;
  animation: spin .6s linear infinite;
  display: none;
}
@keyframes spin { to { transform: rotate(360deg); } }

.error-msg {
  background: #ffebee;
  color: var(--error);
  border: 1px solid #ffcdd2;
  border-radius: 8px;
  padding: 10px 14px;
  font-size: 13px;
  margin-bottom: 16px;
  display: none;
}

.alert-bd {
  background: #fff3e0;
  color: #bf360c;
  border: 1px solid #ffcc80;
  border-radius: 10px;
  padding: 12px 16px;
  font-size: 13px;
  margin-bottom: 16px;
  display: none;
  line-height: 1.5;
}
.alert-bd strong { display: block; margin-bottom: 4px; }

/* ── HINT DE USUARIO DE PRUEBA — más visible ── */
.demo-hint {
  background: var(--verde-bg);
  border: 1.5px solid var(--verde-light);
  border-radius: 12px;
  padding: 14px 16px;
  margin-top: 20px;
}
.demo-hint-titulo {
  font-size: 11px;
  font-weight: 700;
  color: var(--verde-dark);
  text-transform: uppercase;
  letter-spacing: .5px;
  margin-bottom: 8px;
}
.demo-hint-row {
  display: flex;
  justify-content: space-between;
  font-size: 13px;
  color: var(--verde-dark);
  margin-bottom: 4px;
}
.demo-hint-row:last-child { margin-bottom: 0; }
.demo-hint-label { opacity: .7; }
.demo-hint-value {
  font-family: 'DM Mono', monospace;
  font-size: 13px;
  background: white;
  padding: 1px 8px;
  border-radius: 5px;
  font-weight: 500;
  border: 1px solid var(--verde-light);
}
.demo-hint-btn {
  width: 100%;
  margin-top: 10px;
  padding: 8px;
  background: white;
  border: 1.5px solid var(--verde);
  color: var(--verde);
  border-radius: 8px;
  font-family: 'DM Sans', sans-serif;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: background .15s;
}
.demo-hint-btn:hover { background: var(--verde-bg); }

.footer-uts {
  text-align: center;
  font-size: 11px;
  color: var(--texto-sub);
  margin-top: 20px;
}
</style>
</head>
<body>

<a href="noticias.php" class="back-link">← Volver al portal</a>

<div class="login-wrap">
  <div class="logo-area">
    <div class="logo-circle">UTS</div>
    <div class="logo-nombre">Sistema Estudiantil</div>
    <div class="logo-sub">Unidades Tecnológicas de Santander</div>
  </div>

  <div class="card">
    <div class="card-titulo">Iniciar sesión</div>
    <div class="card-sub">Ingresa tu código estudiantil y contraseña</div>

    <div class="alert-bd" id="alert-bd">
      <strong>⚠️ Error de conexión con la base de datos</strong>
      Verifica que MySQL esté activo en Laragon/XAMPP y que la base de datos
      <code>uts_matriculas</code> exista.
    </div>
    <div class="error-msg" id="error-msg"></div>

    <div class="form-group">
      <label class="form-label" for="codigo">Código estudiantil</label>
      <input class="form-input" id="codigo" type="text" placeholder="Ej: 1005678" autocomplete="username">
    </div>

    <div class="form-group">
      <label class="form-label" for="password">Contraseña</label>
      <input class="form-input" id="password" type="password" placeholder="••••••••" autocomplete="current-password">
    </div>

    <button class="btn-login" id="btn-login" onclick="login()">
      <span class="spinner-btn" id="spinner-btn"></span>
      <span id="btn-texto">Ingresar</span>
    </button>

    <!-- HINT DE USUARIO DE PRUEBA -->
    <div class="demo-hint">
      <div class="demo-hint-titulo">🧪 Usuario de prueba</div>
      <div class="demo-hint-row">
        <span class="demo-hint-label">Código estudiantil</span>
        <span class="demo-hint-value">1005678</span>
      </div>
      <div class="demo-hint-row">
        <span class="demo-hint-label">Contraseña</span>
        <span class="demo-hint-value">1234</span>
      </div>
      <button class="demo-hint-btn" onclick="autocompletar()">Autocompletar credenciales</button>
    </div>
  </div>

  <div class="footer-uts">© 2026 Unidades Tecnológicas de Santander</div>
</div>

<script>
// Si ya hay sesión activa, redirigir a home
if (sessionStorage.getItem('estudiante_id')) {
  window.location.href = 'home.php';
}

function autocompletar() {
  document.getElementById('codigo').value = '1005678';
  document.getElementById('password').value = '1234';
  document.getElementById('codigo').focus();
}

function mostrarError(msg) {
  const div = document.getElementById('error-msg');
  div.textContent = msg;
  div.style.display = 'block';
}

function ocultarErrores() {
  document.getElementById('error-msg').style.display = 'none';
  document.getElementById('alert-bd').style.display = 'none';
  document.getElementById('codigo').classList.remove('input-error');
  document.getElementById('password').classList.remove('input-error');
}

function setBtnCargando(cargando) {
  const btn     = document.getElementById('btn-login');
  const spinner = document.getElementById('spinner-btn');
  const texto   = document.getElementById('btn-texto');
  btn.disabled        = cargando;
  spinner.style.display = cargando ? 'block' : 'none';
  texto.textContent   = cargando ? 'Verificando...' : 'Ingresar';
}

async function login() {
  const codigo = document.getElementById('codigo').value.trim();
  const pass   = document.getElementById('password').value.trim();
  ocultarErrores();

  if (!codigo) {
    mostrarError('Por favor ingresa tu código estudiantil.');
    document.getElementById('codigo').classList.add('input-error');
    document.getElementById('codigo').focus();
    return;
  }
  if (!pass) {
    mostrarError('Por favor ingresa tu contraseña.');
    document.getElementById('password').classList.add('input-error');
    document.getElementById('password').focus();
    return;
  }

  setBtnCargando(true);

  try {
    const r = await fetch('login.php', {
      method : 'POST',
      headers: { 'Content-Type': 'application/json' },
      body   : JSON.stringify({ codigo, password: pass })
    });

    const texto = await r.text();
    let data;
    try {
      data = JSON.parse(texto);
    } catch {
      document.getElementById('alert-bd').style.display = 'block';
      setBtnCargando(false);
      return;
    }

    if (data.success) {
      sessionStorage.setItem('estudiante_id',      data.estudiante.id);
      sessionStorage.setItem('estudiante_nombre',   data.estudiante.nombre);
      sessionStorage.setItem('estudiante_codigo',   data.estudiante.codigo);
      sessionStorage.setItem('estudiante_programa', data.estudiante.programa);
      sessionStorage.setItem('estudiante_semestre', data.estudiante.semestre);
      sessionStorage.setItem('estudiante_promedio', data.estudiante.promedio);
      window.location.href = 'home.php';
    } else {
      if (data.error && data.error.toLowerCase().includes('conexión')) {
        document.getElementById('alert-bd').style.display = 'block';
      } else {
        mostrarError(data.error || 'Credenciales incorrectas.');
      }
      setBtnCargando(false);
    }
  } catch (e) {
    document.getElementById('alert-bd').style.display = 'block';
    setBtnCargando(false);
  }
}

document.addEventListener('keydown', e => { if (e.key === 'Enter') login(); });
</script>
</body>
</html>

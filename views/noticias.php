<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UTS — Noticias y Eventos</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root {
  --verde:#4a7c25; --verde-dark:#3a5a1e; --verde-light:#a8d060;
  --verde-bg:#f0f7e8; --gris-bg:#f4f6f8; --gris-borde:#e0e4ea;
  --texto:#1a2530; --texto-sub:#5a6a78;
}
* { box-sizing:border-box; margin:0; padding:0; }
body { font-family:'DM Sans',sans-serif; background:var(--gris-bg); color:var(--texto); }

.topbar {
  background:white; border-bottom:1px solid var(--gris-borde);
  padding:0 32px; height:60px;
  display:flex; align-items:center; justify-content:space-between;
  position:sticky; top:0; z-index:100;
  box-shadow:0 2px 8px rgba(0,0,0,.05);
}
.topbar-logo { display:flex; align-items:center; gap:12px; }
.logo-box {
  width:38px; height:38px; background:var(--verde);
  border-radius:10px; display:flex; align-items:center;
  justify-content:center; color:white; font-weight:700; font-size:14px;
}
.logo-nombre { font-weight:700; font-size:16px; }
.logo-sub { font-size:11px; color:var(--texto-sub); }
.btn-login-nav {
  background:var(--verde); color:white; border:none;
  padding:9px 20px; border-radius:8px; font-family:'DM Sans',sans-serif;
  font-size:13px; font-weight:600; cursor:pointer; text-decoration:none;
  transition:background .15s;
}
.btn-login-nav:hover { background:var(--verde-dark); }

.hero {
  background:linear-gradient(135deg, var(--verde) 0%, var(--verde-dark) 100%);
  color:white; text-align:center; padding:64px 20px 52px;
}
.hero h1 { font-size:36px; font-weight:700; margin-bottom:10px; }
.hero p { font-size:15px; opacity:.8; max-width:520px; margin:0 auto 28px; }
.hero-btns { display:flex; gap:12px; justify-content:center; flex-wrap:wrap; }
.btn-hero-p {
  background:white; color:var(--verde); padding:12px 26px;
  border-radius:10px; font-weight:700; font-size:14px; text-decoration:none;
  transition:opacity .15s;
}
.btn-hero-p:hover { opacity:.9; }
.btn-hero-s {
  background:rgba(255,255,255,.15); color:white; padding:12px 26px;
  border-radius:10px; font-weight:600; font-size:14px; text-decoration:none;
  border:1px solid rgba(255,255,255,.3); transition:background .15s;
}
.btn-hero-s:hover { background:rgba(255,255,255,.25); }

.page { max-width:960px; margin:36px auto; padding:0 20px 60px; }

.seccion-titulo {
  font-size:13px; font-weight:700; color:var(--texto-sub);
  text-transform:uppercase; letter-spacing:.5px; margin-bottom:16px;
}

.noticias-grid {
  display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr));
  gap:16px; margin-bottom:36px;
}
.noticia-card {
  background:white; border-radius:16px;
  box-shadow:0 2px 12px rgba(0,0,0,.07); overflow:hidden;
  transition:transform .15s, box-shadow .15s;
}
.noticia-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(74,124,37,.15); }
.noticia-banner {
  height:110px; display:flex; align-items:center; justify-content:center; font-size:46px;
}
.noticia-body { padding:18px; }
.noticia-tag {
  display:inline-block; background:var(--verde-bg); color:var(--verde);
  font-size:10px; font-weight:700; padding:3px 9px; border-radius:99px;
  text-transform:uppercase; letter-spacing:.5px; margin-bottom:8px;
}
.noticia-titulo { font-size:15px; font-weight:700; margin-bottom:6px; line-height:1.4; }
.noticia-texto { font-size:13px; color:var(--texto-sub); line-height:1.6; }
.noticia-fecha { font-size:11px; color:#bbb; margin-top:10px; }

.eventos-lista { display:flex; flex-direction:column; gap:12px; margin-bottom:36px; }
.evento-item {
  background:white; border-radius:12px; padding:16px 20px;
  box-shadow:0 2px 12px rgba(0,0,0,.07);
  display:flex; align-items:center; gap:16px;
}
.evento-fecha-box {
  background:var(--verde-bg); color:var(--verde);
  border-radius:10px; padding:10px 14px; text-align:center; min-width:56px; flex-shrink:0;
}
.evento-dia { font-size:22px; font-weight:700; line-height:1; }
.evento-mes { font-size:10px; font-weight:600; text-transform:uppercase; }
.evento-titulo { font-size:14px; font-weight:600; }
.evento-lugar { font-size:12px; color:var(--texto-sub); margin-top:3px; }
.evento-tipo {
  margin-left:auto; background:var(--verde-bg); color:var(--verde);
  font-size:11px; font-weight:600; padding:4px 10px; border-radius:99px; white-space:nowrap;
}

.cta-banner {
  background:linear-gradient(135deg, var(--verde) 0%, var(--verde-dark) 100%);
  border-radius:18px; padding:32px 36px;
  display:flex; align-items:center; justify-content:space-between; gap:20px; flex-wrap:wrap;
}
.cta-text h2 { color:white; font-size:20px; font-weight:700; margin-bottom:6px; }
.cta-text p { color:rgba(255,255,255,.75); font-size:13px; }
.cta-hint {
  background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.2);
  border-radius:10px; padding:14px 18px; color:white; font-size:13px; line-height:1.8;
}
.cta-hint strong { display:block; margin-bottom:4px; font-size:12px; opacity:.8; text-transform:uppercase; letter-spacing:.5px; }
.cta-hint code {
  background:rgba(255,255,255,.2); padding:2px 8px;
  border-radius:5px; font-family:monospace; font-size:13px;
}
.btn-cta {
  background:white; color:var(--verde); padding:13px 30px;
  border-radius:10px; font-weight:700; font-size:14px;
  text-decoration:none; white-space:nowrap; transition:opacity .15s;
}
.btn-cta:hover { opacity:.9; }

footer {
  text-align:center; font-size:11px; color:var(--texto-sub);
  padding:24px 0; border-top:1px solid var(--gris-borde); margin-top:40px;
}
</style>
</head>
<body>

<!-- BARRA SUPERIOR -->
<nav class="topbar">
  <div class="topbar-logo">
    <div class="logo-box">UTS</div>
    <div>
      <div class="logo-nombre">Sistema Estudiantil UTS</div>
      <div class="logo-sub">Unidades Tecnológicas de Santander</div>
    </div>
  </div>
  <a href="index.php" class="btn-login-nav">Iniciar sesión</a>
</nav>

<!-- HERO -->
<div class="hero">
  <h1>Portal Estudiantil UTS</h1>
  <p>Noticias, eventos académicos e información institucional de las Unidades Tecnológicas de Santander.</p>
  <div class="hero-btns">
    <a href="index.php" class="btn-hero-p">Iniciar sesión</a>
    <a href="#noticias" class="btn-hero-s">Ver noticias ↓</a>
  </div>
</div>

<!-- CONTENIDO -->
<div class="page">

  <!-- NOTICIAS -->
  <p class="seccion-titulo" id="noticias">📰 Noticias institucionales</p>
  <div class="noticias-grid">

    <div class="noticia-card">
      <div class="noticia-banner" style="background:#e8f5e9;">🎓</div>
      <div class="noticia-body">
        <span class="noticia-tag">Académico</span>
        <div class="noticia-titulo">Apertura de inscripciones 2026-2</div>
        <div class="noticia-texto">El periodo de inscripción de materias para el segundo semestre de 2026 estará habilitado del 2 al 16 de junio. Ingresa al sistema para inscribir tus créditos.</div>
        <div class="noticia-fecha">📅 26 de mayo de 2026</div>
      </div>
    </div>

    <div class="noticia-card">
      <div class="noticia-banner" style="background:#fff8e1;">🏆</div>
      <div class="noticia-body">
        <span class="noticia-tag">Logros</span>
        <div class="noticia-titulo">Estudiantes UTS destacan en competencia nacional de software</div>
        <div class="noticia-texto">Un grupo de estudiantes del programa de Tecnología en Desarrollo de Software obtuvo el segundo lugar en el Hackathon Nacional Universitario 2026 celebrado en Bogotá.</div>
        <div class="noticia-fecha">📅 20 de mayo de 2026</div>
      </div>
    </div>

    <div class="noticia-card">
      <div class="noticia-banner" style="background:#e3f2fd;">📡</div>
      <div class="noticia-body">
        <span class="noticia-tag">Infraestructura</span>
        <div class="noticia-titulo">Actualización de la red inalámbrica del campus</div>
        <div class="noticia-texto">La UTS finalizó la instalación de 80 nuevos puntos de acceso WiFi 6 en todos los bloques del campus, mejorando la cobertura y velocidad de conexión para estudiantes y docentes.</div>
        <div class="noticia-fecha">📅 15 de mayo de 2026</div>
      </div>
    </div>

    <div class="noticia-card">
      <div class="noticia-banner" style="background:#fce4ec;">🤝</div>
      <div class="noticia-body">
        <span class="noticia-tag">Convenios</span>
        <div class="noticia-titulo">Nuevo convenio con empresas del sector TI de Bucaramanga</div>
        <div class="noticia-texto">La UTS firmó convenios de práctica profesional con 5 empresas tecnológicas de la región, ofreciendo más de 120 cupos para estudiantes de últimos semestres en 2026.</div>
        <div class="noticia-fecha">📅 10 de mayo de 2026</div>
      </div>
    </div>

    <div class="noticia-card">
      <div class="noticia-banner" style="background:#f3e5f5;">🔬</div>
      <div class="noticia-body">
        <span class="noticia-tag">Investigación</span>
        <div class="noticia-titulo">Semillero de investigación recibe financiación de Minciencias</div>
        <div class="noticia-texto">El semillero InnovaUTS fue seleccionado para recibir financiación del Ministerio de Ciencias por su proyecto sobre aplicaciones de IA para la industria agroindustrial de Santander.</div>
        <div class="noticia-fecha">📅 5 de mayo de 2026</div>
      </div>
    </div>

    <div class="noticia-card">
      <div class="noticia-banner" style="background:#e0f7fa;">📚</div>
      <div class="noticia-body">
        <span class="noticia-tag">Bienestar</span>
        <div class="noticia-titulo">Biblioteca amplía horario y recursos digitales</div>
        <div class="noticia-texto">La biblioteca de la UTS extendió su horario hasta las 9 p.m. y habilitó acceso gratuito a más de 5.000 libros digitales a través del portal institucional para todos los estudiantes activos.</div>
        <div class="noticia-fecha">📅 28 de abril de 2026</div>
      </div>
    </div>

  </div>

  <!-- EVENTOS -->
  <p class="seccion-titulo">📅 Próximos eventos</p>
  <div class="eventos-lista">

    <div class="evento-item">
      <div class="evento-fecha-box"><div class="evento-dia">02</div><div class="evento-mes">Jun</div></div>
      <div>
        <div class="evento-titulo">Inicio inscripción de materias 2026-2</div>
        <div class="evento-lugar">📍 Portal estudiantil — En línea</div>
      </div>
      <span class="evento-tipo">Académico</span>
    </div>

    <div class="evento-item">
      <div class="evento-fecha-box"><div class="evento-dia">06</div><div class="evento-mes">Jun</div></div>
      <div>
        <div class="evento-titulo">Feria de empresas y prácticas profesionales</div>
        <div class="evento-lugar">📍 Auditorio principal — Campus UTS</div>
      </div>
      <span class="evento-tipo">Empleabilidad</span>
    </div>

    <div class="evento-item">
      <div class="evento-fecha-box"><div class="evento-dia">13</div><div class="evento-mes">Jun</div></div>
      <div>
        <div class="evento-titulo">Taller de ciberseguridad — Ethical Hacking básico</div>
        <div class="evento-lugar">📍 Sala de sistemas Bloque C</div>
      </div>
      <span class="evento-tipo">Capacitación</span>
    </div>

    <div class="evento-item">
      <div class="evento-fecha-box"><div class="evento-dia">20</div><div class="evento-mes">Jun</div></div>
      <div>
        <div class="evento-titulo">Exposición de proyectos de grado 2026-1</div>
        <div class="evento-lugar">📍 Coliseo cubierto UTS</div>
      </div>
      <span class="evento-tipo">Grados</span>
    </div>

    <div class="evento-item">
      <div class="evento-fecha-box"><div class="evento-dia">27</div><div class="evento-mes">Jun</div></div>
      <div>
        <div class="evento-titulo">Ceremonia de grados — Promoción 2026</div>
        <div class="evento-lugar">📍 Teatro Santander — Bucaramanga</div>
      </div>
      <span class="evento-tipo">Grados</span>
    </div>

  </div>

  <!-- BANNER LOGIN -->
  <div class="cta-banner">
    <div class="cta-text">
      <h2>¿Ya eres estudiante UTS?</h2>
      <p>Ingresa al sistema para inscribir materias, consultar tu resumen académico y más.</p>
    </div>
    <div class="cta-hint">
      <strong>🧪 Usuario de prueba</strong>
      Código: <code>1005678</code> &nbsp;·&nbsp; Contraseña: <code>1234</code>
    </div>
    <a href="index.php" class="btn-cta">Iniciar sesión →</a>
  </div>

</div>

<footer>© 2026 Unidades Tecnológicas de Santander · Bucaramanga, Colombia</footer>

<script>
  // Si ya hay sesión activa, redirigir directo a home
  if (sessionStorage.getItem('estudiante_id')) {
    window.location.href = 'home.php';
  }
</script>

</body>
</html>

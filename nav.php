<!-- nav.php — incluir en cada página con: <?php include 'nav.php'; ?> -->
<nav class="navbar">
  <div class="nav-brand">
    <div class="nav-logo">UTS</div>
    <div>
      <div class="nav-titulo">Sistema Estudiantil</div>
      <div class="nav-sub">Semestre 2026-1</div>
    </div>
  </div>

  <div class="nav-links">
    <a href="home.php"       class="nav-link" data-page="home">🏠 Inicio</a>
    <a href="inscripcion.php" class="nav-link" data-page="inscripcion">📋 Inscripción</a>
    <a href="resumen.php"    class="nav-link" data-page="resumen">📊 Resumen</a>
  </div>

  <div class="nav-right">
    <div class="nav-est">
      <div class="nav-avatar" id="nav-avatar">JP</div>
      <div>
        <div class="nav-nombre" id="nav-nombre">Cargando...</div>
        <div class="nav-codigo" id="nav-codigo"></div>
      </div>
    </div>
    <button class="btn-logout" onclick="logout()">Salir</button>
  </div>
</nav>

<style>
  .navbar {
    background: #3a5a1e;
    padding: 0 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    height: 62px;
    position: sticky;
    top: 0;
    z-index: 200;
    box-shadow: 0 2px 10px rgba(0,0,0,.25);
  }
  .nav-brand { display: flex; align-items: center; gap: 10px; margin-right: 12px; }
  .nav-logo {
    width: 38px; height: 38px;
    background: #a8d060;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; color: #3a5a1e; font-size: 13px;
  }
  .nav-titulo { color: white; font-size: 15px; font-weight: 600; }
  .nav-sub    { color: rgba(255,255,255,.5); font-size: 11px; }

  .nav-links { display: flex; gap: 4px; flex: 1; }
  .nav-link {
    color: rgba(255,255,255,.7);
    text-decoration: none;
    padding: 8px 14px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    transition: all .15s;
    white-space: nowrap;
  }
  .nav-link:hover    { background: rgba(255,255,255,.1); color: white; }
  .nav-link.activo   { background: rgba(168,208,96,.2); color: #a8d060; font-weight: 600; }

  .nav-right { display: flex; align-items: center; gap: 12px; margin-left: auto; }
  .nav-est   { display: flex; align-items: center; gap: 8px; }
  .nav-avatar {
    width: 32px; height: 32px; border-radius: 50%;
    background: #a8d060;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; color: #3a5a1e; font-size: 12px;
  }
  .nav-nombre { color: white; font-size: 13px; font-weight: 500; }
  .nav-codigo { color: rgba(255,255,255,.5); font-size: 11px; }

  .btn-logout {
    background: rgba(255,255,255,.12);
    color: rgba(255,255,255,.8);
    border: 1px solid rgba(255,255,255,.15);
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 12px;
    cursor: pointer;
    font-family: 'DM Sans', sans-serif;
    transition: all .15s;
  }
  .btn-logout:hover { background: rgba(255,255,255,.2); color: white; }

  @media (max-width: 700px) {
    .nav-sub, .nav-codigo, .nav-titulo { display: none; }
    .nav-links { gap: 2px; }
    .nav-link  { padding: 7px 10px; font-size: 12px; }
  }
</style>

<script>
  // Marcar enlace activo
  (function(){
    const page = document.querySelector('meta[name="pagina"]')?.content;
    if (page) {
      document.querySelectorAll('.nav-link').forEach(a => {
        if (a.dataset.page === page) a.classList.add('activo');
      });
    }

    // Cargar datos del estudiante desde sessionStorage
    const nombre  = sessionStorage.getItem('estudiante_nombre') || '';
    const codigo  = sessionStorage.getItem('estudiante_codigo') || '';
    const programa= sessionStorage.getItem('estudiante_programa') || '';
    if (!sessionStorage.getItem('estudiante_id')) {
      window.location.href = 'index.php';
      return;
    }
    const iniciales = nombre.split(' ').slice(0,2).map(w=>w[0]).join('');
    document.getElementById('nav-avatar').textContent  = iniciales;
    document.getElementById('nav-nombre').textContent  = nombre;
    document.getElementById('nav-codigo').textContent  = codigo;
  })();

  function logout() {
    sessionStorage.clear();
    window.location.href = 'index.php';
  }
</script>

<?php
// views/partials/nav.php
// Uso: <?php require_once __DIR__ . '/../partials/nav.php'; ?>
// Requiere que $pagina_actual esté definida en la vista que incluye este archivo
$pagina_actual = $pagina_actual ?? '';
?>
<nav class="navbar">
  <div class="nav-brand">
    <div class="nav-logo">UTS</div>
    <div>
      <div class="nav-titulo">Sistema Estudiantil</div>
      <div class="nav-sub">Semestre 2026-1</div>
    </div>
  </div>

  <div class="nav-links">
    <a href="home.php"       class="nav-link <?= $pagina_actual==='home'       ? 'activo':'' ?>">🏠 Inicio</a>
    <a href="inscripcion.php" class="nav-link <?= $pagina_actual==='inscripcion' ? 'activo':'' ?>">📋 Inscripción</a>
    <a href="resumen.php"    class="nav-link <?= $pagina_actual==='resumen'    ? 'activo':'' ?>">📊 Resumen</a>
  </div>

  <div class="nav-right">
    <div class="nav-est">
      <div class="nav-avatar" id="nav-avatar">?</div>
      <div>
        <div class="nav-nombre" id="nav-nombre">Cargando...</div>
        <div class="nav-codigo" id="nav-codigo"></div>
      </div>
    </div>
    <button class="btn-logout" onclick="Auth.cerrarSesion('<?= BASE_URL ?>/public/index.php')">Salir</button>
  </div>
</nav>

<script>
(function(){
  Auth.requerirSesion('<?= BASE_URL ?>/public/index.php');
  const iniciales = Auth.nombre.split(' ').slice(0,2).map(w => w[0]||'').join('').toUpperCase() || '?';
  document.getElementById('nav-avatar').textContent = iniciales;
  document.getElementById('nav-nombre').textContent = Auth.nombre || 'Estudiante';
  document.getElementById('nav-codigo').textContent = Auth.codigo;
})();
</script>

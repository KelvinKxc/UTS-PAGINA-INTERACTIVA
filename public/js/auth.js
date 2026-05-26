// ============================================================
// public/js/auth.js — Helpers de autenticación (sessionStorage)
// ============================================================

const Auth = {
  get id()       { return sessionStorage.getItem('estudiante_id'); },
  get nombre()   { return sessionStorage.getItem('estudiante_nombre') || ''; },
  get codigo()   { return sessionStorage.getItem('estudiante_codigo') || ''; },
  get programa() { return sessionStorage.getItem('estudiante_programa') || ''; },
  get semestre() { return sessionStorage.getItem('estudiante_semestre') || ''; },
  get promedio() { return sessionStorage.getItem('estudiante_promedio') || '—'; },

  guardar(est) {
    sessionStorage.setItem('estudiante_id',       est.id);
    sessionStorage.setItem('estudiante_nombre',   est.nombre);
    sessionStorage.setItem('estudiante_codigo',   est.codigo);
    sessionStorage.setItem('estudiante_programa', est.programa || '');
    sessionStorage.setItem('estudiante_semestre', est.semestre || '');
    sessionStorage.setItem('estudiante_promedio', est.promedio || '—');
  },

  requerirSesion(redirigir = '../public/index.php') {
    if (!this.id) window.location.replace(redirigir);
  },

  cerrarSesion(redirigir = '../public/index.php') {
    sessionStorage.clear();
    window.location.replace(redirigir);
  }
};

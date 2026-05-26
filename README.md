# UTS — Página Interactiva

Sistema estudiantil para inscripción de materias.

## Estructura

```
UTS-PAGINA-INTERACTIVA/
├── config/
│   ├── config.php       ← BASE_URL y ROOT_PATH
│   └── database.php     ← función conectar() + carga .env
├── api/
│   ├── login.php        ← POST { codigo, password }
│   ├── materias.php     ← GET  ?estudiante_id=N
│   ├── inscribir.php    ← POST { estudiante_id, materia_id }
│   └── resumen.php      ← GET  ?estudiante_id=N
├── views/
│   ├── home.php
│   ├── inscripcion.php
│   ├── resumen.php
│   └── partials/
│       ├── head.php
│       └── nav.php
├── public/
│   ├── index.php        ← login (punto de entrada)
│   ├── css/styles.css
│   └── js/auth.js
├── .env                 ← credenciales (NO subir)
├── .env.example
└── .gitignore
```

## Instalación

1. Copia el proyecto en `htdocs/` (XAMPP) o `www/` (Laragon).
2. Crea el archivo `.env` basado en `.env.example`:
   ```
   DB_HOST=localhost
   DB_USER=root
   DB_PASS=
   DB_NAME=uts_matriculas
   ```
3. Ajusta `BASE_URL` en `config/config.php` según tu entorno:
   ```php
   define('BASE_URL', 'http://localhost/UTS-PAGINA-INTERACTIVA');
   ```
4. Importa la base de datos en MySQL.
5. Abre `http://localhost/UTS-PAGINA-INTERACTIVA/public/index.php`.

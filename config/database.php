<?php
// ============================================================
// config/database.php — Conexión a la base de datos
// Lee las credenciales del archivo .env en la raíz del proyecto
// ============================================================

function cargarEnv(string $path): void {
    if (!file_exists($path)) return;
    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $linea) {
        if (str_starts_with(trim($linea), '#')) continue;
        [$clave, $valor] = array_map('trim', explode('=', $linea, 2));
        if (!empty($clave)) $_ENV[$clave] = $valor;
    }
}

// Cargar .env desde la raíz del proyecto (un nivel arriba de /config)
cargarEnv(dirname(__DIR__) . '/.env');

define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'uts_matriculas');
define('DB_PORT', (int)($_ENV['DB_PORT'] ?? 3306));

function conectar(): mysqli {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

    if ($conn->connect_error) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error'   => 'Error de conexión: ' . $conn->connect_error
        ]);
        exit;
    }

    $conn->set_charset('utf8mb4');
    return $conn;
}

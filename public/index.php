<?php

require __DIR__ . '/../framework/Database.php';
$db = new Database();

$routes = require __DIR__ . '/../routes/web.php';

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Limpiar la URI para obtener solo la ruta
$basePath = '/parqueadero-system/public';
if (strpos($requestUri, $basePath) === 0) {
    $requestUri = substr($requestUri, strlen($basePath));
}

// Si está vacía, es la ruta raíz
if (empty($requestUri) || $requestUri === '/') {
    $requestUri = '/';
}

$route = $routes[$requestUri] ?? null;

if ($route) {
    $filePath = __DIR__ . '/../' . $route;
    if (file_exists($filePath)) {
        require $filePath;
    } else {
        http_response_code(404);
        echo "404 Not Found - Archivo no encontrado: " . $route . " en " . $filePath;
    }
} else {
    http_response_code(404);
    echo "404 Not Found - Ruta no definida: " . $requestUri;
}
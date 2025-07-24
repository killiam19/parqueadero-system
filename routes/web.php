<?php

use App\Controllers\AboutController;
use App\Controllers\HomeController;
use App\Controllers\MisReservasController;
use App\Controllers\AuthController;
use Framework\Middleware\Authenticated;
use Framework\Middleware\Guest;

$router->get('/',                      [HomeController::class,     'index']);
$router->get('/about',  [AboutController::class,    'index']);
$router->get('/mis-reservas',          [MisReservasController::class,   'index']);
$router->post('/mis-reservas/store',   [MisReservasController::class, 'store']);

$router->get('/login',  [AuthController::class, 'login'],        Guest::class);
$router->post('/login', [AuthController::class, 'authenticate'], Guest::class);
$router->post('/logout', [AuthController::class, 'logout'],      Authenticated::class);
return [
    '/'                     => 'app/Controllers/home.php',
    '/mis_reservas'         => 'app/Controllers/mis_reservas.php',
    '/about'                => 'app/Controllers/about.php',
    '/links'                => 'app/Controllers/links.php',
    '/links/create'         => 'app/Controllers/links-create.php',
    '/usuarios'             => 'app/Controllers/usuarios.php',
    '/admin'                => 'app/Controllers/admin.php',
    '/login'                => 'app/Controllers/login.php',
    '/logout'               => 'app/Controllers/logout.php',
];
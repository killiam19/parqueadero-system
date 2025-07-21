<?php

use App\Controllers\HomeController;
use App\Controllers\MisReservasController;
use App\Controllers\LinkController;
use App\Controllers\PostController;

$router->get('/',       [HomeController::class,     'index']);
$router->get('/mis-reservas',   [MisReservasController::class,   'index']);
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
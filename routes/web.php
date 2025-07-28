<?php

use App\Controllers\AboutController;
use App\Controllers\HomeController;
use App\Controllers\MisReservasController;
use App\Controllers\AuthController;
use App\Controllers\UsuariosController;
use Framework\Middleware\Authenticated;
use Framework\Middleware\Guest;

// Rutas principales
$router->get('/',                      [HomeController::class,     'index']);
$router->post('/',                     [HomeController::class,     'store']);
$router->get('/about',                 [AboutController::class,    'index']);

// Rutas de reservas - 
$router->get('/mis-reservas',          [MisReservasController::class, 'index']);
$router->post('/mis-reservas',         [MisReservasController::class, 'index']); // Para manejar cancelaciones
$router->post('/mis-reservas/store',   [MisReservasController::class, 'store']);

// Rutas de usuarios
$router->get('/usuarios',              [UsuariosController::class, 'index']);

// Rutas de autenticación
$router->get('/login',                 [AuthController::class, 'login'],        Guest::class);
$router->get('/registrar',              [AuthController::class,'registrar']);
$router->post('/login',                [AuthController::class, 'authenticate'], Guest::class);
$router->post('/logout',               [AuthController::class, 'logout'],       Authenticated::class);

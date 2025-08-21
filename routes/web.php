<?php

use App\Controllers\AboutController;
use App\Controllers\HomeController;
use App\Controllers\MisReservasController;
use App\Controllers\AuthController;
use App\Controllers\UsuariosController;
use App\Controllers\AdminController;
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
$router->get('/usuarios',      [UsuariosController::class, 'index']);

// Rutas de autenticación
$router->get('/login',         [AuthController::class, 'login'],        Guest::class);
$router->post('/login',        [AuthController::class, 'authenticate'], Guest::class);
$router->post('/logout',       [AuthController::class, 'logout'],       Authenticated::class);

// Rutas para registro
$router->get('/register',    [AuthController::class, 'register']);
$router->post('/register',   [AuthController::class, 'store']);
$router->get('/reglamento',  [AuthController::class, 'reglamento']);

// Rutas de administración
$router->get('/admin',            [AdminController::class, 'index'],       Authenticated::class);
$router->post('/admin',           [AdminController::class, 'index'],       Authenticated::class);
$router->get('/usuarios',         [AdminController::class, 'usuarios'],    Authenticated::class);
$router->get('/admin/usuarios',   [AdminController::class, 'usuarios'],    Authenticated::class);
$router->post('/admin/usuarios',  [AdminController::class, 'usuarios'],    Authenticated::class);

//Rutas para usuario
$router->get('/configuracion',                     [UsuariosController::class, 'configuracion']);
$router->get('/configuracion/cambiar-password',    [UsuariosController::class, 'cambiarContraseña']);
$router->put('/configuracion/cambiar-password',    [UsuariosController::class, 'passwordUpdate']);
$router->get('/cuenta',                            [UsuariosController::class, 'cuenta']);
$router->put('/cuenta',                            [UsuariosController::class, 'cuenta']);
$router->get('/configuracion/editar-cuenta',                     [UsuariosController::class, 'editarCuenta']);

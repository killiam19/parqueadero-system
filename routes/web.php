<?php

use App\Controllers\AboutController;
use App\Controllers\HomeController;
use App\Controllers\LinkController;
use App\Controllers\PostController;

return [
    '/'                     => 'app/Controllers/home.php',
    '/mis-reservas'         => 'app/Controllers/mis-reservas.php',
    '/about'                => 'app/Controllers/about.php',
    '/links'                => 'app/Controllers/links.php',
    '/links/create'         => 'app/Controllers/links-create.php',
    '/usuarios'             => 'app/Controllers/usuarios.php',
    '/admin'                => 'app/Controllers/admin.php',
    '/login'                => 'app/Controllers/login.php',
    '/logout'               => 'app/Controllers/logout.php',
];
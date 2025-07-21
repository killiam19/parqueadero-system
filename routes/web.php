<?php

<<<<<<< HEAD
use App\Controllers\AboutController;
use App\Controllers\HomeController;
use App\Controllers\LinkController;
use App\Controllers\PostController;

return [
    '/'                     => 'app/Controllers/home.php',
    '/mis-reservas'         => 'app/Controllers/mis-reservas.php',
=======
return [
    '/'                     => 'app/Controllers/home.php',
    '/mis_reservas'         => 'app/Controllers/mis_reservas.php',
>>>>>>> 9e48f2e7c5ea5190f470d242aab08f9db2d5bf14
    '/about'                => 'app/Controllers/about.php',
    '/links'                => 'app/Controllers/links.php',
    '/links/create'         => 'app/Controllers/links-create.php',
    '/usuarios'             => 'app/Controllers/usuarios.php',
    '/admin'                => 'app/Controllers/admin.php',
    '/login'                => 'app/Controllers/login.php',
    '/logout'               => 'app/Controllers/logout.php',
];
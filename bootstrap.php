<?php

session_start();

require __DIR__ . '/vendor/autoload.php';

use Framework\Router;

$router = new Router();

require __DIR__ . '/routes/web.php';
<?php

namespace App\Controllers;

class UsuariosController
{
    public function index()
    {
        view('usuarios',[
            'title' => 'Usuarios',
        ]);
    }
}


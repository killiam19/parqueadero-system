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

    public function cuenta()
    {
        view('cuenta',[
            'title' => 'Mi Cuenta',
            ]);
    }

    public function configuracion()
    {
        view('configuracion',[
            'title' => 'Configuracion',
            ]);
    }
}


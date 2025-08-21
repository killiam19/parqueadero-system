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

    public function editarCuenta()
    {
        view('editar-cuenta',[
            'title' => 'Editar Cuenta',
            ]);
    }

    public function cambiarContraseña()
    {
        view('cambiar-password',[
            'title' => 'Editar Contraseña',
            ]);
    }
}


<?php

namespace App\Controllers;

class MisReservasController
{
    public function index()
    {
        view('mis-reservas',[
            'title' => 'Mis reservas',
        ]);
    }
}


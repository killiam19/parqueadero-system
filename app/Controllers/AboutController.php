<?php

namespace App\Controllers;

class AboutController
{
    public function index()
    {
        view('about',[
            'title' => 'Sobre mi',
        ]);
    }
}


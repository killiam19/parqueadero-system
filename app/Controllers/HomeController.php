<?php

namespace App\Controllers;

use Framework\Database;

class HomeController
{
    public function index()
    {
     $db = new Database();

       view('home',[
            'title'=> 'Agendar Parqueadero',
        ]);
    }
}


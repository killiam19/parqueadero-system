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

    public function store()
    {
        $validator = new Validator($_POST, [
            'title'         => 'required',
            'url'           => 'required',
            'description'   => 'required',
        ]);

        if ($validator->passes()) {
            $db = new Database();

            $db->query(
                'INSERT INTO links (title, url, description) VALUES (:title, :url, :description)',
                [
                    'title'         => $_POST['title'],
                    'url'           =>  $_POST['url'],
                    'description'   => $_POST['description'],
                ]
            );

            header('Location: /links');
            exit;
        } 
        
             view('links-create',[
            'title' => 'Registrar proyecto',
            'errors' => $validator->errors(),
        ]);
    }
}




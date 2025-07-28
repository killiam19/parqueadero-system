<?php

namespace App\Controllers;

use Framework\Authenticate;
use Framework\Validator;

class AuthController
{
    public function login()
    {
        view('login');
    }

    public function authenticate()
    {
         Validator::make($_POST, [
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

          $login = (new Authenticate())->login($_POST['email'],$_POST['password']);

          if(!$login){
            session()->setFlash ('errors', 'Invalid email or password');
            session()->setFlash ('old_email', $_POST['email'] ?? '');

            back();
          }
          redirect('/');
        }


    public function logout()
    {
        (new Authenticate())->logout();
        redirect('/login');
    }

     public function registrar()
    {
        view('registrar',[
            'title' => 'Sobre mi',
        ]);
    }
}
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
            'email'    => 'required|email|domain:3shape.com',
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

 // Mostrar el formulario de registro
public function register()
{
    view('registrar');
}

public function reglamento()
{
    view('reglamento');
}

// Procesar el registro de nuevo usuario
public function store()
{
    // Validar los datos del formulario
    Validator::make($_POST, [
        'name'            => 'required|min:2',
        'email'           => 'required|email|domain:3shape.com',
        'password'        => 'required|min:6',
        'repeat-password' => 'required|min:6',
        'terms'          =>  'required'
    ]);

    // Verificar que las contraseñas coincidan
    if ($_POST['password'] !== $_POST['repeat-password']) {
        session()->setFlash('errors', 'Las contraseñas no coinciden');
        session()->setFlash('old_name', $_POST['name'] ?? '');
        session()->setFlash('old_email', $_POST['email'] ?? '');
        back();
    }

    // Verificar que el email no esté ya registrado
    $existingUser = db()->query('SELECT id FROM usuarios WHERE email = :email', [
        'email' => $_POST['email']
    ])->first();

    if ($existingUser) {
        session()->setFlash('errors', 'Ya existe una cuenta con este correo electrónico');
        session()->setFlash('old_name', $_POST['name'] ?? '');
        session()->setFlash('old_email', $_POST['email'] ?? '');
        back();
    }

    // Crear el nuevo usuario
    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    db()->query('INSERT INTO usuarios (nombre, email, password, fecha_registro) VALUES (:nombre, :email, :password, NOW())', [
        'nombre'     => $_POST['name'],
        'email'    => $_POST['email'],
        'password' => $hashedPassword
    ]);

    // Obtener el usuario recién creado para iniciar sesión
    $newUser = db()->query('SELECT * FROM usuarios WHERE email = :email', [
        'email' => $_POST['email']
    ])->first();

    // Iniciar sesión automáticamente después del registro
    session()->set('user', [
        'id'    => $newUser['id'],
        'email' => $newUser['email'],
        'name'  => $newUser['nombre']
    ]);

    // Redirigir al usuario a la página principal
    redirect('/');
}
}
<?php

use Core\Authenticator;
use Http\Forms\LoginForm;

$email = $_POST['email'];
$password = $_POST['password'];

// Server-side form validation
$form = LoginForm::validate([
    'email' => $email,
    'password' => $password,
]);

// User Authentication
$signedIn = (new Authenticator)->attempt($email, $password);
if (!$singedIn) {
    $form->error(
        'password',
        'No matching account found for that email address and password.'
    )->throw();
}

$previousPage = $_POST['previousPage'] ?? "/";
dd($previousPage);
redirect($previousPage);
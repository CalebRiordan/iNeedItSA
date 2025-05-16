<?php

use Core\Authenticator;
use Http\Forms\LoginForm;

$email = $_POST['email'];
$password = $_POST['password'];
$persistentLogin = $_POST['persist-login'] ?? false; 

// Server-side form validation
$form = LoginForm::validate([
    'email' => $email,
    'password' => $password,
]);

// User Authentication
$auth = new Authenticator();
$signedIn = $auth->attempt($email, $password);
if (!$singedIn) {
    $form->error(
        'password',
        'No matching account found for that email address and password.'
    )->throw();
}
if ($persistentLogin){
    $auth->setPersistentLoginCookie($email);
}

$previousPage = $_POST['previousPage'] ?? "/";
dd($previousPage);
redirect($previousPage);
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

// Staff Authentication
$auth = new Authenticator();
$signedIn = $auth->attempt($email, $password);
if (!$signedIn) {
    $form->error(
        'password',
        'No matching account found for that email address and password.'
    )->throw();
}

redirect("/admin");
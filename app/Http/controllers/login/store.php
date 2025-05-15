<?php

// Get form $_POST values

use Http\Forms\LoginForm;

$email = $_POST['email'];
$password = $_POST['password'];

// validate form input on server side
$form = LoginForm::validate([
    'email' => $email,
    'password' => $password,
]);

// authenticate user

// redirect
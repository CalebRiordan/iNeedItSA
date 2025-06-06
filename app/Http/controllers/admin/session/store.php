<?php

use Core\StaffAuthenticator;
use Http\Forms\LoginForm;

$email = $_POST['email'];
$password = $_POST['password'];

// Server-side form validation
$form = LoginForm::validate([
    'email' => $email,
    'password' => $password,
]);

// Staff Authentication
// Critical: Use of user Authenticator will allow users to access the admin dashboard! 
$auth = new StaffAuthenticator();
$signedIn = $auth->attempt($email, $password);
if (!$signedIn) {
    $form->error(
        'password',
        'No matching account found for that email address and password.'
    )->throw();
}

redirect("/admin");
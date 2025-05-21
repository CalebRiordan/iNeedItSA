<?php

use Core\Authenticator;
use Core\DTOs\CreateUserDTO;
use Core\DTOs\LoginDTO;
use Core\Repositories\UserRepository;
use Http\Forms\LoginForm;
use Http\Forms\RegistrationForm;

$email = $_POST['email'];

// Server-side form validation
$form = LoginForm::validate([
    'email' => $email,
    'password' => $password,
]);

$users = new UserRepository();
$user = $users->findByEmail($email);

if ($user) {
    redirect('/login');
} else {
    $fields = [
        "firstName"      => $_POST["first_name"],
        "lastName"       => $_POST["last_name"],
        "email"          => $_POST["email"],
        "password"       => $_POST["password"],
        "phoneNo"        => $_POST["phone_no"],
        "location"       => $_POST["location"],
        "province"       => $_POST["province"],
        "address"        => $_POST["address"],
        "profilePic"  => $_FILES["profile_pic"],
        "shipAddress"    => $_POST["shipping_address"] ?? null,
    ];

    $form = RegistrationForm::validate($fields);

    $user = new CreateUserDTO(
        $fields["firstName"],
        $fields["lastName"],
        $fields["email"],
        $fields["password"],
        $fields["phoneNo"],
        $fields["location"],
        $fields["province"],
        $fields["address"],
        $fields["profilePic"],
        $fields["shipAddress"]
    );

    $users = new UserRepository();
    $user = $users->create($user);

    if ($user) {
        $user = LoginDTO::fromUserDto($user);
        $auth = new Authenticator();
        $auth->login($user);
        redirect('/');
    }

    abort(505);
}

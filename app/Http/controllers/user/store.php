<?php

use Core\Authenticator;
use Core\DTOs\CreateUserDTO;
use Core\Repositories\UserRepository;
use Http\Forms\Form;
use Http\Forms\RegistrationForm;

$email = $_POST['email'];

$users = new UserRepository();
$user = $users->findByEmail($email);

if ($user) {
    redirect("/login?email={$email}");
} else {
    $fields = [
        "firstName" => Form::getField('first_name'),
        "lastName" => Form::getField('last_name'),
        "email" => Form::getField('email'),
        "password" => Form::getField('password'),
        "phoneNo" => Form::getField('phone_no'),
        "location" => Form::getField('location'),
        "province" => Form::getField('province'),
        "address" => Form::getField('address'),
        "profilePic" => Form::getImageField('profile_pic') ? $_FILES["profile_pic"] : null,
        "shipAddress" => Form::getField('ship_address'),
    ];

    // Server-side form validation
    RegistrationForm::validate($fields);

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

    $user = $users->create($user);

    if (!$user) abort(500);

    (new Authenticator())->login($user);

    $previousPage = $_POST['previousPage'] ?? "/";
    redirect($previousPage);
}

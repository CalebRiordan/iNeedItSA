<?php

use Core\Authenticator;
use Core\DTOs\CreateUserDTO;
use Core\Repositories\UserRepository;
use Http\Forms\RegistrationForm;

$email = $_POST['email'];

$users = new UserRepository();
$user = $users->findByEmail($email);

if ($user) {
    redirect("/login?email={$email}");
} else {
    $fields = [
        "firstName" => htmlspecialchars($_POST["first_name"], ENT_QUOTES),
        "lastName" => htmlspecialchars($_POST["last_name"], ENT_QUOTES),
        "email" => htmlspecialchars($_POST["email"], ENT_QUOTES),
        "password" => htmlspecialchars($_POST["password"], ENT_QUOTES),
        "phoneNo" => htmlspecialchars($_POST["phone_no"], ENT_QUOTES),
        "location" => htmlspecialchars($_POST["location"], ENT_QUOTES),
        "province" => htmlspecialchars($_POST["province"], ENT_QUOTES),
        "address" => htmlspecialchars($_POST["address"], ENT_QUOTES),
        "profilePic" => noImageUploaded($_FILES["profile_pic"]) ? null : $_FILES["profile_pic"],
        "shipAddress" => htmlspecialchars($_POST["ship_address"], ENT_QUOTES),
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

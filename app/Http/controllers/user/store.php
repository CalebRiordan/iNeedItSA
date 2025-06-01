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
        "first_name" => htmlspecialchars($_POST["first_name"], ENT_QUOTES),
        "last_name" => htmlspecialchars($_POST["last_name"], ENT_QUOTES),
        "email" => htmlspecialchars($_POST["email"], ENT_QUOTES),
        "password" => htmlspecialchars($_POST["password"], ENT_QUOTES),
        "phone_no" => htmlspecialchars($_POST["phone_no"], ENT_QUOTES),
        "location" => htmlspecialchars($_POST["location"], ENT_QUOTES),
        "province" => htmlspecialchars($_POST["province"], ENT_QUOTES),
        "address" => htmlspecialchars($_POST["address"], ENT_QUOTES),
        "profile_pic" => noImageUploaded($_FILES["profile_pic"]) ? null : $_FILES["profile_pic"],
        "ship_address" => htmlspecialchars($_POST["ship_address"], ENT_QUOTES),
    ];

    // Server-side form validation
    RegistrationForm::validate($fields);

    $user = new CreateUserDTO(
        $fields["first_name"],
        $fields["last_name"],
        $fields["email"],
        $fields["password"],
        $fields["phone_no"],
        $fields["location"],
        $fields["province"],
        $fields["address"],
        $fields["profile_pic"],
        $fields["ship_address"]
    );

    $user = $users->create($user);

    if (!$user) abort(500);

    (new Authenticator())->login($user);

    $previousPage = $_POST['previousPage'] ?? "/";
    redirect($previousPage);
}

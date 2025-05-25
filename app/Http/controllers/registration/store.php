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
        "firstName"      => $_POST["first_name"],
        "lastName"       => $_POST["last_name"],
        "email"          => $_POST["email"],
        "password"       => $_POST["password"],
        "phoneNo"        => $_POST["phone_no"],
        "location"       => $_POST["location"],
        "province"       => $_POST["province"],
        "address"        => $_POST["address"],
        "profilePic"  => noImageUploaded($_FILES["profile_pic"]) ? null : $_FILES["profile_pic"],
        "shipAddress"    => $_POST["ship_address"] ?? null,
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

<?php

use Core\DTOs\UpdateUserDTO;
use Core\Repositories\UserRepository;
use Core\Session;
use Http\Forms\EditUserForm;

$id = $_POST['user_id'] ?? null;
$users = new UserRepository();

if (!$id || !$users->exists($id)) {
    abort();
}

$fields = [
    'firstName' => htmlspecialchars($_POST['first_name'], ENT_QUOTES),
    'lastName' => htmlspecialchars($_POST['last_name'], ENT_QUOTES),
    'phoneNo' => htmlspecialchars($_POST['phone_no'], ENT_QUOTES),
    'location' => htmlspecialchars($_POST['location'], ENT_QUOTES),
    'province' => htmlspecialchars($_POST['province'], ENT_QUOTES),
    'address' => htmlspecialchars($_POST['address'], ENT_QUOTES),
    'imageChanged' => htmlspecialchars($_POST['image_changed'], ENT_QUOTES),
    'profilePic' => noImageUploaded($_FILES['profile_pic']) ? null : $_FILES['profile_pic'],
    'shipAddress' => htmlspecialchars($_POST['ship_address'], ENT_QUOTES)  ?? null,
];

// Server-side form validation
EditUserForm::validate($fields);

$user = new UpdateUserDTO(
    $id,
    $fields['firstName'],
    $fields["lastName"],
    $fields["phoneNo"],
    $fields["location"],
    $fields["province"],
    $fields["address"],
    $fields["shipAddress"],
    $fields["imageChanged"],
    $fields["profilePic"]
);

if (!$users->update($user)) {
    abort(500);
}

// Update Session
$sessionUser = Session::get('user');
$sessionUser['firstName'] = $user->firstName;
$sessionUser['lastName'] = $user->lastName;
$sessionUser['profilePicUrl'] = $user->profilePicUrl;
Session::put('user', $sessionUser);

$previousPage = $_POST['previousPage'] ?? "/";
redirect($previousPage);

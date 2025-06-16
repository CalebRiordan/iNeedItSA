<?php

use Core\DTOs\UpdateUserDTO;
use Core\Repositories\UserRepository;
use Core\Session;
use Http\Forms\EditUserForm;
use Http\Forms\Form;

$id = $params['id'] ?? null;
$users = new UserRepository();

if (!$id || !$users->exists($id)) {
    abort();
}

$fields = [
    'firstName' => Form::getField('first_name'),
    'lastName' => Form::getField('last_name'),
    'phoneNo' => Form::getField('phone_no'),
    'location' => Form::getField('location'),
    'province' => Form::getField('province'),
    'address' => Form::getField('address'),
    'imageChanged' => Form::getBoolField('image_changed'),
    'profilePic' => Form::getImageField('profile_pic'),
    'shipAddress' => Form::getField('ship_address'),
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
if ($user->imageChanged) {
    $sessionUser['profilePicUrl'] = $user->profilePicUrl;
}
Session::put('user', $sessionUser);

$previousPage = $_POST['previousPage'] ?? "/";
redirect($previousPage);

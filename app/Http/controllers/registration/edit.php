<?php

use Core\Repositories\UserRepository;
use Core\Session;

$user = Session::get("user");

if (!$user){
    die();
}

$user = (new UserRepository())->findById($user['id']);

view('registration/edit', [
    'user' => $user,
    'errors' => Session::get('errors')
]);
 
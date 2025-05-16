<?php

use Core\Session;

redirectIfLoggedIn();

view("session/create", [
    'errors' => Session::get('errors')
]);
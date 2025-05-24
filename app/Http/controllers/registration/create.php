<?php

use Core\Session;

redirectIfLoggedIn();

view('registration/create', [
    'errors' => Session::get('errors')
]);

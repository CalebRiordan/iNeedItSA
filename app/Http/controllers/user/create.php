<?php

use Core\Session;

view('user/create', [
    'errors' => Session::get('errors')
]);

<?php

use Core\Session;

view("product/create", [
    'errors' => Session::get('errors')
]);
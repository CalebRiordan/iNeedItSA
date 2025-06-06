<?php

use Core\Authenticator;
use Core\Router;
use Core\Session;

if (Session::has('user')){
    Authenticator::logout();
}

Router::redirectToPrevious();
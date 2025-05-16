<?php

use Core\Authenticator;
use Core\Router;

if (isset($_SESSION['user'])){
    Authenticator::logout();
}

Router::redirectToPrevious();
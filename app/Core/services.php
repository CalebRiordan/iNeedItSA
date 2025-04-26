<?php

use Core\Container;
use Core\Database;

Container::bind('Core\Database', function() {
    return new Database(env('database'));
});
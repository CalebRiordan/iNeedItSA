<?php

if (explode(':', $_SERVER['HTTP_HOST'])[0] === 'localhost') {
    $environment = "dev";
} elseif ($_SERVER['HTTP_HOST'] === '') { // TODO: Replace with actual hosting platform's domain
    $environment = "prod";
} else {
    throw new Exception("Unknown environment: {$_SERVER['HTTP_HOST']}");
}

return require(__DIR__."/env.{$environment}.php");
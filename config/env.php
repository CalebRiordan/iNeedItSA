<?php

if (explode(':', $_SERVER['HTTP_HOST'])[0] === 'localhost') {
    $environment = "dev";
} elseif ($_SERVER['HTTP_HOST'] === 'ineedit.infinityfree.com') {
    $environment = "prod";
} else {
    throw new Exception("Unknown environment: {$_SERVER['HTTP_HOST']}");
}

return require(__DIR__."/env.{$environment}.php");
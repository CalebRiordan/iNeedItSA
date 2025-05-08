<?php

function base_path($path)
{
    return __DIR__ . '/../../' . $path;
}

function env($key, $default = null) {
    // initialize $env only once
    static $env;
    if (!$env) {
        $env = require base_path('config/env.php');
    }

    return $env[$key] ?? $default;
}

function redirect($path)
{
    header("location: {$path}");
    die();
}

function view($path, $attributes = [])
{
    extract($attributes);

    require base_path('views/' . $path . '.view.php');
}
 
function dd($value)
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";

    die();
}

function abort($code = 404)
{
    http_response_code($code);
    require base_path("views/StatusCodes/{$code}.php");
    die();
}

function returnJson($data = [], int $code = 200){
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
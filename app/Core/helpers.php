<?php

function base_path($path)
{
    return __DIR__ . '/../../' . $path;
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

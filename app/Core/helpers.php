<?php

use Core\Session;

function base_path($path)
{
    return __DIR__ . '/../../' . $path;
}

function env($key, $default = null)
{
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

function partial($name)
{
    return base_path('views/partials/' . $name . '.php');
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
    require base_path("views/StatusCodes/{$code}.view.php");
    die();
}

function elapsedTimeString(DateTime $date): string
{
    $now = new DateTime();
    $interval = $date->diff($now);
    $output = "";

    if ($interval->y > 0 || $interval->m > 0) {
        $output = $interval->y * 12 + $interval->m . ' months ago';
    } elseif ($interval->d >= 7) {
        $output = floor($interval->d / 7) . ' weeks ago';
    } else {
        $output = max(1, $interval->d) . ' days ago';
    }

    if (str_starts_with($output, "1")) {
        $output = str_replace("s ", " ", $output);
    }

    return $output;
}

function noImageUploaded(array $file): bool
{
    return $file['error'] === UPLOAD_ERR_NO_FILE;
}

function validImage(?array $file)
{
    return $file
        && isset($file['tmp_name'])
        && is_uploaded_file($file['tmp_name'])
        && $file['error'] === UPLOAD_ERR_OK;
}

function previousPage(string $default = "/")
{
    return $_SERVER['HTTP_REFERER'] ?? $_GET['previous'] ?? $default;
}

function response(array $data, int $code = 200)
{
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function existingFormData(string $key, string $default = "")
{
    return htmlspecialchars(old($key) ?? $_POST[$key] ?? $default);
}

function old($key, $default = '')
{
    return Core\Session::get('old')[$key] ?? $default;
}

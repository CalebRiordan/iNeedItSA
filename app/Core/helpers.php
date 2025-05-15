<?php

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

function returnJson($data = [], int $code = 200)
{
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function filterProductsParams($params): ?array
{
    $validParams = [];
    
    $val = $params['search'] ?? "";
    $val = htmlspecialchars($val, ENT_QUOTES);
    if (!empty($val)) {
        $validParams['search'] = $val;
    }
    
    $val = $params['category'] ?? null;
    if ($val && ctype_digit((string)$val) && (int)$val >= 0 && (int)$val <= 7) {
        $validParams['category'] = (int)$val;
    }

    $val = $params['minPrice'] ?? null;
    if ($val && is_numeric($val) && (float)$val >= 0) {
        $validParams['minPrice'] = (float)$val;
    }

    $val = $params['maxPrice'] ?? null;
    if ($val && is_numeric($val) && (float)$val >= 0) {
        $validParams['maxPrice'] = (float)$val;
    }

    $val = $params['rating'] ?? null;
    if ($val && ctype_digit((string)$val) && (int)$val >= 1 && (int)$val <= 5) {
        $validParams['rating'] = (int)$val;
    }

    $val = $params['page'] ?? null;
    if ($val && ctype_digit((string)$val) && (int)$val >= 1) {
        $validParams['page'] = (int)$val;
    }

    return $validParams;
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

    if (str_starts_with($output, "1")){
        $output = str_replace("s ", " ", $output);
    }

    return $output;
}


function redirectIfGuest(string $uri = ""){
    if (!isset($_SESSION['user'])){
        header("location: {$uri}");
        exit();
    }
}

function redirectIfLoggedIn(string $uri = ""){
    if (isset($_SESSION['user'])){
        header("location: {$uri}");
        exit();
    }
}
<?php

// Sanitize query params

use Http\Controllers\PartialController;

$params = [];

if (isset($_GET['search']) && $_GET['search'] !== "") {
    $params['search'] = htmlspecialchars($_GET['search']);
} else {
    header("location: /");
    exit();
}
$params = filterProductsParams($_GET);

// dd($params);

$sections = PartialController::renderProductDisplay($params);

$productsDisplay = $sections['products-display'];
$pageSelector = $sections['page-selector'];

view('products/index', [
    'productsDisplay' => $productsDisplay,
    'pageSelector' => $pageSelector,
    'params' => $params
]);

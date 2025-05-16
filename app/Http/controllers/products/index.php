<?php

// Sanitize query params

use Core\Filters\ProductFilter;
use Http\Controllers\PartialController;

$params = ProductFilter::validParams($_GET);

if (!$params){
    header("location: /");
    exit();
}

$sections = PartialController::renderProductDisplay($params);

$productsDisplay = $sections['products-display'];
$pageSelector = $sections['page-selector'];

view('products/index', [
    'productsDisplay' => $productsDisplay,
    'pageSelector' => $pageSelector,
    'params' => $params
]);

<?php

// Sanitize query params

use Http\Controllers\PartialController;

$params = filterProductsParams($_GET);

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

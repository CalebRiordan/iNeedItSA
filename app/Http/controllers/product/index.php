<?php

use Core\Filters\ProductFilter;
use Http\Controllers\PartialController;

// Discard invalid params
$params = ProductFilter::validParams($_GET);

if (!$params) redirect('/');

// Get HTML for product catalogue grid and page selector
try {
    $sections = PartialController::renderProductDisplay($params);
} catch (\Throwable) {
    abort();
}

view('product/index', [
    'productsDisplay' => $sections['products-display'],
    'pageSelector' => $sections['page-selector'],
    'params' => $params
]);

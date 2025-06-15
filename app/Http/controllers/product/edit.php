<?php

use Core\Repositories\ProductRepository;
use Core\Session;

$id = $params['id'] ?? null;

if (!$id) abort();

$products = new ProductRepository();
$product = $products->findById($id);

// Product doesn't belong to this seller -> 404 Not Found (obscurity)
if (!$products->isFromSeller($id, Session::get('user')['id'])) abort();

view("product/edit", [
    'errors' => Session::get('errors'),
    'product' => $product
]);
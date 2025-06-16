<?php

use Core\Authorizor;
use Core\Repositories\ProductRepository;
use Core\Session;

$id = $params['id'] ?? null;

if (!$id) abort();

$products = new ProductRepository();
$product = $products->findById($id);

// Product doesn't belong to this seller -> 404 Not Found (obscurity)
if (!(new Authorizor())->ownsProduct($id)) abort();

view("product/edit", [
    'errors' => Session::get('errors'),
    'product' => $product
]);
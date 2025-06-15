<?php

use Core\Repositories\ProductRepository;
use Core\Session;

$id = $params['id'] ?? null;

if (!$id) abort();

$product = (new ProductRepository())->findById($id);

if (!$product) abort();

view("product/edit", [
    'errors' => Session::get('errors'),
    'product' => $product
]);

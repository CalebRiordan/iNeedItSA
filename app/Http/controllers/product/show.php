<?php

use Core\Repositories\ProductRepository;

$id = $params['id'] ?? null;
if (!$id) abort(404);

$repo = new ProductRepository();
$product = $repo->findById($id);

view("product/show", ["product" => $product]);

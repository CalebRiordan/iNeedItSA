<?php

use Core\Repositories\ProductRepository;

$id = $params['id'] ?? null;
if (!$id) abort(404);

$product = (new ProductRepository())->findById($id);

view("product/show", ["product" => $product]);

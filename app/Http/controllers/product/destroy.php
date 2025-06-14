<?php

use Core\Repositories\ProductRepository;

$id = $params['id'] ?? null;

if (!$id) {
    response(["error" => "Invalid input - 'id' is required"], 400);
}

if (!(new ProductRepository())->delete($id)) {
    response(["error" => "Product not found"], 400);
}

response(['message' => 'Product deleted.']);
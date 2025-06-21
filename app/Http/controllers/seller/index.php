<?php

use Core\Filters\ProductFilter;
use Core\Repositories\ProductRepository;
use Core\Session;

// Get seller's products
$filter = new ProductFilter();
$userId = Session::get('user')['id'] ?? null;
if (!$userId) redirect('/login');
$filter->seller(Session::get('user')['id']);
$products = (new ProductRepository())->findAll($filter);

$seller = Session::get('user')['sellerProfile'];

view("seller/index", ['products' => $products, 'seller' => $seller]);

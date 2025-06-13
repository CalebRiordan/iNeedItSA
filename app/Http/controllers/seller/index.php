<?php

use Core\Filters\ProductFilter;
use Core\Repositories\ProductRepository;
use Core\Session;

// Get seller's products
$filter = new ProductFilter();
$filter->seller(Session::get('user')['id']);
$products = (new ProductRepository())->findAll($filter);

view("seller/index", ['products' => $products]);

<?php

use Core\Filters\ProductFilter;
use Core\Repositories\ProductRepository;

$filter = new ProductFilter();
$filter->build($_GET);

$repository = new ProductRepository();
$products = $repository->findAllPreviews($filter);

view('products/index', ['products' => $products]);

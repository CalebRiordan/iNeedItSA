<?php

use Core\Filters\ProductFilter;
use Core\Repositories\ProductRepository;

// Featured Products
$products = new ProductRepository();
$featuredProducts = $products->getFeaturedProducts();

// Discounted Products Row
$filter = new ProductFilter();
$filter->hasDiscount();
$filter->setLimit(16);
$discountedProducts = $products->findAllPreviews($filter);

// Top Rated Products Row
$products = new ProductRepository();
$filter->reset();
$filter->orderBy('avg_rating', 'DESC');
$filter->setLimit(16);
$topRatedProducts = $products->findAllPreviews($filter);

view('index', [
    'featuredProducts' => $featuredProducts,
    'discountedProducts' => $discountedProducts,
    'topRatedProducts' => $topRatedProducts,
]);

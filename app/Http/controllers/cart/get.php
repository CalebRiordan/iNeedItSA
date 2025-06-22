<?php

use Core\Filters\ProductFilter;
use Core\Session;

$cart = Session::get('cart', []);

$productIds = array_column($cart, 'product_id');

response($cart);
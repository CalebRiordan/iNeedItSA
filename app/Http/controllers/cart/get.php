<?php

use Core\Session;

$cart = Session::get('cart', []); 
response($cart);
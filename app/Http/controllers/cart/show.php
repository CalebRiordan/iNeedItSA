<?php

use Core\Session;

$cart = Session::get('cart', []); 
returnJson($cart);
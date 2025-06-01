<?php

use Core\Session;

// Ensure recent processing of order for security (valid for 5 minutes)
if (Session::has('last_order_processed') && time() - Session::get('last_order_processed')['timestamp'] < 300) {
    view("/order/success", ['clearCart' => true]);
} else {
    redirect("/");
}

<?php

$stylesheets = ['order/index.css'];
$scripts = ['order/index.js'];

require base_path('views/partials/header.php');
require base_path('views/partials/navbar.php');

?>

<main>
    <div class="content-column">

        <div class="cart-wrapper" hidden>
            <h1>Shopping Cart</h1>
            <div class="cart-section">
                <div class="cart">

                    <!-- Cart partial loaded here -->
                </div>
                <div class="checkout-card">
                    <div class="checkout-details">
                        <span><strong>TOTAL:</strong> <span id="total-qty"></span></span>
                        <span id="total-price">R</span>
                    </div>
                    <a href="/checkout" class="checkout-btn">Checkout</a>
                </div>
            </div>
        </div>

        <div class="centre-content">
            <span class="loading"></span>
        </div>

        <div class="cart-empty" hidden>
            <p>Your cart is currently empty.</p>
            <a href="/" class="btn">Browse Products</a>
        </div>

        <?php if (!empty($orders)): ?>
        <div class="orders-wrapper">
            <h1>Order History</h1>
            <div class="orders-section history">
                <?php foreach($orders as $order): ?>
                    <div class="order">
                        <span><?= $order->date ?></span>
                        <span><?= $order->shipAddress ?></span>
                        <span><?= $order->totalCost ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</main>

<?php require base_path('views/partials/footer.php') ?>
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
                <div class="history">
                    <?php foreach ($orders as $order): ?>
                        <div class="order" data-order-id="<?= $order->id ?>">
                            <div class="order-dropdown">
                                <div class="order-summary">
                                    <div class="left">
                                        <span class="status">Collected</span>
                                        <span><?= date('j F Y', strtotime($order->date)) ?></span>
                                        <span class="light-text"><?= $order->shipAddress . ', ' .$order->location  ?></span>
                                    </div>
                                    <div>Total: <strong>R<?= $order->totalCost ?></strong></div>
                                </div>

                                <span class="order-toggle-icon">
                                    <svg class="chevron light-fill" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                        <path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z" />
                                    </svg>
                                </span>
                            </div>

                            <div class="order-items" hidden>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require base_path('views/partials/footer.php') ?>
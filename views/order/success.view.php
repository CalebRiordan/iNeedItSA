<?php

$stylesheets = ['order/success.css'];

require partial('header');
require partial('navbar');
?>

<main>
    <div class="order-success-card">
        <svg class="order-success-icon" xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="none" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="12" fill="#4BB543"/>
            <path d="M7 13l3 3 7-7" stroke="#fff" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <h1>Order Successful!</h1>
        <p>Your order has been placed successfully.</p>
        <p>You will be redirected to the <a href="/">home page</a> in <span id="timer">5</span> seconds.</p>
        <a href="/" class="redirect-btn">Go to Home Now</a>
    </div>
</main>

<script>
    // Clear cart in Local Storage
    if (<?= $clearCart ?? false ?> && localStorage.getItem('cart')) {
        localStorage.removeItem('cart');
    }

    // Redirect after 5 seconds
    let seconds = 5;
    const timer = document.getElementById('timer');
    const interval = setInterval(() => {
        seconds--;
        if (timer) timer.textContent = seconds;
        if (seconds <= 0) {
            clearInterval(interval);
            window.location.href = '/';
        }
    }, 1000);
</script>

<?php require partial('footer') ?>
<?php

use Core\Session;

$stylesheets = ['order/create.css'];
$scripts = ['order/create.js'];

require partial('header');
require partial('navbar');
?>

<main class="order-success-container">
    <div class="order-success-card">
        <svg class="order-success-icon" xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="none" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="12" fill="#4BB543"/>
            <path d="M7 13l3 3 7-7" stroke="#fff" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <h1>Order Successful!</h1>
        <p>Your order has been placed successfully.</p>
        <p>You will be redirected to the <a href="/">home page</a> in <span id="redirect-timer">5</span> seconds.</p>
        <a href="/" class="btn btn-primary">Go to Home Now</a>
    </div>
</main>

<script>
    // Clear cart in Local Storage
    if (<?= $clearCart ?? false ?> && localStorage.getItem('cart')) {
        localStorage.removeItem('cart');
    }

    // Redirect after 5 seconds
    let seconds = 5;
    const timer = document.getElementById('redirect-timer');
    const interval = setInterval(() => {
        seconds--;
        if (timer) timer.textContent = seconds;
        if (seconds <= 0) {
            clearInterval(interval);
            window.location.href = '/';
        }
    }, 1000);
</script>

<style>
.order-success-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 60vh;
    background: #f8f9fa;
}
.order-success-card {
    background: #fff;
    padding: 2.5rem 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    text-align: center;
    max-width: 400px;
    width: 100%;
}
.order-success-icon {
    margin-bottom: 1rem;
}
.btn.btn-primary {
    display: inline-block;
    margin-top: 1.5rem;
    padding: 0.75rem 1.5rem;
    background: #4BB543;
    color: #fff;
    border: none;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    transition: background 0.2s;
}
.btn.btn-primary:hover {
    background: #399c36;
}
</style>

<?php require partial('footer') ?>
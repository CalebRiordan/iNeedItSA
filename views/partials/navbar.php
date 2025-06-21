<?php

use Core\Session;

$userSession = Session::get('user');?>

<nav class="navbar">

    <div class="left">
        <a class="navbar-logo" href="/">
            <img src="/assets/logo-main.png" alt="Store Logo">
        </a>
    </div>

    <?php if ($userSession): ?>
        <div id="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    <?php endif; ?>


    <div class="right <?= $userSession ? 'show-mobile-sidebar' : '' ?>">
        <ul>
            <?php if ($userSession): ?>
                <?php $userSession ?>

                <li class="nav-link">
                    <a class="nav-text" href="/logout">Logout</a>
                </li>

                <li class="nav-link nav-link-mobile">
                    <?php if ($userSession['sellerProfile']): ?>
                        <a class="nav-text" href="/seller/dashboard">Seller Dashboard</a>
                    <?php else: ?>
                        <a class="nav-text" href="/seller/register">Become a seller</a>
                    <?php endif; ?>
                </li>

                <li class="nav-link nav-link-mobile">
                    <a class="nav-text orders-link" href="/order#history">Orders</a>
                </li>

                <li class="nav-link cart-nav-link">
                    <a href="/order#cart">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                            <path d="M0 24C0 10.7 10.7 0 24 0L69.5 0c22 0 41.5 12.8 50.6 32l411 0c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3l-288.5 0 5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5L488 336c13.3 0 24 10.7 24 24s-10.7 24-24 24l-288.3 0c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5L24 48C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z" />
                        </svg>
                        <span class="cart-count" hidden></span>
                    </a><!--
                    --><a class="nav-link orders-link nav-link-mobile" href="/order#cart"><span class="nav-text">Cart</span></a>
                </li>

                <li class="nav-link">
                    <div class="user-profile">
                        <?php $imgUrl = $userSession['profilePicUrl'] ?? null ?>
                        <?php if ($imgUrl && file_exists(base_path("/public{$imgUrl}"))): ?>
                            <div class="img-container">
                                <img src="<?= $imgUrl ?>" alt="Profile Picture">
                            </div>
                        <?php else: ?>
                            <!-- Profile picture placeholder SVG -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path d="M399 384.2C376.9 345.8 335.4 320 288 320l-64 0c-47.4 0-88.9 25.8-111 64.2c35.2 39.2 86.2 63.8 143 63.8s107.8-24.7 143-63.8zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256 16a72 72 0 1 0 0-144 72 72 0 1 0 0 144z" />
                            </svg>
                        <?php endif; ?>
                    </div>
                    <a class="nav-link nav-link-mobile" href="/profile/edit"><span class="nav-text">Edit Profile</span></a>
                </li>

            <?php else: ?>
                <li class="nav-link">
                    <a class="nav-btn" aria-current="page" href="/login">Login</a>
                </li>
                <li class="nav-link">
                    <a class="nav-btn salient" href="/register">Sign up</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<?php require partial('sidebar'); ?>

<div id="navbar-offset"></div>
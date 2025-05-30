<nav class="navbar">

    <div class="left">
        <a class="navbar-logo" href="/">
            <img src="/assets/logo-main.png" alt="Store Logo">
        </a>
    </div>

    <div class="right">
        <ul>
            <?php $SessionUser = $_SESSION['user'] ?? null; ?>
            <?php if ($SessionUser): ?>
                <?php $SessionUser ?>
                <li>
                    <a class="nav-link" href="/logout"><span class="logout">Logout</span></a>
                </li>
                <li>
                    <a class="nav-link cart-nav-link" href="/order#cart">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                            <path d="M0 24C0 10.7 10.7 0 24 0L69.5 0c22 0 41.5 12.8 50.6 32l411 0c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3l-288.5 0 5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5L488 336c13.3 0 24 10.7 24 24s-10.7 24-24 24l-288.3 0c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5L24 48C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z" />
                        </svg>
                        <span class="cart-count" hidden></span>
                    </a>
                </li>
                <li>
                    <a class="nav-link user-profile">
                        <?php if ($SessionUser['profilePicUrl']): ?>
                            <div class="img-container">
                                <img src="<?= $SessionUser['profilePicUrl'] ?>" alt="Profile Picture">
                            </div>
                        <?php else: ?>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path d="M399 384.2C376.9 345.8 335.4 320 288 320l-64 0c-47.4 0-88.9 25.8-111 64.2c35.2 39.2 86.2 63.8 143 63.8s107.8-24.7 143-63.8zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256 16a72 72 0 1 0 0-144 72 72 0 1 0 0 144z" />
                            </svg>
                        <?php endif; ?>
                    </a>
                </li>
            <?php else: ?>
                <li>
                    <a class="nav-link nav-btn log-in" aria-current="page" href="/login">Log in</a>
                </li>
                <li>
                    <a class="nav-link nav-btn sign-up salient" href="/register">Sign up</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<?php require base_path('views/partials/sidebar.php'); ?>

<div id="navbar-offset"></div>
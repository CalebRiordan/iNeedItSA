<?php $scripts[] = "sidebar.js"; ?>

<div class="sidebar">
    <div class="user-info">

        <div class="user-profile">
            <?php if ($SessionUser['profilePicUrl']): ?>
                <div class="img-container">
                    <img src="<?= $SessionUser['profilePicUrl'] ?>" alt="Profile Picture">
                </div>
            <?php else: ?>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path d="M399 384.2C376.9 345.8 335.4 320 288 320l-64 0c-47.4 0-88.9 25.8-111 64.2c35.2 39.2 86.2 63.8 143 63.8s107.8-24.7 143-63.8zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256 16a72 72 0 1 0 0-144 72 72 0 1 0 0 144z" />
                </svg>
            <?php endif; ?>
        </div>

        <div class="name-email-container">
            <p class="name"><?= "{$SessionUser['firstName']} {$SessionUser['lastName']}" ?></p>
            <p class="email"><?= $SessionUser['email'] ?? "" ?></p>
        </div>

        <div class="close-button">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                <path d="M376.6 84.5c11.3-13.6 9.5-33.8-4.1-45.1s-33.8-9.5-45.1 4.1L192 206 56.6 43.5C45.3 29.9 25.1 28.1 11.5 39.4S-3.9 70.9 7.4 84.5L150.3 256 7.4 427.5c-11.3 13.6-9.5 33.8 4.1 45.1s33.8 9.5 45.1-4.1L192 306 327.4 468.5c11.3 13.6 31.5 15.4 45.1 4.1s15.4-31.5 4.1-45.1L233.7 256 376.6 84.5z" />
            </svg>
        </div>
    </div>

    <div class="sidebar-item">
        <a href="/profile/edit">Edit Profile</a>
    </div>

    <div class="sidebar-item">
        <a href="/order#cart">Cart</a>
    </div>

    <div class="sidebar-item">
        <a href="/order#history">Orders</a>
    </div>

    <div class="sidebar-item">
        <a href="/seller/register">Become a seller</a>
    </div>

    <div class="sidebar-item">
        <a href="/logout">Logout</a>
    </div>
</div>
<div class="sidebar-overlay hidden"></div>
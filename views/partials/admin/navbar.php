<?php

use Core\Session;

$emp = Session::get('emp'); ?>

<nav class="navbar navbar">

    <div class="left">
        <a class="navbar-logo" href="/admin">
            <img src="/assets/logo-main.png" alt="Store Logo">
            <h3>Admin Dashboard</h3>
        </a>
    </div>
    <?php if ($emp): ?>
        <div class="right">
            <ul>
                <li class="nav-link">
                    <a class="nav-text" href="/admin/logout"><span class="logout">Logout</span></a>
                </li>

                <li class="nav-link">
                    <a class="nav-text" href="/admin#staff"><span>Staff</span></a>
                </li>

                <li class="nav-link">
                    <a class="nav-text" href="/admin#sellers"><span>Pending Sellers</span></a>
                </li>

                <li class="staff-item">
                    <h2 class="staff-name"><?= $emp['firstName'] . " " . $emp['lastName']  ?></h2>
                    <svg class="staff-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path d="M304 128a80 80 0 1 0 -160 0 80 80 0 1 0 160 0zM96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM49.3 464l349.5 0c-8.9-63.3-63.3-112-129-112l-91.4 0c-65.7 0-120.1 48.7-129 112zM0 482.3C0 383.8 79.8 304 178.3 304l91.4 0C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7L29.7 512C13.3 512 0 498.7 0 482.3z" />
                    </svg>
                </li>
            </ul>
        </div>
    <?php endif; ?>
</nav>

<div id="navbar-offset"></div>
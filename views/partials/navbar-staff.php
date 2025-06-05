<?php

use Core\Session;

$emp = Session::get('emp'); ?>

<nav class="navbar navbar-staff">

    <div class="left">
        <a class="navbar-logo" href="/">
            <img src="/assets/logo-main.png" alt="Store Logo">
            <h3>Admin Dashboard</h3>
        </a>
    </div>

    <div class="right">
        <ul>
            <li>
                <a class="nav-link" href="/logout"><span class="logout">Logout</span></a>
            </li>

            <li>
                <a class="nav-link" href="/admin#staff"><span>Staff</span></a>
            <li>

            <li>
                <a class="nav-link" href="/admin#reports"><span>Reports</span></a>
            <li>

            <li>
                <h2><?= $emp->firstName . " " . $emp->lastName  ?></h2>
            </li>
        </ul>
    </div>
</nav>

<div id="navbar-offset"></div>
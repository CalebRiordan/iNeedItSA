<?php

$stylesheets = ['admin/index.css', 'admin/period-toggle.css'];
$scripts = ['admin/index.js'];

require partial('admin/header');
require partial('admin/navbar');
?>

<main>

    <h1>Admin Dashboard</h1>

    <section class="dashboard-grid">

        <!-- Revenue Card -->
        <?php $statType = 'revenue' ?>
        <?php $period = 'month' ?>
        <div class="card revenue-card" data-type="<?= $statType ?>">

            <div class="switch-container"><?php require partial('admin/period-toggle'); ?></div>

            <h2>Total Revenue: <strong>R</strong><span class="stat-value"></span></h2>
            <div class="loading-overlay" hidden><span class="loading"></span></div>
        </div>

        <!-- Orders Card -->
        <?php $statType = 'order-volume' ?>
        <div class="card" data-type="<?= $statType ?>">
            <div class="switch-container"><?php require partial('admin/period-toggle'); ?></div>

            <h2>Total Orders: <span class="stat-value"></span></h2>
            <div class="loading-overlay" hidden><span class="loading"></span></div>
        </div>

        <!-- Sales Card -->
        <?php $statType = 'sales' ?>
        <?php $period = 'year' ?>
        <div class="card" data-type="<?= $statType ?>">
            <div class="switch-container"><?php require partial('admin/period-toggle'); ?></div>

            <h2>Products Sold: <span class="stat-value"></span></h2>
            <div class="loading-overlay" hidden><span class="loading"></span></div>
        </div>

        <!-- New Users Card -->
        <?php $statType = 'new-users' ?>
        <div class="card" data-type="<?= $statType ?>">
            <div class="switch-container"><?php require partial('admin/period-toggle'); ?></div>

            <h2>New Users: <span class="stat-value"></span></h2>
            <div class="loading-overlay" hidden><span class="loading"></span></div>
        </div>

        <!-- Pending Sellers Card -->
        <?php $statType = 'pending-sellers' ?>
        <?php $period = 'month' ?>
        <div class="card" data-type="<?= $statType ?>">
            <div class="switch-container"><?php require partial('admin/period-toggle'); ?></div>

            <h2>Pending Sellers: <span class="stat-value"></span></h2>
            <div class="loading-overlay" hidden><span class="loading"></span></div>
        </div>

    </section>

    <!-- Chart -->
    <div class="chart-container">
        <h1 id="chart-label"></h1>
        <canvas class="chart"></canvas>
    </div>

</main>

<?php require partial('admin/footer') ?>
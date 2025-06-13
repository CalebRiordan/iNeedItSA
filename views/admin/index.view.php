<?php

$stylesheets = ['admin/index.css', 'admin/period-toggle.css'];
$scripts = ['admin/index.js'];

require partial('admin/header');
require partial('admin/navbar');
?>

<main>

    <h1 class="page-heading">Admin Dashboard</h1>

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
    <secition class="chart-section">
        <h1 id="chart-label"></h1>
        <canvas class="chart"></canvas>
    </secition>

    <!-- Pending Sellers -->
    <section class="pending-sellers-section">
        <?php if ($emp['role'] === 'admin' || $emp['role'] === 'moderator'): ?>

            <h1 class="section-heading">Pending Seller Registrations</h1>

            <?php if (!empty($pendingSellers)): ?>
                <div class="seller-cards">
                    <?php foreach ($pendingSellers as $seller): ?>

                        <!-- Seller card -->
                        <div class="seller-card">
                            <h2><?= $seller->firstName . " " . $seller->lastName ?></h2>
                            <p class="email"><?= $seller->email ?></p>
                            <p><strong>Submitted:</strong> <?= $seller->dateSubmitted ?></p>

                            <div class="doc-previews">
                                <a class="doc-item doc-item-id" href="/admin/sellers/pending?file=<?= urlencode($seller->idDocUrl) ?>"
                                    target="_blank"
                                    rel="noopener noreferrer">
                                    📄 View Copy of ID
                                </a>

                                <a class="doc-item doc-item-poa" href="/admin/sellers/pending?file=<?= urlencode($seller->poaDocUrl) ?>"
                                    target="_blank"
                                    rel="noopener noreferrer">
                                    📄 View Proof of Address
                                </a>
                            </div>

                            <form method="POST" action="/admin/sellers/registration">
                                <input type="hidden" name="user_id" value="<?= $seller->id ?>">
                                <button type="submit" name="action" value="approve">✅ Approve</button>
                                <button type="submit" name="action" value="reject">❌ Reject</button>
                            </form>
                        </div>

                    <?php endforeach; ?>
                </div>

            <?php else: ?>
                <div class="no-sellers">
                    <h4>No sellers await registration currently</h4>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </section>

</main>

<?php require partial('admin/footer') ?>
<?php

$stylesheets = ['navbar.css', 'order/index.css'];
$scripts = ['order/index.js'];

require base_path('views/partials/header.php');
require base_path('views/partials/navbar.php');

?>

<main>
    <div class="content-column">
        <div class="cart-section">

        </div>

        <div class="orders-section">

        </div>
    </div>
</main>

<?php require base_path('views/partials/footer.php') ?>

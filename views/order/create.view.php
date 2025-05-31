<?php

$stylesheets = ['order/create.css'];
$scripts = ['order/create.js'];

require base_path('views/partials/header.php');
require base_path('views/partials/navbar.php');

?>

<main class="checkout-container">
    <form action="/orders/confirm" method="POST">

        <h1>Checkout</h1>

        <section>
            <h2 class="section-heading">Shipping Information</h2>
            <p><strong>Phone:</strong> <?= htmlspecialchars($user->phoneNo) ?></p>
            <p><strong>Location: </strong><?= htmlspecialchars($user->location) ?>, <?= htmlspecialchars($user->province) ?></p>
            <label for="address"><strong>Address</strong></label><br>
            <input id="address" type="text" value="<?= htmlspecialchars($user->buyerProfile->shipAddress) ?>">
        </section>

        <section>
            <h2 class="section-heading">Payment</h2>
            <label for="card-number">Enter card number</label>
            <input id="card-number" type="text" placeholder="Card number" />
        </section>

        <section>
            <h2 class="section-heading">Order Summary</h2>
            <p><strong>Items:</strong> <?= $itemsCount ?></p>
            <p><strong>Total:</strong> R<?= number_format($total, 2) ?></p>
            <p class="date-expected">Expected arrival on <strong><?= htmlspecialchars(date("j F Y", strtotime("+5 days"))) ?></strong></p>
        </section>

        <button type="submit">
            Place Order
        </button>

    </form>

    <?php require base_path('views/partials/confirmation-modal.php')?>

</main>

<?php require base_path('views/partials/footer.php') ?>
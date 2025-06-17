<?php

use Core\Session;

$stylesheets = ['order/create.css'];
$scripts = ['order/create.js'];

require partial('header');
require partial('navbar');
?>

<main class="checkout-container">
    <form action="/checkout" method="POST" id="checkout-form">
        <h1>Checkout</h1>

        <section>
            <h2 class="section-heading">Shipping Information</h2>
            <p><strong>Phone:</strong> <?= htmlspecialchars($user->phoneNo) ?></p>
            <p><strong>Location: </strong><?= htmlspecialchars($user->location) ?>, <?= htmlspecialchars($user->province) ?></p>
            <label for="address"><strong>Address</strong></label><br>
            <input id="address" name="address" type="text" value="<?= htmlspecialchars($user->buyerProfile->shipAddress) ?>">
        </section>

        <section>
            <h2 class="section-heading">Payment</h2>
            <label for="card-num">Enter card number</label>
            <input id="card-num" name="card_num" type="text" placeholder="Card number" />
            <p class="error error-card-num"><?= $errors['email'] ?? "" ?></p>

            <label for="csv-code">CSV Code</label>
            <input id="csv-code" name="csv_code" type="text" placeholder="3-digit CSV" maxlength="3" />
            <p class="error error-csv"></p>
        </section>

        <section>
            <h2 class="section-heading heading-3">Order Summary</h2>
            <p class="date-expected"><strong>Expected Arrival:</strong> <?= htmlspecialchars(date("j F Y", strtotime("+5 days"))) ?></p>
            <p><strong>Items:</strong> <?= $itemsCount ?></p>

            <div class="costs">
                <?php $shippingCost = $total > 1000 ? 40 : 75 ?>
                <div class="cost-row">
                    <span class="cost-label"><strong>Subtotal:</strong></span>
                    <span class="cost-amount">R<?= number_format($total, 2) ?></span>
                </div>
                <div class="cost-row">
                    <span class="cost-label"><strong>Shipping:</strong></span>
                    <span class="cost-amount">R<?= $shippingCost ?></span>
                </div>
                <div class="cost-row">
                    <span class="cost-label"><strong>Total:</strong></span>
                    <span class="cost-amount">R<?= number_format($total + $shippingCost, 2) ?></span>
                </div>
            </div>
        </section>

        <button type="submit">
            Place Order
        </button>

        <input type="hidden" name="csrf_token" value="<?= Session::get('csrf_token') ?>">
    </form>

</main>

<?php require_once partial('toast') ?>
<?php require_once partial('confirmation-modal') ?>
<?php require partial('footer') ?>
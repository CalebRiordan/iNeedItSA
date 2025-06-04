<?php

$stylesheets = ['form.css', 'seller/create.css'];

require partial('header');
require partial('navbar');
?>

<main>
    <div class="form-container">
        <form class="seller-reg-form" action="/seller/register" method="POST">
            <h1>Seller Registration</h1>

            <div class="form-group">
                <label for="id-copy">Upload a copy of your ID</label>
                <input type="file" id="id-copy" name="id_copy" accept=".pdf,image/*" required>
            </div>

            <div class="form-group">
                <label for="proof-address">Upload proof of address</label>
                <input type="file" id="proof-address" name="proof_address" accept=".pdf,image/*" required>
            </div>
            
            <small>Accepted formats: PDF, JPG, PNG</small>

            <button type="submit" class="submit-btn">Submit Documents</button>

        </form>
    </div>
</main>

<?php require partial('footer') ?>
<?php

$stylesheets = ['form.css', 'seller/create.css'];
$scripts = ['seller/create.js'];

require partial('header');
require partial('navbar');
?>

<main>
    <div class="form-container">
        <form class="seller-reg-form" action="/seller/register" method="POST" enctype="multipart/form-data">
            <h1>Seller Registration</h1>

            <div class="form-group">
                <label for="id-copy">Upload a copy of your ID</label>
                <input type="file" id="id-copy" name="id_copy" accept=".pdf,image/*" required>
            </div>

            <div class="form-group">
                <label for="poa">Upload proof of address</label>
                <input type="file" id="poa" name="poa" accept=".pdf,image/*" required>
                <p class="error"><?= $errors['files'] ?? "" ?></p>
                <small>Accepted formats: PDF, JPG, PNG</small>
            </div>

            <button type="submit">Submit Documents</button>

        </form>
    </div>
</main>

<?php require partial('footer') ?>
<?php

$stylesheets = ['form.css', 'session/create.css'];

require partial('header');
require partial('navbar-staff');
?>

<main>
    <div class="form-container">
        <form class="login-form" action="/admin/login" method="POST">

            <h1>Staff Login</h1>

            <div class="input-group">
                <div class="input-wrapper">
                    <input type="email" placeholder="Email" name="email" value="<?= existingFormData('email') ?>" oninput="clearErrors()" required>
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48L48 64zM0 176L0 384c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-208L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z" />
                        </svg>
                    </div>
                </div>
                <p class="error"><?= $errors['email'] ?? "" ?></p>
            </div>

            <div class="input-group">
                <div class="input-wrapper">
                    <input id="password" type="password" placeholder="Password" name="password" oninput="clearErrors()" required>
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                            <path d="M144 144l0 48 160 0 0-48c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192l0-48C80 64.5 144.5 0 224 0s144 64.5 144 144l0 48 16 0c35.3 0 64 28.7 64 64l0 192c0 35.3-28.7 64-64 64L64 512c-35.3 0-64-28.7-64-64L0 256c0-35.3 28.7-64 64-64l16 0z" />
                        </svg>
                    </div>
                    <?php require partial('toggle-password-btn') ?>
                </div>
                <p class="error"><?= $errors['password'] ?? "" ?></p>
            </div>

            <button id="btn-submit" type="submit">Login</button>
        </form>
    </div>
</main>

<script>
    function clearErrors() {
        document.querySelectorAll('.error').forEach(el => {
            el.textContent = "";
        });
    }
</script>


<?php require partial('footer') ?>
<?php

use Core\DTOs\CreateUserDTO;

$stylesheets = ['navbar.css', 'form.css', 'user/create.css'];
$scripts = ['user/create.js'];

require base_path('views/partials/header.php');
require base_path('views/partials/navbar.php');

if (!isset($user) || !$user) {
    $user = new CreateUserDTO(
        htmlspecialchars($_POST['first_name'] ?? ''),
        htmlspecialchars($_POST['last_name'] ?? ''),
        htmlspecialchars($_POST['email'] ?? ''),
        htmlspecialchars($_POST['password'] ?? ''),
        htmlspecialchars($_POST['phone_no'] ?? '0'),
        htmlspecialchars($_POST['location'] ?? ''),
        htmlspecialchars($_POST['province'] ?? ''),
        htmlspecialchars($_POST['address'] ?? ''),
        null,
        htmlspecialchars($_POST['ship_address'] ?? '')
    );
}
?>

<main>
    <div class="form-container">
        <form action="/register" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="previousPage" value="<?= $_SERVER['HTTP_REFERER'] ?? "/" ?>">

            <h1>Create a new account</h1>

            <div class="section section-1">

                <div class="text-inputs">
                    <div class="input-group first">
                        <input type="text" placeholder="First Name" name="first_name" required value="<?= $user->firstName ?>">
                        <p class="error"><?= $errors['first_name'] ?? "" ?></p>
                    </div>

                    <div class="input-group">
                        <input type="text" placeholder="Last Name" name="last_name" required value="<?= $user->lastName ?? '' ?>">
                        <p class="error"><?= $errors['last_name'] ?? "" ?></p>
                    </div>

                    <div class="input-group">
                        <input type="email" placeholder="Email" name="email" required value="<?= $user->email ?? '' ?>">
                        <p class="error"><?= $errors['email'] ?? "" ?></p>
                    </div>

                    <div class="input-group">
                        <div class="input-wrapper">
                            <input id="password" type="password" placeholder="Password" name="password" required>

                            <?php require base_path('views/partials/toggle-password-btn.php') ?>
                        </div>

                        <p class="error image-error"><?= $errors['password'] ?? "" ?></p>
                    </div>
                </div>

                <div class="image-input">
                    <div class="profile-pic-container">
                        <input type="file" name="profile_pic" id="profile-pic" accept="image/*">
                        <div id="img-container" onclick="document.getElementById('profile-pic').click();">
                            <img
                                id="profile-pic-preview"
                                src="<?= $user->profilePicUrl ?? '' ?>"
                                class="<?= $user && $user->profilePicUrl && !$_POST["profile_pic"] ? "" : "placeholder" ?>"
                                alt="Profile Preview">
                        </div>
                        <div class="overlay"><span>+</span></div>
                    </div>

                    <button type="button" class="remove-btn" style="display:none;">&times;</button>
                    <p class="error"><?= $errors['profilePic'] ?? "" ?></p>
                </div>
            </div>

            <div class="section section-2">

                <div class="input-group province">
                    <select name="province" required>
                        <option value="">Select Province</option>
                        <?php
                        $provinces = [
                            "Eastern Cape",
                            "Free State",
                            "Gauteng",
                            "KwaZulu-Natal",
                            "Limpopo",
                            "Mpumalanga",
                            "Northern Cape",
                            "North West",
                            "Western Cape"
                        ];
                        foreach ($provinces as $province) {
                            $selected = ($user->province && $user->province === $province) ? 'selected' : '';
                            echo "<option value=\"$province\" $selected>$province</option>";
                        }
                        ?>
                    </select>
                    <p class="error"><?= $errors['province'] ?? "" ?></p>
                </div>

                <div class="input-group">
                    <input class="phone-no" inputmode="numeric" name="phone_no" required pattern="^[6-8][0-9]{8}$" title="Enter a valid South African phone number" value="<?= $user->phoneNo ?? '0' ?>">
                    <div class="country-code">+27</div>
                    <p class="error error-phone-no"><?= $errors['phone_no'] ?? "" ?></p>
                </div>

                <div class="input-group">
                    <input type="text" placeholder="Town/City" name="location" required value="<?= $user->location ?? '' ?>">
                    <p class="error"><?= $errors['location'] ?? "" ?></p>
                </div>
            </div>


            <div class="section section-3">

                <div class="input-group">
                    <input id="address" type="text" placeholder="Home Address" name="address" required value="<?= $user->address ?? '' ?>">
                    <p class="error"><?= $errors['address'] ?? "" ?></p>
                </div>

                <div class="input-group">
                    <input id="ship-address" type="text" placeholder="Shipping Address" name="ship_address" required value="<?= $user->shipAddress ?? '' ?>" readonly>

                    <div class="checkbox">
                        <input id="same-address" name="same-address" type="checkbox" checked>
                        <label for="same-address">Use home address</label>
                    </div>

                    <p class="error"><?= $errors['ship_address'] ?? "" ?></p>
                </div>

            </div>

            <button id="btn-submit" type="submit">Register</button>

            <p class="redirect-text">Already have an account? <a href="/login">Log in!</a></p>
        </form>
    </div>
</main>

<?php require base_path('views/partials/footer.php') ?>
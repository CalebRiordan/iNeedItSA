<?php

$stylesheets = ['form.css', 'user/create.css'];
$scripts = ['user/create.js'];

require partial('header');
require partial('navbar');
?>

<main>
    <div class="form-container">
        <form action="/profile/<?= $user->id ?>" method="POST" enctype="multipart/form-data">
            <!-- Hidden attributes -->
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="previousPage" value="<?= previousPage(); ?>">

            <h1>Edit account</h1>

            <div class="section section-1">

                <div class="text-inputs">
                    <div class="input-group">
                        <input type="text" placeholder="First Name" name="first_name" required value="<?= $user->firstName ?>">
                        <p class="error"><?= $errors['first_name'] ?? "" ?></p>
                    </div>

                    <div class="input-group">
                        <input type="text" placeholder="Last Name" name="last_name" required value="<?= $user->lastName ?? '' ?>">
                        <p class="error"><?= $errors['last_name'] ?? "" ?></p>
                    </div>

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

                <div class="image-input">
                    <div class="profile-pic-container">
                        <!-- Hidden input to track if user changes their profile picture -->
                        <input id="image-changed" type="hidden" name="image_changed" value=false>

                        <input type="file" name="profile_pic" id="profile-pic" accept="image/*">
                        <div id="img-container" onclick="document.getElementById('profile-pic').click();">
                            <img
                                id="profile-pic-preview"
                                src="<?= $user->profilePicUrl ?? '' ?>"
                                class="<?= $user && $user->profilePicUrl ? "" : "placeholder" ?>"
                                alt="Profile Preview">
                        </div>
                        
                        <div class="overlay"><span>+</span></div>
                    </div>

                    <button type="button" class="remove-btn" style="display: <?= $user && $user->profilePicUrl ? "block" : "none" ?>;">&times;</button>
                    <p class="error image-error"><?= $errors['profile_pic'] ?? "" ?></p>
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


            <button id="btn-submit" type="submit">Save Changes</button>
        </form>
    </div>
</main>

<?php require partial('footer') ?>
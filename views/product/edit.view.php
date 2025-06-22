<?php

use Core\Session;

// Use same styles and script as the 'create' view
$stylesheets = ['form.css', 'product/create.css'];
$scripts = ['product/create.js'];

require partial('header');
require partial('navbar');

$seller = Session::get('user');
if (!isset($product) || $product === null) abort();
?>

<main>
    <?php require partial('back-btn') ?>

    <div class="form-container">
        <form action="/products/<?= $product->id ?>" method="POST" enctype="multipart/form-data">
            <!-- Hidden attributes -->
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="previousPage" value="<?= previousPage(); ?>">
            <input type="hidden" name="existingImage" value="<?= $product->displayImageUrl ?>">

            <h1>Update Your Product Listing</h1>

            <div class="section section-1">

                <div class="text-inputs">
                    <!-- Product name -->
                    <div class="input-group">
                        <label for="name">Product Name</label>
                        <textarea id="name" placeholder="Product Name" name="name" required rows="3" maxlength="255"><?= $product->name ?></textarea>
                        <p class="error error-name"><?= $errors['name'] ?? "" ?></p>
                    </div>

                    <!-- Description -->
                    <div class="input-group">
                        <label for="description">Description</label>
                        <textarea id="description" placeholder="Description" name="description" required rows="5" maxlength="800"><?= $product->description ?></textarea>
                        <p class="error error-name"><?= $errors['description'] ?? "" ?></p>
                    </div>
                </div>

                <!-- Product Image -->
                <div class="image-input">
                    <div class="product-img-container">
                        <!-- Hidden input to track if seller changes image -->
                        <input id="image-changed" type="hidden" name="image_changed" value='false'>
                        
                        <input type="file" name="product_img" id="product-img" accept="image/*">
                        <div id="img-container" onclick="document.getElementById('product-img').click();">
                            <img
                                id="product-img-preview"
                                src="<?= $product->displayImageUrl ?? '' ?>"
                                class="<?= $product && $product->displayImageUrl && !$_POST["product_img"] ? "" : "placeholder" ?>"
                                alt="Product Image">
                        </div>

                        <div class="overlay"><span>+</span></div>
                    </div>

                    <button type="button" class="remove-btn" style="display:none;">&times;</button>
                    <p class="error error-image"><?= $errors['displayImageFile'] ?? "" ?></p>
                </div>
            </div>

            <div class="section section-2">

                <!-- Price -->
                <div class="input-group">
                    <label for="price">Price</label>
                    <input id="price" inputmode="numeric" placeholder="R" name="price" required value="<?= $product->price ?? '' ?>">
                    <p class="error error-price"><?= $errors['price'] ?? "" ?></p>
                </div>

                <!-- Category -->
                <div class="input-group">
                    <label for="category">Category</label>

                    <select id="category" name="category" required>
                        <option value="">Select Category</option>
                        <?php
                        $categories = ['Clothing', 'Electronics', 'Home & Garden', 'Books & Stationary', 'Toys', 'Beauty', 'Sports', 'Pets'];
                        foreach ($categories as $i => $category) {
                            $selected = ($product->category && $product->category === $category) ? 'selected' : '';
                            echo "<option value=\"$i\" $selected>$category</option>";
                        } ?>
                    </select>

                    <p class="error"><?= $errors['category'] ?? "" ?></p>
                </div>

                <!-- Quantity in Stock -->
                <div class="input-group">
                    <label for="stock">Quantity in stock</label>
                    <input id="stock" type="text" placeholder="qty" name="stock" required value="<?= $product->stock ?? '' ?>">
                    <p class="error error-stock"><?= $errors['stock'] ?? "" ?></p>
                </div>

                <!-- Discount -->
                <div class="input-group">
                    <label for="discount">Discount (optional)</label>
                    <input id="discount" type="text" name="discount" value="<?= $product->discount ?? '0' ?>">
                    <p class="error error-discount"><?= $errors['discount'] ?? "" ?></p>
                </div>
            </div>


            <div class="section section-3">
                <!-- Condition -->
                <div class="input-group">
                    <label for="condition">Condition</label>

                    <select id="condition" name="condition" required>
                        <option value="">Select Condition</option>
                        <?php
                        $conditions = ['New', 'Used', 'Refurbished'];
                        foreach ($conditions as $condition) {
                            $selected = ($product->condition && $product->condition === $condition) ? 'selected' : '';
                            echo "<option value=\"$condition\" $selected>$condition</option>";
                        } ?>
                    </select>

                    <p class="error"><?= $errors['condition'] ?? "" ?></p>
                </div>

                <!-- Condition Details -->
                <div class="input-group">
                    <label for="condition-details">Condition Details</label>
                    <input id="condition-details" name="condition_details" value="<?= $product->conditionDetails ?? '' ?>">
                    <p class="error"><?= $errors['conditionDetails'] ?? "" ?></p>
                </div>
            </div>

            <button id="btn-submit" type="submit">Save Changes</button>
        </form>
    </div>
</main>

<?php require partial('footer') ?>
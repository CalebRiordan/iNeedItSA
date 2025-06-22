<?php
function categorySelected($params, $index)
{
    return (isset($params['category']) && $params['category'] == $index) ? 'selected' : '';
}
?>

<div class="category-bar">
    <ul>
        <li class="category-item">
            <a class="category-link <?= categorySelected($params ?? null, 0) ?>" href="/products?category=0">Clothing</a>
        </li>
        <li class="category-item">
            <a class="category-link <?= categorySelected($params ?? null, 1) ?>" href="/products?category=1">Electronics</a>
        </li>
        <li class="category-item">
            <a class="category-link <?= categorySelected($params ?? null, 2) ?>" href="/products?category=2">Home & Garden</a>
        </li>
        <li class="category-item">
            <a class="category-link <?= categorySelected($params ?? null, 3) ?>" href="/products?category=3">Books & Stationery</a>
        </li>
        <li class="category-item">
            <a class="category-link <?= categorySelected($params ?? null, 4) ?>" href="/products?category=4">Toys</a>
        </li>
        <li class="category-item">
            <a class="category-link <?= categorySelected($params ?? null, 5) ?>" href="/products?category=5">Beauty</a>
        </li>
        <li class="category-item">
            <a class="category-link <?= categorySelected($params ?? null, 6) ?>" href="/products?category=6">Sports</a>
        </li>
        <li class="category-item">
            <a class="category-link <?= categorySelected($params ?? null, 7) ?>" href="/products?category=7">Pets</a>
        </li>
    </ul>
</div>
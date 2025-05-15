<?php 
    $cardWidth = 'auto';
    $showDesc = True;
?>
<?php foreach ($products as $product): ?>
    <?php require base_path('/views/partials/product-card.php'); ?>
<?php endforeach; ?>
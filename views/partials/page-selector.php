<?php for ($i = 1; $i <= intdiv($productCount, 16) + 1; $i++): ?>
    <button class="page-btn <?= $i == ($currentPage ?? 1) ? 'active' : '' ?>" data-page="<?= $i ?>">
        <?= $i ?>
    </button>
<?php endfor; ?>
<input type="hidden" id="page-count" name="page-count" value="<?= intdiv($productCount, 16) + 1 ?>">
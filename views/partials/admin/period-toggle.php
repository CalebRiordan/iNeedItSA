<div class="switch">
    <input
        id="<?= $statType ?>_switch-w"
        name="<?= $statType ?>_period"
        type="radio"
        value="week"
        class="switch-btn"
        <?= $period === 'week' ? 'checked' : '' ?> />
    <label
        for="<?= $statType ?>_switch-w"
        class="label-w"
        data-stat-type="<?= $statType ?>">
        Week
    </label>

    <input
        id="<?= $statType ?>_switch-m"
        name="<?= $statType ?>_period"
        type="radio" value="month"
        class="switch-btn" <?= $period === 'month' ? 'checked' : '' ?> />
    <label
        for="<?= $statType ?>_switch-m"
        class="label-m"
        data-stat-type="<?= $statType ?>">
        Month
    </label>

    <input
        id="<?= $statType ?>_switch-y"
        name="<?= $statType ?>_period"
        type="radio" value="year"
        class="switch-btn" <?= $period === 'year' ? 'checked' : '' ?> />
    <label
        for="<?= $statType ?>_switch-y"
        class="label-y"
        data-stat-type="<?= $statType ?>">
        Year
    </label>

    <span class="switch-selector"></span>
</div>
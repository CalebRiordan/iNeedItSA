<?php

use Core\Session;

$toast = Session::get('toast');
if (!in_array("utils/toast.js", $scripts ?? [])) {
    $scripts[] = "utils/toast.js";
}
?>

<div id="toast" class="toast">
    <span id="toast-text"></span>
</div>

<input type="hidden" id="toast-data"
    value="<?= $toast['message'] ?? '' ?>"
    data-type="<?= $toast['type'] ?? '' ?>"
    data-duration="<?= $toast['duration'] ?? '' ?>">
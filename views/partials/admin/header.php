<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>iNeedItSA</title>
    <?php

    // Load Styles
    if (isset($stylesheets)): ?>

        <?php $stylesheets[] = 'globals.css'; ?>
        <?php $stylesheets[] = 'navbar.css'; ?>

        <?php foreach ($stylesheets as $stylesheet): ?>
            <link rel="stylesheet" href="/css/<?= $stylesheet ?>">
        <?php endforeach; ?>

    <?php endif; ?>
</head>

<body style="margin: 0;">
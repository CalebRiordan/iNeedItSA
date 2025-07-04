<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>iNeedItSA</title>
    <link rel="icon" type="image/png" href="/favicon.png">

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
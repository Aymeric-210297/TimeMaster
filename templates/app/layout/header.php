<?php include_once __DIR__ . '/../../components/formInput.php' ?>

<!DOCTYPE html>
<html lang="fr">

<?php include_once __DIR__ . "/head.php" ?>

<?php

$navbarItem = $navbarItem ?? 'UNDEFINED';

$getNavbarLink = function ($href, $icon, $value, $name = null) use ($navbarItem) {
    $class = $navbarItem === $name ? "class=\"active\"" : "";

    return <<<HTML
            <a $class href="$href">
                <i class="$icon fa-lg"></i>
                <p>$value</p>
            </a>
    HTML;
};

?>

<body>
    <?php include_once __DIR__ . "/../../components/flashMessage.php" ?>

    <div class="sidebar">
        <h1>Projet horaires</h1>
        <div>
            <?= $getNavbarLink('/app', 'fa-solid fa-house', 'Home', 'HOME') ?>
        </div>
        <div class="bottom-links">
            <?= $getNavbarLink('/app/account', 'fa-solid fa-user', out($_SESSION['user']->userGivenName . ' ' . $_SESSION['user']->userFamilyName), 'ACCOUNT') ?>
            <?= $getNavbarLink('/sign-out', 'fa-solid fa-arrow-right-from-bracket', "Se déconnecter") ?>
        </div>
    </div>

    <div class="sidebar-content">

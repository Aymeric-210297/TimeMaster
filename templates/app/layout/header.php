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
                <i class="$icon"></i>
                <p>$value</p>
            </a>
    HTML;
};

?>

<body>
    <?php include_once __DIR__ . "/../../components/flashMessage.php" ?>

    <div class="sidebar">
        <div class="title">
            <h1>TimeMaster</h1>
            <?php if (isset($school)): ?>
                <p>
                    <i class="fa-solid fa-chevron-right"></i>
                    <?= $school->schoolName ?>
                </p>
            <?php endif; ?>
        </div>
        <div>
            <?= $getNavbarLink('/app', 'fa-solid fa-house', 'Accueil', 'HOME') ?>
            <?= $getNavbarLink('/app/schools', 'fa-solid fa-school', 'Établissements', 'SCHOOLS') ?>
            <?php if (isset($school)): ?>
                <?= $getNavbarLink("/app/schools/{$school->schoolId}/students", 'fa-solid fa-user-graduate', 'Élèves', 'STUDENTS') ?>
                <?= $getNavbarLink("/app/schools/{$school->schoolId}/teachers", 'fa-solid fa-chalkboard-user', 'Professeurs', 'TEACHERS') ?>
                <?= $getNavbarLink("/app/schools/{$school->schoolId}/classes", 'fa-solid fa-users-line', 'Classes d\'élèves', 'CLASSES') ?>
                <?= $getNavbarLink("/app/schools/{$school->schoolId}/classrooms", 'fa-solid fa-chalkboard', 'Salles de classe', 'CLASSROOMS') ?>
                <?= $getNavbarLink("/app/schools/{$school->schoolId}/subjects", 'fa-solid fa-book', 'Matières', 'SUBJECTS') ?>
                <?= $getNavbarLink("/app/schools/{$school->schoolId}/schedules", 'fa-solid fa-calendar-days', 'Horaires', 'SCHEDULES') ?>
                <?= $getNavbarLink("/app/schools/{$school->schoolId}/collaborators", 'fa-solid fa-user-tie', 'Collaborateurs', 'COLLABORATORS') ?>
            <?php endif; ?>
        </div>
        <div class="bottom-links">
            <?= $getNavbarLink('/app/account', 'fa-solid fa-user', out($_SESSION['user']->userGivenName . ' ' . $_SESSION['user']->userFamilyName), 'ACCOUNT') ?>
            <?= $getNavbarLink('/sign-out', 'fa-solid fa-arrow-right-from-bracket', "Se déconnecter") ?>
        </div>
    </div>

    <div class="sidebar-content">

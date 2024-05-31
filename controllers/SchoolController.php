<?php

require_once __DIR__ . "/../models/UserModel.php";

$userModel = new UserModel($dbh, createErrorCallback(500));

get('/app/schools', function () use ($userModel) {
    if (!isset($_SESSION['user'])) {
        redirect('/sign-in');
    }

    $schoolsDetails = $userModel->getUserSchoolsDetails($_SESSION['user']->userId);

    render(
        "app",
        "schools",
        [
            'head' => ['title' => "Établissements"],
            'navbarItem' => 'SCHOOLS',
            'schoolsDetails' => $schoolsDetails,
        ]
    );
});

// TODO: /app/schools/add

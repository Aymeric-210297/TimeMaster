<?php

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

require_once __DIR__ . "/../models/UserModel.php";
require_once __DIR__ . "/../models/SchoolModel.php";

$userModel = new UserModel($dbh, createErrorCallback(500));
$schoolModel = new SchoolModel($dbh, createErrorCallback(500));

get('/app/schools', function () use ($userModel) {
    checkAuth();

    $schoolsDetails = $userModel->getUserSchoolsDetails($_SESSION['user']->userId);

    render(
        "app",
        "schools/list-schools",
        [
            'head' => ['title' => "Établissements"],
            'navbarItem' => 'SCHOOLS',
            'schoolsDetails' => $schoolsDetails,
        ]
    );
});

get('/app/schools/add', function () {
    checkAuth();

    render(
        "app",
        "schools/form-school",
        [
            'head' => ['title' => "Ajouter un établissement"],
            'navbarItem' => 'SCHOOLS'
        ]
    );
});

post('/app/schools/add', function () use ($userModel, $schoolModel) {
    checkCsrf();
    checkAuth();

    $formViolations = validateData($_POST, [
        'name' => [new NotBlank(), new Length(['max' => 50])],
        'address' => [new NotBlank(), new Length(['max' => 255])],
    ]);

    if (count($formViolations) > 0) {
        render(
            "app",
            "schools/form-school",
            [
                'head' => ['title' => "Ajouter un établissement"],
                'navbarItem' => 'SCHOOLS',
                'formViolations' => $formViolations
            ],
            400
        );
    }

    if ($schoolModel->getSchoolByAddress($_POST['address'])) {
        createFlashMessage("Impossible de créer l'établissement", "Cet établissement existe déjà.", "error");

        render(
            "app",
            "schools/form-school",
            [
                'head' => ['title' => "Ajouter un établissement"],
                'navbarItem' => 'SCHOOLS',
            ],
            409
        );
    }

    $schoolId = $schoolModel->createSchool($_POST['name'], $_POST['address']);
    $userModel->createUserSchool($_SESSION['user']->userId, $schoolId);
    // TODO: transaction

    redirect('/app/schools');
});

get('/app/schools/$schoolId', function ($schoolId) use ($userModel, $schoolModel) {
    checkAuth($userModel, $schoolId);

    $school = $schoolModel->getSchoolById($schoolId);

    render("app", "schools/form-school", [
        'school' => $school,
        'navbarItem' => 'SCHOOLS'
    ]);
});

post('/app/schools/$schoolId', function ($schoolId) use ($userModel, $schoolModel) {
    checkCsrf();
    checkAuth($userModel, $schoolId);

    $school = $schoolModel->getSchoolById($schoolId);

    $formViolations = validateData($_POST, [
        'name' => [new NotBlank(), new Length(['max' => 50])],
        'address' => [new NotBlank(), new Length(['max' => 255])],
    ]);

    if (count($formViolations) > 0) {
        render(
            "app",
            "schools/form-school",
            [
                'school' => $school,
                'navbarItem' => 'SCHOOLS',
                'formViolations' => $formViolations
            ],
            400
        );
    }

    $schoolAlr = $schoolModel->getSchoolByAddress($_POST['address']);
    if ($schoolAlr && $schoolAlr->schoolId != $schoolId) {
        createFlashMessage("Impossible de créer l'établissement", "Un autre établissement utilise déjà cette adresse.", "error");

        render(
            "app",
            "schools/form-school",
            [
                'school' => $school,
                'navbarItem' => 'SCHOOLS'
            ],
            400
        );
    }

    $schoolModel->updateSchoolById($schoolId, $_POST['name'], $_POST['address']);
    redirect();
});

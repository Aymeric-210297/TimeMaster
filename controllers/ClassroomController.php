<?php

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

require_once __DIR__ . "/../models/UserModel.php";
require_once __DIR__ . "/../models/ClassroomModel.php";
require_once __DIR__ . "/../models/SchoolModel.php";

$classroomModel = new ClassroomModel($dbh, createErrorCallback(500));
$userModel = new UserModel($dbh, createErrorCallback(500));
$schoolModel = new SchoolModel($dbh, createErrorCallback(500));

get('/app/schools/$schoolId/classrooms', function ($schoolId) use ($classroomModel, $userModel, $schoolModel) {
    checkAuth($userModel, $schoolId);

    $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    $search = $_GET['search'] ?? null;

    if (empty($page)) {
        $page = 1;
    }

    if ($page < 1) {
        render('error', '400');
    }

    $classroomCount = $classroomModel->countClassroomsBySchool($schoolId, $search);
    $pageCount = ceil($classroomCount / ITEMS_PER_PAGE);

    if ($pageCount > 0 && $page > $pageCount + 1) {
        render('error', '400');
    }

    $school = $schoolModel->getSchoolById($schoolId);
    $classrooms = $classroomModel->getClassroomsBySchool($schoolId, ($page - 1) * ITEMS_PER_PAGE, ITEMS_PER_PAGE, $search);

    render('app', 'classrooms/list-classrooms', [
        'school' => $school,
        'navbarItem' => 'CLASSROOMS',
        'classrooms' => $classrooms,
        'page' => $page,
        'pageCount' => $pageCount
    ]);
});

get('/app/schools/$schoolId/classrooms/add', function ($schoolId) use ($userModel, $schoolModel) {
    checkAuth($userModel, $schoolId);

    $school = $schoolModel->getSchoolById($schoolId);

    render('app', 'classrooms/form-classroom', [
        'school' => $school,
        'navbarItem' => 'CLASSROOMS',
    ]);
});

post('/app/schools/$schoolId/classrooms/add', function ($schoolId) use ($userModel, $schoolModel, $classroomModel) {
    checkAuth($userModel, $schoolId);

    $school = $schoolModel->getSchoolById($schoolId);

    $formViolations = validateData($_POST, [
        'ref' => [new NotBlank(), new Length(['max' => 10])],
        'nbPlace' => [new NotBlank(), new Range(['min' => 1, 'max' => 35])],
        'projector' => [new NotBlank(), new Range(['min' => 0, 'max' => 1])],
    ]);

    if (count($formViolations) > 0) {
        render('app', 'classrooms/form-classroom', [
            'formViolations' => $formViolations,
            'school' => $school,
            'navbarItem' => 'CLASSROOMS',
        ]);
    }

    $classroom = $classroomModel->getClassroomByRef($schoolId, $_POST['ref']);
    if ($classroom) {
        createFlashMessage("Impossible de créer la salle de classe", "Cette salle de classe existe déjà.", 'error');

        render('app', 'classrooms/form-classroom', [
            'school' => $school,
            'navbarItem' => 'CLASSROOMS',
        ]);
    }

    $classroomId = $classroomModel->createClassroom($schoolId, $_POST['ref'], $_POST['nbPlace'], $_POST['projector']);

    redirect("/app/schools/$schoolId/classrooms/$classroomId");
});

get('/app/schools/$schoolId/classrooms/$classroomId', function ($schoolId, $classroomId) use ($classroomModel, $userModel, $schoolModel) {
    checkAuth($userModel, $schoolId);

    $school = $schoolModel->getSchoolById($schoolId);
    $classroom = $classroomModel->getClassroomById($schoolId, $classroomId);

    if (!$classroom) {
        render('error', '404');
    }

    render('app', 'classrooms/form-classroom', [
        'school' => $school,
        'navbarItem' => 'CLASSROOMS',
        'classroom' => $classroom
    ]);
});

post('/app/schools/$schoolId/classrooms/$classroomId', function ($schoolId, $classroomId) use ($classroomModel, $userModel, $schoolModel) {
    checkAuth($userModel, $schoolId);

    $school = $schoolModel->getSchoolById($schoolId);
    $classroom = $classroomModel->getClassroomById($schoolId, $classroomId);

    if (!$classroom) {
        render('error', '404');
    }

    $formViolations = validateData($_POST, [
        'ref' => [new NotBlank(), new Length(['max' => 10])],
        'nbPlace' => [new NotBlank(), new Range(['min' => 1, 'max' => 35])],
        'projector' => [new NotBlank(), new Range(['min' => 0, 'max' => 1])],
    ]);

    if (count($formViolations) > 0) {
        render('app', 'classrooms/form-classroom', [
            'formViolations' => $formViolations,
            'school' => $school,
            'navbarItem' => 'CLASSROOMS',
            'classroom' => $classroom
        ]);
    }

    $classroomAlr = $classroomModel->getClassroomByRef($schoolId, $_POST['ref']);
    if ($classroomAlr && $classroomAlr->classroomId != $classroomId) {
        createFlashMessage("Impossible de sauvegarder la salle de classe", "Cette email est déjà utilisée par une autre salle de classe.", "error");

        render('app', 'classrooms/form-classroom', [
            'school' => $school,
            'navbarItem' => 'CLASSROOMS',
            'classroom' => $classroom
        ]);
    }

    $classroomModel->updateClassroomById($schoolId, $classroomId, $_POST['ref'], $_POST['nbPlace'], $_POST['projector']);

    redirect();
});

get('/app/schools/$schoolId/classrooms/$classroomId/delete', function ($schoolId, $classroomId) use ($userModel, $classroomModel) {
    checkCsrf();
    checkAuth($userModel, $schoolId);

    $classroom = $classroomModel->getClassroomById($schoolId, $classroomId);
    if (!$classroom) {
        render('error', '404');
    }

    $classroomModel->deleteClassroomById($schoolId, $classroomId);

    createFlashMessage("Élève supprimé avec succès", "Vous avez supprimé la salle de classe. Cette action ne peut être annulée.", "success");

    redirect("/app/schools/$schoolId/classrooms");
});

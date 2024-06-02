<?php

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

require_once __DIR__ . "/../models/UserModel.php";
require_once __DIR__ . "/../models/ClassModel.php";
require_once __DIR__ . "/../models/SchoolModel.php";
require_once __DIR__ . "/../models/StudentModel.php";

$classModel = new ClasseModel($dbh, createErrorCallback(500));
$userModel = new UserModel($dbh, createErrorCallback(500));
$schoolModel = new SchoolModel($dbh, createErrorCallback(500));
$studentModel = new StudentModel($dbh, createErrorCallback(500));

get('/app/schools/$schoolId/classes', function ($schoolId) use ($classModel, $userModel, $schoolModel) {
    checkAuth($userModel, $schoolId);

    $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    $search = $_GET['search'] ?? null;

    if (empty($page)) {
        $page = 1;
    }

    if ($page < 1) {
        render('error', '400');
    }

    $classeCount = $classModel->countClassesBySchool($schoolId, $search);
    $pageCount = ceil($classeCount / ITEMS_PER_PAGE);

    if ($pageCount > 0 && $page > $pageCount + 1) {
        render('error', '400');
    }

    $school = $schoolModel->getSchoolById($schoolId);
    $classes = $classModel->getClassesBySchool($schoolId, ($page - 1) * ITEMS_PER_PAGE, ITEMS_PER_PAGE, $search);

    render('app', 'classes/list-classes', [
        'school' => $school,
        'navbarItem' => 'CLASSES',
        'classes' => $classes,
        'page' => $page,
        'pageCount' => $pageCount
    ]);
});

get('/app/schools/$schoolId/classes/add', function ($schoolId) use ($userModel, $schoolModel) {
    checkAuth($userModel, $schoolId);

    $school = $schoolModel->getSchoolById($schoolId);

    render('app', 'classes/form-class', [
        'school' => $school,
        'navbarItem' => 'CLASSES',
    ]);
});

post('/app/schools/$schoolId/classes/add', function ($schoolId) use ($userModel, $schoolModel, $classModel) {
    checkAuth($userModel, $schoolId);

    $school = $schoolModel->getSchoolById($schoolId);

    $formViolations = validateData($_POST, [
        'ref' => [new NotBlank(), new Length(['max' => 10])],

    ]);

    if (count($formViolations) > 0) {
        render('app', 'classes/form-class', [
            'formViolations' => $formViolations,
            'school' => $school,
            'navbarItem' => 'CLASSES',
        ]);
    }

    $class = $classModel->getClassByRef($schoolId, $_POST['ref']);
    if ($class) {
        createFlashMessage("Impossible de créer la classe", "Cette classe existe déjà.", 'error');

        render('app', 'classes/form-class', [
            'school' => $school,
            'navbarItem' => 'CLASSES',
        ]);
    }

    $classId = $classModel->createClass($schoolId, $_POST['ref']);

    redirect("/app/schools/$schoolId/classes/$classId");
});

get('/app/schools/$schoolId/classes/$classId/students', function ($schoolId, $classId) use ($classModel, $userModel, $schoolModel, $studentModel) {
    checkAuth($userModel, $schoolId);

    $school = $schoolModel->getSchoolById($schoolId);
    $class = $classModel->getClassById($schoolId, $classId);

    if (!$class) {
        render('error', '404');
    }

    $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    $search = $_GET['search'] ?? null;

    if (empty($page)) {
        $page = 1;
    }

    if ($page < 1) {
        render('error', '400');
    }

    $studentCount = $studentModel->countStudentsByClass($schoolId, $classId, $search);
    $pageCount = ceil($studentCount / ITEMS_PER_PAGE);

    if ($pageCount > 0 && $page > $pageCount + 1) {
        render('error', '400');
    }

    $students = $studentModel->getStudentsByClass($schoolId, $classId, ($page - 1) * ITEMS_PER_PAGE, ITEMS_PER_PAGE, $search);

    render('app', 'classes/list-class-students', [
        'school' => $school,
        'navbarItem' => 'CLASSES',
        'class' => $class,
        'students' => $students
    ]);
});

get('/app/schools/$schoolId/classes/$classId', function ($schoolId, $classId) use ($classModel, $userModel, $schoolModel) {
    checkAuth($userModel, $schoolId);

    $school = $schoolModel->getSchoolById($schoolId);
    $class = $classModel->getClassById($schoolId, $classId);

    if (!$class) {
        render('error', '404');
    }

    render('app', 'classes/form-class', [
        'school' => $school,
        'navbarItem' => 'CLASSES',
        'class' => $class
    ]);
});

post('/app/schools/$schoolId/classes/$classId', function ($schoolId, $classId) use ($classModel, $userModel, $schoolModel) {
    checkAuth($userModel, $schoolId);

    $school = $schoolModel->getSchoolById($schoolId);
    $class = $classModel->getClassById($schoolId, $classId);

    if (!$class) {
        render('error', '404');
    }

    $formViolations = validateData($_POST, [
        'ref' => [new NotBlank(), new Length(['max' => 10])],
    ]);

    if (count($formViolations) > 0) {
        render('app', 'classes/form-class', [
            'formViolations' => $formViolations,
            'school' => $school,
            'navbarItem' => 'CLASSES',
            'class' => $class
        ]);
    }

    $classAlr = $classModel->getClassByRef($schoolId, $_POST['ref']);
    if ($classAlr && $classAlr->classId != $classId) {
        createFlashMessage("Impossible de sauvegarder la classe", "Ce nom est déjà utilisée par une autre classe.", "error");

        render('app', 'classes/form-class', [
            'school' => $school,
            'navbarItem' => 'CLASSES',
            'class' => $class
        ]);
    }

    $classModel->updateClassById($schoolId, $classId, $_POST['ref']);

    redirect();
});

get('/app/schools/$schoolId/classes/$classId/delete', function ($schoolId, $classId) use ($userModel, $classModel) {
    checkCsrf();
    checkAuth($userModel, $schoolId);

    $class = $classModel->getClassById($schoolId, $classId);
    if (!$class) {
        render('error', '404');
    }

    $classModel->deleteClassById($schoolId, $classId);

    createFlashMessage("Classe supprimé avec succès", "Vous avez supprimé la classe. Cette action ne peut être annulée.", "success");

    redirect("/app/schools/$schoolId/classes");
});

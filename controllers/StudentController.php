<?php

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

require_once __DIR__ . "/../models/UserModel.php";
require_once __DIR__ . "/../models/StudentModel.php";
require_once __DIR__ . "/../models/SchoolModel.php";

$studentModel = new StudentModel($dbh, createErrorCallback(500));
$userModel = new UserModel($dbh, createErrorCallback(500));
$schoolModel = new SchoolModel($dbh, createErrorCallback(500));

get('/app/schools/$schoolId/students', function ($schoolId) use ($studentModel, $userModel, $schoolModel) {
    checkAuth($userModel, $schoolId);

    $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    $search = $_GET['search'] ?? null;

    if (empty($page)) {
        $page = 1;
    }

    if ($page < 1) {
        render('error', '400');
    }

    $studentCount = $studentModel->countStudentsBySchool($schoolId, $search);
    $pageCount = ceil($studentCount / ITEMS_PER_PAGE);

    if ($pageCount > 0 && $page > $pageCount + 1) {
        render('error', '400');
    }

    $school = $schoolModel->getSchoolById($schoolId);
    $students = $studentModel->getStudentsBySchool($schoolId, ($page - 1) * ITEMS_PER_PAGE, ITEMS_PER_PAGE, $search);

    render('app', 'students/list-students', [
        'school' => $school,
        'navbarItem' => 'STUDENTS',
        'students' => $students,
        'page' => $page,
        'pageCount' => $pageCount
    ]);
});

get('/app/schools/$schoolId/students/add', function ($schoolId) use ($userModel, $schoolModel) {
    checkAuth($userModel, $schoolId);

    $school = $schoolModel->getSchoolById($schoolId);

    render('app', 'students/form-student', [
        'school' => $school,
        'navbarItem' => 'STUDENTS',
    ]);
});

post('/app/schools/$schoolId/students/add', function ($schoolId) use ($userModel, $schoolModel, $studentModel) {
    checkAuth($userModel, $schoolId);

    $school = $schoolModel->getSchoolById($schoolId);

    $formViolations = validateData($_POST, [
        'first-name' => [new NotBlank(), new Length(['max' => 40])],
        'last-name' => [new NotBlank(), new Length(['max' => 40])],
        'email' => [new NotBlank(), new Email(), new Length(['max' => 255])],
    ]);

    if (count($formViolations) > 0) {
        render('app', 'students/form-student', [
            'formViolations' => $formViolations,
            'school' => $school,
            'navbarItem' => 'STUDENTS',
        ]);
    }

    $student = $studentModel->getStudentByEmail($schoolId, $_POST['email']);
    if ($student) {
        createFlashMessage("Impossible de créer l'élève", "Cet élève existe déjà.", 'error');

        render('app', 'students/form-student', [
            'school' => $school,
            'navbarItem' => 'STUDENTS',
        ]);
    }

    $studentId = $studentModel->createStudent($schoolId, $_POST['first-name'], $_POST['last-name'], $_POST['email']);

    redirect("/app/schools/$schoolId/students/$studentId");
});

get('/app/schools/$schoolId/students/$studentId', function ($schoolId, $studentId) use ($studentModel, $userModel, $schoolModel) {
    checkAuth($userModel, $schoolId);

    $school = $schoolModel->getSchoolById($schoolId);
    $student = $studentModel->getStudentById($schoolId, $studentId);

    if (!$student) {
        render('error', '404');
    }

    render('app', 'students/form-student', [
        'school' => $school,
        'navbarItem' => 'STUDENTS',
        'student' => $student
    ]);
});

post('/app/schools/$schoolId/students/$studentId', function ($schoolId, $studentId) use ($studentModel, $userModel, $schoolModel) {
    checkAuth($userModel, $schoolId);

    $school = $schoolModel->getSchoolById($schoolId);
    $student = $studentModel->getStudentById($schoolId, $studentId);

    if (!$student) {
        render('error', '404');
    }

    $formViolations = validateData($_POST, [
        'first-name' => [new NotBlank(), new Length(['max' => 40])],
        'last-name' => [new NotBlank(), new Length(['max' => 40])],
        'email' => [new NotBlank(), new Email(), new Length(['max' => 255])],
    ]);

    if (count($formViolations) > 0) {
        render('app', 'students/form-student', [
            'formViolations' => $formViolations,
            'school' => $school,
            'navbarItem' => 'STUDENTS',
            'student' => $student
        ]);
    }

    $studentAlr = $studentModel->getStudentByEmail($schoolId, $_POST['email']);
    if ($studentAlr && $studentAlr->studentId != $studentId) {
        createFlashMessage("Impossible de sauvegarder l'élève", "Cette email est déjà utilisée par un autre élève.", "error");

        render('app', 'students/form-student', [
            'school' => $school,
            'navbarItem' => 'STUDENTS',
            'student' => $student
        ]);
    }

    $studentModel->updateStudentById($schoolId, $studentId, $_POST['first-name'], $_POST['last-name'], $_POST['email']);

    redirect();
});

get('/app/schools/$schoolId/students/$studentId/delete', function ($schoolId, $studentId) use ($userModel, $studentModel) {
    checkCsrf();
    checkAuth($userModel, $schoolId);

    $student = $studentModel->getStudentById($schoolId, $studentId);
    if (!$student) {
        render('error', '404');
    }

    $studentModel->deleteStudentById($schoolId, $studentId);

    createFlashMessage("Élève supprimé avec succès", "Vous avez supprimé l'élève. Cette action ne peut être annulée.", "success");

    redirect("/app/schools/$schoolId/students");
});

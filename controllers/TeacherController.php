<?php

use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

require_once __DIR__ . "/../models/UserModel.php";
require_once __DIR__ . "/../models/TeacherModel.php";
require_once __DIR__ . "/../models/SchoolModel.php";

$teacherModel = new TeacherModel($dbh, createErrorCallback(500));
$userModel = new UserModel($dbh, createErrorCallback(500));
$schoolModel = new SchoolModel($dbh, createErrorCallback(500));

get('/app/schools/$schoolId/teachers', function ($schoolId) use ($teacherModel, $userModel, $schoolModel) {
    checkAuth($userModel, $schoolId);

    $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    $search = $_GET['search'] ?? null;

    if (empty($page)) {
        $page = 1;
    }

    if ($page < 1) {
        render('error', '400');
    }

    $teacherCount = $teacherModel->countTeachersBySchool($schoolId, $search);
    $pageCount = ceil($teacherCount / ITEMS_PER_PAGE);

    if ($pageCount > 0 && $page > $pageCount + 1) {
        render('error', '400');
    }

    $school = $schoolModel->getSchoolById($schoolId);
    $teachers = $teacherModel->getTeachersBySchool($schoolId, ($page - 1) * ITEMS_PER_PAGE, ITEMS_PER_PAGE, $search);

    render('app', 'teachers/list-teachers', [
        'school' => $school,
        'navbarItem' => 'TEACHERS',
        'teachers' => $teachers,
        'page' => $page,
        'pageCount' => $pageCount
    ]);
});

get('/app/schools/$schoolId/teachers/add', function ($schoolId) use ($userModel, $schoolModel) {
    checkAuth($userModel, $schoolId);

    $school = $schoolModel->getSchoolById($schoolId);

    render('app', 'teachers/form-teacher', [
        'school' => $school,
        'navbarItem' => 'TEACHERS',
    ]);
});

post('/app/schools/$schoolId/teachers/add', function ($schoolId) use ($userModel, $schoolModel, $teacherModel) {
    checkAuth($userModel, $schoolId);

    $school = $schoolModel->getSchoolById($schoolId);

    $formViolations = validateData($_POST, [
        'first-name' => [new NotBlank(), new Length(['max' => 40])],
        'last-name' => [new NotBlank(), new Length(['max' => 40])],
        'email' => [new NotBlank(), new Email(), new Length(['max' => 255])],
        'gender' => [new NotBlank(), new Choice(['M', 'F', 'X'])],
        'number-hours' => [new NotBlank(), new Positive()],
    ]);

    if (count($formViolations) > 0) {
        render('app', 'teachers/form-teacher', [
            'formViolations' => $formViolations,
            'school' => $school,
            'navbarItem' => 'TEACHERS',
        ]);
    }

    $teacher = $teacherModel->getTeacherByEmail($schoolId, $_POST['email']);
    if ($teacher) {
        createFlashMessage("Impossible de créer le professeur", "Cet professeur existe déjà.", 'error');

        render('app', 'teachers/form-teacher', [
            'school' => $school,
            'navbarItem' => 'TEACHERS',
        ]);
    }

    $teacherId = $teacherModel->createTeacher($schoolId, $_POST['first-name'], $_POST['last-name'], $_POST['email'], $_POST['gender'], $_POST['number-hours']);

    redirect("/app/schools/$schoolId/teachers/$teacherId");
});

get('/app/schools/$schoolId/teachers/$teacherId', function ($schoolId, $teacherId) use ($teacherModel, $userModel, $schoolModel) {
    checkAuth($userModel, $schoolId);

    $school = $schoolModel->getSchoolById($schoolId);
    $teacher = $teacherModel->getTeacherById($schoolId, $teacherId);

    if (!$teacher) {
        render('error', '404');
    }

    render('app', 'teachers/form-teacher', [
        'school' => $school,
        'navbarItem' => 'TEACHERS',
        'teacher' => $teacher
    ]);
});

post('/app/schools/$schoolId/teachers/$teacherId', function ($schoolId, $teacherId) use ($teacherModel, $userModel, $schoolModel) {
    checkAuth($userModel, $schoolId);

    $school = $schoolModel->getSchoolById($schoolId);
    $teacher = $teacherModel->getTeacherById($schoolId, $teacherId);

    if (!$teacher) {
        render('error', '404');
    }

    $formViolations = validateData($_POST, [
        'first-name' => [new NotBlank(), new Length(['max' => 40])],
        'last-name' => [new NotBlank(), new Length(['max' => 40])],
        'email' => [new NotBlank(), new Email(), new Length(['max' => 255])],
        'gender' => [new NotBlank(), new Choice(['M', 'F', 'X'])],
        'number-hours' => [new NotBlank(), new Positive()],
    ]);

    if (count($formViolations) > 0) {
        render('app', 'teachers/form-teacher', [
            'formViolations' => $formViolations,
            'school' => $school,
            'navbarItem' => 'TEACHERS',
            'teacher' => $teacher
        ]);
    }

    $teacherAlr = $teacherModel->getTeacherByEmail($schoolId, $_POST['email']);
    if ($teacherAlr && $teacherAlr->teacherId != $teacherId) {
        createFlashMessage("Impossible de sauvegarder le professeur", "Cette email est déjà utilisée par un autre professeur.", "error");

        render('app', 'teachers/form-teacher', [
            'school' => $school,
            'navbarItem' => 'TEACHERS',
            'teacher' => $teacher
        ]);
    }

    $teacherModel->updateTeacherById($schoolId, $teacherId, $_POST['first-name'], $_POST['last-name'], $_POST['email'], $_POST['gender'], $_POST['number-hours']);

    redirect();
});

get('/app/schools/$schoolId/teachers/$teacherId/delete', function ($schoolId, $teacherId) use ($userModel, $teacherModel) {
    checkCsrf();
    checkAuth($userModel, $schoolId);

    $teacher = $teacherModel->getTeacherById($schoolId, $teacherId);
    if (!$teacher) {
        render('error', '404');
    }

    // TODO: vérifier les contraintes
    $teacherModel->deleteTeacherById($schoolId, $teacherId);

    createFlashMessage("Professeur supprimé avec succès", "Vous avez supprimé le professeur. Cette action ne peut être annulée.", "success");

    redirect("/app/schools/$schoolId/teachers");
});

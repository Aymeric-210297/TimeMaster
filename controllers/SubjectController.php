<?php

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

require_once __DIR__ . "/../models/UserModel.php";
require_once __DIR__ . "/../models/SubjectModel.php";
require_once __DIR__ . "/../models/SchoolModel.php";

$subjectModel = new SubjectModel($dbh, createErrorCallback(500));
$userModel = new UserModel($dbh, createErrorCallback(500));
$schoolModel = new SchoolModel($dbh, createErrorCallback(500));

get('/app/schools/$schoolId/subjects', function ($schoolId) use ($subjectModel, $userModel, $schoolModel) {
    checkAuth($userModel, $schoolId);

    $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    $search = $_GET['search'] ?? null;

    if (empty($page)) {
        $page = 1;
    }

    if ($page < 1) {
        render('error', '400');
    }

    $subjectCount = $subjectModel->countSubjectsBySchool($schoolId, $search);
    $pageCount = ceil($subjectCount / ITEMS_PER_PAGE);

    if ($pageCount > 0 && $page > $pageCount + 1) {
        render('error', '400');
    }

    $school = $schoolModel->getSchoolById($schoolId);
    $subjects = $subjectModel->getSubjectsBySchool($schoolId, ($page - 1) * ITEMS_PER_PAGE, ITEMS_PER_PAGE, $search);

    render('app', 'subjects/list-subjects', [
        'school' => $school,
        'navbarItem' => 'SUBJECTS',
        'subjects' => $subjects,
        'page' => $page,
        'pageCount' => $pageCount
    ]);
});

get('/app/schools/$schoolId/subjects/add', function ($schoolId) use ($userModel, $schoolModel) {
    checkAuth($userModel, $schoolId);

    $school = $schoolModel->getSchoolById($schoolId);

    render('app', 'subjects/form-subject', [
        'school' => $school,
        'navbarItem' => 'SUBJECTS',
    ]);
});

post('/app/schools/$schoolId/subjects/add', function ($schoolId) use ($userModel, $schoolModel, $subjectModel) {
    checkAuth($userModel, $schoolId);

    $school = $schoolModel->getSchoolById($schoolId);

    $formViolations = validateData($_POST, [
        'name' => [new NotBlank(), new Length(['max' => 30])],
    ]);

    if (count($formViolations) > 0) {
        render('app', 'subjects/form-subject', [
            'formViolations' => $formViolations,
            'school' => $school,
            'navbarItem' => 'SUBJECTS',
        ]);
    }

    $subject = $subjectModel->getSubjectByName($schoolId, $_POST['name']);
    if ($subject) {
        createFlashMessage("Impossible de créer la matière", "Cette matière existe déjà.", 'error');

        render('app', 'subjects/form-subject', [
            'school' => $school,
            'navbarItem' => 'SUBJECTS',
        ]);
    }

    $subjectId = $subjectModel->createSubject($schoolId, $_POST['name']);

    redirect("/app/schools/$schoolId/subjects/$subjectId");
});

get('/app/schools/$schoolId/subjects/$subjectId', function ($schoolId, $subjectId) use ($subjectModel, $userModel, $schoolModel) {
    checkAuth($userModel, $schoolId);

    $school = $schoolModel->getSchoolById($schoolId);
    $subject = $subjectModel->getSubjectById($schoolId, $subjectId);

    if (!$subject) {
        render('error', '404');
    }

    render('app', 'subjects/form-subject', [
        'school' => $school,
        'navbarItem' => 'SUBJECTS',
        'subject' => $subject
    ]);
});

post('/app/schools/$schoolId/subjects/$subjectId', function ($schoolId, $subjectId) use ($subjectModel, $userModel, $schoolModel) {
    checkAuth($userModel, $schoolId);

    $school = $schoolModel->getSchoolById($schoolId);
    $subject = $subjectModel->getSubjectById($schoolId, $subjectId);

    if (!$subject) {
        render('error', '404');
    }

    $formViolations = validateData($_POST, [
        'name' => [new NotBlank(), new Length(['max' => 30])],
    ]);

    if (count($formViolations) > 0) {
        render('app', 'subjects/form-subject', [
            'formViolations' => $formViolations,
            'school' => $school,
            'navbarItem' => 'SUBJECTS',
            'subject' => $subject
        ]);
    }

    $subjectAlr = $subjectModel->getSubjectByName($schoolId, $_POST['name']);
    if ($subjectAlr && $subjectAlr->subjectId != $subjectId) {
        createFlashMessage("Impossible de sauvegarder la matière", "Cette matière existe déjà.", "error");

        render('app', 'subjects/form-subject', [
            'school' => $school,
            'navbarItem' => 'SUBJECTS',
            'subject' => $subject
        ]);
    }

    $subjectModel->updateSubjectById($schoolId, $subjectId, $_POST['name']);

    redirect();
});

get('/app/schools/$schoolId/subjects/$subjectId/delete', function ($schoolId, $subjectId) use ($userModel, $subjectModel) {
    checkCsrf();
    checkAuth($userModel, $schoolId);

    $subject = $subjectModel->getSubjectById($schoolId, $subjectId);
    if (!$subject) {
        render('error', '404');
    }

    // TODO: vérifier les contraintes
    $subjectModel->deleteSubjectById($schoolId, $subjectId);

    createFlashMessage("Matière supprimée avec succès", "Vous avez supprimé la matière. Cette action ne peut être annulée.", "success");

    redirect("/app/schools/$schoolId/subjects");
});

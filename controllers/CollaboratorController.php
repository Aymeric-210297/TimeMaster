<?php

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

require_once __DIR__ . "/../models/UserModel.php";
require_once __DIR__ . "/../models/StudentModel.php";
require_once __DIR__ . "/../models/SchoolModel.php";

$userModel = new UserModel($dbh, createErrorCallback(500));
$schoolModel = new SchoolModel($dbh, createErrorCallback(500));

get('/app/schools/$schoolId/collaborators', function ($schoolId) use ($userModel, $schoolModel) {
    checkAuth($userModel, $schoolId);

    $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    $search = $_GET['search'] ?? null;

    if (empty($page)) {
        $page = 1;
    }

    if ($page < 1) {
        render('error', '400');
    }

    $collaboratorCount = $schoolModel->countSchoolCollaboratorsById($schoolId, $search);
    $pageCount = ceil($collaboratorCount / ITEMS_PER_PAGE);

    if ($pageCount > 0 && $page > $pageCount + 1) {
        render('error', '400');
    }

    $school = $schoolModel->getSchoolById($schoolId);
    $collaborators = $schoolModel->getSchoolCollaboratorsById($schoolId, ($page - 1) * ITEMS_PER_PAGE, ITEMS_PER_PAGE, $search);

    render('app', 'collaborators/list-collaborators', [
        'school' => $school,
        'navbarItem' => 'COLLABORATORS',
        'collaborators' => $collaborators,
        'page' => $page,
        'pageCount' => $pageCount
    ]);
});

get('/app/schools/$schoolId/collaborators/$userId/delete', function ($schoolId, $userId) use ($userModel, $schoolModel) {
    checkCsrf();
    checkAuth($userModel, $schoolId);

    $collaborator = $schoolModel->getCollaboratorById($schoolId, $userId);
    if (!$collaborator) {
        render('error', '404');
    }

    $schoolModel->deleteCollaboratorById($schoolId, $userId);

    if ($userId != $_SESSION['user']->userId) {
        createFlashMessage("Collaborateur supprimé avec succès", "Vous avez supprimé un collaborateur. Cette action ne peut être annulée.", "success");
        redirect("/app/schools/$schoolId/collaborators");
    } else {
        createFlashMessage("Vous vous êtes retiré de cet établissement avec succès", "Vous vous êtes retiré de cet établissement. Cette action ne peut être annulée.", "success");
        redirect("/app/schools/");
    }
});

get('/app/schools/$schoolId/collaborators/add', function ($schoolId) use ($userModel, $schoolModel) {
    checkAuth($userModel, $schoolId);

    $school = $schoolModel->getSchoolById($schoolId);

    render('app', 'collaborators/form-collaborator', [
        'school' => $school,
        'navbarItem' => 'COLLABORATORS',
    ]);
});

post('/app/schools/$schoolId/collaborators/add', function ($schoolId) use ($userModel, $schoolModel) {
    checkAuth($userModel, $schoolId);

    $school = $schoolModel->getSchoolById($schoolId);

    $formViolations = validateData($_POST, [
        'email' => [new NotBlank(), new Email(), new Length(['max' => 255])],
    ]);

    if (count($formViolations) > 0) {
        render('app', 'collaborators/form-collaborator', [
            'formViolations' => $formViolations,
            'school' => $school,
            'navbarItem' => 'COLLABORATORS',
        ]);
    }

    $collaborator = $schoolModel->getCollaboratorByEmail($schoolId, $_POST['email']);
    if ($collaborator) {
        createFlashMessage("Impossible d'inviter ce collaborateur", "Ce collaborateur existe déjà.", 'error');

        render('app', 'collaborators/form-collaborator', [
            'school' => $school,
            'navbarItem' => 'COLLABORATORS',
        ]);
    }

    $user = $userModel->getUserByEmail($_POST['email']);
    if (!$user) {
        createFlashMessage("Impossible d'inviter ce collaborateur", "Ce collaborateur n'a pas de compte chez nous. Demandez-lui d'en créer un, puis réessayez.", 'error');

        render('app', 'collaborators/form-collaborator', [
            'school' => $school,
            'navbarItem' => 'COLLABORATORS',
        ]);
    }

    $userModel->createUserSchool($user->userId, $schoolId);

    redirect("/app/schools/$schoolId/collaborators");
});

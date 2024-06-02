<?php

use Symfony\Component\Validator\Constraints\AtLeastOneOf;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PasswordStrength;

require_once __DIR__ . "/../models/UserModel.php";

$userModel = new UserModel($dbh, createErrorCallback(500));

get('/app/account', function () {
    checkAuth();

    render(
        "app",
        "account",
        [
            'head' => ['title' => "Paramètres du compte"],
            'navbarItem' => 'ACCOUNT'
        ]
    );
});

post('/app/account', function () use ($userModel) {
    checkCsrf();
    checkAuth();

    if (isset($_POST['save'])) {
        $formViolations = validateData($_POST, [
            'first-name' => [new NotBlank(), new Length(['max' => 40])],
            'last-name' => [new NotBlank(), new Length(['max' => 40])],
            'email' => [new NotBlank(), new Email(), new Length(['max' => 255])],
            'password' => [new AtLeastOneOf([new Blank(), new PasswordStrength()])],
        ]);

        if (count($formViolations) > 0) {
            render(
                "app",
                "account",
                [
                    'head' => ['title' => "Paramètres du compte"],
                    'navbarItem' => 'ACCOUNT',
                    'formViolations' => $formViolations,
                ],
                400
            );
        }

        $userModel->updateUserById($_SESSION['user']->userId, $_POST['first-name'], $_POST['last-name'], $_POST['email'], $_POST['password']);
        refreshSession($userModel);

        redirect();
    } elseif (isset($_POST['delete-account'])) {
        if (count($userModel->getUserSchools($_SESSION['user']->userId)) > 0) {
            createFlashMessage(
                "Impossible de supprimer votre compte en l'état",
                "Vous devez d'abord vous retirer de tous les établissements auxquels vous avez accès.",
                "error"
            );

            render(
                "app",
                "account",
                [
                    'head' => ['title' => "Paramètres du compte"],
                    'navbarItem' => 'ACCOUNT',
                ],
                409
            );
        } else {
            $userModel->deleteUserById($_SESSION['user']->userId);

            redirect("/sign-out");
        }
    }
});


get('/sign-in', function () {
    if (isset($_SESSION['user'])) {
        redirect('/');
    }

    render(
        "auth",
        "sign-in",
        [
            'head' => ['title' => "Connexion"],
        ]
    );
});

post('/sign-in', function () use ($userModel) {
    checkCsrf();

    $formViolations = validateData($_POST, [
        'email' => [new NotBlank(), new Email(), new Length(['max' => 255])],
        'password' => [new NotBlank()],
    ]);

    if (count($formViolations) > 0) {
        render(
            "auth",
            "sign-in",
            [
                'head' => ['title' => "Connexion"],
                'formViolations' => $formViolations,
            ],
            400
        );
    }

    $user = $userModel->getUserByEmail($_POST['email']);

    if (!$user) {
        render(
            "auth",
            "sign-in",
            [
                'head' => ['title' => "Connexion"],
                'errorMessage' => 'Email ou mot de passe incorrect'
            ],
            401
        );
    }

    if (password_verify($_POST['password'], $user->userPassword)) {
        unset($user->userPassword);
        $_SESSION['user'] = $user;
        redirect('/');
    }

    render(
        "auth",
        "sign-in",
        [
            'head' => ['title' => "Connexion"],
            'errorMessage' => 'Email ou mot de passe incorrect'
        ],
        401
    );
});

get('/sign-up', function () {
    if (isset($_SESSION['user'])) {
        redirect('/');
    }

    render(
        "auth",
        "sign-up",
        [
            'head' => ['title' => "Inscription"],
        ]
    );
});

post('/sign-up', function () use ($userModel) {
    checkCsrf();

    $formViolations = validateData($_POST, [
        'first-name' => [new NotBlank(), new Length(['max' => 40])],
        'last-name' => [new NotBlank(), new Length(['max' => 40])],
        'email' => [new NotBlank(), new Email(), new Length(['max' => 255])],
        'password' => [new NotBlank(), new PasswordStrength()],
    ]);

    if (count($formViolations) > 0) {
        render(
            "auth",
            "sign-up",
            [
                'head' => ['title' => "Inscription"],
                'formViolations' => $formViolations,
            ],
            400
        );
    }

    $user = $userModel->getUserByEmail($_POST['email']);

    if ($user) {
        render(
            "auth",
            "sign-up",
            [
                'head' => ['title' => "Inscription"],
                "errorMessage" => "Adresse email déjà utilisée"
            ],
            409
        );
    }

    $userModel->createUser($_POST['email'], $_POST['last-name'], $_POST['first-name'], $_POST['password']);

    redirect('/sign-in');
});


get('/sign-out', function () {
    session_destroy();
    redirect('/sign-in');
});

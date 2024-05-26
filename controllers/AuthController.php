<?php

require_once __DIR__ . "/../models/UserModel.php";

$userModel = new UserModel($dbh, createErrorCallback(500));

get('/app/account', function () {
    if (!isset ($_SESSION['user'])) {
        redirect('/sign-in');
    }

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

    if (!isset ($_SESSION['user'])) {
        redirect('/');
    }

    // TODO: vérification des données $_POST

    if (isset ($_POST['save'])) {
        $userModel->updateUserById($_SESSION['user']->userId, $_POST['first-name'], $_POST['last-name'], $_POST['email'], $_POST['password']);
        refreshSession($userModel);

        redirect();
    } else if (isset ($_POST['delete-account'])) {
        if (count($userModel->getUserSchools($_SESSION['user']->userId)) > 0) {
            // TODO: renvoyer un message d'erreur
        } else {
            $userModel->deleteUserById($_SESSION['user']->userId);

            redirect("/sign-out");
        }
    }
});


get('/sign-in', function () {
    if (isset ($_SESSION['user'])) {
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

    // TODO: vérification des données $_POST

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
        unset ($user->userPassword);
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
    if (isset ($_SESSION['user'])) {
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

    // TODO: vérification des données $_POST

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

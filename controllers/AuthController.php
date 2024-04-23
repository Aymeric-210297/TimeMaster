<?php

require_once __DIR__ . "/../models/UserModel.php";

$userModel = new UserModel($dbh, create500ResponseCallback("out"));

get('/sign-in', function () {
    if (isset($_SESSION['user'])) {
        redirect('/');
    }

    render(
        "auth",
        "auth/sign-in",
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
            "auth/sign-in",
            [
                'head' => ['title' => "Connexion"],
                'errorMessage' => 'Email ou mot de passe incorrect'
            ],
            401
        );
    }

    if (password_verify($_POST['password'], $user->utilisateurMotDePasse)) {
        unset($user->utilisateurMotDePasse);
        $_SESSION['user'] = $user;
        redirect('/');
    }

    render(
        "auth",
        "auth/sign-in",
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
        "auth/sign-up",
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
            "auth/sign-up",
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

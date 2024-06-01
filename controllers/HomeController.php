<?php
require_once __DIR__ . "/../models/jourModel.php";
require_once __DIR__ . "/../models/horaireModel.php";
require_once __DIR__ . "/../models/creneauModel.php";
$jourModel = new jourModel($dbh, createErrorCallback(500, "out"));
$creneauModel = new creneauModel($dbh, createErrorCallback(500, "out"));
get('/affichageHoraire', function () use($jourModel,$creneauModel,$dbh) {
    $listeJour = $jourModel->recupJourParEtablissement(1);
    $listeCreneau = $creneauModel->recupCreneauParEtablissement(1);
    render(
        "out",
        "display/allSchedule",
        [
            'listeJour' => $listeJour,
            'listeCreneau' => $listeCreneau,
            'dbh' => $dbh,
            'head' => ['title' => "Home"],
        ]
    );
});
get('/', function () {
    if (isset($_SESSION['user'])) {
        redirect('/app');
    } else {
        redirect('/sign-in');
        // TODO: landing page
    }
});

get('/app', function () {
    if (!isset($_SESSION['user'])) {
        redirect('/sign-in');
    }

    render('app', 'home', [
        'head' => [
            'title' => 'Accueil'
        ],
        'navbarItem' => 'HOME'
    ]);
});

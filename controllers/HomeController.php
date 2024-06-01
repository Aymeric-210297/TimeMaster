<?php

require_once __DIR__ . "/../models/classModel.php";
require_once __DIR__ . "/../models/scheduleModel.php";
require_once __DIR__ . "/../models/jourModel.php";
require_once __DIR__ . "/../models/horaireModel.php";
require_once __DIR__ . "/../models/creneauModel.php";
$jourModel = new jourModel($dbh);
$creneauModel = new creneauModel($dbh);
get('/app/testAffichageH', function () use($jourModel, $creneauModel, $dbh) {
    $schoolId = 1;
    $classModel = new ClassModel($dbh);
    $classList = $classModel->getClassIdsBySchoolId($schoolId); 

    $schedule = [];
    if (isset($_GET['classId'])) {
        $classId = intval($_GET['classId']);
        $scheduleModel = new ScheduleModel($dbh);
        $schedule = $scheduleModel->getScheduleByClassId($classId);
    }

    // Fetch all days and timeslots
    $days = $jourModel->recupJourParEtablissement2($schoolId); 
    $timeslots = $creneauModel->recupCreneauParEtablissement2($schoolId); 

    render(
        "app",
        "affichageH",
        [
            'classList' => $classList,
            'schedule' => $schedule,
            'days' => $days,
            'timeslots' => $timeslots,
            'dbh' => $dbh,
            'head' => ['title' => "Home"],
            'navbarItem' => 'AFFICHAGE_HORAIRES',
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

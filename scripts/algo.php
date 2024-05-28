<?php
require_once __DIR__ . "/../utils/functions/logError.php";
require_once __DIR__ . "/../configs/app.php";
require_once __DIR__ . '/printData.php';
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../models/BaseModel.php';
require __DIR__ . '/../models/TeacherModel.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();
require_once __DIR__ . "/../configs/database.php";
$teacherModel = new TeacherModel($dbh);
$schoolId = 1;
$tables = array(
    "class_schedule",
    "schedule_day",
    "schedule_timeslot",
    "schedule",
);
// Réinitialisation des données de chaque table
foreach ($tables as $table) {
    // Suppression des données de la table
    $sql = "DELETE FROM $table;";
    $dbh->exec($sql);
    // Réinitialisation de la valeur d'auto-incrémentation
    $sql = "ALTER TABLE $table AUTO_INCREMENT = 1;";
    $dbh->exec($sql);
    echo ("Ok => Supression de : " . $table . "\n");
}
echo "Les données de la base de données ont été réinitialisées avec succès.\n";


//PROF
$profVariatif = array(); // le tab avec les points des profs
//parametre 1 = type de donnée
//parametre 2 = quel prof 
$profVariatif[0][0] = 1; // id du prof
$profVariatif[1][0][0][0] = 1; // le tab Variatif    3 case : jourId    4 case : creneauId
$profVariatif[2][0][0][0] = 1; // le tab des presence   3 case : jourId    4 case : creneauId
$profVariatif[3][0][0] = 1; // le classement des salle classe   3 case : classement apres le = IdClase

$profVariatif[4][0][0] = 1; // id de la/les matiere qu'il donne
$profVariatif[5][0] = 1; // nombre restant d'heure a donné
$nbProf = $teacherModel->getTeacherCountBySchoolId($schoolId);
for ($i=0; $i < $nbProf; $i++) { 
    $profId = $teacherModel->getTeacherIdBySchoolIdAndIndex($schoolId,$i);
    $profVariatif[2][$i] = $teacherModel->getAvailabilitiesByTeacherId($profId);
    $profVariatif[3][$i] = $teacherModel->getClassroomsByTeacherId($profId);
    $profVariatif[0][$i] = $profId;
    $profVariatif[4][$i] = $teacherModel->getSubjectIdsByTeacherId($profId);
    $profVariatif[5][$i] = $teacherModel->getTeacherHoursByTeacherId($profId);
}
//CLASSE
$classeVariatif = array();
//parametre 1 = type de donnée
//parametre 2 = quel classe

$classeVariatif[0][0] = 1; // Id de la classe
$classeVariatif[1][0][0][0] = 1; // le tab Variatif    3 case : jourId    4 case : creneauId
$classeVariatif[2][0][1] = 10; // 3 : id de la matiere     valeur = nombre d'heure









//SALLE DE CLASSE

//parametre 1 = type de donnée
//parametre 2 = quel salle de classe

$salleClasse[0][0] = 1; // Id de la salle de classe
$salleClasse[1][0][0][0] = 1; // le tab variatif     3 case : jourId    4 case : creneauId
$salleClasse[2][0][0][0] = 1; // le tab des presences      3 case : jourId    4 case : creneauId
$salleClasse[2][0][0] = 1; // Id de la/les matiere preferentiel


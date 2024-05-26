<?php
require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();
require_once __DIR__ . "/../configs/database.php";
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

$profVariatif = array(); // le tab avec les points des profs
//parametre 1 = type de donnée
//parametre 2 = quel prof 
$profVariatif[0][0][0][0] = 1; // le tab Variatif    3 case : jourId    4 case : creneauId
$profVariatif[1][0][0][0] = 1; // le tab des presence   3 case : jourId    4 case : creneauId
$profVariatif[2][0][0][0] = 1; // le classement des salle classe
$profVariatif[3][0] = 1; // id du prof
$profVariatif[4][0] = 1; // id de la matiere qu'il donne
$profVariatif[5][0] = 1; // nombre restant d'heure a donné




//help me please



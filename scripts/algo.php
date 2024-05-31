<?php
require_once __DIR__ . "/../utils/functions/logError.php";
require_once __DIR__ . "/../configs/app.php";
require_once __DIR__ . '/printData.php';
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../models/BaseModel.php';
require __DIR__ . '/../models/TeacherModel.php';
require __DIR__ . '/../models/ClassroomModel.php';
require __DIR__ . '/../models/ClassModel.php';
require __DIR__ . '/../models/jourModel.php';
require __DIR__ . '/../models/creneauModel.php';
require __DIR__ . '/../models/scheduleModel.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();
require_once __DIR__ . "/../configs/database.php";
$teacherModel = new TeacherModel($dbh);
$classroomModel = new classroomModel($dbh);
$classModel = new classModel($dbh);
$jourModel = new jourModel($dbh);
$creneauModel = new creneauModel($dbh);
$scheduleModel = new ScheduleModel($dbh);
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
$dayNumber = $jourModel->getNumberOfDaysBySchoolId($schoolId);

$timeSlotNumber = $creneauModel->getNumberOfTimeslotsBySchoolId($schoolId);
$tabJourIdCreneauId = $jourModel->getDayTimeslotArrayBySchoolId($schoolId);
//JOUR
$days = array();

//CRENEAU
$timeSlots = array();
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
$startTime = microtime(true);
for ($i=0; $i < $nbProf; $i++) { 
    $profId = $teacherModel->getTeacherIdBySchoolIdAndIndex($schoolId,$i);
    $profVariatif[0][$i] = $profId;
    $profVariatif[1][$i] = $tabJourIdCreneauId;
    $profVariatif[2][$i] = $teacherModel->getAvailabilitiesByTeacherId($profId);
    $profVariatif[3][$i] = $teacherModel->getClassroomsByTeacherId($profId);
    $profVariatif[4][$i] = $teacherModel->getSubjectIdsByTeacherId($profId);
    $profVariatif[5][$i] = $teacherModel->getTeacherHoursByTeacherId($profId);
}
$endTime = microtime(true);
echo (number_format($endTime - $startTime, 4)." => Ajout de profVariatif \n");
//CLASSE
$classeVariatif = array();
//parametre 1 = type de donnée
//parametre 2 = quel classe
$classeVariatif[0][0] = 1; // Id de la classe
$classeVariatif[1][0][0][0] = 1; // le tab Variatif    3 case : jourId    4 case : creneauId
$classeVariatif[2][0][1] = 10; // 3 : id de la matiere     valeur = nombre d'heure

$nbClasse = $classModel->getClassCountBySchoolId($schoolId);
$startTime = microtime(true);
for ($i=0; $i < $nbClasse; $i++) { 
    $classeId = $classModel->getClassIdBySchoolIdAndIndex($schoolId,$i);
    $classeVariatif[0][$i] = $classeId; // id de la classe
    $classeVariatif[1][$i] = $tabJourIdCreneauId;
    $classeVariatif[2][$i] = $classModel->getClassSubjectsByClassId($classeId);
}
$endTime = microtime(true);
echo (number_format($endTime - $startTime, 4)." => Ajout de classeVariatif \n");
//SALLE DE CLASSE
//parametre 1 = type de donnée
//parametre 2 = quel salle de classe
$salleClasseVariatif[0][0] = 1; // Id de la salle de classe
$salleClasseVariatif[1][0][0][0] = 1; // le tab variatif     3 case : jourId    4 case : creneauId
$salleClasseVariatif[2][0][0][0] = 1; // le tab des presences      3 case : jourId    4 case : creneauId
$salleClasseVariatif[3][0][0] = 1; // Id de la/les matiere preferentiel
$nbSalleClasse = $classroomModel->getClassroomCountBySchoolId($schoolId);
$startTime = microtime(true);
for ($i=0; $i < $nbSalleClasse; $i++) { 
    $salleClasseId = $classroomModel->getClassroomIdBySchoolIdAndIndex($schoolId,$i);
    $salleClasseVariatif[0][$i] = $salleClasseId;
    $salleClasseVariatif[1][$i] = $tabJourIdCreneauId;
    $salleClasseVariatif[2][$i] = $classroomModel->getClassroomAvailabilitiesByClassroomId($salleClasseId);
    $salleClasseVariatif[3][$i] = $classroomModel->getClassroomSubjectsByClassroomId($salleClasseId);
}
$endTime = microtime(true);
echo (number_format($endTime - $startTime, 4)." => Ajout de salleClasseVariatif\n");


//ajout des points pour chaque tab
$maxReached = false;
$tabClass_Schedule = array();
$tabClass_Schedule[0][0] = 1; //id de l'horaire             V
$tabClass_Schedule[1][0] = 1; //id du jour
$tabClass_Schedule[2][0] = 1; //id du creneau
$tabClass_Schedule[3][0] = 1; //id de la classe             V
$tabClass_Schedule[4][0] = 1; //id du prof                  
$tabClass_Schedule[5][0] = 1; //id de la salle de classe
$tabClass_Schedule[6][0] = 1; //id de la matiere
//creation d'un horaire pour chaque classe
$time_preference = array();


for ($i=0; $i < $nbClasse; $i++) { 
    $tabClass_Schedule[0][$i] = $scheduleModel->createSchedule($schoolId);
    $tabClass_Schedule[3][$i] = $classeVariatif[0][$i];
}
$startTime = microtime(true);
$compteur = 0;
for ($i=0; $i < $dayNumber; $i++) { 
    for ($y=0; $y < $timeSlotNumber; $y++) { 
        for ($j=0; $j < $nbClasse; $j++) { 

            $compteur++;
            echo($compteur . "\n");
        }
        for ($j=0; $j < $nbSalleClasse; $j++) { 

            $compteur++;
            echo($compteur . "\n");
        }
        for ($j=0; $j < $nbProf; $j++) { 
            $compteur++;
            echo($compteur . "\n");
        }
    }
}
$endTime = microtime(true);
echo (number_format($endTime - $startTime, 4)." => test temp\n");
echo($compteur);
for ($i=0; $i < $nbClasse; $i++) { 
    
}


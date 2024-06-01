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
require __DIR__ . '/../models/ScheduleModel.php';
require __DIR__ . '/../models/SubjectModel.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();
require_once __DIR__ . "/../configs/database.php";
$teacherModel = new TeacherModel($dbh);
$classroomModel = new classroomModel($dbh);
$classModel = new classModel($dbh);
$jourModel = new jourModel($dbh);
$creneauModel = new creneauModel($dbh);
$scheduleModel = new ScheduleModel($dbh);
$subjectModel = new SubjectModel($dbh);
$schoolId = 2;
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
$subjectNumber = $subjectModel->getNumberOfSubjectBySchoolId($schoolId);
$dayNumber = $jourModel->getNumberOfDaysBySchoolId($schoolId);
$timeSlotNumber = $creneauModel->getNumberOfTimeslotsBySchoolId($schoolId);
$tabJourIdCreneauId = $jourModel->getDayTimeslotArrayBySchoolId($schoolId);
$allDayIds = $jourModel->getAllDayIds();

// Récupérer tous les IDs de créneaux horaires pour l'établissement spécifié
$allTimeslotIds = $creneauModel->getTimeslotIdsBySchoolId($schoolId);
$timePreferences = $scheduleModel->getTimePreferences($schoolId);
$groupement = $scheduleModel->createTimeslotGroupMapping($schoolId);

// JOUR
$days = array();
for ($i = 0; $i < $dayNumber; $i++) {
    if (isset($allDayIds[$i])) {
        $days[$i] = $allDayIds[$i];
    }
}

// CRENEAU
$timeSlots = array();
for ($i = 0; $i < $timeSlotNumber; $i++) {
    if (isset($allTimeslotIds[$i])) {
        $timeSlots[$i] = $allTimeslotIds[$i];
    }
}


//PROF
//PROF
//PROF
$profVariatif = array(); // le tab avec les points des profs
//parametre 1 = type de donnée
//parametre 2 = quel prof 
$nbProf = $teacherModel->getTeacherCountBySchoolId($schoolId);
$startTime = microtime(true);
for ($i=0; $i < $nbProf; $i++) { 
    $profId = $teacherModel->getTeacherIdBySchoolIdAndIndex($schoolId,$i);
    $profVariatif[0][$i] = $profId;// id du prof
    $profVariatif[1][$i] = $tabJourIdCreneauId; // le tab Variatif    3 case : jourId    4 case : creneauId
    $profVariatif[2][$i] = $teacherModel->getAvailabilitiesByTeacherId($profId);// le tab des presence   3 case : jourId    4 case : creneauId
    $profVariatif[3][$i] = $teacherModel->getClassroomsByTeacherId($profId);// le classement des salle classe   3 case : classement apres le = IdClase
    $profVariatif[4][$i] = $teacherModel->getSubjectIdsByTeacherId($profId); // id de la/les matiere qu'il donne
    $profVariatif[5][$i] = $teacherModel->getTeacherHoursByTeacherId($profId); // nombre restant d'heure a donné
}
$endTime = microtime(true);
echo (number_format($endTime - $startTime, 4)." => Ajout de profVariatif \n");
//CLASSE
//CLASSE
//CLASSE
$classeVariatif = array();
//parametre 1 = type de donnée
//parametre 2 = quel classe
$nbClasse = $classModel->getClassCountBySchoolId($schoolId);
$startTime = microtime(true);
for ($i=0; $i < $nbClasse; $i++) { 
    $classeId = $classModel->getClassIdBySchoolIdAndIndex($schoolId,$i);
    $classeVariatif[0][$i] = $classeId; // id de la classe
    $classeVariatif[1][$i] = $tabJourIdCreneauId; // le tab Variatif    3 case : jourId    4 case : creneauId
    $classeVariatif[2][$i] = $classModel->getClassSubjectsByClassId($classeId); // 3 : id de la matiere     valeur = nombre d'heure
}


$endTime = microtime(true);
echo (number_format($endTime - $startTime, 4)." => Ajout de classeVariatif \n");
//SALLE DE CLASSE
//SALLE DE CLASSE
//SALLE DE CLASSE
//parametre 1 = type de donnée
//parametre 2 = quel salle de classe
$nbSalleClasse = $classroomModel->getClassroomCountBySchoolId($schoolId);
$startTime = microtime(true);
for ($i=0; $i < $nbSalleClasse; $i++) { 
    $salleClasseId = $classroomModel->getClassroomIdBySchoolIdAndIndex($schoolId,$i);
    $salleClasseVariatif[0][$i] = $salleClasseId; // Id de la salle de classe
    $salleClasseVariatif[1][$i] = $tabJourIdCreneauId; // le tab variatif     3 case : jourId    4 case : creneauId
    $salleClasseVariatif[2][$i] = $classroomModel->getClassroomAvailabilitiesByClassroomId($salleClasseId); // le tab des presences      3 case : jourId    4 case : creneauId
    $salleClasseVariatif[3][$i] = $classroomModel->getClassroomSubjectsByClassroomId($salleClasseId); // Id de la/les matiere preferentiel
}



$endTime = microtime(true);
echo (number_format($endTime - $startTime, 4)." => Ajout de salleClasseVariatif\n");
//MATIERE
//MATIERE
//MATIERE
for ($i=0; $i < $subjectNumber; $i++) { 
    $subjects = $subjectModel->getSubjectIdsBySchoolId($schoolId);
}


//ajout des points pour chaque tab
$maxReached = false;
$tabClass_Schedule = array();
$tabClass_Schedule[0][0] = 1; //id de l'horaire             V
$tabClass_Schedule[1][0] = 1; //id du jour
$tabClass_Schedule[2][0] = 1; //id du creneau
$tabClass_Schedule[3][0] = 1; //id de la classe             V
$tabClass_Schedule[4][0] = 1; //id du prof                  
$tabClass_Schedule[5][0] = 1; //id de la salle de classe
//$tabClass_Schedule[6][0] = 1; //id de la matiere
//creation d'un horaire pour chaque classe
$time_preference = array();
$startTime = microtime(true);
$compteur = 0;
$compteur2 = 0;




















/*
for ($i=0; $i < $nbClasse; $i++) { 
    for ($y=0; $y < $dayNumber; $y++) { 
        for ($j=0; $j < $timeSlotNumber; $j++) { 
        
            $tabClass_Schedule[0][$compteur] = $scheduleModel->createSchedule($schoolId); //id de l'horaire 
            $tabClass_Schedule[1][$compteur] = $days[$y]; //id du jour
            $tabClass_Schedule[2][$compteur] = $timeSlots[$j]; //id du creneau
            $tabClass_Schedule[3][$compteur] = $classeVariatif[0][$i]; //id de la classe
            //quelle matiere ? : 
            for ($a=0; $a < $subjectNumber; $a++) { 
                //test si la classe a cours de cette matiere si c'est le cas la matiere id est stockée et on passe a autre chose sinon on continue jusqu'a ne plus avoir de matiere
                
                
                
            }
            $tabClass_Schedule[6][$compteur] = 1; //id de la matiere
            $tabClass_Schedule[4][$compteur] = 1; //id du prof                  
            $tabClass_Schedule[5][$compteur] = 1; //id de la salle de classe
             
            $compteur++; 
        }
    }
    echo($compteur . "\n");
}

$compteur = 0;
$endTime = microtime(true);
echo (number_format($endTime - $startTime, 4)." => remplissage du tab\n");
$startTime = microtime(true);
for ($i=0; $i < $nbClasse; $i++) { 
    for ($y=0; $y < $dayNumber; $y++) { 
        for ($j=0; $j < $timeSlotNumber; $j++) { 
                $scheduleModel->createClassSchedule($tabClass_Schedule, $compteur);
            $compteur++; 
        }
        echo($compteur . "\n");
    }
    
}
$endTime = microtime(true);
echo (number_format($endTime - $startTime, 4)." => Ajout du tab dans la bdd\n");
/*
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
    
}*/


<?php
require_once __DIR__ . "/../utils/functions/logError.php";
require_once __DIR__ . "/../configs/app.php";
require_once __DIR__ . '/printData.php';
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../models/BaseModel.php';
require __DIR__ . '/../models/TeacherModel.php';
require __DIR__ . '/../models/ClassroomModel.php';
require __DIR__ . '/../models/ClassModel.php';
require __DIR__ . '/../models/DayModel.php';
require __DIR__ . '/../models/TimeSlotModel.php';
require __DIR__ . '/../models/ScheduleModel.php';
require __DIR__ . '/../models/SubjectModel.php';



$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();
require_once __DIR__ . "/../configs/database.php";

$teacherModel = new TeacherModel($dbh);
$classroomModel = new classroomModel($dbh);
$classModel = new classModel($dbh);
$dayModel = new dayModel($dbh);
$timeSlotModel = new timeSlotModel($dbh);
$scheduleModel = new ScheduleModel($dbh);
$subjectModel = new SubjectModel($dbh);
$schoolId = 1;
//$scheduleGenerator -> generateSchedule($schoolId);


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
$dayNumber = $dayModel->getNumberOfDaysBySchoolId($schoolId);
$timeSlotNumber = $timeSlotModel->getNumberOfTimeslotsBySchoolId($schoolId);
$tabJourIdCreneauId = $dayModel->getDayTimeslotArrayBySchoolId($schoolId);
$allDayIds = $dayModel->getAllDayIds();

// Récupérer tous les IDs de créneaux horaires pour l'établissement spécifié
$allTimeslotIds = $timeSlotModel->getTimeslotIdsBySchoolId($schoolId);
$timePreferences = $scheduleModel->getTimePreferences($schoolId);


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
for ($i = 0; $i < $nbProf; $i++) {
    $profId = $teacherModel->getTeacherIdBySchoolIdAndIndex($schoolId, $i);
    $profVariatif[0][$i] = $profId; // id du prof
    $profVariatif[1][$i] = $tabJourIdCreneauId; // le tab Variatif    3 case : jourId    4 case : creneauId
    $profVariatif[2][$i] = $teacherModel->getAvailabilitiesByTeacherId($profId); // le tab des presence   3 case : jourId    4 case : creneauId
    $profVariatif[3][$i] = $teacherModel->getClassroomsByTeacherId($profId); // le classement des salle classe   3 case : classement apres le = IdsalleClasse
    $profVariatif[4][$i] = $teacherModel->getSubjectIdsByTeacherId($profId); // id de la/les matiere qu'il donne
    $profVariatif[5][$i] = $teacherModel->getTeacherHoursByTeacherId($profId); // nombre restant d'heure a donné
}

$endTime = microtime(true);
echo (number_format($endTime - $startTime, 4) . " => Ajout de profVariatif \n");
//CLASSE
//CLASSE
//CLASSE
$classeVariatif = array();
//parametre 1 = type de donnée
//parametre 2 = quel classe
$nbClasse = $classModel->getClassCountBySchoolId($schoolId);
$startTime = microtime(true);
for ($i = 0; $i < $nbClasse; $i++) {
    $classeId = $classModel->getClassIdBySchoolIdAndIndex($schoolId, $i);
    $classeVariatif[0][$i] = $classeId; // id de la classe
    $classeVariatif[1][$i] = $tabJourIdCreneauId; // le tab Variatif    3 case : jourId    4 case : creneauId
    $classeVariatif[2][$i] = $classModel->getClassSubjectsByClassId($classeId); // 3 : id de la matiere     valeur = nombre d'heure
}
//print_r($classeVariatif[1][0]);

$endTime = microtime(true);
echo (number_format($endTime - $startTime, 4) . " => Ajout de classeVariatif \n");
//SALLE DE CLASSE
//SALLE DE CLASSE
//SALLE DE CLASSE
//parametre 1 = type de donnée
//parametre 2 = quel salle de classe
$nbSalleClasse = $classroomModel->getClassroomCountBySchoolId($schoolId);
$startTime = microtime(true);
for ($i = 0; $i < $nbSalleClasse; $i++) {
    $salleClasseId = $classroomModel->getClassroomIdBySchoolIdAndIndex($schoolId, $i);
    $salleClasseVariatif[0][$i] = $salleClasseId; // Id de la salle de classe
    $classroomIdToI[$salleClasseId] = $i;
    $salleClasseVariatif[1][$i] = $tabJourIdCreneauId; // le tab variatif     3 case : jourId    4 case : creneauId
    $salleClasseVariatif[2][$i] = $classroomModel->getClassroomAvailabilitiesByClassroomId($salleClasseId); // le tab des presences      3 case : jourId    4 case : creneauId
    $salleClasseVariatif[3][$i] = $classroomModel->getClassroomSubjectsByClassroomId($salleClasseId); // Id de la/les matiere preferentiel
}



$endTime = microtime(true);
echo (number_format($endTime - $startTime, 4) . " => Ajout de salleClasseVariatif\n");
//MATIERE
//MATIERE
//MATIERE
for ($i = 0; $i < $subjectNumber; $i++) {
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



$totalHeureMatiereClasse =  $classModel->getClassHoursBySubject($schoolId);
$totalHeureMatiereProf =  $teacherModel->getTeacherHoursBySubject($schoolId);
$totPasTabClasse = 0;
for ($i = 0; $i < $subjectNumber; $i++) {
    if ($totalHeureMatiereProf[$subjects[$i]] > $totalHeureMatiereClasse[$subjects[$i]]) {
        echo ("la matiere id : " . $subjects[$i] . " contient trop de prof         / difference de : " . $totalHeureMatiereProf[$subjects[$i]] - $totalHeureMatiereClasse[$subjects[$i]] . "\n");
    } else if ($totalHeureMatiereProf[$subjects[$i]] < $totalHeureMatiereClasse[$subjects[$i]]) {
        echo ("la matiere id : " . $subjects[$i] . " ne contient pas asser de prof / difference de : " . $totalHeureMatiereClasse[$subjects[$i]] - $totalHeureMatiereProf[$subjects[$i]] . "\n");
    } else {
        echo ("la matiere id : " . $subjects[$i] . " contient assez de prof et de classe (il faudrait prevoir quand meme plus de prof)\n");
    }
    $totPasTabClasse += $totalHeureMatiereClasse[$subjects[$i]];
}
//print_r($totalHeureMatiereClasse);
$totalClassroomHours = $classroomModel-> getTotalClassroomHours($schoolId);
if ($totalClassroomHours > $totPasTabClasse) {
    echo("il y'auras a priori assez de classe : TotSalleClasse : $totalClassroomHours  TotClasse : $totPasTabClasse");
}
else {
    echo("il n'y aura pas assez de classe : TotSalleClasse : $totalClassroomHours  TotClasse : $totPasTabClasse");
}





$indices = range(0, $subjectNumber - 1);

shuffle($indices);


$doublonMatiere = 0;
$doublonProf = 0;
$doublonSalleClasse = 0;
$testSiProfPris = false;
$testSiSalleClassePris = false;
$testSiMatierePris = false;
$timeSlotsDays = $timeSlotModel->getTimePreferencesBySchoolId($schoolId);
print_r($days);

for ($i = 0; $i < $nbClasse; $i++) {
    $compteurCombineDayTSlot = 0;
    
    for ($y2 = 0; $y2 < $dayNumber; $y2++) {
        
        for ($j2 = 0; $j2 < $timeSlotNumber; $j2++) {
            $y = $timeSlotsDays[$compteurCombineDayTSlot]["dayId"]-1;
            $j = $timeSlotsDays[$compteurCombineDayTSlot]["timeslotId"]-1;

            $compteurCombineDayTSlot = $j2 + ($y2 * $timeSlotNumber);
            $tabClass_Schedule[0][$compteur] = $scheduleModel->createSchedule($schoolId); //id de l'horaire 
            $tabClass_Schedule[1][$compteur] = $days[$y]; //id du jour
            $tabClass_Schedule[2][$compteur] = $timeSlots[$j]; //id du creneau
            $tabClass_Schedule[3][$compteur] = $classeVariatif[0][$i]; //id de la classe
            $tabClass_Schedule[6][$compteur] = null;
            //quelle matiere ? : 
            if ($doublonMatiere == 0) {
                shuffle($indices);
                foreach ($indices as $compteurMatiere) {
                    //echo($compteurMatiere."\n");
                    if (isset($classeVariatif[2][$i][$subjects[$compteurMatiere]])) {
                        if ($classeVariatif[2][$i][$subjects[$compteurMatiere]] > 0) {

                            $tabClass_Schedule[6][$compteur] = $subjects[$compteurMatiere]; //id de la matiere
                            $testSiProfPris = false;
                            $testSiSalleClassePris = false;
                            for ($compteurProf = 0; $compteurProf < $nbProf - 1; $compteurProf++) {
                                for ($compteurProfMatiere = 0; $compteurProfMatiere < count($profVariatif[4][$compteurProf]); $compteurProfMatiere++) {

                                    if ($profVariatif[4][$compteurProf][$compteurProfMatiere] == $subjects[$compteurMatiere] && ($profVariatif[5][$compteurProf] >= $classeVariatif[2][$i][$subjects[$compteurMatiere]]) && ($profVariatif[2][$compteurProf][$days[$y]][$timeSlots[$j]] == "available" || $profVariatif[2][$compteurProf][$days[$y]][$timeSlots[$j]] == "prefer-not") ) {

                                        //la on est sure que ce prof donne au moins cette matiere on va donc le mettre : 
                                        $tabClass_Schedule[4][$compteur] = $profVariatif[0][$compteurProf]; //id du prof   
                                        //determiner la salle de classe
                                        for ($compteurProfSalleClasse=0; $compteurProfSalleClasse < count($profVariatif[3][$compteurProf]); $compteurProfSalleClasse++) { 
                                            //echo($profVariatif[3][$compteurProf][$compteurProfSalleClasse]."\n");
                                            $compteurSalleClasse = $classroomIdToI[$profVariatif[3][$compteurProf][$compteurProfSalleClasse+1]]; //equivalence de $i plus haut
                                            if ($salleClasseVariatif[2][$compteurSalleClasse][$days[$y]][$timeSlots[$j]] == "available" ) {
                                                
                                                $tabClass_Schedule[5][$compteur] = $classroomIdToI[$profVariatif[3][$compteurProf][$compteurProfSalleClasse+1]]; //id de la salle de classe
                                                $doublonSalleClasse = $classroomIdToI[$profVariatif[3][$compteurProf][$compteurProfSalleClasse+1]];
                                                $testSiSalleClassePris = true;
                                                $salleClasseVariatif[2][$compteurSalleClasse][$days[$y]][$timeSlots[$j]] = "unavailable"; 
                                                break;
                                            }
                                        }
                                        $testSiProfPris = true;
                                        break 2;
                                    }
                                }

                            }
                            if (!$testSiSalleClassePris && $testSiProfPris) {
                                //echo("$compteur \n");
                                for ($compteurSalleClasseBoucle=0; $compteurSalleClasseBoucle < $nbSalleClasse; $compteurSalleClasseBoucle++) { 
                                    for ($compteursalleClasseMatiere=0; $compteursalleClasseMatiere < count($salleClasseVariatif[3][$compteurSalleClasseBoucle]); $compteursalleClasseMatiere++) { 
                                        if (isset($salleClasseVariatif[3][$compteurSalleClasseBoucle][$subjects[$compteurMatiere]])) {
                                            $testSiSalleClassePris = true;
                                            $salleClasseVariatif[2][$compteurSalleClasseBoucle][$days[$y]][$timeSlots[$j]] = "unavailable";
                                            $tabClass_Schedule[5][$compteur] = $salleClasseVariatif[0][$compteurSalleClasseBoucle]; //id de la salle de classe
                                            break 2;
                                        }                                    }
                                    
                                }
                            }
                            if (!$testSiSalleClassePris && $testSiProfPris) {
                                for ($compteurSalleClasseBoucle=0; $compteurSalleClasseBoucle < $nbSalleClasse; $compteurSalleClasseBoucle++) { 
                                    if ($salleClasseVariatif[2][$compteurSalleClasseBoucle][$days[$y]][$timeSlots[$j]] == "available") {
                                        $salleClasseVariatif[2][$compteurSalleClasseBoucle][$days[$y]][$timeSlots[$j]] = "unavailable";
                                        $tabClass_Schedule[5][$compteur] = $salleClasseVariatif[0][$compteurSalleClasseBoucle];
                                        $testSiSalleClassePris = true;
                                        break;
                                    }
                                }
                            }
                            if (!$testSiSalleClassePris && $testSiProfPris) {
                                //echo($compteur."\n");
                                $tabClass_Schedule[5][$compteur] = null;
                            }
                            if (!$testSiProfPris) {

                                $tabClass_Schedule[4][$compteur] = null;
                            }
                            if ($classeVariatif[2][$i][$subjects[$compteurMatiere]] % 2 == 0) {
                                //echo("comtpeurProf : 2 : $compteurProf \n");
                                $profVariatif[5][$compteurProf] -= 2;
                                $classeVariatif[2][$i][$subjects[$compteurMatiere]] -= 2;
                                $doublonMatiere = $subjects[$compteurMatiere];
                                $doublonProf = $profVariatif[0][$compteurProf];
                                //echo("id matiere     : $subjects[$compteurMatiere] \n");


                            } else {
                                $profVariatif[5][$compteurProf] -= 1;
                                $classeVariatif[2][$i][$subjects[$compteurMatiere]] -= 1;
                                $doublonMatiere = 0;
                            }

                            break;
                        }
                    }
                }
            } else {
                $tabClass_Schedule[6][$compteur] = $doublonMatiere;
                $tabClass_Schedule[4][$compteur] = $doublonProf;
                $tabClass_Schedule[5][$compteur] = $doublonSalleClasse;
                $doublonMatiere = 0;
            }



            

            $compteur++;
        }
    }
    
}

$compteur = 0;
$endTime = microtime(true);
echo (number_format($endTime - $startTime, 4) . " => remplissage du tab\n");
$startTime = microtime(true);
for ($i = 0; $i < $nbClasse; $i++) {
    for ($y = 0; $y < $dayNumber; $y++) {
        for ($j = 0; $j < $timeSlotNumber; $j++) {
            if (isset($tabClass_Schedule[6][$compteur]) && $tabClass_Schedule[6][$compteur] != null) {
                if (isset($tabClass_Schedule[4][$compteur]) && $tabClass_Schedule[4][$compteur] != null) {
                    if ($tabClass_Schedule[5][$compteur] != null) {
                        $scheduleModel->createClassSchedule($tabClass_Schedule, $compteur);
                    }
                    else{
                        echo("pas assez de salle de classe\n");
                    }
                    
                } else {
                    echo ("pas assez de prof ou aucun prof disponible \n");
                }
            }

            $compteur++;
        }
    }
    echo ($compteur . "\n");
}
$endTime = microtime(true);
echo (number_format($endTime - $startTime, 4) . " => Ajout du tab dans la bdd\n");

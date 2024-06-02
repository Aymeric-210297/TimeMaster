<?php

require __DIR__ . '/../vendor/autoload.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

require_once __DIR__ . "/../configs/database.php";

require_once __DIR__ . '/printData.php';
require_once __DIR__ . '/addData.php';

$faker = Faker\Factory::create("fr_BE");
$compteur2 = 0;

$utilisateurs = array();
$utilisateur_etablissement = array();
$horaire_jour = array();
$classe_matiere = array();
$horaire_jour = array();
$professeur_matiere = array();
$presence_professeur = array();
$fourche_preferentiel = array();
$professeur_affectation = array();
$professeur_salleClasse = array();
$salleClasse_disponibilite = array();
$salleClasse_matiere = array();
$eleves = array();
$salleClasse = array();
$matieres = array();
$classes = array();
$professeurs = array();
$Etablissements = array();
$creneaux = array(
    ["08:25", "09:15", "10:20", "11:10", "12:50", "13:40", "14:40", "15:30"],
    ["09:15", "10:05", "11:10", "12:00", "13:40", "14:30", "15:30", "16:15"],
    ["1", "1", "2", "2", "3", "3", "4", "4"]
);
$jours = array(
    "Lundi",
    "Mardi",
    "Mercredi",
    "Jeudi",
    "Vendredi"
);
$matiereNom = array(
    "SVT",
    "Physique",
    "Chimie",
    "Géographie",
    "Écologie",
    "Mathématiques",
    "Technologie",
    "Histoire",
    "Éducation civique",
    "Langues étrangères",
    "Économie",
    "Arts plastiques",
    "Musique",
    "Éducation physique",
    "Informatique",
    "Français",
    "Biologie",
    "Santé et bien-être",
    "Sciences sociales",
    "Éducation environnementale"
);
$nbMatiere = 20; //si on modifie modifier le tab matiereNom
$nbUtilisateur = 5;
$nbJour = 5;
$nbCreneau = 8;
$nbProf = 150;
$nbEleve = 200;
$nbSalleClasse = 100;
$nbEtablissement = 2;
$nbClasse = 100; //max 800
$nbAnnee = 6;
$anneeDebut = 3;  //le nombre de classe sera ajusté en fonction du nombre d'année
$nbClasse = $nbClasse - ($nbClasse % ($nbAnnee - $anneeDebut));



$startTimeAll = microtime(true);
$tables = array(
    "user_school",
    "user",
    "classroom_subject",
    "classroom_availability",
    "teacher_classroom",
    "class_schedule",
    "classroom",
    "teacher_subject",
    "teacher_availability",
    "teacher_assignment",
    "teacher",
    "class_subject",
    "subject",
    "schedule_day",
    "time_preference",
    "day",
    "schedule_timeslot",
    "schedule",
    "student",
    "timeslot",
    "class",
    "school"
);
// Réinitialisation des données de chaque table
foreach ($tables as $table) {
    $startTime = microtime(true);
    $sql = "DELETE FROM $table;";
    $dbh->exec($sql);
    $sql = "ALTER TABLE $table AUTO_INCREMENT = 1;";
    $dbh->exec($sql);
    $endTime = microtime(true);
    echo "Ok => Suppression de : $table en " . number_format($endTime - $startTime, 4) . " secondes\n";
}
echo "Les données de la base de données ont été réinitialisées avec succès.\n";




$compteur = 0;
//--------------------------
//      ETABLISSEMENT
//--------------------------
$startTime = microtime(true);
for ($i = 0; $i < $nbEtablissement; $i++) {
    $Etablissements[$i][0] = supprimerPassageLigne($faker->address());
    $Etablissements[$i][1] = $faker->company();
    AddEtablissement($dbh, $Etablissements, $i);
    $Etablissements[$i][2] = $dbh->lastInsertId();
}
$endTime = microtime(true);
echo ("Ok => Ajout de établissement en " . number_format($endTime - $startTime, 4) . " secondes\n");
//--------------------------
//          JOUR
//--------------------------
$startTime = microtime(true);
for ($i = 0; $i < $nbJour; $i++) {
    addJour($dbh, $jours, $i);
}
$endTime = microtime(true);
echo ("Ok => Ajout de jour en " . number_format($endTime - $startTime, 4) . " secondes\n");
//--------------------------
//         CRENEAU
//--------------------------

$startTime = microtime(true);
for ($y = 0; $y < $nbEtablissement; $y++) {
    for ($i = 0; $i < $nbCreneau; $i++) {
    
        $creneaux[3][$i] = $Etablissements[$y][2];
        AddCreneau($dbh, $creneaux, $i);
        $creneaux[4][$i+($nbCreneau*$y)] = $dbh->lastInsertId();
        
    }
}

$endTime = microtime(true);
echo ("Ok => Ajout de creneau en " . number_format($endTime - $startTime, 4) . " secondes\n");

//--------------------------
//       UTILISATEUR
//--------------------------
$startTime = microtime(true);
for ($i = 0; $i < $nbUtilisateur; $i++) {
    $utilisateurs[$i][1] = $faker->firstName();
    $utilisateurs[$i][2] = $faker->lastName();
    $utilisateurs[$i][0] = $utilisateurs[$i][2] . "_" . $utilisateurs[$i][1] . "@" . $faker->freeEmailDomain();
    $utilisateurs[$i][3] = $faker->password(6, 8);
    AddUtilisateur($dbh, $utilisateurs, $i);
    $utilisateurs[$i][4] = $dbh->lastInsertId();
}
$endTime = microtime(true);
$utilisateurs[$nbUtilisateur][0] = "test@example.com"; 
$utilisateurs[$nbUtilisateur][1] = "John";
$utilisateurs[$nbUtilisateur][2] = "Doe";
$utilisateurs[$nbUtilisateur][3] = password_hash("test",PASSWORD_DEFAULT);
AddUtilisateur($dbh, $utilisateurs, $nbUtilisateur);
echo ("Ok => Ajout de utilisateur en " . number_format($endTime - $startTime, 4) . " secondes\n");



//--------------------------
//        MATIERE
//--------------------------
$startTime = microtime(true);
for ($y = 0; $y < $nbEtablissement; $y++) {
    for ($i = 0; $i < $nbMatiere; $i++) {
        $matieres[$compteur][0] = $matiereNom[$i];
        $matieres[$compteur][1] = $Etablissements[$y][2];
        AddMatiere($dbh, $matieres, $compteur);
        $matieres[$compteur][2] = $dbh->lastInsertId();
        $compteur++;
    }
}
$endTime = microtime(true);
echo ("Ok => Ajout de matiere en " . number_format($endTime - $startTime, 4) . " secondes\n");
$compteur = 0;
//--------------------------
//         CLASSE
//--------------------------
$startTime = microtime(true);
$optionsUtilisees = []; // Tableau pour stocker les options déjà utilisées

for ($i = 0; $i < $nbEtablissement; $i++) {
    reset($optionsUtilisees);
    for ($y = 0; $y < $nbClasse / ($nbAnnee - $anneeDebut); $y++) {
        $prefixes = ['TQ', 'TT', 'P', 'GT'];
        $prefix = $prefixes[array_rand($prefixes)];

        // Générer une option unique
        do {
            $suffix = $faker->randomElement([$faker->randomElement(['A', 'E', 'I', 'O', 'U']), strtoupper($faker->randomLetter(1, true)) . $faker->randomElement(['A', 'E', 'I', 'O', 'U'])]);
            $option = $prefix . $suffix;
        } while (in_array($option, $optionsUtilisees)); // Vérifier si l'option est déjà utilisée

        $optionsUtilisees[] = $option; // Ajouter l'option utilisée au tableau

        $aleaEtablissement = rand(0, $nbEtablissement);

        for ($j = 0; $j < $nbAnnee - $anneeDebut; $j++) {
            $classes[$compteur][0] = ($j + 1 + $anneeDebut) . $option;
            $classes[$compteur][1] = $Etablissements[$i][2];

            AddClasse($dbh, $classes, $compteur);
            $classes[$compteur][2] = $dbh->lastInsertId();
            $compteur++;
        }
    }
}
$endTime = microtime(true);
echo ("Ok => Ajout de classe en " . number_format($endTime - $startTime, 4) . " secondes\n");

$compteur = 0;
//--------------------------
//         ELEVE
//--------------------------
$startTime = microtime(true);

for ($i = 0; $i < $nbEtablissement; $i++) {
    for ($y = 0; $y < $nbEleve; $y++) {
        $eleves[$compteur][2] = $faker->firstName();
        $eleves[$compteur][1] = $faker->lastName();
        $eleves[$compteur][0] = supprimerEspace($eleves[$compteur][2] . "_" . $eleves[$compteur][1] . $compteur . "@site.com");
        $eleves[$compteur][3] = $i + 1;
        $eleves[$compteur][4] = rand(1, $nbClasse + 1);
        addEleve($dbh, $eleves, $compteur);
        $eleves[$compteur][5] = $dbh->lastInsertId();
        $compteur++;
    }
}
$endTime = microtime(true);
echo ("Ok => Ajout de eleve en " . number_format($endTime - $startTime, 4) . " secondes\n");
$compteur = 0;
//--------------------------
//UTILISATEUR_ETABLISSEMENT
//--------------------------
$startTime = microtime(true);

for ($i = 0; $i < $nbUtilisateur; $i++) {
    for ($y = 0; $y < $nbEtablissement; $y++) {
        $utilisateur_etablissement[$compteur][0] = $utilisateurs[$i][4];
        $utilisateur_etablissement[$compteur][1] = $Etablissements[$y][2];
        AddUtilisateur_Etablissement($dbh, $utilisateur_etablissement, $compteur);
        $compteur++;
    }
}

$utilisateur_etablissement[$compteur+1][0] = $nbUtilisateur+1;
$utilisateur_etablissement[$compteur+1][1] = 1;
AddUtilisateur_Etablissement($dbh, $utilisateur_etablissement, $compteur+1);
$utilisateur_etablissement[$compteur+1][1] = 2;
AddUtilisateur_Etablissement($dbh, $utilisateur_etablissement, $compteur+1);
$endTime = microtime(true);
echo ("Ok => Ajout de utilisateur_etablissement en " . number_format($endTime - $startTime, 4) . " secondes\n");
$compteur = 0;

//--------------------------
//       PROFESSEUR
//--------------------------
$startTime = microtime(true);
$probabilites = [
    8 => 5,
    10 => 5,
    12 => 5,
    14 => 5,
    16 => 5,
    18 => 5,
    20 => 5,
    22 => 5,
    24 => 5,
    26 => 5,
    28 => 10,
    30 => 10,
    32 => 10,
    34 => 10,
    36 => 10,
];
$totalHeureProfs = 0;
for ($y = 0; $y < $nbEtablissement; $y++) {
    for ($i = 0; $i < $nbProf; $i++) {
        if (rand(0, 1) == 0) {
            $professeurs[$compteur][0] = "F";
            $professeurs[$compteur][1] = $faker->firstNameFemale();
        } else {
            $professeurs[$compteur][0] = "M";
            $professeurs[$compteur][1] = $faker->firstNameMale();
        }
        $professeurs[$compteur][2] = $faker->lastName();
        $professeurs[$compteur][3] = str_replace(' ', '', $professeurs[$compteur][2]) . "." . $professeurs[$compteur][1] . "_" . $compteur . "@site.ecole.be";
        $professeurs[$compteur][4] = $Etablissements[$y][2];
        $chiffreAleatoire = genererValeurAleatoire($probabilites);
        $professeurs[$compteur][5] = $chiffreAleatoire;
        $totalHeureProfs += $chiffreAleatoire;
        addProf($dbh, $professeurs, $compteur);
        $professeurs[$compteur][6] = $dbh->lastInsertId();
        $compteur++;
    }
}
$professeurs[$compteur+1][0] = "M";
$professeurs[$compteur+1][1] = "X";
$professeurs[$compteur+1][2] = "X";
$professeurs[$compteur+1][3] = "test@example.com";
$professeurs[$compteur+1][4] = 1;
$professeurs[$compteur+1][5] = 0;
addProf($dbh, $professeurs, $compteur+1);
$professeurs[$compteur+1][4] = 2;

addProf($dbh, $professeurs, $compteur+1);
$compteur = 0;
$endTime = microtime(true);
echo ("Ok => Ajout de professeur en " . number_format($endTime - $startTime, 4) . " secondes // Nb heure total : $totalHeureProfs\n");
//--------------------------
//   PRESENCE_PROFESSEUR
//--------------------------
$startTime = microtime(true);
$probabilites = [
    1 => 20,
    2 => 20,
    3 => 60
];
for ($a = 0; $a < $nbEtablissement; $a++) {
    for ($i=0; $i < $nbProf; $i++) { 
        for ($y = 0; $y < $nbJour; $y++) {
            for ($j = 0; $j < $nbCreneau; $j++) {
                $chiffreAleatoire = genererValeurAleatoire($probabilites);
                
                $presence_professeur[$compteur][0] = $i+($nbProf*$a)+1;
                $presence_professeur[$compteur][1] = $creneaux[4][$j +($nbCreneau*$a)];
                $presence_professeur[$compteur][2] = $y + 1;
                $presence_professeur[$compteur][3] = $chiffreAleatoire;
                AddPresenceProfesseur($dbh, $presence_professeur, $compteur);
                $compteur++;
            }
        }
    }
}
$endTime = microtime(true);
echo ("Ok => Ajout de presence_professeur en " . number_format($endTime - $startTime, 4) . " secondes\n");
$compteur = 0;
//--------------------------
//      SALLE CLASSE
//--------------------------
$startTime = microtime(true);
for ($i = 0; $i < $nbEtablissement; $i++) {
    for ($y = 0; $y < $nbSalleClasse; $y++) {
        $salleClasse[$compteur][0] = $compteur;
        $salleClasse[$compteur][1] = rand(20, 25);
        $random_number = rand(1, 100);
        if ($random_number <= 80) {
            $salleClasse[$compteur][2] = 1;
        } else {
            $salleClasse[$compteur][2] = 0;
        }
        $salleClasse[$compteur][3] = $Etablissements[$i][2];
        AddSalleClasse($dbh, $salleClasse, $compteur);
        $salleClasse[$compteur][4] = $dbh->lastInsertId();
        $compteur++;
    }
}
$compteur = 0;
$endTime = microtime(true);
echo ("Ok => Ajout de salle de classe en " . number_format($endTime - $startTime, 4) . " secondes\n");
//--------------------------
//     CLASSE_MATIERE
//--------------------------
$startTime = microtime(true);
$limitNbHeure = 0;
$totalHeureClasse = 0;
for ($j = 0; $j < $nbEtablissement; $j++) {
    for ($y = 0; $y < $nbClasse; $y++) {
        for ($i = 0; $i < $nbMatiere; $i++) {
            if ($limitNbHeure < $nbCreneau*$nbJour-3) {
                $random_number = rand(0, 4);
                if ($random_number != 0) {
                    $classe_matiere[$compteur][0] = $random_number;
                    $classe_matiere[$compteur][1] = $classes[$compteur2][2];
                    $classe_matiere[$compteur][2] = $matieres[$i+($nbMatiere*$j)][2];
                    AddClass_Matiere($dbh, $classe_matiere, $compteur);
                    $classe_matiere[$compteur][3] = $dbh->lastInsertId();
                    $limitNbHeure += $random_number;
                } else {
                    $classe_matiere[$compteur][0] = null;
                }
                $totalHeureClasse += $random_number;
                $compteur++;
            }
            else {
                break;
            }

        }
        $limitNbHeure = 0;
        $compteur2++;
    }
}
$endTime = microtime(true);
echo ("Ok => Ajout de classe_matiere en " . number_format($endTime - $startTime, 4) . " secondes // Nb heure total : $totalHeureClasse\n");
$compteur = 0;
$compteur2 = 0;
//--------------------------
//  PROFESSEUR_MATIERE
//--------------------------
$startTime = microtime(true);

for ($i=0; $i < $nbEtablissement; $i++) { 
    for ($y = 0; $y < $nbProf ; $y++) {
        $random_number = rand(1, $nbMatiere);
        $professeur_matiere[$y+($nbProf*$i)][0] = $professeurs[$y+($nbProf*$i)][6];
        $professeur_matiere[$y+($nbProf*$i)][1] = $random_number+($nbMatiere*$i);
        AddProfesseur_Matiere($dbh, $professeur_matiere, $y+($nbProf*$i));
        $professeur_matiere[$y+($nbProf*$i)][2] = $dbh->lastInsertId();
    }
}
$endTime = microtime(true);
echo ("Ok => Ajout de professeur_matiere en " . number_format($endTime - $startTime, 4) . " secondes\n");
//--------------------------
//  PROFESSEUR_AFFECTATION
//--------------------------
/*
$probabilites = [
    8 => 5,
    10 => 5,
    12 => 5,
    14 => 5,
    16 => 5,
    18 => 5,
    20 => 5,
    22 => 5,
    24 => 5,
    26 => 5,
    28 => 10,
    30 => 10,
    32 => 10,
    34 => 10,
    36 => 10,
];

$aleatoireNombreHeure = 0;
$profDejaDonneCours = array();


//creation du tableau de profs :
for ($i=0; $i < $nbProf*$nbEtablissement; $i++) { 
    $profDejaDonneCours[$i][0] = $professeur_matiere[$i][1]; //id de la matiere
    $aleatoireNombreHeure = genererValeurAleatoire($probabilites);
    $profDejaDonneCours[$i][1] = $aleatoireNombreHeure; // nb d'heure restante a donner
}
$classeHeureMatiere = array();
$lastValues = array();
//recap de donnée que j'ai
//prof et le nombre d'heure restante a donnée
//telle classe tel nombre d'heure
for ($i=0; $i < $nbClasse*$nbEtablissement; $i++) { 
    for ($y=0; $y < $nbMatiere; $y++) {
        //calcul du prof
        $check = true; 
        do {
            for ($j=0; $j < $nbProf*$nbEtablissement; $j++) { 
                //echo($profDejaDonneCours[$j][1]);
                if ($profDejaDonneCours[$j][0] == $y+1 && $classe_matiere[$compteur][0] <= $profDejaDonneCours[$j][1]) {
                    //verifie si ya un prof de dispo
                    $professeur_affectation[$compteur][0] = $j+1; //prof id
                    $profDejaDonneCours[$j][1] -= $classe_matiere[$compteur][0];
                    $check = false;
                    break;
                }
                else{
                   
                }
               echo("e");
            }
            $check = false;
            
            
        } while ($check);
        
        $professeur_affectation[$compteur][1] = $i+1; //classe id
        $professeur_affectation[$compteur][2] = $y+1; //matiereId
        $professeur_affectation[$compteur][3] = $classe_matiere[$compteur][0]; //nombre heure
        $compteur ++;
        echo($compteur . "\n");
    }
}



for ($i=0; $i < $nbClasse*$nbEtablissement; $i++) { 
    $aleatoireNombreHeure = genererValeurAleatoire($probabilites2);
    $classeHeureMatiere[$i][0] = $aleatoireNombreHeure;
    $totalHeureClasse += $aleatoireNombreHeure;
    $lastValues = array();
    for ($j=0; $j < $classeHeureMatiere[$i][0]; $j++) { 
        $chiffreAleatoire = rand(1,$nbMatiere);
        if ($classeHeureMatiere[$i][1][$chiffreAleatoire] == null) {
            $classeHeureMatiere[$i][1][$chiffreAleatoire] = 0;
        }
        else {
            $classeHeureMatiere[$i][1][$chiffreAleatoire] ++;
        }
    }
}*/
//echo("le nombre d'heure des classe au total est de : " + $totalHeureClasse + " Tandis que le nombre d'heures total des profs est de : " + $totalHeureProfs + " Il y'a donc une difference de : " + $totalHeureProfs - $totalHeureClasse + "si positif trop de prof si negatif pas assez de profs");

//on sait donc pour chaque classe le nombre d'heure de tel matiere qu'elle va recevoir il faut mtn choisir le prof

//--------------------------
//  SALLECLASSE_MATIERE
//--------------------------
$startTime = microtime(true);

for ($y = 0; $y < $nbSalleClasse * $nbEtablissement; $y++) {
    $chiffreAleatoire = rand(0, 3);
    $matieresUtilisees = array();

    for ($j = 0; $j < $chiffreAleatoire; $j++) {
        do {
            $numMatiere = rand(1, $nbMatiere);
        } while (in_array($numMatiere, $matieresUtilisees));

        $matieresUtilisees[] = $numMatiere;

        $salleClasse_matiere[$compteur][0] = $y + 1;
        $salleClasse_matiere[$compteur][1] = $numMatiere;
        AddSalleClasseMatiere($dbh, $salleClasse_matiere, $compteur);
        $compteur++;
    }
}

$endTime = microtime(true);
echo ("Ok => Ajout de salleClasse_matiere en " . number_format($endTime - $startTime, 4) . " secondes\n");
$compteur = 0;

//--------------------------
//  FOURCHE_PREFERENTIEL
//--------------------------
$startTime = microtime(true);
$multiplicateur = 0;
for ($i = 0; $i < $nbEtablissement; $i++) {
    for ($y = 0; $y < $nbJour; $y++) {
        for ($j = 0; $j < $nbCreneau; $j++) {
            $fourche_preferentiel[$compteur][0] = $j + ($nbCreneau * $multiplicateur) + 1;
            $fourche_preferentiel[$compteur][1] = $y + 1;
            if ($j == 0 or $j == 1) {
                //08h25-10h05 :
                $fourche_preferentiel[$compteur][2] = 2;
            } else if ($j == 2 or $j == 3) {
                //10h20-12h00 :
                $fourche_preferentiel[$compteur][2] = 3;
            } else if ($j == 4) {
                //12h00-12h50 :
                $fourche_preferentiel[$compteur][2] = 0;
            }
            if ($y == 2) {
                //Mercredi aprem :
                if ($j >= 5) {
                    $fourche_preferentiel[$compteur][2] = 0;
                }

            } else {
                //autres jour
                if ($j == 5 or $j == 6) {
                    $fourche_preferentiel[$compteur][2] = 3;
                }
                if ($j == 7 or $j == 8) {
                    $fourche_preferentiel[$compteur][2] = 1;
                }

            }
            AddFourchePreferentiel($dbh, $fourche_preferentiel, $compteur);
            $compteur++;
        }
    }
    $multiplicateur++;
}
$endTime = microtime(true);
echo ("Ok => Ajout de fourche_preferentiel en " . number_format($endTime - $startTime, 4) . " secondes\n");
$compteur = 0;



//--------------------------
//  PROFESSEUR_SALLECLASSE
//--------------------------
$startTime = microtime(true);
for ($i = 0; $i < $nbProf * $nbEtablissement; $i++) {
    $chiffreAleatoire = rand(0, 5);
    $sallesUtilisees = array(); // Initialisation d'un tableau pour les salles utilisées

    for ($y = 0; $y < $chiffreAleatoire; $y++) {
        // Générer un numéro de salle unique pour cette itération
        do {
            $numSalle = rand(1, $nbSalleClasse);
        } while (in_array($numSalle, $sallesUtilisees));

        $sallesUtilisees[] = $numSalle; // Ajouter le numéro de salle au tableau des salles utilisées

        $professeur_salleClasse[$compteur][0] = $i + 1;
        $professeur_salleClasse[$compteur][1] = $numSalle;
        $professeur_salleClasse[$compteur][2] = $y + 1;

        AddProfesseurSalleClasse($dbh, $professeur_salleClasse, $compteur);
        $compteur++;
    }
}
$compteur = 0;
$endTime = microtime(true);
echo ("Ok => Ajout de professeur_salleClasse en " . number_format($endTime - $startTime, 4) . " secondes\n");

//--------------------------
//SALLECLASSE_DISPONIBILITE
//--------------------------
$startTime = microtime(true);
$probabilites = [
    1 => 5,
    2 => 95
];
for ($i = 0; $i < $nbSalleClasse * $nbEtablissement; $i++) {
    for ($y = 0; $y < $nbJour; $y++) {
        for ($j = 0; $j < $nbCreneau; $j++) {
            $salleClasse_disponibilite[$compteur][0] = $i + 1;
            $salleClasse_disponibilite[$compteur][1] = $j + 1;
            $salleClasse_disponibilite[$compteur][2] = $y + 1;
            $salleClasse_disponibilite[$compteur][3] = genererValeurAleatoire($probabilites);
            AddSalleClasseDisponibilite($dbh, $salleClasse_disponibilite, $compteur);
            $compteur++;
        }
    }
}
$endTime = microtime(true);
echo ("Ok => Ajout de salleClasse_disponibilite en " . number_format($endTime - $startTime, 4) . " secondes\n");

$endTimeAll = microtime(true);
echo("--------------------\nTemps Total de la génération des données : " . number_format($endTimeAll - $startTimeAll, 4) . " secondes\n");


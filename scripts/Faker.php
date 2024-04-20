<?php

require __DIR__ . '/../vendor/autoload.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

require_once __DIR__ . "/../configs/database.php";

require_once __DIR__ . '/printData.php';
require_once __DIR__ . '/addData.php';

$faker = Faker\Factory::create("fr_BE");
//Utilisateur
$nbUtilisateur = 5;
$utilisateurs = array();
//lien
$utilisateur_etablissement = array();
$horaire_jour = array();
$classe_matiere = array();
$horaire_jour = array();

$nbEleve = 1200;
$eleves = array();

$nbClasse = 100; //max 800
$nbAnnee = 6;
$anneeDebut = 3;
//le nombre de classe sera ajusté en fonction du nombre d'année
$nbClasse = $nbClasse - ($nbClasse % ($nbAnnee - $anneeDebut));
$classes = array();

$nbEtablissement = 2;
$Etablissements = array();

$nbMatiere = 20;
$matieres = array();
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

$nbProf = 10;
$professeurs = array();
$tables = array("donnee", "classe_matiere", "professeur_matiere", "horaire_creneau", "horaire_jour", "utilisateur_etablissement", "utilisateur", "salleClasse_matiere", "creneau", "classe", "matiere", "professeur", "eleve", "salleClasse", "horaire", "jour", "horaire_creneau", "horaire_jour","etablissement");

$compteur = 0;
// Réinitialisation des données de chaque table
foreach ($tables as $table) {
    // Suppression des données de la table
    $sql = "DELETE FROM $table;";
    $dbh->exec($sql);
    // Réinitialisation de la valeur d'auto-incrémentation
    $sql = "ALTER TABLE $table AUTO_INCREMENT = 1;";
    $dbh->exec($sql);
    echo("supression de : " . $table . " -> V\n");
}
echo "Les données de la base de données ont été réinitialisées avec succès.";






//--------------------------
//      ETABLISSEMENT
//--------------------------

for ($i = 0; $i < $nbEtablissement; $i++) {
    $Etablissements[$i][0] = supprimerPassageLigne($faker->address());
    $Etablissements[$i][1] = $faker->company();
    AddEtablissement($dbh, $Etablissements, $i);
    $Etablissements[$i][2] = $dbh->lastInsertId();
}


PrintTabEtablissement($Etablissements);

//--------------------------
//       UTILISATEUR
//--------------------------
for ($i = 0; $i < $nbUtilisateur; $i++) {
    $utilisateurs[$i][1] = $faker->firstName();
    $utilisateurs[$i][2] = $faker->lastName();
    $utilisateurs[$i][0] = $utilisateurs[$i][2]."_".$utilisateurs[$i][1]."@".$faker->freeEmailDomain();
    $utilisateurs[$i][3] = $faker->password(6, 8);
    AddUtilisateur($dbh, $utilisateurs, $i);
    $utilisateurs[$i][4] = $dbh->lastInsertId();

}
PrintTabUtilisateur($utilisateurs);



//--------------------------
//        MATIERE
//--------------------------
for ($y = 0; $y < $nbEtablissement; $y++) {
    for ($i = 0; $i < $nbMatiere; $i++) {
        $matieres[$compteur][0] = $matiereNom[$i];
        $matieres[$compteur][1] = $Etablissements[$y][2];
        AddMatiere($dbh, $matieres, $compteur);
        $matieres[$compteur][2] = $dbh->lastInsertId();
        $compteur++;
    }
}

$compteur = 0;
//--------------------------
//         CLASSE
//--------------------------
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
            $classes[$compteur][0] = ($j + 1 + $anneeDebut) . $option ;
            $classes[$compteur][1] = $Etablissements[$i][2];

            AddClasse($dbh, $classes, $compteur);
            $classes[$compteur][2] = $dbh->lastInsertId();
            $compteur++;

        }
    }
}
PrintTabClasse($classes);

$compteur = 0;
//--------------------------
//         ELEVE
//--------------------------


for ($i = 0; $i < $nbEtablissement; $i++) {
    for ($y = 0; $y < $nbEleve; $y++) {
        $eleves[$compteur][2] = $faker->firstName();
        $eleves[$compteur][1] = $faker->lastName();
        $eleves[$compteur][0] = supprimerEspace($eleves[$compteur][2] . "_" . $eleves[$compteur][1] . $compteur . "@site.com");
        $eleves[$compteur][3] = $i + 1;
        $eleves[$compteur][4] = rand(0, $nbClasse);
        addEleve($dbh, $eleves, $compteur);
        $eleves[$compteur][5] = $dbh->lastInsertId();
        $compteur++;
    }
}
PrintEleve($eleves);
$compteur = 0;
//--------------------------
//UTILISATEUR_ETABLISSEMENT
//--------------------------

PrintTabUtilisateur($utilisateurs);
for ($i = 0; $i < $nbUtilisateur; $i++) {
    for ($y = 0; $y < $nbEtablissement; $y++) {
        $utilisateur_etablissement[$compteur][0] = $utilisateurs[$i][4];
        $utilisateur_etablissement[$compteur][1] = $Etablissements[$y][2];
        AddUtilisateur_Etablissement($dbh, $utilisateur_etablissement, $compteur);
        $compteur++;
    }
}
PrintTabUtilisateur_Etablissement($utilisateur_etablissement);
$compteur = 0;

//--------------------------
//       PROFESSEUR
//--------------------------

for ($i = 0; $i < $nbProf; $i++) {
    if (rand(0, 1) == 0) {
        $professeurs[$i][0] = "Mme.";
        $professeurs[$i][1] = $faker->firstNameFemale();

    } else {
        $professeurs[$i][0] = "Mr.";
        $professeurs[$i][1] = $faker->firstNameMale();
    }
    $professeurs[$i][2] = $faker->lastName();
    $professeurs[$i][3] = str_replace(' ', '', $professeurs[$i][2]) . "." . $professeurs[$i][1] . "@site.ecole.be";
    addProf($dbh, $professeurs, $i);
    $professeurs[$i][4] = $dbh->lastInsertId();
}
PrintTabProf($professeurs);


//--------------------------
//     CLASSE_MATIERE
//--------------------------

for ($y = 0; $y < $nbClasse; $y++) {
    for ($i = 0; $i < $nbMatiere; $i++) {

    }
}

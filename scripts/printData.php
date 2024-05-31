<?php

 
function generateUniqueRandom($min, $max, &$lastValues) {
    do {
        $newValue = rand($min, $max);
    } while (in_array($newValue, $lastValues));
    
    // Ajouter la nouvelle valeur générée au tableau des dernières valeurs
    array_push($lastValues, $newValue);

    // Limiter la taille du tableau à 10
    if (count($lastValues) > 10) {
        array_shift($lastValues); // Retirer la première valeur (la plus ancienne)
    }

    return $newValue;
}
function genererValeurAleatoire($probabilites) {
    // Vérifiez que la somme des probabilités est égale à 100
    $sommeProbabilites = array_sum($probabilites);
    if ($sommeProbabilites != 100) {
        throw new Exception("La somme des probabilités doit être égale à 100. Actuelle somme: $sommeProbabilites");
    }

    // Génère un nombre aléatoire entre 1 et 100
    $nombreAleatoire = rand(1, 100);

    // Détermine quel chiffre correspond à ce nombre aléatoire selon les probabilités
    $seuil = 0;
    foreach ($probabilites as $chiffre => $probabilite) {
        $seuil += $probabilite;
        if ($nombreAleatoire <= $seuil) {
            return $chiffre;
        }
    }
}



function afficherTableau($tab, $separateur = ' ')
{
    $rows = count($tab);
    $cols = count($tab[0]);
    echo "\n";
    for ($i = 0; $i < $rows; $i++) {
        for ($j = 0; $j < $cols; $j++) {
            echo $tab[$i][$j];
            if ($j < $cols - 1) {
                echo $separateur;
            }
        }
        echo "\n";
    }
}



function PrintTabEtablissement($tab)
{
    for ($i = 0; $i < count($tab); $i++) {
        echo $tab[$i][0] . " | ";
        echo $tab[$i][1] . " ";

    }
}


function PrintTabClasseMatiere($tab)
{
    for ($i = 0; $i < count($tab); $i++) {
        if ($tab[$i][0] != null) {
            echo $tab[$i][0] . " | ";
            echo $tab[$i][1] . " ";
            echo $tab[$i][2] . " ";
            echo "\n";
        }
        

    }
}
function PrintTabUtilisateur($tab)
{
    for ($i = 0; $i < count($tab); $i++) {
        echo $tab[$i][0] . " ";
        echo $tab[$i][1] . " ";
        echo $tab[$i][2] . " ";
        echo $tab[$i][3] . " ";
        echo "  |id : " . $tab[$i][4];
        echo "\n";
    }
}
function PrintTabProf($tab)
{
    for ($i = 0; $i < count($tab); $i++) {
        echo $tab[$i][0] . " ";
        echo $tab[$i][1] . " ";
        echo $tab[$i][2] . " ";
        echo $tab[$i][3] . " ";
        echo "  |id : " . $tab[$i][4];
        echo "\n";
    }
}
function PrintTabClasse($tab)
{
    for ($i = 0; $i < count($tab); $i++) {
        echo $tab[$i][0] . " ";
        echo $tab[$i][1] . " ";
        echo "  |id : " . $tab[$i][2];
        echo "\n";


    }
}
function PrintTabUtilisateur_Etablissement($tab)
{
    for ($i = 0; $i < count($tab); $i++) {
        echo $tab[$i][0] . " ";
        echo $tab[$i][1] . " ";
        echo "\n";
    }
}
function supprimerEspace($chaine)
{
    // Utilisation de la fonction str_replace pour remplacer les sauts de ligne par une chaîne vide
    $chaine = str_replace(" ", "_", $chaine);
    return $chaine;
}

function supprimerPassageLigne($chaine)
{
    // Utilisation de la fonction str_replace pour remplacer les sauts de ligne par une chaîne vide
    $chaine = str_replace("\n", ",", $chaine);
    return $chaine;
}

function PrintEleve($tab)
{
    for ($i = 0; $i < count($tab); $i++) {
        echo $tab[$i][0] . "|";
        echo $tab[$i][1] . "|";
        echo $tab[$i][2] . "|";
        echo $tab[$i][3] . "|";
        echo $tab[$i][4] . "id : ";
        echo $tab[$i][5] . "";
        echo "\n";
    }
}

function PrintProf_Matiere($tab)
{
    for ($i = 0; $i < count($tab); $i++) {
        echo $tab[$i][0] . "|";
        echo $tab[$i][1] . "|";

        echo "\n";
    }
}
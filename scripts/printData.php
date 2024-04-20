<?php





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

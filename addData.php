<?php
function AddUtilisateur_Etablissement($dbh,$utilisateur_etablissement,$numero){
    try {
        $query = "insert into utilisateur_etablissement (utilisateurId,etablissementId) values (:utilisateurId,:etablissementId)";
        $addEtablissement = $dbh->prepare($query);
        $addEtablissement->execute([
            'utilisateurId' => $utilisateur_etablissement[$numero][0],
            'etablissementId' => $utilisateur_etablissement[$numero][1],
        ]);
        $listeCreneau = $addEtablissement->fetchAll();
        return $listeCreneau;
    } catch (PDOException $e) {
        $message = $e->getMessage();
        die($message);
    }
}
function AddEtablissement($dbh,$Etablissements,$numeroEtablissement){
    try {
        $query = "insert into etablissement (etablissementAdresse,etablissementNom) values (:etablissementAdresse,:etablissementNom)";
        $addEtablissement = $dbh->prepare($query);
        $addEtablissement->execute([
            'etablissementAdresse' => $Etablissements[$numeroEtablissement][0],
            'etablissementNom' => $Etablissements[$numeroEtablissement][1],
        ]);
        $listeCreneau = $addEtablissement->fetchAll();
        return $listeCreneau;
    } catch (PDOException $e) {
        $message = $e->getMessage();
        die($message);
    }
}

function AddUtilisateur($dbh,$utilisateurs,$numeroUtilisateur){
    try {
        $query = "insert into utilisateur (utilisateurEmail,utilisateurNom,utilisateurPrenom,utilisateurMotDePasse) values (:utilisateurEmail,:utilisateurNom,:utilisateurPrenom,:utilisateurMotDePasse)";
        $addUtilisateur = $dbh->prepare($query);
        $addUtilisateur->execute([
            'utilisateurEmail' => $utilisateurs[$numeroUtilisateur][0],
            'utilisateurNom' => $utilisateurs[$numeroUtilisateur][2],
            'utilisateurPrenom' => $utilisateurs[$numeroUtilisateur][1],
            'utilisateurMotDePasse' => $utilisateurs[$numeroUtilisateur][3],
        ]);
        $listeCreneau = $addUtilisateur->fetchAll();
        return $listeCreneau;
    } catch (PDOException $e) {
        $message = $e->getMessage();
        die($message);
    }
}
function AddMatiere($dbh,$Matieres,$numeroMatiere){
    try {
        $query = "insert into matiere (matiereNom,etablissementId) values (:matiereNom,:etablissementId)";
        $addClasse = $dbh->prepare($query);
        $addClasse->execute([
            'matiereNom' => $Matieres[$numeroMatiere][0],
            'etablissementId' => $Matieres[$numeroMatiere][1],
        ]);
    } catch (PDOException $e) {
        $message = $e->getMessage();
        die($message);
    }
}
function AddClasse($dbh,$Classes,$numeroClasse){
    try {
        $query = "insert into classe (classeRef,etablissementId) values (:classeRef,:etablissementId)";
        $addClasse = $dbh->prepare($query);
        $addClasse->execute([
            'classeRef' => $Classes[$numeroClasse][0],
            'etablissementId' => $Classes[$numeroClasse][1],
        ]);
    } catch (PDOException $e) {
        $message = $e->getMessage();
        die($message);
    }
}
function addEleve($dbh,$Eleves,$numeroEleve){
    try {
        $query = "insert into eleve (EleveEmail,eleveNom,elevePrenom,etablissementId,classeId) values (:EleveEmail,:elevePrenom,:eleveNom,:etablissementId,:classeId)";
        $addEleve = $dbh->prepare($query);
        $addEleve->execute([
            'EleveEmail' => $Eleves[$numeroEleve][0],
            'eleveNom' => $Eleves[$numeroEleve][1],
            'elevePrenom' => $Eleves[$numeroEleve][2],
            'etablissementId' => $Eleves[$numeroEleve][3],
            'classeId' => $Eleves[$numeroEleve][4]
        ]);
    } catch (PDOException $e) {
        $message = $e->getMessage();
        die($message);
    }
}


function AddProf($dbh,$professeurs,$numeroProf){
    try {
        $query = "insert into professeur(professeurEmail,professeurNom,professeurPrenom,etablissementId) values (:professeurEmail,:professeurNom,:professeurPrenom,:etablissementId);";
        $addProf = $dbh->prepare($query);
        $addProf->execute([
            'professeurEmail' => $professeurs[$numeroProf][3],
            'professeurNom' => $professeurs[$numeroProf][2],
            'professeurPrenom' => $professeurs[$numeroProf][1],
            'etablissementId' => 1
        ]);
        $listeCreneau = $addProf->fetchAll();
        return $listeCreneau;
    } catch (PDOException $e) {
        $message = $e->getMessage();
        die($message);
    }
}

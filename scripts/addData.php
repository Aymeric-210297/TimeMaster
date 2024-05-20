<?php
function AddSalleClasseMatiere($dbh, $salleClasse_matiere, $numeroSalleClasseMatiere)
{
    try {
        $query = "INSERT INTO salleClasse_matiere (salleClasseId, matiereId) VALUES (:salleClasseId, :matiereId);";
        $addAffectation = $dbh->prepare($query);
        $addAffectation->execute([
            'salleClasseId' => $salleClasse_matiere[$numeroSalleClasseMatiere][0],
            'matiereId' => $salleClasse_matiere[$numeroSalleClasseMatiere][1],
        ]);
        $listeAffectation = $addAffectation->fetchAll();
        return $listeAffectation;
    } catch (PDOException $e) {
        $message = $e->getMessage();
        die($message);
    }
}
function AddSalleClasseDisponibilite($dbh, $salleClasse_disponibilite, $numeroSalleDispo)
{
    try {
        $query = "INSERT INTO salleClasse_disponibilite (salleClasseId, creneauId, jourId,salleClasseDisponibilite) VALUES (:salleClasseId, :creneauId, :jourId,:salleClasseDisponibilite);";
        $addAffectation = $dbh->prepare($query);
        $addAffectation->execute([
            'salleClasseId' => $salleClasse_disponibilite[$numeroSalleDispo][0],
            'creneauId' => $salleClasse_disponibilite[$numeroSalleDispo][1],
            'jourId' => $salleClasse_disponibilite[$numeroSalleDispo][2],
            'salleClasseDisponibilite' => $salleClasse_disponibilite[$numeroSalleDispo][3],

        ]);
        $listeAffectation = $addAffectation->fetchAll();
        return $listeAffectation;
    } catch (PDOException $e) {
        $message = $e->getMessage();
        die($message);
    }
}
function AddProfesseurSalleClasse($dbh, $professeur_salleClasse, $numeroProfSalle)
{
    try {
        $query = "INSERT INTO professeur_salleClasse (professeurId, salleClasseId, professeurSalleClasseClassement) VALUES (:professeurId, :salleClasseId, :professeurSalleClasseClassement);";
        $addAffectation = $dbh->prepare($query);
        $addAffectation->execute([
            'professeurId' => $professeur_salleClasse[$numeroProfSalle][0],
            'salleClasseId' => $professeur_salleClasse[$numeroProfSalle][1],
            'professeurSalleClasseClassement' => $professeur_salleClasse[$numeroProfSalle][2],

        ]);
        $listeAffectation = $addAffectation->fetchAll();
        return $listeAffectation;
    } catch (PDOException $e) {
        $message = $e->getMessage();
        die($message);
    }
}
function AddProfesseurAffectation($dbh, $professeur_affectation, $numeroAffectation)
{
    try {
        $query = "INSERT INTO professeur_affectation (professeurId, classeId, matiereId, nombreHeures) VALUES (:professeurId, :classeId, :matiereId, :nombreHeures);";
        $addAffectation = $dbh->prepare($query);
        $addAffectation->execute([
            'professeurId' => $professeur_affectation[$numeroAffectation][0],
            'classeId' => $professeur_affectation[$numeroAffectation][1],
            'matiereId' => $professeur_affectation[$numeroAffectation][2],
            'nombreHeures' => $professeur_affectation[$numeroAffectation][3],
        ]);
        $listeAffectation = $addAffectation->fetchAll();
        return $listeAffectation;
    } catch (PDOException $e) {
        $message = $e->getMessage();
        die($message);
    }
}
function AddPresenceProfesseur($dbh, $presence_professeur, $numeroPresenceProf)
{
    try {
        $query = "insert into presence_professeur(professeurId,creneauId,jourId,presenceProfesseurDisponibilite) values (:professeurId,:creneauId,:jourId,:presenceProfesseurDisponibilite);";
        $addProf = $dbh->prepare($query);
        $addProf->execute([
            'professeurId' => $presence_professeur[$numeroPresenceProf][0],
            'creneauId' => $presence_professeur[$numeroPresenceProf][1],
            'jourId' => $presence_professeur[$numeroPresenceProf][2],
            'presenceProfesseurDisponibilite' => $presence_professeur[$numeroPresenceProf][3],
        ]);
        $listeCreneau = $addProf->fetchAll();
        return $listeCreneau;
    } catch (PDOException $e) {
        $message = $e->getMessage();
        die($message);
    }
}
function AddFourchePreferentiel($dbh, $fourche_preferentiel, $numeroFourchePref)
{
    try {
        $query = "insert into fourche_preferentiel(creneauId,jourId,fourchePreferentielPreference) values (:creneauId,:jourId,:fourchePreferentielPreference);";
        $addProf = $dbh->prepare($query);
        $addProf->execute([
            'creneauId' => $fourche_preferentiel[$numeroFourchePref][0],
            'jourId' => $fourche_preferentiel[$numeroFourchePref][1],
            'fourchePreferentielPreference' => $fourche_preferentiel[$numeroFourchePref][2],
        ]);
        $listeCreneau = $addProf->fetchAll();
        return $listeCreneau;
    } catch (PDOException $e) {
        $message = $e->getMessage();
        die($message);
    }
}
function AddJour($dbh, $jours,$compteur)
{
    try {
        $query = "insert into jour (jourNom) values (:jourNom)";
        $addJour = $dbh->prepare($query);
        $addJour->execute([
            'jourNom' => $jours[$compteur],
        ]);
        $listeCreneau = $addJour->fetchAll();
        return $listeCreneau;
    } catch (PDOException $e) {
        $message = $e->getMessage();
        die($message);
    }
}
function AddCreneau($dbh, $creneaux,$compteur)
{
    try {
        $query = "insert into creneau (creneauHeureStart,creneauHeureEnd,creneauGroupe,etablissementId) values (:creneauHeureStart,:creneauHeureEnd,:creneauGroupe,:etablissementId)";
        $addCreneau = $dbh->prepare($query);
        $addCreneau->execute([
            'creneauHeureStart' => $creneaux[0][$compteur],
            'creneauHeureEnd' => $creneaux[1][$compteur],
            'creneauGroupe' => $creneaux[2][$compteur],
            'etablissementId' => $creneaux[3][$compteur]
        ]);
        $listeCreneau = $addCreneau->fetchAll();
        return $listeCreneau;
    } catch (PDOException $e) {
        $message = $e->getMessage();
        die($message);
    }
}
function AddProfesseur_Matiere($dbh, $professeur_matiere, $numero)
{
    try {
        $query = "insert into professeur_matiere (professeurId,matiereId) values (:professeurId,:matiereId)";
        $addEtablissement = $dbh->prepare($query);
        $addEtablissement->execute([
            'professeurId' => $professeur_matiere[$numero][0],
            'matiereId' => $professeur_matiere[$numero][1],
            
        ]);
        $listeCreneau = $addEtablissement->fetchAll();
        return $listeCreneau;
    } catch (PDOException $e) {
        $message = $e->getMessage();
        die($message);
    }
}
function AddClass_Matiere($dbh, $classe_matiere, $numero)
{
    try {
        $query = "insert into classe_matiere (classeMatiereNombreHeures,classeId,matiereId) values (:classeMatiereNombreHeures,:classeId,:matiereId)";
        $addEtablissement = $dbh->prepare($query);
        $addEtablissement->execute([
            'classeMatiereNombreHeures' => $classe_matiere[$numero][0],
            'classeId' => $classe_matiere[$numero][1],
            'matiereId' => $classe_matiere[$numero][2],
        ]);
        $listeCreneau = $addEtablissement->fetchAll();
        return $listeCreneau;
    } catch (PDOException $e) {
        $message = $e->getMessage();
        die($message);
    }
}
function AddSalleClasse($dbh, $salleClasse, $numero)
{
    try {
        $query = "insert into salleClasse (salleClasseRef,salleClasseNombrePlace,salleClasseProjecteur,etablissementId) values (:salleClasseRef,:salleClasseNombrePlace,:salleClasseProjecteur,:etablissementId)";
        $addEtablissement = $dbh->prepare($query);
        $addEtablissement->execute([
            'salleClasseRef' => $salleClasse[$numero][0],
            'salleClasseNombrePlace' => $salleClasse[$numero][1],
            'salleClasseProjecteur' => $salleClasse[$numero][2],
            'etablissementId' => $salleClasse[$numero][3],
        ]);
        $listeCreneau = $addEtablissement->fetchAll();
        return $listeCreneau;
    } catch (PDOException $e) {
        $message = $e->getMessage();
        die($message);
    }
}
function AddUtilisateur_Etablissement($dbh, $utilisateur_etablissement, $numero)
{
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
function AddEtablissement($dbh, $Etablissements, $numeroEtablissement)
{
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

function AddUtilisateur($dbh, $utilisateurs, $numeroUtilisateur)
{
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
function AddMatiere($dbh, $Matieres, $numeroMatiere)
{
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
function AddClasse($dbh, $Classes, $numeroClasse)
{
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
function addEleve($dbh, $Eleves, $numeroEleve)
{
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


function AddProf($dbh, $professeurs, $numeroProf)
{
    try {
        $query = "insert into professeur(professeurEmail,professeurNom,professeurPrenom,etablissementId,professeurGenre) values (:professeurEmail,:professeurNom,:professeurPrenom,:etablissementId,:professeurGenre);";
        $addProf = $dbh->prepare($query);
        $addProf->execute([
            'professeurGenre' => $professeurs[$numeroProf][0],
            'professeurEmail' => $professeurs[$numeroProf][3],
            'professeurNom' => $professeurs[$numeroProf][2],
            'professeurPrenom' => $professeurs[$numeroProf][1],
            'etablissementId' => $professeurs[$numeroProf][4]
        ]);
        $listeCreneau = $addProf->fetchAll();
        return $listeCreneau;
    } catch (PDOException $e) {
        $message = $e->getMessage();
        die($message);
    }
}

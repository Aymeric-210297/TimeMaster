<?php

class UserModel extends BaseModel
{
    public function getUserByEmail($utilisateurEmail)
    {
        try {
            $query = "SELECT * ";
            $query .= "FROM utilisateur ";
            $query .= "WHERE utilisateurEmail = :utilisateurEmail";

            $sth = $this->dbh->prepare($query);
            $sth->execute([
                ":utilisateurEmail" => $utilisateurEmail
            ]);

            return $sth->fetch();
        } catch (PDOException $error) {
            $this->handleError("Impossible de récupérer l'utilisateur via son email", $error);
        }
    }

    public function createUser($utilisateurEmail, $utilisateurNom, $utilisateurPrenom, $utilisateurMotDePasse)
    {
        try {
            $query = "INSERT INTO utilisateur ";
            $query .= "(utilisateurEmail, utilisateurNom, utilisateurPrenom, utilisateurMotDePasse)";
            $query .= " VALUES ";
            $query .= "(:utilisateurEmail, :utilisateurNom, :utilisateurPrenom, :utilisateurMotDePasse)";

            $sth = $this->dbh->prepare($query);
            $sth->execute([
                ":utilisateurEmail" => $utilisateurEmail,
                ":utilisateurNom" => $utilisateurNom,
                ":utilisateurPrenom" => $utilisateurPrenom,
                ":utilisateurMotDePasse" => password_hash($utilisateurMotDePasse, PASSWORD_DEFAULT)
            ]);

            return $this->dbh->lastInsertId();
        } catch (PDOException $error) {
            $this->handleError("Impossible de créer l'utilisateur", $error);
        }
    }
}

<?php

class UserModel extends BaseModel
{
    public function getUserByEmail($userEmail)
    {
        try {
            $query = "SELECT * ";
            $query .= "FROM user ";
            $query .= "WHERE userEmail = :userEmail";

            $sth = $this->dbh->prepare($query);
            $sth->execute([
                ":userEmail" => $userEmail
            ]);

            return $sth->fetch();
        } catch (PDOException $error) {
            $this->handleError($error, "Impossible de récupérer l'utilisateur via son email");
        }
    }

    public function createUser($userEmail, $userFamilyName, $userGivenName, $userPassword)
    {
        try {
            $query = "INSERT INTO user ";
            $query .= "(userEmail, userFamilyName, userGivenName, userPassword)";
            $query .= " VALUES ";
            $query .= "(:userEmail, :userFamilyName, :userGivenName, :userPassword)";

            $sth = $this->dbh->prepare($query);
            $sth->execute([
                ":userEmail" => $userEmail,
                ":userFamilyName" => $userFamilyName,
                ":userGivenName" => $userGivenName,
                ":userPassword" => password_hash($userPassword, PASSWORD_DEFAULT)
            ]);

            return $this->dbh->lastInsertId();
        } catch (PDOException $error) {
            $this->handleError($error, "Impossible de créer l'utilisateur");
        }
    }
}

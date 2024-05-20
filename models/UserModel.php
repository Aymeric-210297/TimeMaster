<?php

class UserModel extends BaseModel
{
    public function getUserByEmail($userEmail)
    {
        $query = "SELECT * ";
        $query .= "FROM user ";
        $query .= "WHERE userEmail = :userEmail";

        $sth = $this->executeQuery($query, [
            ":userEmail" => $userEmail
        ]);

        return $sth->fetch();
    }

    public function createUser($userEmail, $userFamilyName, $userGivenName, $userPassword)
    {
        $query = "INSERT INTO user ";
        $query .= "(userEmail, userFamilyName, userGivenName, userPassword)";
        $query .= " VALUES ";
        $query .= "(:userEmail, :userFamilyName, :userGivenName, :userPassword)";

        $this->executeQuery($query, [
            ":userEmail" => $userEmail,
            ":userFamilyName" => $userFamilyName,
            ":userGivenName" => $userGivenName,
            ":userPassword" => password_hash($userPassword, PASSWORD_DEFAULT)
        ]);

        return $this->dbh->lastInsertId();
    }
}

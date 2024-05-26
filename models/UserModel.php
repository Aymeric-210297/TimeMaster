<?php

class UserModel extends BaseModel
{
    public function getUserByEmail($userEmail)
    {
        $query = "SELECT userId, userGivenName, userFamilyName, userEmail, userPassword ";
        $query .= "FROM user ";
        $query .= "WHERE userEmail = :userEmail";

        $sth = $this->executeQuery($query, [
            ":userEmail" => $userEmail
        ]);

        return $sth->fetch();
    }

    public function getUserById($userId)
    {
        $query = "SELECT userId, userGivenName, userFamilyName, userEmail ";
        $query .= "FROM user ";
        $query .= "WHERE userId = :userId";

        $sth = $this->executeQuery($query, [
            ":userId" => $userId
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

    public function updateUserById($userId, $newUserGivenName, $newUserFamilyName, $newUserEmail, $newUserPassword = null)
    {
        $query = "UPDATE user ";
        $query .= "SET userGivenName = :newUserGivenName, userFamilyName = :newUserFamilyName, userEmail = :newUserEmail ";
        if (!empty($newUserPassword)) {
            $query .= ", userPassword = :newUserPassword ";
        }
        $query .= "WHERE userId = :userId";

        $params = [
            ":userId" => $userId,
            ":newUserGivenName" => $newUserGivenName,
            ":newUserFamilyName" => $newUserFamilyName,
            ":newUserEmail" => $newUserEmail,
        ];

        if (!empty($newUserPassword)) {
            $params[":newUserPassword"] = password_hash($newUserPassword, PASSWORD_DEFAULT);
        }

        $this->executeQuery($query, $params);

        return true;
    }

    public function getUserSchools($userId)
    {
        $query = "SELECT schoolId FROM user_school WHERE user_school.userId = :userId";

        $sth = $this->executeQuery($query, [
            ":userId" => $userId
        ]);

        return $sth->fetchAll();
    }

    public function deleteUserById($userId)
    {
        $query = "DELETE FROM user ";
        $query .= "WHERE userId = :userId";

        $this->executeQuery($query, [
            ":userId" => $userId,
        ]);

        return true;
    }
}

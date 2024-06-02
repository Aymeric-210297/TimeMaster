<?php

class SchoolModel extends BaseModel
{
    public function createSchool($schoolName, $schoolAddress)
    {
        $query = "INSERT INTO school ";
        $query .= "(schoolName, schoolAddress)";
        $query .= " VALUES ";
        $query .= "(:schoolName, :schoolAddress)";

        $this->executeQuery($query, [
            ":schoolName" => $schoolName,
            ":schoolAddress" => $schoolAddress
        ]);

        return $this->dbh->lastInsertId();
    }

    public function getSchoolByAddress($schoolAddress)
    {
        $query = "SELECT schoolId, schoolName, schoolAddress ";
        $query .= "FROM school ";
        $query .= "WHERE schoolAddress = :schoolAddress";

        $sth = $this->executeQuery($query, [
            ":schoolAddress" => $schoolAddress
        ]);

        return $sth->fetch();
    }

    public function getSchoolById($schoolId)
    {
        $query = "SELECT schoolId, schoolName, schoolAddress ";
        $query .= "FROM school ";
        $query .= "WHERE schoolId = :schoolId";

        $sth = $this->executeQuery($query, [
            ":schoolId" => $schoolId
        ]);

        return $sth->fetch();
    }

    public function getSchoolCollaboratorsById($schoolId, $offset, $limit, $search = null)
    {
        $query = "SELECT user.userId, userGivenName, userFamilyName ";
        $query .= "FROM user_school ";
        $query .= "INNER JOIN user ON user.userId = user_school.userId ";
        $query .= "WHERE schoolId = :schoolId ";
        if (!empty($search)) {
            $query .= "AND (userGivenName LIKE :search OR userFamilyName LIKE :search) ";
        }
        $query .= "ORDER BY userFamilyName ASC, userGivenName ASC ";
        $query .= "LIMIT :offset, :limit";

        $params = [
            ":schoolId" => $schoolId,
            ":offset" => [$offset, PDO::PARAM_INT],
            ":limit" => [$limit, PDO::PARAM_INT]
        ];

        if (!empty($search)) {
            $params[':search'] = "%{$search}%";
        }

        $sth = $this->executeQuery($query, $params);

        return $sth->fetchAll();
    }

    public function countSchoolCollaboratorsById($schoolId, $search = null)
    {
        $query = "SELECT COUNT(user.userId) ";
        $query .= "FROM user_school ";
        $query .= "INNER JOIN user ON user.userId = user_school.userId ";
        $query .= "WHERE schoolId = :schoolId ";
        if (!empty($search)) {
            $query .= "AND (userGivenName LIKE :search OR userFamilyName LIKE :search) ";
        }

        $params = [
            ":schoolId" => $schoolId
        ];

        if (!empty($search)) {
            $params[':search'] = "%{$search}%";
        }

        $sth = $this->executeQuery($query, $params);

        return $sth->fetch(PDO::FETCH_COLUMN, 0);
    }

    public function deleteCollaboratorById($schoolId, $userId)
    {
        $query = "DELETE FROM user_school WHERE schoolId = :schoolId AND userId = :userId";

        $this->executeQuery($query, [
            ":schoolId" => $schoolId,
            ":userId" => $userId
        ]);

        return true;
    }

    public function getCollaboratorById($schoolId, $userId)
    {
        $query = "SELECT * FROM user_school WHERE schoolId = :schoolId AND userId = :userId";

        $sth = $this->executeQuery($query, [":schoolId" => $schoolId, ":userId" => $userId]);
        return $sth->fetch();
    }

    public function getCollaboratorByEmail($schoolId, $userEmail)
    {
        $query = "SELECT * FROM user_school ";
        $query .= "INNER JOIN user ON user.userId = user_school.userId ";
        $query .= "WHERE schoolId = :schoolId AND userEmail = :userEmail";

        $sth = $this->executeQuery($query, [
            ":schoolId" => $schoolId,
            ":userEmail" => $userEmail
        ]);

        return $sth->fetch();
    }

    public function updateSchoolById($schoolId, $schoolName, $schoolAddress)
    {
        $query = "UPDATE school ";
        $query .= "SET schoolName = :schoolName, schoolAddress = :schoolAddress ";
        $query .= "WHERE schoolId = :schoolId";

        $this->executeQuery($query, [
            "schoolId" => $schoolId,
            ":schoolName" => $schoolName,
            ":schoolAddress" => $schoolAddress,
        ]);

        return true;
    }
}

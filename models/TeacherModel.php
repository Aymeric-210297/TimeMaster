<?php

class TeacherModel extends BaseModel
{
    public function getTeachersBySchool($schoolId, $offset, $limit, $search = null)
    {
        $query = "SELECT teacherId, teacherGivenName, teacherFamilyName, teacherGender, teacherNumberHours ";
        $query .= "FROM teacher ";
        $query .= "WHERE teacher.schoolId = :schoolId ";
        if (!empty($search)) {
            $query .= "AND (teacherGivenName LIKE :search OR teacherFamilyName LIKE :search) ";
        }
        $query .= "ORDER BY teacherFamilyName ASC, teacherGivenName ASC ";
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

    public function countTeachersBySchool($schoolId, $search = null)
    {
        $query = "SELECT COUNT(*) ";
        $query .= "FROM teacher ";
        $query .= "WHERE teacher.schoolId = :schoolId ";
        if (!empty($search)) {
            $query .= "AND (teacherGivenName LIKE :search OR teacherFamilyName LIKE :search) ";
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

    public function getTeacherById($schoolId, $teacherId)
    {
        $query = "SELECT teacherId, teacherGivenName, teacherFamilyName, teacherEmail, teacherGender, teacherNumberHours ";
        $query .= "FROM teacher ";
        $query .= "WHERE teacher.schoolId = :schoolId AND teacher.teacherId = :teacherId";

        $sth = $this->executeQuery($query, [
            ":schoolId" => $schoolId,
            ":teacherId" => $teacherId
        ]);

        return $sth->fetch();
    }

    public function deleteTeacherById($schoolId, $teacherId)
    {
        $query = "DELETE FROM teacher WHERE schoolId = :schoolId AND teacherId = :teacherId";

        $this->executeQuery($query, [
            ":schoolId" => $schoolId,
            ":teacherId" => $teacherId
        ]);

        return true;
    }

    public function createTeacher($schoolId, $teacherGivenName, $teacherFamilyName, $teacherEmail, $teacherGender, $teacherNumberHours)
    {
        $query = "INSERT INTO teacher ";
        $query .= "(schoolId, teacherGivenName, teacherFamilyName, teacherEmail, teacherGender, teacherNumberHours)";
        $query .= " VALUES ";
        $query .= "(:schoolId, :teacherGivenName, :teacherFamilyName, :teacherEmail, :teacherGender, :teacherNumberHours)";

        $this->executeQuery($query, [
            ":schoolId" => $schoolId,
            ":teacherGivenName" => $teacherGivenName,
            ":teacherFamilyName" => $teacherFamilyName,
            ":teacherEmail" => $teacherEmail,
            ":teacherGender" => $teacherGender,
            ":teacherNumberHours" => $teacherNumberHours
        ]);

        return $this->dbh->lastInsertId();
    }

    public function getTeacherByEmail($schoolId, $teacherEmail)
    {
        $query = "SELECT * FROM teacher WHERE schoolId = :schoolId AND teacherEmail = :teacherEmail";

        $sth = $this->executeQuery($query, [
            ":schoolId" => $schoolId,
            ":teacherEmail" => $teacherEmail
        ]);

        return $sth->fetch();
    }

    public function updateTeacherById($schoolId, $teacherId, $teacherGivenName, $teacherFamilyName, $teacherEmail, $teacherGender, $teacherNumberHours)
    {
        $query = "UPDATE teacher SET ";
        $query .= "teacherGivenName = :teacherGivenName, ";
        $query .= "teacherFamilyName = :teacherFamilyName, ";
        $query .= "teacherEmail = :teacherEmail, ";
        $query .= "teacherGender = :teacherGender, ";
        $query .= "teacherNumberHours = :teacherNumberHours ";
        $query .= "WHERE schoolId = :schoolId AND teacherId  = :teacherId ";

        $this->executeQuery($query, [
            ":teacherGivenName" => $teacherGivenName,
            ":teacherFamilyName" => $teacherFamilyName,
            ":teacherEmail" => $teacherEmail,
            ":teacherGender" => $teacherGender,
            ":teacherNumberHours" => $teacherNumberHours,
            ':schoolId' => $schoolId,
            ':teacherId' => $teacherId
        ]);

        return true;
    }
}

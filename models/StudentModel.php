<?php

class StudentModel extends BaseModel
{
    public function getStudentsBySchool($schoolId, $offset, $limit, $search = null)
    {
        $query = "SELECT studentId, studentGivenName, studentFamilyName, classRef ";
        $query .= "FROM student ";
        $query .= "LEFT JOIN class ON class.classId = student.classId ";
        $query .= "WHERE student.schoolId = :schoolId ";
        if (!empty($search)) {
            $query .= "AND (studentGivenName LIKE :search OR studentFamilyName LIKE :search) ";
        }
        $query .= "ORDER BY studentFamilyName ASC, studentGivenName ASC ";
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

    public function countStudentsBySchool($schoolId, $search = null)
    {
        $query = "SELECT COUNT(*) ";
        $query .= "FROM student ";
        $query .= "WHERE student.schoolId = :schoolId ";
        if (!empty($search)) {
            $query .= "AND (studentGivenName LIKE :search OR studentFamilyName LIKE :search) ";
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

    public function getStudentById($schoolId, $studentId)
    {
        $query = "SELECT studentId, studentGivenName, studentFamilyName, studentEmail, classRef ";
        $query .= "FROM student ";
        $query .= "LEFT JOIN class ON class.classId = student.classId ";
        $query .= "WHERE student.schoolId = :schoolId AND student.studentId = :studentId";

        $sth = $this->executeQuery($query, [
            ":schoolId" => $schoolId,
            ":studentId" => $studentId
        ]);

        return $sth->fetch();
    }

    public function deleteStudentById($schoolId, $studentId)
    {
        $query = "DELETE FROM student WHERE schoolId = :schoolId AND studentId = :studentId";

        $this->executeQuery($query, [
            ":schoolId" => $schoolId,
            ":studentId" => $studentId
        ]);

        return true;
    }

    public function createStudent($schoolId, $studentGivenName, $studentFamilyName, $studentEmail)
    {
        $query = "INSERT INTO student ";
        $query .= "(schoolId, studentGivenName, studentFamilyName, studentEmail)";
        $query .= " VALUES ";
        $query .= "(:schoolId, :studentGivenName, :studentFamilyName, :studentEmail)";

        $this->executeQuery($query, [
            ":schoolId" => $schoolId,
            ":studentGivenName" => $studentGivenName,
            ":studentFamilyName" => $studentFamilyName,
            ":studentEmail" => $studentEmail
        ]);

        return $this->dbh->lastInsertId();
    }

    public function getStudentByEmail($schoolId, $studentEmail)
    {
        $query = "SELECT * FROM student WHERE schoolId = :schoolId AND studentEmail = :studentEmail";

        $sth = $this->executeQuery($query, [
            ":schoolId" => $schoolId,
            ":studentEmail" => $studentEmail
        ]);

        return $sth->fetch();
    }

    public function updateStudentById($schoolId, $studentId, $studentGivenName, $studentFamilyName, $studentEmail)
    {
        $query = "UPDATE student SET ";
        $query .= "studentGivenName = :studentGivenName, ";
        $query .= "studentFamilyName = :studentFamilyName, ";
        $query .= "studentEmail = :studentEmail ";
        $query .= "WHERE schoolId = :schoolId AND studentId  = :studentId ";

        $this->executeQuery($query, [
            ":studentGivenName" => $studentGivenName,
            ":studentFamilyName" => $studentFamilyName,
            ":studentEmail" => $studentEmail,
            ':schoolId' => $schoolId,
            ':studentId' => $studentId
        ]);

        return true;
    }
}

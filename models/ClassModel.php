<?php

class ClasseModel extends BaseModel
{
    public function getClassesBySchool($schoolId, $offset, $limit, $search = null)
    {
        $query = "SELECT class.classId, class.classRef, COUNT(student.studentId) AS numberOfStudents ";
        $query .= "FROM class ";
        $query .= "LEFT JOIN student ON class.classId = student.classId ";
        $query .= "WHERE class.schoolId = :schoolId ";

        if (!empty($search)) {
            $query .= "AND class.classRef LIKE :search ";
        }

        $query .= "GROUP BY class.classId ";
        $query .= "ORDER BY class.classRef ASC ";
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

    public function countClassesBySchool($schoolId, $search = null)
    {
        $query = "SELECT COUNT(*) ";
        $query .= "FROM class ";
        $query .= "WHERE class.schoolId = :schoolId ";
        if (!empty($search)) {
            $query .= "AND classRef LIKE :search ";
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

    public function getClassById($schoolId, $classId)
    {
        $query = "SELECT classId, classRef ";
        $query .= "FROM class ";
        $query .= "WHERE class.schoolId = :schoolId AND class.classId = :classId";

        $sth = $this->executeQuery($query, [
            ":schoolId" => $schoolId,
            ":classId" => $classId
        ]);

        return $sth->fetch();
    }

    public function deleteClassById($schoolId, $classId)
    {
        $query = "DELETE FROM class WHERE schoolId = :schoolId AND classId = :classId";

        $this->executeQuery($query, [
            ":schoolId" => $schoolId,
            ":classId" => $classId
        ]);

        return true;
    }

    public function createClass($schoolId, $classRef)
    {
        $query = "INSERT INTO class ";
        $query .= "(schoolId, classRef)";
        $query .= " VALUES ";
        $query .= "(:schoolId, :classRef)";

        $this->executeQuery($query, [
            ":schoolId" => $schoolId,
            ":classRef" => $classRef,

        ]);

        return $this->dbh->lastInsertId();
    }

    public function getClassByRef($schoolId, $classRef)
    {
        $query = "SELECT * FROM class WHERE schoolId = :schoolId AND classRef = :classRef";

        $sth = $this->executeQuery($query, [
            ":schoolId" => $schoolId,
            ":classRef" => $classRef
        ]);

        return $sth->fetch();
    }

    public function updateClassById($schoolId, $classId, $classRef)
    {
        $query = "UPDATE class SET ";
        $query .= "classRef = :classRef ";
        $query .= "WHERE schoolId = :schoolId AND classId = :classId ";

        $this->executeQuery($query, [
            ":classRef" => $classRef,
            ":classId" => $classId,
            ':schoolId' => $schoolId,
        ]);

        return true;
    }
}

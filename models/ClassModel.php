<?php

class ClassModel extends BaseModel
{
    public function getClassesBySchool($schoolId, $offset, $limit, $search = null)
    {
        /*$query = "SELECT class.classId, class.classRef, COUNT(student.studentId) AS numberOfStudents ";
        $query .= "FROM class ";
        $query .= "LEFT JOIN student ON class.classId = student.classId ";
        $query .= "WHERE class.schoolId = :schoolId ";*/

        $query = "SELECT
             class.classId,
             class.classRef,
             COUNT(student.studentId) AS numberOfStudents,
             EXISTS (SELECT 1 FROM class_schedule WHERE class_schedule.classId = class.classId) AS classExistsInSchedule ";
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

    // ALGO

    public function getClassCountBySchoolId($schoolId)
    {
        $query = "SELECT COUNT(*) AS classCount ";
        $query .= "FROM class ";
        $query .= "WHERE schoolId = :schoolId";

        $sth = $this->executeQuery($query, [
            ":schoolId" => $schoolId
        ]);

        return $sth->fetch(PDO::FETCH_ASSOC)['classCount'];
    }
    public function getClassIdBySchoolIdAndIndex($schoolId, $index)
    {
        $query = "SELECT classId ";
        $query .= "FROM class ";
        $query .= "WHERE schoolId = :schoolId ";
        $query .= "ORDER BY classId ASC ";
        $query .= "LIMIT 1 OFFSET :index";

        // Le binding des paramètres OFFSET nécessite une approche légèrement différente
        $sth = $this->dbh->prepare($query);
        $sth->bindParam(':schoolId', $schoolId, PDO::PARAM_INT);
        $sth->bindValue(':index', (int) $index, PDO::PARAM_INT);
        $sth->execute();

        return $sth->fetch(PDO::FETCH_ASSOC)['classId'];
    }
    public function getClassSubjectsByClassId($classId)
    {
        $query = "SELECT subjectId, classSubjectNumberHours ";
        $query .= "FROM class_subject ";
        $query .= "WHERE classId = :classId";

        $sth = $this->executeQuery($query, [
            ":classId" => $classId
        ]);

        $classSubjects = [];
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $classSubjects[$row['subjectId']] = $row['classSubjectNumberHours'];
        }

        return $classSubjects;
    }

    public function getClassIdsBySchoolId($schoolId)
    {
        $query = "SELECT classId, classRef FROM class WHERE schoolId = :schoolId";

        $sth = $this->executeQuery($query, [
            ":schoolId" => $schoolId
        ]);

        return $sth->fetchAll();
    }
    public function getClassHoursBySubject($schoolId)
    {
        $query = "SELECT
                    cs.subjectId,
                    SUM(cs.classSubjectNumberHours) AS TotalHours
                  FROM
                    class_subject cs
                    JOIN class c ON cs.classId = c.classId
                  WHERE
                    c.schoolId = :schoolId
                  GROUP BY
                    cs.subjectId";

        $sth = $this->executeQuery($query, [
            ":schoolId" => $schoolId
        ]);

        $results = $sth->fetchAll(PDO::FETCH_ASSOC);

        $tabClass = [];
        foreach ($results as $row) {
            $tabClass[$row['subjectId']] = $row['TotalHours'];
        }

        return $tabClass;
    }
}

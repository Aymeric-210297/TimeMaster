<?php

class SubjectModel extends BaseModel
{
    public function getSubjectsBySchool($schoolId, $offset, $limit, $search = null)
    {
        $query = "SELECT subjectId, subjectName ";
        $query .= "FROM subject ";
        $query .= "WHERE subject.schoolId = :schoolId ";
        if (!empty($search)) {
            $query .= "AND subjectName LIKE :search ";
        }
        $query .= "ORDER BY subjectName ASC ";
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

    public function countSubjectsBySchool($schoolId, $search = null)
    {
        $query = "SELECT COUNT(*) ";
        $query .= "FROM subject ";
        $query .= "WHERE subject.schoolId = :schoolId ";
        if (!empty($search)) {
            $query .= "AND subjectName LIKE :search ";
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

    public function getSubjectById($schoolId, $subjectId)
    {
        $query = "SELECT subjectId, subjectName ";
        $query .= "FROM subject ";
        $query .= "WHERE subject.schoolId = :schoolId AND subject.subjectId = :subjectId";

        $sth = $this->executeQuery($query, [
            ":schoolId" => $schoolId,
            ":subjectId" => $subjectId
        ]);

        return $sth->fetch();
    }

    public function deleteSubjectById($schoolId, $subjectId)
    {
        $query = "DELETE FROM subject WHERE schoolId = :schoolId AND subjectId = :subjectId";

        $this->executeQuery($query, [
            ":schoolId" => $schoolId,
            ":subjectId" => $subjectId
        ]);

        return true;
    }

    public function createSubject($schoolId, $subjectName)
    {
        $query = "INSERT INTO subject ";
        $query .= "(schoolId, subjectName)";
        $query .= " VALUES ";
        $query .= "(:schoolId, :subjectName)";

        $this->executeQuery($query, [
            ":schoolId" => $schoolId,
            ":subjectName" => $subjectName
        ]);

        return $this->dbh->lastInsertId();
    }

    public function getSubjectByName($schoolId, $subjectName)
    {
        $query = "SELECT * FROM subject WHERE schoolId = :schoolId AND subjectName = :subjectName";

        $sth = $this->executeQuery($query, [
            ":schoolId" => $schoolId,
            ":subjectName" => $subjectName
        ]);

        return $sth->fetch();
    }

    public function updateSubjectById($schoolId, $subjectId, $subjectName)
    {
        $query = "UPDATE subject SET ";
        $query .= "subjectName = :subjectName ";
        $query .= "WHERE schoolId = :schoolId AND subjectId  = :subjectId ";

        $this->executeQuery($query, [
            ":subjectName" => $subjectName,
            ':schoolId' => $schoolId,
            ':subjectId' => $subjectId
        ]);

        return true;
    }

    // ALGO

    public function getNumberOfSubjectBySchoolId($schoolId)
    {
        $query = "SELECT COUNT(*) AS numberOfSubjects ";
        $query .= "FROM subject ";
        $query .= "WHERE schoolId = :schoolId";

        $sth = $this->executeQuery($query, [
            ":schoolId" => $schoolId
        ]);

        return $sth->fetch(PDO::FETCH_ASSOC)['numberOfSubjects'];
    }

    public function getSubjectIdsBySchoolId($schoolId)
    {
        $query = "SELECT subjectId FROM subject WHERE schoolId = :schoolId";
        $sth = $this->executeQuery($query, [":schoolId" => $schoolId]);

        $subjects = [];
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $subjects[] = $row['subjectId'];
        }

        return $subjects;
    }
}

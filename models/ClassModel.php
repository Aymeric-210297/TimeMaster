<?php
class classModel extends BaseModel
{
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
    public function getClasses()
    {
        $query = "SELECT * FROM class";
        $sth = $this->executeQuery($query);
        return $sth->fetchAll();
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

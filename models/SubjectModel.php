<?php
class subjectModel extends BaseModel
{
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


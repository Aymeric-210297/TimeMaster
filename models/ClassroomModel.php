<?php
class classroomModel extends BaseModel
{
    public function getClassroomCountBySchoolId($schoolId)
    {
        $query = "SELECT COUNT(*) AS classroomCount ";
        $query .= "FROM classroom ";
        $query .= "WHERE schoolId = :schoolId";

        $sth = $this->executeQuery($query, [
            ":schoolId" => $schoolId
        ]);

        return $sth->fetch(PDO::FETCH_ASSOC)['classroomCount'];
    }
    public function getClassroomIdBySchoolIdAndIndex($schoolId, $index)
    {
        $query = "SELECT classroomId ";
        $query .= "FROM classroom ";
        $query .= "WHERE schoolId = :schoolId ";
        $query .= "ORDER BY classroomId ASC ";
        $query .= "LIMIT 1 OFFSET :index";

        // Le binding des paramètres OFFSET nécessite une approche légèrement différente
        $sth = $this->dbh->prepare($query);
        $sth->bindParam(':schoolId', $schoolId, PDO::PARAM_INT);
        $sth->bindValue(':index', (int) $index, PDO::PARAM_INT);
        $sth->execute();

        return $sth->fetch(PDO::FETCH_ASSOC)['classroomId'];
    }
    public function getClassroomAvailabilitiesByClassroomId($classroomId)
    {
        $query = "SELECT dayId, timeslotId, classroomAvailability ";
        $query .= "FROM classroom_availability ";
        $query .= "WHERE classroomId = :classroomId";

        $sth = $this->executeQuery($query, [
            ":classroomId" => $classroomId
        ]);

        $availabilities = [];
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            if (!isset($availabilities[$row['dayId']])) {
                $availabilities[$row['dayId']] = [];
            }
            $availabilities[$row['dayId']][$row['timeslotId']] = $row['classroomAvailability'];
        }

        return $availabilities;
    }
    public function getClassroomSubjectsByClassroomId($classroomId)
    {
        $query = "SELECT subjectId ";
        $query .= "FROM classroom_subject ";
        $query .= "WHERE classroomId = :classroomId";

        $sth = $this->executeQuery($query, [
            ":classroomId" => $classroomId
        ]);

        $classroomSubjects = [];
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $classroomSubjects[] = $row['subjectId'];
        }

        return $classroomSubjects;
    }


    
    public function getTotalClassroomHours($schoolId)
    {
        $query = "SELECT 
                    COUNT(*) AS TotalHours
                  FROM 
                    classroom_availability ca
                  JOIN 
                    classroom c ON ca.classroomId = c.classroomId
                  WHERE 
                    ca.classroomAvailability = 'available'
                    AND c.schoolId = :schoolId";

        $sth = $this->executeQuery($query, [
            ":schoolId" => $schoolId
        ]);

        $result = $sth->fetch(PDO::FETCH_ASSOC);
        
        return $result['TotalHours'];
    }
}
<?php
class teacherModel extends BaseModel
{
    public function getTeacherCountBySchoolId($schoolId)
    {
        $query = "SELECT COUNT(*) AS teacherCount ";
        $query .= "FROM teacher ";
        $query .= "WHERE schoolId = :schoolId";

        $sth = $this->executeQuery($query, [
            ":schoolId" => $schoolId
        ]);

        return $sth->fetch(PDO::FETCH_ASSOC)['teacherCount'];
    }
    public function getSubjectIdsByTeacherId($teacherId)
    {
        $query = "SELECT subjectId ";
        $query .= "FROM teacher_subject ";
        $query .= "WHERE teacherId = :teacherId";

        $sth = $this->executeQuery($query, [
            ":teacherId" => $teacherId
        ]);

        $subjectIds = [];
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $subjectIds[] = $row['subjectId'];
        }

        return $subjectIds;
    }
    public function getTeacherIdBySchoolIdAndIndex($schoolId, $index)
    {
        $query = "SELECT teacherId ";
        $query .= "FROM teacher ";
        $query .= "WHERE schoolId = :schoolId ";
        $query .= "ORDER BY teacherId ASC ";
        $query .= "LIMIT 1 OFFSET :index";

        // Le binding des paramètres OFFSET nécessite une approche légèrement différente
        $sth = $this->dbh->prepare($query);
        $sth->bindParam(':schoolId', $schoolId, PDO::PARAM_INT);
        $sth->bindValue(':index', (int) $index, PDO::PARAM_INT);
        $sth->execute();

        return $sth->fetch(PDO::FETCH_ASSOC)['teacherId'];
    }
    public function getTeacherHoursByTeacherId($teacherId)
    {
        $query = "SELECT teacherNumberHours ";
        $query .= "FROM teacher ";
        $query .= "WHERE teacherId = :teacherId";

        $sth = $this->executeQuery($query, [
            ":teacherId" => $teacherId
        ]);

        return $sth->fetch(PDO::FETCH_ASSOC)['teacherNumberHours'];
    }
    public function getClassroomsByTeacherId($teacherId)
    {
        $query = "SELECT classroomId, teacherClassroomRanking ";
        $query .= "FROM teacher_classroom ";
        $query .= "WHERE teacherId = :teacherId";
        
        $sth = $this->executeQuery($query, [
            ":teacherId" => $teacherId
        ]);

        $classrooms = [];
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $classrooms[$row['teacherClassroomRanking']] = $row['classroomId'];
        }

        return $classrooms;
    }
    public function getAvailabilitiesByTeacherId($teacherId)
    {
        $query = "SELECT dayId, timeslotId, teacherAvailability ";
        $query .= "FROM teacher_availability ";
        $query .= "WHERE teacherId = :teacherId";

        $sth = $this->executeQuery($query, [
            ":teacherId" => $teacherId
        ]);

        $availabilities = [];
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            if (!isset($availabilities[$row['dayId']])) {
                $availabilities[$row['dayId']] = [];
            }
            $availabilities[$row['dayId']][$row['timeslotId']] = $row['teacherAvailability'];
        }

        return $availabilities;
    }
    public function getTeachers()
    {
        $query = "SELECT * FROM teacher";
        $sth = $this->executeQuery($query);
        return $sth->fetchAll();
    }

    public function getTeacherAvailability($teacherId)
    {
        $query = "SELECT * FROM teacher_availability WHERE teacherId = :teacherId";
        $sth = $this->executeQuery($query, [":teacherId" => $teacherId]);
        return $sth->fetchAll();
    }
}
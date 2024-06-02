<?php
class ScheduleModel extends BaseModel
{
    public function createSchedule($schoolId)
    {
        $query = "INSERT INTO schedule ";
        $query .= "(schoolId)";
        $query .= " VALUES ";
        $query .= "(:schoolId)";

        $this->executeQuery($query, [
            ":schoolId" => $schoolId,
        ]);

        return $this->dbh->lastInsertId();
    }
    public function createClassSchedule($tabClass_Schedule,$compteur)
    {
        $query = "INSERT INTO class_schedule ";
        $query .= "(scheduleId, dayId, timeslotId, classId, teacherId, classroomId, subjectId)";
        $query .= " VALUES ";
        $query .= "(:scheduleId, :dayId, :timeslotId, :classId, :teacherId, :classroomId, :subjectId)";

        $this->executeQuery($query, [
            ":scheduleId" => $tabClass_Schedule[0][$compteur],
            ":dayId" => $tabClass_Schedule[1][$compteur],
            ":timeslotId" => $tabClass_Schedule[2][$compteur],
            ":classId" => $tabClass_Schedule[3][$compteur],
            ":teacherId" => $tabClass_Schedule[4][$compteur],
            ":classroomId" => $tabClass_Schedule[5][$compteur],
            ":subjectId" => $tabClass_Schedule[6][$compteur]
        ]);

        return $this->dbh->lastInsertId();
    }
    public function getTimePreferences()
    {
        $query = "SELECT timeslotId, dayId, timePreference ";
        $query .= "FROM time_preference";

        $sth = $this->executeQuery($query);
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        $timePreferences = [];
        foreach ($result as $row) {
            $timePreferences[$row['timeslotId']][$row['dayId']] = $row['timePreference'];
        }

        return $timePreferences;
    }



    
    public function getScheduleByClassId($classId)
    {
        $query = "
            SELECT cs.*, t.teacherGender, t.teacherFamilyName, r.classroomRef, s.subjectName, ts.timeslotStartHour, ts.timeslotEndHour, d.dayName
            FROM class_schedule cs
            JOIN teacher t ON cs.teacherId = t.teacherId
            JOIN classroom r ON cs.classroomId = r.classroomId
            JOIN subject s ON cs.subjectId = s.subjectId
            JOIN timeslot ts ON cs.timeslotId = ts.timeslotId
            JOIN day d ON cs.dayId = d.dayId
            WHERE cs.classId = :classId
        ";

        $sth = $this->executeQuery($query, [
            ":classId" => $classId
        ]);

        return $sth->fetchAll(PDO::FETCH_OBJ); // Fetch results as objects
    }
}
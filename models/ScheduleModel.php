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
    public function getTimeslotGroupsBySchoolId($schoolId)
    {
        $query = "SELECT timeslotId, timeslotGroup FROM timeslot WHERE schoolId = :schoolId";
        $sth = $this->executeQuery($query, [":schoolId" => $schoolId]);
        
        $timeslotGroups = [];
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $timeslotGroups[$row['timeslotId']] = $row['timeslotGroup'];
        }
        
        return $timeslotGroups;
    }

    // Fonction pour créer une structure de groupement de créneaux horaires
    public function createTimeslotGroupMapping($schoolId)
    {
        $timeslotGroups = $this->getTimeslotGroupsBySchoolId($schoolId);

        $groupMapping = [];
        foreach ($timeslotGroups as $timeslotId => $timeslotGroup) {
            // Associe chaque créneau (timeslotId) au groupe (timeslotGroup)
            if (!isset($groupMapping[$timeslotGroup])) {
                $groupMapping[$timeslotGroup] = [];
            }
            $groupMapping[$timeslotGroup][] = $timeslotId;
        }

        return $groupMapping;
    }
}
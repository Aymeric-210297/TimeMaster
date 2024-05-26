<?php
class jourModel extends BaseModel
{
    public function recupJourParEtablissement($schoolId)
    {
        try {
            $query = "SELECT DISTINCT 
                          day.dayId, 
                          day.dayName 
                      FROM 
                          day 
                      JOIN schedule_day ON day.dayId = schedule_day.dayId 
                      JOIN schedule_timeslot ON schedule_day.scheduleId = schedule_timeslot.scheduleId 
                      JOIN class_schedule ON schedule_timeslot.timeslotId = class_schedule.timeslotId 
                      JOIN class ON class_schedule.classId = class.classId 
                      JOIN school ON class.schoolId = school.schoolId 
                      WHERE 
                          school.schoolId = :schoolId";
            $sth = $this->executeQuery($query, [':schoolId' => $schoolId]);
            return $sth->fetchAll();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}
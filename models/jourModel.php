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
    public function recupJourParEtablissement2($schoolId)
    {
        $query = "
            SELECT dayId, dayName
            FROM day
        ";

        $sth = $this->executeQuery($query);

        return $sth->fetchAll(PDO::FETCH_OBJ);
    }
    public function getDayIdAndTimeSlotId($schoolId)
    {
        // Récupérer les jours
        $query = "SELECT dayId FROM day";
        $sth = $this->executeQuery($query);
        $days = [];
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $days[] = $row['dayId'];
        }

        // Récupérer les créneaux horaires pour l'école
        $query = "SELECT timeslotId FROM timeslot WHERE schoolId = :schoolId";
        $sth = $this->executeQuery($query, [":schoolId" => $schoolId]);
        $timeslots = [];
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $timeslots[] = $row['timeslotId'];
        }

        // Initialiser le tableau
        $tabVariatif = [];
        foreach ($days as $dayId) {
            foreach ($timeslots as $timeslotId) {
                $tabVariatif[$dayId][$timeslotId] = 0;
            }
        }

        return $tabVariatif;
    }

    public function getNumberOfDaysBySchoolId($schoolId)
    {
        $query = "SELECT COUNT(*) AS numberOfDays ";
        $query .= "FROM day ";
        $sth = $this->executeQuery($query);

        return $sth->fetch(PDO::FETCH_ASSOC)['numberOfDays'];
    }
    public function getDayTimeslotArrayBySchoolId($schoolId)
    {
        $query = "SELECT d.dayId, t.timeslotId ";
        $query .= "FROM day d ";
        $query .= "CROSS JOIN timeslot t ";
        $query .= "WHERE t.schoolId = :schoolId";
        
        $sth = $this->executeQuery($query, [
            ":schoolId" => $schoolId
        ]);
    
        $tabVariatif = [];
    
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $dayId = $row['dayId'];
            $timeslotId = $row['timeslotId'];
    
            // Initialize the value to 0
            if (!isset($tabVariatif[$dayId])) {
                $tabVariatif[$dayId] = [];
            }
            $tabVariatif[$dayId][$timeslotId] = 0;
        }
    
        return $tabVariatif;
    }
    public function getAllDayIds()
    {
        $query = "SELECT dayId ";
        $query .= "FROM day";

        $sth = $this->executeQuery($query);
        $dayIds = $sth->fetchAll(PDO::FETCH_COLUMN, 0); // Récupère uniquement les valeurs de la première colonne (dayId)

        $result = [];
        foreach ($dayIds as $i => $dayId) {
            $result[$i] = $dayId;
        }
        return $result;
    }

}
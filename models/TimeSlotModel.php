<?php
class timeSlotModel extends BaseModel 
{

    public function recupCreneauParEtablissement($schoolId)
    {
        $query = "
            SELECT timeslotId, timeslotStartHour, timeslotEndHour
            FROM timeslot
            WHERE schoolId = :schoolId
            ORDER BY timeslotStartHour
        ";

        $sth = $this->executeQuery($query, [
            ":schoolId" => $schoolId
        ]);

        return $sth->fetchAll(PDO::FETCH_OBJ);
    }
    public function getNumberOfTimeslotsBySchoolId($schoolId)
    {
        $query = "SELECT COUNT(*) AS numberOfTimeslots ";
        $query .= "FROM timeslot ";
        $query .= "WHERE schoolId = :schoolId";

        $sth = $this->executeQuery($query, [
            ":schoolId" => $schoolId
        ]);

        return $sth->fetch(PDO::FETCH_ASSOC)['numberOfTimeslots'];
    }
    public function getTimeslotIdsBySchoolId($schoolId)
    {
        $query = "SELECT timeslotId ";
        $query .= "FROM timeslot ";
        $query .= "WHERE schoolId = :schoolId";

        $sth = $this->executeQuery($query, [":schoolId" => $schoolId]);
        $timeslotIds = $sth->fetchAll(PDO::FETCH_COLUMN, 0); // Récupère uniquement les valeurs de la première colonne (timeslotId)
        
        $result = [];
        foreach ($timeslotIds as $i => $timeslotId) {
            $result[$i] = $timeslotId;
        }
        return $result;
    }

    public function getTimePreferencesBySchoolId($schoolId)
    {
        $query = "
        SELECT tp.timeslotId, tp.dayId
        FROM time_preference tp
        JOIN timeslot t ON tp.timeslotId = t.timeslotId
        JOIN school s ON t.schoolId = s.schoolId
        JOIN day d ON tp.dayId = d.dayId
        WHERE s.schoolId = :schoolId
        ORDER BY tp.timePreference DESC, d.dayId, t.timeslotId;
        ";
    
        $sth = $this->executeQuery($query, [
            ":schoolId" => $schoolId
        ]);
    
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    
        $structuredResult = [];
        foreach ($result as $index => $row) {
            $structuredResult[$index]["dayId"] = $row["dayId"];
            $structuredResult[$index]["timeslotId"] = $row["timeslotId"];
        }
    
        return $structuredResult;
    }
}

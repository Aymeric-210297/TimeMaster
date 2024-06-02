<?php
class dayModel extends BaseModel
{

    public function recupJourParEtablissement($schoolId)
    {
        $query = "
            SELECT dayId, dayName
            FROM day
            order by dayId
        ";

        $sth = $this->executeQuery($query);

        return $sth->fetchAll(PDO::FETCH_OBJ);
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
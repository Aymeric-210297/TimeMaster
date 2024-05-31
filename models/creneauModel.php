<?php
class creneauModel extends BaseModel 
{
    public function recupCreneauParEtablissement($schoolId)
    {
        try {
            $query = "SELECT * FROM timeslot WHERE schoolId = :schoolId ORDER BY timeslotStartHour";
            $sth = $this->executeQuery($query, [':schoolId' => $schoolId]);
            return $sth->fetchAll();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
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
}

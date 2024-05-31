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
}
<?php

class SchoolModel extends BaseModel
{
    public function createSchool($schoolName, $schoolAddress)
    {
        $query = "INSERT INTO school ";
        $query .= "(schoolName, schoolAddress)";
        $query .= " VALUES ";
        $query .= "(:schoolName, :schoolAddress)";

        $this->executeQuery($query, [
            ":schoolName" => $schoolName,
            ":schoolAddress" => $schoolAddress
        ]);

        return $this->dbh->lastInsertId();
    }

    public function getSchoolByAddress($schoolAddress) {
        $query = "SELECT schoolName, schoolAddress ";
        $query .= "FROM school ";
        $query .= "WHERE schoolAddress = :schoolAddress";

        $sth = $this->executeQuery($query, [
            ":schoolAddress" => $schoolAddress
        ]);

        return $sth->fetch();  
    }
}

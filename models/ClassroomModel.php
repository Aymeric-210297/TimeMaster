<?php
class classroomModel extends BaseModel
{
    public function getClassroomCountBySchoolId($schoolId)
    {
        $query = "SELECT COUNT(*) AS classroomCount ";
        $query .= "FROM classroom ";
        $query .= "WHERE schoolId = :schoolId";

        $sth = $this->executeQuery($query, [
            ":schoolId" => $schoolId
        ]);

        return $sth->fetch(PDO::FETCH_ASSOC)['classroomCount'];
    }
    public function getClassroomIdBySchoolIdAndIndex($schoolId, $index)
    {
        $query = "SELECT classroomId ";
        $query .= "FROM classroom ";
        $query .= "WHERE schoolId = :schoolId ";
        $query .= "ORDER BY classroomId ASC ";
        $query .= "LIMIT 1 OFFSET :index";

        // Le binding des paramètres OFFSET nécessite une approche légèrement différente
        $sth = $this->dbh->prepare($query);
        $sth->bindParam(':schoolId', $schoolId, PDO::PARAM_INT);
        $sth->bindValue(':index', (int) $index, PDO::PARAM_INT);
        $sth->execute();

        return $sth->fetch(PDO::FETCH_ASSOC)['classroomId'];
    }
}
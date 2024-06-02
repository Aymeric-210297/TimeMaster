<?php

class ClassroomModel extends BaseModel
{
    public function getClassroomsBySchool($schoolId, $offset, $limit, $search = null)
    {
        $query = "SELECT classroomId,classroomRef, classroomNumberSeats, classroomProjector ";
        $query .= "FROM classroom ";
        $query .= "WHERE classroom.schoolId = :schoolId ";
        if (!empty($search)) {
            $query .= "AND (classroomRef LIKE :search) ";
        }
        $query .= "ORDER BY classroomRef ASC ";
        $query .= "LIMIT :offset, :limit";

        $params = [
            ":schoolId" => $schoolId,
            ":offset" => [$offset, PDO::PARAM_INT],
            ":limit" => [$limit, PDO::PARAM_INT]
        ];

        if (!empty($search)) {
            $params[':search'] = "%{$search}%";
        }

        $sth = $this->executeQuery($query, $params);

        return $sth->fetchAll();
    }

    public function countClassroomsBySchool($schoolId, $search = null)
    {
        $query = "SELECT COUNT(*) ";
        $query .= "FROM classroom ";
        $query .= "WHERE classroom.schoolId = :schoolId ";
        if (!empty($search)) {
            $query .= "AND (classroomRef LIKE :search) ";
        }

        $params = [
            ":schoolId" => $schoolId
        ];

        if (!empty($search)) {
            $params[':search'] = "%{$search}%";
        }

        $sth = $this->executeQuery($query, $params);

        return $sth->fetch(PDO::FETCH_COLUMN, 0);
    }

    public function getClassroomById($schoolId, $classroomId)
    {
        $query = "SELECT classroomId, classroomRef, classroomNumberSeats, classroomProjector ";
        $query .= "FROM classroom ";
        $query .= "WHERE classroom.schoolId = :schoolId AND classroom.classroomId = :classroomId";

        $sth = $this->executeQuery($query, [
            ":schoolId" => $schoolId,
            ":classroomId" => $classroomId
        ]);

        return $sth->fetch();
    }

    public function deleteClassroomById($schoolId, $classroomId)
    {
        $query = "DELETE FROM classroom WHERE schoolId = :schoolId AND classroomId = :classroomId";

        $this->executeQuery($query, [
            ":schoolId" => $schoolId,
            ":classroomId" => $classroomId
        ]);

        return true;
    }

    public function createClassroom($schoolId, $classroomRef, $classroomNumberSeats, $classroomProjector)
    {
        $query = "INSERT INTO classroom ";
        $query .= "(schoolId, classroomRef, classroomNumberSeats, classroomProjector)";
        $query .= " VALUES ";
        $query .= "(:schoolId, :classroomRef, :classroomNumberSeats, :classroomProjector)";

        $this->executeQuery($query, [
            ":schoolId" => $schoolId,
            ":classroomRef" => $classroomRef,
            ":classroomNumberSeats" => $classroomNumberSeats,
            ":classroomProjector" => $classroomProjector
        ]);

        return $this->dbh->lastInsertId();
    }

    public function getClassroomByRef($schoolId, $classroomRef)
    {
        $query = "SELECT * FROM classroom WHERE schoolId = :schoolId AND classroomRef = :classroomRef";

        $sth = $this->executeQuery($query, [
            ":schoolId" => $schoolId,
            ":classroomRef" => $classroomRef
        ]);

        return $sth->fetch();
    }

    public function updateClassroomById($schoolId, $classroomId, $classroomRef, $classroomNumberSeats, $classroomProjector)
    {
        $query = "UPDATE classroom SET ";
        $query .= "classroomRef = :classroomRef, ";
        $query .= "classroomNumberSeats = :classroomNumberSeats, ";
        $query .= "classroomProjector = :classroomProjector ";
        $query .= "WHERE schoolId = :schoolId AND classroomId = :classroomId ";

        $this->executeQuery($query, [
            ":classroomRef" => $classroomRef,
            ":classroomNumberSeats" => $classroomNumberSeats,
            ':classroomProjector' => $classroomProjector,
            ':classroomId' => $classroomId,
            ':schoolId' => $schoolId
        ]);

        return true;
    }
}

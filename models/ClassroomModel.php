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

    // ALGO

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
    public function getClassroomAvailabilitiesByClassroomId($classroomId)
    {
        $query = "SELECT dayId, timeslotId, classroomAvailability ";
        $query .= "FROM classroom_availability ";
        $query .= "WHERE classroomId = :classroomId";

        $sth = $this->executeQuery($query, [
            ":classroomId" => $classroomId
        ]);

        $availabilities = [];
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            if (!isset($availabilities[$row['dayId']])) {
                $availabilities[$row['dayId']] = [];
            }
            $availabilities[$row['dayId']][$row['timeslotId']] = $row['classroomAvailability'];
        }

        return $availabilities;
    }
    public function getClassroomSubjectsByClassroomId($classroomId)
    {
        $query = "SELECT subjectId ";
        $query .= "FROM classroom_subject ";
        $query .= "WHERE classroomId = :classroomId";

        $sth = $this->executeQuery($query, [
            ":classroomId" => $classroomId
        ]);

        $classroomSubjects = [];
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $classroomSubjects[] = $row['subjectId'];
        }

        return $classroomSubjects;
    }



    public function getTotalClassroomHours($schoolId)
    {
        $query = "SELECT
                    COUNT(*) AS TotalHours
                  FROM
                    classroom_availability ca
                  JOIN
                    classroom c ON ca.classroomId = c.classroomId
                  WHERE
                    ca.classroomAvailability = 'available'
                    AND c.schoolId = :schoolId";

        $sth = $this->executeQuery($query, [
            ":schoolId" => $schoolId
        ]);

        $result = $sth->fetch(PDO::FETCH_ASSOC);

        return $result['TotalHours'];
    }
}

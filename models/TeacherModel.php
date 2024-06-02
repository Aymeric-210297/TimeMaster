<?php

class TeacherModel extends BaseModel
{
    public function getTeachersBySchool($schoolId, $offset, $limit, $search = null)
    {
        $query = "SELECT teacherId, teacherGivenName, teacherFamilyName, teacherGender, teacherNumberHours ";
        $query .= "FROM teacher ";
        $query .= "WHERE teacher.schoolId = :schoolId ";
        if (!empty($search)) {
            $query .= "AND (teacherGivenName LIKE :search OR teacherFamilyName LIKE :search) ";
        }
        $query .= "ORDER BY teacherFamilyName ASC, teacherGivenName ASC ";
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

    public function countTeachersBySchool($schoolId, $search = null)
    {
        $query = "SELECT COUNT(*) ";
        $query .= "FROM teacher ";
        $query .= "WHERE teacher.schoolId = :schoolId ";
        if (!empty($search)) {
            $query .= "AND (teacherGivenName LIKE :search OR teacherFamilyName LIKE :search) ";
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

    public function getTeacherById($schoolId, $teacherId)
    {
        $query = "SELECT teacherId, teacherGivenName, teacherFamilyName, teacherEmail, teacherGender, teacherNumberHours ";
        $query .= "FROM teacher ";
        $query .= "WHERE teacher.schoolId = :schoolId AND teacher.teacherId = :teacherId";

        $sth = $this->executeQuery($query, [
            ":schoolId" => $schoolId,
            ":teacherId" => $teacherId
        ]);

        return $sth->fetch();
    }

    public function deleteTeacherById($schoolId, $teacherId)
    {
        $query = "DELETE FROM teacher WHERE schoolId = :schoolId AND teacherId = :teacherId";

        $this->executeQuery($query, [
            ":schoolId" => $schoolId,
            ":teacherId" => $teacherId
        ]);

        return true;
    }

    public function createTeacher($schoolId, $teacherGivenName, $teacherFamilyName, $teacherEmail, $teacherGender, $teacherNumberHours)
    {
        $query = "INSERT INTO teacher ";
        $query .= "(schoolId, teacherGivenName, teacherFamilyName, teacherEmail, teacherGender, teacherNumberHours)";
        $query .= " VALUES ";
        $query .= "(:schoolId, :teacherGivenName, :teacherFamilyName, :teacherEmail, :teacherGender, :teacherNumberHours)";

        $this->executeQuery($query, [
            ":schoolId" => $schoolId,
            ":teacherGivenName" => $teacherGivenName,
            ":teacherFamilyName" => $teacherFamilyName,
            ":teacherEmail" => $teacherEmail,
            ":teacherGender" => $teacherGender,
            ":teacherNumberHours" => $teacherNumberHours
        ]);

        return $this->dbh->lastInsertId();
    }

    public function getTeacherByEmail($schoolId, $teacherEmail)
    {
        $query = "SELECT * FROM teacher WHERE schoolId = :schoolId AND teacherEmail = :teacherEmail";

        $sth = $this->executeQuery($query, [
            ":schoolId" => $schoolId,
            ":teacherEmail" => $teacherEmail
        ]);

        return $sth->fetch();
    }

    public function updateTeacherById($schoolId, $teacherId, $teacherGivenName, $teacherFamilyName, $teacherEmail, $teacherGender, $teacherNumberHours)
    {
        $query = "UPDATE teacher SET ";
        $query .= "teacherGivenName = :teacherGivenName, ";
        $query .= "teacherFamilyName = :teacherFamilyName, ";
        $query .= "teacherEmail = :teacherEmail, ";
        $query .= "teacherGender = :teacherGender, ";
        $query .= "teacherNumberHours = :teacherNumberHours ";
        $query .= "WHERE schoolId = :schoolId AND teacherId  = :teacherId ";

        $this->executeQuery($query, [
            ":teacherGivenName" => $teacherGivenName,
            ":teacherFamilyName" => $teacherFamilyName,
            ":teacherEmail" => $teacherEmail,
            ":teacherGender" => $teacherGender,
            ":teacherNumberHours" => $teacherNumberHours,
            ':schoolId' => $schoolId,
            ':teacherId' => $teacherId
        ]);

        return true;
    }

    // ALGO

    public function getTeacherCountBySchoolId($schoolId)
    {
        $query = "SELECT COUNT(*) AS teacherCount ";
        $query .= "FROM teacher ";
        $query .= "WHERE schoolId = :schoolId";

        $sth = $this->executeQuery($query, [
            ":schoolId" => $schoolId
        ]);

        return $sth->fetch(PDO::FETCH_ASSOC)['teacherCount'];
    }
    public function getSubjectIdsByTeacherId($teacherId)
    {
        $query = "SELECT subjectId ";
        $query .= "FROM teacher_subject ";
        $query .= "WHERE teacherId = :teacherId";

        $sth = $this->executeQuery($query, [
            ":teacherId" => $teacherId
        ]);

        $subjectIds = [];
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $subjectIds[] = $row['subjectId'];
        }

        return $subjectIds;
    }
    public function getTeacherIdBySchoolIdAndIndex($schoolId, $index)
    {
        $query = "SELECT teacherId ";
        $query .= "FROM teacher ";
        $query .= "WHERE schoolId = :schoolId ";
        $query .= "ORDER BY teacherId ASC ";
        $query .= "LIMIT 1 OFFSET :index";

        // Le binding des paramètres OFFSET nécessite une approche légèrement différente
        $sth = $this->dbh->prepare($query);
        $sth->bindParam(':schoolId', $schoolId, PDO::PARAM_INT);
        $sth->bindValue(':index', (int) $index, PDO::PARAM_INT);
        $sth->execute();

        return $sth->fetch(PDO::FETCH_ASSOC)['teacherId'];
    }
    public function getTeacherHoursByTeacherId($teacherId)
    {
        $query = "SELECT teacherNumberHours ";
        $query .= "FROM teacher ";
        $query .= "WHERE teacherId = :teacherId";

        $sth = $this->executeQuery($query, [
            ":teacherId" => $teacherId
        ]);

        return $sth->fetch(PDO::FETCH_ASSOC)['teacherNumberHours'];
    }
    public function getClassroomsByTeacherId($teacherId)
    {
        $query = "SELECT classroomId, teacherClassroomRanking ";
        $query .= "FROM teacher_classroom ";
        $query .= "WHERE teacherId = :teacherId";

        $sth = $this->executeQuery($query, [
            ":teacherId" => $teacherId
        ]);

        $classrooms = [];
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $classrooms[$row['teacherClassroomRanking']] = $row['classroomId'];
        }

        return $classrooms;
    }
    public function getAvailabilitiesByTeacherId($teacherId)
    {
        $query = "SELECT dayId, timeslotId, teacherAvailability ";
        $query .= "FROM teacher_availability ";
        $query .= "WHERE teacherId = :teacherId";

        $sth = $this->executeQuery($query, [
            ":teacherId" => $teacherId
        ]);

        $availabilities = [];
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            if (!isset($availabilities[$row['dayId']])) {
                $availabilities[$row['dayId']] = [];
            }
            $availabilities[$row['dayId']][$row['timeslotId']] = $row['teacherAvailability'];
        }

        return $availabilities;
    }

    public function getTeacherHoursBySubject($schoolId)
    {
        $query = "SELECT
                    ts.subjectId,
                    SUM(t.teacherNumberHours) AS TotalHours
                  FROM
                    teacher_subject ts
                    JOIN teacher t ON ts.teacherId = t.teacherId
                  WHERE
                    t.schoolId = :schoolId
                  GROUP BY
                    ts.subjectId";

        $sth = $this->executeQuery($query, [
            ":schoolId" => $schoolId
        ]);

        $results = $sth->fetchAll(PDO::FETCH_ASSOC);

        $tabProf = [];
        foreach ($results as $row) {
            $tabProf[$row['subjectId']] = $row['TotalHours'];
        }

        return $tabProf;
    }
}

<?php
class horaireModel extends BaseModel
{
    public function recupInfoHoraire($classId)
    {
        try {
            $query = "SELECT 
                          teacher.teacherFamilyName AS NomProfesseur, 
                          teacher.teacherGivenName AS PrenomProfesseur, 
                          classroom.classroomRef AS SalleClasse, 
                          class.classRef AS Classe, 
                          day.dayName AS Jour, 
                          timeslot.timeslotStartHour AS HeureDebut, 
                          timeslot.timeslotEndHour AS HeureFin 
                      FROM 
                          class_schedule 
                      JOIN teacher ON class_schedule.teacherId = teacher.teacherId 
                      JOIN classroom ON class_schedule.classroomId = classroom.classroomId 
                      JOIN class ON class_schedule.classId = class.classId 
                      JOIN day ON class_schedule.dayId = day.dayId 
                      JOIN timeslot ON class_schedule.timeslotId = timeslot.timeslotId 
                      WHERE 
                          class_schedule.classId = :classId";
            $sth = $this->executeQuery($query, [':classId' => $classId]);
            return $sth->fetchAll();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function checkSiHoraireExiste($timeslotId, $classId, $dayId)
    {
        try {
            $query = "SELECT 
                          class_schedule.*, 
                          teacher.teacherFamilyName, 
                          teacher.teacherGivenName, 
                          class.classRef, 
                          classroom.classroomRef, 
                          subject.subjectName 
                      FROM 
                          class_schedule 
                      JOIN teacher ON class_schedule.teacherId = teacher.teacherId 
                      JOIN class ON class_schedule.classId = class.classId 
                      JOIN classroom ON class_schedule.classroomId = classroom.classroomId 
                      JOIN subject ON class_schedule.subjectId = subject.subjectId 
                      WHERE 
                          class_schedule.dayId = :dayId 
                          AND class_schedule.timeslotId = :timeslotId 
                          AND class_schedule.classId = :classId";
            $sth = $this->executeQuery($query, [
                ':dayId' => $dayId,
                ':timeslotId' => $timeslotId,
                ':classId' => $classId
            ]);
            return $sth->fetch();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function recupInfoParCreneauIdEtJourId($timeslotId, $classId, $dayId)
    {
        try {
            $query = "SELECT 
                          teacher.teacherFamilyName, 
                          teacher.teacherGivenName, 
                          subject.subjectName, 
                          classroom.classroomRef 
                      FROM 
                          class_schedule 
                      JOIN teacher ON class_schedule.teacherId = teacher.teacherId 
                      JOIN subject ON class_schedule.subjectId = subject.subjectId 
                      JOIN classroom ON class_schedule.classroomId = classroom.classroomId 
                      WHERE 
                          class_schedule.classId = :classId 
                          AND class_schedule.timeslotId = :timeslotId 
                          AND class_schedule.dayId = :dayId";
            $sth = $this->executeQuery($query, [
                ':classId' => $classId,
                ':timeslotId' => $timeslotId,
                ':dayId' => $dayId
            ]);
            return $sth->fetch();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}
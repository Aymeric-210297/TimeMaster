<?php

class ScheduleGenerator extends BaseModel
{
    private $classModel;
    private $teacherModel;
    private $classroomModel;
    private $timeslotModel;
    private $scheduleModel;

    public function __construct($dbh, $errorCallback = null)
    {
        $this->classModel = new ClassModel($dbh, $errorCallback);
        $this->teacherModel = new TeacherModel($dbh, $errorCallback);
        $this->classroomModel = new ClassroomModel($dbh, $errorCallback);
        $this->timeslotModel = new creneauModel($dbh, $errorCallback);
        $this->scheduleModel = new ScheduleModel($dbh, $errorCallback);
    }

    public function generateSchedule($schoolId)
    {
        $classes = $this->classModel->getClasses();
        $teachers = $this->teacherModel->getTeachers();
        $classrooms = $this->classroomModel->getClassrooms();
        $timeslots = $this->timeslotModel->getTimeslots();

        $scheduleId = $this->scheduleModel->createSchedule($schoolId);

        foreach ($classes as $class) {
            foreach ($class['subjects'] as $subject) {
                $allocated = false;

                foreach ($teachers as $teacher) {
                    if ($this->isTeacherAvailable($teacher, $subject, $timeslots)) {
                        foreach ($classrooms as $classroom) {
                            if ($this->isClassroomAvailable($classroom, $timeslots)) {
                                $this->allocateClass($scheduleId, $class, $teacher, $classroom, $subject, $timeslots);
                                $allocated = true;
                                break;
                            }
                        }
                    }

                    if ($allocated) break;
                }

                if (!$allocated) {
                    throw new Exception("Unable to allocate class {$class['classRef']} for subject {$subject['subjectName']}");
                }
            }
        }
    }

    private function isTeacherAvailable($teacherId, $subjectId, $timeslots)
{
    $query = "SELECT COUNT(*) as count FROM teacher_availability 
              WHERE teacherId = :teacherId AND dayId = :dayId AND timeslotId = :timeslotId 
              AND teacherAvailability IN ('available', 'prefer-not')";

    foreach ($timeslots as $timeslot) {
        $params = [
            ':teacherId' => $teacherId,
            ':dayId' => $timeslot['dayId'],
            ':timeslotId' => $timeslot['timeslotId']
        ];

        $sth = $this->executeQuery($query, $params);
        $result = $sth->fetch();
        if ($result['count'] == 0) {
            return false;
        }
    }

    return true;
}

private function isClassroomAvailable($classroomId, $timeslots)
{
    $query = "SELECT COUNT(*) as count FROM classroom_availability 
              WHERE classroomId = :classroomId AND dayId = :dayId AND timeslotId = :timeslotId 
              AND classroomAvailability = 'available'";

    foreach ($timeslots as $timeslot) {
        $params = [
            ':classroomId' => $classroomId,
            ':dayId' => $timeslot['dayId'],
            ':timeslotId' => $timeslot['timeslotId']
        ];

        $sth = $this->executeQuery($query, $params);
        $result = $sth->fetch();
        if ($result['count'] == 0) {
            return false;
        }
    }

    return true;
}

private function allocateClass($scheduleId, $classId, $teacherId, $classroomId, $subjectId, $timeslots)
{
    foreach ($timeslots as $timeslot) {
        $query = "INSERT INTO class_schedule 
                  (scheduleId, dayId, timeslotId, classId, teacherId, classroomId, subjectId) 
                  VALUES (:scheduleId, :dayId, :timeslotId, :classId, :teacherId, :classroomId, :subjectId)";

        $params = [
            ':scheduleId' => $scheduleId,
            ':dayId' => $timeslot['dayId'],
            ':timeslotId' => $timeslot['timeslotId'],
            ':classId' => $classId,
            ':teacherId' => $teacherId,
            ':classroomId' => $classroomId,
            ':subjectId' => $subjectId
        ];

        $this->executeQuery($query, $params);
    }
}
public function optimizeSchedule($scheduleId){
    // Get all classes, teachers, classrooms, and subjects
    $query = "SELECT * FROM class";
    $classes = $this->executeQuery($query)->fetchAll();

    $query = "SELECT * FROM teacher";
    $teachers = $this->executeQuery($query)->fetchAll();

    $query = "SELECT * FROM classroom";
    $classrooms = $this->executeQuery($query)->fetchAll();

    $query = "SELECT * FROM subject";
    $subjects = $this->executeQuery($query)->fetchAll();

    // Define time slots for optimization
    $query = "SELECT * FROM timeslot";
    $timeslots = $this->executeQuery($query)->fetchAll();

    // Attempt to allocate each class to a suitable time slot, teacher, and classroom
    foreach ($classes as $class) {
        foreach ($subjects as $subject) {
            foreach ($teachers as $teacher) {
                foreach ($classrooms as $classroom) {
                    // Check if the teacher is available
                    if ($this->isTeacherAvailable($teacher['teacherId'], $subject['subjectId'], $timeslots)) {
                        // Check if the classroom is available
                        if ($this->isClassroomAvailable($classroom['classroomId'], $timeslots)) {
                            // Allocate the class
                            $this->allocateClass($scheduleId, $class['classId'], $teacher['teacherId'], $classroom['classroomId'], $subject['subjectId'], $timeslots);
                        }
                    }
                }
            }
        }
    }
}
}
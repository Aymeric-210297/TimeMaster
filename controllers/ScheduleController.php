<?php

require_once __DIR__ . "/../models/UserModel.php";
require_once __DIR__ . "/../models/SchoolModel.php";
require_once __DIR__ . "/../models/ClassModel.php";
require_once __DIR__ . "/../models/ScheduleModel.php";
require_once __DIR__ . "/../models/DayModel.php";
require_once __DIR__ . "/../models/TimeSlotModel.php";

$userModel = new UserModel($dbh, createErrorCallback(500));
$schoolModel = new SchoolModel($dbh, createErrorCallback(500));
$dayModel = new DayModel($dbh, createErrorCallback(500));
$timeSlotModel = new TimeSlotModel($dbh, createErrorCallback(500));
$scheduleModel = new ScheduleModel($dbh, createErrorCallback(500));
$classModel = new ClassModel($dbh, createErrorCallback(500));

get('/app/schools/$schoolId/schedules', function ($schoolId) use ($userModel, $schoolModel, $scheduleModel, $timeSlotModel, $dayModel) {
    checkAuth($userModel, $schoolId);

    $school = $schoolModel->getSchoolById($schoolId);

    if (!$school->schoolAlgoGenerating) {
        $schedules = $scheduleModel->getSchedulesBySchool($schoolId);
    }

    render('app', 'schedules/schedules', [
        'school' => $school,
        'schedules' => $schedules ?? null,
        'navbarItem' => 'SCHEDULES',
    ]);
});

get('/app/schools/$schoolId/schedules/generate', function ($schoolId) use ($userModel, $schoolModel) {
    checkCsrf();
    checkAuth($userModel, $schoolId);

    $school = $schoolModel->getSchoolById($schoolId);

    if ($school->schoolAlgoGenerating) {
        createFlashMessage("Les horaires sont déjà en cours de génération", "Vous devez attendre que les horaires finissent de se générer", 'warning');
        redirect();
    }

    $schoolModel->updateSchoolAlgoGeneratingById($schoolId, true);
    shell_exec("php " . __DIR__ . "/../scripts/algo.php " . escapeshellarg($schoolId) . " > /dev/null 2>/dev/null &");
    redirect();
});

get('/app/schools/$schoolId/schedules/$entity/$entityId', function ($schoolId, $entity, $entityId) use ($userModel, $schoolModel, $dayModel, $timeSlotModel, $scheduleModel) {
    checkAuth($userModel, $schoolId);

    switch ($entity) {
        case 'classes':
            $schedule = $scheduleModel->getScheduleByClassId($schoolId, $entityId);
            break;
        case 'classrooms':
            $schedule = $scheduleModel->getScheduleByClassroomId($schoolId, $entityId);
            break;
        case 'teachers':
            $schedule = $scheduleModel->getScheduleByTeacherId($schoolId, $entityId);
            break;
        default:
            render('error', '404');
    }

    $school = $schoolModel->getSchoolById($schoolId);

    $days = $dayModel->recupJourParEtablissement($schoolId);
    $timeslots = $timeSlotModel->recupCreneauParEtablissement($schoolId);

    render(
        "app",
        "schedules/schedules-view",
        [
            'entity' => $entity,
            'school' => $school,
            'schedule' => $schedule,
            'days' => $days,
            'timeslots' => $timeslots,
            'navbarItem' => 'SCHEDULES',
        ]
    );
});

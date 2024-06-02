<?php

$titlesByGender = [
    'F' => 'Mme. ',
    'M' => 'M. ',
    'X' => ''
];

$pageTitlesByEntity = [
    'classes' => "d'une classe",
    'classrooms' => "d'une salle de classe",
    'teachers' => "d'un professeur"
];

$pageDescriptionsByEntity = [
    'classes' => "de la classe",
    'classrooms' => "de la salle de classe",
    'teachers' => "du professeur"
];

$emptyMessagesByEntity = [
    'classes' => "cette classe",
    'classrooms' => "cette salle de classe",
    'teachers' => "ce professeur"
];

?>

<main>
    <div class="content-header">
        <div>
            <h2>Horaire <?= $pageTitlesByEntity[$entity] ?></h2>
            <p>Affichage de l'horaire <?= $pageDescriptionsByEntity[$entity] ?></p>
        </div>
    </div>

    <?php if (!isset($schedule) || count($schedule) <= 0): ?>
        <div class="no-element">
            <i class="fa-solid fa-calendar-xmark"></i>
            <p>Aucun horaire trouvé pour <?= $emptyMessagesByEntity[$entity] ?>, vous pouvez générer les horaires en <a
                    href="/app/schools/<?= $school->schoolId ?>/schedules" class="link primary">cliquant ici</a>.
            </p>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Créneau / Jour</th>
                    <?php foreach ($days as $day): ?>
                        <th><?= out($day->dayName) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($timeslots as $timeslot): ?>
                    <tr>
                        <td><?= out($timeslot->timeslotStartHour) . ' - ' . out($timeslot->timeslotEndHour) ?></td>
                        <?php foreach ($days as $day): ?>
                            <td>
                                <?php
                                $entryFound = false;
                                foreach ($schedule as $entry) {
                                    if ($entry->timeslotId == $timeslot->timeslotId && $entry->dayId == $day->dayId) {
                                        ?>
                                        <p><?= out($entry->subjectName) ?></p>
                                        <?php if ($entity === 'classes'): ?>
                                            <p>
                                                <?= $titlesByGender[$entry->teacherGender] . out($entry->teacherFamilyName) ?>
                                            </p>
                                            <p><?= out($entry->classroomRef) ?></p>
                                        <?php elseif ($entity === 'teachers'): ?>
                                            <p><?= out($entry->classRef) ?></p>
                                            <p><?= out($entry->classroomRef) ?></p>
                                        <?php elseif ($entity === 'classrooms'): ?>
                                            <p>
                                                <?= $titlesByGender[$entry->teacherGender] . out($entry->teacherFamilyName) ?>
                                            </p>
                                            <p><?= out($entry->classRef) ?></p>
                                        <?php endif; ?>
                                        <?php
                                        $entryFound = true;
                                        break;
                                    }
                                }
                                if (!$entryFound): ?>
                                    <p>--</p>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<main>
    <div class="content-header">
        <div>
            <h2>Liste des horaires</h2>
        </div>
    </div>
</main>

<form method="GET" action="/app/testAffichageH">
    <label for="classSelect">Sélectionnez un horaire à afficher</label>
    <select name="classId" id="classSelect">
        <?php foreach ($classList as $class): ?>
            <option value="<?= htmlspecialchars($class->classId) ?>">
                <?= htmlspecialchars($class->classRef) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Afficher l'horaire</button>
</form>

<?php if (!empty($schedule)): ?>
    <h3>Horaire pour la classe sélectionnée:</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Créneau/Jour</th>
                <?php foreach ($days as $day): ?>
                    <th><?= htmlspecialchars($day->dayName) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($timeslots as $timeslot): ?>
                <tr>
                    <td><?= htmlspecialchars($timeslot->timeslotStartHour) . ' - ' . htmlspecialchars($timeslot->timeslotEndHour) ?></td>
                    <?php foreach ($days as $day): ?>
                        <td>
                            <?php 
                                $entryFound = false;
                                foreach ($schedule as $entry) {
                                    if ($entry->timeslotId == $timeslot->timeslotId && $entry->dayId == $day->dayId) {
                                        ?>
                                        <p><?= htmlspecialchars($entry->subjectName) ?></p>
                                        <p><?php if ($entry->teacherGender == "M"):?>M.<?php else : ?>Mme<?php endif ?> <?= htmlspecialchars($entry->teacherFamilyName) ?></p>
                                        <p><?= htmlspecialchars($entry->classroomRef) ?></p>
                                        <?php
                                        $entryFound = true;
                                        break;
                                    }
                                }
                                if (!$entryFound): ?>
                                    <p>-</p>
                                <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
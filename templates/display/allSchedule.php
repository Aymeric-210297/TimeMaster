<?php
$horaireModel = new horaireModel($dbh, createErrorCallback(500, "out"));
$listeInfo = $horaireModel->recupInfoHoraire(1);

 ?>

<body>
    <a href="/affichageHoraire">affichage horaire</a>
    <?php for ($i=0; $i < 10; $i++) : ?>
        <?php
        $classNumber = $i+1;
        $listeInfo = $horaireModel->recupInfoHoraire($classNumber);
        ?>
        <h1>Id de la classe : <?= $classNumber ?></h1>
        <table>
        <thead>
            <tr>
                <th>Heure</th>
                <?php foreach ($listeJour as $jour) : ?>
                    <th><?= $jour->dayName ?></th>
                <?php endforeach ?>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($listeCreneau as $creneau) : ?>
            <tr>  
                <td><?= $creneau->timeslotStartHour . " - " . $creneau->timeslotEndHour ?></td>
                <?php foreach ($listeJour as $jour) : ?>
                    <?php if (isset($horaireModel->checkSiHoraireExiste($creneau->timeslotId, $classNumber, $jour->dayId)->classScheduleId)) : ?>
                        <td class="info-cell">
                            <p class="bold"><?= $horaireModel->recupInfoParCreneauIdEtJourId($creneau->timeslotId, $classNumber, $jour->dayId)->subjectName ?></p>
                            <p><?= $horaireModel->recupInfoParCreneauIdEtJourId($creneau->timeslotId, $classNumber, $jour->dayId)->classroomRef ?></p>
                            <p><?= strtoupper($horaireModel->recupInfoParCreneauIdEtJourId($creneau->timeslotId, $classNumber, $jour->dayId)->teacherFamilyName) ?>
                               <?= $horaireModel->recupInfoParCreneauIdEtJourId($creneau->timeslotId, $classNumber, $jour->dayId)->teacherGivenName ?></p>
                        </td>
                    <?php else : ?>
                        <td class="null-cell"></td>
                    <?php endif ?>
                <?php endforeach ?>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
    <?php endfor ?>
    
</body>
</html>
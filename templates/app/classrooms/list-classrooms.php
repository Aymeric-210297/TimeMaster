<main>
    <div class="content-header">
        <div>
            <h2>Salles de classe</h2>
            <p>Vous pouvez gérer l'ensemble des salle de classes de l'établissement via cette page.</p>
        </div>
        <div>
            <a href="/app/schools/<?= $school->schoolId ?>/classrooms/add" class="button primary">
                Ajouter une salle de classe <i class="fa-solid fa-plus"></i>
            </a>
        </div>
    </div>

    <div class="table-top">
        <form method="get" class="search">
            <input type="search" name="search" id="search" placeholder="Rechercher"
                value="<?= out($_GET['search'] ?? '') ?>" />
            <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
    </div>

    <?php if (count($classrooms) <= 0): ?>
        <div class="no-element">
            <i class="fa-solid fa-magnifying-glass-minus"></i>
            <p>Aucune salle de classe trouvée, vous pouvez en ajouter en <a
                    href="/app/schools/<?= $school->schoolId ?>/classrooms/add" class="link primary">cliquant ici</a>.</p>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Nom de la classe</th>
                    <th>Nombre de place</th>
                    <th>Projecteur</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classrooms as $classroom): ?>
                    <tr>
                        <td><?= out($classroom->classroomRef) ?></td>
                        <td><?= out($classroom->classroomNumberSeats) ?></td>
                        <td><?php if (out($classroom->classroomProjector) == 1): ?>Oui<?php else: ?>Non<?php endif ?></td>

                        <td class="actions">
                            <?php if (!$school->schoolAlgoGenerating && $classroom->classroomExistsInSchedule): ?>
                                <a class="link success icon"
                                    href="/app/schools/<?= $school->schoolId ?>/schedules/classrooms/<?= $classroom->classroomId ?>">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </a>
                            <?php endif; ?>
                            <a class="link primary icon"
                                href="/app/schools/<?= $school->schoolId ?>/classrooms/<?= $classroom->classroomId ?>">
                                <i class="fa-solid fa-edit"></i>
                            </a>
                            <a class="link error icon"
                                href="/app/schools/<?= $school->schoolId ?>/classrooms/<?= $classroom->classroomId ?>/delete?csrf=<?= $_SESSION['csrf'] ?>">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php include_once __DIR__ . '/../../components/pagination.php' ?>
    <?php endif; ?>
</main>

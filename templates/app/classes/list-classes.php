<main>
    <div class="content-header">
        <div>
            <h2>Classes</h2>
            <p>Vous pouvez gérer l'ensemble des classes de l'établissement via cette page.</p>
        </div>
        <div>
            <a href="/app/schools/<?= $school->schoolId ?>/classes/add" class="button primary">
                Ajouter une classe <i class="fa-solid fa-plus"></i>
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

    <?php if (count($classes) <= 0): ?>
        <div class="no-element">
            <i class="fa-solid fa-magnifying-glass-minus"></i>
            <p>Aucun classe trouvée, vous pouvez en ajouter en <a href="/app/schools/<?= $school->schoolId ?>/classes/add"
                    class="link primary">cliquant ici</a>.</p>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Nom de la classe</th>
                    <th>Nombre d'élèves</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classes as $class): ?>
                    <tr>
                        <td><?= out($class->classRef) ?></td>
                        <td><?= out($class->numberOfStudents) ?></td>
                        <td class="actions">
                            <?php if (!$school->schoolAlgoGenerating && $class->classExistsInSchedule): ?>
                                <a class="link success icon"
                                    href="/app/schools/<?= $school->schoolId ?>/schedules/classes/<?= $class->classId ?>">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </a>
                            <?php endif; ?>
                            <a class="link primary icon"
                                href="/app/schools/<?= $school->schoolId ?>/classes/<?= $class->classId ?>">
                                <i class="fa-solid fa-edit"></i>
                            </a>
                            <a class="link error icon"
                                href="/app/schools/<?= $school->schoolId ?>/classes/<?= $class->classId ?>/delete?csrf=<?= $_SESSION['csrf'] ?>">
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

<main>
    <div class="content-header">
        <div>
            <h2>Élèves de la classe</h2>
            <p>Vous pouvez modifier les élèves d'une classe via cette page.</p>
        </div>
        <div>
            <a href="/app/schools/<?= $school->schoolId ?>/classes/<?= $class->classId ?>" class="button secondary">
                Modifier la classe <i class="fa-solid fa-edit"></i>
            </a>
            <a href="/app/schools/<?= $school->schoolId ?>/students?add-class=<?= $class->classId ?>"
                class="button primary">
                Ajouter un élève à cette classe <i class="fa-solid fa-plus"></i>
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

    <?php if (count($students) <= 0): ?>
        <div class="no-element">
            <i class="fa-solid fa-magnifying-glass-minus"></i>
            <p>Aucun élève trouvé, vous pouvez en ajouter en <a
                    href="/app/schools/<?= $school->schoolId ?>/students?add-class=<?= $class->classId ?>"
                    class="link primary">cliquant ici</a>.</p>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= out($student->studentGivenName) ?></td>
                        <td><?= out($student->studentFamilyName) ?></td>
                        <td class="actions">
                            <a class="link primary icon"
                                href="/app/schools/<?= $school->schoolId ?>/students/<?= $student->studentId ?>">
                                <i class="fa-solid fa-edit"></i>
                            </a>
                            <a class="link error icon"
                                href="/app/schools/<?= $school->schoolId ?>/students/<?= $student->studentId ?>/remove-class?csrf=<?= $_SESSION['csrf'] ?>">
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

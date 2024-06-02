<main>
    <div class="content-header">
        <div>
            <h2><?= !empty($_GET['add-class']) ? 'Ajouter un élève à une classe' : 'Élèves' ?></h2>
            <p><?= !empty($_GET['add-class']) ? 'Cliquez sur le plus (+) à côté d\'un élève pour l\'ajouter à la classe.' : 'Vous pouvez gérer l\'ensemble des élèves de l\'établissement via cette page.' ?>
            </p>
        </div>
        <div>
            <a href="/app/schools/<?= $school->schoolId ?>/students/add" class="button primary">
                Ajouter un élève <i class="fa-solid fa-plus"></i>
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
            <?php if (!empty($_GET['add-class'])): ?>
                <p>Aucun élève n'a pas de classe attribué, vous pouvez ajouter un nouvel élève en <a
                        href="/app/schools/<?= $school->schoolId ?>/students/add" class="link primary">cliquant ici</a>.</p>
                <p>Pour déplacer la classe d'un élève, retirez le de sa classe actuelle puis revenez sur cette page.</p>
            <?php else: ?>
                <p>Aucun élève trouvé, vous pouvez en ajouter en <a href="/app/schools/<?= $school->schoolId ?>/students/add"
                        class="link primary">cliquant ici</a>.</p>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <?php if (empty($_GET['add-class'])): ?>
                        <th>Classe</th>
                    <?php endif; ?>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= out($student->studentGivenName) ?></td>
                        <td><?= out($student->studentFamilyName) ?></td>
                        <?php if (empty($_GET['add-class'])): ?>
                            <td><?= out($student->classRef ?? '--') ?></td>
                        <?php endif; ?>
                        <td class="actions">
                            <?php if (!empty($_GET['add-class'])): ?>
                                <a class="link success icon"
                                    href="/app/schools/<?= $school->schoolId ?>/students/<?= $student->studentId ?>/add-class?class-id=<?= out($_GET['add-class']) ?>&csrf=<?= $_SESSION['csrf'] ?>">
                                    <i class="fa-solid fa-plus"></i>
                                </a>
                            <?php else: ?>
                                <a class="link primary icon"
                                    href="/app/schools/<?= $school->schoolId ?>/students/<?= $student->studentId ?>">
                                    <i class="fa-solid fa-edit"></i>
                                </a>
                                <a class="link error icon"
                                    href="/app/schools/<?= $school->schoolId ?>/students/<?= $student->studentId ?>/delete?csrf=<?= $_SESSION['csrf'] ?>">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php include_once __DIR__ . '/../../components/pagination.php' ?>
    <?php endif; ?>
</main>

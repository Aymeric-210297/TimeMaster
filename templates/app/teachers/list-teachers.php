<main>
    <div class="content-header">
        <div>
            <h2>Professeurs</h2>
            <p>Vous pouvez gérer l'ensemble des professeurs de l'établissement via cette page.</p>
        </div>
        <div>
            <a href="/app/schools/<?= $school->schoolId ?>/teachers/add" class="button primary">
                Ajouter un professeur <i class="fa-solid fa-plus"></i>
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

    <?php if (count($teachers) <= 0): ?>
        <div class="no-element">
            <i class="fa-solid fa-magnifying-glass-minus"></i>
            <p>Aucun professeur trouvé, vous pouvez en ajouter en <a
                    href="/app/schools/<?= $school->schoolId ?>/teachers/add" class="link primary">cliquant ici</a>.</p>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Genre</th>
                    <th>Nombre d'heures</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($teachers as $teacher): ?>
                    <tr>
                        <td><?= out($teacher->teacherGivenName) ?></td>
                        <td><?= out($teacher->teacherFamilyName) ?></td>
                        <td><?= out($teacher->teacherGender) ?></td>
                        <td><?= out($teacher->teacherNumberHours) ?></td>
                        <td class="actions">
                            <a class="link primary icon"
                                href="/app/schools/<?= $school->schoolId ?>/teachers/<?= $teacher->teacherId ?>">
                                <i class="fa-solid fa-edit"></i>
                            </a>
                            <a class="link error icon"
                                href="/app/schools/<?= $school->schoolId ?>/teachers/<?= $teacher->teacherId ?>/delete?csrf=<?= $_SESSION['csrf'] ?>">
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

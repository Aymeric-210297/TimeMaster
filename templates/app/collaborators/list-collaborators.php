<main>
    <div class="content-header">
        <div>
            <h2>Collaborateurs</h2>
            <p>Vous pouvez gérer l'ensemble des collaborateurs de l'établissement via cette page.</p>
        </div>
        <div>
            <a href="/app/schools/<?= $school->schoolId ?>/collaborators/add" class="button primary">
                Inviter un collaborateur <i class="fa-solid fa-handshake"></i>
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
    <?php if (count($collaborators) <= 0): ?>
        <div class="no-element">
            <i class="fa-solid fa-magnifying-glass-minus"></i>
            <p>Aucun collaborateur trouvé, vous pouvez inviter des personnes à collaborer en <a
                    href="/app/schools/<?= $school->schoolId ?>/classes/add" class="link primary">cliquant ici</a>.</p>
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
                <?php foreach ($collaborators as $collaborator): ?>
                    <tr>
                        <td><?= out($collaborator->userGivenName) ?><?= $collaborator->userId == $_SESSION['user']->userId ? " <strong>*</strong>" : "" ?>
                        </td>
                        <td><?= out($collaborator->userFamilyName) ?></td>
                        <td class="actions">
                            <a class="link error icon"
                                href="/app/schools/<?= $school->schoolId ?>/collaborators/<?= $collaborator->userId ?>/delete?csrf=<?= $_SESSION['csrf'] ?>">
                                <i class="fa-solid fa-handshake-slash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php include_once __DIR__ . '/../../components/pagination.php' ?>
    <?php endif; ?>
</main>

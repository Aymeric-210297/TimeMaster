<main>
    <div class="content-header">
        <div>
            <h2>Établissements</h2>
            <p>Vous pouvez gérer vos établissements et en ajouter via cette page.
            </p>
        </div>
        <div>
            <a href="/app/schools/add" class="button primary">
                Ajouter un établissement <i class="fa-solid fa-plus"></i>
            </a>
        </div>
    </div>

    <?php if (count($schoolsDetails) <= 0): ?>
        <div class="no-element">
            <i class="fa-solid fa-magnifying-glass-minus"></i>
            <p>Vous ne gérez aucun établissement, vous pouvez ajouter votre établissement en <a href="/app/schools/add"
                    class="link primary">cliquant
                    ici</a>.</p>
        </div>
    <?php else: ?>
        <div class="list-schools">
            <?php foreach ($schoolsDetails as $schoolDetails): ?>
                <div>
                    <div class="head">
                        <h3><?= out($schoolDetails->schoolName) ?></h3>
                        <p><?= out($schoolDetails->schoolAddress) ?></p>
                    </div>
                    <div>
                        <p><?= out($schoolDetails->teacherCount ?? 0) ?> professeurs -
                            <?= out($schoolDetails->studentCount ?? 0) ?> élèves
                        </p>
                        <a href="/app/schools/<?= out($schoolDetails->schoolId) ?>" class="button primary">Gérer</a>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    <?php endif; ?>
</main>

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
    <?php foreach ($schoolsDetails as $schoolDetails): ?>
        <h3><?= out($schoolDetails->schoolName) ?></h3>
        <p><?= out($schoolDetails->schoolAddress) ?></p>
        <p><?= out($schoolDetails->teacherCount) ?> professeurs - <?= out($schoolDetails->studentCount) ?> élèves</p>
    <?php endforeach ?>
    <!-- TODO: style de la page -->
</main>

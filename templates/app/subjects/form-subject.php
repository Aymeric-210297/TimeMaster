<main>
    <div class="content-header">
        <div>
            <h2><?= isset($subject) ? 'Modifier' : 'Ajouter' ?> une matière</h2>
            <p><?= isset($subject) ? 'Vous êtes en train de modifier une matière existante.' : 'Vous pouvez ajouter une matière via cette page.' ?>
            </p>
        </div>
    </div>
    <form method="post">
        <?= set_csrf() ?>

        <?= getFormInput($formViolations, "name", "Nom de la matière", [
            'type' => 'text',
            'required' => true,
            'value' => isset($subject) ? $subject->subjectName : null
        ]) ?>

        <button type="submit" class="button primary"><?= isset($subject) ? 'Sauvegarder' : 'Ajouter' ?> la matière <i
                class="fa-solid fa-<?= isset($subject) ? 'save' : 'add' ?>"></i></button>
        <?php if (isset($subject)): ?>
            <a href="/app/schools/<?= $school->schoolId ?>/subjects/<?= $subject->subjectId ?>/delete?csrf=<?= $_SESSION['csrf'] ?>"
                class="button error">Retirer la matière <i class="fa-solid fa-trash"></i></a>
        <?php endif; ?>
    </form>
</main>

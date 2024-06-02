<main>
    <div class="content-header">
        <div>
            <h2><?= isset($teacher) ? 'Modifier' : 'Ajouter' ?> un professeur</h2>
            <p><?= isset($teacher) ? 'Vous êtes en train de modifier un professeur existant.' : 'Vous pouvez ajouter un professeur via cette page.' ?>
            </p>
        </div>
    </div>
    <form method="post">
        <?= set_csrf() ?>

        <?= getFormInput($formViolations, "first-name", "Prénom du professeur", [
            'type' => 'text',
            'required' => true,
            'value' => isset($teacher) ? $teacher->teacherGivenName : null
        ]) ?>
        <?= getFormInput($formViolations, "last-name", "Nom du professeur", [
            'type' => 'text',
            'required' => true,
            'value' => isset($teacher) ? $teacher->teacherFamilyName : null
        ]) ?>
        <?= getFormInput($formViolations, "email", "Email du professeur", [
            'type' => 'email',
            'required' => true,
            'value' => isset($teacher) ? $teacher->teacherEmail : null
        ], "Indiquez l'email de l'école lié à cet professeur.") ?>
        <!-- TODO: utiliser un select -->
        <?= getFormInput($formViolations, "gender", "Genre du professeur", [
            'type' => 'text',
            'required' => true,
            'value' => isset($teacher) ? $teacher->teacherGender : null
        ], "Indiquez M pour homme, F pour femme ou X pour aucun des deux.") ?>
        <?= getFormInput($formViolations, "number-hours", "Nombre d'heures du professeur", [
            'type' => 'number',
            'required' => true,
            'value' => isset($teacher) ? $teacher->teacherNumberHours : null
        ]) ?>

        <button type="submit" class="button primary"><?= isset($teacher) ? 'Sauvegarder' : 'Ajouter' ?> le professeur <i
                class="fa-solid fa-<?= isset($teacher) ? 'save' : 'add' ?>"></i></button>
        <?php if (isset($teacher)): ?>
            <a href="/app/schools/<?= $school->schoolId ?>/teachers/<?= $teacher->teacherId ?>/delete?csrf=<?= $_SESSION['csrf'] ?>"
                class="button error">Retirer le professeur <i class="fa-solid fa-trash"></i></a>
        <?php endif; ?>
    </form>
</main>

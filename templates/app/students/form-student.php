<main>
    <div class="content-header">
        <div>
            <h2><?= isset($student) ? 'Modifier' : 'Ajouter' ?> un élève</h2>
            <p><?= isset($student) ? 'Vous êtes en train de modifier un élève existant.' : 'Vous pouvez ajouter un élève via cette page.' ?>
            </p>
        </div>
    </div>
    <form method="post">
        <?= set_csrf() ?>

        <?= getFormInput($formViolations, "first-name", "Prénom de l'élève", [
            'type' => 'text',
            'required' => true,
            'value' => isset($student) ? $student->studentGivenName : null
        ]) ?>
        <?= getFormInput($formViolations, "last-name", "Nom de l'élève", [
            'type' => 'text',
            'required' => true,
            'value' => isset($student) ? $student->studentFamilyName : null
        ]) ?>
        <?= getFormInput($formViolations, "email", "Email de l'élève", [
            'type' => 'email',
            'required' => true,
            'value' => isset($student) ? $student->studentEmail : null
        ], "Indiquez l'email de l'école lié à cet élève.") ?>

        <!-- TODO: automatiquement ajouter le nouvel élève à la classe si param GET add-class défini -->
        <button type="submit" class="button primary"><?= isset($student) ? 'Sauvegarder' : 'Ajouter' ?> l'élève <i
                class="fa-solid fa-<?= isset($student) ? 'save' : 'add' ?>"></i></button>
        <?php if (isset($student)): ?>
            <a href="/app/schools/<?= $school->schoolId ?>/students/<?= $student->studentId ?>/delete?csrf=<?= $_SESSION['csrf'] ?>"
                class="button error">Retirer l'élève <i class="fa-solid fa-trash"></i></a>
        <?php endif; ?>
    </form>
</main>

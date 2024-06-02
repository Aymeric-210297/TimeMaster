<main>
    <div class="content-header">
        <div>
            <h2><?= isset($school) ? 'Modifier' : 'Ajouter' ?> un établissement</h2>
            <p><?= isset($school) ? 'Vous pouvez gérer cet établissement via cette page.' : 'Vous pouvez ajouter un établissement via cette page.' ?>
            </p>
        </div>
    </div>
    <form method="post">
        <?= set_csrf() ?>

        <?= getFormInput($formViolations, "name", "Nom de l'établissement", [
            'type' => 'text',
            'required' => true,
            'value' => isset($school) ? $school->schoolName : null
        ]) ?>
        <?= getFormInput($formViolations, "address", "Adresse de l'établissement", [
            'type' => 'text',
            'required' => true,
            'value' => isset($school) ? $school->schoolAddress : null
        ]) ?>

        <button type="submit" class="button primary"><?= isset($school) ? 'Sauvegarder' : 'Ajouter' ?> l'établissement
            <i class="fa-solid fa-<?= isset($school) ? 'save' : 'add' ?>"></i></button>
    </form>
</main>

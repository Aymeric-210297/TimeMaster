<main>
    <div class="content-header">
        <div>
            <h2>Ajouter un établissement</h2>
            <p>Vous pouvez ajouter un établissement via cette page
            </p>
        </div>
    </div>
    <form method="post">
        <?= set_csrf() ?>

        <?= getFormInput($formViolations, "name", "Nom de l'établissement", [
            'type' => 'text',
            'required' => true,
        ]) ?>
        <?= getFormInput($formViolations, "address", "Adresse de l'établissement", [
            'type' => 'text',
            'required' => true,
        ]) ?>

        <button type="submit" class="button primary">Ajouter l'établissement <i class="fa-solid fa-add"></i></button>
    </form>
</main>

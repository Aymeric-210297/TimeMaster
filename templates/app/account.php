<main>
    <h2>Paramètres du compte</h2>
    <form method="post">
        <?= set_csrf() ?>

        <?= getFormInput($formViolations, "first-name", "Prénom", [
            'type' => 'text',
            'autocomplete' => 'given-name',
            'value' => out($_SESSION['user']->userGivenName),
            'required' => true,
        ]) ?>
        <?= getFormInput($formViolations, "last-name", "Nom", [
            'type' => 'text',
            'autocomplete' => 'family-name',
            'value' => out($_SESSION['user']->userFamilyName),
            'required' => true,
        ]) ?>
        <?= getFormInput($formViolations, "email", "Email", [
            'type' => 'email',
            'autocomplete' => 'email',
            'value' => out($_SESSION['user']->userEmail),
            'required' => true,
        ]) ?>
        <?= getFormInput($formViolations, "password", "Nouveau mot de passe", [
            'type' => 'password',
            'autocomplete' => 'new-password',
        ], 'Laissez ce champ vide si vous ne souhaitez pas modifier votre mot de passe.') ?>

        <button type="submit" name="save" class="button primary">Sauvegarder <i class="fa-solid fa-save"></i></button>
        <button type="submit" name="delete-account" class="button error">
            Supprimer mon compte <i class="fa-solid fa-user-minus"></i>
        </button>
    </form>
</main>

<main>
    <h1>Inscription</h1>
    <form method="post">
        <?= set_csrf() ?>

        <div class="one-line">
            <?= getFormInput($formViolations, "first-name", "Prénom", [
                'type' => 'text',
                'autocomplete' => 'given-name',
                'required' => true,
            ]) ?>
            <?= getFormInput($formViolations, "last-name", "Nom", [
                'type' => 'text',
                'autocomplete' => 'family-name',
                'required' => true,
            ]) ?>
        </div>
        <?= getFormInput($formViolations, "email", "Email", [
            'type' => 'email',
            'autocomplete' => 'email',
            'required' => true,
        ]) ?>
        <?= getFormInput($formViolations, "password", "Mot de passe", [
            'type' => 'password',
            'autocomplete' => 'new-password',
            'required' => true,
        ]) ?>

        <p class="switch-form-text">Déjà membre ? <a href="/sign-in">Se connecter</a></p>

        <?php if (!empty($errorMessage)): ?>
            <p class="error-text"><?= out($errorMessage) ?></p>
        <?php endif; ?>

        <button type="submit" class="button primary">S'inscrire</button>
    </form>
</main>

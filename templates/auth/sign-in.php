<main>
    <h1>Connexion</h1>
    <form method="post">
        <?= set_csrf() ?>

        <?= getFormInput($formViolations, "email", "Email", [
            'type' => 'email',
            'autocomplete' => 'email',
            'required' => true,
        ]) ?>
        <?= getFormInput($formViolations, "password", "Mot de passe", [
            'type' => 'password',
            'autocomplete' => 'current-password',
            'required' => true,
        ]) ?>

        <p class="switch-form-text">Pas encore membre ? <a href="/sign-up">S'inscrire</a></p>

        <?php if (!empty($errorMessage)): ?>
            <p class="error-text"><?= out($errorMessage) ?></p>
        <?php endif; ?>

        <button type="submit" class="button primary">Se connecter</button>
    </form>
</main>

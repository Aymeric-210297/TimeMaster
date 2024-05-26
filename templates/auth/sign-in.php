<main>
    <h1>Connexion</h1>
    <form method="post">
        <?= set_csrf() ?>

        <div>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" autocomplete="email" required>
        </div>
        <div>
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" autocomplete="current-password" required>
        </div>

        <p class="switch-form-text">Pas encore membre ? <a href="/sign-up">S'inscrire</a></p>

        <?php if (!empty($errorMessage)): ?>
            <p class="error-text"><?= out($errorMessage) ?></p>
        <?php endif; ?>

        <button type="submit" class="button primary">Se connecter</button>
    </form>
</main>

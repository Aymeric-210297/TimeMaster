<main>
    <h1>Connexion</h1>
    <form method="post">
        <?= set_csrf() ?>

        <div>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" autocomplete="email">
        </div>
        <div>
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" autocomplete="current-password">
        </div>

        <p>Pas encore membre ? <a href="/sign-up">S'inscrire</a></p>

        <button type="submit">Se connecter</button>
    </form>
</main>
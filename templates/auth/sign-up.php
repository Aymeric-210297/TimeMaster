<main>
    <h1>Inscription</h1>
    <form method="post">
        <?= set_csrf() ?>

        <div class="one-line">
            <div>
                <label for="first-name">Prénom</label>
                <input type="text" name="first-name" id="first-name" autocomplete="given-name">
            </div>
            <div>
                <label for="last-name">Nom</label>
                <input type="text" name="last-name" id="last-name" autocomplete="family-name">
            </div>
        </div>
        <div>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" autocomplete="email">
        </div>
        <div>
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" autocomplete="new-password">
        </div>

        <p>Déjà membre ? <a href="/sign-in">Se connecter</a></p>

        <button type="submit">S'inscrire</button>
    </form>
</main>
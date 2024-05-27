<main>
    <h2>Paramètres du compte</h2>
    <form method="post">
        <?= set_csrf() ?>

        <div>
            <label for="first-name">Prénom</label>
            <input type="text" name="first-name" id="first-name" autocomplete="given-name"
                value="<?= out($_SESSION['user']->userGivenName) ?>" required>
        </div>
        <div>
            <label for="last-name">Nom</label>
            <input type="text" name="last-name" id="last-name" autocomplete="family-name"
                value="<?= out($_SESSION['user']->userFamilyName) ?>" required>
        </div>
        <div>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" autocomplete="email"
                value="<?= out($_SESSION['user']->userEmail) ?>" required>
        </div>
        <div>
            <label for="password">Nouveau mot de passe</label>
            <input type="password" name="password" id="password" autocomplete="new-password">
        </div>

        <button type="submit" name="save" class="button primary">Sauvegarder <i class="fa-solid fa-save"></i></button>
        <button type="submit" name="delete-account" class="button error">
            Supprimer mon compte <i class="fa-solid fa-user-minus"></i>
        </button>
    </form>
</main>

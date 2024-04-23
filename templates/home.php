<main>
    <h1>Page d'accueil</h1>
    <?php if (isset($_SESSION['user'])): ?>
        <p>Bonjour, <?= out($_SESSION['user']->utilisateurPrenom) ?></p>
        <a href="/sign-out">Déconnexion</a>
    <?php else: ?>
        <a href="/sign-in">Connexion</a>
    <?php endif; ?>
</main>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil | <?= APP_NAME ?></title>
</head>
<body>
    <main>
        <h1>Page d'accueil</h1>
        <?php if (isset($_SESSION['user'])): ?>
            <p>Bonjour, <?= out($_SESSION['user']->userGivenName) ?></p>
            <a href="/sign-out">Déconnexion</a>
        <?php else: ?>
            <a href="/sign-in">Connexion</a>
        <?php endif; ?>
    </main>
</body>
</html>
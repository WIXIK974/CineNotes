<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>CinéNote 🎬</title>
    <link rel="stylesheet" href="/CINENOTES/assets/styles.css">
</head>
<body>
<header>
    <h1>Bienvenue sur CinéNote 🎬</h1>
    <nav>
        <a class="btn-Accueil" href="/CINENOTES/pages/index.php">Accueil</a> |
        <a class="btn-Accueil" href="/CINENOTES/pages/noter.php">Noter un film</a>
        <?php if (isset($_SESSION['user_id']) && $_SESSION['type_utilisateur'] === 'Admin'): ?>
            | <a class="btn-Accueil" href="/CINENOTES/pages/addPage.php">Ajouter un Film</a>
        <?php endif; ?>
    </nav>
</header>
<main>
    <!-- Contenu principal de votre page -->
</main>
</body>
</html>

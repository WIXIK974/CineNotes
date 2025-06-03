<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CinéNote 🎬</title>
    <link rel="stylesheet" href="/CINENOTES/assets/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="logo">
            <h1>CinéNote 🎬</h1>
        </div>
        <nav>
            <a class="btn-Accueil" href="/CINENOTES/pages/index.php">Accueil</a>
            <?php if (isset($_SESSION['user_id']) && $_SESSION['type_utilisateur'] === 'Admin'): ?>
                | <a href="/CINENOTES/pages/ajouter_film.php">Ajouter un Film</a>
            <?php endif; ?>

        </nav>
    </header>
    <main>

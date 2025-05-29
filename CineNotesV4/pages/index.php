<?php
session_start();
require_once("../config.php");

$query = 'SELECT f.id, f.titre, f.realisateur, f.annee, f.image, AVG(c.note) AS moyenne
          FROM Films f
          LEFT JOIN Critiques c ON f.id = c.id_film
          GROUP BY f.id';
$stmt = $pdo->prepare($query);
$stmt->execute();
$movies = $stmt->fetchAll();

include("../includes/header.php");
?>

<link rel="stylesheet" href="/CINENOTES/assets/styles.css">

<nav>
    <?php if (isset($_SESSION['user_id'])): ?>
        <p>Bonjour, <?php echo $_SESSION['email']; ?>!</p>
        <a href="/CINENOTES/process/logout.php">Se déconnecter</a>
    <?php else: ?>
        <a href="/CINENOTES/pages/login.php">Se connecter</a> | <a href="/CINENOTES/pages/register.php">S’inscrire</a>
    <?php endif; ?>
</nav>

<h2>Films Disponibles</h2>
<div class="movies">
    <?php foreach ($movies as $movie): ?>
    <div class="movie">
        <img src="/CINENOTES/assets/images/<?php echo htmlspecialchars($movie['image']); ?>" alt="<?php echo htmlspecialchars($movie['titre']); ?>">
        <h3><?php echo htmlspecialchars($movie['titre']); ?></h3>
        <p>Réalisateur: <?php echo htmlspecialchars($movie['realisateur']); ?></p>
        <p>Année: <?php echo htmlspecialchars($movie['annee']); ?></p>
        <p>Moyenne des notes: <?php echo number_format($movie['moyenne'], 1); ?></p>
        <a href="<?php echo isset($_SESSION['user_id']) ? '/CINENOTES/pages/noter.php?movie_id=' . $movie['id'] : '/CINENOTES/pages/login.php'; ?>">Noter ce film</a>
        <a href="/CINENOTES/pages/avis.php?movie_id=<?php echo $movie['id']; ?>">Voir les avis</a>
    </div>
    <?php endforeach; ?>
</div>

<?php include("../includes/footer.php"); ?>

<?php
session_start();
include("../config.php");

$movie_id = isset($_GET['movie_id']) ? $_GET['movie_id'] : null;
$movie = null;
$reviews = [];

if ($movie_id) {
    $query = 'SELECT f.titre, c.note, c.commentaire, c.date, u.nom AS utilisateur
              FROM Critiques c
              JOIN Films f ON c.id_film = f.id
              JOIN Utilisateurs u ON c.id_utilisateur = u.id
              WHERE f.id = :movie_id
              ORDER BY c.date DESC';
    $stmt = $pdo->prepare($query);
    $stmt->execute(['movie_id' => $movie_id]);
    $reviews = $stmt->fetchAll();

    $query = 'SELECT titre FROM Films WHERE id = :movie_id';
    $stmt = $pdo->prepare($query);
    $stmt->execute(['movie_id' => $movie_id]);
    $movie = $stmt->fetch();
}

include("../includes/header.php");
?>

<h2>Avis pour <?php echo htmlspecialchars($movie['titre']); ?></h2>

<?php if (empty($reviews)): ?>
    <p>Aucun avis pour ce film.</p>
<?php else: ?>
    <div class="reviews">
        <?php foreach ($reviews as $review): ?>
        <div class="review">
            <p><strong><?php echo htmlspecialchars($review['utilisateur']); ?></strong> - <?php echo $review['date']; ?></p>
            <p>Note: <?php echo $review['note']; ?>/10</p>
            <p><?php echo htmlspecialchars($review['commentaire']); ?></p>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include("../includes/footer.php"); ?>

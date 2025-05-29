<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /CINENOTES/pages/login.php');
    exit();
}

include('../config.php');

$movie_id = isset($_GET['movie_id']) ? $_GET['movie_id'] : null;
$movie = null;

if ($movie_id) {
    $query = 'SELECT id, titre FROM Films WHERE id = :movie_id';
    $stmt = $pdo->prepare($query);
    $stmt->execute(['movie_id' => $movie_id]);
    $movie = $stmt->fetch();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $movie_id = $_POST['movie_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $query = 'INSERT INTO Critiques (id_utilisateur, id_film, note, commentaire) VALUES (:user_id, :movie_id, :rating, :comment)';
    $stmt = $pdo->prepare($query);
    $stmt->execute(['user_id' => $_SESSION['user_id'], 'movie_id' => $movie_id, 'rating' => $rating, 'comment' => $comment]);

    $success = 'Votre note et commentaire ont été enregistrés !';
}

include('../includes/header.php');
?>

<h2>Noter un film</h2>

<?php if ($error): ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p style="color: green;"><?php echo $success; ?></p>
<?php endif; ?>

<?php if ($movie): ?>
<form method="POST" action="noter.php">
    <input type="hidden" name="movie_id" value="<?php echo $movie['id']; ?>">
    <label for="rating">Note (1 à 5) :</label>
    <input type="number" name="rating" id="rating" min="1" max="5" required><br><br>

    <label for="comment">Commentaire :</label>
    <textarea name="comment" id="comment" required></textarea><br><br>

    <button type="submit">Noter</button>
</form>
<?php else: ?>
<p>Film non trouvé.</p>
<?php endif; ?>

<?php include('../includes/footer.php'); ?>

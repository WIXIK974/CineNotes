<!-- noter_process.php -->
<?php
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Connexion à la base de données
include('../pages/config.php');

// Traitement de la notation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $movie_id = $_POST['movie_id'];
    $rating = $_POST['rating'];

    // Vérifier si l'utilisateur a déjà noté ce film
    $query = 'SELECT * FROM ratings WHERE user_id = :user_id AND movie_id = :movie_id';
    $stmt = $pdo->prepare($query);
    $stmt->execute(['user_id' => $_SESSION['user_id'], 'movie_id' => $movie_id]);
    $existing_rating = $stmt->fetch();

    // Si une note existe déjà, on la met à jour
    if ($existing_rating) {
        $query = 'UPDATE ratings SET rating = :rating WHERE user_id = :user_id AND movie_id = :movie_id';
        $stmt = $pdo->prepare($query);
        $stmt->execute(['rating' => $rating, 'user_id' => $_SESSION['user_id'], 'movie_id' => $movie_id]);
        $message = 'Votre note a été mise à jour avec succès !';
    } else {
        // Sinon, on insère une nouvelle note
        $query = 'INSERT INTO ratings (user_id, movie_id, rating) VALUES (:user_id, :movie_id, :rating)';
        $stmt = $pdo->prepare($query);
        $stmt->execute(['user_id' => $_SESSION['user_id'], 'movie_id' => $movie_id, 'rating' => $rating]);
        $message = 'Votre note a été enregistrée !';
    }
}
?>

<?php include('includes/header.php'); ?>

<h2>Résultat de la notation</h2>

<p style="color: green;"><?php echo $message; ?></p>

<p><a href="index.php">Retour à l'accueil</a></p>

<?php include('includes/footer.php'); ?>

<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['type_utilisateur'] !== 'Admin') {
    header('Location: /CINENOTES/pages/index.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre = $_POST['titre'];
    $realisateur = $_POST['realisateur'];
    $annee = $_POST['annee'];

    if (isset($_FILES[`image`]) && $_FILES[`image`]["error"] == UPLOAD_ERR_OK) {
        $imageData = file_get_contents($_FILES[`image`]["tmp_name"]);

        include('../config.php');
        try {
            $query = 'INSERT INTO Films (titre, realisateur, annee, image) VALUES (:titre, :realisateur, :annee, :image)';
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':titre', $titre);
            $stmt->bindParam(':realisateur', $realisateur);
            $stmt->bindParam(':annee', $annee);
            $stmt->bindParam(':image', $imageData, PDO::PARAM_LOB);

            if ($stmt->execute()) {
                $success = 'Film ajouté avec succès !';
            } else {
                $error = "Erreur lors de l'ajout du film.";
            }
        } catch (PDOException $e) {
            $error = 'Erreur SQL : ' . $e->getMessage();
        }
    } else {
        $error = "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
    }
}

include('../includes/header.php');
?>

<h2>Ajouter un Film</h2>

<?php if ($error): ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p style="color: green;"><?php echo $success; ?></p>
<?php endif; ?>

<form method="POST" action="/CINENOTES/pages/ajouter_film.php" enctype="multipart/form-data">
    <label for="titre">Titre :</label>
    <input type="text" name="titre" id="titre" required><br><br>

    <label for="realisateur">Réalisateur :</label>
    <input type="text" name="realisateur" id="realisateur" required><br><br>

    <label for="annee">Année :</label>
    <input type="number" name="annee" id="annee" required><br><br>

    <label for="image">Image :</label>
    <input type="file" name="image" id="image" accept="image/*" required><br><br>

    <button type="submit">Ajouter le Film</button>
</form>

<?php include('../includes/footer.php'); ?>

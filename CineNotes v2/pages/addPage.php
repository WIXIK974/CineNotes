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

    // Gestion du téléchargement de l'image
    $target_dir = "../assets/images/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Vérifiez si le fichier est une image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        $error = "Le fichier n'est pas une image.";
        $uploadOk = 0;
    }

    // Vérifiez si le fichier existe déjà
    if (file_exists($target_file)) {
        $error = "Désolé, le fichier existe déjà.";
        $uploadOk = 0;
    }

    // Vérifiez la taille du fichier
    if ($_FILES["image"]["size"] > 500000) {
        $error = "Désolé, votre fichier est trop volumineux.";
        $uploadOk = 0;
    }

    // Autorisez certains formats de fichiers
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $error = "Désolé, seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
        $uploadOk = 0;
    }

    // Vérifiez si $uploadOk est défini à 0 par une erreur
    if ($uploadOk == 0) {
        $error = "Désolé, votre fichier n'a pas été téléchargé.";
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            include('../config.php');
            try {
                $query = 'INSERT INTO Films (titre, realisateur, annee, image) VALUES (:titre, :realisateur, :annee, :image)';
                $stmt = $pdo->prepare($query);
                $stmt->execute(['titre' => $titre, 'realisateur' => $realisateur, 'annee' => $annee, 'image' => basename($_FILES["image"]["name"])]);
                $success = 'Film ajouté avec succès !';
            } catch (PDOException $e) {
                $error = 'Erreur SQL : ' . $e->getMessage();
            }
        } else {
            $error = "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
        }
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

<form method="POST" action="ajouter_film.php" enctype="multipart/form-data">
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

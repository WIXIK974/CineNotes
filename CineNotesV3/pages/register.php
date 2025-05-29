<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: /CINENOTES/pages/index.php');
    exit();
}

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $type_utilisateur = 'Utilisateur';

    if ($password !== $confirm_password) {
        $error = 'Les mots de passe ne correspondent pas.';
    } else {
        include('../config.php');
        $query = 'SELECT id FROM Utilisateurs WHERE email = :email';
        $stmt = $pdo->prepare($query);
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) {
            $error = 'L\'email est déjà utilisé.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = 'INSERT INTO Utilisateurs (nom, email, motdepasse, type_utilisateur) VALUES (:nom, :email, :motdepasse, :type_utilisateur)';
            $stmt = $pdo->prepare($query);
            $stmt->execute(['nom' => $nom, 'email' => $email, 'motdepasse' => $hashed_password, 'type_utilisateur' => $type_utilisateur]);
            $success = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
        }
    }
}

include('../includes/header.php');
?>

<h2>Inscription</h2>

<?php if ($error): ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p style="color: green;"><?php echo $success; ?></p>
<?php endif; ?>

<form method="POST" action="register.php">
    <label for="nom">Nom :</label>
    <input type="text" name="nom" id="nom" required><br><br>

    <label for="email">Email :</label>
    <input type="email" name="email" id="email" required><br><br>

    <label for="password">Mot de passe :</label>
    <input type="password" name="password" id="password" required><br><br>

    <label for="confirm_password">Confirmer le mot de passe :</label>
    <input type="password" name="confirm_password" id="confirm_password" required><br><br>

    <input type="hidden" name="type_utilisateur" value="Utilisateur"> <!-- Champ caché pour le type d'utilisateur -->

    <button type="submit">S'inscrire</button>
</form>

<p>Déjà un compte ? <a href="/CINENOTES/pages/login.php">Se connecter ici</a></p>

<?php include('../includes/footer.php'); ?>

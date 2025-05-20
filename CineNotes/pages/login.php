<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: /CINENOTES/pages/index.php');
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    include('../config.php');
    $query = 'SELECT id, email, motdepasse, type_utilisateur FROM Utilisateurs WHERE email = :email';
    $stmt = $pdo->prepare($query);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['motdepasse'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['type_utilisateur'] = $user['type_utilisateur']; // Stockez le type d'utilisateur dans la session
        header('Location: /CINENOTES/pages/index.php');
        exit();
    } else {
        $error = 'Identifiants incorrects. Veuillez réessayer.';
    }
}

include('../includes/header.php');
?>

<h2>Connexion</h2>
<?php if ($error): ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>

<form method="POST" action="login.php">
    <label for="email">Email :</label>
    <input type="email" name="email" id="email" required><br><br>

    <label for="password">Mot de passe :</label>
    <input type="password" name="password" id="password" required><br><br>

    <button type="submit">Se connecter</button>
</form>

<p>Pas encore de compte ? <a href="/CINENOTES/pages/register.php">Inscrivez-vous ici</a></p>

<?php include('../includes/footer.php'); ?>

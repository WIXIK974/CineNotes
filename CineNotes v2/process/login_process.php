<?php
session_start();
require 'config.php';

$email = $_POST['email'];
$email = $_POST['nom'];
$mdp = $_POST['motdepasse'];

$stmt = $pdo->prepare("SELECT * FROM Utilisateurs WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();


if ($user && password_verify($mdp, $user['motdepasse'])) {
    $_SESSION['id'] = $user['id'];
    $_SESSION['type'] = $user['type_utilisateur'];
    header("Location: ../pages/dashboard.php");
} else {
    echo "Identifiants invalides";
}

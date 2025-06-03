<?php
require 'config.php';
$nom = $_POST['nom'];
$email = $_POST['email'];
$mdp = password_hash($_POST['motdepasse'], PASSWORD_BCRYPT);

$stmt = $pdo->prepare("INSERT INTO Utilisateurs (nom, email, motdepasse) VALUES (?, ?, ?)");
$stmt->execute([$nom, $email, $mdp]);
header("Location: ../pages/login.php");

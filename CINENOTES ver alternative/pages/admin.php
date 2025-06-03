<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include('config.php');
$query = 'SELECT id, email FROM users';
$stmt = $pdo->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll();

include('includes/header.php');
?>

<h2>Panneau d'administration</h2>
<h3>Liste des utilisateurs</h3>
<table>
    <tr>
        <th>Email</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?php echo $user['email']; ?></td>
        <td>
            <a href="delete_user.php?id=<?php echo $user['id']; ?>">Supprimer</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php include('includes/footer.php'); ?>

<?php
require_once("config.php"); // ou adapter le chemin si c’est ailleurs

if (!isset($_GET['id'])) {
    http_response_code(400);
    exit("ID manquant");
}

$id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT image FROM Films WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $imageData = $row['image'];

    // Afficher l'image (détection rudimentaire du type)
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_buffer($finfo, $imageData);
    finfo_close($finfo);

    header("Content-Type: $mimeType");
    echo $imageData;
} else {
    http_response_code(404);
    echo "Image non trouvée.";
}

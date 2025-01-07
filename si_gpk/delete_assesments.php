<?php
include 'includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare('DELETE FROM assesments WHERE id = ?');
    $stmt->execute([$id]);

    header('Location: assesments.php');
    exit;
}
?>

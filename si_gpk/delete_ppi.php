<?php
include 'includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete PPI record from database
    $stmt = $pdo->prepare('DELETE FROM ppi WHERE id = ?');
    $stmt->execute([$id]);

    // Redirect back to PPI page after deletion
    header('Location: ppi.php');
    exit;
}
?>

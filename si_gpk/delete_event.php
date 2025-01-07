<?php
include 'includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

include 'includes/db.php';
$id = $_GET['id'];

$stmt = $pdo->prepare('DELETE FROM calendar WHERE id = ?');
$stmt->execute([$id]);

redirect('calendar.php');
?>

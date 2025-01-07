<?php
include 'includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

include 'includes/db.php';
$id = $_GET['id'];
$stmt = $pdo->prepare('DELETE FROM students WHERE id = ?');
$stmt->execute([$id]);
redirect('students.php');
?>

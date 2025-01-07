<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirect($url) {
    header("Location: $url");
    exit();
}
function getStudentNameById($student_id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT name FROM students WHERE id = ?');
    $stmt->execute([$student_id]);
    $student = $stmt->fetch();
    return $student ? $student['name'] : 'Unknown';
}
?>

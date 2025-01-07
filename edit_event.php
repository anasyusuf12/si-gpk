<?php
include 'includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

include 'includes/db.php';
$id = $_GET['id'];

$stmt = $pdo->prepare('SELECT * FROM calendar WHERE id = ?');
$stmt->execute([$id]);
$event = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_title = $_POST['event_title'];
    $event_date = $_POST['event_date'];
    $event_description = $_POST['event_description'];

    $stmt = $pdo->prepare('UPDATE calendar SET event_title = ?, event_date = ?, event_description = ? WHERE id = ?');
    $stmt->execute([$event_title, $event_date, $event_description, $id]);

    redirect('calendar.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event - SI-GPK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container mt-4">
        <h2>Edit Event</h2>
        <a href="calendar.php" class="btn btn-secondary">Back to Calendar</a>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="event_title" class="form-label">Event Title</label>
                <input type="text" class="form-control" name="event_title" value="<?= $event['event_title']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="event_date" class="form-label">Event Date</label>
                <input type="date" class="form-control" name="event_date" value="<?= $event['event_date']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="event_description" class="form-label">Event Description</label>
                <textarea class="form-control" name="event_description" required><?= $event['event_description']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Event</button>
        </form>
    </div>
</body>
</html>

<?php
include 'includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

include 'includes/db.php';
$id = $_GET['id'];
$stmt = $pdo->prepare('SELECT * FROM ppi WHERE id = ?');
$stmt->execute([$id]);
$ppi = $stmt->fetch();

// Fetch students for dropdown
$stmt = $pdo->prepare('SELECT * FROM students WHERE user_id = :user_id');
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$students = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $current_ability = $_POST['current_ability'];
    $long_term_goal = $_POST['long_term_goal'];
    $short_term_goal = $_POST['short_term_goal'];
    $special_services = $_POST['special_services'];
    $service_arrangement = $_POST['service_arrangement'];
    $execution_time = $_POST['execution_time'];
    $evaluation_criteria = $_POST['evaluation_criteria'];

    $stmt = $pdo->prepare('UPDATE ppi SET student_id = ?, current_ability = ?, long_term_goal = ?, short_term_goal = ?, special_services = ?, service_arrangement = ?, execution_time = ?, evaluation_criteria = ? WHERE id = ?');
    $stmt->execute([$student_id, $current_ability, $long_term_goal, $short_term_goal, $special_services, $service_arrangement, $execution_time, $evaluation_criteria, $id]);

    redirect('ppi.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit PPI - SI-GPK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container mt-4">
        <h2>Edit PPI</h2>
        <a href="ppi.php" class="btn btn-secondary">Back to PPI</a>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="student_id" class="form-label">Student</label>
                <select class="form-control" name="student_id" required>
                    <?php foreach ($students as $student): ?>
                        <option value="<?= $student['id']; ?>" <?= $student['id'] == $ppi['student_id'] ? 'selected' : ''; ?>><?= $student['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="current_ability" class="form-label">Current Ability</label>
                <textarea class="form-control" name="current_ability" required><?= $ppi['current_ability']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="long_term_goal" class="form-label">Long-term Goal</label>
                <textarea class="form-control" name="long_term_goal" required><?= $ppi['long_term_goal']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="short_term_goal" class="form-label">Short-term Goal</label>
                <textarea class="form-control" name="short_term_goal" required><?= $ppi['short_term_goal']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="special_services" class="form-label">Special Services</label>
                <textarea class="form-control" name="special_services" required><?= $ppi['special_services']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="service_arrangement" class="form-label">Service Arrangement</label>
                <textarea class="form-control" name="service_arrangement" required><?= $ppi['service_arrangement']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="execution_time" class="form-label">Execution Time</label>
                <input type="text" class="form-control" name="execution_time" value="<?= $ppi['execution_time']; ?>" required />
            </div>
            <div class="mb-3">
                <label for="evaluation_criteria" class="form-label">Evaluation Criteria</label>
                <textarea class="form-control" name="evaluation_criteria" required><?= $ppi['evaluation_criteria']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update PPI</button>
        </form>
    </div>
</body>
</html>

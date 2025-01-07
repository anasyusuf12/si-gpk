<?php
include 'includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

include 'includes/db.php';
$id = $_GET['id'];

$stmt = $pdo->prepare('SELECT * FROM assessments WHERE id = ?');
$stmt->execute([$id]);
$assessment = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $assessment_type = $_POST['assessment_type'];
    $assessment_date = $_POST['assessment_date'];
    $assessment_results = $_POST['assessment_results'];
    $analysis = $_POST['analysis'];
    $report = $_POST['report'];

    $stmt = $pdo->prepare('UPDATE assessments SET student_id = ?, assessment_type = ?, assessment_date = ?, assessment_results = ?, analysis = ?, report = ? WHERE id = ?');
    $stmt->execute([$student_id, $assessment_type, $assessment_date, $assessment_results, $analysis, $report, $id]);

    redirect('assesments.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Assessment - SI-GPK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container mt-4">
        <h2>Edit Assessment</h2>
        <a href="assesments.php" class="btn btn-secondary">Back to Assessments</a>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="student_id" class="form-label">Student</label>
                <select class="form-control" name="student_id" required>
                    <?php 
                    $stmt = $pdo->prepare('SELECT * FROM students WHERE user_id = :user_id');
                    $stmt->execute(['user_id' => $_SESSION['user_id']]);
                    $students = $stmt->fetchAll();
                    ?>
                    <?php foreach ($students as $student): ?>
                        <option value="<?= $student['id']; ?>" <?= $student['id'] == $assessment['student_id'] ? 'selected' : ''; ?>><?= $student['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="assessment_type" class="form-label">Assessment Type</label>
                <input type="text" class="form-control" name="assessment_type" value="<?= $assessment['assessment_type']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="assessment_date" class="form-label">Assessment Date</label>
                <input type="date" class="form-control" name="assessment_date" value="<?= $assessment['assessment_date']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="assessment_results" class="form-label">Assessment Results</label>
                <textarea class="form-control" name="assessment_results" required><?= $assessment['assessment_results']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="analysis" class="form-label">Analysis</label>
                <textarea class="form-control" name="analysis" required><?= $assessment['analysis']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="report" class="form-label">Report</label>
                <textarea class="form-control" name="report" required><?= $assessment['report']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-success">Update Assessment</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

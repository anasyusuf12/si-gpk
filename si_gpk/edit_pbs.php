<?php
include 'includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

include 'includes/db.php';
$id = $_GET['id'];

$stmt = $pdo->prepare('SELECT * FROM pbs WHERE id = ?');
$stmt->execute([$id]);
$pbs = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $disability_identification = $_POST['disability_identification'];
    $special_assistive_tools = $_POST['special_assistive_tools'];
    $movement_in_school = $_POST['movement_in_school'];
    $strength_or_ability = $_POST['strength_or_ability'];
    $health_information = $_POST['health_information'];
    $support_needed = $_POST['support_needed'];
    $other_information = $_POST['other_information'];
    $temporary_conclusion = $_POST['temporary_conclusion'];

    $stmt = $pdo->prepare('UPDATE pbs SET student_id = ?, disability_identification = ?, special_assistive_tools = ?, movement_in_school = ?, strength_or_ability = ?, health_information = ?, support_needed = ?, other_information = ?, temporary_conclusion = ? WHERE id = ?');
    $stmt->execute([$student_id, $disability_identification, $special_assistive_tools, $movement_in_school, $strength_or_ability, $health_information, $support_needed, $other_information, $temporary_conclusion, $id]);

    redirect('pbs.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit PBS - SI-GPK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container mt-4">
        <h2>Edit PBS</h2>
        <a href="pbs.php" class="btn btn-secondary">Back to PBS</a>

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
                        <option value="<?= $student['id']; ?>" <?= $student['id'] == $pbs['student_id'] ? 'selected' : ''; ?>><?= $student['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="disability_identification" class="form-label">Disability Identification</label>
                <textarea class="form-control" name="disability_identification" required><?= $pbs['disability_identification']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="special_assistive_tools" class="form-label">Assistive Tools</label>
                <textarea class="form-control" name="special_assistive_tools" required><?= $pbs['special_assistive_tools']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="movement_in_school" class="form-label">Movement in School</label>
                <textarea class="form-control" name="movement_in_school" required><?= $pbs['movement_in_school']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="strength_or_ability" class="form-label">Strength or Ability</label>
                <textarea class="form-control" name="strength_or_ability" required><?= $pbs['strength_or_ability']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="health_information" class="form-label">Health Information</label>
                <textarea class="form-control" name="health_information" required><?= $pbs['health_information']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="support_needed" class="form-label">Support Needed</label>
                <textarea class="form-control" name="support_needed" required><?= $pbs['support_needed']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="other_information" class="form-label">Other Information</label>
                <textarea class="form-control" name="other_information" required><?= $pbs['other_information']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="temporary_conclusion" class="form-label">Temporary Conclusion</label>
                <textarea class="form-control" name="temporary_conclusion" required><?= $pbs['temporary_conclusion']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update PBS</button>
        </form>
    </div>
</body>
</html>

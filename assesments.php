<?php
include 'includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

include 'includes/db.php';

// Fetch Students
$stmt = $pdo->prepare('SELECT * FROM students WHERE user_id = :user_id');
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$students = $stmt->fetchAll();

// Add assessment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_assessment'])) {
    $student_id = $_POST['student_id'];
    $assessment_date = $_POST['assessment_date'];
    $assessment_type = $_POST['assessment_type'];
    $result = $_POST['result'];
    $evaluator = $_POST['evaluator'];

    $stmt = $pdo->prepare('INSERT INTO assessments (student_id, assessment_date, assessment_type, result, evaluator) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$student_id, $assessment_date, $assessment_type, $result, $evaluator]);

    redirect('assesments.php');
}

// Edit assessment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_assessment'])) {
    $id = $_POST['id'];
    $student_id = $_POST['student_id'];
    $assessment_date = $_POST['assessment_date'];
    $assessment_type = $_POST['assessment_type'];
    $result = $_POST['result'];
    $evaluator = $_POST['evaluator'];

    $stmt = $pdo->prepare('UPDATE assessments SET student_id = ?, assessment_date = ?, assessment_type = ?, result = ?, evaluator = ? WHERE id = ?');
    $stmt->execute([$student_id, $assessment_date, $assessment_type, $result, $evaluator, $id]);

    redirect('assesments.php');
}

// Delete assessment
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $pdo->prepare('DELETE FROM assessments WHERE id = ?');
    $stmt->execute([$id]);
    redirect('assesments.php');
}

// Modify the query to join students table and filter by user_id
$stmt = $pdo->prepare('
    SELECT a.*, s.name 
    FROM assessments a
    JOIN students s ON a.student_id = s.id
    WHERE s.user_id = :user_id
');
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$assessments = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asesmen - SI-GPK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fc;
            font-family: 'Poppins', sans-serif;
        }
        .sidebar {
            background-color: #0062cc;
            color: white;
            height: 100vh;
            padding-top: 20px;
            transition: width 0.3s;
        }
        .sidebar h4 {
            font-size: 20px;
            padding: 0 20px;
            color: #ffffff;
            font-weight: 600;
        }
        .nav-link {
            color: white !important;
            font-size: 16px;
            padding: 10px 20px;
            margin: 5px 0;
            border-radius: 5px;
        }
        .nav-link:hover {
            background-color: #004bb5;
        }
        .container {
            margin-left: 270px;
            padding: 30px;
        }
        .header {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .header h2 {
            font-size: 28px;
            font-weight: 600;
            color: #0062cc;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .modal-content {
            border-radius: 0.5rem;
        }
        .modal-header, .modal-footer {
            border: none;
        }
        .btn-close {
            background-color: #f8f9fa;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }
        .table-striped tbody tr:nth-of-type(even) {
            background-color: #f1f1f1;
        }
        .btn {
            border-radius: 0.25rem;
        }
        .modal-body input, .modal-body select {
            border-radius: 0.25rem;
        }
    </style></head>
<body>
    <div class="d-flex">
        <div class="sidebar text-white p-3" style="width: 250px; height: 100vh;">
            <h4>Welcome, <?= $_SESSION['username']; ?></h4>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link text-white" href="profile.php">Profile Guru</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="students.php">Data Siswa</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="calendar.php">Kalender Akademik</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="ppi.php">PBS</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="pbs.php">PPI</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="assesments.php">Asesmen</a></li>
            </ul>
        </div>

        <div class="container mt-4" style="flex-grow: 1;">
            <h2>Asesmen</h2>
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addassessmentModal">Add New assessment</button>

            <table class="table mt-3" id="assessmentTable">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Assessment Date</th>
                        <th>Assessment Type</th>
                        <th>Result</th>
                        <th>Evaluator</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($assessments as $assessment): ?>
                        <tr>
                            <td><?= $assessment['name']; ?></td>
                            <td><?= date('d-m-Y', strtotime($assessment['assessment_date'])); ?></td>
                            <td><?= $assessment['assessment_type']; ?></td>
                            <td><?= $assessment['result']; ?></td>
                            <td><?= $assessment['evaluator']; ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm editassessmentBtn" data-bs-toggle="modal" data-bs-target="#editassessmentModal"
                                    data-id="<?= $assessment['id']; ?>"
                                    data-student_id="<?= $assessment['student_id']; ?>"
                                    data-assessment_date="<?= $assessment['assessment_date']; ?>"
                                    data-assessment_type="<?= $assessment['assessment_type']; ?>"
                                    data-result="<?= $assessment['result']; ?>"
                                    data-evaluator="<?= $assessment['evaluator']; ?>"
                                >Edit</button>

                                <a href="?delete_id=<?= $assessment['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this assessment?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Add assessment -->
    <div class="modal fade" id="addassessmentModal" tabindex="-1" aria-labelledby="addassessmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="assessments.php" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addassessmentModalLabel">Add New assessment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="student_id" class="form-label">Student</label>
                            <select class="form-control" name="student_id" required>
                                <?php foreach ($students as $student): ?>
                                    <option value="<?= $student['id']; ?>"><?= $student['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="assessment_date" class="form-label">Assessment Date</label>
                            <input type="date" class="form-control" name="assessment_date" required />
                        </div>
                        <div class="mb-3">
                            <label for="assessment_type" class="form-label">Assessment Type</label>
                            <input type="text" class="form-control" name="assessment_type" required />
                        </div>
                        <div class="mb-3">
                            <label for="result" class="form-label">Result</label>
                            <textarea class="form-control" name="result" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="evaluator" class="form-label">Evaluator</label>
                            <input type="text" class="form-control" name="evaluator" required />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="add_assessment" class="btn btn-success">Add assessment</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit assessment -->
    <div class="modal fade" id="editassessmentModal" tabindex="-1" aria-labelledby="editassessmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="assessments.php" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editassessmentModalLabel">Edit assessment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="assessment_id" />
                        <div class="mb-3">
                            <label for="student_id" class="form-label">Student</label>
                            <select class="form-control" name="student_id" id="edit_student_id" required>
                                <?php foreach ($students as $student): ?>
                                    <option value="<?= $student['id']; ?>"><?= $student['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="assessment_date" class="form-label">Assessment Date</label>
                            <input type="date" class="form-control" name="assessment_date" id="edit_assessment_date" required />
                        </div>
                        <div class="mb-3">
                            <label for="assessment_type" class="form-label">Assessment Type</label>
                            <input type="text" class="form-control" name="assessment_type" id="edit_assessment_type" required />
                        </div>
                        <div class="mb-3">
                            <label for="result" class="form-label">Result</label>
                            <textarea class="form-control" name="result" id="edit_result" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="evaluator" class="form-label">Evaluator</label>
                            <input type="text" class="form-control" name="evaluator" id="edit_evaluator" required />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="edit_assessment" class="btn btn-success">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Populate Edit Modal with assessment Data
        $('.editassessmentBtn').click(function() {
            $('#assessment_id').val($(this).data('id'));
            $('#edit_student_id').val($(this).data('student_id'));
            $('#edit_assessment_date').val($(this).data('assessment_date'));
            $('#edit_assessment_type').val($(this).data('assessment_type'));
            $('#edit_result').val($(this).data('result'));
            $('#edit_evaluator').val($(this).data('evaluator'));
        });
    </script>
</body>
</html>

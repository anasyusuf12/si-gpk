<?php
include 'includes/functions.php';
include 'includes/db.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

// Fetch Students for the dropdown
$stmt = $pdo->prepare('SELECT id, name FROM students WHERE user_id = :user_id');
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$students = $stmt->fetchAll();

// Add PBS
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_pbs'])) {
    $student_id = $_POST['student_id'];
    $program_name = $_POST['program_name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $objectives = $_POST['objectives'];
    $resources = $_POST['resources'];
    $implementation = $_POST['implementation'];
    $evaluation = $_POST['evaluation'];

    $stmt = $pdo->prepare('INSERT INTO pbs (student_id, program_name, start_date, end_date, objectives, resources, implementation, evaluation) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$student_id, $program_name, $start_date, $end_date, $objectives, $resources, $implementation, $evaluation]);

    redirect('pbs.php');
}

// Fetch PBS data
$stmt = $pdo->prepare('SELECT pbs.*, students.name as student_name FROM pbs JOIN students ON pbs.student_id = students.id WHERE students.user_id = :user_id');
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$pbs_data = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PBS - SI-GPK</title>
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
    </style>
</head>
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
            <h2>PPI - Program Pembelajaran Individual</h2>
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPbsModal">Add New PPI</button>

            <table class="table table-striped" id="pbsTable">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Program Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Objectives</th>
                        <th>Resources</th>
                        <th>Implementation</th>
                        <th>Evaluation</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pbs_data as $pbs): ?>
                        <tr>
                            <td><?= $pbs['student_name']; ?></td>
                            <td><?= $pbs['program_name']; ?></td>
                            <td><?= $pbs['start_date']; ?></td>
                            <td><?= $pbs['end_date']; ?></td>
                            <td><?= $pbs['objectives']; ?></td>
                            <td><?= $pbs['resources']; ?></td>
                            <td><?= $pbs['implementation']; ?></td>
                            <td><?= $pbs['evaluation']; ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editPbsModal" data-id="<?= $pbs['id']; ?>" data-student_id="<?= $pbs['student_id']; ?>" data-program_name="<?= $pbs['program_name']; ?>" data-start_date="<?= $pbs['start_date']; ?>" data-end_date="<?= $pbs['end_date']; ?>" data-objectives="<?= $pbs['objectives']; ?>" data-resources="<?= $pbs['resources']; ?>" data-implementation="<?= $pbs['implementation']; ?>" data-evaluation="<?= $pbs['evaluation']; ?>">Edit</button>
                                <a href="delete_pbs.php?id=<?= $pbs['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Add PBS -->
    <div class="modal fade" id="addPbsModal" tabindex="-1" aria-labelledby="addPbsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="pbs.php" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPbsModalLabel">Add New PPI</h5>
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
                            <label for="program_name" class="form-label">Program Name</label>
                            <input type="text" class="form-control" name="program_name" required />
                        </div>
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date" required />
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" name="end_date" required />
                        </div>
                        <div class="mb-3">
                            <label for="objectives" class="form-label">Objectives</label>
                            <textarea class="form-control" name="objectives" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="resources" class="form-label">Resources</label>
                            <textarea class="form-control" name="resources" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="implementation" class="form-label">Implementation</label>
                            <textarea class="form-control" name="implementation" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="evaluation" class="form-label">Evaluation</label>
                            <textarea class="form-control" name="evaluation" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="add_pbs" class="btn btn-success">Add PBS</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit PBS -->
    <div class="modal fade" id="editPbsModal" tabindex="-1" aria-labelledby="editPbsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="edit_pbs.php" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPbsModalLabel">Edit PPI</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editPbsId">
                        <div class="mb-3">
                            <label for="editStudentId" class="form-label">Student</label>
                            <select class="form-control" name="student_id" id="editStudentId" required></select>
                        </div>
                        <div class="mb-3">
                            <label for="editProgramName" class="form-label">Program Name</label>
                            <input type="text" class="form-control" name="program_name" id="editProgramName" required />
                        </div>
                        <div class="mb-3">
                            <label for="editStartDate" class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date" id="editStartDate" required />
                        </div>
                        <div class="mb-3">
                            <label for="editEndDate" class="form-label">End Date</label>
                            <input type="date" class="form-control" name="end_date" id="editEndDate" required />
                        </div>
                        <div class="mb-3">
                            <label for="editObjectives" class="form-label">Objectives</label>
                            <textarea class="form-control" name="objectives" id="editObjectives" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editResources" class="form-label">Resources</label>
                            <textarea class="form-control" name="resources" id="editResources" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editImplementation" class="form-label">Implementation</label>
                            <textarea class="form-control" name="implementation" id="editImplementation" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editEvaluation" class="form-label">Evaluation</label>
                            <textarea class="form-control" name="evaluation" id="editEvaluation" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="edit_pbs" class="btn btn-success">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#pbsTable').DataTable();

            // Edit PBS Modal
            $('#editPbsModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var student_id = button.data('student_id');
                var program_name = button.data('program_name');
                var start_date = button.data('start_date');
                var end_date = button.data('end_date');
                var objectives = button.data('objectives');
                var resources = button.data('resources');
                var implementation = button.data('implementation');
                var evaluation = button.data('evaluation');

                var modal = $(this);
                modal.find('#editPbsId').val(id);
                modal.find('#editStudentId').val(student_id);
                modal.find('#editProgramName').val(program_name);
                modal.find('#editStartDate').val(start_date);
                modal.find('#editEndDate').val(end_date);
                modal.find('#editObjectives').val(objectives);
                modal.find('#editResources').val(resources);
                modal.find('#editImplementation').val(implementation);
                modal.find('#editEvaluation').val(evaluation);
            });
        });
    </script>
</body>
</html>

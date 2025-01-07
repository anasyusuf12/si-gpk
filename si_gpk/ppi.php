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

// Add PPI
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_ppi'])) {
    $student_id = $_POST['student_id'];
    $current_ability = $_POST['current_ability'];
    $long_term_goals = $_POST['long_term_goals'];
    $short_term_goals = $_POST['short_term_goals'];
    $special_services = $_POST['special_services'];
    $service_provision = $_POST['service_provision'];
    $implementation_time = $_POST['implementation_time'];
    $evaluation_criteria = $_POST['evaluation_criteria'];

    $stmt = $pdo->prepare('INSERT INTO ppi (student_id, current_ability, long_term_goals, short_term_goals, special_services, service_provision, implementation_time, evaluation_criteria) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$student_id, $current_ability, $long_term_goals, $short_term_goals, $special_services, $service_provision, $implementation_time, $evaluation_criteria]);

    redirect('ppi.php');
}

// Fetch PPI data
$stmt = $pdo->prepare('SELECT ppi.*, students.name as student_name FROM ppi JOIN students ON ppi.student_id = students.id WHERE students.user_id = :user_id');
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$ppi_data = $stmt->fetchAll();
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
            background-color: ##0d6efd;
            font-family: 'Poppins', sans-serif;
        }
        .sidebar {
            background-color: #0d6efd;
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
            <h2>PBS - Profil Belajar Siswa</h2>
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPpiModal">Add New PPI</button>

            <table class="table table-striped" id="ppiTable">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Current Ability</th>
                        <th>Long Term Goals</th>
                        <th>Short Term Goals</th>
                        <th>Special Services</th>
                        <th>Service Provision</th>
                        <th>Implementation Time</th>
                        <th>Evaluation Criteria</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ppi_data as $ppi): ?>
                        <tr>
                            <td><?= $ppi['student_name']; ?></td>
                            <td><?= $ppi['current_ability']; ?></td>
                            <td><?= $ppi['long_term_goals']; ?></td>
                            <td><?= $ppi['short_term_goals']; ?></td>
                            <td><?= $ppi['special_services']; ?></td>
                            <td><?= $ppi['service_provision']; ?></td>
                            <td><?= $ppi['implementation_time']; ?></td>
                            <td><?= $ppi['evaluation_criteria']; ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editPpiModal" data-id="<?= $ppi['id']; ?>" data-student_id="<?= $ppi['student_id']; ?>" data-current_ability="<?= $ppi['current_ability']; ?>" data-long_term_goals="<?= $ppi['long_term_goals']; ?>" data-short_term_goals="<?= $ppi['short_term_goals']; ?>" data-special_services="<?= $ppi['special_services']; ?>" data-service_provision="<?= $ppi['service_provision']; ?>" data-implementation_time="<?= $ppi['implementation_time']; ?>" data-evaluation_criteria="<?= $ppi['evaluation_criteria']; ?>">Edit</button>
                                <a href="delete_ppi.php?id=<?= $ppi['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirmDelete()">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Add PPI -->
    <div class="modal fade" id="addPpiModal" tabindex="-1" aria-labelledby="addPpiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="ppi.php" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPpiModalLabel">Add New PBS</h5>
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
                            <label for="current_ability" class="form-label">Current Ability</label>
                            <textarea class="form-control" name="current_ability" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="long_term_goals" class="form-label">Long Term Goals</label>
                            <textarea class="form-control" name="long_term_goals" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="short_term_goals" class="form-label">Short Term Goals</label>
                            <textarea class="form-control" name="short_term_goals" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="special_services" class="form-label">Special Services</label>
                            <textarea class="form-control" name="special_services" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="service_provision" class="form-label">Service Provision</label>
                            <textarea class="form-control" name="service_provision" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="implementation_time" class="form-label">Implementation Time</label>
                            <input type="text" class="form-control" name="implementation_time" required />
                        </div>
                        <div class="mb-3">
                            <label for="evaluation_criteria" class="form-label">Evaluation Criteria</label>
                            <textarea class="form-control" name="evaluation_criteria" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="add_ppi" class="btn btn-success">Add PBS</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit PPI -->
<div class="modal fade" id="editPpiModal" tabindex="-1" aria-labelledby="editPpiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="ppi.php" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPpiModalLabel">Edit PBS</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="editPpiId">

                    <div class="mb-3">
                        <label for="editStudentId" class="form-label">Student</label>
                        <select class="form-control" name="student_id" id="editStudentId" required>
                            <?php foreach ($students as $student): ?>
                                <option value="<?= $student['id']; ?>"><?= $student['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                 

                    <div class="mb-3">
                        <label for="editCurrentAbility" class="form-label">Current Ability</label>
                        <input type="text" class="form-control" name="current_ability" id="editCurrentAbility" required />
                    </div>

                    <!-- New Fields Added -->
                    <div class="mb-3">
                        <label for="editLongTermGoals" class="form-label">Long Term Goals</label>
                        <textarea class="form-control" name="long_term_goals" id="editLongTermGoals" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="editShortTermGoals" class="form-label">Short Term Goals</label>
                        <textarea class="form-control" name="short_term_goals" id="editShortTermGoals" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="editSpecialServices" class="form-label">Special Services</label>
                        <textarea class="form-control" name="special_services" id="editSpecialServices" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="editServiceProvision" class="form-label">Service Provision</label>
                        <textarea class="form-control" name="service_provision" id="editServiceProvision" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="editImplementationTime" class="form-label">Implementation Time</label>
                        <input type="text" class="form-control" name="implementation_time" id="editImplementationTime" required />
                    </div>

                    <div class="mb-3">
                        <label for="editEvaluationCriteria" class="form-label">Evaluation Criteria</label>
                        <textarea class="form-control" name="evaluation_criteria" id="editEvaluationCriteria" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="edit_ppi" class="btn btn-warning">Update PPI</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script>

// Function to confirm delete action
function confirmDelete() {
    return confirm("Are you sure you want to delete this PPI record?");
}


        $(document).ready(function() {
            $('#ppiTable').DataTable();

            // Populate Edit Modal with data
const editButtons = document.querySelectorAll('.editPpiBtn');
editButtons.forEach(button => {
    button.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const student_id = this.getAttribute('data-student_id');
        
        const current_ability = this.getAttribute('data-current_ability');
        
        // New data
        const long_term_goals = this.getAttribute('data-long_term_goals');
        const short_term_goals = this.getAttribute('data-short_term_goals');
        const special_services = this.getAttribute('data-special_services');
        const service_provision = this.getAttribute('data-service_provision');
        const implementation_time = this.getAttribute('data-implementation_time');
        const evaluation_criteria = this.getAttribute('data-evaluation_criteria');

        document.getElementById('editPpiId').value = id;
        document.getElementById('editStudentId').value = student_id;
        document.getElementById('editProgramName').value = program_name;
       
        document.getElementById('editCurrentAbility').value = current_ability;

        // New data
        document.getElementById('editLongTermGoals').value = long_term_goals;
        document.getElementById('editShortTermGoals').value = short_term_goals;
        document.getElementById('editSpecialServices').value = special_services;
        document.getElementById('editServiceProvision').value = service_provision;
        document.getElementById('editImplementationTime').value = implementation_time;
        document.getElementById('editEvaluationCriteria').value = evaluation_criteria;
    });
});

    </script>
</body>
</html>

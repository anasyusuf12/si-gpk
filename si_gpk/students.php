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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_student'])) {
    $photo = $_FILES['photo']['name'];
    $name = $_POST['name'];
    $birth_of_date = $_POST['birth_of_date'];
    $gender = $_POST['gender'];
    $disability_type = $_POST['disability_type'];
    $class = $_POST['class'];

    // Move uploaded photo file
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);

    // Insert student data
    $stmt = $pdo->prepare('INSERT INTO students (user_id, photo, name, birth_of_date, gender, disability_type, class) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$_SESSION['user_id'], $photo, $name, $birth_of_date, $gender, $disability_type, $class]);

    // Redirect after successful insert
    redirect('students.php');
}

// Update Student
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_student'])) {
    $id = $_POST['id'];
    $photo = $_FILES['photo']['name'] ? $_FILES['photo']['name'] : $_POST['old_photo'];
    $name = $_POST['name'];
    $birth_of_date = $_POST['birth_of_date'];
    $gender = $_POST['gender'];
    $disability_type = $_POST['disability_type'];
    $class = $_POST['class'];

    if ($_FILES['photo']['name']) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
        move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);
    }

    $stmt = $pdo->prepare('UPDATE students SET photo = ?, name = ?, birth_of_date = ?, gender = ?, disability_type = ?, class = ? WHERE id = ?');
    $stmt->execute([$photo, $name, $birth_of_date, $gender, $disability_type, $class, $id]);
    redirect('students.php');
}

// Delete Student
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $pdo->prepare('DELETE FROM students WHERE id = ?');
    $stmt->execute([$id]);
    redirect('students.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa - SI-GPK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fc;
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
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <h4 class="text-center">Welcome, <?= $_SESSION['username']; ?></h4>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="profile.php">Profile Guru</a></li>
                <li class="nav-item"><a class="nav-link" href="students.php">Data Siswa</a></li>
                <li class="nav-item"><a class="nav-link" href="calendar.php">Kalender Akademik</a></li>
                <li class="nav-item"><a class="nav-link" href="ppi.php">PBS</a></li>
                <li class="nav-item"><a class="nav-link" href="pbs.php">PPI</a></li>
                <li class="nav-item"><a class="nav-link" href="assesments.php">Asesmen</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="container">
            <div class="header">
                <h2>Data Siswa</h2>
            </div>

            <div class="d-flex justify-content-between mb-4">
                <a href="dashboard.php" class="btn btn-outline-secondary">Back to Dashboard</a>
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">Add New Student</button>
            </div>

            <!-- Students List -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Tanggal Lahir</th>
                        <th>Jenis Kelamin</th>
                        <th>Disabilitas</th>
                        <th>Kelas</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $index => $student): ?>
                    <tr>
                        <td><?= $index + 1; ?></td>
                        <td><?= $student['name']; ?></td>
                        <td><?= $student['birth_of_date']; ?></td>
                        <td><?= $student['gender']; ?></td>
                        <td><?= $student['disability_type']; ?></td>
                        <td><?= $student['class']; ?></td>
                        <td><img src="uploads/<?= $student['photo']; ?>" alt="Photo" width="50"></td>
                        <td>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editStudentModal" data-id="<?= $student['id']; ?>" data-name="<?= $student['name']; ?>" data-birth="<?= $student['birth_of_date']; ?>" data-gender="<?= $student['gender']; ?>" data-disability="<?= $student['disability_type']; ?>" data-class="<?= $student['class']; ?>" data-photo="<?= $student['photo']; ?>">Edit</button>
                            <a href="?delete_id=<?= $student['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <a href="logout.php" class="btn btn-danger mt-4">Logout</a>
        </div>
    </div>

    <!-- Add Student Modal -->
    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="students.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addStudentModalLabel">Add New Student</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="birth_of_date" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="birth_of_date" name="birth_of_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="disability_type" class="form-label">Disability Type</label>
                            <input type="text" class="form-control" id="disability_type" name="disability_type" required>
                        </div>
                        <div class="mb-3">
                            <label for="class" class="form-label">Class</label>
                            <input type="text" class="form-control" id="class" name="class" required>
                        </div>
                        <div class="mb-3">
                            <label for="photo" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="photo" name="photo" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="add_student">Add Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Student Modal -->
    <div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="students.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editStudentModalLabel">Edit Student</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        <input type="hidden" id="old_photo" name="old_photo">

                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_birth_of_date" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="edit_birth_of_date" name="birth_of_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_gender" class="form-label">Gender</label>
                            <select class="form-select" id="edit_gender" name="gender" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_disability_type" class="form-label">Disability Type</label>
                            <input type="text" class="form-control" id="edit_disability_type" name="disability_type" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_class" class="form-label">Class</label>
                            <input type="text" class="form-control" id="edit_class" name="class" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_photo" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="edit_photo" name="photo">
                            <img id="current_photo" src="" alt="Photo" class="img-fluid mt-2" width="50">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="edit_student">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const editModal = document.getElementById('editStudentModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const birth = button.getAttribute('data-birth');
            const gender = button.getAttribute('data-gender');
            const disability = button.getAttribute('data-disability');
            const className = button.getAttribute('data-class');
            const photo = button.getAttribute('data-photo');

            const modal = this;
            modal.querySelector('#edit_id').value = id;
            modal.querySelector('#edit_name').value = name;
            modal.querySelector('#edit_birth_of_date').value = birth;
            modal.querySelector('#edit_gender').value = gender;
            modal.querySelector('#edit_disability_type').value = disability;
            modal.querySelector('#edit_class').value = className;
            modal.querySelector('#current_photo').src = 'uploads/' + photo;
            modal.querySelector('#old_photo').value = photo;
        });
    </script>
</body>
</html>

<?php
include 'includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

include 'includes/db.php';
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $school = $_POST['school'];
    $email = $_POST['email'];

    $stmt = $pdo->prepare('UPDATE users SET name = ?, school = ?, email = ? WHERE id = ?');
    $stmt->execute([$name, $school, $email, $_SESSION['user_id']]);
    redirect('profile.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Guru - SI-GPK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
            font-family: 'Poppins', sans-serif;
        }

        .sidebar {
            background-color: #2575fc;
            color: white;
            height: 100vh;
            padding-top: 20px;
            transition: width 0.3s;
        }

        .sidebar h4 {
            font-size: 20px;
            padding: 0 20px;
        }

        .nav-link {
            color: white !important;
            font-size: 16px;
            padding: 10px 20px;
            margin: 5px 0;
            border-radius: 5px;
        }

        .nav-link:hover {
            background-color: #1d63e0;
        }

        .container {
            margin-left: 270px;
            padding: 30px;
        }

        .btn-danger {
            background-color: #f44336;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn-danger:hover {
            background-color: #d32f2f;
        }

        .card-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-top: 40px;
        }

        .profile-card {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        .profile-card h2 {
            font-size: 28px;
            font-weight: 600;
            color: #2575fc;
            margin-bottom: 20px;
        }

        .profile-card input {
            border-radius: 5px;
            padding: 10px;
            width: 100%;
            margin-bottom: 15px;
        }

        .btn-primary {
            background-color: #2575fc;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #1d63e0;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .form-group label {
            font-weight: 600;
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
            <div class="profile-card">
                <h2>Edit Profile Guru</h2>
                <form action="profile.php" method="POST">
                    <div class="form-group mb-3">
                        <label for="name">Nama Guru</label>
                        <input type="text" class="form-control" name="name" value="<?= $user['name']; ?>" required />
                    </div>
                    <div class="form-group mb-3">
                        <label for="school">Sekolah</label>
                        <input type="text" class="form-control" name="school" value="<?= $user['school']; ?>" required />
                    </div>
                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" value="<?= $user['email']; ?>" required />
                    </div>
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                    <a href="dashboard.php" class="btn btn-secondary ml-3">Back to Dashboard</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
include 'includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

include 'includes/db.php';
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SI-GPK</title>
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
        body {
            background-color: #f4f7fc;
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

        .header {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .header h2 {
            font-size: 28px;
            font-weight: 600;
            color: #2575fc;
        }

        .card-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-top: 40px;
        }

        .card {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.05);
            flex: 1;
            text-align: center;
        }

        .card h4 {
            font-size: 24px;
            color: #2575fc;
        }

        .card p {
            font-size: 16px;
            color: #666;
        }

        .card .btn {
            margin-top: 10px;
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
                <h2>Selamat datang, <?= $user['name']; ?>!</h2>
            </div>

            <!-- Dashboard Cards -->
            <div class="card-container">
                <div class="card">
                    <h4>Data Siswa</h4>
                    <p>Manage and view student data</p>
                    <a href="students.php" class="btn btn-primary">Go to Students</a>
                </div>
                <div class="card">
                    <h4>Kalender Akademik</h4>
                    <p>Check academic schedule</p>
                    <a href="calendar.php" class="btn btn-primary">View Calendar</a>
                </div>
                <div class="card">
                    <h4>Asesmen</h4>
                    <p>View and manage assessments</p>
                    <a href="assesments.php" class="btn btn-primary">Manage Assessments</a>
                </div>
            </div>

            <a href="logout.php" class="btn btn-danger mt-4">Logout</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

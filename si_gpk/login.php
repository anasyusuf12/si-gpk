<?php
include 'includes/db.php';
include 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        redirect('dashboard.php');
    } else {
        $error = 'Invalid username or password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SI-GPK - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            font-family: 'Poppins', sans-serif;
        }

        .login-container {
            max-width: 400px;
            margin: 80px auto;
            padding: 40px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .login-container h2 {
            text-align: center;
            font-weight: 600;
            margin-bottom: 30px;
            color: #2575fc;
        }

        .form-control {
            border-radius: 5px;
            padding: 15px;
            font-size: 16px;
        }

        .btn-primary {
            background-color: #2575fc;
            border: none;
            padding: 12px;
            font-size: 16px;
            width: 100%;
            border-radius: 5px;
        }

        .btn-primary:hover {
            background-color: #1d63e0;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-radius: 5px;
            padding: 10px;
        }

        .btn-link {
            text-decoration: none;
            color: #2575fc;
        }

        .btn-link:hover {
            text-decoration: underline;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>SI-GPK</h2>
        <form action="login.php" method="POST">
            <?php if (isset($error)) { echo '<div class="alert alert-danger">' . $error . '</div>'; } ?>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" required />
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required />
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <a href="register.php" class="btn btn-link">Daftar</a>
        </form>
    </div>
    
    <div class="footer">
        <p>&copy; 2025 SI-GPK. All Rights Reserved.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

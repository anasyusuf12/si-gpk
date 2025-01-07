<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SI-GPK - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f4f7fc;
            font-family: 'Poppins', sans-serif;
        }
        .register-container {
            max-width: 450px;
            margin: 50px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .register-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .register-header h2 {
            font-size: 28px;
            color: #0062cc;
            font-weight: bold;
        }
        .form-label {
            font-weight: 500;
            color: #333333;
        }
        .form-control {
            border-radius: 5px;
        }
        .btn-register {
            background-color: #0062cc;
            color: #ffffff;
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn-register:hover {
            background-color: #004bb5;
        }
        .text-center a {
            text-decoration: none;
            color: #0062cc;
        }
        .text-center a:hover {
            text-decoration: underline;
            color: #004bb5;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-container">
            <div class="register-header">
                <h2>Daftar User Baru</h2>
                <p>Silakan isi formulir di bawah ini untuk membuat akun baru.</p>
            </div>
            <form action="register.php" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Guru</label>
                    <input type="text" class="form-control" name="name" placeholder="Masukkan nama lengkap" required />
                </div>
                <div class="mb-3">
                    <label for="school" class="form-label">Sekolah</label>
                    <input type="text" class="form-control" name="school" placeholder="Masukkan nama sekolah" required />
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" placeholder="Masukkan email aktif" required />
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" placeholder="Masukkan username" required />
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Masukkan password" required />
                </div>
                <button type="submit" class="btn btn-register w-100">Daftar</button>
            </form>
            <p class="text-center mt-3">
                Sudah punya akun? <a href="login.php">Masuk di sini</a>.
            </p>
        </div>
    </div>
</body>
</html>

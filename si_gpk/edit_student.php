<?php
// Menghubungkan dengan file untuk koneksi database dan fungsi tambahan
include 'includes/functions.php';
include 'includes/db.php';

// Memastikan pengguna sudah login
if (!isLoggedIn()) {
    redirect('login.php');
}

// Mengambil ID siswa dari parameter URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Menyiapkan query untuk mengambil data siswa berdasarkan ID
    $stmt = $pdo->prepare('SELECT * FROM students WHERE id = ?');
    $stmt->execute([$id]);
    $student = $stmt->fetch();

    // Jika data siswa tidak ditemukan
    if (!$student) {
        die('Siswa tidak ditemukan!');
    }
} else {
    die('ID siswa tidak diberikan!');
}

// Proses ketika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_student'])) {
    $name = $_POST['name'];
    $birth_of_date = $_POST['birth_of_date'];
    $gender = $_POST['gender'];
    $disability_type = $_POST['disability_type'];
    $class = $_POST['class'];

    // Menyiapkan query untuk memperbarui data siswa
    $stmt = $pdo->prepare('UPDATE students SET name = ?, birth_of_date = ?, gender = ?, disability_type = ?, class = ? WHERE id = ?');
    $stmt->execute([$name, $birth_of_date, $gender, $disability_type, $class, $id]);

    // Redirect ke halaman daftar siswa setelah berhasil mengupdate
    redirect('students.php');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJv7JU2v4pR4kz7+NS6wM1l+G8g5+z5e9doU5Rxm0g0gNdVt9n5g5U7OQj5E" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Data Siswa</h2>
        <!-- Tombol untuk membuka modal edit -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
            Edit Data Siswa
        </button>

        <!-- Modal Edit Siswa -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Data Siswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="edit_student.php?id=<?= $student['id']; ?>" method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control" name="name" value="<?= $student['name']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="birth_of_date" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" name="birth_of_date" value="<?= $student['birth_of_date']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="gender" class="form-label">Jenis Kelamin</label>
                                <select class="form-control" name="gender" required>
                                    <option value="Male" <?= $student['gender'] == 'Male' ? 'selected' : ''; ?>>Laki-laki</option>
                                    <option value="Female" <?= $student['gender'] == 'Female' ? 'selected' : ''; ?>>Perempuan</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="disability_type" class="form-label">Jenis Ketunaan</label>
                                <input type="text" class="form-control" name="disability_type" value="<?= $student['disability_type']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="class" class="form-label">Kelas</label>
                                <input type="text" class="form-control" name="class" value="<?= $student['class']; ?>" required>
                            </div>

                            <button type="submit" class="btn btn-success" name="edit_student">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Kembali -->
        <a href="students.php" class="btn btn-secondary mt-3">Kembali ke Daftar Siswa</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybB2Fq6k5rGg3o0v5j6fpOd1L+ZlJzHpGz1tB8v0fK8FE5+cYB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0J9bBq6XqQs3QU5dO1+5X5FL2+5d9tG5y5S6nX5y5v5ObyO8" crossorigin="anonymous"></script>
</body>
</html>

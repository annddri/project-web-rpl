<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['status']) && ($_SESSION['role'] == 'konselor' || $_SESSION['role'] == 'pasien' || $_SESSION['role'] == 'admin')) {
    header("location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>Login - Stark Hope</title>
</head>
<body class="bg-light">

<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="card border-0 shadow-sm p-4" style="max-width: 400px; width: 100%;">
        
        <div class="card-body">
            <div class="mb-3">
                <a href="index.php" class="text-decoration-none text-secondary small">
                    &larr; Kembali ke Beranda
                </a>
            </div>

            <div class="text-center mb-4">
                <h3 class="fw-bold text-primary">Stark Hope</h3>
                <p class="text-muted">Selamat datang kembali, silakan login.</p>
            </div>

            <form action="login_process.php" method="POST">
                
                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="nama@email.com" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                </div>

                <div class="d-grid gap-2 mb-4">
                    <button type="submit" class="btn btn-primary py-2">Masuk</button>
                </div>

            </form>

            <div class="text-center">
                <p class="mb-0">Belum punya akun?</p>
                <a href="signup.php" class="text-decoration-none fw-bold">Daftar Akun Baru</a>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
</body>
</html>
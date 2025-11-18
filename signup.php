<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>Daftar Akun - Stark Hope</title>
</head>
<body class="bg-light">

    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="card border-0 shadow-sm p-4" style="max-width: 400px; width: 100%;">
            
            <div class="card-body">
                <div class="mb-3">
                    <a href="index.html" class="text-decoration-none text-secondary small">
                        &larr; Kembali ke Beranda
                    </a>
                </div>

                <div class="text-center mb-4">
                    <h3 class="fw-bold text-primary">Stark Hope</h3>
                    <p class="text-muted">Buat akun untuk memulai.</p>
                </div>

                <form action="signup_process.php" method="POST">
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Buat username unik" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Alamat Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="nama@email.com" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 6 karakter" required>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Ulangi Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Ketik ulang password" required>
                    </div>

                    <div class="d-grid gap-2 mb-4">
                        <button type="submit" class="btn btn-primary py-2">Daftar Sekarang</button>
                    </div>

                </form>

                <div class="text-center">
                    <p class="mb-0">Sudah punya akun?</p>
                    <a href="login.html" class="text-decoration-none fw-bold">Login di sini</a>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
</body>
</html>
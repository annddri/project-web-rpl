<?php
session_start();
include 'koneksi.php';

// 1. Cek Login
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    header("location: login.html");
    exit;
}

$user_id = $_SESSION['user_id'];

// 2. LOGIKA UPDATE DATA (Jika tombol Simpan ditekan)
if (isset($_POST['simpan'])) {
    $nama          = mysqli_real_escape_string($conn, $_POST['nama']);
    $username      = mysqli_real_escape_string($conn, $_POST['username']);
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat        = mysqli_real_escape_string($conn, $_POST['alamat']);

    // Query Update (Email tidak diupdate)
    $query_update = "UPDATE users SET 
                     nama = '$nama',
                     username = '$username',
                     jenis_kelamin = '$jenis_kelamin',
                     tanggal_lahir = '$tanggal_lahir',
                     alamat = '$alamat'
                     WHERE user_id = '$user_id'";

    if (mysqli_query($conn, $query_update)) {
        // Update Session Nama & Username agar tampilan di navbar langsung berubah
        $_SESSION['nama'] = $nama;
        $_SESSION['username'] = $username;
        
        echo "<script>alert('Profil berhasil diperbarui!'); window.location.href='profil.php';</script>";
    } else {
        echo "<script>alert('Gagal update: " . mysqli_error($conn) . "');</script>";
    }
}

// 3. AMBIL DATA USER TERBARU
$query = "SELECT * FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);
$d = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Stark Hope</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg bg-white shadow-sm mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold text-primary" href="index.php">Stark Hope</a>
    <div class="d-flex gap-2">
        <a href="index.php" class="btn btn-outline-secondary btn-sm">Kembali ke Beranda</a>
        <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container mb-5">
    <div class="row justify-content-center">
        
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm text-center p-4 h-100">
                <div class="mb-3">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($d['nama']); ?>&background=0D6EFD&color=fff&size=150" 
                         class="rounded-circle shadow-sm" alt="Foto Profil">
                </div>
                <h5 class="fw-bold"><?php echo $d['nama']; ?></h5>
                <p class="text-muted small mb-1"><?php echo $d['email']; ?></p>
                <span class="badge bg-primary-subtle text-primary mb-3"><?php echo ucfirst($d['role']); ?></span>
                
                <hr>
                <div class="text-start small text-muted">
                    <p class="mb-2"><i class="bi bi-person-vcard me-2"></i> ID: <?php echo $d['user_id']; ?></p>
                    <p class="mb-2"><i class="bi bi-calendar3 me-2"></i> Bergabung: <?php echo date('d M Y', strtotime($d['created_at'] ?? 'now')); ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Profil</h5>
                </div>
                <div class="card-body p-4">
                    
                    <form action="" method="POST">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Username</label>
                                <input type="text" name="username" class="form-control" value="<?php echo $d['username']; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" value="<?php echo $d['nama']; ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Alamat Email</label>
                            <input type="email" class="form-control bg-light" value="<?php echo $d['email']; ?>" readonly>
                            <small class="text-muted" style="font-size: 11px;">*Email tidak dapat diubah.</small>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-select" required>
                                    <option value="" disabled <?php echo ($d['jenis_kelamin'] == '') ? 'selected' : ''; ?>>Pilih...</option>
                                    
                                    <option value="L" <?php echo ($d['jenis_kelamin'] == 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                                    
                                    <option value="P" <?php echo ($d['jenis_kelamin'] == 'P') ? 'selected' : ''; ?>>Perempuan</option>
                                    <option value="T" <?php echo ($d['jenis_kelamin'] == 'T') ? 'selected' : ''; ?>>Tidak ingin memberi tahu</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="form-control" value="<?php echo $d['tanggal_lahir']; ?>">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat domisili Anda..."><?php echo $d['alamat']; ?></textarea>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" name="simpan" class="btn btn-primary px-4">
                                <i class="bi bi-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>
</body>
</html>
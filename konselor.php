<?php 
session_start(); 
include 'koneksi.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Konselor - Stark Hope</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold text-primary" href="index.php">Stark Hope</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link active fw-bold" href="konselor.php">Cari Konselor</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php">Kembali ke Beranda</a></li>
      </ul>
      
      <div class="d-flex align-items-center gap-2">
        <?php if (isset($_SESSION['status']) && $_SESSION['status'] == 'login'): ?>
            <div class="text-end me-2 d-none d-md-block">
                <span class="fw-bold text-primary d-block">Halo, <?php echo htmlspecialchars($_SESSION['nama']); ?></span>
                <small class="text-muted" style="font-size: 0.75rem;">
                    <?php echo isset($_SESSION['role']) ? ucfirst($_SESSION['role']) : 'User'; ?>
                </small>
            </div>
            <a href="logout.php" class="btn btn-danger btn-sm px-3">Logout</a>
        <?php else: ?>
            <a href="signup.html" class="btn btn-primary">Daftar</a>
            <a href="login.html" class="btn btn-outline-primary">Masuk</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<header class="bg-primary text-white py-5 mb-5">
    <div class="container text-center">
        <h1 class="fw-bold display-5">Tim Konselor Kami</h1>
        <p class="lead opacity-75">Pilih dari 10+ profesional terbaik yang siap mendengarkan Anda.</p>
    </div>
</header>

<div class="container mb-5">
    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">

        <?php
// 1. UBAH QUERY (Gunakan LEFT JOIN biar data profil ikut terpanggil)
$query = "SELECT u.*, kp.pendidikan, kp.nomor_str, kp.metode_terapi, kp.bahasa, kp.tentang_saya
          FROM users u
          LEFT JOIN konselor_profil kp ON u.user_id = kp.user_id
          WHERE u.role = 'konselor' AND u.status = 'active'
          LIMIT 10";

$result = mysqli_query($conn, $query);
$img_counter = 1; 

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        
        // Rotasi Gambar Dummy
        $gambar = "img/dokter" . $img_counter . ".jpg";
        $img_counter = ($img_counter < 4) ? $img_counter + 1 : 1;
        
        // Cek Data Kosong (Fallback)
        $spesialis  = !empty($row['spesialisasi']) ? $row['spesialisasi'] : 'Psikolog Umum';
        $pendidikan = !empty($row['pendidikan']) ? $row['pendidikan'] : '-';
        $tentang    = !empty($row['tentang_saya']) ? $row['tentang_saya'] : 'Konselor profesional Stark Hope.';
        $metode     = !empty($row['metode_terapi']) ? $row['metode_terapi'] : '-';
        $bahasa     = !empty($row['bahasa']) ? $row['bahasa'] : 'Indonesia';
        $str        = !empty($row['nomor_str']) ? $row['nomor_str'] : '-';
        ?>
        
        <div class="col">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <img src="<?php echo $gambar; ?>" class="card-img-top" style="height: 220px; object-fit: cover;">
                
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold text-dark mb-1"><?php echo $row['nama']; ?></h5>
                    <small class="text-primary fw-bold mb-3"><?php echo $spesialis; ?></small>
                    
                    <p class="card-text text-muted small flex-grow-1">
                        <?php echo substr($tentang, 0, 60) . '...'; ?>
                    </p>
                    
                    <?php 
                        $is_konselor = (isset($_SESSION['role']) && $_SESSION['role'] == 'konselor'); 
                    ?>

                    <div class="mt-3 d-flex gap-2">
                        
                        <button type="button" class="btn btn-outline-primary <?php echo $is_konselor ? 'w-100' : 'w-50'; ?>"
                           data-bs-toggle="modal" 
                           data-bs-target="#modalProfil"
                           data-nama="<?php echo $row['nama']; ?>"
                           data-spesialis="<?php echo $spesialis; ?>"
                           data-foto="<?php echo $gambar; ?>"
                           data-str="<?php echo $str; ?>"
                           data-bahasa="<?php echo $bahasa; ?>"
                           data-tentang="<?php echo $tentang; ?>"
                           data-pendidikan="<?php echo $pendidikan; ?>"
                           data-metode="<?php echo $metode; ?>">
                           Detail
                        </button>

                        <?php if (!$is_konselor): ?>
                            <button type="button" class="btn btn-primary w-50" 
                               data-bs-toggle="modal" 
                               data-bs-target="#modalJadwal"
                               data-nama="<?php echo $row['nama']; ?>"
                               data-spesialis="<?php echo $spesialis; ?>"
                               data-id="<?php echo $row['user_id']; ?>">
                               Book
                            </button>
                        <?php endif; ?>

                    </div>

                </div>
            </div>
        </div>
        
        <?php
    }
} else {
    echo '<div class="col-12 text-center"><p class="text-muted">Data konselor belum tersedia.</p></div>';
}
?>

    </div>
</div>

<footer class="bg-dark text-light py-4 mt-auto text-center">
    <div class="container">
        <p class="small text-white-50 mb-0">&copy; 2024 Stark Hope Indonesia.</p>
    </div>
</footer>

<?php include 'components/modal_profil.php'; ?>
<?php include 'components/modal_jadwal.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>
</body>
</html>
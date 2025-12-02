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

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top shadow-sm">
  <div class="container">
    
    <a class="navbar-brand fw-bold text-primary" href="index.php">Stark Hope</a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link active" href="#">Cari Konselor</a></li>
        <li class="nav-item"><a class="nav-link" href="artikel.php">Pusat Edukasi</a></li>
        <?php if (isset($_SESSION['status']) && $_SESSION['status'] == 'login'): ?>
            <li class="nav-item"><a class="nav-link" href="jadwal_konsultasi.php">Jadwal Konsultasi</a></li>
        <?php endif; ?>
      </ul>

      <div class="d-flex align-items-center gap-3">
        
        <?php if (isset($_SESSION['status']) && $_SESSION['status'] == 'login'): ?>
            
        <?php else: ?>
            <a href="signup.php" class="btn btn-primary">Daftar</a>
            <a href="login.php" class="btn btn-outline-primary">Masuk</a>
        <?php endif; ?>

      </div>
      
    </div>
  </div>
</nav>
<!-- END NAVBAR -->

<header class="bg-primary text-white py-5 mb-5">
    <div class="container text-center">
        <h1 class="fw-bold display-5">Tim Konselor Kami</h1>
        <p class="lead opacity-75">Pilih dari 10+ profesional terbaik yang siap mendengarkan Anda.</p>
    </div>
</header>

<section class="py-5">
    <div class="container">
        
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            <?php
            // FILTER PENCARIAN
            $where = "u.role = 'konselor' AND u.status = 'active'";
            if (isset($_GET['q'])) {
                $keyword = mysqli_real_escape_string($conn, $_GET['q']);
                // Pencarian bisa berdasarkan nama (di tabel users) atau spesialisasi (di tabel konselor_profil)
                $where .= " AND (u.nama LIKE '%$keyword%' OR kp.spesialisasi LIKE '%$keyword%')";
            }

            // QUERY UTAMA (JOIN Tabel USERS & KONSELOR_PROFIL)
            // Kita ambil semua kolom yang diperlukan untuk modal
            $query = "SELECT u.nama, u.user_id, 
                             u.spesialisasi, kp.foto, kp.nomor_str, 
                             kp.bahasa, kp.tentang_saya, kp.pendidikan, kp.metode_terapi
                      FROM users u 
                      LEFT JOIN konselor_profil kp ON u.user_id = kp.user_id 
                      WHERE $where 
                      ORDER BY u.nama ASC";

            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    
                    // --- LOGIKA GAMBAR (Sama seperti index.php) ---
                    $foto_db = isset($row['foto']) ? $row['foto'] : '';
                    if (!empty($foto_db) && file_exists("img/" . $foto_db) && $foto_db != 'default.jpg') {
                        $gambar = "img/" . $foto_db . "?t=" . time(); 
                    } else {
                        $gambar = "https://ui-avatars.com/api/?name=" . urlencode($row['nama']) . "&background=random&color=fff&size=400";
                    }
                    
                    // Data Fallback (Agar tidak kosong di tampilan)
                    $spesialis  = !empty($row['spesialisasi']) ? $row['spesialisasi'] : 'Psikolog Umum';
                    $str        = !empty($row['nomor_str']) ? $row['nomor_str'] : '-';
                    $bahasa     = !empty($row['bahasa']) ? $row['bahasa'] : 'Indonesia';
                    $tentang    = !empty($row['tentang_saya']) ? $row['tentang_saya'] : 'Belum ada deskripsi.';
                    $pendidikan = !empty($row['pendidikan']) ? $row['pendidikan'] : '-';
                    $metode     = !empty($row['metode_terapi']) ? $row['metode_terapi'] : '-';
            ?>

            <div class="col">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    
                    <!-- Foto Profil -->
                    <div class="position-relative">
                        <img src="<?php echo $gambar; ?>" class="card-img-top" style="height: 250px; object-fit: cover;">
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold text-truncate"><?php echo $row['nama']; ?></h5>
                        <p class="card-text text-muted small mb-3"><?php echo $spesialis; ?></p>
                        
                        <?php 
                            $is_konselor = (isset($_SESSION['role']) && $_SESSION['role'] == 'konselor'); 
                        ?>

                        <div class="mt-auto d-flex gap-2">
                            <!-- TOMBOL DETAIL -->
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

                            <!-- TOMBOL BOOKING -->
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
                echo '<div class="col-12 text-center py-5 text-muted">
                        <i class="bi bi-emoji-frown fs-1 d-block mb-3"></i>
                        <h4>Tidak ada konselor yang ditemukan.</h4>
                        <p>Coba kata kunci lain atau hapus filter pencarian.</p>
                      </div>';
            }
            ?>
        </div>

    </div>
</section>

<footer class="bg-dark text-light py-4 mt-auto text-center">
    <div class="container">
        <p class="small text-white-50 mb-0">&copy; 2025 Stark Hope Indonesia.</p>
    </div>
</footer>

<?php include 'components/modal_profil.php'; ?>
<?php include 'components/modal_jadwal.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>
</body>
</html>
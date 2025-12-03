<?php session_start(); ?>
<?php include_once 'koneksi.php'; ?> 
<?php include 'components/modal_jadwal.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Stark Hope</title>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top shadow-sm">
  <div class="container">
    
    <a class="navbar-brand fw-bold text-primary" href="#">Stark Hope</a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link active" href="#">Beranda</a></li>
        <li class="nav-item"><a class="nav-link" href="konselor.php">Cari Konselor</a></li>
        <li class="nav-item"><a class="nav-link" href="artikel.php">Pusat Edukasi</a></li>
        <?php if (isset($_SESSION['status']) && $_SESSION['status'] == 'login'): ?>
            <li class="nav-item"><a class="nav-link" href="jadwal_konsultasi.php">Jadwal Konsultasi</a></li>
        <?php endif; ?>
      </ul>

      <div class="d-flex align-items-center gap-3">
        
        <?php if (isset($_SESSION['status']) && $_SESSION['status'] == 'login'): ?>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" 
                  id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                    
                    <div class="text-end me-2 d-none d-md-block" style="line-height: 1.2;">
                        <span class="fw-bold text-dark d-block" style="font-size: 0.9rem;">
                            <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </span>
                        <small class="text-muted" style="font-size: 0.75rem;">
                            <?php echo ucfirst($_SESSION['role']); ?>
                        </small>
                    </div>

                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['username']); ?>&background=0D6EFD&color=fff" 
                        alt="Profil" 
                        class="rounded-circle border border-2 border-white shadow-sm"
                        width="40" height="40">
                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" aria-labelledby="dropdownUser">
                    <li class="d-block d-md-none px-3 py-2 text-center">
                        <span class="fw-bold text-primary">Halo, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    </li>
                    
                    <li>
                        <a class="dropdown-item py-2" href="<?php echo ($_SESSION['role'] == 'konselor') ? 'profil_konselor.php' : 'profil.php'; ?>">
                            <i class="bi bi-person-circle me-2 text-secondary"></i> Profil Saya
                        </a>
                    </li>
                    
                    <li><hr class="dropdown-divider"></li>
                    
                    <li>
                        <a class="dropdown-item py-2 text-danger fw-bold" href="logout.php">
                            <i class="bi bi-box-arrow-right me-2"></i> Keluar (Logout)
                        </a>
                    </li>
                </ul>
            </div>

        <?php else: ?>
            <a href="signup.php" class="btn btn-primary">Daftar</a>
            <a href="login.php" class="btn btn-outline-primary">Masuk</a>
        <?php endif; ?>

      </div>
      
    </div>
  </div>
</nav>

<!-- CAROUSEL -->
<div id="carouselExampleIndicators" class="carousel slide carousel-fixed-height" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="img/carousel1.jpg" class="d-block w-100" alt="Slide 1">
    </div>
    <div class="carousel-item">
      <img src="img/carousel2.jpg" class="d-block w-100" alt="Slide 2">
    </div>
    <div class="carousel-item">
      <img src="img/carousel3.jpg" class="d-block w-100" alt="Slide 3">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<!-- SECTION KONSELOR -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col text-center">
                <h2 class="fw-bold">Pilih Konselor Terbaik Kami</h2>
                <p class="text-muted">Temukan profesional yang tepat untuk Anda.</p>
            </div>
        </div>
        
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            <?php 
            $query_home = "SELECT u.nama, u.user_id, 
                                  u.spesialisasi, kp.foto, kp.nomor_str, 
                                  kp.bahasa, kp.tentang_saya, kp.pendidikan, kp.metode_terapi
                          FROM users u
                          LEFT JOIN konselor_profil kp ON u.user_id = kp.user_id
                          WHERE u.role = 'konselor' AND u.status = 'active'
                          LIMIT 4"; 

            $result_home = mysqli_query($conn, $query_home);

            if ($result_home && mysqli_num_rows($result_home) > 0) {
                while ($row = mysqli_fetch_assoc($result_home)) {
                    
                    $foto_db = isset($row['foto']) ? $row['foto'] : '';
                    
                    if (!empty($foto_db) && file_exists("img/" . $foto_db) && $foto_db != 'default.jpg') {
                        $gambar = "img/" . $foto_db . "?t=" . time(); 
                    } else {
                        $gambar = "https://ui-avatars.com/api/?name=" . urlencode($row['nama']) . "&background=random&color=fff&size=400";
                    }
                    
                    $spesialis  = !empty($row['spesialisasi']) ? $row['spesialisasi'] : 'Psikolog Umum';
                    $str        = !empty($row['nomor_str']) ? $row['nomor_str'] : '-';
                    $bahasa     = !empty($row['bahasa']) ? $row['bahasa'] : 'Indonesia';
                    $tentang    = !empty($row['tentang_saya']) ? $row['tentang_saya'] : 'Belum ada deskripsi.';
                    $pendidikan = !empty($row['pendidikan']) ? $row['pendidikan'] : '-';
                    $metode     = !empty($row['metode_terapi']) ? $row['metode_terapi'] : '-';
            ?>
            
            <div class="col">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <img src="<?php echo $gambar; ?>" class="card-img-top" style="height: 250px; object-fit: cover;" alt="<?php echo $row['nama']; ?>">
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold"><?php echo $row['nama']; ?></h5>
                        <p class="card-text text-muted small mb-4"><?php echo $spesialis; ?></p>
                        
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

                            <!-- TOMBOL BOOKING (Hanya untuk Pasien/Umum) -->
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
                echo '<div class="col-12 text-center py-5 text-muted">Belum ada konselor yang tersedia saat ini.</div>';
            }
            ?>
        </div>

        <div class="row mt-5">
            <div class="col text-center">
                <p class="mb-3 text-muted">Belum menemukan spesialis yang cocok?</p>
                <a href="konselor.php" class="btn btn-outline-primary btn-lg px-5 rounded-pill shadow-sm">
                    Lihat Daftar Lengkap Konselor <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- SECTION EDUKASI -->
<section id="edukasi" class="py-5 bg-light">
    <div class="container">
        
        <div class="row mb-5 align-items-end">
            <div class="col-md-8">
                <h6 class="text-primary fw-bold text-uppercase">Jurnal Edukasi</h6>
                <h2 class="fw-bold display-6">Wawasan Kesehatan Mental</h2>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="artikel.php" class="btn btn-outline-dark rounded-pill px-4">
                    Lihat Semua Artikel <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>

        <div class="row g-4">
            <?php
            if(isset($conn)){
                $q_edu_home = mysqli_query($conn, "SELECT * FROM edukasi ORDER BY id DESC LIMIT 3");
                
                if ($q_edu_home && mysqli_num_rows($q_edu_home) > 0) {
                    while ($edu = mysqli_fetch_assoc($q_edu_home)) {
                        
                        $kat = $edu['kategori'];
                        $badge_color = 'primary'; 
                        if ($kat == 'Video') $badge_color = 'danger'; 
                        if ($kat == 'Kisah Nyata') $badge_color = 'success'; 
                        
                        $deskripsi = substr(strip_tags($edu['isi_konten']), 0, 90) . '...';
            ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm hover-card overflow-hidden">
                        <div class="position-relative">
                            <img src="<?php echo $edu['gambar_url']; ?>" class="card-img-top" alt="Thumbnail" style="height: 200px; object-fit: cover;">
                            
                            <span class="position-absolute top-0 start-0 m-3 badge bg-<?php echo $badge_color; ?> shadow-sm">
                                <?php echo $kat; ?>
                            </span>
                        </div>
                        
                        <div class="card-body p-4 d-flex flex-column">
                            <h5 class="card-title fw-bold mb-3">
                                <a href="detail_artikel.php?id=<?php echo $edu['id']; ?>" class="text-dark text-decoration-none stretched-link">
                                    <?php echo $edu['judul']; ?>
                                </a>
                            </h5>
                            
                            <p class="card-text text-muted small mb-4 flex-grow-1">
                                <?php echo $deskripsi; ?>
                            </p>
                            
                            <div class="mt-auto">
                                <span class="text-primary fw-bold small">
                                    <?php echo ($kat == 'Video') ? 'Tonton Sekarang' : 'Baca Selengkapnya'; ?> 
                                    <i class="bi bi-arrow-right ms-1"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
                    }
                } else {
                    echo '<div class="col-12 text-center text-muted py-5">Belum ada konten edukasi terbaru.</div>';
                }
            }
            ?>
        </div>
    </div>
</section>

<!-- HUBUNGI ADMIN -->
<section class="py-5 bg-white border-top">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                
                <h2 class="fw-bold mb-3">Butuh Bantuan Lebih Lanjut?</h2>
                <p class="text-muted mb-4 lead">
                    Hubungi admin apabila Anda membutuhkan bantuan teknis atau informasi lainnya.
                </p>

                <div class="alert alert-primary d-inline-block px-4 py-3 mb-4 rounded-3 shadow-sm text-start text-md-center">
                    <span class="fw-bold">
                        <i class="bi bi-person-badge-fill me-2"></i>
                        Anda ingin menjadi seorang konselor?
                    </span> 
                    <span class="text-dark ms-1">Segera hubungi admin untuk bergabung dengan tim kami.</span>
                </div>

                <br>

                <a href="https://wa.me/62895372029530" target="_blank" class="btn btn-success btn-lg px-5 rounded-pill shadow hover-up">
                    <i class="bi bi-whatsapp me-2"></i> Hubungi Admin
                </a>

            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="bg-dark text-light pt-5 pb-4 mt-auto">
  <div class="container">
    <div class="row">
      
      <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
        <h5 class="text-uppercase fw-bold mb-3 text-primary">Stark Hope</h5>
        <p class="text-white-50 small">
          Mitra perjalanan kesehatan mental Anda. Kami menghubungkan Anda dengan konselor profesional.
        </p>
        <div class="d-flex gap-3 mt-3">
            <a href="#" class="social-icon"><i class="bi bi-instagram"></i></a>
            <a href="#" class="social-icon"><i class="bi bi-twitter"></i></a>
            <a href="#" class="social-icon"><i class="bi bi-linkedin"></i></a>
        </div>
      </div>

      <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
        <h6 class="text-uppercase fw-bold mb-3">Menu</h6>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="#" class="footer-link">Beranda</a></li>
          <li class="mb-2"><a href="#" class="footer-link">Cari Konselor</a></li>
          <li class="mb-2"><a href="#" class="footer-link">Jurnal Kesehatan</a></li>
          <li class="mb-2"><a href="#" class="footer-link">Tentang Kami</a></li>
        </ul>
      </div>

      <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
        <h6 class="text-uppercase fw-bold mb-3">Bantuan</h6>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="#" class="footer-link">FAQ</a></li>
          <li class="mb-2"><a href="#" class="footer-link">Kebijakan Privasi</a></li>
          <li class="mb-2"><a href="#" class="footer-link">Syarat & Ketentuan</a></li>
          <li class="mb-2"><a href="#" class="footer-link">Kontak Support</a></li>
        </ul>
      </div>

      <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
        <h6 class="text-uppercase fw-bold mb-3">Hubungi Kami</h6>
        <p class="text-white-50 small mb-1">Jl. Sejahtera No. 10, Jakarta Selatan</p>
        <p class="text-white-50 small mb-1">Email: support@starkhope.id</p>
        <p class="text-white-50 small mb-3">WA: +62 895 3720 29530</p>
        
        <div class="alert alert-secondary py-2 px-3 small border-0" role="alert">
          <strong>Darurat:</strong> Jika dalam bahaya, segera hubungi 119.
        </div>
      </div>

    </div>

    <hr class="my-4 border-secondary">

    <div class="row align-items-center">
      <div class="col-md-7 col-lg-8 text-center text-md-start">
        <p class="small text-white-50 mb-0">
          &copy; 2025 Stark Hope Indonesia.
        </p>
      </div>
    </div>

  </div>
</footer>

<?php include 'components/modal_profil.php'; ?>
<?php include 'components/modal_jadwal.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
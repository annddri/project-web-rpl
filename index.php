<?php session_start(); ?>
<?php include 'components/modal_jadwal.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>Stark Hope</title>
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

<section class="container my-5 py-5">
  <div class="row">
    <div class="col-lg-8 offset-lg-2 text-center">

      <h2 class="display-5 fw-bold mb-3">
        Bantu Kami Memahami Kebutuhan Anda
      </h2>

      <p class="lead mb-4">
        Isi penilaian singkat ini agar kami dapat merekomendasikan konselor 
        yang paling sesuai dengan kondisi dan preferensi Anda.
      </p>

      <a href="/link-ke-halaman-survey" class="btn btn-primary btn-lg px-5 py-3">
        Mulai Penilaian Kebutuhan
      </a>

    </div>
  </div>
</section>

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
include_once 'koneksi.php'; // Pastikan koneksi dipanggil

// QUERY UNTUK HOME (Sama kayak tadi, tapi LIMIT 4)
$query_home = "SELECT u.*, kp.pendidikan, kp.nomor_str, kp.metode_terapi, kp.bahasa, kp.tentang_saya
               FROM users u
               LEFT JOIN konselor_profil kp ON u.user_id = kp.user_id
               WHERE u.role = 'konselor' AND u.status = 'active'
               LIMIT 4"; // <--- PENTING: Tetap limit 4 biar rapi

$result_home = mysqli_query($conn, $query_home);
$img_counter = 1;

if (mysqli_num_rows($result_home) > 0) {
    while ($row = mysqli_fetch_assoc($result_home)) {
        
        // Variable Setup (Sama persis)
        $gambar = "img/dokter" . $img_counter . ".jpg";
        $img_counter = ($img_counter < 4) ? $img_counter + 1 : 1;
        
        $spesialis  = !empty($row['spesialisasi']) ? $row['spesialisasi'] : 'Psikolog Umum';
        $pendidikan = !empty($row['pendidikan']) ? $row['pendidikan'] : '-';
        $tentang    = !empty($row['tentang_saya']) ? $row['tentang_saya'] : 'Halo, saya siap membantu Anda.';
        $metode     = !empty($row['metode_terapi']) ? $row['metode_terapi'] : '-';
        $bahasa     = !empty($row['bahasa']) ? $row['bahasa'] : 'Indonesia';
        $str        = !empty($row['nomor_str']) ? $row['nomor_str'] : '-';
?>

    <div class="col">
        <div class="card h-100 border-0 shadow-sm">
            <img src="<?php echo $gambar; ?>" class="card-img-top" style="height: 250px; object-fit: cover;">
            
            <div class="card-body d-flex flex-column">
                <h5 class="card-title fw-bold"><?php echo $row['nama']; ?></h5>
                <p class="card-text text-muted small mb-4"><?php echo $spesialis; ?></p>
                
                <?php 
                    $is_konselor = (isset($_SESSION['role']) && $_SESSION['role'] == 'konselor'); 
                ?>

                <div class="mt-auto d-flex gap-2">
                    
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

    </div> </section>

  </div>
</section>

<section class="py-5" id="edukasi">
  <div class="container">

    <div class="row mb-5 align-items-end">
      <div class="col-md-8">
        <span class="badge bg-primary-subtle text-primary fw-bold mb-2">INSIGHT & MOTIVASI</span>
        <h2 class="fw-bold display-6">Jurnal Edukasi & Inspirasi</h2>
        <p class="text-muted lead mb-0">
          Temukan artikel kesehatan, video panduan, dan kisah nyata yang menguatkan langkahmu.
        </p>
      </div>
      <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="artikel.php" class="btn btn-outline-dark rounded-pill px-4">Lihat Semua Artikel <i class="bi bi-arrow-right ms-2"></i></a>
      </div>
    </div>

    <div class="row g-4">

      <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm hover-card overflow-hidden">
          <div class="position-relative">
            <img src="https://images.unsplash.com/photo-1499209974431-9dddcece7f88?q=80&w=600&auto=format&fit=crop" class="card-img-top img-edukasi" alt="Artikel Cemas">
            <span class="position-absolute top-0 start-0 m-3 badge bg-white text-primary fw-bold shadow-sm">
              <i class="bi bi-book me-1"></i> Artikel
            </span>
          </div>
          <div class="card-body p-4">
            <div class="d-flex align-items-center text-muted small mb-2">
              <span><i class="bi bi-clock me-1"></i> 5 Menit Baca</span>
              <span class="mx-2">•</span>
              <span>Oleh Dr. Andi</span>
            </div>
            <h5 class="card-title fw-bold mb-3">
              <a href="detail_artikel.php?id=1" class="text-dark text-decoration-none stretched-link">Cara Mengelola Serangan Panik di Tempat Umum</a>
            </h5>
            <p class="card-text text-muted">
              Pelajari teknik pernapasan 4-7-8 dan langkah praktis untuk menenangkan diri saat kecemasan melanda secara tiba-tiba.
            </p>
          </div>
          <div class="card-footer bg-white border-0 p-4 pt-0">
            <span class="text-primary fw-bold text-decoration-none">Baca Selengkapnya <i class="bi bi-chevron-right small"></i></span>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm hover-card overflow-hidden">
          <div class="position-relative">
            <img src="https://images.unsplash.com/photo-1506126613408-eca07ce68773?q=80&w=600&auto=format&fit=crop" class="card-img-top img-edukasi" alt="Video Yoga">
            <span class="position-absolute top-0 start-0 m-3 badge bg-danger text-white fw-bold shadow-sm">
              <i class="bi bi-play-circle-fill me-1"></i> Video
            </span>
            <div class="position-absolute top-50 start-50 translate-middle bg-dark bg-opacity-50 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                <i class="bi bi-play-fill text-white fs-2"></i>
            </div>
          </div>
          <div class="card-body p-4">
             <div class="d-flex align-items-center text-muted small mb-2">
              <span><i class="bi bi-camera-video me-1"></i> 10 Menit</span>
              <span class="mx-2">•</span>
              <span>Mindfulness</span>
            </div>
            <h5 class="card-title fw-bold mb-3">
              <a href="detail_artikel.php?id=2" class="text-dark text-decoration-none stretched-link">Meditasi Pagi untuk Ketenangan Jiwa</a>
            </h5>
            <p class="card-text text-muted">
              Panduan visual meditasi ringan yang bisa Anda lakukan setiap pagi sebelum memulai aktivitas.
            </p>
          </div>
          <div class="card-footer bg-white border-0 p-4 pt-0">
            <span class="text-primary fw-bold text-decoration-none">Tonton Sekarang <i class="bi bi-chevron-right small"></i></span>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm hover-card overflow-hidden">
          <div class="position-relative">
            <img src="https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?q=80&w=600&auto=format&fit=crop" class="card-img-top img-edukasi" alt="Kisah Nyata">
            <span class="position-absolute top-0 start-0 m-3 badge bg-success text-white fw-bold shadow-sm">
              <i class="bi bi-people-fill me-1"></i> Kisah Nyata
            </span>
          </div>
          <div class="card-body p-4">
             <div class="d-flex align-items-center text-muted small mb-2">
              <span><i class="bi bi-calendar3 me-1"></i> 12 Nov 2024</span>
              <span class="mx-2">•</span>
              <span>Inspirasi</span>
            </div>
            <h5 class="card-title fw-bold mb-3">
              <a href="detail_artikel.php?id=3" class="text-dark text-decoration-none stretched-link">"Aku Berdamai dengan Luka Masa Lalu"</a>
            </h5>
            <p class="card-text text-muted">
              Kisah perjalanan Rina bangkit dari depresi pasca-trauma dan menemukan harapan baru melalui konseling.
            </p>
          </div>
          <div class="card-footer bg-white border-0 p-4 pt-0">
            <span class="text-primary fw-bold text-decoration-none">Baca Kisahnya <i class="bi bi-chevron-right small"></i></span>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<footer class="bg-dark text-light pt-5 pb-4 mt-auto">
  <div class="container">
    <div class="row">
      
      <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
        <h5 class="text-uppercase fw-bold mb-3 text-primary">Stark Hope</h5>
        <p class="text-white-50 small">
          Mitra perjalanan kesehatan mental Anda. Kami menghubungkan Anda dengan konselor profesional.
        </p>
        <div class="d-flex gap-3 mt-3">
            <a href="#" class="social-icon">IG</a>
            <a href="#" class="social-icon">TW</a>
            <a href="#" class="social-icon">IN</a>
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
        <p class="text-white-50 small mb-3">WA: +62 812-3456-7890</p>
        
        <div class="alert alert-secondary py-2 px-3 small border-0" role="alert">
          <strong>Darurat:</strong> Jika dalam bahaya, segera hubungi 119.
        </div>
      </div>

    </div>

    <hr class="my-4 border-secondary">

    <div class="row align-items-center">
      <div class="col-md-7 col-lg-8 text-center text-md-start">
        <p class="small text-white-50 mb-0">
          &copy; 2024 Stark Hope Indonesia.
        </p>
      </div>
    </div>

  </div>
</footer>

<?php include 'components/modal_profil.php'; ?>
<?php include 'components/modal_jadwal.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
</body>
</html>
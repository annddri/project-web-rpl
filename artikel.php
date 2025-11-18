<?php 
session_start(); 

// DATA DUMMY (5 KONTEN CAMPURAN)
// Di aplikasi nyata, ini diambil dari database tabel 'articles'
$semua_artikel = [
    [
        'id' => 1,
        'judul' => 'Cara Mengelola Serangan Panik di Tempat Umum',
        'kategori' => 'Artikel',
        'warna' => 'primary', // Biru
        'gambar' => 'https://images.unsplash.com/photo-1499209974431-9dddcece7f88?q=80&w=600&auto=format&fit=crop',
        'desc' => 'Pelajari teknik pernapasan 4-7-8 dan langkah praktis menenangkan diri.'
    ],
    [
        'id' => 2,
        'judul' => 'Meditasi Pagi untuk Ketenangan Jiwa',
        'kategori' => 'Video',
        'warna' => 'danger', // Merah
        'gambar' => 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?q=80&w=600&auto=format&fit=crop',
        'desc' => 'Panduan visual meditasi ringan 10 menit sebelum memulai aktivitas.'
    ],
    [
        'id' => 3,
        'judul' => '"Aku Berdamai dengan Luka Masa Lalu"',
        'kategori' => 'Kisah Nyata',
        'warna' => 'success', // Hijau
        'gambar' => 'https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?q=80&w=600&auto=format&fit=crop',
        'desc' => 'Kisah inspiratif Rina bangkit dari depresi dan menemukan harapan baru.'
    ],
    [
        'id' => 4, // ID BARU
        'judul' => 'Pentingnya "Sleep Hygiene" untuk Mental',
        'kategori' => 'Artikel',
        'warna' => 'primary',
        'gambar' => 'https://images.unsplash.com/photo-1541781777621-ddb1d920ca7a?q=80&w=600&auto=format&fit=crop',
        'desc' => 'Mengapa tidur yang berkualitas adalah kunci utama kestabilan emosi Anda.'
    ],
    [
        'id' => 5, // ID BARU
        'judul' => 'Kenali Tanda "Burnout" Pekerjaan',
        'kategori' => 'Video',
        'warna' => 'danger',
        'gambar' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=600&auto=format&fit=crop',
        'desc' => 'Video singkat gejala kelelahan mental akibat kerja dan solusinya.'
    ]
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Edukasi - Stark Hope</title>
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
        <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link active fw-bold" href="artikel.php">Pusat Edukasi</a></li>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <?php if (isset($_SESSION['status']) && $_SESSION['status'] == 'login'): ?>
            <div class="text-end me-2 d-none d-md-block">
                <span class="fw-bold text-primary d-block">Halo, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
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

<header class="bg-dark text-white py-5">
    <div class="container text-center">
        <h1 class="fw-bold display-5">Wawasan & Inspirasi</h1>
        <p class="lead opacity-75">Kumpulan artikel, video, dan cerita untuk menemani perjalanan kesehatan mentalmu.</p>
        
        <div class="row justify-content-center mt-4">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control rounded-start-pill ps-4" placeholder="Cari topik (misal: cemas, tidur...)">
                    <button class="btn btn-primary rounded-end-pill px-4" type="button"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container mt-5 mb-4">
    <div class="d-flex justify-content-center gap-2 flex-wrap">
        <button class="btn btn-dark rounded-pill px-4 active">Semua</button>
        <button class="btn btn-outline-secondary rounded-pill px-4">Artikel</button>
        <button class="btn btn-outline-secondary rounded-pill px-4">Video</button>
        <button class="btn btn-outline-secondary rounded-pill px-4">Kisah Nyata</button>
    </div>
</div>

<div class="container mb-5">
    <div class="row g-4">
        
        <?php foreach($semua_artikel as $item): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm hover-card overflow-hidden">
                <div class="position-relative">
                    <img src="<?php echo $item['gambar']; ?>" class="card-img-top" alt="<?php echo $item['judul']; ?>" style="height: 200px; object-fit: cover;">
                    
                    <span class="position-absolute top-0 start-0 m-3 badge bg-<?php echo $item['warna']; ?> shadow-sm">
                        <?php echo $item['kategori']; ?>
                    </span>
                </div>
                
                <div class="card-body p-4 d-flex flex-column">
                    <h5 class="card-title fw-bold mb-3">
                        <a href="detail_artikel.php?id=<?php echo $item['id']; ?>" class="text-dark text-decoration-none stretched-link">
                            <?php echo $item['judul']; ?>
                        </a>
                    </h5>
                    <p class="card-text text-muted small mb-4 flex-grow-1">
                        <?php echo $item['desc']; ?>
                    </p>
                    <div class="mt-auto">
                        <span class="text-primary fw-bold small">
                            <?php echo ($item['kategori'] == 'Video') ? 'Tonton Sekarang' : 'Baca Selengkapnya'; ?> 
                            <i class="bi bi-arrow-right ms-1"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

    </div>
</div>

<footer class="bg-dark text-light py-4 mt-auto text-center">
    <div class="container">
        <p class="small text-white-50 mb-0">&copy; 2024 Stark Hope Indonesia.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>
</body>
</html>
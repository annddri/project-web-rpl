<?php 
session_start(); 

// --- DATA DUMMY ARTIKEL (PENGGANTI DATABASE SEMENTARA) ---
$id = isset($_GET['id']) ? $_GET['id'] : 1; // Default ke artikel 1 jika tidak ada ID

$artikel_db = [
    1 => [
        'judul' => 'Cara Mengelola Serangan Panik di Tempat Umum',
        'kategori' => 'Artikel Kesehatan',
        'penulis' => 'Dr. Andi Pratama',
        'tanggal' => '15 Nov 2024',
        'gambar' => 'https://images.unsplash.com/photo-1499209974431-9dddcece7f88?q=80&w=1000&auto=format&fit=crop',
        'isi' => '<p class="lead">Serangan panik bisa terjadi kapan saja dan di mana saja. Memahaminya adalah langkah awal untuk mengendalikannya.</p>
                  <p>Ketika jantung berdebar kencang dan napas terasa sesak di tengah keramaian, hal pertama yang harus dilakukan adalah <strong>mencari tempat yang tenang</strong>. Jangan melawan perasaan itu, tapi terima bahwa itu sedang terjadi dan akan segera berlalu.</p>
                  <h4>Teknik Pernapasan 4-7-8</h4>
                  <p>Cobalah teknik ini: Tarik napas selama 4 detik, tahan selama 7 detik, dan hembuskan perlahan melalui mulut selama 8 detik. Ulangi siklus ini sebanyak 4 kali. Ini akan memberi sinyal pada sistem saraf parasimpatis untuk menenangkan diri.</p>
                  <p>Ingatlah bahwa Anda tidak sendirian, dan perasaan ini tidak berbahaya secara fisik meskipun terasa sangat menakutkan.</p>'
    ],
    2 => [
        'judul' => 'Meditasi Pagi untuk Ketenangan Jiwa',
        'kategori' => 'Video & Panduan',
        'penulis' => 'Tim Stark Hope',
        'tanggal' => '14 Nov 2024',
        'gambar' => 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?q=80&w=1000&auto=format&fit=crop',
        'isi' => '<div class="ratio ratio-16x9 mb-4"><iframe src="https://www.youtube.com/embed/inpok4MKVLM?rel=0" title="YouTube video" allowfullscreen></iframe></div>
                  <p class="lead">Memulai hari dengan 10 menit keheningan dapat mengubah produktivitas Anda sepanjang hari.</p>
                  <p>Meditasi bukan tentang mengosongkan pikiran, melainkan melatih fokus. Dalam video di atas, kita akan belajar teknik <em>mindfulness</em> dasar untuk menyadari napas dan sensasi tubuh.</p>
                  <p>Lakukan ini setiap bangun tidur sebelum memegang handphone Anda. Rasakan perbedaannya dalam 7 hari.</p>'
    ],
    3 => [
        'judul' => '"Aku Berdamai dengan Luka Masa Lalu"',
        'kategori' => 'Kisah Nyata',
        'penulis' => 'Rina (Nama Samaran)',
        'tanggal' => '12 Nov 2024',
        'gambar' => 'https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?q=80&w=1000&auto=format&fit=crop',
        'isi' => '<p class="lead font-italic">"Dulu aku berpikir luka itu akan hilang seiring waktu. Ternyata, waktu tidak menyembuhkan. Penerimaan-lah yang menyembuhkan."</p>
                  <p>Selama bertahun-tahun, saya lari dari kenangan masa kecil yang menyakitkan. Saya menyibukkan diri dengan pekerjaan hingga lupa cara beristirahat. Akibatnya, saya mengalami <em>burnout</em> parah.</p>
                  <p>Melalui sesi konseling di Stark Hope, saya belajar bahwa menangis itu bukan tanda kelemahan. Saya belajar memeluk diri saya yang kecil (inner child) dan berkata, "Kamu aman sekarang".</p>
                  <p>Perjalanan ini tidak mudah, tapi sangat layak diperjuangkan.</p>'
    ],
    4 => [
        'judul' => 'Cara Mengelola Serangan Panik di Tempat Umum',
        'kategori' => 'Artikel Kesehatan',
        'penulis' => 'Dr. Andi Pratama',
        'tanggal' => '15 Nov 2024',
        'gambar' => 'https://images.unsplash.com/photo-1499209974431-9dddcece7f88?q=80&w=1000&auto=format&fit=crop',
        'isi' => '<p class="lead">Serangan panik bisa terjadi kapan saja dan di mana saja. Memahaminya adalah langkah awal untuk mengendalikannya.</p>
                  <p>Ketika jantung berdebar kencang dan napas terasa sesak di tengah keramaian, hal pertama yang harus dilakukan adalah <strong>mencari tempat yang tenang</strong>. Jangan melawan perasaan itu, tapi terima bahwa itu sedang terjadi dan akan segera berlalu.</p>
                  <h4>Teknik Pernapasan 4-7-8</h4>
                  <p>Cobalah teknik ini: Tarik napas selama 4 detik, tahan selama 7 detik, dan hembuskan perlahan melalui mulut selama 8 detik. Ulangi siklus ini sebanyak 4 kali. Ini akan memberi sinyal pada sistem saraf parasimpatis untuk menenangkan diri.</p>
                  <p>Ingatlah bahwa Anda tidak sendirian, dan perasaan ini tidak berbahaya secara fisik meskipun terasa sangat menakutkan.</p>'
    ],
    5 => [
        'judul' => 'Cara Mengelola Serangan Panik di Tempat Umum',
        'kategori' => 'Artikel Kesehatan',
        'penulis' => 'Dr. Andi Pratama',
        'tanggal' => '15 Nov 2024',
        'gambar' => 'https://images.unsplash.com/photo-1499209974431-9dddcece7f88?q=80&w=1000&auto=format&fit=crop',
        'isi' => '<p class="lead">Serangan panik bisa terjadi kapan saja dan di mana saja. Memahaminya adalah langkah awal untuk mengendalikannya.</p>
                  <p>Ketika jantung berdebar kencang dan napas terasa sesak di tengah keramaian, hal pertama yang harus dilakukan adalah <strong>mencari tempat yang tenang</strong>. Jangan melawan perasaan itu, tapi terima bahwa itu sedang terjadi dan akan segera berlalu.</p>
                  <h4>Teknik Pernapasan 4-7-8</h4>
                  <p>Cobalah teknik ini: Tarik napas selama 4 detik, tahan selama 7 detik, dan hembuskan perlahan melalui mulut selama 8 detik. Ulangi siklus ini sebanyak 4 kali. Ini akan memberi sinyal pada sistem saraf parasimpatis untuk menenangkan diri.</p>
                  <p>Ingatlah bahwa Anda tidak sendirian, dan perasaan ini tidak berbahaya secara fisik meskipun terasa sangat menakutkan.</p>'
    ]
];

// Ambil data sesuai ID, jika ID salah/tidak ada, ambil data ke-1
$data = isset($artikel_db[$id]) ? $artikel_db[$id] : $artikel_db[1];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['judul']; ?> - Stark Hope</title>
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
        <li class="nav-item"><a class="nav-link" href="index.php">Kembali ke Beranda</a></li>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <?php if (isset($_SESSION['status']) && $_SESSION['status'] == 'login'): ?>
            <span class="fw-bold text-primary d-none d-md-block me-2">Halo, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
        <?php else: ?>
            <a href="login.html" class="btn btn-outline-primary">Masuk</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<header class="position-relative" style="height: 400px; overflow: hidden;">
    <img src="<?php echo $data['gambar']; ?>" class="w-100 h-100" style="object-fit: cover; filter: brightness(0.5);" alt="Cover Artikel">
    <div class="position-absolute top-50 start-50 translate-middle text-center text-white w-75">
        <span class="badge bg-primary mb-2"><?php echo $data['kategori']; ?></span>
        <h1 class="fw-bold display-5"><?php echo $data['judul']; ?></h1>
        <p class="mt-3">
            <i class="bi bi-person-circle me-1"></i> <?php echo $data['penulis']; ?> &nbsp;|&nbsp; 
            <i class="bi bi-calendar3 me-1"></i> <?php echo $data['tanggal']; ?>
        </p>
    </div>
</header>

<div class="container my-5">
    <div class="row justify-content-center">
        
        <div class="col-lg-8">
            <a href="index.php#edukasi" class="text-decoration-none text-secondary mb-4 d-inline-block">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Artikel
            </a>

            <div class="bg-white p-4 p-md-5 rounded shadow-sm article-content">
                <?php echo $data['isi']; ?>
            </div>

            <div class="mt-5 text-center">
                <p class="text-muted small fw-bold">BAGIKAN ARTIKEL INI</p>
                <button class="btn btn-outline-primary btn-sm rounded-circle mx-1"><i class="bi bi-whatsapp"></i></button>
                <button class="btn btn-outline-primary btn-sm rounded-circle mx-1"><i class="bi bi-twitter-x"></i></button>
                <button class="btn btn-outline-primary btn-sm rounded-circle mx-1"><i class="bi bi-facebook"></i></button>
            </div>
        </div>

        <div class="col-lg-4 mt-5 mt-lg-0">
            <div class="sticky-top" style="top: 100px;">
                <h5 class="fw-bold mb-3">Artikel Lainnya</h5>
                
                <div class="list-group list-group-flush">
                    <a href="detail_artikel.php?id=1" class="list-group-item list-group-item-action d-flex gap-3 py-3 <?php echo ($id==1)?'active':''; ?>" aria-current="true">
                        <i class="bi bi-book fs-4 flex-shrink-0"></i>
                        <div class="d-flex gap-2 w-100 justify-content-between">
                            <div>
                                <h6 class="mb-0">Mengelola Serangan Panik</h6>
                                <p class="mb-0 opacity-75 small">Tips praktis 4-7-8.</p>
                            </div>
                        </div>
                    </a>
                    <a href="detail_artikel.php?id=2" class="list-group-item list-group-item-action d-flex gap-3 py-3 <?php echo ($id==2)?'active':''; ?>" aria-current="true">
                        <i class="bi bi-play-circle fs-4 flex-shrink-0"></i>
                        <div class="d-flex gap-2 w-100 justify-content-between">
                            <div>
                                <h6 class="mb-0">Meditasi Pagi</h6>
                                <p class="mb-0 opacity-75 small">Video panduan 10 menit.</p>
                            </div>
                        </div>
                    </a>
                    <a href="detail_artikel.php?id=3" class="list-group-item list-group-item-action d-flex gap-3 py-3 <?php echo ($id==3)?'active':''; ?>" aria-current="true">
                        <i class="bi bi-heart fs-4 flex-shrink-0"></i>
                        <div class="d-flex gap-2 w-100 justify-content-between">
                            <div>
                                <h6 class="mb-0">Kisah Nyata: Berdamai</h6>
                                <p class="mb-0 opacity-75 small">Inspirasi penyembuhan.</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="mt-4 d-grid">
                    <a href="artikel.php" class="btn btn-outline-dark btn-sm py-2">
                        Lihat Indeks Lengkap <i class="bi bi-grid ms-2"></i>
                    </a>
                </div>

                <div class="card bg-primary text-white mt-4 text-center p-3 border-0">
                    <div class="card-body">
                        <h5 class="card-title">Butuh teman cerita?</h5>
                        <p class="card-text small">Konselor kami siap mendengarkan masalahmu tanpa menghakimi.</p>
                        <a href="konselor.php" class="btn btn-light text-primary fw-bold btn-sm">Cari Konselor</a>
                    </div>
                </div>
            </div>
        </div>

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
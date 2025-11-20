<?php 
session_start(); 
include 'koneksi.php'; // WAJIB ADA
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
      <div class="d-flex align-items-center gap-3">
        <?php if (isset($_SESSION['status']) && $_SESSION['status'] == 'login'): ?>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                    <span class="fw-bold text-primary me-2 d-none d-md-block"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['username']); ?>&background=0D6EFD&color=fff" class="rounded-circle" width="35">
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="profil.php">Profil</a></li>
                    <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                </ul>
            </div>
        <?php else: ?>
            <a href="login.html" class="btn btn-outline-primary">Masuk</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<header class="bg-dark text-white py-5">
    <div class="container text-center">
        <h1 class="fw-bold display-5">Wawasan & Inspirasi</h1>
        <p class="lead opacity-75">Kumpulan konten terbaru yang dikurasi oleh tim ahli kami.</p>
    </div>
</header>

<div class="container my-5">
    <div class="row g-4">
        
        <?php 
        // 1. QUERY KE DATABASE (Mengambil data yang diinput Admin)
        $query = "SELECT * FROM edukasi ORDER BY id DESC";
        $result = mysqli_query($conn, $query);

        if(mysqli_num_rows($result) > 0):
            while($row = mysqli_fetch_assoc($result)):
                
                // Logika Warna Badge Berdasarkan Kategori
                $kategori = $row['kategori'];
                $bg_color = 'primary'; // Default Biru (Artikel)
                if ($kategori == 'Video') $bg_color = 'danger'; // Merah
                if ($kategori == 'Kisah Nyata') $bg_color = 'success'; // Hijau

                // Buat Deskripsi Singkat (Potong teks isi konten)
                // strip_tags: Menghilangkan tag HTML (<p>, <br>) biar rapi
                $deskripsi_pendek = substr(strip_tags($row['isi_konten']), 0, 100) . '...';
        ?>
        
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm hover-card overflow-hidden">
                <div class="position-relative">
                    <img src="<?php echo $row['gambar_url']; ?>" class="card-img-top" alt="Thumbnail" style="height: 200px; object-fit: cover;">
                    
                    <span class="position-absolute top-0 start-0 m-3 badge bg-<?php echo $bg_color; ?> shadow-sm">
                        <?php echo $kategori; ?>
                    </span>
                </div>
                
                <div class="card-body p-4 d-flex flex-column">
                    <h5 class="card-title fw-bold mb-3">
                        <a href="detail_artikel.php?id=<?php echo $row['id']; ?>" class="text-dark text-decoration-none stretched-link">
                            <?php echo $row['judul']; ?>
                        </a>
                    </h5>
                    
                    <p class="card-text text-muted small mb-4 flex-grow-1">
                        <?php echo $deskripsi_pendek; ?>
                    </p>
                    
                    <div class="mt-auto d-flex justify-content-between align-items-center text-muted small">
                        <span><i class="bi bi-person me-1"></i> <?php echo $row['penulis']; ?></span>
                        <span><?php echo date('d M Y', strtotime($row['tanggal_upload'])); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <?php endwhile; else: ?>
            <div class="col-12 text-center py-5">
                <h4 class="text-muted">Belum ada konten edukasi saat ini.</h4>
            </div>
        <?php endif; ?>

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
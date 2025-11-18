<?php 
session_start(); 
include 'koneksi.php'; 
?>
<?php include 'components/modal_jadwal.php'; ?>

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
                <span class="fw-bold text-primary d-block">Halo, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <small class="text-muted" style="font-size: 0.75rem;"><?php echo ucfirst($_SESSION['role']); ?></small>
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
        // QUERY: Ambil 10 Konselor saja
        $query = "SELECT * FROM users WHERE role = 'konselor' AND status = 'active' LIMIT 8";
        $result = mysqli_query($conn, $query);

        // Variabel bantu untuk memutar gambar (agar gambar 1-4 dipakai bergantian)
        $img_counter = 1; 

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                
                // Logika pilih gambar: dokter1.jpg s/d dokter4.jpg
                $gambar = "img/dokter" . $img_counter . ".jpg";
                $img_counter++;
                if ($img_counter > 4) { $img_counter = 1; } // Reset balik ke 1 jika sudah lewat 4

                // Handle Spesialisasi kosong
                $spesialis = !empty($row['spesialisasi']) ? $row['spesialisasi'] : 'Psikolog Umum';
                ?>
                
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm hover-card">
                        <img src="<?php echo $gambar; ?>" class="card-img-top" alt="<?php echo $row['nama']; ?>" style="height: 220px; object-fit: cover;">
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold text-dark"><?php echo $row['nama']; ?></h5>
                            <span class="badge bg-primary-subtle text-primary mb-2 w-auto align-self-start">
                                <?php echo $spesialis; ?>
                            </span>
                            
                            <p class="card-text text-muted small flex-grow-1">
                                <?php 
                                // Tampilkan potongan pengalaman (jika ada)
                                echo !empty($row['pengalaman']) 
                                    ? substr($row['pengalaman'], 0, 60) . '...' 
                                    : 'Siap membantu Anda pulih dari masalah kesehatan mental.'; 
                                ?>
                            </p>
                            
                            <div class="d-grid mt-3">
                                <button class="btn btn-outline-primary btn-sm">Lihat Profil</button>
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
    
    <div class="row mt-5">
        <div class="col d-flex justify-content-center">
            <nav>
              <ul class="pagination">
                <li class="page-item disabled"><a class="page-link">Previous</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
              </ul>
            </nav>
        </div>
    </div>
</div>

<footer class="bg-dark text-light py-4 mt-auto">
    <div class="container text-center">
        <p class="small text-white-50 mb-0">&copy; 2024 Stark Hope Indonesia.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>
</body>
</html>
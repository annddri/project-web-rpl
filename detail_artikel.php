<?php 
session_start(); 
include 'koneksi.php'; 

$id_artikel = isset($_GET['id']) ? $_GET['id'] : 0;

$query = "SELECT * FROM edukasi WHERE id = '$id_artikel'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header("location: artikel.php");
    exit;
}
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

<nav class="navbar navbar-expand-lg bg-white sticky-top shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold text-primary" href="index.php">Stark Hope</a>
    <div class="ms-auto">
        <a href="artikel.php" class="btn btn-outline-secondary btn-sm">Kembali ke Daftar</a>
    </div>
  </div>
</nav>

<header class="position-relative" style="height: 400px; overflow: hidden;">
    <img src="<?php echo $data['gambar_url']; ?>" class="w-100 h-100" style="object-fit: cover; filter: brightness(0.4);" alt="Cover">
    <div class="position-absolute top-50 start-50 translate-middle text-center text-white w-75">
        <span class="badge bg-primary mb-2"><?php echo $data['kategori']; ?></span>
        <h1 class="fw-bold display-5"><?php echo $data['judul']; ?></h1>
        <p class="mt-3">
            <i class="bi bi-person-circle me-1"></i> <?php echo $data['penulis']; ?> &nbsp;|&nbsp; 
            <i class="bi bi-calendar3 me-1"></i> <?php echo date('d F Y', strtotime($data['tanggal_upload'])); ?>
        </p>
    </div>
</header>

<div class="container my-5">
    <div class="row justify-content-center">
        
        <div class="col-lg-8">
            <div class="bg-white p-4 p-md-5 rounded shadow-sm">
                <div class="article-content" style="line-height: 1.8; color: #333;">
                    <?php echo $data['isi_konten']; ?>
                </div>
            </div>

            <div class="mt-5 text-center">
                <p class="text-muted small fw-bold">BAGIKAN</p>
                <button class="btn btn-outline-primary btn-sm rounded-circle mx-1"><i class="bi bi-whatsapp"></i></button>
                <button class="btn btn-outline-primary btn-sm rounded-circle mx-1"><i class="bi bi-twitter-x"></i></button>
                <button class="btn btn-outline-primary btn-sm rounded-circle mx-1"><i class="bi bi-facebook"></i></button>
            </div>
        </div>

        <div class="col-lg-4 mt-5 mt-lg-0">
            <div class="sticky-top" style="top: 100px;">
                <h5 class="fw-bold mb-3">Artikel Terbaru Lainnya</h5>
                <div class="list-group list-group-flush">
                    <?php
                    $q_lain = mysqli_query($conn, "SELECT * FROM edukasi WHERE id != '$id_artikel' ORDER BY id DESC LIMIT 3");
                    while($lain = mysqli_fetch_assoc($q_lain)):
                    ?>
                    <a href="detail_artikel.php?id=<?php echo $lain['id']; ?>" class="list-group-item list-group-item-action d-flex gap-3 py-3">
                        <img src="<?php echo $lain['gambar_url']; ?>" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                        <div>
                            <h6 class="mb-1 small fw-bold"><?php echo $lain['judul']; ?></h6>
                            <small class="text-muted"><?php echo date('d M', strtotime($lain['tanggal_upload'])); ?></small>
                        </div>
                    </a>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<footer class="bg-dark text-light py-4 mt-auto text-center">
    <div class="container">
        <p class="small text-white-50 mb-0">&copy; 2025 Stark Hope Indonesia.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>
</body>
</html>
<?php 
session_start(); 
include 'koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    header("location: login.php");
    exit;
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
$nama_user = $_SESSION['nama'];

if ($role == 'konselor' && isset($_GET['aksi']) && $_GET['aksi'] == 'selesai' && isset($_GET['id'])) {
    $id_konsul = $_GET['id'];
    mysqli_query($conn, "UPDATE konsultasi SET status='Selesai' WHERE id='$id_konsul'");
    echo "<script>alert('Sesi selesai.'); window.location.href='jadwal_konsultasi.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Konsultasi - Stark Hope</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

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
        <li class="nav-item"><a class="nav-link" href="konselor.php">Cari Konselor</a></li>
        <li class="nav-item"><a class="nav-link" href="artikel.php">Pusat Edukasi</a></li>
        <?php if (isset($_SESSION['status']) && $_SESSION['status'] == 'login'): ?>
            <li class="nav-item"><a class="nav-link active" href="#">Jadwal Konsultasi</a></li>
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

<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">Jadwal Konsultasi</h2>
            <p class="text-muted">
                <?php echo ($role == 'konselor') ? 'Daftar pasien yang harus Anda tangani.' : 'Riwayat sesi pertemuan Anda.'; ?>
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            
            <?php
            if ($role == 'konselor') {
                $query = "SELECT k.*, 
                                 u.nama AS nama_pasien, 
                                 u.email, 
                                 u.jenis_kelamin, 
                                 u.tanggal_lahir, 
                                 u.alamat
                          FROM konsultasi k 
                          JOIN users u ON k.user_id = u.user_id 
                          WHERE k.konselor_nama = '$nama_user' 
                          ORDER BY k.id DESC";
            } else {
                $query = "SELECT * FROM konsultasi WHERE user_id = '$user_id' ORDER BY id DESC";
            }

            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    if ($role == 'konselor') {
                        $label_lawan = "Pasien";
                        $nama_lawan  = $row['nama_pasien']; 
                    } else {
                        $label_lawan = "Konselor";
                        $nama_lawan  = $row['konselor_nama'];
                    }
            ?>
                <div class="card border-0 shadow-sm mb-3 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="row g-0 align-items-center">
                            
                            <div class="col-1 <?php echo ($role=='konselor')?'bg-warning':'bg-primary'; ?> d-flex align-items-center justify-content-center py-4 py-md-0" style="min-height: 100px;">
                                <i class="bi bi-calendar-event text-white fs-3"></i>
                            </div>
                            
                            <div class="col-md-8 p-4">
                                <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">
                                    <?php echo $label_lawan; ?>
                                </small>
                                <h5 class="fw-bold mb-1"><?php echo $nama_lawan; ?></h5>
                                
                                <div class="d-flex gap-3 mt-2 text-secondary small">
                                    <span><i class="bi bi-clock me-1"></i> <?php echo $row['hari']; ?>, <?php echo $row['jam']; ?></span>
                                    <?php if(!empty($row['link_meet'])): ?>
                                        <span class="text-primary"><i class="bi bi-link-45deg me-1"></i>Link Tersedia</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-md-3 p-4 text-md-end bg-light h-100 d-flex flex-column justify-content-center">
                                <?php 
                                    if ($row['status'] == 'Terjadwal') {
                                        $badge_class = 'bg-success';
                                    } elseif ($row['status'] == 'Selesai') {
                                        $badge_class = 'bg-secondary'; 
                                    } else {
                                        $badge_class = 'bg-danger'; 
                                    }
                                ?>
                            <span class="badge <?php echo ($row['status']=='Terjadwal')?'bg-success':'bg-secondary'; ?> mb-3 align-self-md-end w-auto">
                                    <?php echo $row['status']; ?>
                                </span>

                                <?php 
                                if ($row['status'] == 'Selesai'): ?>
                                    <button class="btn btn-secondary btn-sm mb-2" disabled>Sesi Berakhir</button>
                                <?php elseif (!empty($row['link_meet'])): ?>
                                    <a href="<?php echo $row['link_meet']; ?>" target="_blank" class="btn btn-primary btn-sm shadow-sm mb-2">
                                        <i class="bi bi-camera-video-fill me-2"></i>Masuk Room
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-sm mb-2" disabled>Menunggu Link</button>
                                <?php endif; ?>

                                <?php 
                                
                                if ($role == 'konselor' && $row['status'] == 'Terjadwal'): 
                                ?>
                                    <a href="jadwal_konsultasi.php?aksi=selesai&id=<?php echo $row['id']; ?>" 
                                    class="btn btn-outline-success btn-sm"
                                    onclick="return confirm('Tandai sesi ini sebagai selesai?')">
                                        <i class="bi bi-check-lg me-1"></i> Tandai Selesai
                                    </a>
                                <?php endif; ?>


                                <?php if ($role == 'konselor'): ?>
                                <?php
                                $kode_jk = $row['jenis_kelamin'];
                                
                                if ($kode_jk == 'L') {
                                    $p_gender = 'Laki-laki';
                                } elseif ($kode_jk == 'P') {
                                    $p_gender = 'Perempuan';
                                } elseif ($kode_jk == 'T') {
                                    $p_gender = 'Tidak ingin memberi tahu';
                                } else {
                                    $p_gender = '-'; 
                                }

                                $p_lahir = !empty($row['tanggal_lahir']) ? date('d M Y', strtotime($row['tanggal_lahir'])) : '-';
                                
                                $p_alamat = !empty($row['alamat']) ? $row['alamat'] : '-';
                            ?>

                                <div class="d-flex align-items-center">
                                    <h5 class="fw-bold mb-1 me-2"><?php echo $nama_lawan; ?></h5>
                                    
                                    <a href="#" class="text-primary" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalPasien"
                                    data-id="<?php echo $row['user_id']; ?>"
                                    data-nama="<?php echo $row['nama_pasien']; ?>"
                                    data-email="<?php echo $row['email']; ?>"
                                    data-gender="<?php echo $p_gender; ?>"
                                    data-lahir="<?php echo $p_lahir; ?>"
                                    data-alamat="<?php echo $p_alamat; ?>"
                                    title="Lihat Profil Pasien">
                                    <i class="bi bi-info-circle-fill"></i>
                                    </a>
                                </div>

                            <?php else: ?>
                                <h5 class="fw-bold mb-1"><?php echo $nama_lawan; ?></h5>
                            <?php endif; ?>

                            </div>

                        </div>
                    </div>
                </div>

            <?php 
                }
            } else {
                echo '<div class="alert alert-info text-center">Belum ada jadwal konsultasi saat ini.</div>';
            }
            ?>

        </div>
    </div>
</div>

<footer class="bg-dark text-light py-4 mt-auto text-center">
    <div class="container">
        <p class="small text-white-50 mb-0">&copy; 2025 Stark Hope Indonesia.</p>
    </div>
</footer>

<?php include 'components/modal_pasien.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>
</body>
</html>
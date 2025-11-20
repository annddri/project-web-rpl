<?php 
session_start(); 
include 'koneksi.php';

// 1. CEK LOGIN
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    header("location: login.html");
    exit;
}

$role = $_SESSION['role']; // 'pasien' atau 'konselor'
$user_id = $_SESSION['user_id'];
$nama_user = $_SESSION['nama'];

// 2. LOGIKA AKSI (KHUSUS KONSELOR: UPDATE STATUS)
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

<nav class="navbar navbar-expand-lg <?php echo ($role=='konselor') ? 'navbar-dark bg-primary' : 'navbar-light bg-white shadow-sm'; ?> mb-4">
  <div class="container">
    
    <a class="navbar-brand fw-bold" href="<?php echo ($role=='konselor') ? 'profil_konselor.php' : 'index.php'; ?>">
        <?php echo ($role=='konselor') ? '<i class="bi bi-hospital-fill me-2"></i>Panel Konselor' : 'Stark Hope'; ?>
    </a>
    
    <div class="d-flex align-items-center gap-3">
        
        <span class="<?php echo ($role=='konselor') ? 'text-white' : 'text-muted'; ?> small d-none d-md-block">
            Halo, <?php echo htmlspecialchars($nama_user); ?> 
        </span>

        <?php if($role == 'pasien'): ?>
            <a href="index.php" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-house-door-fill"></i> Beranda
            </a>
        <?php else: ?>
            <a href="index.php" class="btn btn-outline-light btn-sm">
                <i class="bi bi-house-door-fill"></i> Beranda
            </a>

            <a href="profil_konselor.php" class="btn btn-outline-light btn-sm">
                <i class="bi bi-person-circle me-1"></i> Profil Saya
            </a>
            <a href="logout.php" class="btn btn-danger btn-sm">
                <i class="bi bi-power"></i>
            </a>
        <?php endif; ?>
        
    </div>
  </div>
</nav>

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
            // 3. QUERY DINAMIS BERDASARKAN ROLE
            // ...
            if ($role == 'konselor') {
                // UPDATE QUERY: Tambahkan data detail user (u.email, u.jenis_kelamin, dll)
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
                // JIKA PASIEN: Ambil berdasarkan user_id sendiri
                $query = "SELECT * FROM konsultasi WHERE user_id = '$user_id' ORDER BY id DESC";
            }

            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    
                    // Tentukan siapa "Lawan Bicara"
                    if ($role == 'konselor') {
                        $label_lawan = "Pasien";
                        $nama_lawan  = $row['nama_pasien']; // Dari hasil JOIN
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
        // Tentukan warna badge
        if ($row['status'] == 'Terjadwal') {
            $badge_class = 'bg-success';
        } elseif ($row['status'] == 'Selesai') {
            $badge_class = 'bg-secondary'; // Abu-abu jika selesai
        } else {
            $badge_class = 'bg-danger'; // Dibatalkan
        }
    ?>
<span class="badge <?php echo ($row['status']=='Terjadwal')?'bg-success':'bg-secondary'; ?> mb-3 align-self-md-end w-auto">
        <?php echo $row['status']; ?>
    </span>

    <?php 
    // 1. JIKA STATUS SUDAH SELESAI
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
    // Debugging: Pastikan role benar
    // var_dump($role, $row['status']); 
    
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
    // LOGIKA LAMA (SALAH):
    // $p_gender = !empty($row['jenis_kelamin']) ? (($row['jenis_kelamin']=='L')?'Laki-laki':'Perempuan') : '-';

    // LOGIKA BARU (BENAR):
    $kode_jk = $row['jenis_kelamin'];
    
    if ($kode_jk == 'L') {
        $p_gender = 'Laki-laki';
    } elseif ($kode_jk == 'P') {
        $p_gender = 'Perempuan';
    } elseif ($kode_jk == 'T') {
        $p_gender = 'Tidak ingin memberi tahu';
    } else {
        $p_gender = '-'; // Jika data kosong
    }

    // Format Tanggal Lahir
    $p_lahir = !empty($row['tanggal_lahir']) ? date('d M Y', strtotime($row['tanggal_lahir'])) : '-';
    
    // Alamat
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

<?php include 'components/modal_pasien.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>
</body>
</html>
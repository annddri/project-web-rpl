<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['role'] != 'konselor') {
    header("location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// --- LOGIKA 1: SIMPAN PROFIL ---
if (isset($_POST['simpan_profil'])) {
    $nama         = mysqli_real_escape_string($conn, $_POST['nama']);
    $spesialisasi = mysqli_real_escape_string($conn, $_POST['spesialisasi']);
    $pendidikan   = mysqli_real_escape_string($conn, $_POST['pendidikan']);
    $str          = mysqli_real_escape_string($conn, $_POST['str']);
    $metode       = mysqli_real_escape_string($conn, $_POST['metode']);
    $bahasa       = mysqli_real_escape_string($conn, $_POST['bahasa']);
    $tentang      = mysqli_real_escape_string($conn, $_POST['tentang']);

    $q_user = "UPDATE users SET nama = '$nama', spesialisasi = '$spesialisasi' WHERE user_id = '$user_id'";
    mysqli_query($conn, $q_user);
    $_SESSION['nama'] = $nama; 

    $cek_profil = mysqli_query($conn, "SELECT * FROM konselor_profil WHERE user_id = '$user_id'");
    if (mysqli_num_rows($cek_profil) > 0) {
        $q_profil = "UPDATE konselor_profil SET pendidikan='$pendidikan', nomor_str='$str', metode_terapi='$metode', bahasa='$bahasa', tentang_saya='$tentang' WHERE user_id='$user_id'";
    } else {
        $q_profil = "INSERT INTO konselor_profil (user_id, pendidikan, nomor_str, metode_terapi, bahasa, tentang_saya) VALUES ('$user_id', '$pendidikan', '$str', '$metode', '$bahasa', '$tentang')";
    }
    mysqli_query($conn, $q_profil);
    echo "<script>alert('Profil diperbarui!'); window.location.href='profil_konselor.php';</script>";
}

// --- LOGIKA 2: TAMBAH JADWAL (DENGAN HARGA) ---
if (isset($_POST['tambah_jadwal'])) {
    $hari  = $_POST['hari'];
    $mulai = $_POST['jam_mulai']; 
    $akhir = $_POST['jam_selesai']; 
    $harga = $_POST['harga']; // Tangkap Harga

    $q_tambah = "INSERT INTO jadwal_praktik (user_id, hari, jam_mulai, jam_selesai, harga) 
                 VALUES ('$user_id', '$hari', '$mulai', '$akhir', '$harga')";
    mysqli_query($conn, $q_tambah);
    echo "<script>alert('Jadwal ditambahkan!'); window.location.href='profil_konselor.php';</script>";
}

// --- LOGIKA 3: HAPUS JADWAL ---
if (isset($_GET['hapus_jadwal'])) {
    $id_jadwal = $_GET['hapus_jadwal'];
    mysqli_query($conn, "DELETE FROM jadwal_praktik WHERE id = '$id_jadwal' AND user_id = '$user_id'");
    echo "<script>alert('Jadwal dihapus.'); window.location.href='profil_konselor.php';</script>";
}

// AMBIL DATA
$q_data = "SELECT u.*, kp.* FROM users u LEFT JOIN konselor_profil kp ON u.user_id = kp.user_id WHERE u.user_id = '$user_id'";
$res_data = mysqli_query($conn, $q_data);
$d = mysqli_fetch_assoc($res_data);
$tampil_spesialis = !empty($d['spesialisasi']) ? $d['spesialisasi'] : 'Psikolog Umum';

$q_jadwal = "SELECT * FROM jadwal_praktik WHERE user_id = '$user_id' ORDER BY FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'), jam_mulai";
$res_jadwal = mysqli_query($conn, $q_jadwal);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Profil - Stark Hope</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg bg-primary navbar-dark shadow-sm mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="profil_konselor.php"><i class="bi bi-hospital-fill me-2"></i>Panel Konselor</a>
    <div class="d-flex gap-2">
        <a href="index.php" class="btn btn-primary btn-sm text-white-50"><i class="bi bi-globe"></i> Web Utama</a>
        <a href="jadwal_konsultasi.php" class="btn btn-outline-light btn-sm"><i class="bi bi-calendar-check me-1"></i> Lihat Pasien</a>
        <a href="logout.php" class="btn btn-danger btn-sm"><i class="bi bi-box-arrow-right"></i></a>
    </div>
  </div>
</nav>

<div class="container mb-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm text-center p-4">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($d['nama']); ?>&background=random&color=fff&size=150" class="rounded-circle shadow-sm mx-auto d-block mb-3">
                <h5 class="fw-bold mb-1"><?php echo $d['nama']; ?></h5>
                <div class="badge bg-primary-subtle text-primary mb-2"><?php echo $tampil_spesialis; ?></div>
                <p class="text-muted small mb-3"><?php echo $d['email']; ?></p>
                <div class="text-start border-top pt-3">
                    <div class="mb-2"><small class="text-muted fw-bold d-block" style="font-size: 10px;">PENDIDIKAN</small><span class="small"><?php echo !empty($d['pendidikan']) ? $d['pendidikan'] : '-'; ?></span></div>
                    <div class="mb-2"><small class="text-muted fw-bold d-block" style="font-size: 10px;">NO. STR</small><span class="small"><?php echo !empty($d['nomor_str']) ? $d['nomor_str'] : '-'; ?></span></div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                        <li class="nav-item"><button class="nav-link active" id="profil-tab" data-bs-toggle="tab" data-bs-target="#profil-pane" type="button">Edit Data Diri</button></li>
                        <li class="nav-item"><button class="nav-link" id="jadwal-tab" data-bs-toggle="tab" data-bs-target="#jadwal-pane" type="button">Atur Jadwal Praktik</button></li>
                    </ul>
                </div>
                
                <div class="card-body p-4">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="profil-pane">
                            <form method="POST">
                                <div class="mb-3"><label class="form-label fw-bold small">Nama Lengkap & Gelar</label><input type="text" name="nama" class="form-control" value="<?php echo $d['nama']; ?>" required></div>
                                <div class="mb-3"><label class="form-label fw-bold small text-primary">Spesialisasi / Jabatan</label><input type="text" name="spesialisasi" class="form-control border-primary" value="<?php echo $tampil_spesialis; ?>"></div>
                                <div class="row mb-3">
                                    <div class="col-md-6"><label class="form-label fw-bold small">Pendidikan</label><input type="text" name="pendidikan" class="form-control" value="<?php echo $d['pendidikan']; ?>"></div>
                                    <div class="col-md-6"><label class="form-label fw-bold small">Nomor STR</label><input type="text" name="str" class="form-control" value="<?php echo $d['nomor_str']; ?>"></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6"><label class="form-label fw-bold small">Metode</label><input type="text" name="metode" class="form-control" value="<?php echo $d['metode_terapi']; ?>"></div>
                                    <div class="col-md-6"><label class="form-label fw-bold small">Bahasa</label><input type="text" name="bahasa" class="form-control" value="<?php echo $d['bahasa']; ?>"></div>
                                </div>
                                <div class="mb-3"><label class="form-label fw-bold small">Tentang Saya</label><textarea name="tentang" class="form-control" rows="4"><?php echo $d['tentang_saya']; ?></textarea></div>
                                <div class="d-flex justify-content-end"><button type="submit" name="simpan_profil" class="btn btn-primary px-4">Simpan Perubahan</button></div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="jadwal-pane">
                            <div class="bg-light p-3 rounded mb-4 border">
                                <h6 class="fw-bold mb-3">Tambah Jadwal Baru</h6>
                                <form method="POST" class="row g-2 align-items-end">
                                    <div class="col-md-3">
                                        <label class="small fw-bold">Hari</label>
                                        <select name="hari" class="form-select form-select-sm" required>
                                            <option value="Senin">Senin</option>
                                            <option value="Selasa">Selasa</option>
                                            <option value="Rabu">Rabu</option>
                                            <option value="Kamis">Kamis</option>
                                            <option value="Jumat">Jumat</option>
                                            <option value="Sabtu">Sabtu</option>
                                            <option value="Minggu">Minggu</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2"> <label class="small fw-bold">Mulai</label>
                                        <input type="time" name="jam_mulai" class="form-control form-control-sm" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="small fw-bold">Selesai</label>
                                        <input type="time" name="jam_selesai" class="form-control form-control-sm" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small fw-bold">Harga (Rp)</label>
                                        <input type="number" name="harga" class="form-control form-control-sm" placeholder="150000" required>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" name="tambah_jadwal" class="btn btn-success btn-sm w-100"><i class="bi bi-plus-circle"></i></button>
                                    </div>
                                </form>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover small align-middle">
                                    <thead class="table-secondary">
                                        <tr>
                                            <th>Hari</th>
                                            <th>Jam</th>
                                            <th>Harga</th> <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (mysqli_num_rows($res_jadwal) > 0): ?>
                                            <?php while($jadwal = mysqli_fetch_assoc($res_jadwal)): ?>
                                            <tr>
                                                <td class="fw-bold"><?php echo $jadwal['hari']; ?></td>
                                                <td><?php echo $jadwal['jam_mulai'] . ' - ' . $jadwal['jam_selesai']; ?></td>
                                                <td>Rp <?php echo number_format($jadwal['harga'], 0, ',', '.'); ?></td>
                                                <td class="text-center">
                                                    <a href="profil_konselor.php?hapus_jadwal=<?php echo $jadwal['id']; ?>" class="btn btn-danger btn-sm py-0" onclick="return confirm('Hapus jadwal ini?')"><i class="bi bi-trash"></i></a>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr><td colspan="4" class="text-center text-muted">Belum ada jadwal.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
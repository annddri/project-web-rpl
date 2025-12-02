<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['role'] != 'konselor') {
    header("location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];




// 2. LOGIC SIMPAN PROFIL
if (isset($_POST['simpan_profil'])) {
    // Ambil data input
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $spesialisasi = mysqli_real_escape_string($conn, $_POST['spesialisasi']);
    $pendidikan = mysqli_real_escape_string($conn, $_POST['pendidikan']);
    $str = mysqli_real_escape_string($conn, $_POST['str']); // Pastikan name di HTML adalah "str"
    $metode = mysqli_real_escape_string($conn, $_POST['metode']);
    $bahasa = mysqli_real_escape_string($conn, $_POST['bahasa']);
    $tentang = mysqli_real_escape_string($conn, $_POST['tentang']);

    // --- PROSES GAMBAR (DILAKUKAN DI AWAL) ---
    $foto_sql_update = ""; // Potongan query untuk UPDATE
    $foto_col_insert = ""; // Potongan kolom untuk INSERT
    $foto_val_insert = ""; // Potongan value untuk INSERT
    
    if (!empty($_FILES['foto']['name'])) {
        $foto_name = $_FILES['foto']['name'];
        $foto_tmp  = $_FILES['foto']['tmp_name'];
        $ext       = pathinfo($foto_name, PATHINFO_EXTENSION);
        $foto_baru = "konselor_" . $user_id . "_" . time() . "." . $ext;
        $target_dir = "img/";

        // Cek folder
        if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

        if (move_uploaded_file($foto_tmp, $target_dir . $foto_baru)) {
            // Jika upload sukses, siapkan string SQL-nya
            $foto_sql_update = ", foto='$foto_baru'";
            $foto_col_insert = ", foto";
            $foto_val_insert = ", '$foto_baru'";
        }
    }

    // --- CEK STATUS PENGAJUAN ---
    $cek_pending = mysqli_query($conn, "SELECT * FROM pengajuan_profil WHERE user_id='$user_id' AND status='Pending'");
    
    if (mysqli_num_rows($cek_pending) > 0) {
        // SKENARIO UPDATE: Update data yang sudah ada (Pending)
        // Perhatikan variabel $foto_sql_update dimasukkan ke sini
        $query = "UPDATE pengajuan_profil SET 
                  nama_baru='$nama', 
                  spesialisasi_baru='$spesialisasi', 
                  pendidikan_baru='$pendidikan', 
                  str_baru='$str', 
                  metode_baru='$metode', 
                  bahasa_baru='$bahasa', 
                  tentang_baru='$tentang' 
                  $foto_sql_update 
                  WHERE user_id='$user_id' AND status='Pending'";
    } else {
        // SKENARIO INSERT: Buat pengajuan baru
        // Perhatikan variabel $foto_col_insert dan $foto_val_insert
        $query = "INSERT INTO pengajuan_profil 
                  (user_id, nama_baru, spesialisasi_baru, pendidikan_baru, str_baru, metode_baru, bahasa_baru, tentang_baru $foto_col_insert)
                  VALUES 
                  ('$user_id', '$nama', '$spesialisasi', '$pendidikan', '$str', '$metode', '$bahasa', '$tentang' $foto_val_insert)";
    }

    // --- EKSEKUSI QUERY ---
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Perubahan diajukan! Menunggu persetujuan Admin.'); window.location.href='profil_konselor.php';</script>";
    } else {
        echo "<script>alert('Gagal: " . mysqli_error($conn) . "');</script>";
    }
}



if (isset($_POST['tambah_jadwal'])) {
    $hari  = $_POST['hari'];
    $mulai = $_POST['jam_mulai']; 
    $akhir = $_POST['jam_selesai']; 
    $harga = $_POST['harga']; 

    $q_ajukan = "INSERT INTO pengajuan_jadwal (user_id, hari, jam_mulai, jam_selesai, harga, status) 
                 VALUES ('$user_id', '$hari', '$mulai', '$akhir', '$harga', 'Pending')";
                 
    if(mysqli_query($conn, $q_ajukan)) {
        echo "<script>alert('Jadwal berhasil diajukan! Menunggu persetujuan Admin.'); window.location.href='profil_konselor.php';</script>";
    } else {
        echo "<script>alert('Gagal mengajukan: " . mysqli_error($conn) . "');</script>";
    }
}

if (isset($_GET['hapus_jadwal'])) {
    $id_jadwal = $_GET['hapus_jadwal'];
    mysqli_query($conn, "DELETE FROM jadwal_praktik WHERE id = '$id_jadwal' AND user_id = '$user_id'");
    echo "<script>alert('Jadwal dihapus.'); window.location.href='profil_konselor.php';</script>";
}

if (isset($_GET['hapus_pengajuan'])) {
    $id_pengajuan = $_GET['hapus_pengajuan'];
    
    mysqli_query($conn, "DELETE FROM pengajuan_jadwal WHERE id = '$id_pengajuan' AND user_id = '$user_id'");
    
    echo "<script>alert('Riwayat pengajuan dihapus.'); window.location.href='profil_konselor.php';</script>";
}

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

    <?php
    // $xx_jadwal = mysqli_query($conn, "SELECT foto FROM konselor_profil WHERE user_id='$user_id'");
    

    // 2. CEK: Apakah datanya ada?
    // if (mysqli_num_rows($xx_jadwal) > 0) {
        
    //     while ($j = mysqli_fetch_assoc($xx_jadwal)) {
            
    //         $foto_db = $j['foto'];
    //     } 
    // }
    // ?>

<div class="container mb-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm text-center p-4">
                <img src="<?php 
                    if (!empty($d['foto']) && $d['foto'] != 'default.jpg' && file_exists('img/'.$d['foto'])) {
                        echo 'img/' . $d['foto'];
                    } else {
                        echo 'https://ui-avatars.com/api/?name=' . urlencode($d['nama']) . '&background=random&color=fff&size=150';
                    } 
                ?>" class="rounded-circle shadow-sm mx-auto d-block mb-3" style="width: 150px; height: 150px; object-fit: cover;">
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
                            <form method="POST" enctype="multipart/form-data">
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
                                <div class="mb-3"><label class="form-label fw-bold small">Ganti Foto Profil</label><input type="file" name="foto" class="form-control" value="<?php echo $d['foto']; ?>"></div>
                                <div class="d-flex justify-content-end"><button type="submit" name="simpan_profil" class="btn btn-primary px-4">Simpan Perubahan</button></div>
                            </form>
                        </div>

                    <?php
                    // Ambil status pengajuan terakhir
                    $q_status = mysqli_query($conn, "SELECT status, tanggal_request FROM pengajuan_profil WHERE user_id='$user_id' ORDER BY id DESC LIMIT 1");
                    $row_status = mysqli_fetch_assoc($q_status);

                    if ($row_status) {
                        $status = $row_status['status'];
                        $tanggal = date('d M Y', strtotime($row_status['tanggal_request']));

                        if ($status == 'Pending') {
                            echo '
                            <div class="alert alert-warning small mt-3 border-0 shadow-sm d-flex align-items-center">
                                <i class="bi bi-hourglass-split fs-4 me-3"></i>
                                <div>
                                    <strong>Menunggu Persetujuan Admin</strong><br>
                                    Pengajuan perubahan data Anda sedang ditinjau.
                                </div>
                            </div>';
                        } 
                        elseif ($status == 'Disetujui') {
                            echo '
                            <div class="alert alert-success small mt-3 border-0 shadow-sm d-flex align-items-center alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                                <div>
                                    <strong>Disetujui!</strong><br>
                                    Profil Anda telah diperbarui oleh Admin pada '.$tanggal.'.
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
                        } 
                        elseif ($status == 'Ditolak') {
                            echo '
                            <div class="alert alert-danger small mt-3 border-0 shadow-sm d-flex align-items-center">
                                <i class="bi bi-x-circle-fill fs-4 me-3"></i>
                                <div>
                                    <strong>Pengajuan Ditolak</strong><br>
                                    Mohon periksa kembali data Anda dan ajukan ulang jika diperlukan.
                                </div>
                            </div>';
                        }
                    }
                    ?>  

<div class="tab-pane fade" id="jadwal-pane">
                            
                            <div class="bg-light p-3 rounded mb-4 border">
                                <h6 class="fw-bold mb-3">Ajukan Jadwal Baru</h6>
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
                                    <div class="col-md-2">
                                        <label class="small fw-bold">Mulai</label>
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
                                        <button type="submit" name="tambah_jadwal" class="btn btn-warning btn-sm w-100 text-dark">
                                            <i class="bi bi-send"></i> Ajukan
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <h6 class="fw-bold text-primary">
                                <i class="bi bi-clock-history"></i> Riwayat Pengajuan Jadwal
                            </h6>
                            
                            <div class="table-responsive mb-4">
                                <table class="table table-sm table-bordered small align-middle bg-white">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Jadwal Diajukan</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center" width="10%">Aksi</th> </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $q_aj = mysqli_query($conn, "SELECT * FROM pengajuan_jadwal WHERE user_id='$user_id' ORDER BY id DESC");
                                        
                                        if (mysqli_num_rows($q_aj) > 0):
                                            while($aj = mysqli_fetch_assoc($q_aj)):
                                        ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo $aj['hari']; ?></strong>, 
                                                <?php echo $aj['jam_mulai'] . '-' . $aj['jam_selesai']; ?>
                                                <div class="text-muted" style="font-size: 11px;">
                                                    Rp <?php echo number_format($aj['harga']); ?>
                                                </div>
                                            </td>
                                            
                                            <td class="text-center">
                                                <?php if($aj['status']=='Pending'): ?>
                                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                                <?php elseif($aj['status']=='Disetujui'): ?>
                                                    <span class="badge bg-success">Disetujui</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Ditolak</span>
                                                <?php endif; ?>
                                            </td>
                                            
                                            <td class="text-center">
                                                <a href="profil_konselor.php?hapus_pengajuan=<?php echo $aj['id']; ?>" 
                                                   class="btn btn-outline-secondary btn-sm py-0 px-2"
                                                   onclick="return confirm('Hapus catatan riwayat ini? (Jadwal aktif tidak akan terhapus)')"
                                                   title="Bersihkan Riwayat">
                                                    <i class="bi bi-x-lg"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endwhile; else: ?>
                                            <tr><td colspan="3" class="text-center text-muted fst-italic">Belum ada riwayat pengajuan.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <h6 class="fw-bold text-success"><i class="bi bi-check-circle"></i> Jadwal Aktif (Tayang)</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover small align-middle">
                                    <thead class="table-secondary">
                                        <tr>
                                            <th>Hari</th>
                                            <th>Jam</th>
                                            <th>Harga</th>
                                            <th class="text-center">Aksi</th>
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
                                                    <a href="profil_konselor.php?hapus_jadwal=<?php echo $jadwal['id']; ?>" 
                                                       class="btn btn-danger btn-sm py-0"
                                                       onclick="return confirm('Hapus jadwal ini?')">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr><td colspan="4" class="text-center text-muted">Belum ada jadwal aktif.</td></tr>
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
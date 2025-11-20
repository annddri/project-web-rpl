<?php
session_start();
include 'koneksi.php';

// CEK AKSES ADMIN
if (!isset($_SESSION['status']) || $_SESSION['role'] != 'admin') {
    header("location: login.html");
    exit;
}

// --- LOGIKA CRUD USER ---
if (isset($_GET['hapus_user'])) {
    $uid = $_GET['hapus_user'];
    mysqli_query($conn, "DELETE FROM users WHERE user_id='$uid'");
    echo "<script>alert('User dihapus'); window.location.href='admin_panel.php';</script>";
}

// --- LOGIKA APPROVAL PROFIL ---
if (isset($_POST['approve_profil'])) {
    $id_pengajuan = $_POST['id_pengajuan'];
    
    // 1. Ambil Data Baru dari Pengajuan
    $q_ambil = mysqli_query($conn, "SELECT * FROM pengajuan_profil WHERE id='$id_pengajuan'");
    $data = mysqli_fetch_assoc($q_ambil);
    $uid = $data['user_id'];

    // 2. Update Tabel Users
    mysqli_query($conn, "UPDATE users SET nama='".$data['nama_baru']."', spesialisasi='".$data['spesialisasi_baru']."' WHERE user_id='$uid'");

    // 3. Update Tabel Konselor Profil (Cek dulu ada/nggak)
    $cek = mysqli_query($conn, "SELECT * FROM konselor_profil WHERE user_id='$uid'");
    if (mysqli_num_rows($cek) > 0) {
        $sql_prof = "UPDATE konselor_profil SET pendidikan='".$data['pendidikan_baru']."', nomor_str='".$data['str_baru']."', metode_terapi='".$data['metode_baru']."', bahasa='".$data['bahasa_baru']."', tentang_saya='".$data['tentang_baru']."' WHERE user_id='$uid'";
    } else {
        $sql_prof = "INSERT INTO konselor_profil (user_id, pendidikan, nomor_str, metode_terapi, bahasa, tentang_saya) VALUES ('$uid', '".$data['pendidikan_baru']."', '".$data['str_baru']."', '".$data['metode_baru']."', '".$data['bahasa_baru']."', '".$data['tentang_baru']."')";
    }
    mysqli_query($conn, $sql_prof);

    // 4. Set Status jadi Disetujui
    mysqli_query($conn, "UPDATE pengajuan_profil SET status='Disetujui' WHERE id='$id_pengajuan'");
    echo "<script>alert('Profil Disetujui & Diupdate!'); window.location.href='admin_panel.php';</script>";
}

// --- LOGIKA APPROVAL JADWAL (BARU) ---
if (isset($_GET['approve_jadwal'])) {
    $id_aj = $_GET['approve_jadwal'];
    
    // 1. Ambil data dari tabel pengajuan
    $q_data = mysqli_query($conn, "SELECT * FROM pengajuan_jadwal WHERE id='$id_aj'");
    $d = mysqli_fetch_assoc($q_data);
    
    // 2. Masukkan ke tabel jadwal_praktik (RESMI)
    $q_resmi = "INSERT INTO jadwal_praktik (user_id, hari, jam_mulai, jam_selesai, harga) 
                VALUES ('".$d['user_id']."', '".$d['hari']."', '".$d['jam_mulai']."', '".$d['jam_selesai']."', '".$d['harga']."')";
    mysqli_query($conn, $q_resmi);

    // 3. Update status pengajuan jadi Disetujui
    mysqli_query($conn, "UPDATE pengajuan_jadwal SET status='Disetujui' WHERE id='$id_aj'");
    
    echo "<script>alert('Jadwal Disetujui & Ditayangkan!'); window.location.href='admin_panel.php';</script>";
}

if (isset($_GET['reject_jadwal'])) {
    $id_aj = $_GET['reject_jadwal'];
    mysqli_query($conn, "UPDATE pengajuan_jadwal SET status='Ditolak' WHERE id='$id_aj'");
    echo "<script>alert('Jadwal Ditolak.'); window.location.href='admin_panel.php';</script>";
}

if (isset($_GET['reject_profil'])) {
    $id = $_GET['reject_profil'];
    mysqli_query($conn, "UPDATE pengajuan_profil SET status='Ditolak' WHERE id='$id'");
    echo "<script>alert('Pengajuan Ditolak.'); window.location.href='admin_panel.php';</script>";
}

// --- LOGIKA EDUKASI ---
if (isset($_POST['tambah_edukasi'])) {
    $judul = $_POST['judul'];
    $kategori = $_POST['kategori'];
    $penulis = $_POST['penulis'];
    $gambar = $_POST['gambar'];
    $isi = mysqli_real_escape_string($conn, $_POST['isi']);
    
    mysqli_query($conn, "INSERT INTO edukasi (judul, kategori, penulis, gambar_url, isi_konten) VALUES ('$judul', '$kategori', '$penulis', '$gambar', '$isi')");
    echo "<script>alert('Konten ditambahkan!'); window.location.href='admin_panel.php';</script>";
}
if (isset($_GET['hapus_edukasi'])) {
    $id = $_GET['hapus_edukasi'];
    mysqli_query($conn, "DELETE FROM edukasi WHERE id='$id'");
    echo "<script>alert('Konten dihapus.'); window.location.href='admin_panel.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Stark Hope</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark shadow-sm mb-4">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold" href="#">ADMINISTRATOR</a>
    <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
  </div>
</nav>

<div class="container-fluid px-4">
    
    <ul class="nav nav-pills mb-4 bg-white p-3 rounded shadow-sm" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-users-tab" data-bs-toggle="pill" data-bs-target="#pills-users" type="button">1. Data Users</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link position-relative" id="pills-approval-tab" data-bs-toggle="pill" data-bs-target="#pills-approval" type="button">
                2. Approval Konselor
                <?php 
                $count_pending = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pengajuan_profil WHERE status='Pending'"));
                if($count_pending > 0) echo '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">'.$count_pending.'</span>';
                ?>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-edu-tab" data-bs-toggle="pill" data-bs-target="#pills-edu" type="button">3. Kelola Edukasi</button>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        
        <div class="tab-pane fade show active" id="pills-users">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">Master Data Users</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Tambahkan WHERE role != 'admin'
                                $q_users = mysqli_query($conn, "SELECT * FROM users WHERE role != 'admin' ORDER BY role");
                                while($u = mysqli_fetch_assoc($q_users)):
                                ?>
                                <tr>
                                    <td><?php echo $u['user_id']; ?></td>
                                    <td><?php echo $u['nama']; ?></td>
                                    <td><?php echo $u['email']; ?></td>
                                    <td><span class="badge <?php echo ($u['role']=='admin'?'bg-danger':($u['role']=='konselor'?'bg-primary':'bg-secondary')); ?>"><?php echo strtoupper($u['role']); ?></span></td>
                                    <td><?php echo $u['status']; ?></td>
                                    <td>
                                        <a href="admin_panel.php?hapus_user=<?php echo $u['user_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus user ini?')"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

<div class="tab-pane fade" id="pills-approval">
            
            <h5 class="fw-bold mb-3 text-primary">Pengajuan Profil Baru</h5>
            <div class="row mb-5">
                <?php
                $q_pending = mysqli_query($conn, "SELECT pp.*, u.nama as nama_asli FROM pengajuan_profil pp JOIN users u ON pp.user_id = u.user_id WHERE pp.status='Pending'");
                if(mysqli_num_rows($q_pending) > 0):
                    while($p = mysqli_fetch_assoc($q_pending)):
                ?>
                <div class="col-md-6 mb-3">
                    <div class="card border-warning shadow-sm">
                        <div class="card-body">
                            <h6><strong><?php echo $p['nama_asli']; ?></strong> ingin mengubah profil.</h6>
                            <ul class="small text-muted ps-3">
                                <li>Nama Baru: <?php echo $p['nama_baru']; ?></li>
                                <li>Spesialis: <?php echo $p['spesialisasi_baru']; ?></li>
                            </ul>
                            <form method="POST" class="d-flex gap-2 mt-2">
                                <input type="hidden" name="id_pengajuan" value="<?php echo $p['id']; ?>">
                                <button type="submit" name="approve_profil" class="btn btn-success btn-sm w-50">Setujui</button>
                                <a href="admin_panel.php?reject_profil=<?php echo $p['id']; ?>" class="btn btn-danger btn-sm w-50">Tolak</a>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endwhile; else: ?>
                    <div class="col-12"><div class="alert alert-light border">Tidak ada pengajuan profil.</div></div>
                <?php endif; ?>
            </div>
            
            <h5 class="fw-bold mb-3 text-success border-top pt-4">Pengajuan Jadwal Praktik</h5>
            <div class="row">
                <?php
                $q_jadwal = mysqli_query($conn, "SELECT pj.*, u.nama FROM pengajuan_jadwal pj JOIN users u ON pj.user_id = u.user_id WHERE pj.status='Pending'");
                if(mysqli_num_rows($q_jadwal) > 0):
                    while($j = mysqli_fetch_assoc($q_jadwal)):
                ?>
                <div class="col-md-4 mb-3">
                    <div class="card border-success shadow-sm">
                        <div class="card-header bg-success bg-opacity-10 fw-bold small">
                            <?php echo $j['nama']; ?>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title text-center fw-bold"><?php echo $j['hari']; ?></h5>
                            <p class="card-text text-center mb-1 fs-5">
                                <?php echo $j['jam_mulai']; ?> - <?php echo $j['jam_selesai']; ?>
                            </p>
                            <p class="text-center text-muted small mb-3">
                                Tarif: Rp <?php echo number_format($j['harga']); ?>
                            </p>
                            
                            <div class="d-flex gap-2">
                                <a href="admin_panel.php?approve_jadwal=<?php echo $j['id']; ?>" class="btn btn-success btn-sm w-100" onclick="return confirm('Setujui jadwal ini?')">Acc</a>
                                <a href="admin_panel.php?reject_jadwal=<?php echo $j['id']; ?>" class="btn btn-danger btn-sm w-100">Tolak</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; else: ?>
                    <div class="col-12"><div class="alert alert-light border">Tidak ada pengajuan jadwal.</div></div>
                <?php endif; ?>
            </div>

        </div>

        <div class="tab-pane fade" id="pills-edu">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Tambah Konten Baru</div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-2"><input type="text" name="judul" class="form-control" placeholder="Judul Artikel" required></div>
                            <div class="col-md-3 mb-2">
                                <select name="kategori" class="form-select">
                                    <option value="Artikel">Artikel</option>
                                    <option value="Video">Video</option>
                                    <option value="Kisah Nyata">Kisah Nyata</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-2"><input type="text" name="penulis" class="form-control" placeholder="Penulis" required></div>
                            <div class="col-md-12 mb-2"><input type="text" name="gambar" class="form-control" placeholder="URL Gambar (https://...)" required></div>
                            <div class="col-md-12 mb-2"><textarea name="isi" class="form-control" rows="3" placeholder="Isi Konten (Boleh HTML)" required></textarea></div>
                            <div class="col-md-12"><button type="submit" name="tambah_edukasi" class="btn btn-primary">Publish Konten</button></div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive bg-white p-3 rounded shadow-sm">
                <table class="table table-striped small">
                    <thead><tr><th>Judul</th><th>Kategori</th><th>Penulis</th><th>Aksi</th></tr></thead>
                    <tbody>
                        <?php
                        $q_edu = mysqli_query($conn, "SELECT * FROM edukasi ORDER BY id DESC");
                        while($e = mysqli_fetch_assoc($q_edu)):
                        ?>
                        <tr>
                            <td><?php echo $e['judul']; ?></td>
                            <td><?php echo $e['kategori']; ?></td>
                            <td><?php echo $e['penulis']; ?></td>
                            <td><a href="admin_panel.php?hapus_edukasi=<?php echo $e['id']; ?>" class="text-danger" onclick="return confirm('Hapus?')"><i class="bi bi-trash-fill"></i></a></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
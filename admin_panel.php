<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['role'] != 'admin') {
    header("location: login.php"); 
    exit;
}

// UPDATE ROLE USER
if (isset($_POST['update_role_user'])) {
    $uid = $_POST['user_id'];
    $new_role = $_POST['role_baru'];
    
    $update = mysqli_query($conn, "UPDATE users SET role='$new_role' WHERE user_id='$uid'");
    
    if ($update) {
        echo "<script>alert('Role pengguna berhasil diubah!'); window.location.href='admin_panel.php';</script>";
    } else {
        echo "<script>alert('Gagal mengubah role.'); window.location.href='admin_panel.php';</script>";
    }
}

// Logic Hapus User
if (isset($_GET['hapus_user'])) {
    $uid = $_GET['hapus_user'];
    mysqli_query($conn, "DELETE FROM users WHERE user_id='$uid'");
    echo "<script>alert('User dihapus'); window.location.href='admin_panel.php';</script>";
}

// APPROVE PROFIL
if (isset($_POST['approve_profil'])) {
    $id_pengajuan = $_POST['id_pengajuan'];
    
    $q_ambil = mysqli_query($conn, "SELECT * FROM pengajuan_profil WHERE id='$id_pengajuan'");
    $data = mysqli_fetch_assoc($q_ambil);
    $uid = $data['user_id'];

    $nama_baru = mysqli_real_escape_string($conn, $data['nama_baru']);
    mysqli_query($conn, "UPDATE users SET nama='$nama_baru' WHERE user_id='$uid'");

    $spesialisasi = mysqli_real_escape_string($conn, $data['spesialisasi_baru']);
    $pendidikan   = mysqli_real_escape_string($conn, $data['pendidikan_baru']);
    $str          = mysqli_real_escape_string($conn, $data['str_baru']);
    $metode       = mysqli_real_escape_string($conn, $data['metode_baru']);
    $bahasa       = mysqli_real_escape_string($conn, $data['bahasa_baru']);
    $tentang      = mysqli_real_escape_string($conn, $data['tentang_baru']);
    $foto      = mysqli_real_escape_string($conn, $data['foto']);
    if ($foto == 'default.jpg') {
        $cek_konselor = mysqli_query($conn, "SELECT foto FROM konselor_profil WHERE user_id='$uid'");
        $k_lama = mysqli_fetch_assoc($cek_konselor);

        $foto = $k_lama['foto'];  
    }
    
    $cek = mysqli_query($conn, "SELECT id FROM konselor_profil WHERE user_id='$uid'");  
                    
    if (mysqli_num_rows($cek) > 0) {                 
        $sql_prof = "UPDATE konselor_profil SET                             
        pendidikan='$pendidikan',                             
        nomor_str='$str',                             
        metode_terapi='$metode',                             
        bahasa='$bahasa',                             
        tentang_saya='$tentang',                             
        foto='$foto'                             
        WHERE user_id='$uid'";                 
        $sql_users = "UPDATE users SET                             spesialisasi='$spesialisasi'                             
        WHERE user_id='$uid'";             
    } else {                 
        $sql_prof = "INSERT INTO konselor_profil                             
        (user_id, pendidikan, nomor_str, metode_terapi, bahasa, tentang_saya, foto)                             
        VALUES                             
        ('$uid', '$pendidikan', '$str', '$metode', '$bahasa', '$tentang', '$foto')";                             
    }         
    
    if (isset($sql_users)) {             
        mysqli_query($conn, $sql_users);         
    }

    if (mysqli_query($conn, $sql_prof)) {
        mysqli_query($conn, "UPDATE pengajuan_profil SET status='Disetujui' WHERE id='$id_pengajuan'");
        echo "<script>alert('Profil berhasil disetujui dan diperbarui!'); window.location.href='admin_panel.php';</script>";
    } else {
        echo "<script>alert('Gagal update profil: " . mysqli_error($conn) . "');</script>";
    }
}

    


// Logic Approve Jadwal
if (isset($_GET['approve_jadwal'])) {
    $id_aj = $_GET['approve_jadwal'];
    $q_data = mysqli_query($conn, "SELECT * FROM pengajuan_jadwal WHERE id='$id_aj'");
    $d = mysqli_fetch_assoc($q_data);
    
    $q_resmi = "INSERT INTO jadwal_praktik (user_id, hari, jam_mulai, jam_selesai, harga) 
                VALUES ('".$d['user_id']."', '".$d['hari']."', '".$d['jam_mulai']."', '".$d['jam_selesai']."', '".$d['harga']."')";
    mysqli_query($conn, $q_resmi);
    mysqli_query($conn, "UPDATE pengajuan_jadwal SET status='Disetujui' WHERE id='$id_aj'");
    echo "<script>alert('Jadwal Disetujui & Ditayangkan!'); window.location.href='admin_panel.php';</script>";
}

// Logic Reject Jadwal
if (isset($_GET['reject_jadwal'])) {
    $id_aj = $_GET['reject_jadwal'];
    mysqli_query($conn, "UPDATE pengajuan_jadwal SET status='Ditolak' WHERE id='$id_aj'");
    echo "<script>alert('Jadwal Ditolak.'); window.location.href='admin_panel.php';</script>";
}

// Logic Reject Profil
if (isset($_GET['reject_profil'])) {
    $id = $_GET['reject_profil'];
    
    $q_cek = mysqli_query($conn, "SELECT foto FROM pengajuan_profil WHERE id='$id'");
    $d_cek = mysqli_fetch_assoc($q_cek);
    
    if ($d_cek && !empty($d_cek['foto'])) {
        $path_file = "img/" . $d_cek['foto'];
        
        if (file_exists($path_file)) {
            unlink($path_file); 
        }
    }

    mysqli_query($conn, "UPDATE pengajuan_profil SET status='Ditolak' WHERE id='$id'");
    
    echo "<script>alert('Pengajuan Ditolak. File foto pengajuan (jika ada) telah dihapus dari server.'); window.location.href='admin_panel.php';</script>";
}

// Logic Tambah Edukasi
if (isset($_POST['simpan_edukasi'])) {
    $mode = $_POST['mode'];
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $penulis = mysqli_real_escape_string($conn, $_POST['penulis']);
    $gambar = mysqli_real_escape_string($conn, $_POST['gambar']);
    $isi = mysqli_real_escape_string($conn, $_POST['isi']);

    if ($mode == 'tambah') {
        // QUERY INSERT
        $query = "INSERT INTO edukasi (judul, kategori, penulis, gambar_url, isi_konten) 
                VALUES ('$judul', '$kategori', '$penulis', '$gambar', '$isi')";
        $pesan = "Konten berhasil ditambahkan!";
    } else {
        // QUERY UPDATE
        $id = $_POST['id_edu'];
        $query = "UPDATE edukasi SET 
                judul='$judul', kategori='$kategori', penulis='$penulis', 
                gambar_url='$gambar', isi_konten='$isi' 
                WHERE id='$id'";
        $pesan = "Konten berhasil diperbarui!";
    }
    
    mysqli_query($conn, $query);
    echo "<script>alert('$pesan'); window.location.href='admin_panel.php';</script>";
}

// Logic Hapus Edukasi
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-dark shadow-sm mb-4">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold" href="#">ADMINISTRATOR</a>
        <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
</nav>
<!-- END NAVBAR -->

<div class="container-fluid px-4">
    
    <!-- HEADER -->
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
    <!-- END HEADER -->

    <div class="tab-content" id="pills-tabContent">
        
        <!-- TAB DATA USERS -->
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
                                $q_users = mysqli_query($conn, "SELECT * FROM users WHERE role != 'admin' ORDER BY role");
                                while($u = mysqli_fetch_assoc($q_users)):
                                ?>
                                <tr>
                                    <td><?php echo $u['user_id']; ?></td>
                                    <td><?php echo $u['nama']; ?></td>
                                    <td><?php echo $u['email']; ?></td>
                                    <td>
                                        <span class="badge <?php echo ($u['role']=='admin'?'bg-danger':($u['role']=='konselor'?'bg-primary':'bg-secondary')); ?>">
                                            <?php echo strtoupper($u['role']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo $u['status']; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEditRole"
                                            data-id="<?php echo $u['user_id']; ?>"
                                            data-nama="<?php echo $u['nama']; ?>"
                                            data-role="<?php echo $u['role']; ?>">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

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
        <!-- END TAB DATA USERS -->

        <!-- TAB APPROVAL -->
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
        <!-- END TAB APPROVAL -->

        <!-- TAB KELOLA EDUKASI -->
        <div class="tab-pane fade" id="pills-edu">
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                    <span id="formTitle">Tambah Konten Baru</span>
                    <button type="button" class="btn btn-sm btn-outline-secondary d-none" id="btnReset" onclick="resetForm()">Batal Edit</button>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="id_edu" id="inputIdEdu">
                        
                        <input type="hidden" name="mode" id="inputMode" value="tambah">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label class="form-label small fw-bold">Judul</label>
                                <input type="text" name="judul" id="inputJudul" class="form-control" placeholder="Judul Artikel" required>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="form-label small fw-bold">Kategori</label>
                                <select name="kategori" id="inputKategori" class="form-select">
                                    <option value="Artikel">Artikel</option>
                                    <option value="Video">Video</option>
                                    <option value="Kisah Nyata">Kisah Nyata</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="form-label small fw-bold">Penulis</label>
                                <input type="text" name="penulis" id="inputPenulis" class="form-control" placeholder="Penulis" required>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label class="form-label small fw-bold">URL Gambar</label>
                                <input type="text" name="gambar" id="inputGambar" class="form-control" placeholder="https://..." required>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label class="form-label small fw-bold">Isi Konten</label>
                                <textarea name="isi" id="inputIsi" class="form-control" rows="5" placeholder="Isi Konten (Boleh HTML)" required></textarea>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" name="simpan_edukasi" id="btnSubmit" class="btn btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i> Publish Konten
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive bg-white p-3 rounded shadow-sm">
                <table class="table table-striped small align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Penulis</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $q_edu = mysqli_query($conn, "SELECT * FROM edukasi ORDER BY id DESC");
                        while($e = mysqli_fetch_assoc($q_edu)):
                            $data_json = htmlspecialchars(json_encode($e), ENT_QUOTES, 'UTF-8');
                        ?>
                        <tr>
                            <td class="fw-bold"><?php echo $e['judul']; ?></td>
                            <td><span class="badge bg-secondary"><?php echo $e['kategori']; ?></span></td>
                            <td><?php echo $e['penulis']; ?></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <!-- TOMBOL EDIT -->
                                    <button type="button" class="btn btn-warning btn-sm text-white" onclick='editKonten(<?php echo $data_json; ?>)'>
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    
                                    <!-- TOMBOL HAPUS -->
                                    <a href="admin_panel.php?hapus_edukasi=<?php echo $e['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus konten ini?')">
                                        <i class="bi bi-trash-fill"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END TAB KELOLA EDUKASI -->
    </div>
</div>

<!-- MODAL EDIT ROLE (POP-UP)                   -->
<div class="modal fade" id="modalEditRole" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Ubah Role User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST">
            <div class="modal-body">
                <input type="hidden" name="user_id" id="editUserId">
                
                <div class="mb-3">
                    <label class="form-label">Nama User</label>
                    <input type="text" class="form-control" id="editUserNama" readonly>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Pilih Role Baru</label>
                    <select name="role_baru" class="form-select" id="editUserRole">
                        <option value="pasien">Pasien</option>
                        <option value="konselor">Konselor</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" name="update_role_user" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- JAVASCRIPT UNTUK MEMINDAHKAN DATA KE MODAL -->
<script>
    const modalEditRole = document.getElementById('modalEditRole');
    if (modalEditRole) {
        modalEditRole.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            
            const id = button.getAttribute('data-id');
            const nama = button.getAttribute('data-nama');
            const role = button.getAttribute('data-role');
            
            modalEditRole.querySelector('#editUserId').value = id;
            modalEditRole.querySelector('#editUserNama').value = nama;
            modalEditRole.querySelector('#editUserRole').value = role;
        })
    }
</script>

<!-- SCRIPT JAVASCRIPT KHUSUS UNTUK FITUR EDIT -->
<script>
function editKonten(data) {
    // 1. Ubah Judul Form & Tombol
    document.getElementById('formTitle').innerText = "Edit Konten";
    document.getElementById('btnSubmit').innerHTML = "<i class='bi bi-save me-1'></i> Simpan Perubahan";
    document.getElementById('btnSubmit').classList.replace('btn-primary', 'btn-success');
    document.getElementById('btnReset').classList.remove('d-none');

    // 2. Isi Form dengan Data
    document.getElementById('inputIdEdu').value = data.id;
    document.getElementById('inputMode').value = 'edit'; 
    
    document.getElementById('inputJudul').value = data.judul;
    document.getElementById('inputKategori').value = data.kategori;
    document.getElementById('inputPenulis').value = data.penulis;
    document.getElementById('inputGambar').value = data.gambar_url;
    document.getElementById('inputIsi').value = data.isi_konten;

    // 3. Scroll ke atas agar admin sadar form sudah terisi
    document.getElementById('formTitle').scrollIntoView({ behavior: 'smooth' });
}

function resetForm() {
    // 1. Kembalikan Judul & Tombol ke kondisi awal (Tambah)
    document.getElementById('formTitle').innerText = "Tambah Konten Baru";
    document.getElementById('btnSubmit').innerHTML = "<i class='bi bi-plus-lg me-1'></i> Publish Konten";
    document.getElementById('btnSubmit').classList.replace('btn-success', 'btn-primary');
    document.getElementById('btnReset').classList.add('d-none');

    // 2. Kosongkan Form
    document.getElementById('inputIdEdu').value = '';
    document.getElementById('inputMode').value = 'tambah';
    
    document.getElementById('inputJudul').value = '';
    document.getElementById('inputKategori').value = 'Artikel';
    document.getElementById('inputPenulis').value = '';
    document.getElementById('inputGambar').value = '';
    document.getElementById('inputIsi').value = '';
}
</script>

</body>
</html>
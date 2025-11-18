<?php 
session_start(); 
// Cek Login (Wajib Login buat bayar)
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href='login.html';</script>";
    exit;
}

// Tangkap Data dari URL (Metode GET)
$dokter = isset($_GET['dokter']) ? $_GET['dokter'] : '-';
$hari   = isset($_GET['hari']) ? $_GET['hari'] : '-';
$jam    = isset($_GET['jam']) ? $_GET['jam'] : '-';
$harga  = isset($_GET['harga']) ? $_GET['harga'] : 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Stark Hope</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-center">Rincian Pembayaran</h5>
                </div>
                <div class="card-body p-4">
                    
                    <div class="mb-4">
                        <h6 class="text-muted small text-uppercase fw-bold">Detail Sesi</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Konselor</span>
                            <span class="fw-bold"><?php echo htmlspecialchars($dokter); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Jadwal</span>
                            <span class="fw-bold"><?php echo $hari . ', ' . $jam; ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Durasi</span>
                            <span>60 Menit</span>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-bold">Total Biaya</span>
                        <span class="fs-4 fw-bold text-primary">Rp <?php echo number_format($harga, 0, ',', '.'); ?></span>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Metode Pembayaran</label>
                        <select class="form-select">
                            <option>QRIS (GoPay/OVO/Dana)</option>
                            <option>Transfer Bank BCA</option>
                            <option>Transfer Bank Mandiri</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button onclick="alert('Pembayaran Berhasil! (Simulasi)')" class="btn btn-success btn-lg">
                            Bayar Sekarang
                        </button>
                        <a href="konselor.php" class="btn btn-outline-secondary">Batal</a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
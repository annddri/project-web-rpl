<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    header("location: login.php");
    exit;
}

$user_id       = $_SESSION['user_id'];
$konselor_id   = $_POST['konselor_id']; 
$konselor_nama = $_POST['konselor'];
$hari          = $_POST['hari'];
$jam           = $_POST['jam'];

$id_transaksi = "BOOK-" . time() . rand(100, 999);

    // 4. GENERATE LINK MEET (PERBAIKAN DI SINI)
    // Kita gunakan Jitsi Meet. Link ini otomatis membuat room saat diklik.
    // Kita gunakan ID Transaksi agar room-nya unik untuk sesi ini saja.
    $link_meet = "https://meet.jit.si/StarkHope-" . $id_transaksi;

$query = "INSERT INTO konsultasi (user_id, konselor_id, konselor_nama, hari, jam, status, link_meet) 
          VALUES ('$user_id', '$konselor_id', '$konselor_nama', '$hari', '$jam', 'Terjadwal', '$link_meet')";

if (mysqli_query($conn, $query)) {
    echo "<script>alert('Booking Berhasil!'); window.location.href = 'jadwal_konsultasi.php';</script>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
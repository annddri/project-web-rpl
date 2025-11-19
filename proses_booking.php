<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    header("location: login.html");
    exit;
}

$user_id       = $_SESSION['user_id'];
// TAMBAHAN BARU: Tangkap konselor_id
$konselor_id   = $_POST['konselor_id']; 
$konselor_nama = $_POST['konselor'];
$hari          = $_POST['hari'];
$jam           = $_POST['jam'];

// Fungsi Link GMeet (Tetap sama)
function generateGMeetLink() {
    return "https://meet.google.com/" . substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 3) . "-" . substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 4) . "-" . substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 3);
}
$link_meet = generateGMeetLink();

// QUERY UPDATE: Masukkan konselor_id juga
$query = "INSERT INTO konsultasi (user_id, konselor_id, konselor_nama, hari, jam, status, link_meet) 
          VALUES ('$user_id', '$konselor_id', '$konselor_nama', '$hari', '$jam', 'Terjadwal', '$link_meet')";

if (mysqli_query($conn, $query)) {
    echo "<script>alert('Booking Berhasil!'); window.location.href = 'jadwal_konsultasi.php';</script>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
<?php
$host = "localhost";
$user = "root";     // Default XAMPP username
$pass = "";         // Default XAMPP password (kosong)
$db   = "projectrpl";  // Nama database yang kita buat tadi

// Melakukan koneksi
$conn = mysqli_connect($host, $user, $pass, $db);

// Cek apakah koneksi gagal
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>
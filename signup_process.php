<?php
// echo "<script>alert('Password tidak cocok!'); window.location.href='signup.html';</script>";
include 'koneksi.php';

$username = $_POST['username'];
$email    = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// 1. Cek Password Cocok
if ($password !== $confirm_password) {
    echo "<script>alert('Password tidak cocok!'); window.location.href='signup.html';</script>";
    exit;
}

// 2. Cek apakah Username atau Email sudah ada
// Kita sesuaikan query agar mencari di kolom yang tepat
$check_query = "SELECT * FROM users WHERE email = '$email' OR username = '$username'";
$check_result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    echo "<script>alert('Username atau Email sudah terdaftar!'); window.location.href='signup.html';</script>";
    exit;
}

// 3. Persiapan Data untuk Insert
// a. Hash Password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// b. Generate user_id (Karena varchar(20), kita buat random string misal: USR-123456)
// Format: USR + angka random + timestamp detik (agar unik)
$user_id = "USR-" . rand(100, 999) . date("Hs"); 

// c. Set Role Default
$role = 'pasien';

// d. Set Nama (Sementara kita isi dengan username dulu agar kolom tidak kosong/error)
$nama = $username;

// 4. Query Insert ke Database (Sesuai nama kolom di gambar)
$query = "INSERT INTO users (user_id, nama, username, email, password, role, status) 
          VALUES ('$user_id', '$nama', '$username', '$email', '$hashed_password', '$role', 'active')";

if (mysqli_query($conn, $query)) {
    echo "<script>
            alert('Pendaftaran Berhasil! Silakan login.');
            window.location.href = 'login.php';
          </script>";
} else {
    // Tampilkan error jika ada masalah dengan query
    echo "Error: " . mysqli_error($conn);
}
?>
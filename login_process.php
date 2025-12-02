<?php
session_start();
include 'koneksi.php';

$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = $_POST['password'];

$query = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    if (password_verify($password, $row['password'])) {
        if ($row['status'] == 'inactive') {
            echo "<script>alert('Akun Anda dinonaktifkan. Hubungi admin.'); window.location.href='login.php';</script>";
            exit;
        }
        $_SESSION['status'] = "login";
        $_SESSION['user_id'] = $row['user_id'];   
        $_SESSION['username'] = $row['username']; 
        $_SESSION['role'] = $row['role'];         
        $_SESSION['nama'] = $row['nama'];         
        if ($row['role'] == 'admin') {
            echo "<script>alert('Login Admin Berhasil'); window.location.href = 'admin_panel.php';</script>";
        } 
        elseif ($row['role'] == 'konselor') {
            echo "<script>alert('Selamat Datang Dokter'); window.location.href = 'jadwal_konsultasi.php';</script>";
        } 
        else {
            echo "<script>alert('Selamat Datang'); window.location.href = 'index.php';</script>";
        } 
    }
    else {
        echo "<script>alert('Password salah!'); window.location.href='login.php';</script>";
    } 
}
else {
    echo "<script>alert('Email tidak terdaftar!'); window.location.href='login.php';</script>";
}
?>
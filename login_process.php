<?php
session_start();
include 'koneksi.php';

// Amankan input
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = $_POST['password'];

// Query cari user berdasarkan email
$query = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $query);

// Cek ketersediaan user
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    
    // Verifikasi Password
    if (password_verify($password, $row['password'])) {
        
        // Cek Status (Opsional, sesuai kolom 'status' di DB kamu)
        if ($row['status'] == 'inactive') {
            echo "<script>alert('Akun Anda dinonaktifkan. Hubungi admin.'); window.location.href='login.html';</script>";
            exit;
        }

        // Set Session
        $_SESSION['status'] = "login";
        $_SESSION['user_id'] = $row['user_id'];   // Simpan user_id
        $_SESSION['username'] = $row['username']; // Simpan username
        $_SESSION['role'] = $row['role'];         // Simpan role (penting untuk dashboard nanti)
        $_SESSION['nama'] = $row['nama'];         // Simpan nama asli
        
if ($row['role'] == 'konselor') {
        // Jika Konselor, arahkan ke halaman kerjanya
        echo "<script>
                alert('Selamat Datang, Dokter " . $row['nama'] . "!');
                window.location.href = 'jadwal_konsultasi.php'; 
              </script>";
    } else {
        // Jika Pasien, arahkan ke halaman depan
        echo "<script>
                alert('Selamat Datang, " . $row['username'] . "!');
                window.location.href = 'index.php'; 
              </script>";
    }
    } else {
        echo "<script>alert('Password salah!'); window.location.href='login.php';</script>";
    }
} else {
    echo "<script>alert('Email tidak terdaftar!'); window.location.href='login.php';</script>";
}
?>
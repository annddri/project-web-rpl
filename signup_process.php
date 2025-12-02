<?php
include 'koneksi.php';

$username_raw = strip_tags($_POST['username']);
$email_raw    = strip_tags($_POST['email']);
$username = htmlspecialchars($username_raw, ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($email_raw, ENT_QUOTES, 'UTF-8');
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

if ($password !== $confirm_password) {
    echo "<script>alert('Password tidak cocok!'); window.location.href='signup.php';</script>";
    exit;
}

$check_query = "SELECT * FROM users WHERE email = '$email' OR username = '$username'";
$check_result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    echo "<script>alert('Username atau Email sudah terdaftar!'); window.location.href='signup.php';</script>";
    exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$user_id = "USR-" . rand(100, 999) . date("Hs"); 

$role = 'pasien';

$nama = $username;

$query = "INSERT INTO users (user_id, nama, username, email, password, role, status) 
          VALUES ('$user_id', '$nama', '$username', '$email', '$hashed_password', '$role', 'active')";

if (mysqli_query($conn, $query)) {
    echo "<script>
            alert('Pendaftaran Berhasil! Silakan login.');
            window.location.href = 'login.php';
          </script>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
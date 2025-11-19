<?php
session_start();

session_destroy(); // Hapus sesi
echo "<script>
        alert('Anda telah logout.');
        window.location.href = 'index.php'; // Kembali ke login
      </script>";
?>
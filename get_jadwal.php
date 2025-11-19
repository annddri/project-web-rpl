<?php
include 'koneksi.php';

$konselor_id = $_GET['id'];

// 1. Ambil Nama Konselor (untuk keperluan link pembayaran)
$q_user = mysqli_query($conn, "SELECT nama FROM users WHERE user_id = '$konselor_id'");
$d_user = mysqli_fetch_assoc($q_user);
$nama_konselor = $d_user['nama'];

// 2. Ambil Jadwal Praktik Konselor Ini
$query = "SELECT * FROM jadwal_praktik WHERE user_id = '$konselor_id' ORDER BY FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'), jam_mulai";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $hari = $row['hari'];
        $jam  = $row['jam_mulai'];
        
        // 3. CEK APAKAH SUDAH DIBOOKING?
        // Kita cek di tabel konsultasi: Ada gak yang konselornya INI, harinya INI, jamnya INI, dan statusnya BUKAN Dibatalkan?
        $cek_booking = mysqli_query($conn, "SELECT * FROM konsultasi 
                                            WHERE konselor_id = '$konselor_id' 
                                            AND hari = '$hari' 
                                            AND jam = '$jam' 
                                            AND status != 'Dibatalkan'");
        
        $is_booked = (mysqli_num_rows($cek_booking) > 0);

        // 4. RENDER HTML BARIS TABEL
        echo '<tr>';
        echo '<td>' . $hari . '</td>';
        echo '<td>' . $row['jam_mulai'] . ' - ' . $row['jam_selesai'] . '</td>';
        echo '<td>';
        
        if ($is_booked) {
            // JIKA PENUH
            echo '<button class="btn btn-secondary btn-sm w-100" disabled>Penuh</button>';
        } else {
            // JIKA TERSEDIA (Tombol mengarah ke pembayaran)
            // Kita kirim ID Konselor juga di URL
            echo '<a href="pembayaran.php?konselor_id='.$konselor_id.'&dokter='.urlencode($nama_konselor).'&hari='.$hari.'&jam='.$jam.'&harga=150000" 
                     class="btn btn-primary btn-sm w-100">
                     Booking
                  </a>';
        }
        
        echo '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="3" class="text-center text-muted py-3">Konselor ini belum mengatur jadwal praktik.</td></tr>';
}
?>
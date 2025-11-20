<?php
include 'koneksi.php';

$konselor_id = $_GET['id'];

// Ambil Nama Konselor
$q_user = mysqli_query($conn, "SELECT nama FROM users WHERE user_id = '$konselor_id'");
$d_user = mysqli_fetch_assoc($q_user);
$nama_konselor = $d_user['nama'];

// Ambil Jadwal Praktik (Beserta Harganya)
$query = "SELECT * FROM jadwal_praktik WHERE user_id = '$konselor_id' ORDER BY FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'), jam_mulai";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $hari  = $row['hari'];
        $jam   = $row['jam_mulai'];
        $harga = $row['harga']; // Ambil harga dari database
        
        // --- LOGIKA KETERSEDIAAN SLOT (DIPERBAIKI) ---
        // Slot dianggap PENUH hanya jika statusnya 'Terjadwal'
        // Jika status 'Selesai' atau 'Dibatalkan', maka slot dianggap KOSONG (Bisa dibooking lagi)
        $cek_booking = mysqli_query($conn, "SELECT * FROM konsultasi 
                                            WHERE konselor_id = '$konselor_id' 
                                            AND hari = '$hari' 
                                            AND jam = '$jam' 
                                            AND status = 'Terjadwal'"); // Cuma cek yang Terjadwal
        
        $is_booked = (mysqli_num_rows($cek_booking) > 0);

        echo '<tr>';
        echo '<td>' . $hari . '</td>';
        echo '<td>' . $row['jam_mulai'] . ' - ' . $row['jam_selesai'] . '</td>';
        echo '<td>';
        
        if ($is_booked) {
            echo '<button class="btn btn-secondary btn-sm w-100" disabled>Penuh</button>';
        } else {
            // KITA KIRIM HARGA DI SINI KE LINK
            echo '<a href="pembayaran.php?konselor_id='.$konselor_id.'&dokter='.urlencode($nama_konselor).'&hari='.$hari.'&jam='.$jam.'&harga='.$harga.'" 
                     class="btn btn-primary btn-sm w-100">
                     Booking (Rp '.number_format($harga/1000,0).'k)
                  </a>';
        }
        
        echo '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="3" class="text-center text-muted py-3">Jadwal belum tersedia.</td></tr>';
}
?>
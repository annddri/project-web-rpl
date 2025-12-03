<?php
include 'koneksi.php';

$konselor_id = $_GET['id'];

$q_user = mysqli_query($conn, "SELECT nama FROM users WHERE user_id = '$konselor_id'");
$d_user = mysqli_fetch_assoc($q_user);
$nama_konselor = $d_user['nama'];

$query = "SELECT * FROM jadwal_praktik WHERE user_id = '$konselor_id' ORDER BY FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'), jam_mulai";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $hari = $row['hari'];
        $jam  = $row['jam_mulai'];
        $harga  = $row['harga'];
        $harga_tampil = "Rp " . number_format($harga, 0, ',', '.');
        
$cek_booking = mysqli_query($conn, "SELECT * FROM konsultasi 
                                            WHERE konselor_id = '$konselor_id' 
                                            AND hari = '$hari' 
                                            AND jam = '$jam'  
                                            AND status = 'Terjadwal'"); 
        $is_booked = (mysqli_num_rows($cek_booking) > 0);

        echo '<tr>';
        echo '<td>' . $hari . '</td>';
        echo '<td>' . $row['jam_mulai'] . ' - ' . $row['jam_selesai'] . '</td>';
        echo '<td>' . $harga_tampil . '</td>';
        echo '<td>';
        
        if ($is_booked) {
            echo '<button class="btn btn-secondary btn-sm w-100" disabled>Penuh</button>';
        } else {
            echo '<a href="pembayaran.php?konselor_id='.$konselor_id.'&dokter='.urlencode($nama_konselor).'&hari='.$hari.'&jam='.$jam.'&harga='.$harga.'" 
                    class="btn btn-primary btn-sm w-100">
                    Booking
                </a>';
        }
        
        echo '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="4" class="text-center text-muted py-3">Konselor ini belum mengatur jadwal praktik.</td></tr>';
}
?>
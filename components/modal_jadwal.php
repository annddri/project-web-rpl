<div class="modal fade" id="modalJadwal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      
      <div class="modal-header bg-primary text-white">
        <div>
            <h5 class="modal-title fw-bold" id="modalNamaKonselor">Nama Konselor</h5>
            <small id="modalSpesialisasi" class="text-light opacity-75">Spesialisasi</small>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      
      <div class="modal-body">
        <p class="text-muted small mb-3">Pilih jadwal yang sesuai untuk melanjutkan ke pembayaran:</p>
        
        <table class="table table-bordered align-middle small">
            <thead class="table-light">
                <tr>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th width="30%">Aksi</th>
                </tr>
            </thead>
            <tbody id="tabelJadwal">
                <tr>
                    <td>Senin</td>
                    <td>09:00 - 12:00</td>
                    <td>
                        <a href="pembayaran.php?hari=Senin&jam=09:00" class="btn btn-sm btn-primary w-100 btn-booking">
                            Booking
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>Rabu</td>
                    <td>13:00 - 16:00</td>
                    <td>
                        <a href="pembayaran.php?hari=Rabu&jam=13:00" class="btn btn-sm btn-primary w-100 btn-booking">
                            Booking
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>Jumat</td>
                    <td>09:00 - 11:00</td>
                    <td>
                        <button class="btn btn-sm btn-secondary w-100" disabled>Penuh</button>
                    </td>
                </tr>
            </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
    const modalJadwal = document.getElementById('modalJadwal')
    if (modalJadwal) {
        modalJadwal.addEventListener('show.bs.modal', event => {
            // 1. Tangkap data dari tombol yang ditekan
            const button = event.relatedTarget
            const nama = button.getAttribute('data-nama')
            const spesialis = button.getAttribute('data-spesialis')
            const harga = 150000; // Contoh harga flat

            // 2. Update Teks Judul Modal
            modalJadwal.querySelector('#modalNamaKonselor').textContent = nama
            modalJadwal.querySelector('#modalSpesialisasi').textContent = spesialis
            
            // 3. Update Link di Tombol Booking (Menambahkan paramater dokter & harga)
            // Kita cari semua tombol yang punya class 'btn-booking'
            const bookingButtons = modalJadwal.querySelectorAll('.btn-booking');
            
            bookingButtons.forEach(btn => {
                // Ambil URL dasar (misal: pembayaran.php?hari=Senin&jam=09:00)
                // Kita reset dulu href-nya biar tidak menumpuk jika modal dibuka tutup
                let originalHref = btn.getAttribute('href').split('&dokter=')[0]; 
                
                // Tambahkan nama dokter dan harga ke URL
                btn.href = originalHref + `&dokter=${encodeURIComponent(nama)}&harga=${harga}`;
            });
        })
    }
</script>
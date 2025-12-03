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
            <p class="text-muted small mb-3">Pilih jadwal yang tersedia (Real-time):</p>
            
            <div class="table-responsive">
                <table class="table table-bordered align-middle small text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Hari</th>
                            <th>Jam</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="isiTabelJadwal">
                        <tr><td colspan="4">Memuat jadwal...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>
</div>

<script>
    const modalJadwal = document.getElementById('modalJadwal')
    if (modalJadwal) {
        modalJadwal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget
            const id = button.getAttribute('data-id')
            const nama = button.getAttribute('data-nama')
            const spesialis = button.getAttribute('data-spesialis')

            modalJadwal.querySelector('#modalNamaKonselor').textContent = nama
            modalJadwal.querySelector('#modalSpesialisasi').textContent = spesialis
            
            const tbody = modalJadwal.querySelector('#isiTabelJadwal');
            tbody.innerHTML = '<tr><td colspan="4" class="py-4"><div class="spinner-border text-primary" role="status"></div></td></tr>';

            fetch('get_jadwal.php?id=' + id)
                .then(response => response.text())
                .then(data => {
                    tbody.innerHTML = data;
                })
                .catch(error => {
                    tbody.innerHTML = '<tr><td colspan="3" class="text-danger">Gagal memuat jadwal.</td></tr>';
                });
        })
    }
</script>
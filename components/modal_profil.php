<div class="modal fade" id="modalProfil" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      
      <div class="modal-header border-0 pb-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
        <div class="row">
            <div class="col-md-4 text-center border-end">
                <img src="" id="profilFoto" class="img-fluid rounded-circle mb-3 shadow-sm" style="width: 150px; height: 150px; object-fit: cover;">
                
                <h5 class="fw-bold mb-1" id="profilNama">Nama Konselor</h5>
                <span class="badge bg-primary-subtle text-primary mb-3" id="profilSpesialis">Spesialisasi</span>
                
                <div class="text-start mt-4 px-2">
                    <small class="text-muted fw-bold text-uppercase">Nomor STR</small>
                    <p class="small mb-3" id="profilSTR">-</p>

                    <small class="text-muted fw-bold text-uppercase">Bahasa</small>
                    <p class="small mb-3" id="profilBahasa">-</p>
                </div>
            </div>

            <div class="col-md-8 ps-md-4">
                <h6 class="fw-bold text-primary"><i class="bi bi-person-lines-fill me-2"></i>Tentang Saya</h6>
                <p class="text-muted small" id="profilTentang">Belum ada informasi.</p>
                
                <hr class="border-secondary opacity-10">

                <div class="row g-3">
                    <div class="col-12">
                        <h6 class="fw-bold text-primary"><i class="bi bi-mortarboard-fill me-2"></i>Pendidikan</h6>
                        <p class="text-muted small mb-0" id="profilPendidikan">-</p>
                    </div>
                    <div class="col-12">
                        <h6 class="fw-bold text-primary"><i class="bi bi-heart-pulse-fill me-2"></i>Pendekatan Terapi</h6>
                        <p class="text-muted small mb-0" id="profilMetode">-</p>
                    </div>
                </div>
            </div>
        </div>
      </div>
      
      <div class="modal-footer border-0 bg-light">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary" id="btnBookingDariProfil" data-bs-toggle="modal" data-bs-target="#modalJadwal">
            Jadwalkan Konsultasi
        </button>
      </div>

    </div>
  </div>
</div>

<script>
    const modalProfil = document.getElementById('modalProfil')
    if (modalProfil) {
        modalProfil.addEventListener('show.bs.modal', event => {
            // 1. Tangkap tombol pemicu
            const button = event.relatedTarget
            
            // 2. Ambil data dari atribut
            const nama = button.getAttribute('data-nama')
            const spesialis = button.getAttribute('data-spesialis')
            const foto = button.getAttribute('data-foto')
            const str = button.getAttribute('data-str')
            const bahasa = button.getAttribute('data-bahasa')
            const tentang = button.getAttribute('data-tentang')
            const pendidikan = button.getAttribute('data-pendidikan')
            const metode = button.getAttribute('data-metode')
            
            // 3. Isi Modal Profil
            modalProfil.querySelector('#profilNama').textContent = nama
            modalProfil.querySelector('#profilSpesialis').textContent = spesialis
            modalProfil.querySelector('#profilFoto').src = foto
            modalProfil.querySelector('#profilSTR').textContent = str
            modalProfil.querySelector('#profilBahasa').textContent = bahasa
            modalProfil.querySelector('#profilTentang').textContent = tentang
            modalProfil.querySelector('#profilPendidikan').textContent = pendidikan
            modalProfil.querySelector('#profilMetode').textContent = metode

            // 4. Update Tombol "Jadwalkan Konsultasi" di dalam modal profil
            // Agar saat diklik, data dokternya terkirim ke modal jadwal
            const btnBooking = modalProfil.querySelector('#btnBookingDariProfil')
            btnBooking.setAttribute('data-nama', nama)
            btnBooking.setAttribute('data-spesialis', spesialis)
        })
    }
</script>
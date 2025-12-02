<div class="modal fade" id="modalPasien" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold">Profil Pasien</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      
      <div class="modal-body">
        <div class="text-center mb-4">
            <img src="" id="pFoto" class="rounded-circle shadow-sm mb-2" width="80" height="80">
            <h5 class="fw-bold mb-0" id="pNama">-</h5>
            <small class="text-muted" id="pEmail">-</small>
        </div>

        <ul class="list-group list-group-flush small">
            <li class="list-group-item d-flex justify-content-between px-0">
                <span class="text-muted">User ID</span>
                <span class="fw-bold text-dark" id="pId">-</span>
            </li>
            <li class="list-group-item d-flex justify-content-between px-0">
                <span class="text-muted">Jenis Kelamin</span>
                <span class="fw-bold text-dark" id="pGender">-</span>
            </li>
            <li class="list-group-item d-flex justify-content-between px-0">
                <span class="text-muted">Tanggal Lahir</span>
                <span class="fw-bold text-dark" id="pLahir">-</span>
            </li>
            <li class="list-group-item px-0">
                <span class="text-muted d-block mb-1">Alamat Domisili</span>
                <span class="fw-bold text-dark" id="pAlamat">-</span>
            </li>
        </ul>
      </div>

      <div class="modal-footer border-0">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
      </div>

    </div>
  </div>
</div>

<script>
    const modalPasien = document.getElementById('modalPasien')
    if (modalPasien) {
        modalPasien.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget
            
            const nama = button.getAttribute('data-nama')
            const email = button.getAttribute('data-email')
            const id = button.getAttribute('data-id')
            const gender = button.getAttribute('data-gender')
            const lahir = button.getAttribute('data-lahir')
            const alamat = button.getAttribute('data-alamat')
            
            const fotoUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(nama)}&background=random&color=fff`;

            modalPasien.querySelector('#pNama').textContent = nama
            modalPasien.querySelector('#pEmail').textContent = email
            modalPasien.querySelector('#pFoto').src = fotoUrl
            modalPasien.querySelector('#pId').textContent = id
            modalPasien.querySelector('#pGender').textContent = gender
            modalPasien.querySelector('#pLahir').textContent = lahir
            modalPasien.querySelector('#pAlamat').textContent = alamat
        })
    }
</script>
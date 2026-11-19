<!-- Popup konfirmasi hapus (HTML saja). Nama produk diisi oleh JavaScript di script.js.
     BACKEND: sebaiknya soft delete (IsActive = 0) supaya riwayat transaksi tetap utuh. -->
<div class="modal fade" id="modalDeleteProduct" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">

    <!-- BACKEND: isi action dengan route hapus produk -->
    <form class="modal-content" method="POST" action="">

      <div class="modal-body text-center p-4">
        <i data-lucide="triangle-alert" width="44" height="44" class="text-danger"></i>
        <h5 class="fw-bold mt-2">Hapus Produk?</h5>
        <p class="text-muted mb-0">
          Apakah Anda yakin ingin menghapus produk <strong id="hapusNama">-</strong>?
        </p>
        <input type="hidden" name="ProductID" id="hapusProductID">
      </div>

      <div class="modal-footer justify-content-center border-0 pt-0">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
      </div>

    </form>
  </div>
</div>
<!-- Popup "Edit Produk" (HTML saja).
     Kolom diisi otomatis oleh JavaScript di index.php saat tombol Edit diklik.
     name="..." = nama kolom di tabel tblproduct. -->
<div class="modal fade" id="modalEditProduct" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">

    <!-- BACKEND: isi action dengan route ubah produk -->
    <form class="modal-content" method="POST" action="">

      <div class="modal-header">
        <h5 class="modal-title fw-bold">Edit Produk</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>

      <div class="modal-body">
        <!-- ProductID tersembunyi: penanda produk mana yang diubah -->
        <input type="hidden" name="ProductID" id="editProductID">

        <div class="row g-3">

          <div class="col-md-8">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="ProductName" id="editProductName" class="form-control" maxlength="100" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Satuan</label>
            <input type="text" name="Unit" id="editUnit" class="form-control" maxlength="20" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Kategori</label>
            <!-- Opsi diisi otomatis dari variabel $categories (index.php). value = CategoryID. -->
            <select name="CategoryID" id="editCategoryID" class="form-select" required>
              <?php foreach ($categories ?? [] as $idKategori => $namaKategori): ?>
                <option value="<?= (int) $idKategori ?>"><?= htmlspecialchars($namaKategori) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Lokasi Penyimpanan <span class="text-muted">(opsional)</span></label>
            <input type="text" name="Location" id="editLocation" class="form-control" maxlength="50">
          </div>

          <div class="col-md-6">
            <label class="form-label">Harga Modal</label>
            <div class="input-group">
              <span class="input-group-text">Rp</span>
              <input type="number" name="PurchasePrice" id="editPurchasePrice" class="form-control" min="0" step="0.01" required>
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label">Harga Jual</label>
            <div class="input-group">
              <span class="input-group-text">Rp</span>
              <input type="number" name="SellingPrice" id="editSellingPrice" class="form-control" min="0" step="0.01" required>
            </div>
          </div>

          <div class="col-md-4">
            <label class="form-label">Stok</label>
            <input type="number" name="Stock" id="editStock" class="form-control" min="0" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Batas Stok Menipis</label>
            <input type="number" name="MinimumStock" id="editMinimumStock" class="form-control" min="0" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Tanggal Kedaluwarsa <span class="text-muted">(opsional)</span></label>
            <input type="date" name="ExpiredDate" id="editExpiredDate" class="form-control">
          </div>

          <div class="col-12">
            <label class="form-label">Catatan <span class="text-muted">(opsional)</span></label>
            <textarea name="Note" id="editNote" class="form-control" rows="2"></textarea>
          </div>

        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      </div>

    </form>
  </div>
</div>

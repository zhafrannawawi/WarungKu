<!-- ====================================================================
     Popup "Tambah Produk Baru"  (HTML saja, tanpa logika)
     Dibuka oleh tombol dengan data-bs-target="#modalAddProduct".

     Atribut name="..." = nama kolom di tabel tblproduct.
     Kolom yang TIDAK ada di form ini (diisi backend / memakai nilai bawaan):
       StoreID, EntryDate, IsActive  → diisi backend
       Location, ExpiredDate, Note   → boleh kosong (NULL)
     Daftar kategori memakai variabel $categories dari index.php
     (bentuk: [CategoryID => NamaKategori]).
     ==================================================================== -->
<div class="modal fade" id="modalAddProduct" tabindex="-1" aria-labelledby="addProductTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">

    <!-- BACKEND: isi action dengan route simpan produk -->
    <form class="modal-content" method="POST" action="">

      <!-- ===== Header: ikon + judul + tombol tutup ===== -->
      <div class="modal-header align-items-center gap-3 px-4 py-3">
        <div class="bg-primary-subtle text-primary rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
             style="width: 44px; height: 44px;">
          <!-- Ikon kotak produk (SVG langsung, tidak butuh library ikon) -->
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
            <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2zm3.564 1.426L5.596 5 8 5.961 14.154 3.5zm3.25 1.7-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464z"/>
          </svg>
        </div>
        <div>
          <h5 class="modal-title fw-bold mb-0" id="addProductTitle">Tambah Produk Baru</h5>
          <small class="text-muted">Lengkapi informasi data barang</small>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>

      <!-- ===== Isi form ===== -->
      <div class="modal-body px-4 py-4">
        <div class="row g-3">

          <!-- Nama Produk -->
          <div class="col-12">
            <label for="addProductName" class="form-label fw-semibold text-secondary">
              Nama Produk <span class="text-danger">*</span>
            </label>
            <input type="text" id="addProductName" name="ProductName" class="form-control"
                   maxlength="100" placeholder="Contoh: Beras Ramos Setra 5kg" required>
          </div>

          <!-- Kategori + Satuan -->
          <div class="col-md-6">
            <label for="addCategoryID" class="form-label fw-semibold text-secondary">
              Kategori <span class="text-danger">*</span>
            </label>
            <select id="addCategoryID" name="CategoryID" class="form-select" required>
              <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $idKategori => $namaKategori): ?>
                  <option value="<?= (int) $idKategori ?>"><?= htmlspecialchars($namaKategori) ?></option>
                <?php endforeach; ?>
              <?php else: ?>
                <option value="" selected disabled>Belum ada kategori</option>
              <?php endif; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label for="addUnit" class="form-label fw-semibold text-secondary">
              Satuan <span class="text-danger">*</span>
            </label>
            <input type="text" id="addUnit" name="Unit" class="form-control" maxlength="20"
                   placeholder="Contoh: Pcs" list="daftarSatuan" autocomplete="off" required>
            <!-- Saran satuan (boleh tetap mengetik satuan lain) -->
            <datalist id="daftarSatuan">
              <option value="Pcs"></option>
              <option value="Kg"></option>
              <option value="Karung"></option>
              <option value="Liter"></option>
              <option value="Bungkus"></option>
              <option value="Dus"></option>
            </datalist>
          </div>

          <!-- Harga Modal + Harga Jual -->
          <div class="col-md-6">
            <label for="addPurchasePrice" class="form-label fw-semibold text-secondary">Harga Modal (Rp)</label>
            <!-- Boleh kosong di tampilan. BACKEND: anggap kosong = 0 (kolom PurchasePrice NOT NULL) -->
            <input type="number" id="addPurchasePrice" name="PurchasePrice" class="form-control"
                   min="0" step="0.01" placeholder="Contoh: 60000">
          </div>
          <div class="col-md-6">
            <label for="addSellingPrice" class="form-label fw-semibold text-secondary">
              Harga Jual (Rp) <span class="text-danger">*</span>
            </label>
            <input type="number" id="addSellingPrice" name="SellingPrice" class="form-control"
                   min="0" step="0.01" placeholder="Contoh: 69000" required>
          </div>

          <!-- Stok Awal + Min. Peringatan Stok -->
          <div class="col-md-6">
            <label for="addStock" class="form-label fw-semibold text-secondary">
              Stok Awal <span class="text-danger">*</span>
            </label>
            <input type="number" id="addStock" name="Stock" class="form-control"
                   min="0" step="1" placeholder="Contoh: 50" required>
          </div>
          <div class="col-md-6">
            <label for="addMinimumStock" class="form-label fw-semibold text-secondary">Min. Peringatan Stok</label>
            <input type="number" id="addMinimumStock" name="MinimumStock" class="form-control"
                   min="0" step="1" value="10">
          </div>

        </div>

        <!-- Catatan kecil di bawah form -->
        <p class="small text-muted mt-4 mb-0 d-flex align-items-center gap-2">
          <!-- Ikon info (SVG langsung) -->
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="flex-shrink-0" aria-hidden="true">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
          </svg>
          Ikon produk otomatis disesuaikan berdasarkan kategori yang dipilih.
        </p>
      </div>

      <!-- ===== Footer: tombol ===== -->
      <div class="modal-footer bg-body-tertiary px-4 py-3">
        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary px-4 d-inline-flex align-items-center gap-2">
          <!-- Ikon centang (SVG langsung) -->
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
            <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
          </svg>
          Simpan Produk
        </button>
      </div>

    </form>
  </div>
</div>
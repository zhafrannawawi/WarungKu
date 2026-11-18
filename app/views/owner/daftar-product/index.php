<?php
// =====================================================================
// views/owner/product/index.php  →  "Daftar Produk" (DATA ASLI dari database)
//
// Data datang dari controller lewat variabel $product_list
// (hasil ProductModel->get_all()). Setiap halaman dibuka, data diambil
// ulang, jadi tabel selalu mengikuti isi database terbaru.
// =====================================================================

// ---------- 1. SIAPKAN DATA ----------
// Kalau controller belum mengirim data, anggap kosong (tidak error).
$product_list = $product_list ?? [];

$products = [];
foreach ($product_list as $p) {

    // Lewati produk yang sudah "dihapus" (IsActive = 0), kalau kolomnya ikut dikirim
    if (isset($p['IsActive']) && (int) $p['IsActive'] === 0) {
        continue;
    }

    $products[] = [
        'id'          => (int) ($p['ProductID'] ?? 0),
        'nama'        => $p['ProductName'] ?? '-',
        'satuan'      => $p['Unit'] ?? 'Pcs',
        'kategori_id' => (int) ($p['CategoryID'] ?? 0),
        // Nama kategori: harusnya dikirim backend lewat JOIN (CategoryName).
        // Kalau belum ada, sementara tampilkan "Kategori #id".
        'kategori'    => $p['CategoryName'] ?? ('Kategori #' . ($p['CategoryID'] ?? '?')),
        'modal'       => (float) ($p['PurchasePrice'] ?? 0),
        'jual'        => (float) ($p['SellingPrice'] ?? 0),
        'stok'        => (int) ($p['Stock'] ?? 0),
        'min'         => (int) ($p['MinimumStock'] ?? 0),
        'expired'     => $p['ExpiredDate'] ?? '',
        'lokasi'      => $p['Location'] ?? '',
        'catatan'     => $p['Note'] ?? '',
    ];
}

// Fungsi bantu: 69000 → "Rp 69.000"
if (!function_exists('rupiah')) {
    function rupiah($angka) {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
}

// ---------- 2. HITUNG KARTU RINGKASAN ----------
$totalProduk = count($products);
$totalNilai  = 0;     // harga modal x stok, semua produk
$menipis     = [];    // nama produk yang stoknya menipis

foreach ($products as $pr) {
    $totalNilai += $pr['modal'] * $pr['stok'];
    if ($pr['stok'] <= $pr['min']) {
        $menipis[] = $pr['nama'];
    }
}

// ---------- 3. DAFTAR KATEGORI (untuk filter dan popup) ----------
// Idealnya backend mengirim $category_list (isi: CategoryID dan CategoryName).
// Kalau belum ada, diambil dari kategori yang dipakai produk.
$categories = [];   // bentuk: [CategoryID => NamaKategori]
if (!empty($category_list)) {
    foreach ($category_list as $c) {
        $categories[(int) ($c['CategoryID'] ?? 0)] = $c['CategoryName'] ?? ($c['Name'] ?? '-');
    }
} else {
    foreach ($products as $pr) {
        $categories[$pr['kategori_id']] = $pr['kategori'];
    }
}
$kategoriDipakai = array_unique(array_column($products, 'kategori'));
?>

<!-- ============================ JUDUL + TOMBOL ============================ -->
<div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-4">
  <div>
    <h2 class="fw-bold mb-1">Daftar Produk</h2>
    <p class="text-muted mb-0">Kelola data inventaris, harga jual, kategori, dan pantau stok barang warung.</p>
  </div>
  <div class="d-flex gap-2">
    <button type="button" class="btn btn-outline-secondary">
      <i class="bi bi-download"></i> Export Excel
    </button>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddProduct">
      <i class="bi bi-plus-lg"></i> Tambah Produk
    </button>
  </div>
</div>

<!-- ============================ 4 KARTU RINGKASAN ============================ -->
<div class="row g-3 mb-4">

  <div class="col-12 col-md-6 col-xl-3">
    <div class="card h-100 shadow-sm">
      <div class="card-body">
        <div class="d-flex justify-content-between text-secondary">
          <span>Total Produk</span><i class="bi bi-box-seam"></i>
        </div>
        <div class="d-flex justify-content-between align-items-center my-2">
          <h4 class="fw-bold mb-0"><?= $totalProduk ?> Item</h4>
          <span class="badge text-success border border-success-subtle bg-success-subtle">
            <i class="bi bi-check2"></i> Aktif
          </span>
        </div>
        <small class="text-muted">Semua terdaftar</small>
      </div>
    </div>
  </div>

  <div class="col-12 col-md-6 col-xl-3">
    <div class="card h-100 shadow-sm">
      <div class="card-body">
        <div class="d-flex justify-content-between text-secondary">
          <span>Total Nilai Stok</span><i class="bi bi-cash-stack"></i>
        </div>
        <h4 class="fw-bold my-2"><?= rupiah($totalNilai) ?></h4>
        <small class="text-muted">Estimasi modal barang</small>
      </div>
    </div>
  </div>

  <div class="col-12 col-md-6 col-xl-3">
    <div class="card h-100 shadow-sm">
      <div class="card-body">
        <div class="d-flex justify-content-between text-secondary">
          <span>Stok Menipis</span><i class="bi bi-exclamation-triangle"></i>
        </div>
        <div class="d-flex justify-content-between align-items-center my-2">
          <h4 class="fw-bold mb-0 <?= $menipis ? 'text-danger' : '' ?>"><?= count($menipis) ?> Item</h4>
          <?php if ($menipis): ?>
            <span class="badge text-danger border border-danger-subtle bg-danger-subtle">Kritis</span>
          <?php endif; ?>
        </div>
        <small class="text-muted">
          <?= $menipis ? htmlspecialchars(implode(', ', array_slice($menipis, 0, 2))) : 'Semua stok aman' ?>
        </small>
      </div>
    </div>
  </div>

  <div class="col-12 col-md-6 col-xl-3">
    <div class="card h-100 shadow-sm">
      <div class="card-body">
        <div class="d-flex justify-content-between text-secondary">
          <span>Kategori Aktif</span><i class="bi bi-layers"></i>
        </div>
        <h4 class="fw-bold my-2"><?= count($kategoriDipakai) ?> Kategori</h4>
        <small class="text-muted">
          <?= htmlspecialchars(implode(', ', array_slice($kategoriDipakai, 0, 2))) ?><?= count($kategoriDipakai) > 2 ? ', dll' : '' ?>
        </small>
      </div>
    </div>
  </div>

</div>

<!-- ============================ KARTU TABEL ============================ -->
<div class="card shadow-sm">

  <!-- Baris pencarian + filter -->
  <div class="card-body border-bottom">
    <div class="row g-2 align-items-center">
      <div class="col-12 col-md">
        <div class="input-group" style="max-width: 360px;">
          <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
          <input type="text" id="cariProduk" class="form-control" placeholder="Cari nama produk, kode...">
        </div>
      </div>
      <div class="col-6 col-md-auto">
        <select id="filterKategori" class="form-select">
          <option value="">Semua Kategori</option>
          <?php foreach ($categories as $namaKategori): ?>
            <option value="<?= htmlspecialchars($namaKategori) ?>"><?= htmlspecialchars($namaKategori) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-6 col-md-auto">
        <select id="filterStatus" class="form-select">
          <option value="">Semua Status Stok</option>
          <option value="aman">Stok Aman</option>
          <option value="menipis">Stok Menipis</option>
        </select>
      </div>
      <div class="col-auto">
        <button type="button" id="resetFilter" class="btn btn-outline-secondary" title="Reset filter">
          <i class="bi bi-arrow-counterclockwise"></i>
        </button>
      </div>
    </div>
  </div>

  <!-- Tabel -->
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr class="text-uppercase small text-secondary">
          <th class="ps-4">Produk</th>
          <th>Kode</th>
          <th>Kategori</th>
          <th class="text-end">Harga Modal</th>
          <th class="text-end">Harga Jual</th>
          <th class="text-center">Stok</th>
          <th class="text-center pe-4">Aksi</th>
        </tr>
      </thead>
      <tbody id="tabelProduk">

        <?php foreach ($products as $pr): ?>
          <?php
            $kode       = '#PRD-' . str_pad($pr['id'], 3, '0', STR_PAD_LEFT);  // tblproduct tidak punya kolom kode
            $menipisIni = $pr['stok'] <= $pr['min'];
          ?>
          <tr data-nama="<?= htmlspecialchars(strtolower($pr['nama'])) ?>"
              data-kode="<?= strtolower($kode) ?>"
              data-kategori="<?= htmlspecialchars($pr['kategori']) ?>"
              data-status="<?= $menipisIni ? 'menipis' : 'aman' ?>">

            <td class="ps-4">
              <div class="fw-semibold"><?= htmlspecialchars($pr['nama']) ?></div>
              <small class="text-muted">Satuan: <?= htmlspecialchars($pr['satuan']) ?></small>
            </td>
            <td><?= $kode ?></td>
            <td><span class="badge text-bg-light border"><?= htmlspecialchars($pr['kategori']) ?></span></td>
            <td class="text-end text-secondary"><?= rupiah($pr['modal']) ?></td>
            <td class="text-end fw-semibold"><?= rupiah($pr['jual']) ?></td>
            <td class="text-center">
              <span class="badge <?= $menipisIni
                  ? 'bg-danger-subtle text-danger border border-danger-subtle'
                  : 'bg-success-subtle text-success border border-success-subtle' ?>">
                <?= $pr['stok'] ?> <?= htmlspecialchars(strtolower($pr['satuan'])) ?>
              </span>
            </td>
            <td class="text-center pe-4">
              <button type="button" class="btn btn-sm btn-outline-secondary btn-edit"
                      data-bs-toggle="modal" data-bs-target="#modalEditProduct"
                      data-product-id="<?= $pr['id'] ?>"
                      data-name="<?= htmlspecialchars($pr['nama']) ?>"
                      data-category-id="<?= $pr['kategori_id'] ?>"
                      data-unit="<?= htmlspecialchars($pr['satuan']) ?>"
                      data-purchase="<?= $pr['modal'] ?>"
                      data-selling="<?= $pr['jual'] ?>"
                      data-stock="<?= $pr['stok'] ?>"
                      data-min="<?= $pr['min'] ?>"
                      data-expired="<?= htmlspecialchars($pr['expired']) ?>"
                      data-location="<?= htmlspecialchars($pr['lokasi']) ?>"
                      data-note="<?= htmlspecialchars($pr['catatan']) ?>">
                <i class="bi bi-pencil-square"></i>
              </button>
              <button type="button" class="btn btn-sm btn-outline-danger btn-hapus"
                      data-bs-toggle="modal" data-bs-target="#modalDeleteProduct"
                      data-product-id="<?= $pr['id'] ?>"
                      data-name="<?= htmlspecialchars($pr['nama']) ?>">
                <i class="bi bi-trash"></i>
              </button>
            </td>
          </tr>
        <?php endforeach; ?>

        <!-- Muncul kalau belum ada produk, atau hasil pencarian kosong -->
        <tr id="barisKosong" class="<?= $products ? 'd-none' : '' ?>">
          <td colspan="7" class="text-center text-muted py-5">
            <?= $products ? 'Produk tidak ditemukan.' : 'Belum ada produk. Klik "Tambah Produk" untuk mulai.' ?>
          </td>
        </tr>

      </tbody>
    </table>
  </div>

  <!-- Footer: jumlah data + pagination (pagination masih tampilan saja) -->
  <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-2 border-top">
    <span class="text-secondary">
      Menampilkan <strong id="jumlahTampil"><?= $totalProduk ?></strong>
      dari <strong><?= $totalProduk ?></strong> produk
    </span>
    <nav>
      <ul class="pagination mb-0">
        <li class="page-item disabled"><a class="page-link" href="#">&laquo;</a></li>
        <li class="page-item active"><a class="page-link" href="#">1</a></li>
        <li class="page-item disabled"><a class="page-link" href="#">&raquo;</a></li>
      </ul>
    </nav>
  </div>

</div>

<!-- ============================ MODAL (POPUP) ============================ -->
<!-- Popup memakai variabel $categories dari atas file ini -->
<?php include __DIR__ . '/modals/modal_add_product.php'; ?>
<?php include __DIR__ . '/modals/modal_edit_product.php'; ?>
<?php include __DIR__ . '/modals/modal_delete_product.php'; ?>

<!-- ============================ JAVASCRIPT ============================ -->
<!-- LETAKKAN DI SINI pemanggilan script.js, sama persis seperti yang sudah
     kamu pasang di index.php-mu sekarang. File ini tidak mengubah script.js. -->

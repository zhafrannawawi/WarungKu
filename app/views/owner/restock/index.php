<!-- ============================ JUDUL + TOMBOL ============================ -->
<div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-4">
  <div>
    <h2 class="fw-bold mb-1">Restock Barang</h2>
    <p class="text-muted mb-0">Catat barang masuk dan pantau riwayat restock stok dagangan.</p>
  </div>
  <div class="d-flex gap-2">
    <button type="button" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
      <i data-lucide="download" width="16" height="16"></i> Export Excel
    </button>
    <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalAddProduct">
      <i data-lucide="plus" width="16" height="16"></i> Catat Restock Baru
    </button>
  </div>
</div>

<!-- ============================ 4 KARTU RINGKASAN ============================ -->
<div class="row g-3 mb-4">

  <div class="col-12 col-md-6 col-xl-3">
    <div class="card h-100 shadow-sm">
      <div class="card-body">
        <div class="d-flex justify-content-between text-secondary">
          <span>Total Pengaluaran Beli</span><i data-lucide="wallet" width="16" height="16"></i>
        </div>
        <div class="d-flex justify-content-between align-items-center my-2">
          <h4 class="fw-bold mb-0">!!  Item</h4>
        </div>
        <small class="text-muted">Restock Masuk</small>
      </div>
    </div>
  </div>
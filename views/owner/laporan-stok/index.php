<?php include __DIR__ . '/../../partials/header.php'; ?>
<?php
$ringkasan    = $ringkasan    ?? [];
$produk       = $produk       ?? [];
$kategoriList = $kategoriList ?? [];
$q            = $q            ?? '';
$categoryId   = (int)($categoryId ?? 0);
$exportUrl    = $exportUrl    ?? '#';

$totalNilai  = $ringkasan['total_nilai']  ?? 0;
$jumlahSku   = $ringkasan['jumlah_sku']   ?? 0;
$totalFisik  = $ringkasan['total_fisik']  ?? 0;
$totalKritis = $ringkasan['total_kritis'] ?? 0;
$totalKeluar = $ringkasan['total_keluar'] ?? 0;
$periodeHari = $ringkasan['periode_hari'] ?? 30;

$totalValuasiFilter = array_sum(array_column($produk, 'valuasi'));

// ---------- Helper tampilan ----------
if (!function_exists('e')) {
    function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
}
if (!function_exists('rupiah')) {
    function rupiah($n) { return 'Rp ' . number_format((float)$n, 0, ',', '.'); }
}
if (!function_exists('angka')) {
    function angka($n) { return number_format((float)$n, 0, ',', '.'); }
}
?>


<div class="container-xl py-4">

    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Laporan Stok &amp; Inventaris</h1>
            <p class="text-secondary mb-0">Audit perputaran persediaan barang, stok masuk/keluar, dan estimasi valuasi aset.</p>
        </div>
        <a href="<?= e($exportUrl) ?>" class="btn bg-white border shadow-sm d-inline-flex align-items-center gap-2">
            <i data-lucide="download" width="16" height="16"></i> Export Laporan
        </a>
    </div>
<?php include __DIR__ . '/../../partials/alert.php'; ?>

    <!-- Kartu ringkasan -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-inline-flex bg-light text-secondary rounded-3 p-2 mb-3">
                        <i data-lucide="wallet" width="20" height="20"></i>
                    </div>
                    <div class="small text-secondary">Total Nilai Persediaan</div>
                    <div class="h3 fw-bold my-1"><?= rupiah($totalNilai) ?></div>
                    <div class="small text-secondary"><?= angka($jumlahSku) ?> SKU produk aktif</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-inline-flex bg-light text-secondary rounded-3 p-2">
                            <i data-lucide="package" width="20" height="20"></i>
                        </div>
                        <?php if ($totalKritis === 0): ?>
                            <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle d-inline-flex align-items-center gap-1">
                                <i data-lucide="check" width="12" height="12"></i> Aman
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="small text-secondary">Total Kuantitas Fisik</div>
                    <div class="h3 fw-bold my-1"><?= angka($totalFisik) ?> Unit</div>
                    <div class="small text-secondary">Semua gudang/etalase</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-inline-flex bg-danger-subtle text-danger rounded-3 p-2">
                            <i data-lucide="triangle-alert" width="20" height="20"></i>
                        </div>
                        <?php if ($totalKritis > 0): ?>
                            <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle">Perlu Restock</span>
                        <?php endif; ?>
                    </div>
                    <div class="small text-secondary">Stok Menipis / Kritis</div>
                    <div class="h3 fw-bold my-1 <?= $totalKritis > 0 ? 'text-danger' : '' ?>"><?= angka($totalKritis) ?> SKU</div>
                    <div class="small text-secondary">Di bawah batas minimum</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-inline-flex bg-light text-secondary rounded-3 p-2 mb-3">
                        <i data-lucide="shopping-bag" width="20" height="20"></i>
                    </div>
                    <div class="small text-secondary">Barang Keluar (<?= (int)$periodeHari ?> Hari)</div>
                    <div class="h3 fw-bold my-1"><?= angka($totalKeluar) ?> Pcs</div>
                    <div class="small text-secondary">Terjual ke konsumen</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">

            <!-- Filter (method GET, backend membaca ?q= dan ?category=) -->
            <form method="get" class="row g-2 justify-content-between mb-4">
                <div class="col-12 col-md-5 col-lg-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white text-secondary">
                            <i data-lucide="search" width="16" height="16"></i>
                        </span>
                        <input type="search" name="q" value="<?= e($q) ?>" class="form-control border-start-0"
                               placeholder="Cari nama produk, kategori...">
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-3">
                    <select name="category" class="form-select" onchange="this.form.submit()">
                        <option value="0">Semua Kategori</option>
                        <?php foreach ($kategoriList as $k): ?>
                            <option value="<?= (int)$k['id'] ?>" <?= $categoryId === (int)$k['id'] ? 'selected' : '' ?>>
                                <?= e($k['nama']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="small text-secondary">
                            <th class="fw-medium">Nama Barang</th>
                            <th class="fw-medium">Kategori</th>
                            <th class="fw-medium text-end">Stok Awal</th>
                            <th class="fw-medium">Masuk</th>
                            <th class="fw-medium">Keluar (Terjual)</th>
                            <th class="fw-medium" style="min-width:140px">Sisa Stok</th>
                            <th class="fw-medium text-end">Harga Beli (HPP)</th>
                            <th class="fw-medium text-end">Total Valuasi Aset</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($produk)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-secondary py-5">
                                <i data-lucide="package-open" width="32" height="32" class="mb-2"></i>
                                <div class="fw-medium">Belum ada data produk</div>
                                <div class="small">Tambahkan produk dan lakukan restock agar laporan stok muncul di sini.</div>
                            </td>
                        </tr>
                    <?php else: foreach ($produk as $p):
                        $kritis = (int)$p['sisa_stok'] <= (int)$p['stok_minimum'];
                        $total  = max((int)$p['stok_awal'] + (int)$p['masuk'], (int)$p['sisa_stok'], 1);
                        $persen = min(100, round(((int)$p['sisa_stok'] / $total) * 100));
                    ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-3 d-inline-flex align-items-center justify-content-center fw-semibold small
                                                <?= $kritis ? 'bg-danger-subtle text-danger' : 'bg-secondary-subtle text-secondary' ?>"
                                         style="width:36px;height:36px">
                                        <?= e(mb_strtoupper(mb_substr($p['nama'], 0, 1))) ?>
                                    </div>
                                    <span class="fw-semibold"><?= e($p['nama']) ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="badge rounded-pill bg-light text-secondary border fw-normal"><?= e($p['kategori']) ?></span>
                            </td>
                            <td class="text-end text-secondary"><?= angka($p['stok_awal']) ?></td>
                            <td>
                                <span class="d-inline-flex align-items-center gap-1 <?= $p['masuk'] > 0 ? 'text-success' : 'text-secondary' ?>">
                                    <i data-lucide="arrow-down-left" width="14" height="14"></i> +<?= angka($p['masuk']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="d-inline-flex align-items-center gap-1 <?= $p['keluar'] > 0 ? 'text-danger' : 'text-secondary' ?>">
                                    <i data-lucide="arrow-up-right" width="14" height="14"></i> -<?= angka($p['keluar']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge rounded-pill <?= $kritis
                                    ? 'bg-danger-subtle text-danger border border-danger-subtle'
                                    : 'bg-success-subtle text-success border border-success-subtle' ?>">
                                    <?= angka($p['sisa_stok']) ?> <?= e($p['satuan']) ?>
                                </span>
                                <div class="progress mt-1" style="height:5px;width:135px" role="progressbar"
                                     aria-valuenow="<?= (int)$persen ?>" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar <?= $kritis ? 'bg-danger' : 'bg-success' ?>"
                                         style="width: <?= (int)$persen ?>%"></div>
                                </div>
                            </td>
                            <td class="text-end text-secondary"><?= rupiah($p['harga_beli']) ?></td>
                            <td class="text-end fw-bold"><?= rupiah($p['valuasi']) ?></td>
                        </tr>
                    <?php endforeach; endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7" class="fw-semibold">Total valuasi (sesuai filter)</td>
                            <td class="text-end fw-bold"><?= rupiah($totalValuasiFilter) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../../partials/footer.php'; ?>

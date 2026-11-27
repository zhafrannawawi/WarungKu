<?php
// ============================================================
// MODEL: Produk (tblproduct)
// Fungsi database untuk mengelola data produk
// ============================================================

// 1. Ambil Semua Produk Milik Toko (Belum Dihapus)
function product_get_all(PDO $koneksi, string $store_id, bool $only_active = false): array
{
    $active_filter = $only_active ? " AND p.IsActive = 1" : "";
    $sql = "SELECT p.*, c.CategoryName
            FROM tblproduct p
            LEFT JOIN tblcategory c ON p.CategoryID = c.CategoryID
            WHERE p.StoreID = ? AND p.DeletedAt IS NULL{$active_filter}
            ORDER BY p.ProductName ASC";
    $stmt = $koneksi->prepare($sql);
    $stmt->execute([$store_id]);
    return $stmt->fetchAll();
}

// Helper: Cek apakah nama produk sudah dipakai di toko ini
function product_name_exists(PDO $koneksi, string $store_id, string $name, string $exclude_id = ''): bool
{
    $stmt = $koneksi->prepare(
        "SELECT ProductID FROM tblproduct
         WHERE StoreID = ? AND LOWER(ProductName) = LOWER(?) AND ProductID != ? AND DeletedAt IS NULL"
    );
    $stmt->execute([$store_id, $name, $exclude_id]);
    return (bool)$stmt->fetch();
}

// 2. Tambah Produk Baru
function product_add(PDO $koneksi, string $store_id, array $data): bool
{
    $check_category = $koneksi->prepare("SELECT CategoryID FROM tblcategory WHERE CategoryID = ? AND StoreID = ? AND DeletedAt IS NULL");
    $check_category->execute([$data['category_id'], $store_id]);
    if (!$check_category->fetch()) {
        throw new Exception('Gagal: Kategori tidak ditemukan atau sudah dihapus.');
    }

    if (product_name_exists($koneksi, $store_id, $data['name'])) {
        throw new Exception('Gagal: Nama produk sudah ada.');
    }

    $id   = generate_id($koneksi, 'tblproduct', 'ProductID', 'PRD-');
    $stmt = $koneksi->prepare(
        "INSERT INTO tblproduct (ProductID, StoreID, CategoryID, ProductName, Unit, PurchasePrice, SellingPrice, Stock, MinimumStock, Location, IsActive, CreatedAt)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())"
    );
    return $stmt->execute([
        $id,
        $store_id,
        $data['category_id'],
        $data['name'],
        $data['unit'],
        $data['purchase_price'],
        $data['selling_price'],
        $data['stock'],
        $data['min_stock'],
        $data['location'],
    ]);
}

// 3. Perbarui / Edit Data Produk
function product_update(PDO $koneksi, string $product_id, string $store_id, array $data, int $is_active = 1): bool
{
    if (product_name_exists($koneksi, $store_id, $data['name'], $product_id)) {
        throw new Exception('Gagal: Nama produk sudah dipakai.');
    }

    $stmt = $koneksi->prepare(
        "UPDATE tblproduct
         SET CategoryID = ?, ProductName = ?, Unit = ?, PurchasePrice = ?, SellingPrice = ?, Stock = ?, MinimumStock = ?, Location = ?, IsActive = ?
         WHERE ProductID = ? AND StoreID = ? AND DeletedAt IS NULL"
    );
    return $stmt->execute([
        $data['category_id'],
        $data['name'],
        $data['unit'],
        $data['purchase_price'],
        $data['selling_price'],
        $data['stock'],
        $data['min_stock'],
        $data['location'],
        $is_active,
        $product_id,
        $store_id,
    ]);
}

// 4. Hapus Produk (Soft Delete)
function product_delete(PDO $koneksi, string $product_id, string $store_id): bool
{
    $stmt = $koneksi->prepare("UPDATE tblproduct SET DeletedAt = NOW() WHERE ProductID = ? AND StoreID = ?");
    return $stmt->execute([$product_id, $store_id]);
}

// 5. Ambil Data Laporan Stok & Perputaran Barang per Periode
function product_get_stock_report(PDO $koneksi, string $store_id, string $from_date, string $to_date): array
{
    $start = $from_date . ' 00:00:00';
    $end   = $to_date   . ' 23:59:59';

    $sql = "SELECT p.*, c.CategoryName,
                   COALESCE((SELECT SUM(r.Quantity) FROM tblrestock r WHERE r.ProductID = p.ProductID AND r.RestockDate BETWEEN ? AND ?), 0) AS Masuk,
                   COALESCE((SELECT SUM(td.Quantity) FROM tbltransactiondetail td JOIN tbltransaction t ON td.TransactionID = t.TransactionID WHERE td.ProductID = p.ProductID AND t.TransactionDate BETWEEN ? AND ?), 0) AS Keluar,
                   COALESCE((SELECT SUM(r.Quantity) FROM tblrestock r WHERE r.ProductID = p.ProductID AND r.RestockDate > ?), 0) AS masuk_setelah,
                   COALESCE((SELECT SUM(td.Quantity) FROM tbltransactiondetail td JOIN tbltransaction t ON td.TransactionID = t.TransactionID WHERE td.ProductID = p.ProductID AND t.TransactionDate > ?), 0) AS keluar_setelah
            FROM tblproduct p
            LEFT JOIN tblcategory c ON p.CategoryID = c.CategoryID
            WHERE p.StoreID = ? AND p.DeletedAt IS NULL
            ORDER BY p.ProductName ASC";
    $stmt = $koneksi->prepare($sql);
    $stmt->execute([$start, $end, $start, $end, $end, $end, $store_id]);
    $rows = $stmt->fetchAll();

    // Hitung valuasi aset & stok awal (stok sekarang dikurangi mutasi setelah dan selama periode)
    foreach ($rows as $i => $row) {
        $stok_awal = (int)$row['Stock']
            - (int)$row['masuk_setelah'] + (int)$row['keluar_setelah']
            - (int)$row['Masuk']         + (int)$row['Keluar'];

        $rows[$i]['Valuasi']  = (float)$row['Stock'] * (float)$row['PurchasePrice'];
        $rows[$i]['StokAwal'] = max(0, $stok_awal);
    }

    return $rows;
}

// 6. Ambil Produk dengan Stok Menipis / Habis (F011: Stock <= MinimumStock)
function product_get_low_stock(PDO $koneksi, string $store_id): array
{
    $sql = "SELECT ProductID, ProductName, Stock, MinimumStock, Unit, Location
            FROM tblproduct
            WHERE StoreID = ? AND DeletedAt IS NULL AND IsActive = 1 AND Stock <= MinimumStock
            ORDER BY Stock ASC, ProductName ASC";
    $stmt = $koneksi->prepare($sql);
    $stmt->execute([$store_id]);
    return $stmt->fetchAll();
}

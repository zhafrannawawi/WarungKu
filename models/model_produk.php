<?php
// ============================================================
// MODEL: Produk (tblproduct)
// Fungsi database untuk mengelola data produk
// ============================================================

// 1. Ambil Semua Produk Milik Toko (Aktif & Belum Dihapus)
function product_get_all(PDO $koneksi, string $store_id): array
{
    $sql = "SELECT p.*, c.CategoryName 
            FROM tblproduct p 
            LEFT JOIN tblcategory c ON p.CategoryID = c.CategoryID 
            WHERE p.StoreID = ? AND p.DeletedAt IS NULL 
            ORDER BY p.ProductName ASC";
    $stmt = $koneksi->prepare($sql);
    $stmt->execute([$store_id]);
    return $stmt->fetchAll();
}

// 2. Tambah Produk Baru
function product_add(PDO $koneksi, string $store_id, string $category_id, string $name, string $unit, float $buy_price, float $sell_price, int $stock, int $min_stock, string $location): bool
{
    $check_category = $koneksi->prepare("SELECT CategoryID FROM tblcategory WHERE CategoryID = ? AND StoreID = ? AND DeletedAt IS NULL");
    $check_category->execute([$category_id, $store_id]);
    if (!$check_category->fetch()) {
        throw new Exception('Gagal: Kategori tidak ditemukan atau sudah dihapus.');
    }

    $check_name = $koneksi->prepare("SELECT ProductID FROM tblproduct WHERE StoreID = ? AND LOWER(ProductName) = LOWER(?) AND DeletedAt IS NULL");
    $check_name->execute([$store_id, $name]);
    if ($check_name->fetch()) {
        throw new Exception('Gagal: Nama produk sudah ada.');
    }

    $id   = generate_id($koneksi, 'tblproduct', 'ProductID', 'PRD-');
    $stmt = $koneksi->prepare(
        "INSERT INTO tblproduct (ProductID, StoreID, CategoryID, ProductName, Unit, PurchasePrice, SellingPrice, Stock, MinimumStock, Location, IsActive, CreatedAt)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())"
    );
    return $stmt->execute([$id, $store_id, $category_id, $name, $unit, $buy_price, $sell_price, $stock, $min_stock, $location]);
}

// 3. Perbarui / Edit Data Produk
function product_update(PDO $koneksi, string $product_id, string $store_id, string $category_id, string $name, string $unit, float $buy_price, float $sell_price, int $stock, int $min_stock, string $location, int $is_active = 1): bool
{
    $check_name = $koneksi->prepare("SELECT ProductID FROM tblproduct WHERE StoreID = ? AND LOWER(ProductName) = LOWER(?) AND ProductID != ? AND DeletedAt IS NULL");
    $check_name->execute([$store_id, $name, $product_id]);
    if ($check_name->fetch()) {
        throw new Exception('Gagal: Nama produk sudah dipakai.');
    }

    $stmt = $koneksi->prepare(
        "UPDATE tblproduct 
         SET CategoryID = ?, ProductName = ?, Unit = ?, PurchasePrice = ?, SellingPrice = ?, Stock = ?, MinimumStock = ?, Location = ?, IsActive = ?
         WHERE ProductID = ? AND StoreID = ? AND DeletedAt IS NULL"
    );
    return $stmt->execute([$category_id, $name, $unit, $buy_price, $sell_price, $stock, $min_stock, $location, $is_active, $product_id, $store_id]);
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
                   p.Stock * p.PurchasePrice AS Valuasi,
                   masuk_periode AS Masuk,
                   keluar_periode AS Keluar,
                   GREATEST(0, p.Stock - masuk_setelah + keluar_setelah - masuk_periode + keluar_periode) AS StokAwal
            FROM (
                SELECT prod.*,
                       COALESCE((SELECT SUM(r.Quantity) FROM tblrestock r WHERE r.ProductID = prod.ProductID AND r.RestockDate BETWEEN ? AND ?), 0) AS masuk_periode,
                       COALESCE((SELECT SUM(td.Quantity) FROM tbltransactiondetail td JOIN tbltransaction t ON td.TransactionID = t.TransactionID WHERE td.ProductID = prod.ProductID AND t.TransactionDate BETWEEN ? AND ?), 0) AS keluar_periode,
                       COALESCE((SELECT SUM(r.Quantity) FROM tblrestock r WHERE r.ProductID = prod.ProductID AND r.RestockDate > ?), 0) AS masuk_setelah,
                       COALESCE((SELECT SUM(td.Quantity) FROM tbltransactiondetail td JOIN tbltransaction t ON td.TransactionID = t.TransactionID WHERE td.ProductID = prod.ProductID AND t.TransactionDate > ?), 0) AS keluar_setelah
                FROM tblproduct prod
                WHERE prod.StoreID = ? AND prod.DeletedAt IS NULL
            ) p
            LEFT JOIN tblcategory c ON p.CategoryID = c.CategoryID
            ORDER BY p.ProductName ASC";
    $stmt = $koneksi->prepare($sql);
    $stmt->execute([$start, $end, $start, $end, $end, $end, $store_id]);
    return $stmt->fetchAll();
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

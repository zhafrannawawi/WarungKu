<?php
// ============================================================
// MODEL: Produk (tblproduct)
// Fungsi database untuk mengelola data produk
// ============================================================

// 1. Ambil Semua Produk Milik Toko
function product_get_all(PDO $koneksi, int $store_id): array
{
    $sql = "SELECT p.*, c.CategoryName 
            FROM tblproduct p 
            LEFT JOIN tblcategory c ON p.CategoryID = c.CategoryID 
            WHERE p.StoreID = ? AND p.IsActive = 1 
            ORDER BY p.ProductName ASC";
    $stmt = $koneksi->prepare($sql);
    $stmt->execute([$store_id]);
    return $stmt->fetchAll();
}

// 2. Tambah Produk Baru
function product_add(PDO $koneksi,int $store_id,int $category_id,string $name,string $unit,float $buy_price,float $sell_price,int $stock,int $min_stock,string $location): bool {
    $check_category = $koneksi->prepare("SELECT CategoryID FROM tblcategory WHERE CategoryID = ? AND StoreID = ?");
    $check_category->execute([$category_id, $store_id]);
    if (!$check_category->fetch()) {
        throw new Exception('Gagal: Kategori belum tersedia.');
    }

    $stmt = $koneksi->prepare(
        "INSERT INTO tblproduct (StoreID, CategoryID, ProductName, Unit, PurchasePrice, SellingPrice, Stock, MinimumStock, Location, EntryDate, IsActive)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE(), 1)"
    );
    return $stmt->execute([$store_id, $category_id, $name, $unit, $buy_price, $sell_price, $stock, $min_stock, $location]);
}

// 3. Perbarui / Edit Data Produk
function product_update(PDO $koneksi, int $product_id, int $store_id, int $category_id, string $name, string $unit, float $buy_price, float $sell_price, int $stock, int $min_stock, string $location): bool {
    $stmt = $koneksi->prepare(
        "UPDATE tblproduct 
         SET CategoryID = ?, ProductName = ?, Unit = ?, PurchasePrice = ?, SellingPrice = ?, Stock = ?, MinimumStock = ?, Location = ?
         WHERE ProductID = ? AND StoreID = ?"
    );
    return $stmt->execute([$category_id, $name, $unit, $buy_price, $sell_price, $stock, $min_stock, $location, $product_id, $store_id]);
}

// 4. Hapus Produk (Soft Delete: IsActive = 0)
function product_delete(PDO $koneksi, int $product_id, int $store_id): bool
{
    $stmt = $koneksi->prepare("UPDATE tblproduct SET IsActive = 0 WHERE ProductID = ? AND StoreID = ?");
    return $stmt->execute([$product_id, $store_id]);
}

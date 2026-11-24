<?php
// ============================================================
// MODEL: Kategori Produk (tblcategory)
// Fungsi database untuk mengelola data kategori
// ============================================================

// 1. Ambil Semua Kategori Milik Toko
function category_get_all(PDO $koneksi, int $store_id): array
{
    $sql = "SELECT c.*, COUNT(p.ProductID) AS ProductCount 
            FROM tblcategory c 
            LEFT JOIN tblproduct p ON c.CategoryID = p.CategoryID AND p.IsActive = 1 
            WHERE c.StoreID = ? AND c.IsActive = 1 
            GROUP BY c.CategoryID 
            ORDER BY c.CategoryName ASC";
    $stmt = $koneksi->prepare($sql);
    $stmt->execute([$store_id]);
    return $stmt->fetchAll();
}

// 2. Tambah Kategori Baru
function category_add(PDO $koneksi, int $store_id, string $category_name): bool
{
    $stmt = $koneksi->prepare("INSERT INTO tblcategory (StoreID, CategoryName, IsActive) VALUES (?, ?, 1)");
    return $stmt->execute([$store_id, $category_name]);
}

// 3. Perbarui / Edit Nama Kategori
function category_update(PDO $koneksi, int $category_id, int $store_id, string $category_name): bool
{
    $stmt = $koneksi->prepare("UPDATE tblcategory SET CategoryName = ? WHERE CategoryID = ? AND StoreID = ?");
    return $stmt->execute([$category_name, $category_id, $store_id]);
}

// 4. Hapus Kategori (Soft Delete: IsActive = 0)
function category_delete(PDO $koneksi, int $category_id, int $store_id): bool
{
    $stmt = $koneksi->prepare("UPDATE tblcategory SET IsActive = 0 WHERE CategoryID = ? AND StoreID = ?");
    return $stmt->execute([$category_id, $store_id]);
}

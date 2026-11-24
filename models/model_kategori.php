<?php
// ============================================================
// MODEL: Kategori Produk (tblcategory)
// Fungsi database untuk mengelola data kategori
// ============================================================

// 1. Ambil Semua Kategori Milik Toko (Aktif & Belum Dihapus)
function category_get_all(PDO $koneksi, string $store_id): array
{
    $sql = "SELECT c.*, COUNT(p.ProductID) AS ProductCount 
            FROM tblcategory c 
            LEFT JOIN tblproduct p ON c.CategoryID = p.CategoryID AND p.DeletedAt IS NULL 
            WHERE c.StoreID = ? AND c.DeletedAt IS NULL 
            GROUP BY c.CategoryID 
            ORDER BY c.CategoryName ASC";
    $stmt = $koneksi->prepare($sql);
    $stmt->execute([$store_id]);
    return $stmt->fetchAll();
}

// 2. Tambah Kategori Baru
function category_add(PDO $koneksi, string $store_id, string $category_name): bool
{
    $id   = generate_id($koneksi, 'tblcategory', 'CategoryID', 'CAT-');
    $stmt = $koneksi->prepare("INSERT INTO tblcategory (CategoryID, StoreID, CategoryName, IsActive, CreatedAt) VALUES (?, ?, ?, 1, NOW())");
    return $stmt->execute([$id, $store_id, $category_name]);
}

// 3. Perbarui / Edit Nama Kategori
function category_update(PDO $koneksi, string $category_id, string $store_id, string $category_name): bool
{
    $stmt = $koneksi->prepare("UPDATE tblcategory SET CategoryName = ? WHERE CategoryID = ? AND StoreID = ? AND DeletedAt IS NULL");
    return $stmt->execute([$category_name, $category_id, $store_id]);
}

// 4. Hapus Kategori (Soft Delete)
function category_delete(PDO $koneksi, string $category_id, string $store_id): bool
{
    $stmt = $koneksi->prepare("UPDATE tblcategory SET DeletedAt = NOW() WHERE CategoryID = ? AND StoreID = ?");
    return $stmt->execute([$category_id, $store_id]);
}

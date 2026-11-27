<?php
// ============================================================
// MODEL: Kategori Produk (tblcategory)
// Fungsi database untuk mengelola data kategori
// ============================================================

// 1. Ambil Semua Kategori Milik Toko (Belum Dihapus)
function category_get_all(PDO $koneksi, string $store_id, bool $only_active = false): array
{
    $active_filter = $only_active ? " AND c.IsActive = 1" : "";
    $sql = "SELECT c.*, COUNT(p.ProductID) AS ProductCount
            FROM tblcategory c
            LEFT JOIN tblproduct p ON c.CategoryID = p.CategoryID AND p.DeletedAt IS NULL
            WHERE c.StoreID = ? AND c.DeletedAt IS NULL{$active_filter}
            GROUP BY c.CategoryID
            ORDER BY c.CategoryName ASC";
    $stmt = $koneksi->prepare($sql);
    $stmt->execute([$store_id]);
    return $stmt->fetchAll();
}

// Helper: Cek apakah nama kategori sudah dipakai di toko ini
function category_name_exists(PDO $koneksi, string $store_id, string $category_name, string $exclude_id = ''): bool
{
    $stmt = $koneksi->prepare(
        "SELECT CategoryID FROM tblcategory
         WHERE StoreID = ? AND LOWER(CategoryName) = LOWER(?) AND CategoryID != ? AND DeletedAt IS NULL"
    );
    $stmt->execute([$store_id, $category_name, $exclude_id]);
    return (bool)$stmt->fetch();
}

// 2. Tambah Kategori Baru
function category_add(PDO $koneksi, string $store_id, string $category_name): bool
{
    if (category_name_exists($koneksi, $store_id, $category_name)) {
        throw new Exception('Gagal: Nama kategori sudah ada.');
    }

    $id   = generate_id($koneksi, 'tblcategory', 'CategoryID', 'CAT-');
    $stmt = $koneksi->prepare("INSERT INTO tblcategory (CategoryID, StoreID, CategoryName, IsActive, CreatedAt) VALUES (?, ?, ?, 1, NOW())");
    return $stmt->execute([$id, $store_id, $category_name]);
}

// 3. Perbarui / Edit Nama & Status Kategori
function category_update(PDO $koneksi, string $category_id, string $store_id, string $category_name, int $is_active = 1): bool
{
    if (category_name_exists($koneksi, $store_id, $category_name, $category_id)) {
        throw new Exception('Gagal: Nama kategori sudah dipakai.');
    }

    $stmt = $koneksi->prepare("UPDATE tblcategory SET CategoryName = ?, IsActive = ? WHERE CategoryID = ? AND StoreID = ? AND DeletedAt IS NULL");
    return $stmt->execute([$category_name, $is_active, $category_id, $store_id]);
}

// 4. Hapus Kategori (Soft Delete)
function category_delete(PDO $koneksi, string $category_id, string $store_id): bool
{
    $stmt = $koneksi->prepare("UPDATE tblcategory SET DeletedAt = NOW() WHERE CategoryID = ? AND StoreID = ?");
    return $stmt->execute([$category_id, $store_id]);
}

<?php
// ============================================================
// MODEL KATEGORI PRODUK
// ============================================================

function category_get_all(PDO $koneksi, int $store_id) {
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

function category_add(PDO $koneksi, int $store_id, string $category_name) {
    $stmt = $koneksi->prepare("INSERT INTO tblcategory (StoreID, CategoryName, IsActive) VALUES (?, ?, 1)");
    return $stmt->execute([$store_id, $category_name]);
}

function category_update(PDO $koneksi, int $category_id, int $store_id, string $category_name) {
    $stmt = $koneksi->prepare("UPDATE tblcategory SET CategoryName = ? WHERE CategoryID = ? AND StoreID = ?");
    return $stmt->execute([$category_name, $category_id, $store_id]);
}

function category_delete(PDO $koneksi, int $category_id, int $store_id) {
    // Soft delete
    $stmt = $koneksi->prepare("UPDATE tblcategory SET IsActive = 0 WHERE CategoryID = ? AND StoreID = ?");
    return $stmt->execute([$category_id, $store_id]);
}

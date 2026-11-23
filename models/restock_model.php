<?php
// ============================================================
// MODEL RESTOCK
// ============================================================

function restock_get_all(PDO $koneksi, int $store_id) {
    $sql = "SELECT r.*, p.ProductName, u.Fullname as CashierName 
            FROM tblrestock r 
            LEFT JOIN tblproduct p ON r.ProductID = p.ProductID 
            LEFT JOIN tbluser u ON r.UserID = u.UserID 
            WHERE r.StoreID = ? 
            ORDER BY r.RestockDate DESC";
    $stmt = $koneksi->prepare($sql);
    $stmt->execute([$store_id]);
    return $stmt->fetchAll();
}

function restock_add(PDO $koneksi, int $store_id, int $product_id, int $user_id, int $qty, float $purchase_price, string $note) {
    try {
        $koneksi->beginTransaction();

        // 1. Simpan riwayat restock
        $stmt = $koneksi->prepare(
            "INSERT INTO tblrestock (StoreID, ProductID, UserID, Quantity, PurchasePrice, RestockDate, Note)
             VALUES (?, ?, ?, ?, ?, NOW(), ?)"
        );
        $stmt->execute([$store_id, $product_id, $user_id, $qty, $purchase_price, $note]);

        // 2. Tambah stok barang di tabel produk
        $stmt2 = $koneksi->prepare("UPDATE tblproduct SET Stock = Stock + ? WHERE ProductID = ? AND StoreID = ?");
        $stmt2->execute([$qty, $product_id, $store_id]);

        $koneksi->commit();
        return true;
    } catch (Exception $e) {
        $koneksi->rollBack();
        return false;
    }
}

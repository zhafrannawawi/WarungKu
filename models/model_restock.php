<?php
// ============================================================
// MODEL RESTOCK
// ============================================================

function restock_get_all(PDO $koneksi, string $store_id): array
{
    $sql = "SELECT r.*, p.ProductName, p.Unit, u.Fullname as CashierName 
            FROM tblrestock r 
            LEFT JOIN tblproduct p ON r.ProductID = p.ProductID 
            LEFT JOIN tbluser u ON r.UserID = u.UserID 
            WHERE r.StoreID = ? 
            ORDER BY r.RestockDate DESC";
    $stmt = $koneksi->prepare($sql);
    $stmt->execute([$store_id]);
    return $stmt->fetchAll();
}

function restock_add(PDO $koneksi, string $store_id, string $product_id, string $user_id, int $qty, float $purchase_price, string $note): bool
{
    try {
        $koneksi->beginTransaction();

        // 1. Simpan riwayat restock (Contoh: RCK-20261124-001)
        $restock_id = generate_id($koneksi, 'tblrestock', 'RestockID', 'RCK-' . date('Ymd') . '-');
        $stmt       = $koneksi->prepare(
            "INSERT INTO tblrestock (RestockID, StoreID, ProductID, UserID, Quantity, PurchasePrice, RestockDate, Note)
             VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)"
        );
        $stmt->execute([$restock_id, $store_id, $product_id, $user_id, $qty, $purchase_price, $note]);

        // 2. Tambah stok barang dan perbarui harga beli terakhir di tabel produk
        $stmt2 = $koneksi->prepare(
            "UPDATE tblproduct 
             SET Stock = Stock + ?, PurchasePrice = ? 
             WHERE ProductID = ? AND StoreID = ? AND DeletedAt IS NULL"
        );
        $stmt2->execute([$qty, $purchase_price, $product_id, $store_id]);

        $koneksi->commit();
        return true;
    } catch (Exception $e) {
        $koneksi->rollBack();
        return false;
    }
}

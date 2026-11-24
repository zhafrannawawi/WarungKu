<?php
// ============================================================
// MODEL: Transaksi & Ringkasan Harian (tbltransaction)
// ============================================================

// 1. Ringkasan Metrik Harian (Omzet, Tunai, QRIS, Laba Bersih Hari Ini)
function transaction_get_today_summary(PDO $koneksi, string $store_id): array
{
    $sql = "SELECT COUNT(*) AS total_trx,
                   COALESCE(SUM(TotalAmount), 0) AS omzet,
                   COALESCE(SUM(CASE WHEN PaymentMethod = 'cash' THEN TotalAmount ELSE 0 END), 0) AS tunai,
                   COALESCE(SUM(PaymentMethod = 'cash'), 0) AS jml_tunai,
                   COALESCE(SUM(CASE WHEN PaymentMethod = 'qris' THEN TotalAmount ELSE 0 END), 0) AS qris,
                   COALESCE(SUM(PaymentMethod = 'qris'), 0) AS jml_qris
            FROM tbltransaction
            WHERE StoreID = ? AND DATE(TransactionDate) = CURDATE()";
    $stmt = $koneksi->prepare($sql);
    $stmt->execute([$store_id]);
    $summary = $stmt->fetch();

    $sql_profit = "SELECT COALESCE(SUM((td.SellingPriceAtTransaction - td.PurchasePriceAtTransaction) * td.Quantity), 0)
                   FROM tbltransactiondetail td
                   JOIN tbltransaction t ON td.TransactionID = t.TransactionID
                   WHERE t.StoreID = ? AND DATE(t.TransactionDate) = CURDATE()";
    $stmt2 = $koneksi->prepare($sql_profit);
    $stmt2->execute([$store_id]);
    $summary['laba_bersih'] = (float)$stmt2->fetchColumn();

    return $summary;
}

// 2. 5 Transaksi Terbaru Hari Ini
function transaction_get_today_recent(PDO $koneksi, string $store_id, int $limit = 5): array
{
    $sql = "SELECT t.*, u.Fullname AS CashierName
            FROM tbltransaction t
            LEFT JOIN tbluser u ON t.UserID = u.UserID
            WHERE t.StoreID = ? AND DATE(t.TransactionDate) = CURDATE()
            ORDER BY t.TransactionDate DESC
            LIMIT " . (int)$limit;
    $stmt = $koneksi->prepare($sql);
    $stmt->execute([$store_id]);
    return $stmt->fetchAll();
}

// 3. Top Produk Paling Laku Hari Ini
function transaction_get_today_top_products(PDO $koneksi, string $store_id, int $limit = 5): array
{
    $sql = "SELECT p.ProductID, p.ProductName, p.Unit, p.Stock, p.MinimumStock,
                   COALESCE(c.CategoryName, 'Umum') AS CategoryName,
                   SUM(td.Quantity) AS Terjual,
                   SUM(td.Subtotal) AS TotalOmzet
            FROM tbltransactiondetail td
            JOIN tbltransaction t ON td.TransactionID = t.TransactionID
            JOIN tblproduct p ON td.ProductID = p.ProductID
            LEFT JOIN tblcategory c ON p.CategoryID = c.CategoryID
            WHERE t.StoreID = ? AND DATE(t.TransactionDate) = CURDATE()
            GROUP BY p.ProductID
            ORDER BY Terjual DESC, TotalOmzet DESC
            LIMIT " . (int)$limit;
    $stmt = $koneksi->prepare($sql);
    $stmt->execute([$store_id]);
    return $stmt->fetchAll();
}

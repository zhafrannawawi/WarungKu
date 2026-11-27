<?php
// ============================================================
// MODEL: Transaksi & Ringkasan Harian (tbltransaction)
// ============================================================

// Helper: Ringkasan transaksi hari ini (seluruh toko atau khusus 1 kasir)
function transaction_get_today_metrics(PDO $koneksi, string $store_id, ?string $user_id = null): array
{
    $user_filter = $user_id !== null ? " AND UserID = ?" : "";
    $params      = $user_id !== null ? [$store_id, $user_id] : [$store_id];

    $sql = "SELECT COUNT(*) AS total_trx,
                   COALESCE(SUM(TotalAmount), 0) AS omzet,
                   COALESCE(SUM(CASE WHEN PaymentMethod = 'cash' THEN TotalAmount ELSE 0 END), 0) AS tunai,
                   COALESCE(SUM(PaymentMethod = 'cash'), 0) AS jml_tunai,
                   COALESCE(SUM(CASE WHEN PaymentMethod = 'qris' THEN TotalAmount ELSE 0 END), 0) AS qris,
                   COALESCE(SUM(PaymentMethod = 'qris'), 0) AS jml_qris
            FROM tbltransaction
            WHERE StoreID = ?{$user_filter} AND DATE(TransactionDate) = CURDATE()";
    $stmt = $koneksi->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch();
}

// 1. Ringkasan Metrik Harian Owner (Omzet, Tunai, QRIS, Laba Bersih Hari Ini)
function transaction_get_today_summary(PDO $koneksi, string $store_id): array
{
    $summary = transaction_get_today_metrics($koneksi, $store_id);

    $sql_profit = "SELECT COALESCE(SUM((td.SellingPriceAtTransaction - td.PurchasePriceAtTransaction) * td.Quantity), 0)
                   FROM tbltransactiondetail td
                   JOIN tbltransaction t ON td.TransactionID = t.TransactionID
                   WHERE t.StoreID = ? AND DATE(t.TransactionDate) = CURDATE()";
    $stmt = $koneksi->prepare($sql_profit);
    $stmt->execute([$store_id]);
    $summary['laba_bersih'] = (float)$stmt->fetchColumn();

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
    $today = date('Y-m-d');
    return transaction_get_top_products($koneksi, $store_id, $today, $today, $limit);
}

// 4. Ringkasan Kerja Harian Kasir Tertentu (F013)
function transaction_get_cashier_today_summary(PDO $koneksi, string $store_id, string $user_id): array
{
    $summary = transaction_get_today_metrics($koneksi, $store_id, $user_id);
    $summary['total_penjualan'] = $summary['omzet'];
    return $summary;
}

// 5. Transaksi Terbaru Hari Ini Milik Kasir Tertentu (F013)
function transaction_get_cashier_today_recent(PDO $koneksi, string $store_id, string $user_id, int $limit = 5): array
{
    $sql = "SELECT t.*,
                   COALESCE((SELECT SUM(td.Quantity) FROM tbltransactiondetail td WHERE td.TransactionID = t.TransactionID), 0) AS TotalItem
            FROM tbltransaction t
            WHERE t.StoreID = ? AND t.UserID = ? AND DATE(t.TransactionDate) = CURDATE()
            ORDER BY t.TransactionDate DESC
            LIMIT " . (int)$limit;
    $stmt = $koneksi->prepare($sql);
    $stmt->execute([$store_id, $user_id]);
    return $stmt->fetchAll();
}

// 6. Riwayat Seluruh Transaksi Toko beserta Rincian Item (F009)
function transaction_get_history(PDO $koneksi, string $store_id, string $from_date, string $to_date): array
{
    $start = $from_date . ' 00:00:00';
    $end   = $to_date   . ' 23:59:59';

    $sql = "SELECT t.*, u.Fullname AS CashierName
            FROM tbltransaction t
            LEFT JOIN tbluser u ON t.UserID = u.UserID
            WHERE t.StoreID = ? AND t.TransactionDate BETWEEN ? AND ?
            ORDER BY t.TransactionDate DESC";
    $stmt = $koneksi->prepare($sql);
    $stmt->execute([$store_id, $start, $end]);
    $list = $stmt->fetchAll();

    if (empty($list)) {
        return [];
    }

    $ids          = array_column($list, 'TransactionID');
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $sql_items    = "SELECT td.*, p.ProductName, p.Unit
                     FROM tbltransactiondetail td
                     LEFT JOIN tblproduct p ON td.ProductID = p.ProductID
                     WHERE td.TransactionID IN ($placeholders)";
    $stmt2 = $koneksi->prepare($sql_items);
    $stmt2->execute($ids);

    $grouped = [];
    foreach ($stmt2->fetchAll() as $item) {
        $grouped[$item['TransactionID']][] = $item;
    }

    foreach ($list as $i => $row) {
        $list[$i]['Items'] = $grouped[$row['TransactionID']] ?? [];
    }

    return $list;
}

// 7. Simpan Transaksi Kasir POS Baru secara Atomik (F008)
function transaction_create(PDO $koneksi, string $store_id, string $user_id, string $payment_method, float $amount_paid, array $items): string
{
    if (empty($items)) {
        throw new Exception('Keranjang belanja masih kosong.');
    }
    if (!in_array($payment_method, ['cash', 'qris'], true)) {
        throw new Exception('Metode pembayaran tidak valid.');
    }

    $koneksi->beginTransaction();
    try {
        $stmt_prod     = $koneksi->prepare("SELECT * FROM tblproduct WHERE ProductID = ? AND StoreID = ? AND DeletedAt IS NULL AND IsActive = 1 FOR UPDATE");
        $total_amount  = 0.0;
        $prepared_rows = [];

        foreach ($items as $it) {
            $pid = trim((string)($it['id'] ?? ''));
            $qty = (int)($it['qty'] ?? 0);
            if ($pid === '' || $qty <= 0) {
                continue;
            }

            $stmt_prod->execute([$pid, $store_id]);
            $prod = $stmt_prod->fetch();
            if (!$prod) {
                throw new Exception("Produk tidak ditemukan atau non-aktif.");
            }
            if ((int)$prod['Stock'] < $qty) {
                throw new Exception("Stok {$prod['ProductName']} tidak mencukupi (tersisa {$prod['Stock']} {$prod['Unit']}).");
            }

            $sell_price    = (float)$prod['SellingPrice'];
            $buy_price     = (float)$prod['PurchasePrice'];
            $subtotal      = $sell_price * $qty;
            $total_amount += $subtotal;

            $prepared_rows[] = [
                'product_id' => $pid,
                'qty'        => $qty,
                'sell_price' => $sell_price,
                'buy_price'  => $buy_price,
                'subtotal'   => $subtotal,
            ];
        }

        if (empty($prepared_rows)) {
            throw new Exception('Tidak ada item valid di keranjang.');
        }

        if ($payment_method === 'qris') {
            $amount_paid = $total_amount;
        }
        if ($amount_paid < $total_amount) {
            throw new Exception('Nominal uang diterima kurang dari total tagihan.');
        }

        $change_amount = $amount_paid - $total_amount;
        $trx_id        = generate_id($koneksi, 'tbltransaction', 'TransactionID', 'TRX-' . date('Ymd') . '-');

        $stmt_trx = $koneksi->prepare(
            "INSERT INTO tbltransaction (TransactionID, StoreID, UserID, TransactionDate, TotalAmount, AmountPaid, ChangeAmount, PaymentMethod)
             VALUES (?, ?, ?, NOW(), ?, ?, ?, ?)"
        );
        $stmt_trx->execute([$trx_id, $store_id, $user_id, $total_amount, $amount_paid, $change_amount, $payment_method]);

        $stmt_detail = $koneksi->prepare(
            "INSERT INTO tbltransactiondetail (TransactionDetailID, TransactionID, ProductID, Quantity, SellingPriceAtTransaction, PurchasePriceAtTransaction, Subtotal)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt_stock = $koneksi->prepare("UPDATE tblproduct SET Stock = Stock - ? WHERE ProductID = ? AND StoreID = ?");

        foreach ($prepared_rows as $r) {
            $detail_id = generate_id($koneksi, 'tbltransactiondetail', 'TransactionDetailID', 'TRD-');
            $stmt_detail->execute([$detail_id, $trx_id, $r['product_id'], $r['qty'], $r['sell_price'], $r['buy_price'], $r['subtotal']]);
            $stmt_stock->execute([$r['qty'], $r['product_id'], $store_id]);
        }

        $koneksi->commit();
        return $trx_id;
    } catch (Exception $e) {
        $koneksi->rollBack();
        throw $e;
    }
}

// 8. Ambil 1 Nota Struk Lengkap untuk Preview Cetak
function transaction_get_receipt(PDO $koneksi, string $trx_id, string $store_id): ?array
{
    $stmt = $koneksi->prepare(
        "SELECT t.*, u.Fullname AS CashierName
         FROM tbltransaction t
         LEFT JOIN tbluser u ON t.UserID = u.UserID
         WHERE t.TransactionID = ? AND t.StoreID = ?"
    );
    $stmt->execute([$trx_id, $store_id]);
    $trx = $stmt->fetch();
    if (!$trx) {
        return null;
    }

    $stmt2 = $koneksi->prepare(
        "SELECT td.*, p.ProductName, p.Unit
         FROM tbltransactiondetail td
         LEFT JOIN tblproduct p ON td.ProductID = p.ProductID
         WHERE td.TransactionID = ?"
    );
    $stmt2->execute([$trx_id]);
    $trx['Items'] = $stmt2->fetchAll();

    return $trx;
}

// 9. Laporan Produk Terlaris per Periode (F014)
function transaction_get_top_products(PDO $koneksi, string $store_id, string $from_date, string $to_date, ?int $limit = null): array
{
    $start     = $from_date . ' 00:00:00';
    $end       = $to_date   . ' 23:59:59';
    $limit_sql = $limit !== null ? " LIMIT " . (int)$limit : "";

    $sql = "SELECT p.ProductID, p.ProductName, p.Unit, p.Stock, p.MinimumStock,
                   COALESCE(c.CategoryName, 'Umum') AS CategoryName,
                   SUM(td.Quantity) AS Terjual,
                   SUM(td.Subtotal) AS TotalOmzet,
                   SUM((td.SellingPriceAtTransaction - td.PurchasePriceAtTransaction) * td.Quantity) AS TotalLaba
            FROM tbltransactiondetail td
            JOIN tbltransaction t ON td.TransactionID = t.TransactionID
            JOIN tblproduct p ON td.ProductID = p.ProductID
            LEFT JOIN tblcategory c ON p.CategoryID = c.CategoryID
            WHERE t.StoreID = ? AND t.TransactionDate BETWEEN ? AND ?
            GROUP BY p.ProductID
            ORDER BY Terjual DESC, TotalOmzet DESC{$limit_sql}";
    $stmt = $koneksi->prepare($sql);
    $stmt->execute([$store_id, $start, $end]);
    return $stmt->fetchAll();
}

// 10. Laporan Laba Rugi Harian per Periode (F014)
function transaction_get_profit_loss_report(PDO $koneksi, string $store_id, string $from_date, string $to_date): array
{
    $start = $from_date . ' 00:00:00';
    $end   = $to_date   . ' 23:59:59';

    $sql = "SELECT DATE(t.TransactionDate) AS Tanggal,
                   COUNT(DISTINCT t.TransactionID) AS JumlahTransaksi,
                   COALESCE(SUM(td.Subtotal), 0) AS TotalOmzet,
                   COALESCE(SUM(td.PurchasePriceAtTransaction * td.Quantity), 0) AS TotalModal,
                   COALESCE(SUM((td.SellingPriceAtTransaction - td.PurchasePriceAtTransaction) * td.Quantity), 0) AS LabaBersih
            FROM tbltransaction t
            JOIN tbltransactiondetail td ON t.TransactionID = td.TransactionID
            WHERE t.StoreID = ? AND t.TransactionDate BETWEEN ? AND ?
            GROUP BY DATE(t.TransactionDate)
            ORDER BY Tanggal DESC";
    $stmt = $koneksi->prepare($sql);
    $stmt->execute([$store_id, $start, $end]);
    return $stmt->fetchAll();
}

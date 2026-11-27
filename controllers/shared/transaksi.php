<?php
// ============================================================
// SHARED CONTROLLER: Riwayat Transaksi (F009 — Owner & Kasir)
// ============================================================

require_once __DIR__ . '/../../config/app.php';

$store_id = require_auth();

// Filter Periode Tanggal (Default: Awal Bulan Ini s/d Hari Ini)
[$from_date, $to_date] = get_date_range();

$trx_list    = transaction_get_history($koneksi, $store_id, $from_date, $to_date);
$total_trx   = count($trx_list);
$total_omzet = 0;
$total_tunai = 0;
$total_qris  = 0;

foreach ($trx_list as $t) {
    $amount       = (float)$t['TotalAmount'];
    $total_omzet += $amount;
    if ($t['PaymentMethod'] === 'cash') {
        $total_tunai += $amount;
    } elseif ($t['PaymentMethod'] === 'qris') {
        $total_qris += $amount;
    }
}

$page_title = 'Riwayat Transaksi';
$page_name  = 'transaksi';
include __DIR__ . '/../../views/shared/transaksi/index.php';

<?php
// ============================================================
// SHARED CONTROLLER: Riwayat Transaksi (F009 — Owner & Kasir)
// ============================================================

require_once __DIR__ . '/../../config/app.php';

$store_id = require_auth();
$is_owner = ($_SESSION['role'] ?? '') === 'owner';

// Filter Periode Tanggal (Default: Awal Bulan Ini s/d Hari Ini)
[$from_date, $to_date] = get_date_range();

$trx_list     = transaction_get_history($koneksi, $store_id, $from_date, $to_date);
$total_trx    = count($trx_list);
$total_omzet  = array_sum(array_column($trx_list, 'TotalAmount'));
$total_tunai  = array_sum(array_map(fn($t) => $t['PaymentMethod'] === 'cash' ? (float)$t['TotalAmount'] : 0, $trx_list));
$total_qris   = array_sum(array_map(fn($t) => $t['PaymentMethod'] === 'qris' ? (float)$t['TotalAmount'] : 0, $trx_list));

$page_title = 'Riwayat Transaksi';
$page_name  = 'transaksi';
include __DIR__ . '/../../views/shared/transaksi/index.php';

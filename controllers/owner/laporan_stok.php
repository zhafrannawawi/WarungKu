<?php
// ============================================================
// CONTROLLER: Laporan Stok
// ============================================================

require_once __DIR__ . '/../../config/app.php';

if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'owner') {
    redirect('/login');
}
$store_id = (int)($_SESSION['store_id'] ?? 0);

// Filter Periode Tanggal (Default: Awal Bulan Ini s/d Hari Ini)
$from_date = $_GET['from'] ?? date('Y-m-01');
$to_date   = $_GET['to']   ?? date('Y-m-d');
if ($from_date > $to_date) {
    [$from_date, $to_date] = [$to_date, $from_date];
}

$product_list  = product_get_stock_report($koneksi, $store_id, $from_date, $to_date);
$category_list = category_get_all($koneksi, $store_id);

// Ringkasan Metrik Laporan Stok
$total_nilai  = array_sum(array_column($product_list, 'Valuasi'));
$total_fisik  = array_sum(array_column($product_list, 'Stock'));
$total_keluar = array_sum(array_column($product_list, 'Keluar'));
$stok_menipis = count(array_filter($product_list, fn($p) => (int)$p['Stock'] <= (int)$p['MinimumStock']));

$page_title = 'Laporan Stok & Inventaris';
$page_name  = 'laporan-stok';
include __DIR__ . '/../../views/owner/laporan-stok/index.php';

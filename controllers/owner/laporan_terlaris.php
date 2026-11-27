<?php
// ============================================================
// CONTROLLER: Laporan Produk Terlaris (F014)
// ============================================================

require_once __DIR__ . '/../../config/app.php';

$store_id = require_auth('owner');

// Filter Periode Tanggal (Default: Awal Bulan Ini s/d Hari Ini)
[$from_date, $to_date] = get_date_range();

$top_products  = transaction_get_top_products($koneksi, $store_id, $from_date, $to_date);
$category_list = category_get_all($koneksi, $store_id);

// Ringkasan Metrik Terlaris
$total_terjual = array_sum(array_column($top_products, 'Terjual'));
$total_omzet   = array_sum(array_column($top_products, 'TotalOmzet'));
$produk_juara  = $top_products[0] ?? null;

$cat_totals = [];
foreach ($top_products as $tp) {
    $cat_totals[$tp['CategoryName']] = ($cat_totals[$tp['CategoryName']] ?? 0) + (int)$tp['Terjual'];
}
arsort($cat_totals);
$kategori_juara     = array_key_first($cat_totals) ?? '-';
$kategori_juara_qty = $cat_totals[$kategori_juara] ?? 0;

$page_title = 'Laporan Terlaris';
$page_name  = 'laporan-terlaris';
include __DIR__ . '/../../views/owner/laporan-terlaris/index.php';

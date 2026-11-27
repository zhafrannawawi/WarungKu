<?php
// ============================================================
// CONTROLLER: Laporan Stok
// ============================================================

require_once __DIR__ . '/../../config/app.php';

$store_id = require_auth('owner');

// Filter Periode Tanggal (Default: Awal Bulan Ini s/d Hari Ini)
[$from_date, $to_date] = get_date_range();

$product_list  = product_get_stock_report($koneksi, $store_id, $from_date, $to_date);
$category_list = category_get_all($koneksi, $store_id);

// Ringkasan Metrik Laporan Stok
$total_nilai  = 0;
$total_fisik  = 0;
$total_keluar = 0;
$stok_menipis = 0;

foreach ($product_list as $p) {
    $total_nilai  += (float)$p['Valuasi'];
    $total_fisik  += (int)$p['Stock'];
    $total_keluar += (int)$p['Keluar'];
    if ((int)$p['Stock'] <= (int)$p['MinimumStock']) {
        $stok_menipis++;
    }
}

$page_title = 'Laporan Stok & Inventaris';
$page_name  = 'laporan-stok';
include __DIR__ . '/../../views/owner/laporan-stok/index.php';

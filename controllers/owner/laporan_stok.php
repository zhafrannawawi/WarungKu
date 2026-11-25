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
$total_nilai  = array_sum(array_column($product_list, 'Valuasi'));
$total_fisik  = array_sum(array_column($product_list, 'Stock'));
$total_keluar = array_sum(array_column($product_list, 'Keluar'));
$stok_menipis = count(array_filter($product_list, fn($p) => (int)$p['Stock'] <= (int)$p['MinimumStock']));

$page_title = 'Laporan Stok & Inventaris';
$page_name  = 'laporan-stok';
include __DIR__ . '/../../views/owner/laporan-stok/index.php';

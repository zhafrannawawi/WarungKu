<?php
// ============================================================
// CONTROLLER: Dashboard Harian Owner
// ============================================================

require_once __DIR__ . '/../../config/app.php';

if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'owner') {
    redirect('/login');
}

$store_id = (string)($_SESSION['store_id'] ?? '');

// 1. Ringkasan Kas & Laba Hari Ini
$summary     = transaction_get_today_summary($koneksi, $store_id);
$recent_trx  = transaction_get_today_recent($koneksi, $store_id, 5);
$top_today   = transaction_get_today_top_products($koneksi, $store_id, 5);

$rata_trx    = $summary['total_trx'] > 0 ? ($summary['omzet'] / $summary['total_trx']) : 0;
$margin_pct  = $summary['omzet'] > 0 ? round(($summary['laba_bersih'] / $summary['omzet']) * 100) : 0;

// 2. Status Inventaris & Peringatan Stok Menipis
$all_products   = product_get_all($koneksi, $store_id);
$total_katalog  = count($all_products);
$low_stock_list = array_slice(
    array_values(array_filter($all_products, fn($p) => (int)$p['Stock'] <= (int)$p['MinimumStock'])),
    0,
    5
);

// 3. Format Tanggal Indonesia Singkat
$hari_id  = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$bulan_id = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
$tanggal_hari_ini = $hari_id[(int)date('w')] . ', ' . date('d') . ' ' . $bulan_id[(int)date('n')] . ' ' . date('Y');

$page_title = 'Dashboard Harian';
$page_name  = 'dashboard';
include __DIR__ . '/../../views/owner/dashboard/index.php';

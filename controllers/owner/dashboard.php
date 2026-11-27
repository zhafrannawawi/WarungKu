<?php
// ============================================================
// CONTROLLER: Dashboard Harian Owner
// ============================================================

require_once __DIR__ . '/../../config/app.php';

$store_id = require_auth('owner');

// 1. Ringkasan Kas & Laba Hari Ini
$summary     = transaction_get_today_summary($koneksi, $store_id);
$recent_trx  = transaction_get_today_recent($koneksi, $store_id, 5);
$top_today   = transaction_get_today_top_products($koneksi, $store_id, 5);

$rata_trx    = $summary['total_trx'] > 0 ? ($summary['omzet'] / $summary['total_trx']) : 0;
$margin_pct  = $summary['omzet'] > 0 ? round(($summary['laba_bersih'] / $summary['omzet']) * 100) : 0;

// 2. Status Inventaris & Peringatan Stok Menipis (maksimal 5 item)
$all_products   = product_get_all($koneksi, $store_id);
$total_katalog  = count($all_products);
$low_stock_list = [];
foreach ($all_products as $p) {
    if ((int)$p['Stock'] <= (int)$p['MinimumStock']) {
        $low_stock_list[] = $p;
        if (count($low_stock_list) === 5) {
            break;
        }
    }
}

// 3. Format Tanggal Indonesia
$tanggal_hari_ini = tanggal_id();

$page_title = 'Dashboard Harian';
$page_name  = 'dashboard';
include __DIR__ . '/../../views/owner/dashboard/index.php';

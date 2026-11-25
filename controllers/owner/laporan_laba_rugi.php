<?php
// ============================================================
// CONTROLLER: Laporan Laba Rugi (F014)
// ============================================================

require_once __DIR__ . '/../../config/app.php';

$store_id = require_auth('owner');

// Filter Periode Tanggal (Default: Awal Bulan Ini s/d Hari Ini)
[$from_date, $to_date] = get_date_range();

$daily_report = transaction_get_profit_loss_report($koneksi, $store_id, $from_date, $to_date);

// Ringkasan Metrik Laba Rugi
$total_trx   = array_sum(array_column($daily_report, 'JumlahTransaksi'));
$total_omzet = array_sum(array_column($daily_report, 'TotalOmzet'));
$total_modal = array_sum(array_column($daily_report, 'TotalModal'));
$total_laba  = array_sum(array_column($daily_report, 'LabaBersih'));
$margin_pct  = $total_omzet > 0 ? round(($total_laba / $total_omzet) * 100, 1) : 0;

$page_title = 'Laporan Laba Rugi';
$page_name  = 'laporan-laba-rugi';
include __DIR__ . '/../../views/owner/laporan_laba_rugi/index.php';

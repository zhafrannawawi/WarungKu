<?php
// ============================================================
// CONTROLLER: Dashboard Ringkasan Kerja Harian Kasir (F013)
// ============================================================

require_once __DIR__ . '/../../config/app.php';

$store_id = require_auth('kasir');
$user_id  = (string)($_SESSION['user_id'] ?? '');

$summary    = transaction_get_cashier_today_summary($koneksi, $store_id, $user_id);
$recent_trx = transaction_get_cashier_today_recent($koneksi, $store_id, $user_id, 5);

$tanggal_hari_ini = tanggal_id();

$page_title = 'Dashboard Kerja Kasir';
$page_name  = 'dashboard';
include __DIR__ . '/../../views/cashier/dashboard/index.php';

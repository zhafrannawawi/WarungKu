<?php
require_once __DIR__ . '/../../config/app.php';

if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    redirect('/login');
}

$store_id = $_SESSION['store_id'];
$metrics  = user_count_metrics($koneksi, $store_id);

$page_title = 'Owner Dashboard';
include __DIR__ . '/../../views/owner/dashboard/index.php';

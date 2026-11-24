<?php
require_once __DIR__ . '/../../config/app.php';

if (empty($_SESSION['user_id'])) {
    redirect('/login');
}

$page_title = 'Kasir Dashboard';
$page_name  = 'dashboard';
include __DIR__ . '/../../views/cashier/dashboard/index.php';

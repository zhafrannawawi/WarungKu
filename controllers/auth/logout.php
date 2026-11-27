<?php
require_once __DIR__ . '/../../config/app.php';

if (!empty($_SESSION['user_id'])) {
    user_set_active($koneksi, (string)$_SESSION['user_id'], 0);
}

$_SESSION = [];
session_destroy();

redirect('/login');

<?php
date_default_timezone_set('Asia/Jakarta');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

// 1. Mulai Sesi
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Pengaturan & Koneksi Database
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'warungku';

try {
    $koneksi = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    $koneksi->exec("SET time_zone = '+07:00'");
} catch (PDOException $e) {
    die("Koneksi Database Gagal: " . $e->getMessage());
}

// 3. Panggil File Model Satu per Satu secara Jelas
require_once __DIR__ . '/../models/model_pengguna.php';
require_once __DIR__ . '/../models/model_toko.php';
require_once __DIR__ . '/../models/model_produk.php';
require_once __DIR__ . '/../models/model_kategori.php';
require_once __DIR__ . '/../models/model_restock.php';
require_once __DIR__ . '/../models/model_transaksi.php';

// 4. Fungsi Pembantu Sederhana
function redirect(string $url): void {
    header("Location: $url");
    exit;
}

function set_alert(string $type, string $message): void {
    $_SESSION['alert'] = [
        'type'    => $type,
        'message' => $message
    ];
}

function get_alert(): ?array {
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        unset($_SESSION['alert']);
        return $alert;
    }
    return null;
}


// Generator ID Berurutan Ber-prefix (misal: PRD-001, RCK-20261124-001)
function generate_id(PDO $koneksi, string $table, string $column, string $prefix): string
{
    $len  = strlen($prefix);
    $stmt = $koneksi->prepare("SELECT MAX(CAST(SUBSTRING($column, ? + 1) AS UNSIGNED)) FROM $table WHERE $column LIKE ?");
    $stmt->execute([$len, $prefix . '%']);
    $next = ((int)$stmt->fetchColumn()) + 1;
    return $prefix . str_pad((string)$next, 3, '0', STR_PAD_LEFT);
}

// Guard autentikasi, role & status IsActive; mengembalikan store_id aktif
function require_auth(?string $role = null): string
{
    global $koneksi;

    if (empty($_SESSION['user_id']) || ($role !== null && ($_SESSION['role'] ?? '') !== $role)) {
        redirect('/login');
    }

    $active_user = user_find_by_id($koneksi, (string)$_SESSION['user_id']);
    if (!$active_user || !(int)$active_user['IsActive']) {
        $_SESSION = [];
        session_destroy();
        redirect('/login');
    }

    return (string)($_SESSION['store_id'] ?? '');
}

// Format tanggal hari ini dalam bahasa Indonesia (misal: Rabu, 25 November 2026)
function tanggal_id(?int $timestamp = null): string
{
    $ts    = $timestamp ?? time();
    $hari  = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    return $hari[(int)date('w', $ts)] . ', ' . date('d', $ts) . ' ' . $bulan[(int)date('n', $ts)] . ' ' . date('Y', $ts);
}

// Ambil rentang tanggal filter GET (?from=...&to=...) dengan auto-swap jika terbalik
function get_date_range(): array
{
    $from = $_GET['from'] ?? date('Y-m-01');
    $to   = $_GET['to'] ?? date('Y-m-d');
    if ($from > $to) {
        [$from, $to] = [$to, $from];
    }
    return [$from, $to];
}

// Escape teks agar aman ditampilkan di HTML
function escape_html($text): string
{
    return htmlspecialchars((string)($text ?? ''), ENT_QUOTES, 'UTF-8');
}

// Format angka ribuan Indonesia (misal: 15.000)
function format_number($value): string
{
    return number_format((float)$value, 0, ',', '.');
}

// Format mata uang Rupiah (misal: Rp 15.000)
function format_rupiah($value): string
{
    return 'Rp ' . format_number($value);
}

// Encode data PHP menjadi atribut JSON yang aman untuk onclick HTML
function json_attr($data): string
{
    return htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8');
}

// URL dashboard sesuai role pengguna
function dashboard_url(?string $role = null): string
{
    $active_role = $role ?? ($_SESSION['role'] ?? '');
    return $active_role === 'owner' ? '/owner/dashboard' : '/cashier/dashboard';
}
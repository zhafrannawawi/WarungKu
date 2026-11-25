<?php
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

// Guard autentikasi & role; mengembalikan store_id aktif
function require_auth(?string $role = null): string
{
    if (empty($_SESSION['user_id']) || ($role !== null && ($_SESSION['role'] ?? '') !== $role)) {
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
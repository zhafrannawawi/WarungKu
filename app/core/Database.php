<?php
// ============================================================
// app/core/Database.php
// Class pembuat koneksi database (pola Singleton):
// koneksi dibuat SEKALI, lalu dipakai ulang di seluruh aplikasi.
// ============================================================
class Database
{
    // Menyimpan koneksi. Awalnya null (belum ada koneksi).
    // static = dibagi ke seluruh aplikasi, private = hanya bisa diakses dari class ini.
    private static $connection = null;

    // Dipanggil dari mana saja dengan: Database::connect()
    public static function connect()
    {
        // Buat koneksi hanya jika belum pernah dibuat.
        if (self::$connection === null) {

            // Ambil pengaturan (host, nama db, user, pass, charset)
            // dari file config yang mengembalikan array.
            $config = require APP_PATH . '/config/database.php';

            // DSN = "alamat lengkap" database dalam satu teks.
            // Contoh hasil: mysql:host=localhost;dbname=shop_db;charset=utf8mb4
            $dsn = 'mysql:host=' . $config['host']
                 . ';dbname=' . $config['name']
                 . ';charset=' . $config['charset'];

            // Buat koneksi memakai PDO, lalu simpan agar bisa dipakai ulang.
            self::$connection = new PDO($dsn, $config['user'], $config['pass'], [

                // Jika query SQL salah, lempar exception (error jelas),
                // bukan diam-diam gagal.
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,

                // Hasil query berupa array dengan nama kolom,
                // contoh: ['id' => 1, 'name' => 'Kopi'] (tanpa indeks angka ganda).
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }

        // Kembalikan koneksi (baru dibuat atau yang sudah ada).
        return self::$connection;
    }
}
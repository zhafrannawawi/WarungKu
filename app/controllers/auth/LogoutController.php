<?php
// Controller Logout Umum (Bisa digunakan untuk Owner maupun Kasir)
class AuthLogoutController extends Controller
{
    public function index()
    {
        // 1. Bersihkan semua kemungkinan sesi aktif (owner maupun cashier)
        foreach (['owner', 'cashier'] as $active_role) {
            if (isset($_COOKIE[$active_role . '_session'])) {
                session_name($active_role . '_session');
                session_start();
                $_SESSION = [];

                // Hapus cookie sesi dari browser
                if (ini_get("session.use_cookies")) {
                    $cookie_params = session_get_cookie_params();
                    setcookie(
                        session_name(),
                        '',
                        time() - 42000,
                        $cookie_params["path"],
                        $cookie_params["domain"],
                        $cookie_params["secure"],
                        $cookie_params["httponly"]
                    );
                }

                session_destroy();
            }
        }

        // 2. Beritahu browser agar tidak menyimpan cache
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');

        // 3. Arahkan kembali ke halaman login
        header('Location: /login');
        exit;
    }
}

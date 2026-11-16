<?php
class CashierLogoutController extends Controller
{
    public function index()
    {
        // FIX: resume sesi yang benar sebelum destroy
        session_name('cashier_session');
        session_start();
        $_SESSION = [];
        session_destroy();

        header('Location: /login');
        exit;
    }
}

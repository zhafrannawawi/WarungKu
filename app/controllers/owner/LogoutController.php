<?php
class OwnerLogoutController extends Controller
{
    public function index()
    {
        // FIX: resume sesi yang benar sebelum destroy
        session_name('owner_session');
        session_start();
        $_SESSION = [];
        session_destroy();

        header('Location: /login');
        exit;
    }
}

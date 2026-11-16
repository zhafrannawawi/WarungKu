<?php
class AuthRegisterController extends Controller
{
    public function index()
    {
        // Beritahu browser agar tidak menyimpan cache formulir pendaftaran
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');

        $error_text = '';
        $old_input  = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $store_name    = trim($_POST['store_name'] ?? '');
            $store_address = trim($_POST['store_address'] ?? '');
            $full_name     = trim($_POST['full_name'] ?? '');
            $email         = trim($_POST['email'] ?? '');
            $phone_number  = trim($_POST['phone_number'] ?? '');
            $user_name     = trim($_POST['user_name'] ?? '');
            $password      = $_POST['user_password'] ?? '';
            $pass_again    = $_POST['password_again'] ?? '';

            // Simpan input lama agar tidak hilang jika ada error
            $old_input = [
                'store_name'    => $store_name,
                'store_address' => $store_address,
                'full_name'     => $full_name,
                'email'         => $email,
                'phone_number'  => $phone_number,
                'user_name'     => $user_name,
            ];

            $user_model = $this->model('UserModel');

            if ($store_name == '' || $full_name == '' || $user_name == '' || $email == '' || $phone_number == '') {
                $error_text = 'Semua data bertanda bintang (*) wajib diisi.';
            } elseif (strlen($password) < 6) {
                $error_text = 'Password minimal 6 karakter.';
            } elseif ($password != $pass_again) {
                $error_text = 'Konfirmasi password tidak cocok.';
            } else {
                $saved = $user_model->add_owner($store_name, $store_address, $full_name, $email, $phone_number, $user_name, $password);
                if ($saved) {
                    header('Location: /login?registered=1');
                    exit;
                } else {
                    $error_text = 'Gagal mendaftar, coba lagi nanti.';
                }
            }
        }

        $this->view('auth', 'register', [
            'page_title' => 'Register',
            'error_text' => $error_text,
            'old_input'  => $old_input,
        ], 'auth');
    }
}

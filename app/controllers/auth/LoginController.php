<?php
class AuthLoginController extends Controller
{
    public function index()
    {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');

        $error_text   = '';
        $success_text = isset($_GET['registered']) ? 'Registrasi berhasil, silakan masuk.' : '';
        $modal_error  = '';
        $reset_user_id = null;

        $user_model = $this->model('UserModel');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $action = $_POST['action'] ?? 'login';

            // 1. Aksi Login Standar
            if ($action === 'login') {
                $user_name     = trim($_POST['user_name'] ?? '');
                $user_password = $_POST['user_password'] ?? '';

                $user_data = $user_model->find_user($user_name);

                if ($user_password == $user_data)
                {

                }
                if ($user_data && password_verify($user_password, $user_data['Password'])) {
                    $role_name = trim($user_data['Role']);

                    session_name($role_name . '_session');
                    session_start();
                    session_regenerate_id(true);

                    $_SESSION['user_id']   = $user_data['UserID'] ?? $user_data['UserId'];
                    $_SESSION['user_name'] = $user_data['Username'];
                    $_SESSION['role']      = $role_name;
                    $_SESSION['store_id']  = $user_data['StoreID'] ?? $user_data['StoreId'];

                    $user_store = $user_model->get_store_name($_SESSION['store_id']);
                    $_SESSION['store_name'] = $user_store['StoreName'] ?? 'WarungKu';

                    header('Location: /' . $role_name . '/dashboard');
                    exit;
                }

                $error_text = 'Username atau password salah.';
            }

            // 2. Aksi Tahap 1: Verifikasi Akun Lupa Password
            elseif ($action === 'verify_reset') {
                $email        = trim($_POST['reset_email'] ?? '');
                $phone_number = trim($_POST['reset_phone'] ?? '');

                $user_data = $user_model->find_user_verify($email, $phone_number);

                if ($user_data) {
                    $reset_user_id = $user_data['UserID'];
                } else {
                    $modal_error = 'Email atau No. WhatsApp tidak cocok dengan akun manapun.';
                }
            }

            // 3. Aksi Tahap 2: Simpan Password Baru
            elseif ($action === 'save_new_password') {
                $user_id    = (int)($_POST['reset_user_id'] ?? 0);
                $new_pass   = $_POST['new_password'] ?? '';
                $pass_again = $_POST['confirm_new_password'] ?? '';

                if (strlen($new_pass) < 6) {
                    $modal_error = 'Password baru minimal 6 karakter.';
                    $reset_user_id = $user_id;
                } elseif ($new_pass !== $pass_again) {
                    $modal_error = 'Konfirmasi password baru tidak cocok.';
                    $reset_user_id = $user_id;
                } else {
                    $user_model->update_password($user_id, $new_pass);
                    $success_text = 'Password berhasil diubah! Silakan masuk dengan password baru Anda.';
                }
            }
        }

        $this->view('auth', 'login', [
            'page_title'    => 'Login',
            'error_text'    => $error_text,
            'success_text'  => $success_text,
            'modal_error'   => $modal_error,
            'reset_user_id' => $reset_user_id,
        ], 'auth');
    }

    
}

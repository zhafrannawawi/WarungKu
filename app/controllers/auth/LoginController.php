<?php
class AuthLoginController extends Controller
{
    public function index()
    {
        $error_text   = '';
        $success_text = isset($_GET['registered']) ? 'Register success, please login.' : '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_name     = trim($_POST['user_name'] ?? '');
            $user_password = $_POST['user_password'] ?? '';

            $user_model = $this->model('UserModel');
            $user_data  = $user_model->find_user($user_name);

            if ($user_data && password_verify($user_password, $user_data['Password'])) {
                $role_name = trim($user_data['Role']);

                session_name($role_name . '_session');
                session_start();
                session_regenerate_id(true);

                // Menggunakan penulisan kolom database asli: UserID dan StoreID
                $_SESSION['user_id']   = $user_data['UserID'] ?? $user_data['UserId'];
                $_SESSION['user_name'] = $user_data['Username'];
                $_SESSION['role']      = $role_name;
                $_SESSION['store_id']  = $user_data['StoreID'] ?? $user_data['StoreId'];

                header('Location: /' . $role_name . '/dashboard');
                exit;
            }

            $error_text   = 'Username atau password salah.';
            $success_text = '';
        }

        $this->view('auth', 'login', [
            'page_title'   => 'Login',
            'error_text'   => $error_text,
            'success_text' => $success_text,
        ], 'auth');
    }

}

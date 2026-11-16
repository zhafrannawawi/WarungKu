<?php
class OwnerAkunKasirController extends Controller
{
    public function index()
    {
        $user_model = $this->model('UserModel');
        $user_list = $user_model->get_user_by_store($_SESSION['store_id']);
        $count_user = $user_model->count_user($_SESSION['store_id']);
        

        $success_message = $_SESSION['success_message'] ?? '';
        $error_message   = $_SESSION['error_message'] ?? '';
        unset($_SESSION['success_message'], $_SESSION['error_message']);

        $this->view('owner', 'akun-kasir', [
            'page_title'      => 'Akun Kasir',
            'user_list'       => $user_list,
            'count_user'      => $count_user,
            'success_message' => $success_message,
            'error_message'   => $error_message,
        ]);
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_model   = $this->model('UserModel');
            $full_name    = trim($_POST['full_name'] ?? '');
            $user_name    = trim($_POST['user_name'] ?? '');
            $email        = trim($_POST['email'] ?? '');
            $phone_number = trim($_POST['phone_number'] ?? '');

            if ($full_name === '' || $user_name === '') {
                $_SESSION['error_message'] = 'Nama lengkap dan username wajib diisi.';
            } else {
                $is_saved = $user_model->add_cashier($_SESSION['store_id'], $full_name, $user_name, $email, $phone_number);
                $_SESSION[$is_saved ? 'success_message' : 'error_message'] = $is_saved
                    ? 'Akun kasir berhasil ditambahkan!'
                    : 'Username sudah dipakai, coba username lain.';
            }
        }

        header('Location: /owner/akun-kasir');
        exit;
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_model   = $this->model('UserModel');
            $user_id      = (int) ($_POST['user_id'] ?? 0);
            $full_name    = trim($_POST['full_name'] ?? '');
            $user_name    = trim($_POST['user_name'] ?? '');
            $phone_number = trim($_POST['phone_number'] ?? '');
            $is_active    = isset($_POST['is_active']) ? (int) $_POST['is_active'] : 1;

            if ($user_id === 0 || $full_name === '' || $user_name === '') {
                $_SESSION['error_message'] = 'Data tidak valid.';
            } else {
                $is_updated = $user_model->update_cashier($user_id, $_SESSION['store_id'], $full_name, $user_name, $phone_number, $is_active);
                $_SESSION[$is_updated ? 'success_message' : 'error_message'] = $is_updated
                    ? 'Akun kasir berhasil diperbarui!'
                    : 'Gagal: username sudah dipakai user lain.';
            }
        }

        header('Location: /owner/akun-kasir');
        exit;
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_model = $this->model('UserModel');
            $user_id    = (int) ($_POST['user_id'] ?? 0);

            if ($user_id > 0) {
                $is_deleted = $user_model->delete_cashier($user_id, $_SESSION['store_id']);
                $_SESSION[$is_deleted ? 'success_message' : 'error_message'] = $is_deleted
                    ? 'Akun kasir berhasil dihapus.'
                    : 'Gagal menghapus akun.';
            }
        }

        header('Location: /owner/akun-kasir');
        exit;
    }
}

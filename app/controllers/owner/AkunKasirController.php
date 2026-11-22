
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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /owner/akun-kasir');
            exit;
        }

        $user_model   = $this->model('UserModel');
        $full_name    = trim($_POST['full_name'] ?? '');
        $user_name    = trim($_POST['user_name'] ?? '');
        $email        = trim($_POST['email'] ?? '');
        $phone_number = trim($_POST['phone_number'] ?? '');

        try {
            if ($full_name === '' || $user_name === '') {
                throw new InvalidArgumentException('Nama lengkap dan username wajib diisi.');
            }

            if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new InvalidArgumentException('Format email tidak valid.');
            }

            if ($phone_number !== '') {
                $existing = $user_model->get_store_phone_numbers($_SESSION['store_id']);

                if (in_array($phone_number, $existing, true)) {
                    throw new InvalidArgumentException('Nomor telepon sudah dipakai akun lain di toko ini.');
                }
            }

            $is_saved = $user_model->add_cashier(
                $_SESSION['store_id'],
                $full_name,
                $user_name,
                $email,
                $phone_number
            );

            if (!$is_saved) {
                throw new InvalidArgumentException('Username sudah dipakai, coba username lain.');
            }

            $_SESSION['success_message'] = 'Akun kasir berhasil ditambahkan!';
        } catch (InvalidArgumentException $e) {
            $_SESSION['error_message'] = $e->getMessage();
        } catch (Throwable $e) {
            error_log($e->getMessage());
            $_SESSION['error_message'] = 'Terjadi kesalahan pada server.';
        }

        header('Location: /owner/akun-kasir');
        exit;
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /owner/akun-kasir');
            exit;
        }

        $user_model   = $this->model('UserModel');
        $user_id      = (int) ($_POST['user_id'] ?? 0);
        $full_name    = trim($_POST['full_name'] ?? '');
        $user_name    = trim($_POST['user_name'] ?? '');
        $phone_number = trim($_POST['phone_number'] ?? '');
        $is_active    = (isset($_POST['is_active']) && (int) $_POST['is_active'] === 0) ? 0 : 1;

        try {
            if ($user_id === 0 || $full_name === '' || $user_name === '') {
                throw new InvalidArgumentException('Data tidak valid.');
            }

            if ($phone_number !== '') {
                // Exclude user yang sedang diedit supaya tidak bentrok dengan dirinya sendiri
                $existing = $user_model->get_store_phone_numbers($_SESSION['store_id'], $user_id);

                if (in_array($phone_number, $existing, true)) {
                    throw new InvalidArgumentException('Nomor telepon sudah dipakai akun lain di toko ini.');
                }
            }

            $is_updated = $user_model->update_cashier(
                $user_id,
                $_SESSION['store_id'],
                $full_name,
                $user_name,
                $phone_number,
                $is_active
            );

            if (!$is_updated) {
                throw new InvalidArgumentException('Gagal memperbarui akun. Username mungkin sudah dipakai user lain.');
            }

            $_SESSION['success_message'] = 'Akun kasir berhasil diperbarui!';
        } catch (InvalidArgumentException $e) {
            $_SESSION['error_message'] = $e->getMessage();
        } catch (Throwable $e) {
            error_log($e->getMessage());
            $_SESSION['error_message'] = 'Terjadi kesalahan pada server.';
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

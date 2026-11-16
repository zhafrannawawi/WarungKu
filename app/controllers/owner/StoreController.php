<?php
class OwnerStoreController extends Controller
{
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $store_model  = $this->model('StoreModel');
            $store_name   = trim($_POST['store_name'] ?? '');
            $address      = trim($_POST['address']    ?? '');

            if ($store_name === '') {
                $_SESSION['error_message'] = 'Nama warung wajib diisi.';
            } else {
                $is_updated = $store_model->update_store($_SESSION['store_id'], $store_name, $address);
                $_SESSION[$is_updated ? 'success_message' : 'error_message'] = $is_updated
                    ? 'Profil warung berhasil diperbarui!'
                    : 'Gagal menyimpan profil warung.';
            }
        }

        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/owner/dashboard'));
        exit;
    }
}

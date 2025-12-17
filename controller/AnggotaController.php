<?php
require_once "model/AnggotaModel.php";

class AnggotaController {
    private $model;

    public function __construct($conn) {
        $this->model = new AnggotaModel($conn); // PDO connection
    }

    public function edit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id     = $_POST['id_anggota'] ?? null;
            $nama   = $_POST['nama_lengkap'] ?? '';
            $alamat = $_POST['alamat'] ?? '';
            $hp     = $_POST['hp'] ?? '';
            $masa   = $_POST['masa'] ?? '';

            if (!$id) {
                echo "<script>
                        alert('ID anggota tidak ditemukan!');
                        window.history.back();
                      </script>";
                exit;
            }

            $updated = $this->model->update($id, $nama, $alamat, $hp, $masa);

            if ($updated) {
                echo "<script>
                        alert('Data anggota berhasil diperbarui!');
                        window.location.href='index.php?page=landing';
                      </script>";
            } else {
                echo "<script>
                        alert('Gagal memperbarui data!');
                        window.history.back();
                      </script>";
            }
            exit;
        }

        // GET method → ambil data anggota
        if (!isset($_GET['id'])) die("ID anggota tidak ditemukan!");

        $id = (int)$_GET['id'];
        $data = $this->model->getById($id);

        if (!$data) die("Data anggota tidak ditemukan!");

        include "view/edit_anggota/edit_anggota.php";
    }
}
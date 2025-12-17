<?php
require_once "model/PengembalianModel.php";

class PengembalianController {

    private $model;

    public function __construct(PDO $conn) {
        $this->model = new PengembalianModel($conn);
    }

    // ============================
    // RIWAYAT PENGEMBALIAN USER
    // ============================
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['id_anggota'])) {
            header("Location: indexUser.php?page=login");
            exit;
        }

        $id_anggota = $_SESSION['id_anggota'];

        // 🔥 AMBIL DARI VIEW
        $pengembalian = $this->model->getAllByUser($id_anggota);

        $content = "pengembalian.php";
        include __DIR__ . "/../../view/user/layout.php";
    }

    // ============================
    // PROSES PENGEMBALIAN
    // ============================
    public function kembalikan($id_peminjaman) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['id_anggota'])) {
            header("Location: indexUser.php?page=login");
            exit;
        }

        $this->model->tambah($id_peminjaman);

        header("Location: indexUser.php?page=pengembalian");
        exit;
    }
}
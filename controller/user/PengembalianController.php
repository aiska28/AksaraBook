<?php
require_once "model/user/pengembalian.php";

class PengembalianController {

    private $conn;

    public function __construct(PDO $conn){
        $this->conn = $conn;
    }

    // ============================
    // TAMPILKAN DAFTAR PENGEMBALIAN USER
    // ============================
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Pastikan user login
        if (!isset($_SESSION['id_anggota'])) {
            header("Location: indexUser.php?page=login");
            exit;
        }

        $id_anggota = $_SESSION['id_anggota'];

        // Ambil data pengembalian user dari model
        $pengembalian = Pengembalian::getAllByUser($this->conn, $id_anggota);

        // Kirimkan variabel ke view melalui layout
        $content = "pengembalian.php";
        include __DIR__ . '/../../view/user/layout.php';
    }

    // ============================
    // PROSES PENGEMBALIAN BUKU
    // ============================
    public function kembalikan($id_peminjaman, $id_buku) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['id_anggota'])) {
            header("Location: indexUser.php?page=login");
            exit;
        }

        // Proses pengembalian
        Pengembalian::insertReturn($this->conn, $id_peminjaman);
        Pengembalian::updatePeminjaman($this->conn, $id_peminjaman);
        Pengembalian::tambahStok($this->conn, $id_buku);

        header("Location: indexUser.php?page=pengembalian&msg=success");
        exit;
    }
}
?>

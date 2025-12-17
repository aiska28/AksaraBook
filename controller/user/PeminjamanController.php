<?php
require_once __DIR__ . '/../../model/user/peminjaman.php';

class PeminjamanController {
    private $model;

    public function __construct($conn) {
        $this->model = new peminjaman($conn);
    }

    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['id_anggota'])) {
            header("Location: indexUser.php?page=login");
            exit;
        }

        $id_anggota = $_SESSION['id_anggota'];

        // 1Ambil data peminjaman user (buku yg sedang dipinjam)
        $peminjaman = $this->model->getByUser($id_anggota);

        foreach ($peminjaman as &$p) {
            $hari_ini = date('Y-m-d');
            $p['denda'] = peminjaman::getDenda($this->model->getConn(), $hari_ini, $p['batas_peminjaman']);
        }

        // KIRIM DATA KE VIEW
        $content = "peminjaman.php";
        $data = [
            'peminjaman' => $peminjaman
        ];

        // Kirim ke view
        $content = "peminjaman.php";
        include __DIR__ . '/../../view/user/layout.php';
    }

    public function pinjam() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['id_anggota'])) {
            header("Location: indexUser.php?page=login");
            exit;
        }

        $id_anggota = $_SESSION['id_anggota'];
        $id_buku = $_POST['id_buku'] ?? null;

        if (!$id_buku) {
            echo "ID buku tidak ditemukan!";
            exit;
        }

        // cek stok
        $stok = $this->model->cekStok($id_buku);
        if ($stok <= 0) {
            header("Location: indexUser.php?page=detail&id=" . $id_buku . "&msg=stok_habis");
            exit;
        }

        // cek max pinjam
        $jumlahPinjam = $this->model->cekJumlahPinjaman($id_anggota);
        $maxPinjam = $this->model->cekMaxPinjam($id_anggota);

        if ($jumlahPinjam >= $maxPinjam) {
            header("Location: indexUser.php?page=peminjaman&msg=max_reached");
            exit;
        }

        // proses peminjaman
        try {
            $result = $this->model->pinjamBuku($id_anggota, $id_buku);
            if (!$result) {
                throw new Exception("Gagal meminjam buku");
            }

            header("Location: indexUser.php?page=peminjaman&msg=success");
            exit;

        } catch (Exception $e) {
            $errorMsg = strtolower($e->getMessage());

            if (strpos($errorMsg, 'batas peminjaman') !== false) {
                header("Location: indexUser.php?page=peminjaman&msg=max_reached");
            } elseif (strpos($errorMsg, 'stok buku habis') !== false) {
                header("Location: indexUser.php?page=buku&msg=stok_habis");
            } else {
                header("Location: indexUser.php?page=buku&msg=error");
            }
            exit;
        }
    }
}

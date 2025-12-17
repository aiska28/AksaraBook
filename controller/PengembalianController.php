<?php
require_once "model/PengembalianModel.php";

class PengembalianController {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // ================================
    // RIWAYAT PENGEMBALIAN
    // ================================
    public function riwayatPengembalian() {
        // Panggil model getRiwayat
        $riwayat = PengembalianModel::getRiwayat($this->conn);
        return $riwayat;
    }

    // Tambah pengembalian baru
    public function tambahPengembalian($idPeminjaman) {
        return PengembalianModel::tambah($this->conn, $idPeminjaman);
    }
}
?>
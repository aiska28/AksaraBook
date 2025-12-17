<?php
require_once "model/PeminjamanModel.php";

class PeminjamanController {
    private $conn;

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    // ==========================
    // DAFTAR PEMINJAM AKTIF
    // ==========================
    public function daftarPeminjam() {
        $peminjam = PeminjamanModel::getPeminjam($this->conn);
        return $peminjam;
    }

    // ==========================
    // DAFTAR KETERLAMBATAN + DENDA
    // ==========================
    public function daftarKeterlambatan() {
        $sql = "
            SELECT 
                p.*, 
                a.nama_lengkap, 
                a.no_telp, 
                a.alamat, 
                b.judul,
                (CURRENT_DATE - p.batas_peminjaman) AS terlambat
            FROM peminjaman p
            JOIN anggota a ON p.id_anggota = a.id_anggota
            JOIN buku b ON p.id_buku = b.id_buku
            WHERE p.status_peminjaman = 'dalam peminjaman'
              AND CURRENT_DATE > p.batas_peminjaman
            ORDER BY p.id_peminjaman DESC
        ";
        $stmt = $this->conn->query($sql);
        $telat = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Hitung denda untuk setiap peminjam yang telat
        foreach ($telat as &$t) {
            $tgl_kembali = date('Y-m-d'); // asumsikan hari ini
            $batas = $t['batas_peminjaman'];
            $t['denda'] = PeminjamanModel::getDenda($this->conn, $tgl_kembali, $batas);
        }

        return $telat;
    }

    // ==========================
    // SELESAIKAN PEMINJAMAN
    // ==========================
    public function selesaiPinjam($id) {
        return PeminjamanModel::selesaiPinjam($this->conn, $id);
    }

   // ==========================
    // LAPORAN DENDA PER BUKU (STATISTIK)
    // ==========================
    public function laporanDendaPerBuku() {
        $sql = "SELECT * FROM v_denda_per_buku ORDER BY jumlah_pinjam DESC";

        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
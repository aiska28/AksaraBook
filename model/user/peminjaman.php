<?php
class peminjaman {
    private $conn;

    public function __construct(PDO $conn){
        $this->conn = $conn;
    }

    public function getConn() {
        return $this->conn;
    }
    
    /* ============================
       AMBIL PEMINJAMAN BERDASARKAN USER
    ============================ */
    public function getByUser($id_anggota) {
        $sql = "
            SELECT p.*, b.judul 
            FROM peminjaman p
            JOIN buku b ON p.id_buku = b.id_buku
            WHERE p.id_anggota = :id_anggota 
              AND p.status_peminjaman = 'dalam peminjaman'
            ORDER BY p.tanggal_peminjaman DESC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id_anggota' => $id_anggota]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ============================
       RIWAYAT PEMINJAMAN (SELESAI)
    ============================ */
    public function getRiwayat($id_anggota = null){
        $sql = "
            SELECT p.*, b.judul, a.nama_lengkap
            FROM peminjaman p
            JOIN buku b ON b.id_buku = p.id_buku
            JOIN anggota a ON a.id_anggota = p.id_anggota
            WHERE p.status_peminjaman = 'selesai'
        ";

        if($id_anggota){
            $sql .= " AND p.id_anggota = :id_anggota";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['id_anggota' => $id_anggota]);
        } else {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ============================
       HITUNG BUKU YANG SEDANG DIPINJAM
    ============================ */
    public function cekJumlahPinjaman($id_anggota) {
        $sql = "
            SELECT COUNT(*) 
            FROM peminjaman 
            WHERE id_anggota = :id_anggota 
              AND LOWER(TRIM(status_peminjaman)) = 'dalam peminjaman'
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id_anggota' => $id_anggota]);
        return (int) $stmt->fetchColumn();
    }

    /* ============================
       CEK STOK BUKU
    ============================ */
    public function cekStok($id_buku) {
        $sql = "SELECT get_stok_buku(:id_buku) AS stok";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id_buku' => $id_buku]);
        return (int) $stmt->fetchColumn();
    }

    /* ============================
       AMBIL BATAS MAKSIMAL PEMINJAMAN
    ============================ */
    public function cekMaxPinjam($id_anggota) {
        $sql = "SELECT get_maksimal_peminjaman(:id_anggota) AS max_pinjam";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id_anggota' => $id_anggota]);

        return (int) $stmt->fetchColumn(); 
    }

    /* ============================
       PROSES PINJAM BUKU
    ============================ */
    public function pinjamBuku($id_anggota, $id_buku) {
        $sql = "
            INSERT INTO peminjaman (id_anggota, id_buku, tanggal_peminjaman, status_peminjaman)
            VALUES (:id_anggota, :id_buku, NOW(), 'dalam peminjaman')
        ";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id_anggota' => $id_anggota, 'id_buku' => $id_buku]);
    }

    /* ============================
       DENDA
    ============================ */
    public static function getDenda($conn, $tgl_kembali, $batas) {
        $stmt = $conn->prepare("SELECT hitungDenda(:tgl_kembali, :batas) AS denda");
        $stmt->execute([
            ':tgl_kembali' => $tgl_kembali,
            ':batas' => $batas
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['denda'] ?? 0;
    }

}
?>
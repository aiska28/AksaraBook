<?php
class PengembalianModel {
    private $conn;

    // ============================
    // CONSTRUCTOR (WAJIB)
    // ============================
    public function __construct($conn) {
        $this->conn = $conn; 
    }

    /* ============================
       PEMANGGILAN getAll()
    ============================ */
    public function getAll() {
        $sql = "
            SELECT 
                p.*, 
                a.nama_lengkap,
                b.judul
            FROM pengembalian p
            JOIN peminjaman pm ON p.id_peminjaman = pm.id_peminjaman
            JOIN anggota a ON pm.id_anggota = a.id_anggota
            JOIN buku b ON pm.id_buku = b.id_buku
            ORDER BY p.id_pengembalian DESC
        ";

        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ============================
       AMBIL RIWAYAT
    ============================ */
    public static function getRiwayat($conn) {
        $sql = "SELECT * FROM vw_pengembalian_status";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ============================
       TAMBAH + SP
    ============================ */
    public static function tambah($conn, $idPeminjaman) {
        try {
            $conn->beginTransaction();

            $sql1 = "
                INSERT INTO pengembalian (id_peminjaman, tanggal_pengembalian, keterangan)
                VALUES (:idPeminjaman, CURRENT_DATE, 'Dikembalikan')
            ";
            $stmt1 = $conn->prepare($sql1);
            $stmt1->execute([':idPeminjaman' => $idPeminjaman]);

            $stmt2 = $conn->prepare("CALL kembalikan_buku_anggota(:id)");
            $stmt2->execute([':id' => $idPeminjaman]);

            $conn->commit();
            return true;

        } catch (Exception $e) {
            $conn->rollBack();
            return false;
        }
    }
}

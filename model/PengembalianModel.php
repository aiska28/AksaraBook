<?php
class PengembalianModel {
    private $conn;

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    // ============================
    // AMBIL RIWAYAT PENGEMBALIAN
    // DARI VIEW (TERLAMBAT / TEPAT WAKTU)
    // ============================
   public function getAllByUser($id_anggota) {
    $sql = "
        SELECT
            v.id_pengembalian,
            v.judul_buku AS judul,
            v.tanggal_pengembalian,
            v.keterangan
        FROM vw_pengembalian_status v
        JOIN peminjaman pm ON v.id_pengembalian = pm.id_peminjaman
        WHERE pm.id_anggota = :id_anggota
        ORDER BY v.id_pengembalian DESC
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':id_anggota' => $id_anggota]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    // ============================
    // TAMBAH PENGEMBALIAN
    // ============================
    public function tambah($idPeminjaman) {
        try {
            $this->conn->beginTransaction();

            $sql = "
                INSERT INTO pengembalian (id_peminjaman, tanggal_pengembalian)
                VALUES (:id, CURRENT_DATE)
            ";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $idPeminjaman]);

            // stored procedure
            $stmt2 = $this->conn->prepare("CALL kembalikan_buku_anggota(:id)");
            $stmt2->execute([':id' => $idPeminjaman]);

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
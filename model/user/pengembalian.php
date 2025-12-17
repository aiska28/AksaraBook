<?php
class Pengembalian {

    // ============================
    // AMBIL SEMUA PENGEMBALIAN
    // ============================
    public static function getAll(PDO $conn) {
        $stmt = $conn->query("SELECT * FROM pengembalian ORDER BY id_pengembalian DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ============================
    // AMBIL PENGEMBALIAN USER TERTENTU
    // ============================
    public static function getAllByUser(PDO $conn, $id_anggota) {
        $stmt = $conn->prepare("
            SELECT k.*, p.id_anggota, b.judul
            FROM pengembalian k
            JOIN peminjaman p ON p.id_peminjaman = k.id_peminjaman
            JOIN buku b ON b.id_buku = p.id_buku
            WHERE p.id_anggota = :id_anggota
            ORDER BY k.tanggal_pengembalian DESC
        ");
        $stmt->execute(['id_anggota' => $id_anggota]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ============================
    // PROSES PENGEMBALIAN (INSERT + CALL SP)
    // ============================
    public static function prosesPengembalian(PDO $conn, $id_peminjaman) {
        try {
            $conn->beginTransaction();

            //Insert ke tabel pengembalian
            $stmt = $conn->prepare("
                INSERT INTO pengembalian (id_peminjaman, tanggal_pengembalian)
                VALUES (:id_peminjaman, NOW())
            ");
            $stmt->execute(['id_peminjaman' => $id_peminjaman]);

            //Panggil Stored Procedure untuk update stok + peminjaman + anggota
            $stmt2 = $conn->prepare("CALL kembalikan_buku_anggota(:id)");
            $stmt2->execute(['id' => $id_peminjaman]);

            $conn->commit();
            return true;

        } catch (Exception $e) {
            $conn->rollBack();
            return false;
        }
    }
}
?>

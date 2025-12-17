<?php
class KeterlambatanModel {

    /* ============================
       AMBIL DATA PEMINJAMAN TELAT
    ============================ */
    public static function getTelat($conn) {
        $sql = "
            SELECT p.*, a.nama_lengkap, a.no_telp, a.alamat, b.judul,
                   (CURRENT_DATE - p.batas_peminjaman) AS Terlambat
            FROM peminjaman p
            JOIN anggota a ON p.id_anggota = a.id_anggota
            JOIN buku b ON p.id_buku = b.id_buku
            WHERE p.status_peminjaman = 'Terlambat'
            ORDER BY p.id_peminjaman
        ";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ============================
       SELESAIKAN STATUS KETERLAMBATAN
    ============================ */
    public static function selesaiTelat($conn, $id) {
        $sql = "UPDATE peminjaman SET status_peminjaman = 'Terlambat' WHERE id_peminjaman = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}
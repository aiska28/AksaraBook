<?php
class PeminjamanModel {

    /* ============================
       AMBIL SEMUA PEMINJAM AKTIF
    ============================ */
    public static function getPeminjam($conn) {
        $sql = "
            SELECT 
                p.*, 
                a.nama_lengkap, 
                a.no_telp, 
                a.alamat, 
                b.judul,
                (p.batas_peminjaman - p.tanggal_peminjaman) AS periode
            FROM peminjaman p
            JOIN anggota a ON p.id_anggota = a.id_anggota
            JOIN buku b ON p.id_buku = b.id_buku
            WHERE p.status_peminjaman = 'dalam peminjaman'
            ORDER BY p.id_peminjaman DESC
        ";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ================================
       AMBIL PEMINJAMAN BERDASARKAN ID
    =================================== */
    public static function getById($conn, $id) {
        $sql = "SELECT * FROM peminjaman WHERE id_peminjaman = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* ============================
       HAPUS PEMINJAMAN
    ============================ */
    public static function delete($conn, $id) {
        $sql = "DELETE FROM peminjaman WHERE id_peminjaman = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
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

    /* ============================
       SELESAIKAN PEMINJAMAN
    ============================ */
    public static function selesaiPinjam($conn, $id) {

        $sql = "
            SELECT p.*, b.id_buku
            FROM peminjaman p
            JOIN buku b ON p.id_buku = b.id_buku
            WHERE p.id_peminjaman = :id
        ";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        $p = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$p) return false;

        // cek telat
        $isTelat = date('Y-m-d') > $p['batas_peminjaman'];
        $denda = $isTelat
            ? self::getDenda($conn, date('Y-m-d'), $p['batas_peminjaman'])
            : 0;

        // insert pengembalian
        $sql = "
            INSERT INTO pengembalian 
            (id_peminjaman, tanggal_pengembalian, denda, keterangan)
            VALUES (:id, CURRENT_DATE, :denda, :ket)
        ";
        $conn->prepare($sql)->execute([
            ':id' => $id,
            ':denda' => $denda,
            ':ket' => $isTelat ? ' Terlambat' : 'Tepat Waktu'
        ]);

        // update peminjaman
        $conn->prepare("
            UPDATE peminjaman 
            SET status_peminjaman = 'dikembalikan', denda = :denda
            WHERE id_peminjaman = :id
        ")->execute([
            ':id' => $id,
            ':denda' => $denda
        ]);

        // update stok buku
        $conn->prepare("
            UPDATE buku SET stok = stok + 1 WHERE id_buku = :id_buku
        ")->execute([
            ':id_buku' => $p['id_buku']
        ]);

        return true;
    }
}
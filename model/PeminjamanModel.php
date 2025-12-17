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
        // 1. Ambil data peminjaman
        $sql = "SELECT id_anggota, id_buku FROM peminjaman WHERE id_peminjaman = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) return false;

        $idBuku = $data['id_buku'];

        // 2. Masukkan ke tabel pengembalian
        $sql_insert = "
            INSERT INTO pengembalian (id_peminjaman, tanggal_pengembalian, keterangan)
            VALUES (:id, CURRENT_DATE, 'Dikembalikan tepat waktu')
        ";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->execute([':id' => $id]);

        // 3. Update status peminjaman
        $sql_update = "
            UPDATE peminjaman 
            SET status_peminjaman = 'dikembalikan' 
            WHERE id_peminjaman = :id
        ";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->execute([':id' => $id]);

        // 4. Update stok buku
        $sql_stok = "UPDATE buku SET stok = stok + 1 WHERE id_buku = :idBuku";
        $stmt_stok = $conn->prepare($sql_stok);
        $stmt_stok->execute([':idBuku' => $idBuku]);

        return true;
    }

}
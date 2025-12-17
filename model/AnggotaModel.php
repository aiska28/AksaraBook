<?php
class AnggotaModel {
    private $conn;

    public function __construct(PDO $conn){
        $this->conn = $conn;
    }

    /* Ambil semua anggota */
    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM anggota ORDER BY id_anggota");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* Ambil anggota berdasarkan ID */
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM anggota WHERE id_anggota = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* Update data */
    public function update($id, $nama, $alamat, $hp, $masa) {
        $sql = "
            UPDATE anggota 
            SET nama_lengkap = :nama,
                alamat = :alamat,
                no_telp = :hp,
                berlaku_sampai = :masa
            WHERE id_anggota = :id
        ";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':nama'  => $nama,
            ':alamat'=> $alamat,
            ':hp'    => $hp,
            ':masa'  => $masa,
            ':id'    => $id
        ]);
    }

    public function delete($id){
        $sql = "DELETE FROM anggota WHERE id_anggota = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

}
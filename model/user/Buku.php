<?php
class Buku {

    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
    }

    public function getKategori() {
        $sql = "SELECT * FROM kategori ORDER BY nama_kategori ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBooks($keyword = '', $limit = 12, $start = 0, $kategori = '') {

        $sql = "SELECT * FROM buku WHERE 1=1";
        $params = [];

        // SEARCH
        if (!empty($keyword)) {
            $sql .= " AND (judul ILIKE :keyword OR penulis ILIKE :keyword)";
            $params[':keyword'] = "%$keyword%";
        }

        // FILTER KATEGORI
        if (!empty($kategori)) {
            $sql .= " AND id_kategori = :kategori";
            $params[':kategori'] = (int)$kategori;
        }

        $sql .= " ORDER BY id_buku DESC LIMIT :limit OFFSET :start";

        $stmt = $this->conn->prepare($sql);

        // Bind parameter string
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }

        // Bind integer untuk limit & offset
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':start', (int)$start, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countBooks($keyword = '', $kategori = '') {

        $sql = "SELECT COUNT(*) FROM buku WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (judul ILIKE :keyword OR penulis ILIKE :keyword)";
            $params[':keyword'] = "%$keyword%";
        }

        if (!empty($kategori)) {
            $sql .= " AND id_kategori = :kategori";
            $params[':kategori'] = (int)$kategori;
        }

        $stmt = $this->conn->prepare($sql);

        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }

        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getById($id) {
        $sql = "SELECT * FROM buku WHERE id_buku = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', (int)$id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
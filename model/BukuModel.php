<?php
class BukuModel {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn; // $conn = PDO
    }

    // Ambil semua buku untuk landing_admin
    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM buku ORDER BY id_buku");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //insert buku baru
    public function insert($data) {
        $sql = "INSERT INTO buku 
                (isbn, judul, penulis, id_kategori, id_penerbit, tahun_terbit, stok, status_buku)
                VALUES (:isbn, :judul, :penulis, :id_kategori, :id_penerbit, :tahun, :stok, :status_buku)";
        
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':isbn', $data['isbn']);
        $stmt->bindParam(':judul', $data['judul']);
        $stmt->bindParam(':penulis', $data['penulis']);
        $stmt->bindParam(':id_kategori', $data['id_kategori']);
        $stmt->bindParam(':id_penerbit', $data['id_penerbit']);
        $stmt->bindParam(':tahun', $data['tahun']);
        $stmt->bindParam(':stok', $data['stok']);
        $stmt->bindParam(':status_buku', $data['status_buku']);

         if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;
    }

    //SQL Injection terjadi ketika data input dari user langsung dimasukkan ke query SQL 
    //tanpa filter atau tanpa prepared statement, sehingga penyerang bisa memanipulasi query.
    
    // Ambil detail buku + join kategori dan penerbit
    public function getDetail($id_buku) {
        $sql = "SELECT * FROM vw_detail_buku WHERE id_buku = :id_buku";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id_buku' => $id_buku]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Ambil buku berdasarkan id (digunakan edit)
    public function getById($id) {
        $sql = "SELECT * FROM buku WHERE id_buku = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // single row
    }

    // Update data buku
    public function update($data) {
        $sql = "
            UPDATE buku SET
                isbn = :isbn,
                judul = :judul,
                penulis = :penulis,
                nama_kategori = :kategori,
                nama_penerbit = :penerbit,
                tahun_terbit = :tahun,
                stok = :stok,
                status_buku = :status_buku
            WHERE id_buku = :id_buku
        ";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'isbn'       => $data['isbn'],
            'judul'      => $data['judul'],
            'penulis'    => $data['penulis'],
            'kategori'   => $data['kategori'],
            'penerbit'   => $data['penerbit'],
            'tahun'      => $data['tahun'],
            'stok'       => $data['stok'],
            'status_buku'=> $data['status_buku'],
            'id_buku'    => $data['id_buku']
        ]);
    }

    //kategori
        public function getKategori() {
        $sql = "SELECT * FROM kategori ORDER BY nama_kategori";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function tambahKategori($nama, $deskripsi) {
        $sql = "INSERT INTO kategori (nama_kategori, deskripsi_kategori) VALUES (:nama, :deskripsi)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':nama' => $nama,
            ':deskripsi' => $deskripsi
        ]);
    }

    //penerbit
    public function getPenerbit() {
        $sql = "SELECT * FROM penerbit ORDER BY nama_penerbit";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function tambahPenerbit($nama, $alamat, $telp) {
        $sql = "INSERT INTO penerbit (nama_penerbit, alamat, no_telp)
                VALUES (:nama, :alamat, :telp)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':nama' => $nama,
            ':alamat' => $alamat,
            ':telp' => $telp
        ]);
    }

    public function TangkapPenerbit() {
        $sql = "SELECT * FROM penerbit ORDER BY id_penerbit";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function TangkapKategori() {
        $sql = "SELECT * FROM kategori ORDER BY id_kategori";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
<?php
class User {

    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
    }

    /* ============================
       GET USER BY USERNAME
    ============================ */
    public function getUser($username){
        $query = "SELECT * FROM anggota WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* ============================
       REGISTER USER
    ============================ */
    public function register($data){
        $query = "
            INSERT INTO anggota 
            (nama_lengkap, email, username, password, alamat, no_telp)
            VALUES (:nama_lengkap, :email, :username, :password, :alamat, :no_telp)
        ";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':nama_lengkap' => $data['nama_lengkap'],
            ':email'        => $data['email'],
            ':username'     => $data['username'],
            ':password'     => $data['password'], // pastikan sudah hash
            ':alamat'       => $data['alamat'],
            ':no_telp'      => $data['no_telp']
        ]);
    }
}
?>
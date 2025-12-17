<?php
class Database {
    private $host = "localhost";
    private $port = "5432";
    private $dbname = "perpustakaan_final";
    private $user = "postgres";
    private $password = "nauraPostgreSQL";

    public $conn;

    public function connect() {
        $this->conn = null;

        try {
            // DSN PostgreSQL
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname};";

            // Koneksi PDO
            $this->conn = new PDO($dsn, $this->user, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,   
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
                PDO::ATTR_PERSISTENT => true  
            ]);
        } catch (PDOException $e) {
            die("❌ Koneksi gagal: " . $e->getMessage());
        }

        return $this->conn;
    }
}
?>

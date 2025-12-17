<?php
class DashboardController {

    private $conn;

    public function __construct(PDO $conn){
        $this->conn = $conn;
    }

    public function index(){
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']) || !isset($_SESSION['id_anggota'])) {
            header("Location: ?page=login");
            exit;
        }

        $nama_user  = $_SESSION['user'];
        $id_anggota = $_SESSION['id_anggota'];

        // ========== DATA UNTUK VIEW ==========
        $quote = "Hidup tenang ketika kita fokus pada hal yang bisa kita kendalikan.";
        $quote_author = "Marcus Aurelius";

        // Gunakan layout.php
        $content = "dashboard.php";
        include __DIR__ . '/../../view/user/layout.php';
    }
}
?>
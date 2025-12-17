<?php
require_once "model/AnggotaModel.php";
require_once "model/PeminjamanModel.php";
require_once "model/PengembalianModel.php";
require_once "model/KeterlambatanModel.php";
require_once "model/BukuModel.php";

class AdminController {

    public static function handleRequest($conn) {
        $action = $_GET['action'] ?? null;

        if (!$action) {
            return; 
        }

        switch ($action) {
            case "selesaiPinjam":
            self::selesaiPinjam($conn);
            break;

            case "selesaiTelat":
            self::selesaiTelat($conn);
            break;
        }
    }

    // ======================
    //  SELESAI PEMINJAMAN
    // ======================
    public static function selesaiPinjam($conn) {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header("Location: index.php?page=landing&msg=invalid");
            exit;
        }

        try {
            PeminjamanModel::selesaiPinjam($conn, $id);
            header("Location: index.php?page=landing&msg=success");
        } catch (Exception $e) {
            header("Location: index.php?page=landing&msg=error");
        }
        exit;
    }

    // ======================
    //  SELESAI KETERLAMBATAN
    // ======================
    public static function selesaiTelat($conn) {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header("Location: index.php?page=landing&msg=invalid");
            exit;
        }

        try {
            KeterlambatanModel::selesaiTelat($conn, $id);
            header("Location: index.php?page=landing&msg=success");
        } catch (Exception $e) {
            header("Location: index.php?page=landing&msg=error");
        }
        exit;
    }

    public static function dashboard($conn) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['user'])) {
        header("Location: ?page=login");
        exit;
    }

    // ============= REFRESH MATERIALIZED VIEW =============
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['refresh_mview'])) {
        try {
            $conn->exec("REFRESH MATERIALIZED VIEW mv_laporan_peminjaman_buku");
            echo "<script>alert('Materialized View berhasil di-refresh'); window.location='?page=landing';</script>";
            exit;
        } catch (Exception $e) {
            echo "<script>alert('Gagal refresh: " . $e->getMessage() . "');</script>";
            exit;
        }
    }

    $nama_user = $_SESSION['user'];

    /* ============================ STATISTIK ============================ */
    // Total koleksi buku
    $stmt = $conn->query("SELECT COUNT(*) FROM buku");
    $jumlah_koleksi = (int) $stmt->fetchColumn();

    // Total buku yang sedang dipinjam (semua user)
    $stmt = $conn->query("SELECT COUNT(*) FROM peminjaman");
    $jumlah_dipinjam = (int) $stmt->fetchColumn();

    // Total denda seluruh anggota
    $stmt = $conn->query("SELECT COALESCE(SUM(denda),0) FROM peminjaman");
    $total_denda = (float) $stmt->fetchColumn();

    /* ============================ LINE CHART ============================ */
    $line_labels = [];
    $line_values = [];
    $stmt = $conn->query("
        SELECT EXTRACT(MONTH FROM tanggal_peminjaman) AS bulan, COUNT(*) AS jumlah
        FROM peminjaman
        GROUP BY bulan ORDER BY bulan
    ");
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $line_labels[] = "Bulan ".$row['bulan'];
        $line_values[] = $row['jumlah'];
    }

    /* ============================ BAR CHART ============================ */
    $grafik_labels = [];
    $grafik_values = [];
    $stmt = $conn->query("SELECT judul, total_peminjaman FROM mv_laporan_peminjaman_buku LIMIT 10");
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $grafik_labels[] = $row['judul'];
        $grafik_values[] = $row['total_peminjaman'];
    }

    /* ============================ LAPORAN ============================ */
    $laporan_rows = [];
    $stmt = $conn->query("
        SELECT b.judul, 
            COUNT(p.id_peminjaman) AS jumlah_pinjam,
            COALESCE(SUM(p.denda),0) AS total_denda
        FROM buku b
        LEFT JOIN peminjaman p ON b.id_buku = p.id_buku
        GROUP BY b.judul
        ORDER BY jumlah_pinjam DESC
    ");
    $laporan_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /* ============================ LOAD VIEW ============================ */
    $stat = [
        'jumlah_koleksi' => $jumlah_koleksi,
        'jumlah_dipinjam' => $jumlah_dipinjam,
        'total_denda' => $total_denda,
        'line_labels' => $line_labels,
        'line_values' => $line_values,
        'grafik_labels' => $grafik_labels,
        'grafik_values' => $grafik_values,
        'laporan_rows' => $laporan_rows
    ];

    extract($stat);
    include __DIR__ . '/../view/landing_admin/landing_admin.php';
}

    private static function alert($msg) {
        echo "<script>alert('$msg'); window.location='landing_admin.php';</script>";
        exit;
    }
}
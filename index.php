<?php
require_once "config/koneksi.php"; 
// Inisialisasi koneksi PDO
$db = new Database();
$conn = $db->connect();

/* ==== MODEL ==== */
require_once "model/AnggotaModel.php";
require_once "model/PeminjamanModel.php";
require_once "model/PengembalianModel.php";
require_once "model/KeterlambatanModel.php";
require_once "model/BukuModel.php";

/* ==== CONTROLLER ==== */
require_once "controller/AdminController.php";
require_once "controller/BukuController.php";
require_once "controller/AnggotaController.php";

/* ==== ROUTER ==== */
session_start();
$page = $_GET['page'] ?? 'landing';

switch ($page) {

    case "landing":
        AdminController::handleRequest($conn);

        // Ambil data untuk view
        $anggotaModel  = new AnggotaModel($conn);
        $bukuModel     = new BukuModel($conn);

        // Statistik
        $jumlah_koleksi   = (int) $conn->query("SELECT COUNT(*) FROM buku")->fetchColumn();
        $jumlah_dipinjam  = (int) $conn->query("SELECT COUNT(*) FROM peminjaman")->fetchColumn();
        $total_denda      = (float) $conn->query("SELECT COALESCE(SUM(denda),0) FROM peminjaman")->fetchColumn();
        $nama_user        = $_SESSION['user'] ?? 'Admin';
        $periode          = $_GET['periode'] ?? 'minggu';

        // Data untuk tabel dan charts
        $anggota  = $anggotaModel->getAll();
        $peminjam = PeminjamanModel::getPeminjam($conn);
        $riwayat  = PengembalianModel::getRiwayat($conn);
        $telat    = KeterlambatanModel::getTelat($conn);
        $buku     = $bukuModel->getAll();

        // Line chart
        $line_labels = [];
        $line_values = [];
        $stmt = $conn->query("
            SELECT EXTRACT(MONTH FROM tanggal_peminjaman) AS bulan, COUNT(*) AS jumlah
            FROM peminjaman
            GROUP BY bulan ORDER BY bulan
        ");
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $line_labels[] = "Bulan " . $row['bulan'];
            $line_values[] = $row['jumlah'];
        }

        // Bar chart
        $grafik_labels = [];
        $grafik_values = [];
        $stmt = $conn->query("SELECT judul, total_peminjaman FROM mv_laporan_peminjaman_buku LIMIT 10");
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $grafik_labels[] = $row['judul'];
            $grafik_values[] = $row['total_peminjaman'];
        }

        // Statistik
        $jumlah_koleksi   = (int) $conn->query("SELECT COUNT(*) FROM buku")->fetchColumn();
        $jumlah_dipinjam  = (int) $conn->query("SELECT COUNT(*) FROM peminjaman")->fetchColumn();
        $total_denda = (float) $conn->query("SELECT total_denda_bulan_ini FROM v_total_denda_bulan_ini")->fetchColumn();
        $nama_user        = $_SESSION['user'] ?? 'Admin';
        $periode          = $_GET['periode']??'minggu';

        // Laporan tabel
        $stmt = $conn->query("SELECT * FROM v_denda_per_buku ORDER BY jumlah_pinjam DESC");
        $laporan_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Include view
        include __DIR__ . "/view/landing_admin/landing_admin.php";
        exit;

    // Detail Buku
    case "detailBuku":
        $id = $_GET['id'] ?? null;
        $controller = new BukuController($conn);
        $controller->detail($id);
        exit;

    // Edit Buku
    case "editBuku":
        $id = $_GET['id'] ?? null;
        $controller = new BukuController($conn);
        $controller->edit($id);
        exit;

    // Update Buku
    case "updateBuku":
        if ($_SERVER["REQUEST_METHOD"] !== "POST") die("Invalid request!");
        $controller = new BukuController($conn);
        $controller->update();
        exit;

    // Tambah Buku
    case "tambahBuku":
        $controller = new BukuController($conn);
        $controller->tambah();
        exit;

    // Simpan Buku Baru
    case "simpanBuku":
        if ($_SERVER["REQUEST_METHOD"] !== "POST") die("Invalid request!");
        $controller = new BukuController($conn);
        $controller->simpanBuku();
        exit;

    /* ============================
        KATEGORI
    ============================ */
    case "kategori":
        $model = new BukuModel($conn);
        $data = $model->getKategori();
        include "view/kategori/index.php"; // buat file daftar kategori
        exit;

    // Tambah kategori
    case "tambahKategori":
        $controller = new BukuController($conn);
        $controller->tambahKategori();
        exit;
    
    // Simpan kategori Baru
    case "simpanKategori":
        if ($_SERVER["REQUEST_METHOD"] !== "POST") die("Invalid request!");
        $controller = new BukuController($conn);
        $controller->simpanKategori();
        exit;

    /* ============================
        PENERBIT
    ============================ */
    case "penerbit":
        $model = new BukuModel($conn);
        $data = $model->getPenerbit();
        include "view/tambah_penerbit/tambah_penerbit.php"; 
        exit;

    // Tambah penerbit
    case "tambahPenerbit":
        $controller = new BukuController($conn);
        $controller->tambahPenerbit();
        exit;

    // Simpan penerbit Baru
    case "simpanPenerbit":
        if ($_SERVER["REQUEST_METHOD"] !== "POST") die("Invalid request!");
        $controller = new BukuController($conn);
        $controller->simpanPenerbit();
        exit;

    // Edit Anggota
    case "edit_anggota":
        $controller = new AnggotaController($conn);
        $controller->edit();
        exit;

    // Edit Anggota
    case 'hapus_anggota':
        if(isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $model = new AnggotaModel($conn);
            $model->delete($id);
        }
        header("Location: index.php?page=landing");
        exit;

    // Selesai Peminjaman
    case "selesaiPinjam":
        AdminController::selesaiPinjam($conn);
        exit;

    // Selesai Keterlambatan
    case "selesaiTelat":
        AdminController::selesaiTelat($conn);
        exit;

    default:
        echo "Halaman tidak ditemukan!";
        exit;
}
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once "config/koneksi.php";

$db = new Database();
$conn = $db->connect();

// Login Controller
require_once "controller/user/loginController.php";
$auth = new loginController($conn);

// Routing
$page = $_GET['page'] ?? 'dashboard';

switch($page){

    /* =============================
       LOGIN / REGISTER / LOGOUT
    ============================= */
    case 'login':
        $auth->login();
        break;

    case 'register':
        $auth->register();
        break;

    case 'logout':
        $auth->logout();
        break;

    /* =============================
       DASHBOARD
    ============================= */
    case 'dashboard':
        require_once "controller/user/DashboardController.php";
        $controller = new DashboardController($conn);
        $controller->index();
        break;

    /* =============================
       DAFTAR BUKU
    ============================= */
    case 'buku':
        require_once "controller/user/BukuController.php";
        $controller = new BukuController($conn);
        $controller->index();
        break;

    /* =============================
       DETAIL BUKU
    ============================= */
    case 'detail':
        require_once "controller/user/BukuController.php";
        $controller = new BukuController($conn);
        $controller->detail($_GET['id'] ?? null);
        break;

    /* =============================
       PEMINJAMAN
    ============================= */
    case 'peminjaman':
        require_once "controller/user/PeminjamanController.php";
        $controller = new PeminjamanController($conn);
        $controller->index();
        break;

    /* =============================
       PENGEMBALIAN
    ============================= */
    case 'pengembalian':
        require_once "controller/user/PengembalianController.php";
        $controller = new PengembalianController($conn);
        $controller->index();
        break;

    /* =============================
       PINJAM BUKU
    ============================= */
    case 'pinjam':
        require_once "controller/user/PeminjamanController.php";
        $controller = new PeminjamanController($conn);
        $controller->pinjam();
        break;

    /* =============================
       DEFAULT → redirect ke dashboard
    ============================= */
    default:
        require_once "controller/user/DashboardController.php";
        $controller = new DashboardController($conn);
        $controller->index();
        break;
}
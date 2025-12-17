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
    private static function alert($msg) {
        echo "<script>alert('$msg'); window.location='landing_admin.php';</script>";
        exit;
    }
}
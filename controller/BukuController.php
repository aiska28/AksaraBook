<?php
require_once "model/BukuModel.php";

class BukuController {

    private $model;
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn; 
        $this->model = new BukuModel($conn);
    }

    /* ============================
       DETAIL BUKU
    ============================ */
    public function detail() {
        $id = $_GET['id'] ?? 0;
        $data = $this->model->getDetail($id);

        include "view/buku/detail.php";
    }

    /* ============================
       FORM EDIT BUKU
    ============================ */
    public function edit($id) {
        if (!$id) die("ID buku tidak ditemukan!");

        $buku = $this->model->getById($id);
        if (!$buku) die("Data buku tidak ditemukan!");

        include "view/edit_buku/edit_buku.php"; 
    }

    /* ============================
       PROSES UPDATE BUKU
    ============================ */
    public function update() {
        $data = [
            "id_buku"     => $_POST["id_buku"],
            "isbn"        => $_POST["isbn"],
            "judul"       => $_POST["judul"],
            "penulis"     => $_POST["penulis"],
            "kategori"    => $_POST["kategori"],
            "penerbit"    => $_POST["penerbit"],
            "tahun"       => $_POST["tahun"],
            "stok"        => $_POST["stok"],
            "status_buku" => $_POST["status_buku"],
        ];

        $updated = $this->model->update($data);

        /* ============================
            PROSES UPLOAD FOTO
        ============================ */
        if (!empty($_FILES["foto"]["name"])) {
            if (!is_dir("img/buku")) mkdir("img/buku", 0777, true);

            $ext = strtolower(pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION));
            $tmp = $_FILES["foto"]["tmp_name"];
            $allowed = ["jpg","jpeg","png","webp"];

            if (!in_array($ext, $allowed)) die("Format gambar tidak valid!");

            $fileName = $data["id_buku"] . "." . $ext;
            move_uploaded_file($tmp, "img/buku/" . $fileName);
        }

        header("Location: index.php?page=landing");
        exit;
    }

    /* ============================
       FORM TAMBAH BUKU
    ============================ */
    public function tambah() {
        $kategori = $this->model->TangkapKategori();
        $penerbit = $this->model->TangkapPenerbit();

        include "view/tambah_buku/tambah_buku.php";
    }

    /* ============================
       SIMPAN BUKU BARU
    ============================ */
    public function simpanBuku() {
        $data = [
            "isbn"        => $_POST["isbn"],
            "judul"       => $_POST["judul"],
            "penulis"     => $_POST["penulis"],
            "id_kategori"    => $_POST["id_kategori"],
            "id_penerbit"    => $_POST["id_penerbit"],
            "tahun"       => $_POST["tahun"],
            "stok"        => $_POST["stok"],
            "status_buku" => $_POST["status_buku"],
        ];

        // Simpan ke database & dapatkan ID baru
        $id_baru = $this->model->insert($data);
        if (!$id_baru) die("Gagal menyimpan data buku!");

        /* ============================
           PROSES UPLOAD FOTO
        ============================ */
        if (!empty($_FILES["foto"]["name"])) {
            if (!is_dir("img/buku")) mkdir("img/buku", 0777, true);

            $ext = strtolower(pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION));
            $tmp = $_FILES["foto"]["tmp_name"];
            $allowed = ["jpg","jpeg","png","webp"];

            if (!in_array($ext, $allowed)) die("Format gambar tidak valid!");

            $fileName = $id_baru . "." . $ext;
            move_uploaded_file($tmp, "img/buku/" . $fileName);
        }

        header("Location: index.php?page=landing");
        exit;
    }
    
     /* =====================================================
       =============== TAMBAH KATEGORI ======================
       ===================================================== */
    public function tambahKategori() {
        include "view/tambah_kategori/tambah_kategori.php";
    }

    public function simpanKategori() {
        $nama = $_POST["nama_kategori"];
        $desk = $_POST["deskripsi_kategori"];

        $ok = $this->model->tambahKategori($nama, $desk);

        if ($ok) {
            header("Location: index.php?page=kategori&msg=success");
        } else {
            header("Location: index.php?page=kategori&msg=failed");
        }
        exit;
    }

    /* =====================================================
       =============== TAMBAH PENERBIT ======================
       ===================================================== */

    public function tambahPenerbit() {
        include "view/tambah_penerbit/tambah_penerbit.php";
    }

    public function simpanPenerbit() {
        $nama = $_POST["nama_penerbit"];
        $alamat = $_POST["alamat"];
        $telp = $_POST["no_telp"];

        $ok = $this->model->tambahPenerbit($nama, $alamat, $telp);

        if ($ok) {
            header("Location: index.php?page=penerbit&msg=success");
        } else {
            header("Location: index.php?page=penerbit&msg=failed");
        }
        exit;
    }

}
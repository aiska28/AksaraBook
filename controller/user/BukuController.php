<?php
require_once __DIR__ . '/../../model/user/Buku.php';

class BukuController {

    private $model;

    public function __construct($conn){
        $this->model = new Buku($conn);
    }

    public function index() {

        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $kategoriFilter = isset($_GET['kategori']) ? trim($_GET['kategori']) : '';

        $limit = 12;
        $page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
        if ($page < 1) $page = 1;

        $start = ($page - 1) * $limit;

        // === ambil buku dgn filter ===
        $buku = $this->model->getBooks($keyword, $limit, $start, $kategoriFilter);

        // total data untuk pagination
        $totalData = $this->model->countBooks($keyword, $kategoriFilter);
        $totalPages = ceil($totalData / $limit);

        $kategori = $this->model->getKategori();


        // ===================================
        //   MODE AJAX → KEMBALIKAN LIST SAJA
        // ===================================
        if (isset($_GET['ajax']) && $_GET['ajax'] == "1") {

            ob_start();

            if (!empty($buku)) {
                foreach ($buku as $row) {
                    ?>
                    <div class="book-card">
                        <div class="cover-container">
                            <img src="img/buku/<?= htmlspecialchars($row['id_buku']) ?>.jpg">
                        </div>

                        <div class="book-title"><?= htmlspecialchars($row['judul']) ?></div>
                        <div class="book-author"><?= htmlspecialchars($row['penulis']) ?></div>

                        <a class="btn-detail" 
                           href="indexUser.php?page=detail&id=<?= $row['id_buku'] ?>">
                           Lihat Buku
                        </a>
                    </div>
                    <?php
                }
            } else {
                echo "<p style='text-align:center;'>Tidak ada buku ditemukan.</p>";
            }

            echo ob_get_clean();
            exit; // WAJIB → hentikan layout HTML
        }

        // ===============================
        //   MODE NORMAL → LAYOUT UTUH
        // ===============================
        $content = 'buku.php';
        include __DIR__ . '/../../view/user/layout.php';
    }

    public function detail($id){
        if(!$id){
            echo "ID Buku tidak ditemukan!";
            return;
        }

        $detail = $this->model->getById($id);

        $content = 'detail.php';
        include __DIR__ . '/../../view/user/layout.php';
    }
}
?>
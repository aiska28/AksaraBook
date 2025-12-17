<?php
// ========================================================
// MODE AJAX → return daftar buku saja, tanpa layout
// ========================================================
if (isset($_GET['ajax']) && $_GET['ajax'] == "1") {

    if (!empty($buku)):
        foreach ($buku as $row): ?>
            <div class="book-card">
                <div class="cover-container">
                    <img src="img/buku/<?= htmlspecialchars($row['id_buku']) ?>.jpg" alt="">
                </div>

                <div class="book-title"><?= htmlspecialchars($row['judul']) ?></div>
                <div class="book-author"><?= htmlspecialchars($row['penulis']) ?></div>

                <a class="btn-detail" 
                    href="indexUser.php?page=detail&id=<?= $row['id_buku'] ?>">
                    Lihat Buku
                </a>
                </div>
        <?php endforeach;
    else: ?>
        <p style="text-align:center;">Tidak ada buku ditemukan.</p>
    <?php endif;

    exit; // STOP DI SINI AGAR TIDAK MUAT LAYOUT
}
?>

<!-- ==============================
   NORMAL MODE (LAYOUT LENGKAP)
============================== -->
<div class="book-container">
    <h1>Daftar Buku 📚</h1>

    <!-- SEARCH DAN KATEGORI -->
    <div class="search-box" style="text-align:center; margin: 20px 0;">
        <form method="GET">
            <input type="hidden" name="page" value="buku">

            <select name="kategori" 
                    style="padding:10px 10px; border-radius:10px; border:2px solid #72887b; outline:none; width:150px; cursor:pointer;">
                <option value="">Kategori Buku</option>
                <?php foreach ($kategori as $kat): ?>
                    <option value="<?= $kat['id_kategori'] ?>">
                        <?= htmlspecialchars($kat['nama_kategori']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <input type="text" name="keyword"
                placeholder="Cari buku berdasarkan judul atau penulis..."
                value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>"
                style="padding:10px 15px; border-radius:10px; border:2px solid #72887b; outline:none; width:280px;">

            <button type="submit"
                style="padding:10px 15px; border:none; border-radius:10px; background:#72887b; color:white; cursor:pointer;">
                Cari
            </button>
        </form>
    </div>

    <!-- LIST BUKU -->
    <div class="books" id="booklist">
        <?php if (!empty($buku)): ?>
            <?php foreach ($buku as $row): ?>
                <div class="book-card">
                    <div class="cover-container">
                        <img src="img/buku/<?= htmlspecialchars($row['id_buku']) ?>.jpg" alt="">
                    </div>

                    <div class="book-title"><?= htmlspecialchars($row['judul']) ?></div>
                    <div class="book-author"><?= htmlspecialchars($row['penulis']) ?></div>

                    <a class="btn-detail" 
                       href="indexUser.php?page=detail&id=<?= $row['id_buku'] ?>">
                        Lihat Buku
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Tidak ada buku ditemukan.</p>
        <?php endif; ?>
    </div>


    <!-- PAGINATION -->
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=buku&page_num=<?= $i ?><?= $keyword ? '&keyword='.urlencode($keyword) : '' ?>"
               class="<?= ($i == $page) ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
</div>

<!-- ===========================
   LIVE SEARCH + KATEGORI AJAX
=========================== -->
<script>
let keyword = document.querySelector("input[name='keyword']");
let kategori = document.querySelector("select[name='kategori']");
let bookList = document.getElementById("booklist");
let timer = null;

// ========== LIVE SEARCH ==========
keyword.addEventListener("keyup", function () {
    clearTimeout(timer);

    timer = setTimeout(() => {
        let q = this.value;
        let k = kategori.value;

        fetch(`indexUser.php?page=buku&ajax=1&keyword=${encodeURIComponent(q)}&kategori=${encodeURIComponent(k)}`)
            .then(res => res.text())
            .then(html => bookList.innerHTML = html);
    }, 300);
});

// ========== FILTER KATEGORI ==========
kategori.addEventListener("change", function () {
    let q = keyword.value;
    let k = this.value;

    fetch(`indexUser.php?page=buku&ajax=1&keyword=${encodeURIComponent(q)}&kategori=${encodeURIComponent(k)}`)
        .then(res => res.text())
        .then(html => bookList.innerHTML = html);
});
</script>

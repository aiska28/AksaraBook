<?php if (!isset($detail)) $detail = null; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Buku</title>

    <link rel="stylesheet" href="assets/detail-modern.css">
</head>

<body>

<!-- PESAN NOTIFIKASI -->
<?php if (isset($_GET['msg']) && $_GET['msg'] === 'stok_habis'): ?>
    <div class="alert alert-error" style="
        background:#ff4d4d;
        color:white;
        padding:12px;
        margin:10px auto;
        width:90%;
        border-radius:8px;
        text-align:center;
        font-weight:bold;">
        ❗ Stok buku habis, tidak dapat dipinjam.
    </div>
<?php endif; ?>

<?php if (!empty($detail)): ?>

<div class="detail-hero">

    <div class="detail-left">
        <h1 class="detail-title"><?= htmlspecialchars($detail['judul']); ?></h1>

        <table class="detail-table">
            <tr><td>ID Buku</td><td><?= htmlspecialchars($detail['id_buku']); ?></td></tr>
            <tr><td>ISBN</td><td><?= htmlspecialchars($detail['isbn']); ?></td></tr>
            <tr><td>Penulis</td><td><?= htmlspecialchars($detail['penulis']); ?></td></tr>
            <tr><td>Kategori</td><td><?= htmlspecialchars($detail['id_kategori']); ?></td></tr>
            <tr><td>Penerbit</td><td><?= htmlspecialchars($detail['id_penerbit']); ?></td></tr>
            <tr><td>Tahun Terbit</td><td><?= htmlspecialchars($detail['tahun_terbit']); ?></td></tr>
            <tr><td>Stok</td><td><?= htmlspecialchars($detail['stok']); ?></td></tr>
            <tr><td>Status</td><td><?= htmlspecialchars($detail['status_buku']); ?></td></tr>
        </table>

        <div class="btn-row">

            <!-- FORM UNTUK MEMINJAM -->
            <form method="POST" action="indexUser.php?page=pinjam">
                <input type="hidden" name="id_buku" value="<?= $detail['id_buku'] ?>">
                <button type="submit" class="btn-pinjam">Pinjam Buku</button>
            </form>

            <!-- TOMBOL KEMBALI -->
            <a href="indexUser.php?page=buku" class="btn-kembali">Kembali</a>

        </div>

    </div>

    <div class="detail-right">
        <div class="book-cover">
            <img src="img/buku/<?= htmlspecialchars($detail['id_buku']); ?>.jpg" alt="">
        </div>
    </div>

</div>

<?php else: ?>
    <p style="text-align:center;">Buku tidak ditemukan.</p>
<?php endif; ?>

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Buku | <?= $data['judul']; ?></title>
    <link rel="stylesheet" href="view/buku/detail.css">
</head>

<body>

<div class="container">

    <div class="card">

        <h2 class="judul"><?= $data['judul']; ?></h2>

        <div class="content">

            <!-- FOTO BUKU -->
            <div class="cover-box">

            <?php
            $foto = "img/buku/" . $data['id_buku'] . ".jpg";

            if (!file_exists($foto)) {
                $foto = "img/buku/default.jpg";
            }
            ?>

            <img src="<?= $foto ?>" alt="Cover Buku">
            </div>

            <!-- DETAIL BUKU -->
            <div class="detail-box">

                <p><strong>ID Buku:</strong> <?= $data['id_buku']; ?></p>
                <p><strong>ISBN:</strong> <?= $data['isbn']; ?></p>
                <p><strong>Judul:</strong> <?= $data['judul']; ?></p>
                <p><strong>Penulis:</strong> <?= $data['penulis']; ?></p>

                <p><strong>Kategori:</strong> <?= $data['nama_kategori']; ?></p>
                <p><strong>Penerbit:</strong> <?= $data['nama_penerbit']; ?></p>
                <p><strong>Tahun Terbit:</strong> <?= $data['tahun_terbit']; ?></p>

                <p><strong>Stok:</strong> <?= $data['stok']; ?></p>

                <p>
                    <strong>Status Buku:</strong>  
                    <span class="status <?= strtolower($data['status_buku']); ?>">
                        <?= $data['status_buku']; ?>
                    </span>
                </p>

                <div class="btn-group">
                    <a href="index.php?page=landing" class="btn back">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

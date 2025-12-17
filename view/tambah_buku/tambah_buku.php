<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Buku</title>
    <link rel="stylesheet" href="view/tambah_buku/tambah_buku.css">
</head>

<body>

<a href="index.php?page=landing" class="btn-back">Kembali</a>

<div class="content" style="width:600px;margin:40px auto;">
    <h2>Tambah Buku</h2>

    <form action="index.php?page=simpanBuku" method="POST" enctype="multipart/form-data">

        <label>ISBN</label>
        <input type="text" name="isbn" required>

        <label>Judul Buku</label>
        <input type="text" name="judul" required>

        <label>Penulis</label>
        <input type="text" name="penulis" required>

        <!-- KATEGORI DROPDOWN -->
        <label>Kategori</label>
        <select name="id_kategori" required>
            <option value="">-- Pilih Kategori --</option>
            <?php foreach($kategori as $k): ?>
                <option value="<?= $k['id_kategori']; ?>">
                    <?= $k['nama_kategori']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- PENERBIT DROPDOWN -->
        <label>Penerbit</label>
        <select name="id_penerbit" required>
            <option value="">-- Pilih Penerbit --</option>
            <?php foreach($penerbit as $p): ?>
                <option value="<?= $p['id_penerbit']; ?>">
                    <?= $p['nama_penerbit']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Tahun Terbit</label>
        <input type="number" name="tahun" required>

        <label>Stok Buku</label>
        <input type="number" name="stok" required>

        <label>Upload Foto Buku</label>
        <input type="file" name="foto" required>

        <label>Status Buku</label>
        <select name="status_buku">
            <option value="Tersedia">Tersedia</option>
            <option value="Dipinjam">Dipinjam</option>
        </select>

        <br><br>
        <button type="submit" class="btn add" style="width:100%">Tambah Buku</button>
    </form>
</div>

</body>
</html>

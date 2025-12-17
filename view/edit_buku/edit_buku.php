<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Buku</title>
    <link rel="stylesheet" href="view/edit_buku/edit_buku.css">
</head>

<body>

<a href="index.php?page=landing" class="btn-back">Kembali</a>

<div class="content" style="width:600px;margin:40px auto;">
    <h2>Edit Buku</h2>

    <form action="index.php?page=updateBuku" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="id_buku" value="<?= $buku['id_buku']; ?>">

        <label>ISBN</label>
        <input type="text" name="isbn" value="<?= $buku['isbn']; ?>" required>

        <label>Judul</label>
        <input type="text" name="judul" value="<?= $buku['judul']; ?>" required>

        <label>Penulis</label>
        <input type="text" name="penulis" value="<?= $buku['penulis']; ?>" required>

        <label>Kategori</label>
        <input type="text" name="kategori" value="<?= $buku['id_kategori']; ?>" required>

        <label>Penerbit</label>
        <input type="text" name="penerbit" value="<?= $buku['id_penerbit']; ?>" required>

        <label>Tahun Terbit</label>
        <input type="number" name="tahun" value="<?= $buku['tahun_terbit']; ?>" required>

        <label>Stok</label>
        <input type="number" name="stok" value="<?= $buku['stok']; ?>" required>

        <label>Upload Foto Baru</label>
        <input type="file" name="foto">

        <label>Status Buku</label>
        <select name="status_buku">
            <option value="Tersedia" <?= $buku['status_buku']=='Tersedia'?'selected':'' ?>>Tersedia</option>
            <option value="Dipinjam" <?= $buku['status_buku']=='Dipinjam'?'selected':'' ?>>Dipinjam</option>
        </select>

        <br><br>
        <button type="submit" class="btn edit" style="width:100%">Simpan Perubahan</button>
    </form>
</div>

</body>
</html>
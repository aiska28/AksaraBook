<!DOCTYPE html>
<html>
<head>
    <title>Edit Anggota</title>
    <link rel="stylesheet" href="view/edit_anggota/edit_anggota.css">
</head>

<body>
<div class="form-edit">
<h2>Edit Anggota</h2>

<form method="POST" action="">
    <input type="hidden" name="id_anggota" value="<?= htmlspecialchars($data['id_anggota']) ?>">

    <label>Nama</label>
    <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($data['nama_lengkap']) ?>">

    <label>Alamat</label>
    <input type="text" name="alamat" value="<?= htmlspecialchars($data['alamat']) ?>">

    <label>Nomor HP</label>
    <input type="text" name="hp" value="<?= htmlspecialchars($data['no_telp']) ?>">

    <label>Masa Anggota</label>
    <input type="date" name="masa" value="<?= htmlspecialchars($data['berlaku_sampai']) ?>">

    <button type="submit" class="btn-simpan">Simpan Perubahan</button>
    <button type="button" class="btn-kembali"onclick="window.location.href='index.php?page=landing'">Kembali</button>
</form>
</div>
</body>
</html>
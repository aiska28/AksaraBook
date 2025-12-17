<?php
require_once "controller/PeminjamanController.php";
require_once "controller/PengembalianController.php";
require_once "model/AnggotaModel.php";
require_once "model/BukuModel.php";
require_once "model/PengembalianModel.php";

// Buat instance controller
$peminjamanCtrl = new PeminjamanController($conn);
$pengembalianCtrl = new PengembalianController($conn);
$bukuModel = new BukuModel($conn);
$model = new AnggotaModel($conn);
$pengembalianModel = new PengembalianModel($conn);

// Ambil data dari model lewat controller
$anggota = $model->getAll();
$peminjam = $peminjamanCtrl->daftarPeminjam();
$riwayat = $pengembalianModel->getAll();
$telat = $peminjamanCtrl->daftarKeterlambatan();
$riwayat = $pengembalianCtrl->riwayatPengembalian();
$buku = $bukuModel->getAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Perpustakaan - Admin</title>
  <link rel="stylesheet" href="view/landing_admin/landing_admin.css">
</head>
<body>

<!-- HEADER -->
<header class="header">
  <div class="header-left">
    <h2>AksaraBox Admin</h2>
  </div>
  <a href="indexUser.php?page=dashboard" class="btn btn-primary btn-home">🏠 Home</a>
</header>

<div class="container">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <button class="tab-button active" data-target="anggota">👨‍💼 Daftar Anggota</button>
    <button class="tab-button" data-target="peminjam">📚 Daftar Peminjam</button>
    <button class="tab-button" data-target="riwayat">📄 Riwayat Pengembalian</button>
    <button class="tab-button" data-target="keterlambatan">⏰ Daftar Keterlambatan</button>
    <button class="tab-button" data-target="buku">📖 Daftar Buku</button>
    <button class="tab-button" data-target="laporan">📈 Grafik & Laporan Buku</button>
  </aside>

  <!-- TAB CONTENT -->
  <main class="content">

    <!-- ANGGOTA -->
    <section class="tab-content active" id="anggota">
        <h3>Daftar Anggota</h3>
        <table>
            <thead>
                <tr>
                  <th>No</th>
                  <th>Nama</th>
                  <th>Alamat</th>
                  <th>Nomor HP</th>
                  <th>Masa Anggota</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $no = 1;
            foreach($anggota as $a) {
                $today = date('Y-m-d');
                $mulai = $a['tanggal_join'];
                $akhir = $a['berlaku_sampai'];

                $status = ($today >= $mulai && $today <= $akhir) 
                          ? "<span style='color:black; font-weight:bold;'>Aktif</span>" 
                          : "<span style='color:red; font-weight:bold;'>Tidak Aktif</span>";
            ?>
                <tr>
                  <td><?= $no++; ?></td>
                  <td><?= $a['nama_lengkap']; ?></td>
                  <td><?= $a['alamat']; ?></td>
                  <td><?= $a['no_telp']; ?></td>
                  <td><?= $mulai . " - " . $akhir; ?></td>
                  <td><?= $status; ?></td>
                  <td class="aksi">
                    <a href="index.php?page=edit_anggota&id=<?= $a['id_anggota']; ?>" class="btn edit">Edit</a>
                    <a href="index.php?page=hapus_anggota&id=<?= $a['id_anggota']; ?>" 
                      class="btn delete"
                      onclick="return confirm('Yakin hapus anggota?');">
                      Hapus
                    </a>
                  </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </section>

    <!-- PEMINJAM -->
    <section class="tab-content" id="peminjam">
        <h3>Daftar Peminjam</h3>
        <table>
            <thead>
                <tr>
                    <th>Nama Peminjam</th>
                    <th>Nomor HP</th>
                    <th>Alamat</th>
                    <th>Nama Buku</th>
                    <th>Periode</th>
                    <th>Tgl Pengembalian</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach($peminjam as $p) { ?>
                <tr>
                    <td><?= $p['nama_lengkap']; ?></td>
                    <td><?= $p['no_telp']; ?></td>
                    <td><?= $p['alamat']; ?></td>
                    <td><?= $p['judul']; ?></td>
                    <td><?= $p['periode']; ?> Hari</td>
                    <td><?= $p['batas_peminjaman']; ?></td>
                    <td>
                      <a href="index.php?page=landing&action=selesaiPinjam&id=<?= $p['id_peminjaman']; ?>" class="btn finish">Selesai</a>
                    </td>
                </tr>
              <?php } ?>
            </tbody>
        </table>
    </section>

    <!-- RIWAYAT PENGEMBALIAN -->
    <section class="tab-content" id="riwayat">
        <h3>Riwayat Pengembalian</h3>
        <table>
            <thead>
                <tr>
                    <th>Nama Anggota</th>
                    <th>Judul Buku</th>
                    <th>Tanggal Pengembalian</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($riwayat as $r) { ?>
                <tr>
                    <td><?= $r['nama_lengkap']; ?></td>
                    <td><?= $r['judul_buku']; ?></td>
                    <td><?= $r['tanggal_pengembalian']; ?></td>
                    <td><?= $r['keterangan']; ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </section>

    <!-- KETERLAMBATAN -->
    <section class="tab-content" id="keterlambatan">
        <h3>Daftar Keterlambatan</h3>
        <table>
            <thead>
                <tr>
                    <th>Nama Peminjam</th>
                    <th>Nomor HP</th>
                    <th>Alamat</th>
                    <th>Nama Buku</th>
                    <th>Keterlambatan</th>
                    <th>Denda</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($telat as $t) { ?>
                <tr>
                    <td><?= $t['nama_lengkap']; ?></td>
                    <td><?= $t['no_telp']; ?></td>
                    <td><?= $t['alamat']; ?></td>
                    <td><?= $t['judul']; ?></td>

                    <!-- TERLAMBAT -->
                    <td><?= $t['terlambat']; ?> Hari</td>

                    <!-- DENDA DARI CONTROLLER -->
                    <td>Rp <?= number_format($t['denda'],0,',','.'); ?></td>

                    <td>
                        <a href="index.php?action=selesaiTelat&id=<?= $t['id_peminjaman']; ?>" class="btn finish">Selesai</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </section>

    <!-- BUKU -->
    <section class="tab-content" id="buku">
      <div class="d-flex-between">
        <h3>Daftar Buku</h3>
        <a href="index.php?page=tambahBuku" class="btn add">+ Tambah Buku</a>
        <a href="index.php?page=tambahKategori" class="btn add">+ Tambah Kategori</a>
        <a href="index.php?page=tambahPenerbit" class="btn add">+ Tambah Penerbit</a>
      </div>
      <div class="book-grid">
        <?php foreach($buku as $b) { ?>
          <div class="book-card">
            <img src="img/buku/<?= $b['id_buku']; ?>.jpg" alt="">
            <h4><?= $b['judul']; ?></h4>
            <a href="index.php?page=detailBuku&id=<?= $b['id_buku'] ?>" class="btn detail">Lihat Buku</a>
            <a href="index.php?page=editBuku&id=<?= $b['id_buku']; ?>" class="btn edit">Edit</a>
          </div>
        <?php } ?>
      </div>
    </section>

    <!-- LAPORAN -->
    <section class="tab-content" id="laporan">
        <h1>Selamat Datang di AksaraBook 📚</h1>

        <form method="post" style="margin-bottom: 15px;">
            <button type="submit" name="refresh_mview" class="btn btn-primary">🔄 Refresh Laporan</button>
        </form>

        <!-- STATISTIK -->
        <div class="stat-area">
            <div class="stat-card">
                <div class="stat-icon bg-yellow"></div>
                <div class="stat-info">
                    <div class="stat-label">Koleksi Judul Buku</div>
                    <div class="stat-num"><?= $jumlah_koleksi ?></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-pink"></div>
                <div class="stat-info">
                    <div class="stat-label">Total Riwayat Buku Dipinjam</div>
                    <div class="stat-num"><?= $jumlah_dipinjam ?></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-green"></div>
                <div class="stat-info">
                    <div class="stat-label">Total Denda Bulan Ini</div>
                    <div class="stat-num">Rp <?= number_format($total_denda, 0, ",", ".") ?></div>
                </div>
            </div>
        </div>

        <!-- LINE CHART -->
        <div class="chart-box">
            <h3>Rekapitulasi Peminjaman</h3>
            <form method="get">
                <label>
                    Periode:
                    <select name="periode" onchange="this.form.submit()">
                        <option value="minggu" <?= $periode=='minggu'?'selected':'' ?>>Minggu</option>
                        <option value="bulan" <?= $periode=='bulan'?'selected':'' ?>>Bulan</option>
                        <option value="tahun" <?= $periode=='tahun'?'selected':'' ?>>Tahun</option>
                    </select>
                </label>
            </form>
            <canvas id="lineChart" height="100"></canvas>
        </div>

        <!-- BAR CHART -->
        <div class="chart-box">
            <h3>Buku Paling Banyak Dipinjam</h3>
            <canvas id="barChart" height="100"></canvas>
        </div>

        <!-- LAPORAN TABEL -->
        <div class="laporan-box">
        <h3>Laporan Peminjaman <?= htmlspecialchars($nama_user) ?></h3>
        <table>
            <thead>
                <tr>
                    <th>Nama Buku</th>
                    <th>Jumlah Pinjam</th>
                    <th>Denda</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($laporan_rows as $row): ?>
                    <tr>
                        <td><?= $row['judul'] ?></td>
                        <td><?= $row['jumlah_pinjam'] ?></td>
                        <td>Rp <?= number_format($row['total_denda_buku'], 0, ",", ".") ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>

        <!-- SCRIPT CHART.JS -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const lineCtx = document.getElementById('lineChart').getContext('2d');
            const barCtx = document.getElementById('barChart').getContext('2d');

            // Line Chart
            new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: <?= json_encode($line_labels) ?>,
                    datasets: [{
                        label: 'Jumlah Peminjaman',
                        data: <?= json_encode($line_values) ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true } }
                }
            });

            // Bar Chart
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($grafik_labels) ?>,
                    datasets: [{
                        label: 'Total Pinjaman',
                        data: <?= json_encode($grafik_values) ?>,
                        backgroundColor: '#9285b9',
                        borderColor: '#9285b9',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true } }
                }
            });
        </script>
    </section>

  </main>
</div>

<script>
const tabs = document.querySelectorAll('.tab-button');
const contents = document.querySelectorAll('.tab-content');

tabs.forEach(btn => {
  btn.addEventListener('click', () => {
    tabs.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    contents.forEach(c => c.classList.remove('active'));
    document.getElementById(btn.dataset.target).classList.add('active');
  });
});
</script>

</body>
</html>
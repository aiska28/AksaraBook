<section class="tab-content" id="pengembalian">
    <h1>Riwayat Pengembalian 📁</h1>

    <div class="table-wrapper">
        <table>
            <tr>
                <th>Judul Buku</th>
                <th>Tanggal Pengembalian</th>
                <th>Keterangan</th>
            </tr>

            <?php foreach ($pengembalian as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['judul']) ?></td>
                <td><?= htmlspecialchars($row['tanggal_pengembalian']) ?></td>
                <td><?= htmlspecialchars($row['keterangan']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</section>
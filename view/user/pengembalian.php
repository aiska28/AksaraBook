<section class="tab-content" id="pengembalian">
    <h1>Riwayat Pengembalian 📁</h1>

    <div class="table-wrapper">
        <table>
            <tr>
                <th>ID</th>
                <th>Judul Buku</th>
                <th>Tanggal Pengembalian</th>
                <th>Keterangan</th>
            </tr>

            <?php if (empty($pengembalian)): ?>
                <tr>
                    <td colspan="4" style="text-align:center;">Belum ada pengembalian</td>
                </tr>
            <?php else: ?>
                <?php foreach ($pengembalian as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_pengembalian']) ?></td>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td><?= htmlspecialchars($row['tanggal_pengembalian']) ?></td>
                    <td>
                        <?php if ($row['keterangan'] === 'Terlambat'): ?>
                            <span style="color:red;font-weight:bold;">
                                <?= htmlspecialchars($row['keterangan']) ?>
                            </span>
                        <?php else: ?>
                            <?= htmlspecialchars($row['keterangan']) ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
</section>
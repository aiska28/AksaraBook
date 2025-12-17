<?php
$nama_user = $_SESSION['user'] ?? '';
?>

<!-- ================= POPUP STYLE ================= -->
<style>
    .popup-overlay {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .popup-box {
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        width: 350px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        animation: fadeIn 0.3s ease;
    }

    .popup-btn {
        margin-top: 15px;
        padding: 10px 20px;
        background: #ff4d4d;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.9); }
        to   { opacity: 1; transform: scale(1); }
    }
</style>

<section class="tab-content" id="peminjaman">

    <h1>Peminjaman Saya 📖</h1>

    <!-- ================= POPUP ================= -->
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'max_reached'): ?>
        <div id="popupMax" class="popup-overlay">
            <div class="popup-box">
                <h3>❗ Batas Peminjaman Tercapai</h3>
                <p>
                    Anda sudah mencapai batas maksimal peminjaman buku.<br>
                    Kembalikan buku terlebih dahulu sebelum meminjam lagi.
                </p>

                <button class="popup-btn" onclick="closePopup()">OK</button>
            </div>
        </div>

        <script>
            function closePopup() {
                document.getElementById('popupMax').style.display = 'none';
            }
        </script>
    <?php endif; ?>
    <!-- ================= END POPUP ================= -->

    <div class="table-wrapper">
        <table>
            <tr>
                <th>Judul Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Batas Kembali</th>
                <th>Denda</th>
                <th>Status</th>
            </tr>

            <?php 
            $today = date('Y-m-d');
            foreach ($peminjaman as $row):
                $isTerlambat = (
                    $row['status_peminjaman'] === 'dalam peminjaman' &&
                    $row['batas_peminjaman'] < $today
                );
            ?>
                <tr class="<?= $isTerlambat ? 'terlambat' : '' ?>">
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td><?= $row['tanggal_peminjaman'] ?></td>
                    <td><?= $row['batas_peminjaman'] ?></td>
                     <!-- DENDA ADA DI SINI -->
                    <td><?= number_format($row['denda'] ?? 0, 0, ',', '.') ?></td>
                    <td><?= $row['status_peminjaman'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

</section>
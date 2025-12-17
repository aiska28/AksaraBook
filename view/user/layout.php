<?php
$nama_user = $_SESSION['user'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>AksaraBook</title>
    <link rel="stylesheet" href="assets/dashboard.css">
    <link rel="stylesheet" href="assets/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="page-dashboard">

<!-- NAVBAR -->
<div class="navbar">
    <div class="brand"><b>AksaraBook</b></div>
    <div class="user-menu">
        <span class="user-name"><?= htmlspecialchars($nama_user) ?></span>
            <a href="indexUser.php?page=logout">Log Out</a>
    </div>
</div>

<div class="container">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <button onclick="window.location.href='indexUser.php?page=dashboard'">🏠 Dashboard</button>
        <button onclick="window.location.href='indexUser.php?page=buku'">📚 Daftar Buku</button>
        <button onclick="window.location.href='indexUser.php?page=peminjaman'">📖 Peminjaman Saya</button>
        <button onclick="window.location.href='indexUser.php?page=pengembalian'">📁 Riwayat Pengembalian</button>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="content">
        <?php 
        if(isset($content) && file_exists(__DIR__ . '/' . $content)){
            include __DIR__ . '/' . $content;
        } else {
            echo "File '$content' tidak ditemukan!";
        }
        ?>
    </main>
</div>

</body>
</html>
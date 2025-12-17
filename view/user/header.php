<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>AksaraBook</title>
<link rel="stylesheet" href="public/css/daftarBuku.css">
<link rel="stylesheet" href="../assets/dashboard.css">
</head>
<body>
<?php
$nama_user = isset($_SESSION['user']) ? $_SESSION['user'] : 'Guest';
?>


<div class="navbar">
<div>AksaraBook</div>
<div class="user-menu">
<span class="user-name"><?= htmlentities($nama_user) ?></span>
<div class="dropdown"><a href="logout.php">Log Out</a></div>
</div>
<a href="indexUser.php?route=dashboard" class="btn-back-nav">⬅ Kembali</a>
</div>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Kategori</title>

    <style>
        /* ======== GLOBAL ======== */
        body { 
            font-family: Arial, sans-serif; 
            background: #f3e9da; 
            padding: 40px;
            margin: 0;
        }

        /* ======== CARD CONTAINER ======== */
        .box { 
            width: 600px; 
            margin: auto; 
            padding: 25px; 
            border-radius: 12px;
            background: #fff8ef;
            border: 2px solid #b17a50;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        /* ======== TITLE ======== */
        h2 {
            text-align: center;
            color: #5c3b1e;
            margin-bottom: 20px;
        }

        /* ======== LABEL ======== */
        label {
            display: block;
            font-weight: bold;
            color: #4a2d13;
            margin-top: 10px;
        }

        /* ======== INPUT & TEXTAREA ======== */
        input,
        textarea { 
            width: 100%; 
            padding: 12px; 
            margin-top: 6px;
            margin-bottom: 16px;
            border: 1px solid #b17a50;
            border-radius: 8px;
            background: #fff4e4;
            font-size: 14px;
            box-sizing: border-box;
        }

        textarea {
            height: 80px;
            resize: none;
        }

        /* ======== BUTTON SIMPAN ======== */
        button { 
            width: 100%;
            padding: 12px;
            background: #8b5a2b; 
            color: white; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: 0.2s;
        }

        button:hover {
            background: #6f421f;
        }

        /* ======== BUTTON KEMBALI ======== */
        .btn-back {
            display: block;
            width: 100%;
            box-sizing: border-box; /* 🔥 WAJIB agar width 100% sama kayak button */
            text-align: center;
            padding: 12px;
            margin-top: 15px;
            background: #d7b899;
            color: #3e2a19;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;        /* 🔥 samakan dengan button */
            cursor: pointer;        /* 🔥 biar rasanya tombol */
            transition: 0.2s;
        }


        .btn-back:hover {
            background: #c49c74;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Tambah Kategori Buku</h2>

    <form action="index.php?page=simpanKategori" method="POST">

        <label>Nama Kategori</label>
        <input type="text" name="nama_kategori" required>

        <label>Deskripsi</label>
        <textarea name="deskripsi_kategori"></textarea>

        <button class="btn-submit" type="submit">Simpan</button>
    </form>

    <a href="index.php?page=landing" class="btn-back">← Kembali</a>
</div>

</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <title><?= ($mode == "register") ? "Create Account" : "Login" ?></title>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/login.css">
</head>
<body>

<div class="navbar">AKSARABOOK</div>

<div class="container-auth">

    <!-- LEFT CARD -->
    <div class="box-left">
        <img src="https://cdn-icons-png.flaticon.com/512/2232/2232688.png">
        <h2>AKSARABOOK</h2>
    </div>

    <!-- RIGHT CARD -->
    <div class="box-right">

        <h3><?= ($mode == "register") ? "Create Account" : "Login" ?></h3>

        <?php if (!empty($error)) : ?>
            <div class="alert"><?= $error ?></div>
        <?php endif; ?>

        <form method="post">

            <?php if ($mode == "register") : ?>

                <div class="input-box">
                    <input type="text" name="nama" placeholder="Nama Lengkap" required>
                </div>

                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required>
                </div>

                <div class="input-box">
                    <input type="text" name="username" placeholder="Username" required>
                </div>

                <div class="input-box">
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <div class="input-box">
                    <input type="text" name="alamat" placeholder="Alamat" required>
                </div>

                <div class="input-box">
                    <input type="text" name="telepon" placeholder="No. Telepon" required>
                </div>

                <button class="btn" name="register">Create Account</button>

                <div class="link">
                    <a href="indexUser.php?page=login">Already have an account? Login</a>
                </div>

            <?php else : ?>

                <div class="input-box">
                    <input type="text" name="username" placeholder="Username" required>
                </div>

                <div class="input-box">
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <button class="btn" name="login">Login</button>

                <div class="link">
                    <a href="indexUser.php?page=register">Don't have an account? Create Account</a>
                </div>

            <?php endif; ?>

        </form>

    </div>

</div>

</body>
</html>
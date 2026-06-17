<?php
require_once 'controllers/AuthController.php';
$auth = new AuthController();
$error = $auth->register();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Daftar - E-Bike Campus</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-box">
            <div class="logo-area">
                <img src="assets/img/logo.png" alt="Logo">
                <h2>Daftar Akun</h2>
                <p>Buat akun untuk mulai menyewa</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap" required>
                </div>
                <div class="form-group">
                    <label>NIM</label>
                    <input type="text" name="nim" class="form-control" placeholder="Masukkan NIM" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="email@kampus.ac.id" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Buat password" required>
                </div>
                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="Ulangi password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block mt-2">Daftar</button>
            </form>

            <p class="text-center mt-2 text-small" style="color:#6b7280;">
                Sudah punya akun? <a href="index.php?page=home">Login di sini</a>
            </p>
        </div>
    </div>
</body>
</html>

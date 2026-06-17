<?php
require_once 'controllers/AuthController.php';
$auth = new AuthController();
$error = $auth->login();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Login - E-Bike Campus</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-box">
            <div class="logo-area">
                <img src="assets/img/logo.png" alt="Logo">
                <h2>E-Bike Campus</h2>
                <p>Penyewaan Sepeda Listrik Kampus</p>
            </div>

            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'registered'): ?>
                <div class="alert alert-success">Registrasi berhasil! Silakan login.</div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>NIM / Email</label>
                    <input type="text" name="nim" class="form-control" placeholder="Masukkan NIM" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block mt-2">Login</button>
            </form>

            <p class="text-center mt-2 text-small" style="color:#6b7280;">
                Belum punya akun? <a href="index.php?page=register">Daftar di sini</a>
            </p>
        </div>
    </div>
</body>
</html>

<?php
require_once 'controllers/AuthController.php';
$auth = new AuthController();
$error = $auth->register();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - E-Bike Campus</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <h2>Buat Akun E-Bike</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="nim">NIM</label>
                    <input type="text" id="nim" name="nim" class="form-control" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" class="form-control" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="email">Email Kampus</label>
                    <input type="email" id="email" name="email" class="form-control" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-secondary mt-3">Daftar</button>
            </form>
            <p class="text-center mt-4">Sudah punya akun? <a href="index.php?page=home">Login di sini</a></p>
        </div>
    </div>
</body>
</html>

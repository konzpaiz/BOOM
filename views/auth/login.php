<?php
require_once 'controllers/AuthController.php';
$auth = new AuthController();
$error = $auth->login();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Bike Campus</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-illustration">
                <i class="fa-solid fa-bolt"></i>
                <h2 style="color: var(--primary-color); margin:0;">E-Bike Campus</h2>
                <p class="text-muted text-center" style="max-width: 80%; margin-top: 10px;">Berkeliling kampus jadi lebih cepat, mudah, dan ramah lingkungan.</p>
            </div>
            <div class="auth-form">
                <h2 style="margin-top:0; font-weight: 700;">Selamat Datang!</h2>
                <p class="text-muted mb-4">Silakan masuk ke akun Anda.</p>
                
                <?php if (isset($_GET['msg']) && $_GET['msg'] == 'registered'): ?>
                    <div class="alert alert-success"><i class="fa-solid fa-check-circle"></i> Registrasi berhasil. Silakan login.</div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation"></i> <?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="nim">NIM / Username</label>
                        <div style="position: relative;">
                            <i class="fa-regular fa-user" style="position: absolute; left: 15px; top: 15px; color: #9CA3AF;"></i>
                            <input type="text" id="nim" name="nim" class="form-control" style="padding-left: 40px;" required autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div style="position: relative;">
                            <i class="fa-solid fa-lock" style="position: absolute; left: 15px; top: 15px; color: #9CA3AF;"></i>
                            <input type="password" id="password" name="password" class="form-control" style="padding-left: 40px;" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3" style="width: 100%;"><i class="fa-solid fa-right-to-bracket"></i> Login</button>
                </form>
                <p class="text-center mt-4 text-muted">Belum punya akun? <a href="index.php?page=register" style="color: var(--primary-color); font-weight: 600; text-decoration: none;">Daftar sekarang</a></p>
            </div>
        </div>
    </div>
</body>
</html>

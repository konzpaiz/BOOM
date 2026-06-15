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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-illustration">
                <i class="fa-solid fa-user-plus"></i>
                <h2 style="color: var(--primary-color); margin:0;">Buat Akun Baru</h2>
                <p class="text-muted text-center" style="max-width: 80%; margin-top: 10px;">Daftar dan rasakan kemudahan mobilitas di area kampus dengan sepeda listrik.</p>
            </div>
            <div class="auth-form">
                <h2 style="margin-top:0; font-weight: 700;">Registrasi</h2>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation"></i> <?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="grid" style="gap: 1rem;">
                        <div class="form-group mb-2">
                            <label for="nim">NIM</label>
                            <input type="text" id="nim" name="nim" class="form-control" required autocomplete="off">
                        </div>
                        <div class="form-group mb-2">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" id="name" name="name" class="form-control" required autocomplete="off">
                        </div>
                    </div>
                    
                    <div class="form-group mb-2 mt-2">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required autocomplete="off">
                    </div>
                    
                    <div class="form-group mb-2 mt-2">
                        <label for="phone">Nomor Telepon</label>
                        <input type="text" id="phone" name="phone" class="form-control" required autocomplete="off">
                    </div>

                    <div class="form-group mb-2 mt-2">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn btn-secondary mt-3" style="width: 100%;"><i class="fa-solid fa-user-check"></i> Daftar Akun</button>
                </form>
                <p class="text-center mt-4 text-muted">Sudah punya akun? <a href="index.php?page=home" style="color: var(--primary-color); font-weight: 600; text-decoration: none;">Login di sini</a></p>
            </div>
        </div>
    </div>
</body>
</html>

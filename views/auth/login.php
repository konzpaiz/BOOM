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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <h2>E-Bike Campus Login</h2>
            
            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'registered'): ?>
                <div class="alert alert-success">Registrasi berhasil. Silakan login.</div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="nim">NIM / Username</label>
                    <input type="text" id="nim" name="nim" class="form-control" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Login</button>
            </form>
            <p class="text-center mt-4">Belum punya akun? <a href="index.php?page=register">Daftar di sini</a></p>
        </div>
    </div>
</body>
</html>

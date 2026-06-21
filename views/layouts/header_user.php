<?php
require_once 'models/User.php';
$headerUser = new User();
if (isset($_SESSION['user_id'])) {
    $headerUser->readById($_SESSION['user_id']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>BOOM</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <img src="assets/img/qris/logo.png" alt="Logo">
            <span>BOOM</span>
        </div>
        <div class="header-right">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="index.php?page=profile" style="display:flex; align-items:center; gap:8px; color:inherit; text-decoration:none;">
                    <?php if ($headerUser->foto_profil && file_exists($headerUser->foto_profil)): ?>
                        <img src="<?= htmlspecialchars($headerUser->foto_profil) ?>" class="header-avatar" alt="Avatar">
                    <?php else: ?>
                        <div class="header-avatar" style="background:#1e3a5f; color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:12px; border-radius:50%; width:32px; height:32px;">
                            <?= strtoupper(substr($headerUser->name, 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    <span style="font-weight:600; color:#4b5563;"><?= htmlspecialchars($headerUser->name); ?></span>
                </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="container">

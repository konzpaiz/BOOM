<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOOM</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Include html5-qrcode for QR scanning -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</head>
<body>
    <nav class="navbar">
        <a href="index.php?page=user_dashboard" class="navbar-brand">🚴 BOOM</a>
        <div class="navbar-nav">
            <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                <a href="index.php?page=admin_dashboard">Dashboard Admin</a>
                <a href="index.php?page=admin_bikes">Kelola Sepeda</a>
                <a href="index.php?page=admin_users">Kelola User</a>
                <a href="index.php?page=admin_transactions">Transaksi</a>
            <?php else: ?>
                <a href="index.php?page=user_dashboard">Dashboard</a>
                <a href="index.php?page=scan">Scan QR</a>
                <a href="index.php?page=history">Riwayat Sewa</a>
            <?php endif; ?>
            <a href="index.php?page=logout" class="text-danger" style="font-weight:bold;">Logout (<?php echo $_SESSION['nim']; ?>)</a>
        </div>
    </nav>
    <div class="container">

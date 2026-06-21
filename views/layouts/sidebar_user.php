<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOOM</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</head>
<body>
    <div class="app-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h3><i class="fa-solid fa-bicycle"></i> BOOM</h3>
            </div>
            <div class="sidebar-menu">
                <a href="index.php?page=user_dashboard" class="<?php echo ($page=='user_dashboard')?'active':''; ?>"><i class="fa-solid fa-house"></i> Dashboard</a>
                <a href="index.php?page=user_bikes" class="<?php echo ($page=='user_bikes')?'active':''; ?>"><i class="fa-solid fa-list"></i> Daftar Sepeda</a>
                <a href="index.php?page=scan" class="<?php echo ($page=='scan')?'active':''; ?>"><i class="fa-solid fa-qrcode"></i> Scan QR</a>
                <a href="index.php?page=user_active" class="<?php echo ($page=='user_active')?'active':''; ?>"><i class="fa-solid fa-person-biking"></i> Penyewaan Aktif</a>
                <a href="index.php?page=history" class="<?php echo ($page=='history')?'active':''; ?>"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Sewa</a>
                <a href="index.php?page=profile" class="<?php echo ($page=='profile')?'active':''; ?>"><i class="fa-solid fa-user"></i> Profil</a>
            </div>
            <div class="sidebar-footer">
                <a href="index.php?page=logout" class="btn btn-danger" style="display:block; text-align:center;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="topbar">
                <div style="font-weight: 500;">
                    Hai, <?php echo htmlspecialchars($_SESSION['name']); ?> 👋
                </div>
                <div>
                    <span class="badge badge-success">Mahasiswa</span>
                </div>
            </div>
            <div class="container-fluid">

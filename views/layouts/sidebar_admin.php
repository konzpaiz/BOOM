<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - E-Bike Campus</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Chart.js for Reports -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="app-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h3><i class="fa-solid fa-shield-halved"></i> Admin Panel</h3>
            </div>
            <div class="sidebar-menu">
                <a href="index.php?page=admin_dashboard" class="<?php echo ($page=='admin_dashboard')?'active':''; ?>"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
                <a href="index.php?page=admin_users" class="<?php echo ($page=='admin_users')?'active':''; ?>"><i class="fa-solid fa-users"></i> Data Pengguna</a>
                <a href="index.php?page=admin_bikes" class="<?php echo ($page=='admin_bikes')?'active':''; ?>"><i class="fa-solid fa-bicycle"></i> Data Sepeda</a>
                <a href="index.php?page=admin_transactions" class="<?php echo ($page=='admin_transactions')?'active':''; ?>"><i class="fa-solid fa-money-bill-transfer"></i> Data Transaksi</a>
                <a href="index.php?page=admin_reports" class="<?php echo ($page=='admin_reports')?'active':''; ?>"><i class="fa-solid fa-chart-line"></i> Laporan</a>
            </div>
            <div class="sidebar-footer">
                <a href="index.php?page=logout" class="btn btn-danger" style="display:block; text-align:center;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="topbar">
                <div style="font-weight: 500;">
                    Selamat bekerja, <?php echo htmlspecialchars($_SESSION['name']); ?>
                </div>
                <div>
                    <span class="badge badge-warning">Administrator</span>
                </div>
            </div>
            <div class="container-fluid">

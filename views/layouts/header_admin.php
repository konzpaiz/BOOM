<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - E-Bike Campus</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="admin-layout">
    <aside class="sidebar">
        <h3>Admin Panel</h3>
        <a href="index.php?page=admin_dashboard" class="<?= ($page ?? '') === 'admin_dashboard' ? 'active' : '' ?>">Dashboard</a>
        <a href="index.php?page=admin_bikes" class="<?= ($page ?? '') === 'admin_bikes' ? 'active' : '' ?>">Data Sepeda</a>
        <a href="index.php?page=admin_transactions" class="<?= ($page ?? '') === 'admin_transactions' ? 'active' : '' ?>">Transaksi</a>
        <div class="sidebar-footer">
            <a href="index.php?page=logout">Logout (<?= htmlspecialchars($_SESSION['name'] ?? 'Admin') ?>)</a>
        </div>
    </aside>
    <main class="admin-main">

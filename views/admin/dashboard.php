<?php
require_once 'models/User.php';
require_once 'models/Bike.php';
require_once 'models/Transaction.php';

$bike = new Bike();
$totalBikes = $bike->readAll()->rowCount();
$availBikes = $bike->readAvailable()->rowCount();
$rentedBikes = $totalBikes - $availBikes;

$stmtBikes = $bike->readAll();

require 'views/layouts/header_admin.php';
?>

<div class="greeting" style="display:flex; align-items:center; justify-content:space-between; margin-bottom: 20px;">
    <div>
        <h2>Halo, Admin 👋</h2>
        <p>Status ketersediaan armada saat ini</p>
    </div>
    <div>
        <a href="index.php?page=admin_bikes" style="font-size: 13px; font-weight: 600; color: #1e3a5f; text-decoration: none;">⚙️ Kelola</a>
    </div>
</div>

<!-- Ringkasan Statistik -->
<div class="grid-2" style="margin-bottom: 20px; gap: 10px;">
    <div class="stat-card" style="padding: 14px; border-radius: 12px;">
        <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981; font-size: 20px;">🚲</div>
        <div class="stat-details">
            <h3 style="font-size: 18px;"><?= $availBikes ?></h3>
            <p style="font-size: 11px; color:#6b7280;">Tersedia</p>
        </div>
    </div>
    <div class="stat-card" style="padding: 14px; border-radius: 12px;">
        <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; font-size: 20px;">🔄</div>
        <div class="stat-details">
            <h3 style="font-size: 18px;"><?= $rentedBikes ?></h3>
            <p style="font-size: 11px; color:#6b7280;">Sedang Disewa</p>
        </div>
    </div>
</div>

<div class="section-title">Daftar Sepeda</div>
<div class="section-subtitle">Status ketersediaan unit sepeda listrik</div>

<div class="bike-grid">
    <?php 
    $i = 1; 
    while ($row = $stmtBikes->fetch(PDO::FETCH_ASSOC)): 
    ?>
    <div class="bike-card">
        <span class="bike-icon">🚲</span>
        <div class="bike-name">Sepeda <?= $i ?></div>
        <div style="font-size:11px; color:#9ca3af; margin-bottom:6px;"><?= htmlspecialchars($row['merk']) ?></div>
        <div class="bike-status">
            <?php if ($row['status'] === 'Tersedia'): ?>
                <span class="badge badge-green">Tersedia</span>
            <?php else: ?>
                <span class="badge badge-yellow">Sedang Disewa</span>
            <?php endif; ?>
        </div>
    </div>
    <?php 
    $i++; 
    endwhile; 
    ?>
</div>
<?php require 'views/layouts/footer_admin.php'; ?>

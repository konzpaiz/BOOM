<?php require 'views/layouts/header_user.php'; ?>
<?php
require_once 'models/Bike.php';
require_once 'models/Transaction.php';

$bike = new Bike();
$trx = new Transaction();

$active = $trx->readActiveByUserId($_SESSION['user_id'])->fetch(PDO::FETCH_ASSOC);
$stmt = $bike->readAll();
?>

<div class="section-title">Daftar Sepeda</div>
<div class="section-subtitle">Daftar seluruh sepeda listrik di area kampus</div>

<div class="bike-grid">
    <?php 
    $i = 1; 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
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
        
        <?php if ($row['status'] === 'Tersedia'): ?>
            <?php if ($active): ?>
                <button class="btn btn-secondary btn-sm" disabled style="opacity:0.5; width:100%;">Sewa</button>
            <?php else: ?>
                <a href="index.php?page=scan&code=<?= $row['kode_sepeda'] ?>" class="btn btn-primary btn-sm">Sewa Sepeda</a>
            <?php endif; ?>
        <?php else: ?>
            <button class="btn btn-secondary btn-sm" disabled style="opacity:0.4; cursor:default; width:100%">Tidak Tersedia</button>
        <?php endif; ?>
    </div>
    <?php 
    $i++; 
    endwhile; 
    ?>
</div>

<?php if ($i === 1): ?>
<div class="card text-center" style="padding:32px;">
    <div style="font-size:48px;margin-bottom:12px;">🚲</div>
    <p class="text-muted">Belum ada data sepeda yang terdaftar.</p>
</div>
<?php endif; ?>

<?php require 'views/layouts/footer_user.php'; ?>

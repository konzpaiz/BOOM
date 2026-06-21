<?php require 'views/layouts/header_user.php'; ?>
<?php
require_once 'models/Transaction.php';
require_once 'models/Bike.php';

$trx = new Transaction();
$bikeModel = new Bike();

$active = $trx->readActiveByUserId($_SESSION['user_id'])->fetch(PDO::FETCH_ASSOC);
$stmtBikes = $bikeModel->readAll();

$msg = $_GET['msg'] ?? '';
?>

<!-- Sapaan Pengguna -->
<div class="greeting" style="display:flex; align-items:center; justify-content:space-between; margin-bottom: 20px;">
    <div>
        <h2>Halo, <?= htmlspecialchars($_SESSION['name']) ?> 👋</h2>
        <p>Mau keliling kampus hari ini?</p>
    </div>
</div>

<?php if ($msg === 'returned'): ?>
    <div class="alert alert-success">Penyewaan berhasil diselesaikan! Status: Lunas.</div>
<?php endif; ?>

<?php if ($msg === 'rental_started'): ?>
    <div class="alert alert-success">Pembayaran berhasil! Penyewaan sepeda dimulai.</div>
<?php endif; ?>

<!-- Status Penyewaan Aktif -->
<?php if ($active): ?>
    <div class="rental-card" style="margin-bottom: 20px;">
        <div class="card-title">Sewa Berlangsung</div>
        <div class="detail-row">
            <span class="label">Sepeda</span>
            <span class="value"><?= htmlspecialchars($active['merk']) ?></span>
        </div>
        <div class="detail-row">
            <span class="label">Mulai</span>
            <span class="value"><?= date('H:i', strtotime($active['waktu_mulai'])) ?></span>
        </div>
        <a href="index.php?page=user_active" class="btn btn-block mt-2" style="background:#fff; color:#1e3a5f;">Lihat Detail & Akhiri</a>
    </div>
<?php endif; ?>

<!-- Daftar Sepeda di Home -->
<div class="section-title">Daftar Sepeda Kampus</div>
<div class="section-subtitle">Ketersediaan sepeda listrik saat ini</div>

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

            <?php if ($row['status'] === 'Tersedia'): ?>
                <?php if ($active): ?>
                    <button class="btn btn-secondary btn-sm" disabled style="opacity:0.5; width:100%;">Sewa</button>
                <?php else: ?>
                    <a href="index.php?page=payment&bike_id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Sewa Sepeda</a>
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
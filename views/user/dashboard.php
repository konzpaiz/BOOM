<?php require 'views/layouts/header_user.php'; ?>
<?php 
require_once 'models/Transaction.php';

$trx = new Transaction();
$active = $trx->readActiveByUserId($_SESSION['user_id'])->fetch(PDO::FETCH_ASSOC);

$sewaWarning = false;
$sewaJam = 0;
if ($active) {
    $sewaJam = floor((time() - strtotime($active['waktu_mulai'])) / 3600);
    if ($sewaJam >= 2) $sewaWarning = true;
}
?>

<div class="greeting">
    <h2>Halo, <?php echo htmlspecialchars($_SESSION['name']); ?> 👋</h2>
    <p>Mau kemana hari ini?</p>
</div>

<?php if ($sewaWarning): ?>
<div class="alert alert-warning">
    Anda sudah menyewa selama <?php echo $sewaJam; ?> jam. 
    Estimasi biaya: Rp <?php echo number_format(ceil((time() - strtotime($active['waktu_mulai'])) / 3600) * $active['tarif_per_jam'], 0, ',', '.'); ?>
</div>
<?php endif; ?>

<?php if ($active): ?>
<div class="rental-card">
    <div class="card-title">Sewa Aktif</div>
    <div class="detail-row">
        <span class="label">Sepeda</span>
        <span class="value"><?php echo $active['merk']; ?></span>
    </div>
    <div class="detail-row">
        <span class="label">Mulai</span>
        <span class="value"><?php echo date('H:i', strtotime($active['waktu_mulai'])); ?></span>
    </div>
    <a href="index.php?page=user_active" class="btn btn-block mt-2">Lihat Detail</a>
</div>
<?php endif; ?>

<div class="quick-buttons" style="grid-template-columns: repeat(2, 1fr);">
    <a href="index.php?page=scan">
        <span class="qb-icon">📷</span>Scan QR
    </a>
    <a href="index.php?page=faq">
        <span class="qb-icon">❓</span>Bantuan
    </a>
</div>

<?php require 'views/layouts/footer_user.php'; ?>

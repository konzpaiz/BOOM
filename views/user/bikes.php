<?php require 'views/layouts/header_user.php'; ?>
<?php
require_once 'models/Bike.php';
$bike = new Bike();
$stmt = $bike->readAll();
?>

<div class="section-title">Daftar Sepeda</div>
<div class="section-subtitle">Pilih sepeda yang tersedia untuk disewa</div>

<div class="bike-grid">
    <?php $i = 1; while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
    <div class="bike-card">
        <span class="bike-icon">🚲</span>
        <div class="bike-name">Sepeda <?php echo $i; ?></div>
        <div class="bike-status">
            <?php if ($row['status'] === 'Tersedia'): ?>
                <span class="badge badge-green">Tersedia</span>
            <?php elseif ($row['status'] === 'Disewa'): ?>
                <span class="badge badge-yellow">Dipakai</span>
            <?php else: ?>
                <span class="badge badge-red">Perbaikan</span>
            <?php endif; ?>
        </div>
        <?php if ($row['status'] === 'Tersedia'): ?>
            <a href="index.php?page=scan&code=<?= $row['kode_sepeda'] ?>" class="btn btn-primary btn-sm">Sewa</a>
        <?php else: ?>
            <button class="btn btn-secondary btn-sm" disabled style="opacity:0.4;cursor:default;">Tidak Tersedia</button>
        <?php endif; ?>
    </div>
    <?php $i++; endwhile; ?>
</div>

<?php if ($i === 1): ?>
<div class="card text-center">
    <p class="text-muted">Belum ada data sepeda.</p>
</div>
<?php endif; ?>

<?php require 'views/layouts/footer_user.php'; ?>

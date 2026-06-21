<?php require 'views/layouts/header_user.php'; ?>
<?php
require_once 'models/Transaction.php';
$trx = new Transaction();
$stmt = $trx->readByUserId($_SESSION['user_id']);
?>

<div class="section-title">Riwayat Penyewaan</div>
<div class="section-subtitle">Daftar semua penyewaan Anda</div>

<?php if(isset($_GET['msg']) && $_GET['msg'] == 'payment_success'): ?>
    <div class="alert alert-success">Pembayaran berhasil! Terima kasih.</div>
<?php endif; ?>

<?php if ($stmt->rowCount() > 0): ?>
    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
    <?php 
        $durasi = '-';
        if ($row['waktu_mulai'] && $row['waktu_selesai']) {
            $diff = strtotime($row['waktu_selesai']) - strtotime($row['waktu_mulai']);
            $h = floor($diff / 3600);
            $m = floor(($diff % 3600) / 60);
            $durasi = ($h > 0 ? $h . ' jam ' : '') . $m . ' menit';
        } elseif ($row['status_sewa'] == 'Berjalan') {
            $durasi = 'Berlangsung';
        }
    ?>
    <div class="card">
        <div class="flex-between" style="margin-bottom:8px;">
            <div>
                <div style="font-weight:700; font-size:14px;"><?= $row['merk'] ?></div>
                <div class="text-muted text-small"><?= date('d M Y, H:i', strtotime($row['waktu_mulai'])) ?></div>
            </div>
            <?php if ($row['status_sewa'] == 'Berjalan'): ?>
                <span class="badge badge-yellow">Aktif</span>
            <?php elseif ($row['status_pembayaran'] == 'Belum Bayar'): ?>
                <span class="badge badge-red">Belum Bayar</span>
            <?php else: ?>
                <span class="badge badge-green">Selesai</span>
            <?php endif; ?>
        </div>
        <div class="detail-row">
            <span class="label">Durasi</span>
            <span class="value"><?= $durasi ?></span>
        </div>
        <?php if ($row['total_biaya']): ?>
        <div class="detail-row">
            <span class="label">Total</span>
            <span class="value">Rp <?= number_format($row['total_biaya'],0,',','.') ?></span>
        </div>
        <?php endif; ?>
        <?php if ($row['status_sewa'] !== 'Berjalan' && $row['status_pembayaran'] == 'Belum Bayar'): ?>
            <a href="index.php?page=payment&trx_id=<?= $row['id'] ?>" class="btn btn-primary btn-block btn-sm mt-1">Bayar</a>
        <?php endif; ?>
    </div>
    <?php endwhile; ?>
<?php else: ?>
<div class="card text-center" style="padding:32px;">
    <div style="font-size:48px;margin-bottom:12px;">📋</div>
    <p class="text-muted">Belum ada riwayat penyewaan</p>
</div>
<?php endif; ?>

<?php require 'views/layouts/footer_user.php'; ?>

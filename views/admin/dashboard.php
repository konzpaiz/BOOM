<?php
require_once 'models/User.php';
require_once 'models/Bike.php';
require_once 'models/Transaction.php';
require_once 'config/database.php';

$user = new User();
$bike = new Bike();
$transaction = new Transaction();

$totalUsers = $user->readAll()->rowCount();
$totalBikes = $bike->readAll()->rowCount();
$availBikes = $bike->readAvailable()->rowCount();
$totalTrx = $transaction->readAll()->rowCount();

$db = new Database();
$conn = $db->getConnection();
$stmtRev = $conn->prepare("SELECT COALESCE(SUM(total_biaya), 0) as total FROM transaksi WHERE status_pembayaran = 'Lunas'");
$stmtRev->execute();
$revenue = $stmtRev->fetch(PDO::FETCH_ASSOC)['total'];

$stmtRecent = $conn->prepare("SELECT t.*, u.name as user_name, u.nim, s.kode_sepeda, s.merk FROM transaksi t LEFT JOIN users u ON t.user_id = u.id LEFT JOIN sepeda s ON t.sepeda_id = s.id ORDER BY t.id DESC LIMIT 5");
$stmtRecent->execute();

require 'views/layouts/header_admin.php';
?>

<h3 class="mb-2">Dashboard</h3>

<div class="stats-row">
    <div class="stat-box">
        <div class="number"><?= number_format($totalUsers) ?></div>
        <div class="label">Pengguna</div>
    </div>
    <div class="stat-box">
        <div class="number"><?= $availBikes ?>/<?= $totalBikes ?></div>
        <div class="label">Sepeda Tersedia</div>
    </div>
    <div class="stat-box">
        <div class="number"><?= number_format($totalTrx) ?></div>
        <div class="label">Transaksi</div>
    </div>
    <div class="stat-box">
        <div class="number">Rp <?= number_format($revenue, 0, ',', '.') ?></div>
        <div class="label">Pendapatan</div>
    </div>
</div>

<div class="card" style="padding:0; overflow-x:auto;">
    <div style="padding:10px 15px; border-bottom:1px solid #ddd;"><strong>Transaksi Terbaru</strong></div>
    <table>
        <thead>
            <tr>
                <th>Pengguna</th>
                <th>Sepeda</th>
                <th>Waktu</th>
                <th>Biaya</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $stmtRecent->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= htmlspecialchars($row['user_name'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['merk'] ?? '-') ?></td>
                <td><?= $row['waktu_mulai'] ? date('d M H:i', strtotime($row['waktu_mulai'])) : '-' ?></td>
                <td>Rp <?= number_format($row['total_biaya'] ?? 0, 0, ',', '.') ?></td>
                <td>
                    <?php if ($row['status_sewa'] === 'Berjalan'): ?>
                        <span class="badge badge-yellow">Berjalan</span>
                    <?php else: ?>
                        <span class="badge badge-green">Selesai</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
            <?php if ($stmtRecent->rowCount() === 0): ?>
            <tr><td colspan="5" class="text-center text-muted" style="padding:15px;">Belum ada transaksi</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require 'views/layouts/footer_admin.php'; ?>

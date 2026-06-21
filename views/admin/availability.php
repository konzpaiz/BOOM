<?php
require_once 'config/database.php';

$db = new Database();
$conn = $db->getConnection();

// Query to get all bikes and check their current active rental details
$query = "SELECT s.*, t.waktu_mulai, u.name as user_name, u.nim, u.phone
          FROM sepeda s
          LEFT JOIN transaksi t ON s.id = t.sepeda_id AND t.status_sewa = 'Berjalan'
          LEFT JOIN users u ON t.user_id = u.id
          ORDER BY s.status ASC, s.kode_sepeda ASC";
$stmt = $conn->prepare($query);
$stmt->execute();

$bikes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$availCount = 0;
$rentedCount = 0;
$repairCount = 0;

foreach ($bikes as $b) {
    if ($b['status'] === 'Tersedia') $availCount++;
    elseif ($b['status'] === 'Disewa') $rentedCount++;
    else $repairCount++;
}

require 'views/layouts/header_admin.php';
?>

<div class="admin-topbar">
    <h1>Ketersediaan Sepeda</h1>
    <div class="topbar-info">Status ketersediaan armada secara real-time</div>
</div>

<div class="admin-content">
    <!-- Stat Mini -->
    <div class="stats-row" style="margin-bottom:20px;">
        <div class="stat-box" style="border-top: 4px solid #10b981;">
            <div class="number" style="color:#10b981;"><?= $availCount ?></div>
            <div class="label">Sepeda Tersedia</div>
        </div>
        <div class="stat-box" style="border-top: 4px solid #f59e0b;">
            <div class="number" style="color:#f59e0b;"><?= $rentedCount ?></div>
            <div class="label">Sedang Dipakai</div>
        </div>
        <div class="stat-box" style="border-top: 4px solid #ef4444;">
            <div class="number" style="color:#ef4444;"><?= $repairCount ?></div>
            <div class="label">Dalam Perbaikan</div>
        </div>
    </div>

    <!-- Monitoring Table -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3><i class="fa fa-desktop" style="margin-right:8px; color:#1e3a5f;"></i>Live Monitoring Armada</h3>
        </div>
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>Merk</th>
                        <th>Status</th>
                        <th>Detail Pengguna / Pemakai</th>
                        <th>Waktu Mulai Sewa</th>
                        <th>Durasi Berjalan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bikes as $row): ?>
                    <?php 
                        $durasi = '-';
                        if ($row['status'] === 'Disewa' && $row['waktu_mulai']) {
                            $diff = time() - strtotime($row['waktu_mulai']);
                            $h = floor($diff / 3600);
                            $m = floor(($diff % 3600) / 60);
                            $durasi = ($h > 0 ? $h . ' jam ' : '') . $m . ' menit';
                        }
                    ?>
                    <tr>

                        <td><?= htmlspecialchars($row['merk']) ?></td>
                        <td>
                            <?php if ($row['status'] === 'Tersedia'): ?>
                                <span class="badge badge-green">Tersedia</span>
                            <?php elseif ($row['status'] === 'Disewa'): ?>
                                <span class="badge badge-yellow">Sedang Dipakai</span>
                            <?php else: ?>
                                <span class="badge badge-red">Perbaikan</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($row['status'] === 'Disewa' && $row['user_name']): ?>
                                <div style="font-weight:600; color:#1e3a5f;"><?= htmlspecialchars($row['user_name']) ?></div>
                                <div class="text-muted text-small">NIM: <?= htmlspecialchars($row['nim']) ?></div>
                                <div class="text-muted text-small">Telp: <?= htmlspecialchars($row['phone'] ?? '-') ?></div>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= ($row['status'] === 'Disewa' && $row['waktu_mulai']) ? date('d M Y, H:i', strtotime($row['waktu_mulai'])) : '<span class="text-muted">—</span>' ?>
                        </td>
                        <td>
                            <?php if ($row['status'] === 'Disewa'): ?>
                                <strong style="color:#d97706;"><?= $durasi ?></strong>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (count($bikes) === 0): ?>
                        <tr><td colspan="6" class="text-center text-muted" style="padding:20px;">Belum ada data armada sepeda.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer_admin.php'; ?>

<?php
require_once 'models/Transaction.php';

$transaction = new Transaction();
$stmt = $transaction->readAll();

require 'views/layouts/header_admin.php';
?>

<h3 class="mb-2">Data Transaksi</h3>

<div class="card" style="padding:0; overflow-x:auto;">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Pengguna</th>
                <th>Sepeda</th>
                <th>Mulai</th>
                <th>Selesai</th>
                <th>Biaya</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td>#<?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['user_name'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['merk'] ?? '-') ?></td>
                <td><?= $row['waktu_mulai'] ? date('d M H:i', strtotime($row['waktu_mulai'])) : '-' ?></td>
                <td><?= $row['waktu_selesai'] ? date('d M H:i', strtotime($row['waktu_selesai'])) : '-' ?></td>
                <td>
                    <?php if ($row['total_biaya']): ?>
                        Rp <?= number_format($row['total_biaya'], 0, ',', '.') ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($row['status_sewa'] === 'Berjalan'): ?>
                        <span class="badge badge-yellow">Berjalan</span>
                    <?php else: ?>
                        <span class="badge badge-green">Selesai</span>
                    <?php endif; ?>
                    <?php if ($row['status_pembayaran'] === 'Lunas'): ?>
                        <span class="badge badge-blue">Lunas</span>
                    <?php else: ?>
                        <span class="badge badge-red">Belum Bayar</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
            <?php if ($stmt->rowCount() === 0): ?>
            <tr><td colspan="7" class="text-center text-muted" style="padding:15px;">Belum ada transaksi</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require 'views/layouts/footer_admin.php'; ?>

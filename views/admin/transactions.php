<?php require 'views/layouts/header.php'; ?>
<?php
require_once 'models/Transaction.php';
$trxModel = new Transaction();

$transactions = $trxModel->readAll();
?>

<div>
    <h2>Data Transaksi</h2>

    <div class="table-responsive mt-4">
        <table>
            <thead>
                <tr>
                    <th>ID Trx</th>
                    <th>Pengguna</th>
                    <th>Sepeda</th>
                    <th>Waktu Mulai</th>
                    <th>Waktu Selesai</th>
                    <th>Total Biaya</th>
                    <th>Status Sewa</th>
                    <th>Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $transactions->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td>#<?php echo $row['id']; ?></td>
                    <td><?php echo $row['user_name'] . ' (' . $row['nim'] . ')'; ?></td>
                    <td><?php echo $row['kode_sepeda']; ?></td>
                    <td><?php echo $row['waktu_mulai']; ?></td>
                    <td><?php echo $row['waktu_selesai'] ? $row['waktu_selesai'] : '-'; ?></td>
                    <td><?php echo $row['total_biaya'] ? 'Rp ' . number_format($row['total_biaya'],0,',','.') : '-'; ?></td>
                    <td>
                        <?php if ($row['status_sewa'] == 'Berjalan'): ?>
                            <span class="badge badge-warning">Berjalan</span>
                        <?php else: ?>
                            <span class="badge badge-success">Selesai</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['status_pembayaran'] == 'Belum Bayar'): ?>
                            <span class="badge" style="background-color: #FEE2E2; color: #991B1B;">Belum Bayar</span>
                        <?php else: ?>
                            <span class="badge badge-success">Lunas</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>

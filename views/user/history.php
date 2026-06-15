<?php require 'views/layouts/header.php'; ?>
<?php
require_once 'models/Transaction.php';
require_once 'controllers/RentalController.php';

$rental = new RentalController();
$rental->endRental();

$trx = new Transaction();
$history = $trx->readByUserId($_SESSION['user_id']);
?>

<div>
    <h2>Riwayat Penyewaan</h2>
    
    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'payment_success'): ?>
        <div class="alert alert-success">Pembayaran berhasil! Terima kasih telah menggunakan E-Bike Campus.</div>
    <?php endif; ?>

    <div class="table-responsive mt-3">
        <table>
            <thead>
                <tr>
                    <th>Sepeda</th>
                    <th>Waktu Mulai</th>
                    <th>Waktu Selesai</th>
                    <th>Total Biaya</th>
                    <th>Status Sewa</th>
                    <th>Status Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $history->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $row['merk'] . ' (' . $row['kode_sepeda'] . ')'; ?></td>
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
                        <td>
                            <?php if ($row['status_sewa'] == 'Berjalan'): ?>
                                <form method="POST" action="" onsubmit="return confirm('Akhiri penyewaan ini?');">
                                    <input type="hidden" name="transaction_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">Akhiri</button>
                                </form>
                            <?php elseif ($row['status_pembayaran'] == 'Belum Bayar'): ?>
                                <a href="index.php?page=payment&trx_id=<?php echo $row['id']; ?>" class="btn btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">Bayar</a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
                <?php if($history->rowCount() == 0): ?>
                    <tr><td colspan="7" class="text-center">Belum ada riwayat penyewaan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>

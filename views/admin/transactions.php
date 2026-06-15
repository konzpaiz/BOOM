<?php require 'views/layouts/sidebar_admin.php'; ?>
<?php
require_once 'models/Transaction.php';
$trxModel = new Transaction();

$filter_date = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';

$db = new Database();
$conn = $db->getConnection();

if ($filter_date) {
    $query = "SELECT t.*, u.nim, u.name as user_name, s.kode_sepeda, s.merk FROM transaksi t LEFT JOIN users u ON t.user_id = u.id LEFT JOIN sepeda s ON t.sepeda_id = s.id WHERE DATE(t.waktu_mulai) = :filter_date ORDER BY t.id DESC";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':filter_date', $filter_date);
    $stmt->execute();
    $transactions = $stmt;
} else {
    $transactions = $trxModel->readAll();
}

?>

<div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="margin:0;">Data Transaksi</h2>
        
        <form method="GET" action="index.php" style="display: flex; gap: 0.5rem;">
            <input type="hidden" name="page" value="admin_transactions">
            <input type="date" name="filter_date" class="form-control" value="<?php echo $filter_date; ?>" style="width: auto;">
            <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-filter"></i> Filter</button>
            <?php if($filter_date): ?>
                <a href="index.php?page=admin_transactions" class="btn btn-danger"><i class="fa-solid fa-xmark"></i></a>
            <?php endif; ?>
        </form>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Trx ID</th>
                    <th>Pengguna</th>
                    <th>Sepeda</th>
                    <th>Waktu Mulai</th>
                    <th>Waktu Selesai</th>
                    <th>Biaya</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $transactions->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><strong>#<?php echo $row['id']; ?></strong></td>
                    <td>
                        <?php echo $row['user_name']; ?><br>
                        <small class="text-muted"><?php echo $row['nim']; ?></small>
                    </td>
                    <td>
                        <?php echo $row['merk']; ?><br>
                        <small class="text-muted"><?php echo $row['kode_sepeda']; ?></small>
                    </td>
                    <td><?php echo date('d/m/Y H:i', strtotime($row['waktu_mulai'])); ?></td>
                    <td><?php echo $row['waktu_selesai'] ? date('d/m/Y H:i', strtotime($row['waktu_selesai'])) : '-'; ?></td>
                    <td><strong style="color:var(--primary-color);"><?php echo $row['total_biaya'] ? 'Rp ' . number_format($row['total_biaya'],0,',','.') : '-'; ?></strong></td>
                    <td>
                        <div style="margin-bottom: 0.25rem;">
                        <?php if ($row['status_sewa'] == 'Berjalan'): ?>
                            <span class="badge badge-warning" style="font-size:0.75rem;">Sewa Aktif</span>
                        <?php else: ?>
                            <span class="badge badge-success" style="font-size:0.75rem;">Sewa Selesai</span>
                        <?php endif; ?>
                        </div>
                        <div>
                        <?php if ($row['status_pembayaran'] == 'Belum Bayar'): ?>
                            <span class="badge badge-danger" style="font-size:0.75rem;">Belum Bayar</span>
                        <?php else: ?>
                            <span class="badge badge-success" style="font-size:0.75rem;">Lunas</span>
                        <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php if($transactions->rowCount() == 0): ?>
                    <tr><td colspan="7" class="text-center" style="padding: 3rem;">Tidak ada data transaksi.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>

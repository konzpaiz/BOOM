<?php require 'views/layouts/sidebar_user.php'; ?>
<?php 
require_once 'models/Transaction.php';
require_once 'models/Bike.php';

$trx = new Transaction();
$bikeModel = new Bike();

$active = $trx->readActiveByUserId($_SESSION['user_id'])->fetch(PDO::FETCH_ASSOC);
$available_bikes = $bikeModel->readAvailable()->rowCount();

// Get recent 3 history
$history = $trx->readByUserId($_SESSION['user_id']);
$recent_history = [];
$i = 0;
while($row = $history->fetch(PDO::FETCH_ASSOC)) {
    if($i < 3) $recent_history[] = $row;
    $i++;
}
?>

<div class="grid">
    <div class="card stat-card">
        <div class="stat-icon stat-primary"><i class="fa-solid fa-bicycle"></i></div>
        <div class="stat-details">
            <h3><?php echo $available_bikes; ?></h3>
            <p>Sepeda Tersedia</p>
        </div>
    </div>
    
    <div class="card stat-card">
        <div class="stat-icon <?php echo $active ? 'stat-secondary' : 'stat-danger'; ?>"><i class="fa-solid fa-person-biking"></i></div>
        <div class="stat-details">
            <h3><?php echo $active ? '1' : '0'; ?></h3>
            <p>Penyewaan Aktif</p>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h3 style="margin:0;"><i class="fa-solid fa-clock-rotate-left" style="color: var(--primary-color);"></i> Transaksi Terakhir</h3>
        <a href="index.php?page=history" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">Lihat Semua</a>
    </div>
    
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Sepeda</th>
                    <th>Waktu Mulai</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($recent_history as $row): ?>
                <tr>
                    <td><?php echo $row['merk']; ?></td>
                    <td><?php echo date('d M Y, H:i', strtotime($row['waktu_mulai'])); ?></td>
                    <td>
                        <?php if ($row['status_sewa'] == 'Berjalan'): ?>
                            <span class="badge badge-warning">Berjalan</span>
                        <?php else: ?>
                            <span class="badge badge-success">Selesai</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($recent_history)): ?>
                    <tr><td colspan="3" class="text-center text-muted">Belum ada transaksi.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>

<?php require 'views/layouts/sidebar_user.php'; ?>
<?php
require_once 'models/Transaction.php';
$trx = new Transaction();
$history = $trx->readByUserId($_SESSION['user_id']);
?>

<div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="margin:0;">Riwayat Penyewaan</h2>
        <div style="position: relative; width: 300px;">
            <i class="fa-solid fa-search" style="position: absolute; left: 15px; top: 12px; color: #9CA3AF;"></i>
            <input type="text" id="searchInput" class="form-control" style="padding-left: 40px;" placeholder="Cari sepeda atau tanggal...">
        </div>
    </div>
    
    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'payment_success'): ?>
        <div class="alert alert-success"><i class="fa-solid fa-check-circle"></i> Pembayaran berhasil! Terima kasih telah menggunakan E-Bike Campus.</div>
    <?php endif; ?>

    <div class="card table-responsive mt-3" style="padding: 0;">
        <table id="historyTable">
            <thead>
                <tr>
                    <th>Tanggal & Waktu</th>
                    <th>Unit Sepeda</th>
                    <th>Durasi</th>
                    <th>Total Biaya</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $history->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td>
                            <strong><?php echo date('d M Y', strtotime($row['waktu_mulai'])); ?></strong><br>
                            <small class="text-muted"><?php echo date('H:i', strtotime($row['waktu_mulai'])); ?> - <?php echo $row['waktu_selesai'] ? date('H:i', strtotime($row['waktu_selesai'])) : 'Sekarang'; ?></small>
                        </td>
                        <td>
                            <strong><?php echo $row['merk']; ?></strong><br>
                            <small class="text-muted"><?php echo $row['kode_sepeda']; ?></small>
                        </td>
                        <td>
                            <?php 
                            if ($row['waktu_selesai']) {
                                $mulai = strtotime($row['waktu_mulai']);
                                $selesai = strtotime($row['waktu_selesai']);
                                $diff = ceil(($selesai - $mulai) / 3600);
                                echo $diff . " Jam";
                            } else {
                                echo "-";
                            }
                            ?>
                        </td>
                        <td>
                            <strong style="color: var(--primary-color);">
                                <?php echo $row['total_biaya'] ? 'Rp ' . number_format($row['total_biaya'],0,',','.') : '-'; ?>
                            </strong>
                        </td>
                        <td>
                            <?php if ($row['status_sewa'] == 'Berjalan'): ?>
                                <span class="badge badge-warning">Aktif</span>
                            <?php else: ?>
                                <?php if ($row['status_pembayaran'] == 'Belum Bayar'): ?>
                                    <span class="badge badge-danger">Belum Bayar</span>
                                <?php else: ?>
                                    <span class="badge badge-success">Selesai</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($row['status_sewa'] == 'Berjalan'): ?>
                                <a href="index.php?page=user_active" class="btn btn-secondary" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">Lihat</a>
                            <?php elseif ($row['status_pembayaran'] == 'Belum Bayar'): ?>
                                <a href="index.php?page=payment&trx_id=<?php echo $row['id']; ?>" class="btn btn-primary" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">Bayar</a>
                            <?php else: ?>
                                <span class="text-muted"><i class="fa-solid fa-check"></i> Lunas</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
                <?php if($history->rowCount() == 0): ?>
                    <tr><td colspan="6" class="text-center" style="padding: 3rem;">Belum ada riwayat penyewaan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#historyTable tbody tr');
    
    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script>

<?php require 'views/layouts/footer.php'; ?>

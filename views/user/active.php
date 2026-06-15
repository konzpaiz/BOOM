<?php require 'views/layouts/sidebar_user.php'; ?>
<?php 
require_once 'models/Transaction.php';
require_once 'controllers/RentalController.php';

$rental = new RentalController();
$rental->endRental();

$trx = new Transaction();
$active = $trx->readActiveByUserId($_SESSION['user_id'])->fetch(PDO::FETCH_ASSOC);
?>

<div>
    <h2 style="margin-bottom: 1.5rem;">Penyewaan Aktif</h2>
    
    <?php if ($active): ?>
        <div class="card" style="max-width: 600px; margin: 0 auto; text-align: center; border-top: 5px solid var(--secondary-color);">
            <div style="background-color: #ECFDF5; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: var(--secondary-color); margin: 0 auto 1.5rem auto;">
                <i class="fa-solid fa-person-biking"></i>
            </div>
            
            <h3 style="margin-top: 0;">Sepeda Sedang Digunakan</h3>
            
            <div style="background-color: #F8FAFC; border-radius: 12px; padding: 1.5rem; margin: 1.5rem 0; text-align: left;">
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #E5E7EB; padding-bottom: 0.75rem; margin-bottom: 0.75rem;">
                    <span class="text-muted">Unit Sepeda</span>
                    <strong style="color: var(--primary-color);"><?php echo $active['merk']; ?> (<?php echo $active['kode_sepeda']; ?>)</strong>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #E5E7EB; padding-bottom: 0.75rem; margin-bottom: 0.75rem;">
                    <span class="text-muted">Waktu Mulai</span>
                    <strong><?php echo date('d M Y, H:i', strtotime($active['waktu_mulai'])); ?></strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span class="text-muted">Tarif Berlaku</span>
                    <strong>Rp <?php echo number_format($active['tarif_per_jam'],0,',','.'); ?> / jam</strong>
                </div>
            </div>
            
            <form method="POST" action="" onsubmit="return confirm('Anda yakin ingin mengakhiri penyewaan ini? Biaya akan dihitung dari waktu mulai hingga saat ini.');">
                <input type="hidden" name="transaction_id" value="<?php echo $active['id']; ?>">
                <button type="submit" class="btn btn-danger" style="width: 100%; font-size: 1.1rem; padding: 1rem;"><i class="fa-solid fa-stop-circle"></i> Akhiri Penyewaan</button>
            </form>
        </div>
    <?php else: ?>
        <div class="card text-center" style="padding: 4rem 2rem;">
            <i class="fa-solid fa-bicycle" style="font-size: 4rem; color: #D1D5DB; margin-bottom: 1rem;"></i>
            <h3 style="color: var(--text-muted);">Tidak Ada Penyewaan Aktif</h3>
            <p class="text-muted mb-4">Anda belum menyewa sepeda apapun saat ini.</p>
            <a href="index.php?page=user_bikes" class="btn btn-primary"><i class="fa-solid fa-list"></i> Lihat Daftar Sepeda</a>
        </div>
    <?php endif; ?>
</div>

<?php require 'views/layouts/footer.php'; ?>

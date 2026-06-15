<?php require 'views/layouts/sidebar_user.php'; ?>
<?php
require_once 'models/Transaction.php';
require_once 'controllers/RentalController.php';

$rental = new RentalController();
$rental->pay();

if (!isset($_GET['trx_id'])) {
    echo "<div class='alert alert-danger'><i class='fa-solid fa-circle-exclamation'></i> Transaksi tidak ditemukan.</div>";
    require 'views/layouts/footer.php';
    exit;
}

$trx = new Transaction();
$transaction = $trx->readById($_GET['trx_id']);

if (!$transaction || $transaction['user_id'] != $_SESSION['user_id'] || $transaction['status_pembayaran'] == 'Lunas') {
    echo "<div class='alert alert-danger'><i class='fa-solid fa-circle-exclamation'></i> Transaksi tidak valid atau sudah dibayar.</div>";
    require 'views/layouts/footer.php';
    exit;
}
?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="text-center mb-4">
        <h2 style="margin:0;"><i class="fa-solid fa-wallet" style="color: var(--secondary-color);"></i> Pembayaran Sewa</h2>
        <p class="text-muted">Selesaikan pembayaran Anda untuk transaksi ini.</p>
    </div>
    
    <div style="background-color: #F8FAFC; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; border: 1px solid #E5E7EB;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
            <span class="text-muted">ID Transaksi</span>
            <strong>#<?php echo $transaction['id']; ?></strong>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
            <span class="text-muted">Waktu Mulai</span>
            <strong><?php echo date('d M Y, H:i', strtotime($transaction['waktu_mulai'])); ?></strong>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
            <span class="text-muted">Waktu Selesai</span>
            <strong><?php echo date('d M Y, H:i', strtotime($transaction['waktu_selesai'])); ?></strong>
        </div>
        <hr style="border: none; border-top: 1px dashed #CBD5E1; margin: 1rem 0;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span class="text-muted">Total Tagihan</span>
            <strong style="color: var(--primary-color); font-size: 1.5rem;">Rp <?php echo number_format($transaction['total_biaya'],0,',','.'); ?></strong>
        </div>
    </div>

    <form method="POST" action="">
        <input type="hidden" name="transaction_id" value="<?php echo $transaction['id']; ?>">
        
        <div class="form-group">
            <label>Pilih Metode Pembayaran</label>
            <div style="position: relative;">
                <i class="fa-solid fa-credit-card" style="position: absolute; left: 15px; top: 15px; color: #9CA3AF;"></i>
                <select class="form-control" name="payment_method" style="padding-left: 40px; appearance: none;" required>
                    <option value="qris">QRIS (GoPay, OVO, Dana, LinkAja)</option>
                    <option value="transfer">Virtual Account Bank</option>
                    <option value="saldo">Saldo Dompet Kampus</option>
                </select>
                <i class="fa-solid fa-chevron-down" style="position: absolute; right: 15px; top: 15px; color: #9CA3AF; pointer-events: none;"></i>
            </div>
        </div>
        
        <button type="submit" class="btn btn-secondary mt-3" style="width: 100%; font-size: 1.1rem; padding: 1rem;">
            <i class="fa-solid fa-money-bill-wave"></i> Bayar Sekarang
        </button>
    </form>
</div>

<?php require 'views/layouts/footer.php'; ?>

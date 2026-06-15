<?php require 'views/layouts/header.php'; ?>
<?php
require_once 'models/Transaction.php';
require_once 'controllers/RentalController.php';

$rental = new RentalController();
$rental->pay();

if (!isset($_GET['trx_id'])) {
    echo "<div class='alert alert-danger'>Transaksi tidak ditemukan.</div>";
    require 'views/layouts/footer.php';
    exit;
}

$trx = new Transaction();
$transaction = $trx->readById($_GET['trx_id']);

if (!$transaction || $transaction['user_id'] != $_SESSION['user_id'] || $transaction['status_pembayaran'] == 'Lunas') {
    echo "<div class='alert alert-danger'>Transaksi tidak valid atau sudah dibayar.</div>";
    require 'views/layouts/footer.php';
    exit;
}
?>

<div class="card" style="max-width: 500px; margin: 0 auto;">
    <h2 class="text-center">Pembayaran Sewa Sepeda</h2>
    
    <div style="background-color: #F9FAFB; padding: 1.5rem; border-radius: 8px; margin: 1.5rem 0;">
        <p><strong>ID Transaksi:</strong> #<?php echo $transaction['id']; ?></p>
        <p><strong>Waktu Mulai:</strong> <?php echo $transaction['waktu_mulai']; ?></p>
        <p><strong>Waktu Selesai:</strong> <?php echo $transaction['waktu_selesai']; ?></p>
        <hr style="border: 1px solid #E5E7EB; margin: 1rem 0;">
        <h3 style="margin: 0; color: var(--primary-color);">Total: Rp <?php echo number_format($transaction['total_biaya'],0,',','.'); ?></h3>
    </div>

    <form method="POST" action="">
        <input type="hidden" name="transaction_id" value="<?php echo $transaction['id']; ?>">
        
        <div class="form-group">
            <label>Pilih Metode Pembayaran (Simulasi)</label>
            <select class="form-control" name="payment_method" required>
                <option value="qris">QRIS (GoPay, OVO, Dana)</option>
                <option value="transfer">Transfer Bank (Virtual Account)</option>
                <option value="saldo">Saldo Kampus</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-secondary mt-3">Bayar Sekarang</button>
    </form>
</div>

<?php require 'views/layouts/footer.php'; ?>

<?php require 'views/layouts/header_user.php'; ?>
<?php
require_once 'models/Transaction.php';
require_once 'models/User.php';

$trx = new Transaction();
$userObj = new User();

if (!isset($_GET['trx_id'])) {
    echo '<div class="alert alert-danger">Transaksi tidak ditemukan.</div>';
    require 'views/layouts/footer_user.php';
    exit;
}

$transaction = $trx->readById($_GET['trx_id']);
if (!$transaction || $transaction['user_id'] != $_SESSION['user_id'] || $transaction['status_pembayaran'] == 'Lunas') {
    echo '<div class="alert alert-danger">Transaksi tidak valid atau sudah dibayar.</div>';
    require 'views/layouts/footer_user.php';
    exit;
}

$saldo = $userObj->getSaldo($_SESSION['user_id']);
$totalBiaya = $transaction['total_biaya'];
$mulai = strtotime($transaction['waktu_mulai']);
$selesai = strtotime($transaction['waktu_selesai']);
$jam = ceil(($selesai - $mulai) / 3600);
if ($jam < 1) $jam = 1;

$payError = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['transaction_id'])) {
    $metode = $_POST['metode'] ?? '';
    if ($metode === 'saldo') {
        if ($saldo < $totalBiaya) {
            $payError = "Saldo tidak cukup (Rp " . number_format($saldo,0,',','.') . ").";
        } else {
            $userObj->deductSaldo($_SESSION['user_id'], $totalBiaya);
            $trx->updatePayment($_POST['transaction_id']);
            header('Location: index.php?page=history&msg=payment_success');
            exit;
        }
    } elseif ($metode === 'qris' || $metode === 'transfer') {
        $trx->updatePayment($_POST['transaction_id']);
        header('Location: index.php?page=history&msg=payment_success');
        exit;
    } else {
        $payError = "Pilih metode pembayaran.";
    }
}
?>

<div class="section-title">Pembayaran</div>
<div class="section-subtitle">Selesaikan pembayaran sewa Anda</div>

<?php if ($payError): ?>
    <div class="alert alert-danger"><?php echo $payError; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-title">Detail Transaksi</div>
    <div class="detail-row">
        <span class="label">Sepeda</span>
        <span class="value"><?php echo $transaction['merk']; ?></span>
    </div>
    <div class="detail-row">
        <span class="label">Durasi</span>
        <span class="value"><?php echo $jam; ?> Jam</span>
    </div>
    <div class="detail-row">
        <span class="label" style="font-weight:700;color:#1f2937;">Total Bayar</span>
        <span class="value" style="font-size:18px;color:#1e3a5f;">Rp <?php echo number_format($totalBiaya,0,',','.'); ?></span>
    </div>
</div>

<div class="card">
    <div class="card-title">Metode Pembayaran</div>
    <form method="POST">
        <input type="hidden" name="transaction_id" value="<?php echo $transaction['id']; ?>">
        <div class="form-group">
            <select name="metode" class="form-control" required>
                <option value="">-- Pilih Metode --</option>
                <option value="saldo">Saldo (Rp <?php echo number_format($saldo,0,',','.'); ?>)</option>
                <option value="qris">QRIS</option>
                <option value="transfer">Transfer Bank</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success btn-block">Bayar</button>
    </form>
</div>

<?php require 'views/layouts/footer_user.php'; ?>

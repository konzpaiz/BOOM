<?php require 'views/layouts/header_user.php'; ?>
<?php
require_once 'models/Bike.php';
require_once 'models/Transaction.php';

$trx = new Transaction();
$active = $trx->readActiveByUserId($_SESSION['user_id'])->fetch(PDO::FETCH_ASSOC);

if ($active) {
    echo '<div class="alert alert-danger">Anda masih memiliki penyewaan yang sedang berjalan.</div>';
    require 'views/layouts/footer_user.php';
    exit;
}

$bike_id = isset($_GET['bike_id']) ? (int)$_GET['bike_id'] : (isset($_POST['bike_id']) ? (int)$_POST['bike_id'] : 0);

$bike = new Bike();
if (!$bike_id || !$bike->readById($bike_id) || $bike->status !== 'Tersedia') {
    echo '<div class="alert alert-danger">Sepeda tidak ditemukan atau sudah tidak tersedia.</div>';
    require 'views/layouts/footer_user.php';
    exit;
}

$err = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bike_id'])) {
    $newTrx = new Transaction();
    $newTrx->user_id = $_SESSION['user_id'];
    $newTrx->sepeda_id = $bike_id;

    if ($newTrx->create()) {
        $newTrx->updatePayment($newTrx->id);
        $bike->updateStatus($bike_id, 'Disewa');
        header('Location: index.php?page=history&msg=payment_success');
        exit;
    } else {
        $err = "Gagal memproses pembayaran. Silakan coba lagi.";
    }
}
?>

<div class="section-title">Pembayaran</div>
<div class="section-subtitle">Selesaikan pembayaran untuk mulai menyewa</div>

<?php if ($err): ?>
    <div class="alert alert-danger"><?php echo $err; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-title">Detail Sepeda</div>
    <div class="detail-row">
        <span class="label">Sepeda</span>
        <span class="value"><?php echo htmlspecialchars($bike->merk); ?></span>
    </div>
    <div class="detail-row">
        <span class="label">Tarif</span>
        <span class="value">Rp <?php echo number_format($bike->tarif_per_jam, 0, ',', '.'); ?> / jam</span>
    </div>
</div>

<div class="card text-center">
    <div class="card-title">Scan QRIS untuk Membayar</div>
    <img src="assets/img/qris/qris.png" alt="QRIS" style="max-width:240px; width:100%; margin:12px auto; display:block;">
    <p class="text-muted text-small" style="margin-top:8px;">
        Pembayaran ini merupakan simulasi untuk keperluan tugas praktikum.
    </p>

    <form method="POST">
        <input type="hidden" name="bike_id" value="<?php echo $bike_id; ?>">
        <button type="submit" class="btn btn-success btn-block mt-2">Saya Sudah Membayar</button>
    </form>
</div>

<?php require 'views/layouts/footer_user.php'; ?>
<?php require 'views/layouts/header_user.php'; ?>
<?php
require_once 'models/Bike.php';
require_once 'models/Transaction.php';

$err = null;
$trx = new Transaction();
$activeRental = $trx->readActiveByUserId($_SESSION['user_id'])->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['kode_sepeda'])) {
    if ($activeRental) {
        $err = "Anda masih memiliki penyewaan aktif.";
    } else {
        $bike = new Bike();
        if ($bike->readByKode($_POST['kode_sepeda'])) {
            if ($bike->status !== 'Tersedia') {
                $err = "Sepeda sedang tidak tersedia.";
            } else {
                $newTrx = new Transaction();
                $newTrx->user_id = $_SESSION['user_id'];
                $newTrx->sepeda_id = $bike->id;
                if ($newTrx->create()) {
                    $bike->updateStatus($bike->id, 'Disewa');
                    header('Location: index.php?page=user_active');
                    exit;
                } else {
                    $err = "Gagal memulai penyewaan.";
                }
            }
        } else {
            $err = "Kode sepeda tidak ditemukan.";
        }
    }
}
?>

<div class="section-title">Scan QR Code</div>
<div class="section-subtitle">Arahkan kamera ke QR code pada sepeda</div>

<?php if ($err): ?>
    <div class="alert alert-danger"><?php echo $err; ?></div>
<?php endif; ?>

<div class="scanner-box">
    <div id="reader"></div>
</div>

<form method="POST" id="scan-form" style="display:none;">
    <input type="hidden" name="kode_sepeda" id="scanned-code">
</form>

<script>
    function onScanSuccess(decodedText) {
        document.getElementById('scanned-code').value = decodedText;
        html5QrcodeScanner.clear();
        document.getElementById('scan-form').submit();
    }
    function onScanFailure(error) {}
    var html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: {width: 250, height: 250} }, false);
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
</script>

<?php require 'views/layouts/footer_user.php'; ?>

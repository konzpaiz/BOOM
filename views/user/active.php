<?php require 'views/layouts/header_user.php'; ?>
<?php 
require_once 'models/Transaction.php';
require_once 'models/Bike.php';

$trx = new Transaction();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['transaction_id'])) {
    $transaction = $trx->readById($_POST['transaction_id']);
    if ($transaction && $transaction['user_id'] == $_SESSION['user_id'] && $transaction['status_sewa'] == 'Berjalan') {
        $mulai = strtotime($transaction['waktu_mulai']);
        $sekarang = time();
        $jam = ceil(($sekarang - $mulai) / 3600);
        if ($jam < 1) $jam = 1;
        $biaya = $jam * $transaction['tarif_per_jam'];
        
        if ($trx->endRental($_POST['transaction_id'], $biaya)) {
            // Automatically complete payment (Lunas)
            $trx->updatePayment($_POST['transaction_id']);
            
            $bike = new Bike();
            $bike->updateStatus($transaction['sepeda_id'], 'Tersedia');
            header('Location: index.php?page=user_dashboard&msg=returned');
            exit;
        }
    }
}

$active = $trx->readActiveByUserId($_SESSION['user_id'])->fetch(PDO::FETCH_ASSOC);
?>

<div class="section-title">Penyewaan Aktif</div>
<div class="section-subtitle">Status penyewaan Anda saat ini</div>

<?php if ($active): ?>
<div class="rental-card">
    <div class="card-title">Sedang Menyewa</div>
    <div class="detail-row">
        <span class="label">Sepeda</span>
        <span class="value"><?php echo $active['merk']; ?></span>
    </div>
    <div class="detail-row">
        <span class="label">Waktu Mulai</span>
        <span class="value"><?php echo date('d M Y, H:i', strtotime($active['waktu_mulai'])); ?></span>
    </div>
    <div class="detail-row">
        <span class="label">Durasi</span>
        <span class="value" id="duration">-</span>
    </div>
    <div class="detail-row">
        <span class="label">Estimasi Biaya</span>
        <span class="value" id="est-biaya">-</span>
    </div>
</div>

<form method="POST" onsubmit="return confirm('Yakin ingin mengakhiri penyewaan?');">
    <input type="hidden" name="transaction_id" value="<?php echo $active['id']; ?>">
    <button type="submit" class="btn btn-danger btn-block">Akhiri Penyewaan</button>
</form>

<script>
    var tarif = <?php echo $active['tarif_per_jam']; ?>;
    var mulai = new Date('<?php echo $active['waktu_mulai']; ?>').getTime();
    function update() {
        var now = Date.now();
        var diff = Math.floor((now - mulai) / 1000);
        var h = Math.floor(diff / 3600);
        var m = Math.floor((diff % 3600) / 60);
        var s = diff % 60;
        document.getElementById('duration').textContent = h + ' jam ' + m + ' mnt ' + s + ' dtk';
        var jam = Math.ceil((now - mulai) / 3600000);
        if (jam < 1) jam = 1;
        document.getElementById('est-biaya').textContent = 'Rp ' + (jam * tarif).toLocaleString('id-ID');
    }
    update();
    setInterval(update, 1000);
</script>

<?php else: ?>
<div class="card text-center" style="padding:32px 16px;">
    <div style="font-size:48px; margin-bottom:12px;">🚲</div>
    <div style="font-size:15px; font-weight:600; color:#1f2937; margin-bottom:4px;">Tidak Ada Penyewaan Aktif</div>
    <p class="text-muted text-small mb-2">Mulai sewa sepeda dengan scan QR atau pilih dari daftar</p>
    <a href="index.php?page=scan" class="btn btn-primary mt-2">Sewa Sepeda</a>
</div>
<?php endif; ?>

<?php require 'views/layouts/footer_user.php'; ?>

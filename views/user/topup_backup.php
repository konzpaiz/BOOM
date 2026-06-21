<?php require 'views/layouts/header_user.php'; ?>
<?php
require_once 'models/User.php';
$userObj = new User();
$saldo = $userObj->getSaldo($_SESSION['user_id']);

$msg = null;
$err = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nominal'])) {
    $nominal = (int)$_POST['nominal'];
    $metode = $_POST['metode'] ?? '';

    if ($nominal < 1000) {
        $err = "Minimal top up Rp 1.000.";
    } elseif (empty($metode)) {
        $err = "Pilih metode pembayaran.";
    } else {
        // Simulasi top up berhasil
        if ($userObj->updateSaldo($_SESSION['user_id'], $nominal)) {
            $saldo += $nominal;
            $msg = "Top up berhasil! Saldo Anda sekarang Rp " . number_format($saldo, 0, ',', '.');
        } else {
            $err = "Gagal melakukan top up.";
        }
    }
}
?>

<div class="section-title">Top Up Saldo</div>
<div class="section-subtitle">Isi saldo untuk pembayaran sewa</div>

<?php if ($msg): ?>
    <div class="alert alert-success"><?php echo $msg; ?></div>
<?php endif; ?>
<?php if ($err): ?>
    <div class="alert alert-danger"><?php echo $err; ?></div>
<?php endif; ?>

<div class="card">
    <h3>Saldo Saat Ini</h3>
    <div class="text-center">
        <div style="font-size:24px; font-weight:bold; color:#1a3a5c;">Rp <?php echo number_format($saldo,0,',','.'); ?></div>
    </div>
</div>

<div class="card">
    <h3>Pilih Nominal</h3>
    <form method="POST" id="topup-form">
        <div class="nominal-options">
            <button type="button" onclick="setNominal(10000)">Rp 10.000</button>
            <button type="button" onclick="setNominal(25000)">Rp 25.000</button>
            <button type="button" onclick="setNominal(50000)">Rp 50.000</button>
            <button type="button" onclick="setNominal(100000)">Rp 100.000</button>
        </div>

        <div class="form-group">
            <label>Atau masukkan nominal manual</label>
            <input type="number" name="nominal" id="nominal-input" class="form-control" placeholder="Contoh: 25000" min="1000" required>
        </div>

        <div class="form-group">
            <label>Metode Pembayaran</label>
            <select name="metode" class="form-control" required>
                <option value="">-- Pilih --</option>
                <option value="qris">QRIS</option>
                <option value="transfer">Transfer Bank</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Konfirmasi Top Up</button>
    </form>
</div>

<script>
function setNominal(val) {
    document.getElementById('nominal-input').value = val;
    var btns = document.querySelectorAll('.nominal-options button');
    for (var i = 0; i < btns.length; i++) {
        btns[i].className = '';
    }
    event.target.className = 'selected';
}
</script>

<?php require 'views/layouts/footer_user.php'; ?>

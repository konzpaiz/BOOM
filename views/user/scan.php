<?php require 'views/layouts/sidebar_user.php'; ?>
<?php
require_once 'controllers/RentalController.php';
$rental = new RentalController();
$error = $rental->startRental();

// If coming with a specific bike code (fallback from scanner)
$prefill_code = isset($_GET['code']) ? htmlspecialchars($_GET['code']) : '';
?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="text-center mb-4">
        <h2 style="margin:0;"><i class="fa-solid fa-qrcode" style="color: var(--primary-color);"></i> Scan QR Code Sepeda</h2>
        <p class="text-muted">Arahkan kamera ke QR code yang ada di badan sepeda listrik.</p>
    </div>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation"></i> <?php echo $error; ?></div>
    <?php endif; ?>

    <div id="reader" style="width: 100%; border-radius: 12px; overflow: hidden; margin-bottom: 1.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"></div>
    
    <div style="display: flex; align-items: center; text-align: center; margin: 1.5rem 0;">
        <hr style="flex-grow: 1; border: none; border-top: 1px solid #E5E7EB;">
        <span style="padding: 0 1rem; color: var(--text-muted); font-size: 0.875rem; text-transform: uppercase; font-weight: 600;">Atau Input Manual</span>
        <hr style="flex-grow: 1; border: none; border-top: 1px solid #E5E7EB;">
    </div>

    <form method="POST" action="" id="rentalForm">
        <div class="form-group">
            <label>Kode Sepeda</label>
            <div style="position: relative;">
                <i class="fa-solid fa-hashtag" style="position: absolute; left: 15px; top: 15px; color: #9CA3AF;"></i>
                <input type="text" id="kode_sepeda" class="form-control" style="padding-left: 40px;" value="<?php echo $prefill_code; ?>" placeholder="Contoh: EB-001" required>
            </div>
        </div>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['kode_sepeda'])) {
            require_once 'models/Bike.php';
            $b = new Bike();
            if ($b->readByKode($_POST['kode_sepeda'])) {
                $_POST['bike_id'] = $b->id;
                $error = $rental->startRental();
                if ($error) {
                    echo "<div class='alert alert-danger mt-3'><i class='fa-solid fa-circle-exclamation'></i> $error</div>";
                }
            } else {
                echo "<div class='alert alert-danger mt-3'><i class='fa-solid fa-circle-exclamation'></i> Kode sepeda tidak ditemukan.</div>";
            }
        }
        ?>
        <input type="hidden" name="kode_sepeda_hidden" id="kode_sepeda_hidden">
        <button type="submit" name="kode_sepeda" class="btn btn-primary mt-3" style="width: 100%;" onclick="document.getElementById('kode_sepeda_hidden').value = document.getElementById('kode_sepeda').value; this.value = document.getElementById('kode_sepeda').value;"><i class="fa-solid fa-unlock-keyhole"></i> Buka Kunci & Mulai Sewa</button>
    </form>
</div>

<script>
    function onScanSuccess(decodedText, decodedResult) {
        document.getElementById('kode_sepeda').value = decodedText;
        html5QrcodeScanner.clear();
    }

    function onScanFailure(error) {
        // handle scan failure
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", { fps: 10, qrbox: {width: 250, height: 250} }, false);
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
</script>

<?php require 'views/layouts/footer.php'; ?>

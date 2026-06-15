<?php require 'views/layouts/header.php'; ?>
<?php
require_once 'controllers/RentalController.php';
$rental = new RentalController();
$error = $rental->startRental();

// If coming with a specific bike code (fallback from scanner)
$prefill_code = isset($_GET['code']) ? htmlspecialchars($_GET['code']) : '';
?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h2 class="text-center">Scan QR Code Sepeda</h2>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <div id="reader" style="width: 100%; margin-bottom: 20px;"></div>
    
    <div class="text-center mb-3">
        <p>Atau masukkan kode sepeda secara manual:</p>
    </div>

    <form method="POST" action="" id="rentalForm">
        <div class="form-group">
            <label>Kode Sepeda</label>
            <input type="text" id="kode_sepeda" class="form-control" value="<?php echo $prefill_code; ?>" placeholder="Contoh: EB-001" required>
        </div>
        
        <!-- We need to lookup the bike ID by its Code. Let's do a fetch to API or just submit the Code and let controller handle it. -->
        <!-- Wait, RentalController expects bike_id. I will need to change that to handle code, or lookup here. Let's lookup via PHP in the view for simplicity or modify Controller. -->
        <?php
        $bike_id = null;
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['kode_sepeda'])) {
            $b = new Bike();
            if ($b->readByKode($_POST['kode_sepeda'])) {
                $_POST['bike_id'] = $b->id;
                $error = $rental->startRental();
                if ($error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Kode sepeda tidak ditemukan.</div>";
            }
        }
        ?>
        <input type="hidden" name="kode_sepeda_hidden" id="kode_sepeda_hidden">
        <button type="submit" name="kode_sepeda" class="btn btn-primary" onclick="document.getElementById('kode_sepeda_hidden').value = document.getElementById('kode_sepeda').value; this.value = document.getElementById('kode_sepeda').value;">Mulai Sewa</button>
    </form>
</div>

<script>
    function onScanSuccess(decodedText, decodedResult) {
        // Handle the scanned code.
        document.getElementById('kode_sepeda').value = decodedText;
        // Optionally submit the form automatically
        // document.getElementById('rentalForm').submit();
        html5QrcodeScanner.clear();
    }

    function onScanFailure(error) {
        // handle scan failure, usually better to ignore and keep scanning.
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", { fps: 10, qrbox: {width: 250, height: 250} }, false);
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
</script>

<?php require 'views/layouts/footer.php'; ?>

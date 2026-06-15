<?php require 'views/layouts/header.php'; ?>
<?php
require_once 'models/User.php';
require_once 'models/Bike.php';
require_once 'models/Transaction.php';

$userModel = new User();
$bikeModel = new Bike();
$trxModel = new Transaction();

$total_users = $userModel->readAll()->rowCount();
$total_bikes = $bikeModel->readAll()->rowCount();
$available_bikes = $bikeModel->readAvailable()->rowCount();
$total_trx = $trxModel->readAll()->rowCount();
?>

<div>
    <h2>Dashboard Admin</h2>
    <p>Ringkasan sistem penyewaan sepeda kampus.</p>

    <div class="grid mt-4">
        <div class="card">
            <h3 class="card-title text-primary">Total Pengguna</h3>
            <h1 style="margin:0; font-size: 2.5rem;"><?php echo $total_users; ?></h1>
        </div>
        <div class="card">
            <h3 class="card-title" style="color: var(--secondary-color);">Sepeda Tersedia</h3>
            <h1 style="margin:0; font-size: 2.5rem;"><?php echo $available_bikes; ?> / <?php echo $total_bikes; ?></h1>
        </div>
        <div class="card">
            <h3 class="card-title" style="color: var(--danger);">Total Transaksi</h3>
            <h1 style="margin:0; font-size: 2.5rem;"><?php echo $total_trx; ?></h1>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>

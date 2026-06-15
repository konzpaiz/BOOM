<?php require 'views/layouts/sidebar_admin.php'; ?>
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

// Calculate total revenue
$db = new Database();
$conn = $db->getConnection();
$query = "SELECT SUM(total_biaya) as revenue FROM transaksi WHERE status_pembayaran = 'Lunas'";
$stmt = $conn->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$revenue = $row['revenue'] ? $row['revenue'] : 0;
?>

<div>
    <h2 style="margin-bottom: 1.5rem;">Ringkasan Sistem</h2>

    <div class="grid">
        <div class="card stat-card">
            <div class="stat-icon stat-primary"><i class="fa-solid fa-users"></i></div>
            <div class="stat-details">
                <h3><?php echo $total_users; ?></h3>
                <p>Total Pengguna</p>
            </div>
        </div>
        <div class="card stat-card">
            <div class="stat-icon stat-secondary"><i class="fa-solid fa-bicycle"></i></div>
            <div class="stat-details">
                <h3><?php echo $available_bikes; ?> <span style="font-size: 1rem; color: #9CA3AF;">/ <?php echo $total_bikes; ?></span></h3>
                <p>Sepeda Tersedia</p>
            </div>
        </div>
        <div class="card stat-card">
            <div class="stat-icon stat-danger"><i class="fa-solid fa-money-bill-transfer"></i></div>
            <div class="stat-details">
                <h3><?php echo $total_trx; ?></h3>
                <p>Total Transaksi</p>
            </div>
        </div>
        <div class="card stat-card">
            <div class="stat-icon" style="background-color: #FEF3C7; color: #D97706;"><i class="fa-solid fa-wallet"></i></div>
            <div class="stat-details">
                <h3 style="font-size: 1.5rem;">Rp <?php echo number_format($revenue,0,',','.'); ?></h3>
                <p>Total Pendapatan</p>
            </div>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>

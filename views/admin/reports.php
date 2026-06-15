<?php require 'views/layouts/sidebar_admin.php'; ?>
<?php
require_once 'config/database.php';

$db = new Database();
$conn = $db->getConnection();

// Get last 7 days transactions count
$query_trx = "SELECT DATE(waktu_mulai) as date, COUNT(*) as count FROM transaksi WHERE waktu_mulai >= DATE(NOW()) - INTERVAL 7 DAY GROUP BY DATE(waktu_mulai) ORDER BY date ASC";
$stmt_trx = $conn->prepare($query_trx);
$stmt_trx->execute();

$dates = [];
$counts = [];
while($row = $stmt_trx->fetch(PDO::FETCH_ASSOC)) {
    $dates[] = $row['date'];
    $counts[] = $row['count'];
}

// Get revenue per month (current year)
$query_rev = "SELECT MONTH(waktu_mulai) as month, SUM(total_biaya) as revenue FROM transaksi WHERE YEAR(waktu_mulai) = YEAR(NOW()) AND status_pembayaran = 'Lunas' GROUP BY MONTH(waktu_mulai) ORDER BY month ASC";
$stmt_rev = $conn->prepare($query_rev);
$stmt_rev->execute();

$months_data = array_fill(1, 12, 0); // initialize 12 months
while($row = $stmt_rev->fetch(PDO::FETCH_ASSOC)) {
    $months_data[$row['month']] = $row['revenue'];
}
$month_names = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
$revenues = array_values($months_data);

?>

<div>
    <h2 style="margin-bottom: 1.5rem;">Laporan & Statistik</h2>
    
    <div class="grid">
        <div class="card" style="grid-column: span 1;">
            <h3>Tren Transaksi (7 Hari Terakhir)</h3>
            <canvas id="trxChart"></canvas>
        </div>
        <div class="card" style="grid-column: span 1;">
            <h3>Pendapatan Tahun Ini</h3>
            <canvas id="revChart"></canvas>
        </div>
    </div>
</div>

<script>
    // Chart Transaksi
    const ctxTrx = document.getElementById('trxChart').getContext('2d');
    new Chart(ctxTrx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dates); ?>,
            datasets: [{
                label: 'Jumlah Penyewaan',
                data: <?php echo json_encode($counts); ?>,
                borderColor: '#1E3A8A',
                backgroundColor: 'rgba(30, 58, 138, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // Chart Pendapatan
    const ctxRev = document.getElementById('revChart').getContext('2d');
    new Chart(ctxRev, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($month_names); ?>,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: <?php echo json_encode($revenues); ?>,
                backgroundColor: '#10B981',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>

<?php require 'views/layouts/footer.php'; ?>

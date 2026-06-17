<?php
require_once 'config/database.php';

$db = new Database();
$conn = $db->getConnection();

// Last 7 days transaction counts
$dailyLabels = [];
$dailyData = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $dailyLabels[] = date('d M', strtotime($date));
    
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM transaksi WHERE DATE(waktu_mulai) = :d");
    $stmt->bindParam(':d', $date);
    $stmt->execute();
    $dailyData[] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

// Monthly revenue for current year
$monthlyLabels = [];
$monthlyData = [];
$months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
$currentYear = date('Y');

for ($m = 1; $m <= 12; $m++) {
    $monthlyLabels[] = $months[$m - 1];
    
    $stmt = $conn->prepare("SELECT COALESCE(SUM(total_biaya), 0) as total FROM transaksi WHERE MONTH(waktu_mulai) = :m AND YEAR(waktu_mulai) = :y AND status_pembayaran = 'Lunas'");
    $stmt->bindParam(':m', $m);
    $stmt->bindParam(':y', $currentYear);
    $stmt->execute();
    $monthlyData[] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

// Summary stats for report
$stmtTotalTrx = $conn->prepare("SELECT COUNT(*) as total FROM transaksi");
$stmtTotalTrx->execute();
$totalTrx = $stmtTotalTrx->fetch(PDO::FETCH_ASSOC)['total'];

$stmtTotalRev = $conn->prepare("SELECT COALESCE(SUM(total_biaya), 0) as total FROM transaksi WHERE status_pembayaran = 'Lunas'");
$stmtTotalRev->execute();
$totalRevenue = $stmtTotalRev->fetch(PDO::FETCH_ASSOC)['total'];

$stmtActiveTrx = $conn->prepare("SELECT COUNT(*) as total FROM transaksi WHERE status_sewa = 'Berjalan'");
$stmtActiveTrx->execute();
$activeTrx = $stmtActiveTrx->fetch(PDO::FETCH_ASSOC)['total'];

require 'views/layouts/header_admin.php';
?>

<!-- Summary Cards -->
<div class="grid-3 mb-3">
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(30, 58, 138, 0.1); color: #1E3A8A;">
            <i class="fas fa-chart-bar"></i>
        </div>
        <div class="stat-details">
            <h3><?= number_format($totalTrx) ?></h3>
            <p>Total Transaksi</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="stat-details">
            <h3>Rp <?= number_format($totalRevenue, 0, ',', '.') ?></h3>
            <p>Total Pendapatan</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #F59E0B;">
            <i class="fas fa-spinner"></i>
        </div>
        <div class="stat-details">
            <h3><?= number_format($activeTrx) ?></h3>
            <p>Sewa Aktif</p>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="grid-2">
    <!-- Daily Transactions Chart -->
    <div class="card">
        <h3 style="margin:0 0 16px 0; font-size: 1.05rem; color: #1F2937;">
            <i class="fas fa-chart-line" style="margin-right:8px; color:#1E3A8A;"></i>
            Transaksi 7 Hari Terakhir
        </h3>
        <div style="position:relative; width:100%; min-height:280px;">
            <canvas id="dailyChart"></canvas>
        </div>
    </div>

    <!-- Monthly Revenue Chart -->
    <div class="card">
        <h3 style="margin:0 0 16px 0; font-size: 1.05rem; color: #1F2937;">
            <i class="fas fa-chart-bar" style="margin-right:8px; color:#10B981;"></i>
            Pendapatan Bulanan <?= $currentYear ?>
        </h3>
        <div style="position:relative; width:100%; min-height:280px;">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>
</div>

<script>
// Daily Transactions Line Chart
const dailyCtx = document.getElementById('dailyChart').getContext('2d');
new Chart(dailyCtx, {
    type: 'line',
    data: {
        labels: <?= json_encode($dailyLabels) ?>,
        datasets: [{
            label: 'Jumlah Transaksi',
            data: <?= json_encode($dailyData) ?>,
            borderColor: '#1E3A8A',
            backgroundColor: 'rgba(30, 58, 138, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#1E3A8A',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 7
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                    font: { family: 'Poppins', size: 11 },
                    color: '#6B7280'
                },
                grid: {
                    color: 'rgba(0,0,0,0.05)'
                }
            },
            x: {
                ticks: {
                    font: { family: 'Poppins', size: 11 },
                    color: '#6B7280'
                },
                grid: {
                    display: false
                }
            }
        }
    }
});

// Monthly Revenue Bar Chart
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
new Chart(monthlyCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($monthlyLabels) ?>,
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: <?= json_encode($monthlyData) ?>,
            backgroundColor: 'rgba(16, 185, 129, 0.7)',
            borderColor: '#10B981',
            borderWidth: 2,
            borderRadius: 6,
            borderSkipped: false
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    font: { family: 'Poppins', size: 11 },
                    color: '#6B7280',
                    callback: function(value) {
                        if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                        if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + 'rb';
                        return 'Rp ' + value;
                    }
                },
                grid: {
                    color: 'rgba(0,0,0,0.05)'
                }
            },
            x: {
                ticks: {
                    font: { family: 'Poppins', size: 11 },
                    color: '#6B7280'
                },
                grid: {
                    display: false
                }
            }
        }
    }
});
</script>

<?php require 'views/layouts/footer_admin.php'; ?>

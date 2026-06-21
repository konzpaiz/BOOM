<?php
require_once 'config/database.php';

$db = new Database();
$conn = $db->getConnection();

// Fetch all transactions with user information
$query = "SELECT t.*, u.name as user_name, u.nim, s.merk, s.kode_sepeda 
          FROM transaksi t 
          LEFT JOIN users u ON t.user_id = u.id 
          LEFT JOIN sepeda s ON t.sepeda_id = s.id 
          ORDER BY t.id DESC";
$stmt = $conn->prepare($query);
$stmt->execute();

require 'views/layouts/header_admin.php';
?>

<div class="section-title">Riwayat Penyewaan</div>
<div class="section-subtitle">Daftar persewaan dari semua pengguna</div>

<!-- Search Bar -->
<div class="search-bar" style="margin-bottom: 16px;">
    <input type="text" id="searchRentals" placeholder="Cari Nama, NIM, atau Sepeda..." onkeyup="filterRentals()" style="width: 100%; padding: 11px 14px; border: 1px solid #e5e7eb; border-radius: 10px; font-size: 13px;">
</div>

<!-- List of Rentals (Card-based for Mobile View) -->
<div id="rentalsList">
    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
    <?php
        // Calculate rental duration
        $durasi = '-';
        if ($row['waktu_mulai'] && $row['waktu_selesai']) {
            $diff = strtotime($row['waktu_selesai']) - strtotime($row['waktu_mulai']);
            $h = floor($diff / 3600);
            $m = floor(($diff % 3600) / 60);
            $durasi = ($h > 0 ? $h . ' jam ' : '') . $m . ' menit';
        } elseif ($row['status_sewa'] === 'Berjalan') {
            $diff = time() - strtotime($row['waktu_mulai']);
            $h = floor($diff / 3600);
            $m = floor(($diff % 3600) / 60);
            $durasi = ($h > 0 ? $h . ' jam ' : '') . $m . ' menit (Berlangsung)';
        }
    ?>
    <div class="card rental-item" style="margin-bottom: 12px; padding: 16px;">
        <div class="flex-between" style="margin-bottom: 8px;">
            <div>
                <div style="font-weight: 700; font-size: 14px; color:#1e3a5f;"><?= htmlspecialchars($row['user_name'] ?? '-') ?></div>
                <div class="text-muted text-small">NIM: <?= htmlspecialchars($row['nim'] ?? '') ?></div>
            </div>
            <div>
                <?php if ($row['status_sewa'] === 'Berjalan'): ?>
                    <span class="badge badge-yellow">Sedang Disewa</span>
                <?php else: ?>
                    <span class="badge badge-green">Selesai</span>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="divider" style="margin: 8px 0; border-top: 1px solid #f3f4f6;"></div>
        
        <div class="detail-row">
            <span class="label" style="font-size: 12px; color: #9ca3af;">Sepeda</span>
            <span class="value" style="font-size: 12px; font-weight: 600; color: #1f2937;">
                <?= htmlspecialchars($row['merk'] ?? '-') ?>
            </span>
        </div>
        <div class="detail-row">
            <span class="label" style="font-size: 12px; color: #9ca3af;">Tanggal Sewa</span>
            <span class="value" style="font-size: 12px; font-weight: 600; color: #1f2937;">
                <?= date('d M Y, H:i', strtotime($row['waktu_mulai'])) ?>
            </span>
        </div>
        <div class="detail-row">
            <span class="label" style="font-size: 12px; color: #9ca3af;">Durasi</span>
            <span class="value" style="font-size: 12px; font-weight: 600; color: #1f2937;"><?= $durasi ?></span>
        </div>
        <?php if ($row['total_biaya'] !== null): ?>
        <div class="detail-row">
            <span class="label" style="font-size: 12px; color: #9ca3af;">Total Biaya</span>
            <span class="value" style="font-size: 12px; font-weight: 700; color: #10b981;">Rp <?= number_format($row['total_biaya'], 0, ',', '.') ?></span>
        </div>
        <?php endif; ?>
    </div>
    <?php endwhile; ?>
    
    <?php if ($stmt->rowCount() === 0): ?>
        <div class="card text-center" style="padding:32px;">
            <div style="font-size:48px;margin-bottom:12px;">📋</div>
            <p class="text-muted">Belum ada riwayat transaksi penyewaan.</p>
        </div>
    <?php endif; ?>
</div>

<script>
function filterRentals() {
    const query = document.getElementById('searchRentals').value.toLowerCase();
    const items = document.querySelectorAll('.rental-item');
    items.forEach(function(item) {
        const text = item.textContent.toLowerCase();
        item.style.display = text.indexOf(query) !== -1 ? '' : 'none';
    });
}
</script>

<?php require 'views/layouts/footer_admin.php'; ?>

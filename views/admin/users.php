<?php
require_once 'config/database.php';

$db = new Database();
$conn = $db->getConnection();

// Fetch all transactions with user information to represent tenant history
$query = "SELECT t.*, u.name as user_name, u.nim, u.email, u.phone, s.merk, s.kode_sepeda 
          FROM transaksi t 
          LEFT JOIN users u ON t.user_id = u.id 
          LEFT JOIN sepeda s ON t.sepeda_id = s.id 
          ORDER BY t.id DESC";
$stmt = $conn->prepare($query);
$stmt->execute();

require 'views/layouts/header_admin.php';
?>

<div class="admin-topbar">
    <h1>Riwayat Pengguna</h1>
    <div class="topbar-info">Seluruh data riwayat aktivitas penyewa sepeda</div>
</div>

<div class="admin-content">
    <div class="admin-card">
        <div class="admin-card-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <h3><i class="fa fa-users" style="margin-right:8px; color:#1e3a5f;"></i>Daftar Aktivitas Penyewa</h3>
            <!-- Search bar -->
            <div style="position:relative; min-width:250px;">
                <input type="text" id="searchUserRental" class="form-control" placeholder="Cari Nama / NIM / Sepeda..." onkeyup="filterUserRentals()" style="padding-right:30px;">
            </div>
        </div>
        
        <div style="overflow-x:auto;">
            <table id="userRentalsTable">
                <thead>
                    <tr>
                        <th>Nama Pengguna</th>
                        <th>NIM</th>
                        <th>Tanggal Penyewaan</th>
                        <th>Sepeda</th>
                        <th>Durasi Penyewaan</th>
                        <th>Status Penyewaan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <?php
                        // Calculate duration
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
                            $durasi = ($h > 0 ? $h . ' jam ' : '') . $m . ' menit (berjalan)';
                        }
                    ?>
                    <tr>
                        <td>
                            <div style="font-weight:600; color:#1e3a5f;"><?= htmlspecialchars($row['user_name'] ?? '-') ?></div>
                            <div class="text-muted text-small"><?= htmlspecialchars($row['email'] ?? '') ?></div>
                        </td>
                        <td><strong><?= htmlspecialchars($row['nim'] ?? '-') ?></strong></td>
                        <td><?= date('d M Y, H:i', strtotime($row['waktu_mulai'])) ?></td>
                        <td><?= htmlspecialchars($row['merk'] ?? '-') ?></td>
                        <td><?= $durasi ?></td>
                        <td>
                            <?php if ($row['status_sewa'] === 'Berjalan'): ?>
                                <span class="badge badge-yellow">Masih Berjalan</span>
                            <?php else: ?>
                                <span class="badge badge-green">Selesai</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php if ($stmt->rowCount() === 0): ?>
                        <tr><td colspan="6" class="text-center text-muted" style="padding:20px;">Belum ada riwayat penyewaan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function filterUserRentals() {
    const query = document.getElementById('searchUserRental').value.toLowerCase();
    const rows = document.querySelectorAll('#userRentalsTable tbody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
    });
}
</script>

<?php require 'views/layouts/footer_admin.php'; ?>

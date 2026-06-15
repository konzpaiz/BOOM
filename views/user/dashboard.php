<?php require 'views/layouts/header.php'; ?>
<?php 
require_once 'models/Transaction.php';
$trx = new Transaction();
$active = $trx->readActiveByUserId($_SESSION['user_id'])->fetch(PDO::FETCH_ASSOC);
?>
<div>
    <h2>Selamat Datang, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h2>
    <p>Temukan sepeda listrik di sekitarmu dan mulai perjalananmu di area kampus.</p>
    
    <?php if ($active): ?>
        <div class="card mb-3" style="border-left: 4px solid var(--secondary-color);">
            <h3 class="card-title text-success">Status: Sedang Menyewa</h3>
            <p><strong>Sepeda:</strong> <?php echo $active['merk'] . ' (' . $active['kode_sepeda'] . ')'; ?></p>
            <p><strong>Mulai:</strong> <?php echo $active['waktu_mulai']; ?></p>
            <p><strong>Tarif:</strong> Rp <?php echo number_format($active['tarif_per_jam'],0,',','.'); ?> / jam</p>
            <a href="index.php?page=history" class="btn btn-secondary mt-3">Lihat Detail & Akhiri Sewa</a>
        </div>
    <?php else: ?>
        <div class="card mb-3 text-center">
            <h3>Siap untuk bersepeda?</h3>
            <a href="index.php?page=scan" class="btn btn-primary mt-3" style="max-width: 200px;">📷 Scan QR Code</a>
        </div>
    <?php endif; ?>

    <h3>Sepeda Tersedia Saat Ini</h3>
    <div id="bike-list" class="grid mt-3">
        <p>Memuat data sepeda...</p>
    </div>
</div>

<script>
// AJAX function to fetch available bikes
document.addEventListener("DOMContentLoaded", function() {
    fetch('api/get_bikes.php')
        .then(response => response.json())
        .then(data => {
            const listContainer = document.getElementById('bike-list');
            listContainer.innerHTML = '';
            
            if(data.records && data.records.length > 0) {
                data.records.forEach(bike => {
                    const card = document.createElement('div');
                    card.className = 'card';
                    card.innerHTML = `
                        <h4 class="card-title">${bike.merk}</h4>
                        <p class="text-muted">Kode: ${bike.kode_sepeda}</p>
                        <p><strong>Tarif:</strong> Rp ${parseInt(bike.tarif_per_jam).toLocaleString('id-ID')} / jam</p>
                        <span class="badge badge-success">${bike.status}</span>
                    `;
                    listContainer.appendChild(card);
                });
            } else {
                listContainer.innerHTML = `<p>${data.message || 'Belum ada sepeda tersedia.'}</p>`;
            }
        })
        .catch(error => {
            document.getElementById('bike-list').innerHTML = '<p class="text-danger">Gagal memuat data.</p>';
            console.error('Error fetching bikes:', error);
        });
});
</script>

<?php require 'views/layouts/footer.php'; ?>

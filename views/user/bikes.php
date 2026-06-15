<?php require 'views/layouts/sidebar_user.php'; ?>

<div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="margin:0;">Daftar Sepeda Tersedia</h2>
        <a href="index.php?page=scan" class="btn btn-secondary"><i class="fa-solid fa-qrcode"></i> Scan QR</a>
    </div>

    <div id="bike-list" class="grid">
        <div class="text-center text-muted" style="grid-column: 1 / -1; padding: 2rem;">Memuat data sepeda...</div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    fetch('api/get_bikes.php')
        .then(response => response.json())
        .then(data => {
            const listContainer = document.getElementById('bike-list');
            listContainer.innerHTML = '';
            
            if(data.records && data.records.length > 0) {
                data.records.forEach(bike => {
                    const card = document.createElement('div');
                    card.className = 'card bike-card';
                    // We handle default image or actual image from DB
                    let imgSrc = bike.image ? 'assets/img/bikes/' + bike.image : 'https://placehold.co/400x300?text=Sepeda+E-Bike';
                    // For dummy data, if 'default_bike.png' doesn't exist, fallback to placeholder
                    if (bike.image === 'default_bike.png') imgSrc = 'https://placehold.co/400x300?text=E-Bike';
                    
                    card.innerHTML = `
                        <img src="${imgSrc}" class="bike-img" alt="${bike.merk}">
                        <div class="bike-info">
                            <h3 style="margin-top:0; margin-bottom: 0.5rem;">${bike.merk}</h3>
                            <p class="text-muted mb-2"><i class="fa-solid fa-hashtag"></i> ${bike.kode_sepeda}</p>
                            <div style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                <span style="font-size: 1.25rem; font-weight: 700; color: var(--primary-color);">Rp ${parseInt(bike.tarif_per_jam).toLocaleString('id-ID')}<small style="font-size: 0.875rem; font-weight: 400; color: var(--text-muted);">/jam</small></span>
                                <span class="badge badge-success">${bike.status}</span>
                            </div>
                            <a href="index.php?page=scan&code=${bike.kode_sepeda}" class="btn btn-primary" style="width: 100%; display: block; text-align: center;"><i class="fa-solid fa-bolt"></i> Sewa Sekarang</a>
                        </div>
                    `;
                    listContainer.appendChild(card);
                });
            } else {
                listContainer.innerHTML = `<div class="card text-center text-muted" style="grid-column: 1 / -1; padding: 3rem;">${data.message || 'Belum ada sepeda tersedia.'}</div>`;
            }
        })
        .catch(error => {
            document.getElementById('bike-list').innerHTML = '<div class="alert alert-danger" style="grid-column: 1 / -1;">Gagal memuat data.</div>';
            console.error('Error fetching bikes:', error);
        });
});
</script>

<?php require 'views/layouts/footer.php'; ?>

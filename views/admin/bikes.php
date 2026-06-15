<?php require 'views/layouts/sidebar_admin.php'; ?>
<?php
require_once 'models/Bike.php';
$bikeModel = new Bike();

// Handle Actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        $bikeModel->kode_sepeda = $_POST['kode_sepeda'];
        $bikeModel->merk = $_POST['merk'];
        $bikeModel->tarif_per_jam = $_POST['tarif_per_jam'];
        $bikeModel->status = $_POST['status'];
        
        // Handle Image Upload
        $imageName = 'default_bike.png';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imageName = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], 'assets/img/bikes/' . $imageName);
        }
        $bikeModel->image = $imageName;

        if ($bikeModel->create()) {
            $msg = "Sepeda berhasil ditambahkan.";
        } else {
            $err = "Gagal menambahkan sepeda.";
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $bikeModel->id = $_POST['id'];
        if ($bikeModel->delete()) {
            $msg = "Sepeda berhasil dihapus.";
        } else {
            $err = "Gagal menghapus sepeda.";
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'update_status') {
        if ($bikeModel->updateStatus($_POST['id'], $_POST['status'])) {
            $msg = "Status sepeda diperbarui.";
        } else {
            $err = "Gagal memperbarui status.";
        }
    }
}

$bikes = $bikeModel->readAll();
?>

<div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="margin:0;">Data Sepeda</h2>
        <button class="btn btn-primary" onclick="document.getElementById('addBikeModal').style.display='block'"><i class="fa-solid fa-plus"></i> Tambah Sepeda</button>
    </div>
    
    <?php if (isset($msg)): ?><div class="alert alert-success"><i class="fa-solid fa-check"></i> <?php echo $msg; ?></div><?php endif; ?>
    <?php if (isset($err)): ?><div class="alert alert-danger"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo $err; ?></div><?php endif; ?>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Kode</th>
                    <th>Merk</th>
                    <th>Tarif/Jam</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $bikes->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td>
                        <img src="assets/img/bikes/<?php echo $row['image'] ? $row['image'] : 'default_bike.png'; ?>" alt="Bike" style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                    </td>
                    <td><?php echo $row['kode_sepeda']; ?></td>
                    <td><?php echo $row['merk']; ?></td>
                    <td>Rp <?php echo number_format($row['tarif_per_jam'],0,',','.'); ?></td>
                    <td>
                        <form method="POST" action="" style="display:flex; align-items:center;">
                            <input type="hidden" name="action" value="update_status">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <select name="status" class="form-control" style="width: auto; padding: 0.25rem; font-size: 0.875rem;" onchange="this.form.submit()">
                                <option value="Tersedia" <?php echo $row['status']=='Tersedia'?'selected':''; ?>>Tersedia</option>
                                <option value="Disewa" <?php echo $row['status']=='Disewa'?'selected':''; ?>>Disewa</option>
                                <option value="Rusak" <?php echo $row['status']=='Rusak'?'selected':''; ?>>Rusak</option>
                            </select>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="" onsubmit="return confirm('Hapus sepeda ini?');" style="display:inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Simple Modal -->
<div id="addBikeModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
    <div class="card" style="width: 100%; max-width: 500px; margin: 10vh auto; position: relative;">
        <h3 style="margin-top:0;">Tambah Sepeda Baru</h3>
        <button onclick="document.getElementById('addBikeModal').style.display='none'" style="position:absolute; right:1.5rem; top:1.5rem; background:none; border:none; font-size:1.5rem; cursor:pointer;">&times;</button>
        
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add">
            <div class="form-group mt-3">
                <label>Kode Sepeda (Misal: EB-004)</label>
                <input type="text" name="kode_sepeda" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Merk</label>
                <input type="text" name="merk" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Tarif per Jam (Rp)</label>
                <input type="number" name="tarif_per_jam" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Foto Sepeda</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="Tersedia">Tersedia</option>
                    <option value="Rusak">Rusak</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mt-3" style="width:100%;">Simpan</button>
        </form>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>

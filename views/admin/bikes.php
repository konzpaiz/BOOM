<?php require 'views/layouts/header.php'; ?>
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
    <h2>Kelola Sepeda</h2>
    
    <?php if (isset($msg)): ?><div class="alert alert-success"><?php echo $msg; ?></div><?php endif; ?>
    <?php if (isset($err)): ?><div class="alert alert-danger"><?php echo $err; ?></div><?php endif; ?>

    <div class="card mb-4" style="max-width: 600px;">
        <h3>Tambah Sepeda Baru</h3>
        <form method="POST" action="">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
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
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="Tersedia">Tersedia</option>
                    <option value="Rusak">Rusak</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Tambah Sepeda</button>
        </form>
    </div>

    <h3>Daftar Sepeda</h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
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
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['kode_sepeda']; ?></td>
                    <td><?php echo $row['merk']; ?></td>
                    <td>Rp <?php echo number_format($row['tarif_per_jam'],0,',','.'); ?></td>
                    <td>
                        <form method="POST" action="" style="display:flex; gap:0.5rem; align-items:center;">
                            <input type="hidden" name="action" value="update_status">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <select name="status" class="form-control" style="width: auto; padding: 0.25rem;" onchange="this.form.submit()">
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
                            <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>

<?php
require_once 'models/Bike.php';

$bike = new Bike();
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $bike->kode_sepeda = $_POST['kode_sepeda'];
        $bike->merk = $_POST['merk'];
        $bike->tarif_per_jam = $_POST['tarif_per_jam'];
        $bike->status = $_POST['status'] ?? 'Tersedia';
        $bike->image = 'default_bike.png';
        if ($bike->create()) {
            $message = 'Sepeda berhasil ditambahkan.';
            $messageType = 'success';
        } else {
            $message = 'Gagal menambahkan sepeda.';
            $messageType = 'danger';
        }
    }
    if ($_POST['action'] === 'delete') {
        $bike->id = $_POST['bike_id'];
        if ($bike->delete()) {
            $message = 'Sepeda berhasil dihapus.';
            $messageType = 'success';
        } else {
            $message = 'Gagal menghapus sepeda.';
            $messageType = 'danger';
        }
    }
    if ($_POST['action'] === 'update_status') {
        if ($bike->updateStatus($_POST['bike_id'], $_POST['status'])) {
            $message = 'Status berhasil diperbarui.';
            $messageType = 'success';
        } else {
            $message = 'Gagal memperbarui status.';
            $messageType = 'danger';
        }
    }
}

$bike = new Bike();
$stmt = $bike->readAll();

require 'views/layouts/header_admin.php';
?>

<h3 class="mb-2">Data Sepeda</h3>

<?php if ($message): ?>
    <div class="alert alert-<?= $messageType ?>"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<div class="card mb-2">
    <h3>Tambah Sepeda Baru</h3>
    <form method="POST">
        <input type="hidden" name="action" value="add">
        <div class="form-group">
            <label>Kode Sepeda</label>
            <input type="text" name="kode_sepeda" class="form-control" placeholder="Contoh: EB-001" required>
        </div>
        <div class="form-group">
            <label>Merk</label>
            <input type="text" name="merk" class="form-control" placeholder="Contoh: Xiaomi Himo" required>
        </div>
        <div class="form-group">
            <label>Tarif per Jam (Rp)</label>
            <input type="number" name="tarif_per_jam" class="form-control" placeholder="5000" min="0" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>

<div class="card" style="padding:0; overflow-x:auto;">
    <table>
        <thead>
            <tr>
                <th>Merk</th>
                <th>Tarif/Jam</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= htmlspecialchars($row['kode_sepeda']) ?></td>
                <td><?= htmlspecialchars($row['merk']) ?></td>
                <td>Rp <?= number_format($row['tarif_per_jam'], 0, ',', '.') ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="update_status">
                        <input type="hidden" name="bike_id" value="<?= $row['id'] ?>">
                        <select name="status" class="form-control" style="width:auto; display:inline; padding:3px;" onchange="this.form.submit()">
                            <option value="Tersedia" <?= $row['status'] === 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                            <option value="Disewa" <?= $row['status'] === 'Disewa' ? 'selected' : '' ?>>Disewa</option>
                            <option value="Rusak" <?= $row['status'] === 'Rusak' ? 'selected' : '' ?>>Rusak</option>
                        </select>
                    </form>
                </td>
                <td>
                    <form method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="bike_id" value="<?= $row['id'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require 'views/layouts/footer_admin.php'; ?>

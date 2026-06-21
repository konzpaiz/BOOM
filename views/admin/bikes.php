<?php
require_once 'models/Bike.php';

$bike = new Bike();
$message = '';
$messageType = '';

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        // Auto-generate code EB-00X
        $dbTemp = new Database();
        $connTemp = $dbTemp->getConnection();
        $stmtTemp = $connTemp->query("SELECT MAX(id) FROM sepeda");
        $maxId = (int)$stmtTemp->fetchColumn();
        $bike->kode_sepeda = 'EB-00' . ($maxId + 1);
        $bike->merk = $_POST['merk'];
        $bike->tarif_per_jam = $_POST['tarif_per_jam'];
        $bike->status = $_POST['status'] ?? 'Tersedia';
        $bike->image = 'default_bike.png';
        if ($bike->create()) {
            $message = 'Sepeda berhasil ditambahkan.';
            $messageType = 'success';
        } else {
            $message = 'Gagal menambahkan sepeda. Kode mungkin sudah ada.';
            $messageType = 'danger';
        }
    }
    if ($_POST['action'] === 'delete') {
        $bike->id = $_POST['bike_id'];
        if ($bike->delete()) {
            $message = 'Sepeda berhasil dihapus.';
            $messageType = 'success';
        } else {
            $message = 'Gagal menghapus. Sepeda mungkin sedang disewa.';
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
    if ($_POST['action'] === 'edit') {
        $bike->id = $_POST['bike_id'];
        $bike->kode_sepeda = $_POST['kode_sepeda'];
        $bike->merk = $_POST['merk'];
        $bike->tarif_per_jam = $_POST['tarif_per_jam'];
        $bike->status = $_POST['status'];
        $bike->image = 'default_bike.png';
        if ($bike->update()) {
            $message = 'Data sepeda berhasil diperbarui.';
            $messageType = 'success';
            // Redirect to clean URL
            header("Location: index.php?page=admin_bikes&msg=updated");
            exit;
        } else {
            $message = 'Gagal memperbarui data.';
            $messageType = 'danger';
        }
    }
}

if (isset($_GET['msg']) && $_GET['msg'] == 'updated') {
    $message = 'Data sepeda berhasil diperbarui.';
    $messageType = 'success';
}

// Check for edit mode
$isEditMode = false;
$editBike = new Bike();
if (isset($_GET['edit'])) {
    if ($editBike->readById($_GET['edit'])) {
        $editBike->id = $_GET['edit'];
        $isEditMode = true;
    }
}

$bike = new Bike();
$stmt = $bike->readAll();

require 'views/layouts/header_admin.php';
?>

<div class="admin-topbar">
    <h1>Kelola Sepeda</h1>
    <div class="topbar-info">Kelola seluruh armada sepeda listrik kampus</div>
</div>

<div class="admin-content">
    <?php if ($message): ?>
        <div class="alert alert-<?= $messageType ?>"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <!-- Form Section: Edit or Add -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3><?= $isEditMode ? '📝 Edit Sepeda: ' . htmlspecialchars($editBike->merk) : '➕ Tambah Sepeda Baru' ?></h3>
            <?php if ($isEditMode): ?>
                <a href="index.php?page=admin_bikes" class="badge badge-gray" style="text-decoration:none;">Batal Edit</a>
            <?php endif; ?>
        </div>
        <div class="admin-card-body">
            <form method="POST" style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
                <input type="hidden" name="action" value="<?= $isEditMode ? 'edit' : 'add' ?>">
                <?php if ($isEditMode): ?>
                    <input type="hidden" name="bike_id" value="<?= htmlspecialchars($editBike->id) ?>">
                <?php endif; ?>
                
                <?php if ($isEditMode): ?>
                    <input type="hidden" name="kode_sepeda" value="<?= htmlspecialchars($editBike->kode_sepeda) ?>">
                <?php endif; ?>
                
                <div class="form-group" style="flex:1; min-width:140px; margin-bottom:0;">
                    <label>Merk</label>
                    <input type="text" name="merk" class="form-control" placeholder="Xiaomi Himo" 
                           value="<?= $isEditMode ? htmlspecialchars($editBike->merk) : '' ?>" required>
                </div>
                
                <div class="form-group" style="flex:1; min-width:120px; margin-bottom:0;">
                    <label>Tarif/Jam (Rp)</label>
                    <input type="number" name="tarif_per_jam" class="form-control" placeholder="5000" min="0" 
                           value="<?= $isEditMode ? htmlspecialchars($editBike->tarif_per_jam) : '' ?>" required>
                </div>

                <?php if ($isEditMode): ?>
                <div class="form-group" style="flex:1; min-width:120px; margin-bottom:0;">
                    <label>Status</label>
                    <select name="status" class="form-control" required>
                        <option value="Tersedia" <?= $editBike->status === 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                        <option value="Disewa" <?= $editBike->status === 'Disewa' ? 'selected' : '' ?>>Sedang Disewa</option>
                        <option value="Rusak" <?= $editBike->status === 'Rusak' ? 'selected' : '' ?>>Rusak</option>
                    </select>
                </div>
                <?php endif; ?>
                
                <button type="submit" class="btn btn-primary" style="height:42px;">
                    <?= $isEditMode ? 'Simpan' : 'Tambah' ?>
                </button>
            </form>
        </div>
    </div>

    <!-- Daftar Sepeda -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3>Daftar Sepeda (<?= $stmt->rowCount() ?>)</h3>
        </div>
        <div style="overflow-x:auto;">
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
                    <tr <?= $isEditMode && $editBike->id == $row['id'] ? 'style="background: #eff6ff;"' : '' ?>>
                        <td><?= htmlspecialchars($row['merk']) ?></td>
                        <td>Rp <?= number_format($row['tarif_per_jam'], 0, ',', '.') ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="update_status">
                                <input type="hidden" name="bike_id" value="<?= $row['id'] ?>">
                                <select name="status" class="form-control" style="width:auto; display:inline; padding:4px 8px; font-size:12px; border-radius:6px;" onchange="this.form.submit()">
                                    <option value="Tersedia" <?= $row['status'] === 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                                    <option value="Disewa" <?= $row['status'] === 'Disewa' ? 'selected' : '' ?>>Sedang Disewa</option>
                                    <option value="Rusak" <?= $row['status'] === 'Rusak' ? 'selected' : '' ?>>Rusak</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <a href="index.php?page=admin_bikes&edit=<?= $row['id'] ?>" class="btn btn-warning btn-sm" style="padding:6px 12px; font-size:12px; margin-right:4px;">Edit</a>
                            
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus sepeda ini?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="bike_id" value="<?= $row['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm" style="padding:6px 12px; font-size:12px;">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php if ($stmt->rowCount() === 0): ?>
                        <tr><td colspan="5" class="text-center text-muted" style="padding:20px;">Belum ada data sepeda.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer_admin.php'; ?>

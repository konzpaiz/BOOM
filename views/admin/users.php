<?php require 'views/layouts/sidebar_admin.php'; ?>
<?php
require_once 'models/User.php';
$userModel = new User();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $userModel->id = $_POST['id'];
    if ($userModel->delete()) {
        $msg = "User berhasil dihapus.";
    } else {
        $err = "Gagal menghapus user.";
    }
}

$users = $userModel->readAll();
?>

<div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="margin:0;">Data Pengguna</h2>
        <div style="position: relative; width: 300px;">
            <i class="fa-solid fa-search" style="position: absolute; left: 15px; top: 12px; color: #9CA3AF;"></i>
            <input type="text" id="searchUser" class="form-control" style="padding-left: 40px;" placeholder="Cari pengguna...">
        </div>
    </div>
    
    <?php if (isset($msg)): ?><div class="alert alert-success"><i class="fa-solid fa-check"></i> <?php echo $msg; ?></div><?php endif; ?>
    <?php if (isset($err)): ?><div class="alert alert-danger"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo $err; ?></div><?php endif; ?>

    <div class="table-responsive">
        <table id="userTable">
            <thead>
                <tr>
                    <th>NIM / Username</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $users->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><strong><?php echo $row['nim']; ?></strong></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo isset($row['phone']) ? $row['phone'] : '-'; ?></td>
                    <td>
                        <?php if ($row['role'] == 'admin'): ?>
                            <span class="badge badge-warning">Admin</span>
                        <?php else: ?>
                            <span class="badge badge-success">User</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['role'] != 'admin'): ?>
                        <form method="POST" action="" onsubmit="return confirm('Hapus pengguna ini beserta riwayatnya?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;"><i class="fa-solid fa-trash"></i> Hapus</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.getElementById('searchUser').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#userTable tbody tr');
    
    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script>

<?php require 'views/layouts/footer.php'; ?>

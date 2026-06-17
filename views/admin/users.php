<?php
require_once 'models/User.php';

$user = new User();
$message = '';
$messageType = '';

// Handle delete action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $user->id = $_POST['user_id'];
    if ($user->delete()) {
        $message = 'Pengguna berhasil dihapus.';
        $messageType = 'success';
    } else {
        $message = 'Gagal menghapus pengguna.';
        $messageType = 'danger';
    }
}

$stmt = $user->readAll();

require 'views/layouts/header_admin.php';
?>

<?php if ($message): ?>
    <div class="alert alert-<?= $messageType ?>">
        <i class="fas fa-<?= $messageType === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-bottom:16px;">
        <h3 style="margin:0; font-size: 1.1rem; color: #1F2937;">
            <i class="fas fa-users" style="margin-right:8px; color:#6B7280;"></i>Daftar Pengguna
        </h3>
        <div class="form-group" style="margin:0; position:relative; min-width:220px;">
            <i class="fas fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#6B7280;"></i>
            <input type="text" id="searchUser" class="form-control" placeholder="Cari pengguna..." style="padding-left:36px;" onkeyup="filterUsers()">
        </div>
    </div>

    <div class="table-container">
        <table id="usersTable">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($row['nim']) ?></strong></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td>
                        <a href="mailto:<?= htmlspecialchars($row['email']) ?>" style="color:#1E3A8A; text-decoration:none;">
                            <?= htmlspecialchars($row['email']) ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($row['phone'] ?? '-') ?></td>
                    <td>
                        <?php if ($row['role'] === 'admin'): ?>
                            <span class="badge badge-warning"><i class="fas fa-shield-alt" style="margin-right:4px;"></i>Admin</span>
                        <?php else: ?>
                            <span class="badge badge-success"><i class="fas fa-user" style="margin-right:4px;"></i>User</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['role'] !== 'admin'): ?>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                            <button type="submit" class="btn btn-danger" style="padding:6px 12px; font-size:0.8rem;">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                        <?php else: ?>
                            <span class="text-muted" style="font-size:0.8rem;">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function filterUsers() {
    const input = document.getElementById('searchUser').value.toLowerCase();
    const rows = document.querySelectorAll('#usersTable tbody tr');
    rows.forEach(function(row) {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(input) ? '' : 'none';
    });
}
</script>

<?php require 'views/layouts/footer_admin.php'; ?>

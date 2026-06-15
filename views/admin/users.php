<?php require 'views/layouts/header.php'; ?>
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
    <h2>Kelola Pengguna</h2>
    
    <?php if (isset($msg)): ?><div class="alert alert-success"><?php echo $msg; ?></div><?php endif; ?>
    <?php if (isset($err)): ?><div class="alert alert-danger"><?php echo $err; ?></div><?php endif; ?>

    <div class="table-responsive mt-4">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NIM / Username</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $users->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['nim']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td>
                        <?php if ($row['role'] == 'admin'): ?>
                            <span class="badge badge-warning">Admin</span>
                        <?php else: ?>
                            <span class="badge badge-success">User</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['role'] != 'admin'): ?>
                        <form method="POST" action="" onsubmit="return confirm('Hapus pengguna ini?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">Hapus</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>

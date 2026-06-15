<?php require 'views/layouts/sidebar_user.php'; ?>
<?php
require_once 'models/User.php';
$user = new User();
$user->readById($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'update_profile') {
        $user->name = $_POST['name'];
        $user->email = $_POST['email'];
        $user->phone = $_POST['phone'];
        
        $query = "UPDATE users SET name=:name, email=:email, phone=:phone WHERE id=:id";
        $db = new Database();
        $conn = $db->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":name", $user->name);
        $stmt->bindParam(":email", $user->email);
        $stmt->bindParam(":phone", $user->phone);
        $stmt->bindParam(":id", $user->id);
        
        if ($stmt->execute()) {
            $_SESSION['name'] = $user->name; // update session name
            $msg = "Profil berhasil diperbarui.";
        } else {
            $err = "Gagal memperbarui profil.";
        }
    } elseif ($_POST['action'] == 'update_password') {
        if (!empty($_POST['new_password'])) {
            $password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
            $query = "UPDATE users SET password=:password WHERE id=:id";
            $db = new Database();
            $conn = $db->getConnection();
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":password", $password);
            $stmt->bindParam(":id", $user->id);
            if ($stmt->execute()) {
                $msg_pwd = "Password berhasil diubah.";
            } else {
                $err_pwd = "Gagal mengubah password.";
            }
        }
    }
}
?>

<div>
    <h2 style="margin-bottom: 1.5rem;">Profil Saya</h2>
    
    <div class="grid">
        <div class="card">
            <h3><i class="fa-solid fa-user-pen" style="color: var(--primary-color);"></i> Edit Data Pribadi</h3>
            <?php if(isset($msg)): ?><div class="alert alert-success mt-2"><?php echo $msg; ?></div><?php endif; ?>
            <?php if(isset($err)): ?><div class="alert alert-danger mt-2"><?php echo $err; ?></div><?php endif; ?>
            
            <form method="POST" action="" class="mt-3">
                <input type="hidden" name="action" value="update_profile">
                <div class="form-group">
                    <label>NIM</label>
                    <input type="text" class="form-control" value="<?php echo $user->nim; ?>" disabled style="background-color: #F3F4F6;">
                    <small class="text-muted">NIM tidak dapat diubah.</small>
                </div>
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="<?php echo $user->name; ?>" required>
                </div>
                <div class="form-group">
                    <label>Email Kampus</label>
                    <input type="email" name="email" class="form-control" value="<?php echo $user->email; ?>" required>
                </div>
                <div class="form-group">
                    <label>Nomor Telepon</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo $user->phone; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan Perubahan</button>
            </form>
        </div>
        
        <div class="card">
            <h3><i class="fa-solid fa-shield-halved" style="color: var(--secondary-color);"></i> Keamanan Akun</h3>
            <?php if(isset($msg_pwd)): ?><div class="alert alert-success mt-2"><?php echo $msg_pwd; ?></div><?php endif; ?>
            <?php if(isset($err_pwd)): ?><div class="alert alert-danger mt-2"><?php echo $err_pwd; ?></div><?php endif; ?>
            
            <form method="POST" action="" class="mt-3">
                <input type="hidden" name="action" value="update_password">
                <div class="form-group">
                    <label>Password Baru</label>
                    <input type="password" name="new_password" class="form-control" placeholder="Kosongkan jika tidak ingin diubah">
                </div>
                <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-key"></i> Ubah Password</button>
            </form>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>

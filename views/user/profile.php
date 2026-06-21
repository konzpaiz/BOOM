<?php
require_once 'models/User.php';
$user = new User();
$user->readById($_SESSION['user_id']);

$error = '';
$success = '';

// Handle photo upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_pic'])) {
    $file = $_FILES['profile_pic'];
    
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
    $maxSize = 2 * 1024 * 1024; // 2MB
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = "Gagal mengunggah foto.";
    } elseif (!in_array($file['type'], $allowedTypes)) {
        $error = "Format tidak didukung. Pilih file JPG, PNG, JPEG, atau GIF.";
    } elseif ($file['size'] > $maxSize) {
        $error = "Ukuran file terlalu besar. Maksimal 2MB.";
    } else {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = 'avatar_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
        $targetDir = 'uploads/profile_pics/';
        
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        
        $targetPath = $targetDir . $fileName;
        
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            // Delete old photo
            if ($user->foto_profil && file_exists($user->foto_profil)) {
                @unlink($user->foto_profil);
            }
            
            // Save to database
            if ($user->updateProfilePhoto($_SESSION['user_id'], $targetPath)) {
                $success = "Foto profil berhasil diperbarui!";
                $user->readById($_SESSION['user_id']); // Reload data
            } else {
                $error = "Gagal menyimpan foto profil ke database.";
            }
        } else {
            $error = "Gagal memindahkan file ke folder server.";
        }
    }
}

if ($_SESSION['role'] === 'admin') {
    require 'views/layouts/header_admin.php';
} else {
    require 'views/layouts/header_user.php';
}
?>

<div class="section-title">Profil Saya</div>
<div class="section-subtitle">Detail akun dan pengaturan foto profil</div>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="card text-center" style="padding: 24px 18px;">
    <!-- Foto Profil -->
    <?php if ($user->foto_profil && file_exists($user->foto_profil)): ?>
        <div class="profile-avatar-wrapper">
            <img src="<?= htmlspecialchars($user->foto_profil) ?>" class="profile-avatar" alt="Foto Profil">
        </div>
    <?php else: ?>
        <div class="profile-avatar-default">
            <?= strtoupper(substr($user->name, 0, 1)) ?>
        </div>
    <?php endif; ?>

    <div style="font-size:18px; font-weight:700; margin-bottom:2px; color:#1f2937;"><?= htmlspecialchars($user->name) ?></div>
    <div class="text-muted text-small" style="margin-bottom: 16px;">
        <?= $_SESSION['role'] === 'admin' ? 'Administrator' : 'Pelanggan / Mahasiswa' ?>
    </div>

    <!-- Form Upload Instan -->
    <form method="POST" enctype="multipart/form-data" id="photo-upload-form" style="margin-bottom: 20px;">
        <label class="btn btn-outline btn-sm" style="cursor:pointer; display:inline-block; border-color:#d1d5db; font-size:12px; font-weight:500;">
            📷 Pilih Foto Baru
            <input type="file" name="profile_pic" accept="image/*" style="display:none;" onchange="document.getElementById('photo-upload-form').submit()">
        </label>
    </form>

    <div class="divider" style="margin: 16px 0;"></div>

    <div class="profile-info-row">
        <span class="p-label">NIM</span>
        <span class="p-value"><?= htmlspecialchars($user->nim) ?></span>
    </div>
    <div class="profile-info-row">
        <span class="p-label">Email</span>
        <span class="p-value"><?= htmlspecialchars($user->email) ?></span>
    </div>
    <div class="profile-info-row">
        <span class="p-label">Telepon</span>
        <span class="p-value"><?= htmlspecialchars($user->phone ?? '-') ?></span>
    </div>
</div>

<a href="index.php?page=logout" class="btn btn-danger btn-block" onclick="return confirm('Yakin ingin keluar dari aplikasi?');">Logout</a>

<?php 
if ($_SESSION['role'] === 'admin') {
    require 'views/layouts/footer_admin.php';
} else {
    require 'views/layouts/footer_user.php';
}
?>

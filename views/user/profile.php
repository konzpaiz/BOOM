<?php require 'views/layouts/header_user.php'; ?>
<?php
require_once 'models/User.php';
$user = new User();
$user->readById($_SESSION['user_id']);
?>

<div class="section-title">Profil Saya</div>
<div class="section-subtitle">Informasi akun Anda</div>

<!-- Avatar & Info -->
<div class="card text-center">
    <div class="avatar"><?php echo strtoupper(substr($user->name, 0, 1)); ?></div>
    <div style="font-size:18px; font-weight:700; color:#1f2937; margin-bottom:2px;">
        <?php echo htmlspecialchars($user->name); ?>
    </div>
    <div style="font-size:13px; color:#9ca3af; margin-bottom:16px;">Mahasiswa</div>

    <div class="profile-info-row">
        <span class="p-label">NIM</span>
        <span class="p-value"><?php echo htmlspecialchars($user->nim); ?></span>
    </div>
    <div class="profile-info-row">
        <span class="p-label">Email</span>
        <span class="p-value"><?php echo htmlspecialchars($user->email); ?></span>
    </div>
</div>

<a href="index.php?page=logout" class="btn btn-danger btn-block" onclick="return confirm('Yakin ingin keluar?');">Logout</a>

<?php require 'views/layouts/footer_user.php'; ?>

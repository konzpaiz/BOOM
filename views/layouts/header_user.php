<?php
require_once 'models/User.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>E-Bike Campus</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <img src="assets/img/logo.png" alt="Logo">
            <span>E-Bike Campus</span>
        </div>
        <div class="header-right">
            <?php echo htmlspecialchars($_SESSION['name']); ?>
        </div>
    </div>
    <div class="container">

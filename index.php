<?php
session_start();
require_once 'config/database.php';

// Simple Router
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Routing logic
if ($page == 'home') {
    if (isset($_SESSION['user_id'])) {
        if ($_SESSION['role'] == 'admin') {
            header('Location: index.php?page=admin_dashboard');
        } else {
            header('Location: index.php?page=user_dashboard');
        }
        exit;
    }
    require 'views/auth/login.php';
} elseif ($page == 'register') {
    require 'views/auth/register.php';
} elseif ($page == 'logout') {
    session_destroy();
    header('Location: index.php');
    exit;
} elseif ($page == 'user_dashboard') {
    // protect route
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
        header('Location: index.php');
        exit;
    }
    require 'views/user/dashboard.php';
} elseif ($page == 'scan') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
        header('Location: index.php');
        exit;
    }
    require 'views/user/scan.php';
} elseif ($page == 'payment') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
        header('Location: index.php');
        exit;
    }
    require 'views/user/payment.php';
} elseif ($page == 'history') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
        header('Location: index.php');
        exit;
    }
    require 'views/user/history.php';
} elseif ($page == 'admin_dashboard') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header('Location: index.php');
        exit;
    }
    require 'views/admin/dashboard.php';
} elseif ($page == 'admin_bikes') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header('Location: index.php');
        exit;
    }
    require 'views/admin/bikes.php';
} elseif ($page == 'admin_users') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header('Location: index.php');
        exit;
    }
    require 'views/admin/users.php';
} elseif ($page == 'admin_transactions') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header('Location: index.php');
        exit;
    }
    require 'views/admin/transactions.php';
} else {
    echo "404 Page Not Found";
}
?>

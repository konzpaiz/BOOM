<?php
session_start();
require_once 'config/database.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Auth pages
if ($page == 'home') {
    if (isset($_SESSION['user_id'])) {
        header('Location: index.php?page=' . ($_SESSION['role'] == 'admin' ? 'admin_dashboard' : 'user_dashboard'));
        exit;
    }
    require 'views/auth/login.php';
} elseif ($page == 'register') {
    require 'views/auth/register.php';
} elseif ($page == 'logout') {
    session_destroy();
    header('Location: index.php');
    exit;

    // === USER PAGES ===
} elseif ($page == 'user_dashboard') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
        header('Location: index.php');
        exit;
    }
    require 'views/user/dashboard.php';
} elseif ($page == 'user_bikes') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
        header('Location: index.php');
        exit;
    }
    require 'views/user/bikes.php';
} elseif ($page == 'profile') {
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php');
        exit;
    }
    require 'views/user/profile.php';
} elseif ($page == 'scan') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
        header('Location: index.php');
        exit;
    }
    require 'views/user/scan.php';
} elseif ($page == 'user_active') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
        header('Location: index.php');
        exit;
    }
    require 'views/user/active.php';
} elseif ($page == 'history') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
        header('Location: index.php');
        exit;
    }
    require 'views/user/history.php';
} elseif ($page == 'payment') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
        header('Location: index.php');
        exit;
    }
    require 'views/user/payment.php';
} elseif ($page == 'faq') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
        header('Location: index.php');
        exit;
    }
    require 'views/user/faq.php';

    // === ADMIN PAGES ===
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
} elseif ($page == 'admin_availability') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header('Location: index.php');
        exit;
    }
    require 'views/admin/availability.php';
} elseif ($page == 'admin_users') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header('Location: index.php');
        exit;
    }
    require 'views/admin/users.php';
} elseif ($page == 'admin_rentals') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header('Location: index.php');
        exit;
    }
    require 'views/admin/rentals.php';
} else {
    echo "<p>404 - Halaman tidak ditemukan.</p>";
}
?>//test//
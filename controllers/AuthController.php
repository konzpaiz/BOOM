<?php
require_once 'models/User.php';

class AuthController {
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = new User();
            $nim = $_POST['nim'];
            $password = $_POST['password'];

            $stmt = $user->readByNim($nim);
            $num = $stmt->rowCount();

            if ($num > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($password, $row['password'])) {
                    // Password correct, start session
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['nim'] = $row['nim'];
                    $_SESSION['name'] = $row['name'];
                    $_SESSION['role'] = $row['role'];

                    if ($row['role'] == 'admin') {
                        header('Location: index.php?page=admin_dashboard');
                    } else {
                        header('Location: index.php?page=user_dashboard');
                    }
                    exit;
                } else {
                    $error = "Password salah.";
                }
            } else {
                $error = "NIM/Username tidak ditemukan.";
            }
            return $error;
        }
        return null;
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = new User();
            $user->nim = $_POST['nim'];
            $user->name = $_POST['name'];
            $user->email = $_POST['email'];
            $user->phone = isset($_POST['phone']) ? $_POST['phone'] : null;
            $user->password = $_POST['password'];
            $user->role = 'user';

            // Check if NIM already exists
            $stmt = $user->readByNim($user->nim);
            if ($stmt->rowCount() > 0) {
                return "NIM sudah terdaftar.";
            }

            if ($user->create()) {
                header('Location: index.php?page=home&msg=registered');
                exit;
            } else {
                return "Gagal mendaftar. Silakan coba lagi.";
            }
        }
        return null;
    }
}
?>

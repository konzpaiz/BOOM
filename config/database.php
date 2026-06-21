<?php
class Database {
    private $host = "localhost";
    private $db_name = "ebike_db";
    private $username = "root"; // adjust this if necessary
    private $password = "";     // adjust this if necessary
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            // Connect to MySQL server first without specifying the db
            $temp_conn = new PDO("mysql:host=" . $this->host, $this->username, $this->password);
            $temp_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Create database if it doesn't exist
            $temp_conn->exec("CREATE DATABASE IF NOT EXISTS `" . $this->db_name . "`");
            $temp_conn = null; // Close connection

            // Now connect to the ebike_db database
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Run migrations to ensure tables exist
            $this->initializeTables();
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }

    private function initializeTables() {
        if (!$this->conn) return;

        // 1. users table
        $sql_users = "CREATE TABLE IF NOT EXISTS `users` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `nim` VARCHAR(50) NOT NULL UNIQUE,
            `name` VARCHAR(100) NOT NULL,
            `email` VARCHAR(100) NOT NULL UNIQUE,
            `phone` VARCHAR(20) DEFAULT NULL,
            `password` VARCHAR(255) NOT NULL,
            `role` VARCHAR(20) DEFAULT 'user',
            `saldo` INT DEFAULT 0,
            `foto_profil` VARCHAR(255) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $this->conn->exec($sql_users);

        // 2. sepeda table
        $sql_sepeda = "CREATE TABLE IF NOT EXISTS `sepeda` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `kode_sepeda` VARCHAR(50) NOT NULL UNIQUE,
            `merk` VARCHAR(100) NOT NULL,
            `tarif_per_jam` INT NOT NULL,
            `status` VARCHAR(50) DEFAULT 'Tersedia',
            `image` VARCHAR(255) DEFAULT 'default_bike.png',
            `qr_code_url` VARCHAR(255) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $this->conn->exec($sql_sepeda);

        // 3. transaksi table
        $sql_transaksi = "CREATE TABLE IF NOT EXISTS `transaksi` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NOT NULL,
            `sepeda_id` INT NOT NULL,
            `waktu_mulai` DATETIME NOT NULL,
            `waktu_selesai` DATETIME DEFAULT NULL,
            `total_biaya` INT DEFAULT 0,
            `status_pembayaran` VARCHAR(50) DEFAULT 'Belum Bayar',
            `status_sewa` VARCHAR(50) DEFAULT 'Berjalan',
            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`sepeda_id`) REFERENCES `sepeda`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $this->conn->exec($sql_transaksi);

        // Seed default users if users table is empty
        $stmt = $this->conn->query("SELECT COUNT(*) FROM `users`");
        if ($stmt->fetchColumn() == 0) {
            // Seed Admin (pass: admin)
            $admin_pass = password_hash('admin', PASSWORD_BCRYPT);
            $stmt_admin = $this->conn->prepare("INSERT INTO `users` (nim, name, email, phone, password, role, saldo) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt_admin->execute(['admin', 'Administrator', 'admin@ebike.com', '08123456789', $admin_pass, 'admin', 0]);

            // Seed User 123 (pass: 123)
            $user_pass = password_hash('123', PASSWORD_BCRYPT);
            $stmt_user = $this->conn->prepare("INSERT INTO `users` (nim, name, email, phone, password, role, saldo) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt_user->execute(['123', 'Budi Santoso', 'budi@ebike.com', '08987654321', $user_pass, 'user', 50000]);
        }

        // Seed default bikes if sepeda table is empty
        $stmt_bike = $this->conn->query("SELECT COUNT(*) FROM `sepeda`");
        if ($stmt_bike->fetchColumn() == 0) {
            $bikes_data = [
                ['EB-001', 'Sepeda Listrik 01', 5000, 'Tersedia', 'default_bike.png'],
                ['EB-002', 'Sepeda Listrik 02', 5000, 'Tersedia', 'default_bike.png'],
                ['EB-003', 'Sepeda Listrik 03', 5000, 'Tersedia', 'default_bike.png'],
                ['EB-004', 'Sepeda Listrik 04', 5000, 'Tersedia', 'default_bike.png']
            ];
            $stmt_ins = $this->conn->prepare("INSERT INTO `sepeda` (kode_sepeda, merk, tarif_per_jam, status, image) VALUES (?, ?, ?, ?, ?)");
            foreach ($bikes_data as $bike_row) {
                $stmt_ins->execute($bike_row);
            }
        }
    }
}
?>

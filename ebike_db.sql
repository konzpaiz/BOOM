CREATE DATABASE IF NOT EXISTS ebike_db;
USE ebike_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user'
);

CREATE TABLE IF NOT EXISTS sepeda (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_sepeda VARCHAR(50) UNIQUE NOT NULL,
    merk VARCHAR(100) NOT NULL,
    tarif_per_jam INT NOT NULL,
    status ENUM('Tersedia', 'Disewa', 'Rusak') DEFAULT 'Tersedia',
    image VARCHAR(255) DEFAULT 'default_bike.png',
    qr_code_url VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    sepeda_id INT NOT NULL,
    waktu_mulai DATETIME NOT NULL,
    waktu_selesai DATETIME NULL,
    total_biaya INT NULL,
    status_pembayaran ENUM('Belum Bayar', 'Lunas') DEFAULT 'Belum Bayar',
    status_sewa ENUM('Berjalan', 'Selesai') DEFAULT 'Berjalan',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (sepeda_id) REFERENCES sepeda(id) ON DELETE CASCADE
);

-- Hapus data lama jika ada, untuk menghindari duplikasi saat import ulang
TRUNCATE TABLE transaksi;
DELETE FROM sepeda;
DELETE FROM users;

-- Insert default admin
INSERT INTO users (nim, name, email, phone, password, role) VALUES 
('admin', 'Administrator', 'admin@ebike.campus.edu', '08123456789', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
-- Default password is 'password'

-- Insert some dummy bikes
INSERT INTO sepeda (kode_sepeda, merk, tarif_per_jam, status, image) VALUES 
('EB-001', 'Xiaomi Himo', 5000, 'Tersedia', 'default_bike.png'),
('EB-002', 'Fiido D11', 6000, 'Tersedia', 'default_bike.png'),
('EB-003', 'Polygon Path E5', 8000, 'Tersedia', 'default_bike.png');

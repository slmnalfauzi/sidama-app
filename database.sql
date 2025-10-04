-- Database: sidama
CREATE DATABASE IF NOT EXISTS sidama CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sidama;

-- Tabel users untuk login
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'viewer') DEFAULT 'viewer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel mahasiswa
CREATE TABLE IF NOT EXISTS mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) UNIQUE NOT NULL,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    tanggal_lahir DATE NOT NULL,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    alamat TEXT,
    foto VARCHAR(255) DEFAULT 'default.jpg',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert contoh users
INSERT INTO users (username, password, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'), -- password: password
('viewer', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'viewer'); -- password: password

-- Insert contoh mahasiswa
INSERT INTO mahasiswa (nim, nama, email, tanggal_lahir, jenis_kelamin, alamat) VALUES
('2021001', 'Ahmad Fauzi', 'ahmad.fauzi@email.com', '2003-05-15', 'L', 'Jl. Merdeka No. 10, Jakarta'),
('2021002', 'Siti Nurhaliza', 'siti.nurhaliza@email.com', '2002-08-20', 'P', 'Jl. Sudirman No. 25, Bandung'),
('2021003', 'Budi Santoso', 'budi.santoso@email.com', '2003-12-10', 'L', 'Jl. Gatot Subroto No. 5, Surabaya');

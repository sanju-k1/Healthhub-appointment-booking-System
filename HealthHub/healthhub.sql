DROP DATABASE IF EXISTS healthhub;
CREATE DATABASE healthhub;
USE healthhub;

-- ✅ Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','doctor','patient') DEFAULT 'patient'
);

-- ✅ Doctors Table
CREATE TABLE doctors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    specialty VARCHAR(100) NOT NULL
);

-- ✅ Appointments Table
CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient VARCHAR(50) NOT NULL,
    doctor_id INT NOT NULL,
    date DATE NOT NULL,
    slot VARCHAR(50) NOT NULL,
    status ENUM('Pending','Approved','Rejected') DEFAULT 'Pending',
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
);

-- ✅ Insert Sample Users
INSERT INTO users (username, password, role) VALUES
('admin', 'Admin@2025!', 'admin'),
('doctor1', 'Doc@2025!', 'doctor'),
('patient1', 'Patient@2025!', 'patient');

-- ✅ Insert Sample Doctors
INSERT INTO doctors (name, specialty) VALUES
('Dr. Smith', 'Cardiology'),
('Dr. John', 'Neurology'),
('Dr. Sara', 'Pediatrics');

-- ✅ Sample Appointments
INSERT INTO appointments (patient, doctor_id, date, slot, status) VALUES
('patient1', 1, '2025-08-05', '10:00 AM', 'Pending'),
('patient1', 2, '2025-08-06', '3:00 PM', 'Approved');

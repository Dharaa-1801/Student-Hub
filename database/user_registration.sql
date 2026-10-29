-- StudentHub authentication database export
-- Import this file into MySQL/MariaDB before testing login and role access.

CREATE DATABASE IF NOT EXISTS studenthub_db;
USE studenthub_db;

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    course VARCHAR(100) DEFAULT NULL,
    academic_year VARCHAR(20) DEFAULT NULL,
    gender VARCHAR(20) DEFAULT NULL,
    profile_photo VARCHAR(255) DEFAULT NULL,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    role ENUM('admin', 'student') NOT NULL DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS remember_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    selector VARCHAR(24) NOT NULL UNIQUE,
    token_hash CHAR(64) NOT NULL,
    user_id INT NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT remember_tokens_user_fk
        FOREIGN KEY (user_id) REFERENCES students(id) ON DELETE CASCADE
);

ALTER TABLE students
    ADD COLUMN IF NOT EXISTS status ENUM('active', 'inactive') NOT NULL DEFAULT 'active';

-- The password for these test accounts is: password
INSERT INTO students (fullname, email, password, role) VALUES
('Portal Admin', 'admin@charusat.edu.in', '$2y$10$U5ngh8VOw9vkpXso9Jt8MepTWxm/vQLT7wOzTKKulIifc892ms57e', 'admin'),
('Durva Thakor', '25CS015@charusat.edu.in', '$2y$10$U5ngh8VOw9vkpXso9Jt8MepTWxm/vQLT7wOzTKKulIifc892ms57e', 'student'),
('Shivani Padhiyar', '25CS029@charusat.edu.in', '$2y$10$U5ngh8VOw9vkpXso9Jt8MepTWxm/vQLT7wOzTKKulIifc892ms57e', 'student'),
('Dhara Sarvaiya', '25CS093@charusat.edu.in', '$2y$10$U5ngh8VOw9vkpXso9Jt8MepTWxm/vQLT7wOzTKKulIifc892ms57e', 'student')
ON DUPLICATE KEY UPDATE
    fullname = VALUES(fullname),
    password = VALUES(password),
    role = VALUES(role);

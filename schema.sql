-- Create Database
CREATE DATABASE IF NOT EXISTS studenthub_db;
USE studenthub_db;

-- 1. Students Table
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    course VARCHAR(100) DEFAULT NULL,
    academic_year VARCHAR(20) DEFAULT NULL,
    gender VARCHAR(20) DEFAULT NULL,
    profile_photo VARCHAR(255) DEFAULT NULL,
    role ENUM('admin', 'student') NOT NULL DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Migration for an existing students table from the earlier practical.
-- MySQL/MariaDB will add role only when it is missing.
ALTER TABLE students
    ADD COLUMN IF NOT EXISTS role ENUM('admin', 'student') NOT NULL DEFAULT 'student';
ALTER TABLE students ADD COLUMN IF NOT EXISTS phone VARCHAR(20) DEFAULT NULL;
ALTER TABLE students ADD COLUMN IF NOT EXISTS course VARCHAR(100) DEFAULT NULL;
ALTER TABLE students ADD COLUMN IF NOT EXISTS academic_year VARCHAR(20) DEFAULT NULL;
ALTER TABLE students ADD COLUMN IF NOT EXISTS gender VARCHAR(20) DEFAULT NULL;
ALTER TABLE students ADD COLUMN IF NOT EXISTS profile_photo VARCHAR(255) DEFAULT NULL;

-- Existing users become students by default. Change this email if another
-- existing account should be used for the administrator dashboard.
UPDATE students SET role = 'student';
UPDATE students SET role = 'admin'
WHERE email = 'rahul@charusat.edu.in';

CREATE TABLE IF NOT EXISTS remember_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    selector VARCHAR(24) NOT NULL UNIQUE,
    token_hash CHAR(64) NOT NULL,
    user_id INT NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES students(id) ON DELETE CASCADE
);

-- 2. Events Table
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    category VARCHAR(50) NOT NULL,
    event_date DATE NOT NULL,
    description TEXT
);

-- 3. Registrations Table (Foreign Key relationship)
CREATE TABLE IF NOT EXISTS registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    event_id INT NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject VARCHAR(100) NOT NULL,
    attendance_date DATE NOT NULL,
    status ENUM('present', 'absent') NOT NULL,
    UNIQUE KEY attendance_entry (student_id, subject, attendance_date),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS timetable (
    id INT AUTO_INCREMENT PRIMARY KEY,
    day_name VARCHAR(20) NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    subject VARCHAR(100) NOT NULL,
    room VARCHAR(50) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS study_materials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_code VARCHAR(30) NOT NULL,
    subject_name VARCHAR(150) NOT NULL,
    material_type VARCHAR(50) NOT NULL,
    title VARCHAR(150) NOT NULL,
    resource_url VARCHAR(500) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS certificates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    certificate_type VARCHAR(100) NOT NULL,
    certificate_number VARCHAR(50) NOT NULL UNIQUE,
    issued_at DATE NOT NULL,
    status ENUM('issued', 'revoked') NOT NULL DEFAULT 'issued',
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Insert Test Data into students
INSERT INTO students (fullname, email, password, role) VALUES
('Portal Admin', 'admin@charusat.edu.in', '$2y$10$U5ngh8VOw9vkpXso9Jt8MepTWxm/vQLT7wOzTKKulIifc892ms57e', 'admin'),
('Durva Thakor', '25CS015@charusat.edu.in', '$2y$10$U5ngh8VOw9vkpXso9Jt8MepTWxm/vQLT7wOzTKKulIifc892ms57e', 'student'),
('Shivani Padhiyar','25CS029@charusat.edu.in', '$2y$10$U5ngh8VOw9vkpXso9Jt8MepTWxm/vQLT7wOzTKKulIifc892ms57e', 'student'),
('Dhara Sarvaiya','25CS093@charusat.edu.in', '$2y$10$U5ngh8VOw9vkpXso9Jt8MepTWxm/vQLT7wOzTKKulIifc892ms57e', 'student'),
('Prakruti Saradva','25CS092@charusat.edu.in', '$2y$10$U5ngh8VOw9vkpXso9Jt8MepTWxm/vQLT7wOzTKKulIifc892ms57e', 'student'),
('Erva Savaliya','25CS095@charusat.edu.in', '$2y$10$U5ngh8VOw9vkpXso9Jt8MepTWxm/vQLT7wOzTKKulIifc892ms57e', 'student')
ON DUPLICATE KEY UPDATE
    fullname = VALUES(fullname),
    password = VALUES(password),
    role = VALUES(role);


-- Insert Test Data into events
INSERT INTO events (title, category, event_date, description) VALUES 
('Annual Tech Fest 2026', 'Tech', '2026-03-15', 'Coding hackathons and competitions.'),
('Web Dev Workshop', 'Workshop', '2026-03-20', 'Learn modern PHP and MySQL.');

-- Insert Test Data into registrations
INSERT IGNORE INTO registrations (student_id, event_id) VALUES
    (1, 1),
    (2, 2);
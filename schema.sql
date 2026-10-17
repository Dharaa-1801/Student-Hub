-- Create Database
CREATE DATABASE IF NOT EXISTS studenthub_db;
USE studenthub_db;

-- 1. Students Table
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
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

-- Insert Test Data into students
INSERT INTO students (fullname, email, password) VALUES 
('Durva Thakor', '25CS015@charusat.edu.in', '$2y$10$abcdefghijklmnopqrstuv'),
('Shivani Padhiyar','25CS029@charusat.edu.in', '$2y$10$abcdefghijklmnopqrstuv');
('Dhara Sarvaiya','25CS093@charusat.edu.in', '$2y$10$abcdefghijklmnopqrstuv');
('Prakruti Saradva','25CS092@charusat.edu.in', '$2y$10$abcdefghijklmnopqrstuv');
('Erva Savaliya','25CS095@charusat.edu.in', '$2y$10$abcdefghijklmnopqrstuv');


-- Insert Test Data into events
INSERT INTO events (title, category, event_date, description) VALUES 
('Annual Tech Fest 2026', 'Tech', '2026-03-15', 'Coding hackathons and competitions.'),
('Web Dev Workshop', 'Workshop', '2026-03-20', 'Learn modern PHP and MySQL.');

-- Insert Test Data into registrations
INSERT INTO registrations (student_id, event_id) VALUES (1, 1), (2, 2);
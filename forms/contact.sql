-- Create the database (if it doesn't already exist)
CREATE DATABASE IF NOT EXISTS if0_37598796_contact;

-- Use the newly created database
USE if0_37598796_contact;

-- Create the 'submissions' table
CREATE TABLE IF NOT EXISTS submissions (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

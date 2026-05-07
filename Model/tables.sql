-- Create Database
CREATE DATABASE IF NOT EXISTS globaltickets;
USE globaltickets;

-- Create Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    surname VARCHAR(100) NOT NULL,
    mail VARCHAR(100) NOT NULL UNIQUE,
    cellphone VARCHAR(15),
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    photo VARCHAR(255),
    role VARCHAR(50) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create Discographies Table
CREATE TABLE IF NOT EXISTS discographies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    cif VARCHAR(50) NOT NULL UNIQUE,
    mail VARCHAR(100) NOT NULL UNIQUE,
    cellphone VARCHAR(15),
    adress VARCHAR(255),
    password VARCHAR(255) NOT NULL,
    photo VARCHAR(255),
    role VARCHAR(50) DEFAULT 'discography',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create Events Table
CREATE TABLE IF NOT EXISTS events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(150) NOT NULL,
    event_date DATETIME NOT NULL,
    location VARCHAR(255) NOT NULL,
    description TEXT,
    photo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert 5 Users
INSERT INTO users (name, surname, mail, cellphone, username, password, role) VALUES
('John', 'Doe', 'john@example.com', '555-1001', 'jdoe', '123', 'user'),
('Maria', 'Smith', 'maria@example.com', '555-1002', 'msmith', '123', 'user'),
('Robert', 'Jones', 'robert@example.com', '555-1003', 'rjones', '123', 'user'),
('Ana', 'Garcia', 'ana@example.com', '555-1004', 'agarcia', '123', 'user'),
('Carlos', 'Lopez', 'carlos@example.com', '555-1005', 'clopez', '123', 'user');

-- Insert 3 Discographies
INSERT INTO discographies (name, cif, mail, cellphone, adress, password, role) VALUES
('Universal Music', 'A12345678', 'universal@example.com', '555-2001', '123 Music St', '$2y$10$YourHashedPassword6', 'discography'),
('Sony Records', 'B87654321', 'sony@example.com', '555-2002', '456 Records Ave', '$2y$10$YourHashedPassword7', 'discography'),
('Warner Bros', 'C11111111', 'warner@example.com', '555-2003', '789 Entertainment Blvd', '$2y$10$YourHashedPassword8', 'discography');


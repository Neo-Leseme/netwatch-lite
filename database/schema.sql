CREATE DATABASE IF NOT EXISTS netwatch_lite;
USE netwatch_lite;

CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Servers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    server_name VARCHAR(100) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    created_by INT,
    is_active TINYINT(1) DEFAULT 1,
    FOREIGN KEY (created_by) REFERENCES Users(id) ON DELETE CASCADE
);

CREATE TABLE StatusLogs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    server_id INT NOT NULL,
    status ENUM('online', 'offline') NOT NULL,
    response_time INT, -- in milliseconds
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (server_id) REFERENCES Servers(id) ON DELETE CASCADE
);
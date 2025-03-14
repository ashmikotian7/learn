CREATE DATABASE IF NOT EXISTS quiz_management;
USE quiz_management;

CREATE TABLE IF NOT EXISTS faculty (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    fav_color VARCHAR(50) NOT NULL
);

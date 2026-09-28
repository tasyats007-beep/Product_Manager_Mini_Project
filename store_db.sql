CREATE DATABASE IF NOT EXISTS store_db;
USE store_db;

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    category VARCHAR(50) NOT NULL DEFAULT 'Umum',
    price DECIMAL(12,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO products (name, category, price, stock) VALUES
('Kopi Susu', 'Minuman', 15000, 20),
('Roti Cokelat', 'Makanan', 12000, 15),
('Matcha Latte', 'Minuman', 18000, 10);

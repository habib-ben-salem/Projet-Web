-- Création de la base de données
CREATE DATABASE IF NOT EXISTS appinfo;

-- Donner les permissions à l'utilisateur php_docker
GRANT ALL PRIVILEGES ON appinfo.* TO 'php_docker'@'%';
FLUSH PRIVILEGES;

USE appinfo;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des véhicules
CREATE TABLE IF NOT EXISTS vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    brand VARCHAR(100) NOT NULL,
    model VARCHAR(100) NOT NULL,
    year INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image_path VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertion d'un utilisateur de test
-- Email: admin@test.com
-- Mot de passe: admin123
INSERT INTO users (email, password_hash) VALUES 
('admin@test.com', '$2y$10$mDLuz/7.cZMh92jAE//hbe5X1263yCqtosjr6xGus2KcrWATgHvae');

-- Insertion de quelques véhicules de test
INSERT INTO vehicles (brand, model, year, price, image_path, description) VALUES 
('Porsche', '911 GT3', 2024, 196500.00, 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=500', 'Voiture sportive emblématique avec moteur flat-6 de 510 ch'),
('Tesla', 'Model S Plaid', 2024, 109990.00, 'https://images.unsplash.com/photo-1560958089-b8a1929cea89?w=500', 'Berline électrique ultra-performante avec 1020 ch'),
('Toyota', 'Prius', 2024, 43900.00, 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=500', 'Hybride rechargeable économique et écologique'),
('Ferrari', 'F8 Tributo', 2024, 253000.00, 'https://images.unsplash.com/photo-1592198084033-aade902d1aae?w=500', 'Supercar italienne avec moteur V8 biturbo de 720 ch'),
('BMW', 'M3 Competition', 2024, 89900.00, 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=500', 'Berline sportive avec moteur 6 cylindres de 510 ch');

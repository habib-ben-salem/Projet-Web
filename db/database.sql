-- Création de la base de données
CREATE DATABASE IF NOT EXISTS car_showroom;
USE car_showroom;

-- Table des Marques (Brands)
CREATE TABLE IF NOT EXISTS brands (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    country VARCHAR(50),
    founded_year INT,
    logo_url VARCHAR(255)
);

-- Table des Modèles (Models) - Le catalogue principal
CREATE TABLE IF NOT EXISTS models (
    id INT AUTO_INCREMENT PRIMARY KEY,
    brand_id INT NOT NULL,
    
    -- Identité
    name VARCHAR(100) NOT NULL,        -- ex: "911 GT3", "Model 3"
    trim_level VARCHAR(100),           -- ex: "Performance", "Touring"
    year INT NOT NULL,                 -- Année du modèle
    
    -- Moteur & Performance
    engine_type VARCHAR(50),           -- ex: "Flat-6", "Dual Motor Electric"
    fuel_type VARCHAR(50) NOT NULL,    -- ex: "Essence", "Electrique", "Hybride"
    horsepower INT,                    -- en ch (hp)
    torque INT,                        -- en Nm
    zero_to_hundred DECIMAL(4,2),      -- 0-100 km/h en secondes
    top_speed INT,                     -- km/h
    drivetrain VARCHAR(20),            -- ex: "RWD", "AWD", "FWD"
    transmission VARCHAR(50),          -- ex: "PDK 7-speed", "Manuelle 6"
    
    -- Consommation & Ecologie
    consumption_mixed DECIMAL(5,2),    -- l/100km ou kWh/100km
    co2_emissions INT,                 -- g/km
    range_km INT,                      -- Autonomie (pour les électriques)
    
    -- Dimensions
    weight_kg INT,                     -- Poids à vide
    length_mm INT,
    trunk_capacity_liters INT,         -- Volume coffre
    
    -- Commercial
    base_price DECIMAL(12,2),          -- Prix de base
    image_url VARCHAR(255),
    
    FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE CASCADE
);

-- Insertion de données fictives pour tester le comparateur

-- 1. Ajout des marques
INSERT INTO brands (name, country, founded_year) VALUES 
('Porsche', 'Germany', 1931),
('Tesla', 'USA', 2003),
('Toyota', 'Japan', 1937),
('Ferrari', 'Italy', 1939);

-- 2. Ajout des modèles

-- Porsche 911 GT3 (La sportive thermique)
INSERT INTO models (brand_id, name, trim_level, year, engine_type, fuel_type, horsepower, torque, zero_to_hundred, top_speed, drivetrain, transmission, consumption_mixed, co2_emissions, weight_kg, length_mm, trunk_capacity_liters, base_price, image_url)
VALUES (
    1, '911', 'GT3', 2024, 
    '4.0L Flat-6 NA', 'Essence', 
    510, 470, 3.4, 318, 
    'RWD', 'PDK 7-speed', 
    13.0, 294, 
    1435, 4573, 132, 
    196500.00, 'https://example.com/gt3.jpg'
);

-- Tesla Model S Plaid (Le monstre électrique)
INSERT INTO models (brand_id, name, trim_level, year, engine_type, fuel_type, horsepower, torque, zero_to_hundred, top_speed, drivetrain, transmission, consumption_mixed, co2_emissions, range_km, weight_kg, length_mm, trunk_capacity_liters, base_price, image_url)
VALUES (
    2, 'Model S', 'Plaid', 2024, 
    'Tri-Motor', 'Electrique', 
    1020, 1420, 2.1, 322, 
    'AWD', '1-speed', 
    18.7, 0, 600, 
    2162, 4970, 793, 
    109990.00, 'https://example.com/plaid.jpg'
);

-- Toyota Prius (L'hybride rationnelle)
INSERT INTO models (brand_id, name, trim_level, year, engine_type, fuel_type, horsepower, torque, zero_to_hundred, top_speed, drivetrain, transmission, consumption_mixed, co2_emissions, weight_kg, length_mm, trunk_capacity_liters, base_price, image_url)
VALUES (
    3, 'Prius', 'PHEV 223', 2024, 
    '2.0L I4 Hybrid', 'Hybride Rechargeable', 
    223, 190, 6.7, 177, 
    'FWD', 'e-CVT', 
    0.7, 16, 
    1570, 4600, 284, 
    43900.00, 'https://example.com/prius.jpg'
);



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


ALTER TABLE vehicles ADD COLUMN maxSpeed INT;
ALTER TABLE vehicles ADD COLUMN zeroTOhundred DECIMAL(5,2);
ALTER TABLE vehicles ADD COLUMN engine VARCHAR(50);
ALTER TABLE vehicles ADD COLUMN power INT;
ALTER TABLE vehicles ADD COLUMN torque INT;

-- Mise à jour des véhicules avec les nouvelles colonnes
UPDATE vehicles SET maxSpeed = 320, zeroTOhundred = 3.4, engine = 'Flat-6', power = 510, torque = 470 WHERE brand = 'Porsche' AND model = '911 GT3';
UPDATE vehicles SET maxSpeed = 322, zeroTOhundred = 2.1, engine = 'Electric', power = 1020, torque = 1050 WHERE brand = 'Tesla' AND model = 'Model S Plaid';
UPDATE vehicles SET maxSpeed = 180, zeroTOhundred = 10.5, engine = 'Hybrid', power = 121, torque = 163 WHERE brand = 'Toyota' AND model = 'Prius';
UPDATE vehicles SET maxSpeed = 340, zeroTOhundred = 2.9, engine = 'V8 Biturbo', power = 720, torque = 770 WHERE brand = 'Ferrari' AND model = 'F8 Tributo';
UPDATE vehicles SET maxSpeed = 290, zeroTOhundred = 3.8, engine = 'Inline-6', power = 510, torque = 650 WHERE brand = 'BMW' AND model = 'M3 Competition';

-- Ajouter une colonne 'role' à la table users
ALTER TABLE users ADD COLUMN role VARCHAR(20) DEFAULT 'user';
UPDATE users SET role = 'admin' WHERE email = 'admin@test.com';
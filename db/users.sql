-- Création de la base de données des utilisateurs
CREATE DATABASE IF NOT EXISTS users_accounts;
USE users_accounts;

-- Table des utilisateurs (Utilisateurs simples)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    phone VARCHAR(20),
    is_active BOOLEAN DEFAULT TRUE,
    account_level INT NOT NULL,
    --created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    --updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    --INDEX idx_email (email),
    --INDEX idx_is_active (is_active)
    FOREIGN KEY (account_level) REFERENCES administrators(id) ON DELETE CASCADE,
);

-- Table des administrateurs (étend la table users)
CREATE TABLE IF NOT EXISTS administrators (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role ENUM('user' , 'admin' , 'super_admin') DEFAULT 'user',
    permissions JSON,
    --INDEX idx_role (role)
);

-- Ajout des rôles possibles
INSERT INTO administrators (role,permissions) VALUES
('user',{"can_manage_users": false,"can_manage_content": false,"can_access_admin_pages":false}),
('admin',{"can_manage_users": false,"can_manage_content": True,"can_access_admin_pages":True}),
('super_admin',{"can_manage_users": True,"can_manage_content": True,"can_access_admin_pages":True})

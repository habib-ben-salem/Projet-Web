-- Création de la base de données des utilisateurs
CREATE DATABASE IF NOT EXISTS users_accounts;
USE users_accounts;

-- Donner les permissions à php_docker sur cette base
GRANT ALL PRIVILEGES ON users_accounts.* TO 'php_docker'@'%';
FLUSH PRIVILEGES;

-- Table des administrateurs (doit être créée en premier car la table users la référence)
CREATE TABLE IF NOT EXISTS administrators (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role ENUM('user' , 'admin' , 'super_admin') DEFAULT 'user',
    permissions JSON
);

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
    FOREIGN KEY (account_level) REFERENCES administrators(id) ON DELETE CASCADE
);

-- Ajout des rôles possibles
INSERT INTO administrators (role, permissions) VALUES
('user', '{"can_manage_users": false, "can_manage_content": false, "can_access_admin_pages": false}'),
('admin', '{"can_manage_users": false, "can_manage_content": true, "can_access_admin_pages": true}'),
('super_admin', '{"can_manage_users": true, "can_manage_content": true, "can_access_admin_pages": true}');

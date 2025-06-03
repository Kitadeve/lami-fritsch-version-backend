-- Créer la base de données (optionnel)
CREATE DATABASE IF NOT EXISTS restaurant CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE restaurant;

-- Table des entrées
CREATE TABLE IF NOT EXISTS entrees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL UNIQUE
);

-- Table des plats
CREATE TABLE IF NOT EXISTS plats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL UNIQUE
);

-- Table des menus du jour
CREATE TABLE IF NOT EXISTS menus_du_jour (
    jour VARCHAR(20) PRIMARY KEY,
    entree_id INT NOT NULL,
    plat_id INT NOT NULL,
    FOREIGN KEY (entree_id) REFERENCES entrees(id),
    FOREIGN KEY (plat_id) REFERENCES plats(id)
);
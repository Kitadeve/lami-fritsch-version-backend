-- Création de la base de données (optionnel)
CREATE DATABASE IF NOT EXISTS restaurant CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE restaurant;

-- Table des admins
CREATE TABLE IF NOT EXISTS admins (
  id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

-- Ajout d'un admin par défaut (remplace le hash par le tien)
-- INSERT INTO admins (username, password) VALUES ('admin', 'COLLE_TON_HASH_ICI');

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

-- Table des suggestions
CREATE TABLE IF NOT EXISTS suggestions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL UNIQUE,
    prix DECIMAL(6,2) NOT NULL,
    visible TINYINT(1) NOT NULL DEFAULT 1
);

-- Table des menus du jour
CREATE TABLE IF NOT EXISTS menus_du_jour (
    jour VARCHAR(20) PRIMARY KEY,
    entree_id INT NOT NULL,
    plat_id INT NOT NULL,
    FOREIGN KEY (entree_id) REFERENCES entrees(id),
    FOREIGN KEY (plat_id) REFERENCES plats(id)
);

-- Table des messages de contact
CREATE TABLE IF NOT EXISTS contact_messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  lastName VARCHAR(50),
  firstName VARCHAR(50),
  email VARCHAR(100),
  phoneNumber VARCHAR(30),
  dateTime DATETIME,
  eventType VARCHAR(100),
  message TEXT,
  subscribeNews BOOLEAN
);

CREATe TABLE IF NOT EXISTS carte (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(255) NOT NULL UNIQUE,
  description TEXT,
  prix DECIMAL(6,2) NOT NULL,
  visible TINYINT(1) NOt NULL DEFAULT 1
  categorie VARCHAR(50) ENUM (plats, tartes_flambees, desserts) NOT NULL DEFAULT "plats";
  ordre INT NOT NULL DEFAULT 0;
)
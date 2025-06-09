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

drop table if exists carte;

drop table if exists carte_descriptions;

CREATE TABLE IF NOT EXISTS carte (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(255) NOT NULL UNIQUE,
  prix DECIMAL(6,2) NOT NULL,
  visible TINYINT(1) NOT NULL DEFAULT 1,
  categorie ENUM('plats', 'tartes_flambees', 'desserts') NOT NULL DEFAULT 'plats',
  ordre INT NOT NULL DEFAULT 0
);


CREATE TABLE IF NOT EXISTS carte_descriptions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  carte_id INT NOT NULL,
  description VARCHAR(255) NOT NULL,
  FOREIGN KEY (carte_id) REFERENCES carte(id) ON DELETE CASCADE

);


INSERT INTO carte (nom, categorie, prix, visible)
VALUES
  ('Saumon fumé par nos soins', 'plats', 13.50, 1),
  ('Kaesknepfle à la crème', 'plats', 16.00, 1),
  ('Kaesknepfle à la paysanne', 'plats', 16.90, 1),
  ('Joues de porc braisées au pinot noir', 'plats', 16.50, 1),
  ('Waedele braisé à la bière', 'plats', 18.90, 1),
  ('Rognons de veaux à la moutarde', 'plats', 19.90, 1),
  ('Bouchées à la reine', 'plats', 18.50, 1),
  ('Cordon bleu de veau', 'plats', 22.00, 1),
  ('Cordon bleu de veau munster', 'plats', 24.00, 1),
  ('Bibeleskaes maison', 'plats', 22.00, 1),

  ('Tradition', 'tartes_flambees', 9.50, 1),
  ('Gratinée', 'tartes_flambees', 10.50, 1),
  ('Champignons', 'tartes_flambees', 10.50, 1),
  ('Champignons gratinée', 'tartes_flambees', 11.50, 1),
  ('Munster', 'tartes_flambees', 12.00, 1),
  ('Pommes', 'tartes_flambees', 12.00, 1),

  ('Tarte du moment', 'desserts', 5.20, 1),
  ('Crème brulée à la fève de tonka', 'desserts', 6.10, 1),
  ('Vacherin glacé', 'desserts', 6.80, 1),
  ('Kougelhopf glacé maison au kirsch', 'desserts', 6.50, 1)
;

INSERT INTO carte_descriptions (carte_id, description)
VALUES
  ((SELECT id FROM carte WHERE nom='Saumon fumé par nos soins'), 'Toast de pain de campagne, crème de raifort'),
  ((SELECT id FROM carte WHERE nom='Kaesknepfle à la crème'), 'ciboulette'),
  ((SELECT id FROM carte WHERE nom='Kaesknepfle à la paysanne'), 'Croûtons, lardons, champignons'),
  ((SELECT id FROM carte WHERE nom='Cordon bleu de veau'), 'Sauce crème'),
  ((SELECT id FROM carte WHERE nom='Cordon bleu de veau munster'), 'Sauce crèem'),
  ((SELECT id FROM carte WHERE nom='Bibeleskaes maison'), 'Supplément jambon forêt noir : 3€ | saumon fumé maison : 4,50€ | munster : 3€'),
  ((SELECT id FROM carte WHERE nom='Tradition'), 'Crème, oignons, lardons'),
  ((SELECT id FROM carte WHERE nom='Gratinée'), 'Crème, oignons, lardons, emmental'),
  ((SELECT id FROM carte WHERE nom='Champignons'), 'Crème, oignons, lardons, champignons'),
  ((SELECT id FROM carte WHERE nom='Champignons gratinée'), 'Crème, oignons, lardons, champignons, emmental'),
  ((SELECT id FROM carte WHERE nom='Munster'), 'Crème, oignons, lardons, munster'),
  ((SELECT id FROM carte WHERE nom='Pommes'), 'Canelle, flambée au calvados')
;
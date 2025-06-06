CREATE TABLE if not exists suggestions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL Unique,
    prix DECIMAL(6,2) NOT NULL
);
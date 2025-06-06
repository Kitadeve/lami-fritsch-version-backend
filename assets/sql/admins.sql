CREATE TABLE if not exists admins (
  id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

INSERT INTO admins (username, password) VALUES ('admin', 'COLLE_TON_HASH_ICI');


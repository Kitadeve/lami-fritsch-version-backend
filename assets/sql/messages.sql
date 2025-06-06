CREATE TABLE if NOT exists contact_messages (
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
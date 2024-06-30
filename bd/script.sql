create database bd2_adoteUmAmigo;
use bd2_adoteUmAmigo;
       -- drop database bd2_adoteUmAmigo;
CREATE TABLE users (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    lastname VARCHAR(100),
    email VARCHAR(200),
    password VARCHAR(200),
    cidade VARCHAR(200),
    estado VARCHAR(200),
    isAdmin TINYINT(1) DEFAULT 0,
    image VARCHAR(200),
    token VARCHAR(200),
    whatsapp VARCHAR(45),
    bio TEXT
);
select * from pets;
select * from users;
UPDATE users SET isAdmin = 1 WHERE id = 1;
CREATE TABLE pets (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    description TEXT,
    image VARCHAR(200),
    sex VARCHAR(45),
    pet VARCHAR(45),
    size VARCHAR(45),
    users_id INT(11) UNSIGNED,
    FOREIGN KEY(users_id) REFERENCES users(id)
);

CREATE TABLE comments(
   id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
   comment TEXT,
   users_id INT(11) UNSIGNED,
   pets_id INT(11) UNSIGNED,
   FOREIGN KEY(users_id) REFERENCES users(id),
   FOREIGN KEY(pets_id) REFERENCES pets(id)
);
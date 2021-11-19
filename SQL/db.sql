DROP DATABASE IF EXISTS Resto;
CREATE DATABASE Resto
CHARACTER
SET utf8mb4
COLLATE utf8mb4_unicode_ci;
USE Resto;

/* Global */

CREATE TABLE global_options(
  id INT(3) NOT NULL AUTO_INCREMENT,
  name VARCHAR (255) NOT NULL,
  url VARCHAR (255) NOT NULL,
  maintenance INT (3) NOT NULL,
  coming_soon INT (3) NOT NULL,
  PRIMARY KEY (id)
)ENGINE=INNODB;

/* team */

CREATE TABLE team_photo
(
id_photo INT(3) NOT NULL AUTO_INCREMENT,
 photo VARCHAR (255) NOT NULL,
 PRIMARY KEY (id_photo)
)ENGINE=INNODB;


CREATE TABLE team
(
id_team_member INT(3) NOT NULL AUTO_INCREMENT,
civilite INT (3) NOT NULL,
username VARCHAR (255) NOT NULL,
nom VARCHAR (255) NOT NULL,
prenom VARCHAR (255) NOT NULL,
email VARCHAR (50) NOT NULL,
password VARCHAR (60) NOT NULL,
photo_id INT (3) DEFAULT NULL,
statut INT (3) NOT NULL,
date_enregistrement DATETIME NOT NULL,
last_login DATETIME DEFAULT NULL,
confirmation TINYINT DEFAULT NULL,
token VARCHAR (255) DEFAULT NULL,
name VARCHAR (255) NOT NULL,
  PRIMARY KEY (id_team_member),
      CONSTRAINT fk_team_member_photo
      FOREIGN KEY (photo_id)
      REFERENCES  team_photo(id_photo)
      ON DELETE SET NULL
)ENGINE=INNODB;
DROP DATABASE IF EXISTS Resto;
CREATE DATABASE Resto
CHARACTER
SET utf8mb4
COLLATE utf8mb4_unicode_ci;
USE Resto;

/* Global */

CREATE TABLE resto_photo_categorie
(
id_photo INT(3) NOT NULL AUTO_INCREMENT,
 original VARCHAR (255) NOT NULL,
 webp VARCHAR (255) NOT NULL,
 PRIMARY KEY (id_photo)
)ENGINE=INNODB;

CREATE TABLE resto_categorie
(
id INT(3) NOT NULL AUTO_INCREMENT,
titre VARCHAR (255) NOT NULL,
photo_id INT (3) DEFAULT NULL,
 PRIMARY KEY (id),
 CONSTRAINT fk_resto_categorie_photo
      FOREIGN KEY (photo_id)
      REFERENCES  resto_photo_categorie(id_photo)
      ON DELETE SET NULL
)ENGINE=INNODB;

CREATE TABLE global_options(
  id INT(3) NOT NULL AUTO_INCREMENT,
  url VARCHAR (255) NOT NULL,
  version VARCHAR (15) NOT NULL,
  maintenance INT (3) NOT NULL,
  coming_soon INT (3) NOT NULL,
  PRIMARY KEY (id)

)ENGINE=INNODB;


CREATE TABLE resto_infos(
  id INT(3) NOT NULL AUTO_INCREMENT,
  name VARCHAR (255) NOT NULL,
  type INT (3) NOT NULL,
  adresse VARCHAR (255) NOT NULL,
  est_halal  TINYINT NOT NULL,
  est_vege  TINYINT NOT NULL,
  est_casher  TINYINT NOT NULL,
  insta TEXT DEFAULT NULL,
  facebook TEXT DEFAULT NULL,
  twitter TEXT DEFAULT NULL,
  PRIMARY KEY (id)
)ENGINE=INNODB;


CREATE TABLE options(
id INT(3) NOT NULL AUTO_INCREMENT,
show_signature TINYINT NOT NULL,
show_cat_description TINYINT NOT NULL,
show_cat_photo TINYINT NOT NULL,
show_cat_pieces TINYINT NOT NULL,
show_sous_cat TINYINT NOT NULL,
show_cat_stats TINYINT NOT NULL,
show_plat_photo TINYINT NOT NULL,
show_plat_en_avant TINYINT NOT NULL,
show_plat_stats TINYINT NOT NULL,
show_cat_drink_description TINYINT NOT NULL,
show_cat_drink_photo TINYINT NOT NULL,
show_drink_sous_cat TINYINT NOT NULL,
show_sub_cat_drink_photo TINYINT NOT NULL,
show_sub_cat_drink_description TINYINT NOT NULL,
show_drink_photo TINYINT NOT NULL,
show_drinks_en_avant TINYINT NOT NULL,
show_drinks_stats TINYINT NOT NULL,
PRIMARY KEY (id)
)ENGINE=INNODB;

/* team */

CREATE TABLE team_photo
(
id_photo INT(3) NOT NULL AUTO_INCREMENT,
 img__jpeg VARCHAR (255) NOT NULL,
 img__webp VARCHAR (255) NOT NULL,
 original VARCHAR (255) NOT NULL,
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
  PRIMARY KEY (id_team_member),
      CONSTRAINT fk_team_member_photo
      FOREIGN KEY (photo_id)
      REFERENCES  team_photo(id_photo)
      ON DELETE SET NULL
)ENGINE=INNODB;


/* Journal */

CREATE TABLE journal
(
id INT(3) NOT NULL AUTO_INCREMENT,
 titre VARCHAR (255) NOT NULL,
 member_id INT (3) DEFAULT NULL,
 contenu TEXT DEFAULT NULL,
 statut INT (3) NOT NULL,
 date_enregistrement DATETIME NOT NULL,
 PRIMARY KEY (id),
  CONSTRAINT fk_team_member_journal
      FOREIGN KEY (member_id)
      REFERENCES  team(id_team_member)
      ON DELETE SET NULL
)ENGINE=INNODB;

CREATE TABLE recuperation
(
    id INT(3)NOT NULL AUTO_INCREMENT,
    email varchar(255),
    code INT(11),
    confirm TINYINT,
    PRIMARY KEY (id)
)ENGINE=INNODB;


/* Plats */

CREATE TABLE plats_photo
(
id_photo INT(3) NOT NULL AUTO_INCREMENT,
 img__jpeg VARCHAR (255) NOT NULL,
 img__webp VARCHAR (255) NOT NULL,
 original VARCHAR (255) NOT NULL,
 PRIMARY KEY (id_photo)
)ENGINE=INNODB;

CREATE TABLE plats_categories
(
id INT(3) NOT NULL AUTO_INCREMENT,
titre VARCHAR (255) NOT NULL,
description TEXT DEFAULT NULL,
photo_id INT (3) DEFAULT NULL,
author_id INT(3) DEFAULT NULL,
pieces INT (2) DEFAULT NULL,
position INT (2) DEFAULT NULL,
est_publie TINYINT NOT NULL,
date_enregistrement DATETIME NOT NULL,
 PRIMARY KEY (id),
 CONSTRAINT fk_plats_categories_photo
      FOREIGN KEY (photo_id)
      REFERENCES  plats_photo(id_photo)
      ON DELETE SET NULL,
  CONSTRAINT fk_plats_categories_team
      FOREIGN KEY (author_id)
      REFERENCES  team(id_team_member)
      ON DELETE SET NULL
)ENGINE=INNODB;


CREATE TABLE plats_sous_categories
(
  id INT(3)NOT NULL AUTO_INCREMENT,
  titre VARCHAR (255) NOT NULL,
  description TEXT DEFAULT NULL,
  pieces INT (2) DEFAULT NULL, 
  photo_id INT (3) DEFAULT NULL,
  cat_id INT(3) DEFAULT NULL,
  author_id INT(3) DEFAULT NULL,
  est_publie TINYINT NOT NULL,
  position INT (2) DEFAULT NULL,
  date_enregistrement DATETIME NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_plats_categories_souscat
      FOREIGN KEY  (cat_id)
      REFERENCES  plats_categories(id)
      ON DELETE SET NULL,
    CONSTRAINT fk_plats_sous_categories_photo
      FOREIGN KEY (photo_id)
      REFERENCES  plats_photo(id_photo)
      ON DELETE SET NULL,
    CONSTRAINT fk_plats_sous_categories_team
      FOREIGN KEY (author_id)
      REFERENCES  team(id_team_member)
      ON DELETE SET NULL
)ENGINE=INNODB;

CREATE TABLE plats
(
  id INT(3)NOT NULL AUTO_INCREMENT,
  titre VARCHAR (255) NOT NULL,
  author_id INT(3) DEFAULT NULL,
  prix decimal(10,2) NOT NULL,
  description TEXT DEFAULT NULL,
  quantite INT(5) DEFAULT NULL,
  est_halal  TINYINT NOT NULL,
  est_vege  TINYINT NOT NULL,
  est_vegan  TINYINT NOT NULL,
  est_casher  TINYINT NOT NULL,
  est_epice  TINYINT NOT NULL,
  epice_level INT(3) DEFAULT NULL,
  cat_id INT(3) DEFAULT NULL,
  souscat_id INT(3) DEFAULT NULL,
  have_allergenes TINYINT NOT NULL,
  photo_id INT (3) DEFAULT NULL,
  est_en_avant TINYINT NOT NULL,
  est_nouveau TINYINT NOT NULL,
  est_publie TINYINT NOT NULL,
  position INT (2) DEFAULT NULL,
  date_enregistrement DATETIME NOT NULL,
  date_modification DATETIME DEFAULT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_plats_team
      FOREIGN KEY (author_id)
      REFERENCES  team(id_team_member)
      ON DELETE SET NULL,
    CONSTRAINT fk_plats_categories
      FOREIGN KEY  (cat_id)
      REFERENCES  plats_categories(id)
      ON DELETE SET NULL,
    CONSTRAINT fk_plats_souscategories
      FOREIGN KEY  (souscat_id)
      REFERENCES  plats_sous_categories(id)
      ON DELETE SET NULL,
    CONSTRAINT fk_plats_photo
      FOREIGN KEY (photo_id)
      REFERENCES  plats_photo(id_photo)
      ON DELETE SET NULL
)ENGINE=INNODB;

CREATE TABLE allergenes(
  id INT(3) NOT NULL AUTO_INCREMENT,
  titre VARCHAR(255) NOT NULL,
  description TEXT DEFAULT NULL,
  exclusions TEXT DEFAULT NULL,
  date_enregistrement DATETIME NOT NULL,
  PRIMARY KEY (id)
)ENGINE=InnoDB;

CREATE TABLE plats_allergenes_liaison(
 id INT(3)NOT NULL AUTO_INCREMENT,
 plat_id INT(3) DEFAULT NULL,
 allergene_id INT(3) DEFAULT NULL,
  PRIMARY KEY (id),
    CONSTRAINT fk_plats_liaison
      FOREIGN KEY  (plat_id)
      REFERENCES  plats(id)
      ON DELETE CASCADE,
    CONSTRAINT fk_allergenes_liaison
      FOREIGN KEY (allergene_id)
      REFERENCES  allergenes(id)
      ON DELETE CASCADE
)

/* Boissons */
CREATE TABLE boissons_categories
(
id INT(3) NOT NULL AUTO_INCREMENT,
titre VARCHAR (255) NOT NULL,
description TEXT DEFAULT NULL,
photo_id INT (3) DEFAULT NULL,
author_id INT(3) DEFAULT NULL,
position INT (2) DEFAULT NULL,
est_publie TINYINT NOT NULL,
date_enregistrement DATETIME NOT NULL,
 PRIMARY KEY (id),
 CONSTRAINT fk_boissons_categories_photo
      FOREIGN KEY (photo_id)
      REFERENCES  plats_photo(id_photo)
      ON DELETE SET NULL,
  CONSTRAINT fk_boissons_categories_team
      FOREIGN KEY (author_id)
      REFERENCES  team(id_team_member)
      ON DELETE SET NULL
)ENGINE=INNODB;


CREATE TABLE boissons_sous_categories
(
  id INT(3)NOT NULL AUTO_INCREMENT,
  titre VARCHAR (255) NOT NULL,
  description TEXT DEFAULT NULL,
  photo_id INT (3) DEFAULT NULL,
  cat_id INT(3) DEFAULT NULL,
  author_id INT(3) DEFAULT NULL,
  est_publie TINYINT NOT NULL,
  position INT (2) DEFAULT NULL,
  date_enregistrement DATETIME NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_boissons_categories_souscat
      FOREIGN KEY  (cat_id)
      REFERENCES  boissons_categories(id)
      ON DELETE SET NULL,
    CONSTRAINT fk_boissons_sous_categories_photo
      FOREIGN KEY (photo_id)
      REFERENCES  plats_photo(id_photo)
      ON DELETE SET NULL,
    CONSTRAINT fk_boissons_sous_categories_team
      FOREIGN KEY (author_id)
      REFERENCES  team(id_team_member)
      ON DELETE SET NULL
)ENGINE=INNODB;


CREATE TABLE boissons
(
  id INT(3)NOT NULL AUTO_INCREMENT,
  titre VARCHAR (255) NOT NULL,
  author_id INT(3) DEFAULT NULL,
  prix decimal(10,2) NOT NULL,
  description TEXT DEFAULT NULL,
  cat_id INT(3) DEFAULT NULL,
  souscat_id INT(3) DEFAULT NULL,
  have_allergenes TINYINT NOT NULL,
  photo_id INT (3) DEFAULT NULL,
  est_en_avant TINYINT NOT NULL,
  est_nouveau TINYINT NOT NULL,
  est_publie TINYINT NOT NULL,
  position INT (2) DEFAULT NULL,
  date_enregistrement DATETIME NOT NULL,
  date_modification DATETIME DEFAULT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_boissons_team
      FOREIGN KEY (author_id)
      REFERENCES  team(id_team_member)
      ON DELETE SET NULL,
    CONSTRAINT fk_boissons_categories
      FOREIGN KEY  (cat_id)
      REFERENCES  boissons_categories(id)
      ON DELETE SET NULL,
    CONSTRAINT fk_boissons_souscategories
      FOREIGN KEY  (souscat_id)
      REFERENCES  boissons_sous_categories(id)
      ON DELETE SET NULL,
    CONSTRAINT fk_boissons_photo
      FOREIGN KEY (photo_id)
      REFERENCES  plats_photo(id_photo)
      ON DELETE SET NULL
)ENGINE=INNODB;

CREATE TABLE boissons_allergenes_liaison(
 id INT(3)NOT NULL AUTO_INCREMENT,
 boisson_id INT(3) DEFAULT NULL,
 allergene_id INT(3) DEFAULT NULL,
  PRIMARY KEY (id),
    CONSTRAINT fk_boisson_liaison
      FOREIGN KEY  (boisson_id)
      REFERENCES  boissons(id)
      ON DELETE CASCADE,
    CONSTRAINT fk_allergenes_liaison_boissons
      FOREIGN KEY (allergene_id)
      REFERENCES  allergenes(id)
      ON DELETE CASCADE
)

/* Stats */
CREATE TABLE plats_stats_categories
(
  id INT(3)NOT NULL AUTO_INCREMENT,
  nb INT,
  cat_id INT(3) DEFAULT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_plats_categories_stats
      FOREIGN KEY  (cat_id)
      REFERENCES  plats_categories(id)
      ON DELETE SET NULL
)ENGINE=INNODB;


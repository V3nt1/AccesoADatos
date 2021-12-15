DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS developers_groups;
DROP TABLE IF EXISTS developers;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS groups;
DROP TABLE IF EXISTS engines_versions;
DROP TABLE IF EXISTS engines;

CREATE TABLE users (
id_user INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
user VARCHAR(16) NOT NULL, 
password CHAR(32) NOT NULL,
email VARCHAR(32) NOT NULL,
name VARCHAR(24) NOT NULL,
surname VARCHAR(48) NOT NULL,
birthdate DATE NOT NULL,
registered DATETIME NOT NULL DEFAULT now(),
admin BOOLEAN NOT NULL DEFAULT false 
);

CREATE TABLE developers (
id_developer INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
name VARCHAR(32) NOT NULL,
surname VARCHAR(48) NOT NULL,
email VARCHAR(32) NOT NULL,
website VARCHAR(255) NOT NULL,
birthdate DATE NOT NULL
);

CREATE TABLE groups (
id_group INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
`group` VARCHAR(32) NOT NULL,
course INT NOT NULL,
jam_year YEAR NOT NULL,
mark FLOAT NOT NULL
);

CREATE TABLE developers_groups (
id_developer_group INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
id_developer INT UNSIGNED NOT NULL,
id_group INT UNSIGNED NOT NULL,
FOREIGN KEY (id_developer) REFERENCES developers(id_developer),
FOREIGN KEY (id_group) REFERENCES groups(id_group)
);

CREATE TABLE engines (
id_engine INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
engine VARCHAR(32) NOT NULL
);

CREATE TABLE engines_versions (
id_engine_version INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
version VARCHAR(24) NOT NULL,
id_engine INT UNSIGNED NOT NULL,
FOREIGN KEY (id_engine) REFERENCES engines(id_engine)
);

CREATE TABLE products (
id_product INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
product VARCHAR(128) NOT NULL,
description TEXT NOT NULL,
price DECIMAL(6,2) NOT NULL,
reference VARCHAR(8) NOT NULL,
discount INT NOT NULL,
units_sold INT UNSIGNED NOT NULL,
website VARCHAR(255) NOT NULL,
`size` INT NOT NULL,
duration INT NOT NULL,
release_date DATE NOT NULL,
id_group INT UNSIGNED NOT NULL,
id_engine_version INT UNSIGNED NOT NULL,
FOREIGN KEY (id_group) REFERENCES groups(id_group),
FOREIGN KEY (id_engine_version) REFERENCES engines_versions(id_engine_version)
);

INSERT INTO users (user, password, email, name, surname, birthdate, admin)
VALUES ('root', md5('enti'), 'root@root.com', 'Diego', 'Mena', '0000-00-00', true);

INSERT INTO engines (engine)
VALUES ('Unity'), ('Unreal');

INSERT INTO engines_versions (version, id_engine)
VALUES ('2019', 1), ('4',2);

INSERT INTO developers (name, surname, email, website, birthdate)
VALUES ('Diego', 'Mena González', 'diego.mena@enti.cat', 'diegomena.com', '2001-07-07');

INSERT INTO groups (`group`, course, jam_year, mark)
VALUES ('Goticulones', 2, 2021, 7.07);

INSERT INTO developers_groups (id_developer, id_group)
VALUES (1,1);

INSERT INTO products (product, description, price, reference, discount, units_sold, website, `size`, duration, release_date, id_group, id_engine_version)
VALUES ('Seismic Shadows', 'El mejor juego de DAM-VIOD', 000009.99, 'nose',10, 102332,'seismicthebest.com', 67, 123, '2021-05-23',1,1);

INSERT INTO products (product, description, price, reference, discount, units_sold, website, `size`, duration, release_date, id_group, id_engine_version)
VALUES ('Seismic Shadows 2', 'El mejor juego de DAM-VIOD', 000009.99, 'nose',10, 102332,'seismicthebest.com', 67, 123, '2021-05-23',1,1);

INSERT INTO products (product, description, price, reference, discount, units_sold, website, `size`, duration, release_date, id_group, id_engine_version)
VALUES ('Seismic Shadows 3', 'El mejor juego de DAM-VIOD', 000009.99, 'nose',10, 102332,'seismicthebest.com', 67, 123, '2021-05-23',1,2);

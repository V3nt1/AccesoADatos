
DROP TABLE IF EXISTS users_products;
CREATE TABLE users_products (
id_user_product INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
id_user INT UNSIGNED NOT NULL,
id_product INT UNSIGNED NOT NULL,
FOREIGN KEY (id_user) REFERENCES users(id_user),
FOREIGN KEY (id_product) REFERENCES products(id_product)
);

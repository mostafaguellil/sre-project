CREATE DATABASE IF NOT EXISTS `sre-database`;
USE `sre-database`;

CREATE TABLE IF NOT EXISTS `sre-table` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

INSERT INTO `sre-table` (name) VALUES ('Mostafa'), ('Louise'), ('Thomas');


/*-----------------------------------------------------------------
----------------------DATABASE CREATION--------------------------
-----------------------------------------------------------------*/

CREATE DATABASE IF NOT EXISTS `i_spy_database`
DEFAULT CHARACTER SET utf8mb4;

USE `i_spy_database`;

/*---------------------------------------------------------------
----------------------TABLES CREATION--------------------------
---------------------------------------------------------------*/

CREATE TABLE IF NOT EXISTS `phone` (
       `phone_id` INT NOT NULL AUTO_INCREMENT,
       `login` VARCHAR(25) NOT NULL,
       `password` VARCHAR(25) NOT NULL,
       `phone_number` VARCHAR(25),
       `phone_model` VARCHAR(25),
       CONSTRAINT phone_pk PRIMARY KEY (`phone_id`),
       CONSTRAINT is_unique_login UNIQUE (`login`),
       CONSTRAINT is_unique_password UNIQUE (`password`))
ENGINE = MyISAM CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;

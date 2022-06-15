CREATE DATABASE spedi_warehouse;

USE spedi_warehouse;

CREATE TABLE account_tb (
	account_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL,
    user_type SMALLINT
);

CREATE TABLE inventory_tb (
	inventory_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    item_type VARCHAR(50),
    item_name VARCHAR(50) NOT NULL,
    item_description VARCHAR(100),
    unit VARCHAR(10) NOT NULL,
    unit_cost DOUBLE NOT NULL,
    remarks VARCHAR(100),
    project_id INT NOT NULL
);

CREATE TABLE count_tb (
	count_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    quantity INT DEFAULT 0,
    issued INT DEFAULT 0,
    returned INT DEFAULT 0,
    balance INT DEFAULT 0,
    date_added VARCHAR(20),
    date_issued VARCHAR(20),
    date_returned VARCHAR(20),
    inventory_id INT NOT NULL
);

CREATE TABLE movement_tb (
	movement_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    quantity INT DEFAULT 0,
    issued INT DEFAULT 0,
    returned INT DEFAULT 0,
    balance INT DEFAULT 0,
    date_added VARCHAR(20),
    date_issued VARCHAR(20),
    date_returned VARCHAR(20),
    date_movement VARCHAR(20),
    to_project_office VARCHAR(50),
    inventory_id INT NOT NULL
);

CREATE TABLE project_office_tb (
	project_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    project_office_name VARCHAR(50) NOT NULL,
    location VARCHAR(50)
);

CREATE TABLE recent_tb (
	recent_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    inventory_id INT NOT NULL,
    type SMALLINT NOT NULL
);

INSERT INTO account_tb (username, password, user_type) VALUES (`superadmin`, `superadmin`, 1);
INSERT INTO account_tb (username, password, user_type) VALUES (`admin`, `admin`, 2);
INSERT INTO account_tb (username, password, user_type) VALUES (`materialcontrol`, `materialcontrol`, 3);
-- ---------------------------------------
-- BAZA DANYCH - Quiz
-- Autor: Paweł Szóstkiewicz
-- ---------------------------------------


-- ---------------------------------------
-- Baza danych: quiz
-- ---------------------------------------
CREATE DATABASE IF NOT EXISTS quiz DEFAULT CHARACTER SET utf8 COLLATE utf8_polish_ci;
USE quiz;

-- ---------------------------------------
-- Table groups
-- ---------------------------------------
CREATE TABLE IF NOT EXISTS 	groups (
  ID			    INT(11) NOT NULL AUTO_INCREMENT,
  name 			  VARCHAR(30) NOT NULL,
  permission  TEXT NULL,
  PRIMARY KEY (ID)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- ---------------------------------------
-- Table messagesSended
-- ---------------------------------------
CREATE TABLE IF NOT EXISTS messagesSended (
  ID 			    INT(11) NOT NULL AUTO_INCREMENT,
  receiver		VARCHAR(100) NOT NULL,
  receiverCC 	VARCHAR(100) NULL,
  receiverBCC VARCHAR(100) NULL,
  sender 		  VARCHAR(100) NOT NULL,
  header		  TEXT NOT NULL,
  subject 		VARCHAR(120) NOT NULL,
  body			  TEXT NOT NULL,
  data_send 	DATETIME NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;


-- ---------------------------------------
-- Table question
-- ---------------------------------------
CREATE TABLE IF NOT EXISTS question (
  ID				      INT(11) NOT NULL AUTO_INCREMENT,
  Question			  VARCHAR(255) NOT NULL,
  Correct_answer	VARCHAR(120) NOT NULL,
  Wrong_answer_1	VARCHAR(120) NOT NULL,
  Wrong_answer_2	VARCHAR(120) NOT NULL,
  Wrong_answer_3	VARCHAR(120) NOT NULL,
  PRIMARY KEY (ID)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;


-- ---------------------------------------
-- Table users
-- ---------------------------------------
CREATE TABLE IF NOT EXISTS users (
  ID							              INT(11) NOT NULL AUTO_INCREMENT,
  username					            VARCHAR(30) NOT NULL,
  password					            VARCHAR(100) NOT NULL,
  salt							            VARCHAR(100) NOT NULL,
  password_date			            DATETIME NOT NULL,
  name							            VARCHAR(30) NULL,
  surname						            VARCHAR(35) NULL,
  email							            VARCHAR(40) NULL,
  number_phone			            VARCHAR(20) NULL,
  date_birdth				            DATE NULL,
  city							            VARCHAR(50) NULL,
  street						            VARCHAR(50) NULL,
  no_house					            VARCHAR(10) NULL,
  no_flat						            VARCHAR(10) NULL,
  joined						            DATETIME NOT NULL,
  `group`						            INT(11) NOT NULL,
  consent_rodo	 				        TINYINT(1) NOT NULL,
  password_old_1 				        VARCHAR(100) NULL,
  date_changed_password_old_1 	DATETIME NOT NULL,
  password_old_2 				        VARCHAR(100) NULL,
  date_changed_password_old_2 	DATETIME NULL,
  password_old_3 				        VARCHAR(100) NULL,
  date_changed_password_old_3 	DATETIME NOT NULL,
  last_success_logged 			    DATETIME NOT NULL,
  last_failed_logged 			      DATETIME NOT NULL,
  counter_success_logged 		    INT(6) NOT NULL,
  counter_failed_logged 		    INT(6) NOT NULL,
  PRIMARY KEY (ID)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- ---------------------------------------
-- Table ticketStatus
-- ---------------------------------------
CREATE TABLE IF NOT EXISTS ticketStatus (
	ID 				    INT(11) NOT NULL AUTO_INCREMENT,
	name_status 	VARCHAR(30),
	PRIMARY KEY (ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;


-- ---------------------------------------
-- Table ticketPriority
-- ---------------------------------------
CREATE TABLE IF NOT EXISTS ticketPriority (
	ID 				      INT(11) NOT NULL AUTO_INCREMENT,
	name_priority 	VARCHAR(20) NOT NULL,
	PRIMARY KEY (ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;


-- ---------------------------------------
-- Table ticketGroup
-- ---------------------------------------
CREATE TABLE IF NOT EXISTS ticketQueue (
	ID 			INT(11) NOT NULL AUTO_INCREMENT,
	queue	 	VARCHAR(30) NOT NULL,
	PRIMARY KEY (ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;


-- ---------------------------------------
-- Table ticket
-- ---------------------------------------
CREATE TABLE IF NOT EXISTS ticket (
	ID 						          INT(11) NOT NULL AUTO_INCREMENT,
	subject 				        VARCHAR(100) NOT NULL,
	description 			      TEXT NOT NULL,
	id_declarant			      INT(5) NOT NULL,   -- person which send a ticket
	id_operator_ticket		  INT(5) NULL,	 -- person which realising executes a ticket
	id_ticketStatus			    INT(11) NOT NULL,
	id_ticketQueue			    INT(11) NOT NULL,
	id_ticketPriority		    INT(11) NOT NULL,
	date_create_ticket 		  DATETIME NOT NULL,
	date_acceptance_ticket 	DATETIME NULL,
	date_planned_ending 	  DATETIME NULL,
	date_ended_ticket		    DATETIME NULL,
	id_linked_ticket		    INT(11) NULL,
	id_linker				        INT(5) NULL,
	date_linked				      DATETIME NULL, 
	PRIMARY KEY (ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;


-- ---------------------------------------
-- Table updatedFiles
-- ---------------------------------------
CREATE TABLE IF NOT EXISTS updatedFiles (
  ID              INT(11) NOT NULL AUTO_INCREMENT,
  name            VARCHAR(50) NOT NULL,
  size            VARCHAR(15) NULL,
  type            VARCHAR(20) NULL,
  error_adding    VARCHAR(5) NULL,
  path            VARCHAR(100) NULL,
  date_added      DATETIME,
  id_ticket       INT(11) NULL,
  id_user         INT(11) NULL,
  user            VARCHAR(50) NULL,
  PRIMARY KEY (ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

DELIMITER $$

DROP SCHEMA IF EXISTS training_center $$
CREATE SCHEMA IF NOT EXISTS training_center DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci $$
USE training_center $$

CREATE  TABLE IF NOT EXISTS training_center.person (
  person_id INT(11) NOT NULL AUTO_INCREMENT ,
  first_name VARCHAR(45) NOT NULL ,
  last_name VARCHAR(45) NOT NULL ,
  address VARCHAR(45) NOT NULL ,
  zip_code VARCHAR(5) NOT NULL ,
  town VARCHAR(45) NOT NULL ,
  email VARCHAR(45) NOT NULL ,
  mobile_phone VARCHAR(10) NOT NULL ,
  phone VARCHAR(10) NULL DEFAULT NULL ,
  is_trainer TINYINT(1) NOT NULL DEFAULT false ,
  is_admin TINYINT(1) NOT NULL DEFAULT false ,
  password VARCHAR(45) NOT NULL ,
  picture_location VARCHAR(45) NULL DEFAULT NULL ,
  created_at DATETIME NOT NULL ,
  confirmed_at DATETIME NULL DEFAULT NULL ,
  confirmation_token VARCHAR(45) NULL DEFAULT NULL ,
  renew_password_token VARCHAR(45) NULL DEFAULT NULL ,
  PRIMARY KEY (person_id) ,
  UNIQUE INDEX un_person_email (email ASC) ,
  UNIQUE INDEX un_person_contact (first_name ASC, last_name ASC, address ASC, zip_code ASC, town ASC, mobile_phone ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci $$

CREATE  TABLE IF NOT EXISTS training_center.class (
  class_id INT(11) NOT NULL AUTO_INCREMENT ,
  name VARCHAR(45) NOT NULL ,
  PRIMARY KEY (class_id) ,
  UNIQUE INDEX un_class_name (name ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci $$

CREATE  TABLE IF NOT EXISTS training_center.class_member (
  person_id INT(11) NOT NULL ,
  class_id INT(11) NOT NULL ,
  INDEX fk_class_member_member (person_id ASC) ,
  INDEX fk_class_member_class (class_id ASC) ,
  PRIMARY KEY (person_id, class_id) ,
  CONSTRAINT fk_class_member_member
    FOREIGN KEY (person_id )
    REFERENCES training_center.person (person_id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_class_member_class
    FOREIGN KEY (class_id )
    REFERENCES training_center.class (class_id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci $$

CREATE  TABLE IF NOT EXISTS training_center.project (
  project_id INT(11) NOT NULL AUTO_INCREMENT ,
  owner_id INT(11) NOT NULL ,
  class_id INT(11) NOT NULL ,
  title VARCHAR(255) NOT NULL ,
  created_at DATETIME NOT NULL DEFAULT NOW() ,
  deadline DATETIME NOT NULL ,
  subject VARCHAR(1024) NOT NULL ,
  PRIMARY KEY (project_id) ,
  INDEX fk_project_member (owner_id ASC) ,
  INDEX fk_project_class (class_id ASC) ,
  CONSTRAINT fk_project_member
    FOREIGN KEY (owner_id )
    REFERENCES training_center.person (person_id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_project_class
    FOREIGN KEY (class_id )
    REFERENCES training_center.class (class_id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci $$


CREATE  TABLE IF NOT EXISTS training_center.team (
  team_id INT(11) NOT NULL AUTO_INCREMENT ,
  project_id INT(11) NOT NULL ,
  owner_id INT(11) NOT NULL ,
  created_at DATETIME NOT NULL ,
  summary VARCHAR(45) NULL DEFAULT NULL ,
  PRIMARY KEY (team_id) ,
  INDEX fk_team_project (project_id ASC) ,
  INDEX fk_team_member (owner_id ASC) ,
  CONSTRAINT fk_team_project
    FOREIGN KEY (project_id )
    REFERENCES training_center.project (project_id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_team_member
    FOREIGN KEY (owner_id )
    REFERENCES training_center.person (person_id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci $$



CREATE  TABLE IF NOT EXISTS training_center.document (

  document_id INT NOT NULL AUTO_INCREMENT ,
  author_id INT NOT NULL ,
  team_id INT NOT NULL ,
  location VARCHAR(45) NOT NULL ,
  created_at DATETIME NOT NULL ,
  updated_at DATETIME NULL ,
  content VARCHAR(45) NULL ,
  PRIMARY KEY (document_id) ,
  INDEX fk_document_member (author_id ASC) ,
  INDEX fk_document_team (team_id ASC) ,
  CONSTRAINT fk_document_member
    FOREIGN KEY (author_id )
    REFERENCES training_center.person (person_id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_document_team
    FOREIGN KEY (team_id )
    REFERENCES training_center.team (team_id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci $$

CREATE  TABLE IF NOT EXISTS training_center.team_member (
  team_id INT(11) NOT NULL ,
  student_id INT(11) NOT NULL ,
  INDEX fk_team_member_team (team_id ASC) ,
  INDEX fk_team_member_person (student_id ASC) ,
  PRIMARY KEY (team_id, student_id) ,
  CONSTRAINT fk_team_member_team
    FOREIGN KEY (team_id )
    REFERENCES training_center.team (team_id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_team_member_person
    FOREIGN KEY (student_id )
    REFERENCES training_center.person (person_id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci $$


CREATE TRIGGER `DeleteClassMember` BEFORE DELETE ON `class`
 FOR EACH ROW DELETE FROM `class_member` WHERE (`class_id` = OLD.`class_id`)
$$

CREATE TRIGGER `CreateAtDocument` BEFORE Insert ON `document`
 FOR EACH ROW SET new.`created_at` = NOW()
$$

CREATE TRIGGER `UpdateAtDocument` BEFORE Update ON `document`
 FOR EACH ROW SET new.`updated_at` = NOW()
$$

CREATE TRIGGER `CreateAtPerson` BEFORE INSERT ON `person`
 FOR EACH ROW SET new.`created_at` = NOW()
$$

CREATE TRIGGER `DeleteFromClass` BEFORE DELETE ON `person`
 FOR EACH ROW DELETE FROM `class_member` WHERE (`person_id` = OLD.`person_id`)
$$

CREATE TRIGGER `DeleteFromTeam` BEFORE DELETE ON `person`
 FOR EACH ROW DELETE FROM `Team_member` WHERE (`person_id` = OLD.`person_id`)
$$

CREATE TRIGGER `CreatedAt` BEFORE INSERT ON `project`
 FOR EACH ROW SET new.`created_at` = NOW()
$$

CREATE TRIGGER `CreationDate` BEFORE INSERT ON `team`
 FOR EACH ROW SET new.`created_at` = NOW()
$$

CREATE TRIGGER `DeleteMember` BEFORE DELETE ON `team`
 FOR EACH ROW DELETE FROM `team_member` WHERE (`team_id` = OLD.`team_id`)
$$




DROP PROCEDURE IF EXISTS training_center_reset $$

CREATE PROCEDURE training_center_reset()
BEGIN
	INSERT INTO person (person_id, first_name, last_name, address, zip_code, town, email, mobile_phone, is_trainer, is_admin, password, created_at) VALUES
	(1,'Achyuth','KP', 'Villejuif', '94800', 'Paris', 'achyuth@gmail.com', '0771665541', false, false, 'achyuth', '2017-07-22'),
	(2,'Tom','Riddle', 'England', '12345', 'Hogwarts', 'tomriddle@gmail.com', '0771665541', false, false, 'notmyname', '2017-07-22'),
	(3,'Albus','Dumbledore', 'England', '12345', 'Hogwarts', 'albusdumb@gmail.com', '0771665541', false, false, 'sherbetlemon', '2017-07-22'),
	(4,'Cassi','Wanner', 'Thailand', '54321', 'Bangkok', 'caswan@gmail.com', '0771665541', false, false, 'castiel', '2017-07-22'),
	(5,'Sanora','Heinecke', 'Kenya', '65432', 'Nairobi', 'sanecke@gmail.com', '0771665541', false, false, 'shinobi123', '2017-07-22'),
	(6,'Wilfredo','Eaton', 'China', '76543', 'Beijing', 'fred@gmail.com', '0771665541', true, false, 'startrek', '2017-07-22'),
	(7,'Nicky','Shedd', 'Japan', '87654', 'Yokohama', 'nick@gmail.com', '0771665541', false, false, 'notnickiminaj', '2017-07-22'),
	(8,'Mi','Dunne', 'Iran', '98765', 'Tehran', 'midunne@gmail.com', '1234567891', false, false, 'dunebuggie', '2017-07-22'),
	(9,'Florentina','Drey', 'Morocco', '23456', 'Casablanca', 'flodrey@gmail.com', '0771665541', false, false, 'florence', '2017-07-22'),
	(10,'Jay','Gibby', 'Brazil', '34567', 'Sao Paulo', 'jgib@gmail.com', '0771665541', false, false, 'jblspeakers', '2017-07-22'),
	(11,'Greg','Mayo', 'Colombia', '45678', 'Bogota', 'mayo@gmail.com', '0771665541', true, false, 'veryfunny', '2017-07-22'),
	(12,'Missy','Calvin', 'Germany', '56789', 'Berlin', 'missy@gmail.com', '0771665541', false, true, 'notthemaster', '2017-07-22') ;

	INSERT INTO class (class_id, name) VALUES
	(1, 'Software Security'),
	(2, 'Advanced Web Development') ;

	INSERT INTO project (project_id, owner_id, class_id, title, created_at, deadline, subject) VALUES
	(1, 10, 1, 'Security Project', '2017-07-10', '2017-10-14', 'Penetration Testing'),
	(2, 1, 2, 'Website Development', '2017-07-14', '2017-10-18', 'Test Results');

	INSERT INTO class_member (person_id, class_id) VALUES
	(10,1),
	(9,1),
	(8,1),
	(7,1),
	(6,1),
	(5,1),
	(4,2),
	(3,2),
	(2,2),
	(11,2),
	(12,2),
	(1,2) ;

	INSERT INTO team (team_id, project_id, owner_id, created_at, summary) VALUES
	(1,1,10,'2017-07-10','Random Summary'),
	(2,2,1,'2016-07-10','Yet another Random Summary') ;

	INSERT INTO document (document_id, author_id, team_id, location,content, created_at, updated_at) VALUES
	(1, 10, 1, 'Paris','Random Content', '2017-07-15', '2017-10-16') ;

	INSERT INTO team_member (team_id, student_id) VALUES
	(1,10),
	(1,9),
	(1,8),
	(1,7),
	(1,6),
	(1,5),
	(2,4),
	(2,3),
	(2,2),
	(2,1),
	(2,11),
	(2,12) ;

	COMMIT ;


END $$

CALL training_center_reset();
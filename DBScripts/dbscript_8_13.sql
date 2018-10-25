-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema raihn
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema raihn
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `raihn` DEFAULT CHARACTER SET latin1 ;
USE `raihn` ;

-- -----------------------------------------------------
-- Table `raihn`.`USERS`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `raihn`.`USERS` (
  `userID` TINYINT(4) NOT NULL,
  `userType` VARCHAR(25) CHARACTER SET 'latin1' NULL DEFAULT NULL,
  `username` VARCHAR(30) CHARACTER SET 'latin1' NULL DEFAULT NULL,
  `password` CHAR(40) CHARACTER SET 'latin1' NULL DEFAULT NULL,
  `salt` VARCHAR(100) NULL DEFAULT NULL,
  PRIMARY KEY (`userID`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `raihn`.`BUS_DRIVER`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `raihn`.`BUS_DRIVER` (
  `driverID` TINYINT(1) NOT NULL,
  `userID` TINYINT(4) NOT NULL,
  `name` VARCHAR(100) CHARACTER SET 'latin1' NULL DEFAULT NULL,
  `homePhone` VARCHAR(45) CHARACTER SET 'latin1' NULL DEFAULT NULL,
  `cellPhone` VARCHAR(45) NULL DEFAULT NULL,
  `email` VARCHAR(100) CHARACTER SET 'latin1' NULL DEFAULT NULL,
  `address` VARCHAR(100) NULL DEFAULT NULL,
  PRIMARY KEY (`driverID`, `userID`),
  INDEX `BUS_DRIVER_USERS_userID_idx` (`userID` ASC),
  CONSTRAINT `FK_BUS_DRIVER_USERS_userID`
    FOREIGN KEY (`userID`)
    REFERENCES `raihn`.`USERS` (`userID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `raihn`.`BUS_BLACKOUT`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `raihn`.`BUS_BLACKOUT` (
  `driverID` TINYINT(1) NOT NULL,
  `date` DATE NOT NULL,
  `timeOfDay` CHAR(2) CHARACTER SET 'latin1' NOT NULL,
  PRIMARY KEY (`driverID`, `date`, `timeOfDay`),
  CONSTRAINT `BUS_BLACKOUT_BUS_DRIVER_driverID`
    FOREIGN KEY (`driverID`)
    REFERENCES `raihn`.`BUS_DRIVER` (`driverID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `raihn`.`CONGREGATION`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `raihn`.`CONGREGATION` (
  `congID` TINYINT(1) NOT NULL,
  `congName` VARCHAR(100) CHARACTER SET 'latin1' NULL DEFAULT NULL,
  `congAddress` VARCHAR(100) CHARACTER SET 'latin1' NULL DEFAULT NULL,
  `comments` VARCHAR(255) CHARACTER SET 'latin1' NULL DEFAULT NULL,
  PRIMARY KEY (`congID`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `raihn`.`BUS_SCHEDULE`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `raihn`.`BUS_SCHEDULE` (
  `driverID` TINYINT(1) NOT NULL,
  `date` DATE NOT NULL,
  `timeOfDay` CHAR(2) CHARACTER SET 'latin1' NOT NULL,
  `role` VARCHAR(8) CHARACTER SET 'latin1' NOT NULL,
  `congID` TINYINT(1) NULL DEFAULT NULL,
  PRIMARY KEY (`driverID`, `date`, `timeOfDay`, `role`),
  INDEX `BUS_SCHEDULE_CONGREGATION_congID_idx` (`congID` ASC),
  CONSTRAINT `BUS_SCHEDULE_BUS_DRIVER_driverID`
    FOREIGN KEY (`driverID`)
    REFERENCES `raihn`.`BUS_DRIVER` (`driverID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `BUS_SCHEDULE_CONGREGATION_congID`
    FOREIGN KEY (`congID`)
    REFERENCES `raihn`.`CONGREGATION` (`congID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `raihn`.`DATE_RANGE`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `raihn`.`DATE_RANGE` (
  `weekNumber` TINYINT(1) NOT NULL,
  `startDate` DATE NOT NULL,
  `endDate` DATE NULL DEFAULT NULL,
  `holiday` TINYINT(1) NULL DEFAULT NULL,
  PRIMARY KEY (`weekNumber`, `startDate`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `raihn`.`CONGREGATION_BLACKOUT`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `raihn`.`CONGREGATION_BLACKOUT` (
  `congID` TINYINT(1) NOT NULL,
  `weekNumber` TINYINT(1) NOT NULL,
  `startDate` DATE NOT NULL,
  PRIMARY KEY (`congID`, `weekNumber`, `startDate`),
  INDEX `CONGREGATION_BLACKOUT_CONGREGATION_idx` (`congID` ASC),
  INDEX `fk_CONGREGATION_BLACKOUT_DATE_RANGE1_idx` (`weekNumber` ASC, `startDate` ASC),
  CONSTRAINT `CONGREGATION_BLACKOUT_DATE_RANGE_startDate_weekNumber`
    FOREIGN KEY (`weekNumber` , `startDate`)
    REFERENCES `raihn`.`DATE_RANGE` (`weekNumber` , `startDate`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `CONGREGATION_BLACKOUT_CONGREGATION_congID`
    FOREIGN KEY (`congID`)
    REFERENCES `raihn`.`CONGREGATION` (`congID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `raihn`.`CONGREGATION_COORDINATOR`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `raihn`.`CONGREGATION_COORDINATOR` (
  `congID` TINYINT(4) NOT NULL,
  `userID` TINYINT(4) NOT NULL,
  `coordinatorName` VARCHAR(100) NULL DEFAULT NULL,
  `coordinatorPhone` VARCHAR(20) NULL DEFAULT NULL,
  `coordinatorEmail` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`congID`, `userID`),
  INDEX `FK_CONGREGATION_COORDINATOR_USERS_userID_idx` (`userID` ASC),
  CONSTRAINT `FK_CONGREGATION_COORDINATOR_USERS_userID`
    FOREIGN KEY (`userID`)
    REFERENCES `raihn`.`USERS` (`userID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_CONGREGATION_COORDINATOR_CONGREGATION`
    FOREIGN KEY (`congID`)
    REFERENCES `raihn`.`CONGREGATION` (`congID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `raihn`.`ROTATION_DATE`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `raihn`.`ROTATION_DATE` (
  `rotationNumber` TINYINT(1) NOT NULL,
  `startDate` DATE NULL DEFAULT NULL,
  `endDate` DATE NULL DEFAULT NULL,
  PRIMARY KEY (`rotationNumber`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `raihn`.`CONGREGATION_SCHEDULE`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `raihn`.`CONGREGATION_SCHEDULE` (
  `rotationNumber` TINYINT(1) NOT NULL,
  `congID` TINYINT(1) NOT NULL,
  `weekNumber` TINYINT(1) NOT NULL,
  `startDate` DATE NOT NULL,
  PRIMARY KEY (`rotationNumber`, `congID`, `weekNumber`, `startDate`),
  INDEX `CONGREGATION_SCHEDULE_ROTATION_DATE_rotationNumber_idx` (`rotationNumber` ASC),
  INDEX `CONGREGATION_SCHEDULE_CONGREGATION_congID_idx` (`congID` ASC),
  INDEX `fk_CONGREGATION_SCHEDULE_DATE_RANGE1_idx` (`weekNumber` ASC, `startDate` ASC),
  INDEX `DATE_RANGE_startDate_idx` (`startDate` ASC),
  CONSTRAINT `CONGREGATION_SCHEDULE_DATE_RANGE_startDate_weekNumber`
    FOREIGN KEY (`weekNumber` , `startDate`)
    REFERENCES `raihn`.`DATE_RANGE` (`weekNumber` , `startDate`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `CONGREGATION_SCHEDULE_CONGREGATION_congID`
    FOREIGN KEY (`congID`)
    REFERENCES `raihn`.`CONGREGATION` (`congID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `CONGREGATION_SCHEDULE_ROTATION_DATE_rotationNumber`
    FOREIGN KEY (`rotationNumber`)
    REFERENCES `raihn`.`ROTATION_DATE` (`rotationNumber`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `raihn`.`LEGACY_HOST_BLACKOUT`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `raihn`.`LEGACY_HOST_BLACKOUT` (
  `congID` INT(11) NOT NULL,
  `startDate` DATE NOT NULL,
  `endDate` DATE NULL DEFAULT NULL,
  PRIMARY KEY (`congID`, `startDate`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

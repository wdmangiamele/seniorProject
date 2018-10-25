-- MySQL dump 10.13  Distrib 5.7.12, for Win64 (x86_64)
--
-- Host: localhost    Database: raihn
-- ------------------------------------------------------
-- Server version	5.7.16-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bus_blackout`
--

DROP TABLE IF EXISTS `bus_blackout`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bus_blackout` (
  `driverID` tinyint(1) NOT NULL,
  `date` date NOT NULL,
  `timeOfDay` char(2) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`driverID`,`date`,`timeOfDay`),
  CONSTRAINT `BUS_BLACKOUT_BUS_DRIVER_driverID` FOREIGN KEY (`driverID`) REFERENCES `bus_driver` (`driverID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bus_blackout`
--

LOCK TABLES `bus_blackout` WRITE;
/*!40000 ALTER TABLE `bus_blackout` DISABLE KEYS */;
INSERT INTO `bus_blackout` VALUES (1,'2018-09-03','AM'),(1,'2018-09-05','PM'),(1,'2018-09-06','AM'),(1,'2018-09-08','PM'),(1,'2018-09-11','AM'),(1,'2018-09-12','PM'),(1,'2018-09-18','AM'),(1,'2018-09-21','PM'),(1,'2018-09-26','AM'),(1,'2018-09-28','PM'),(2,'2018-09-02','AM'),(2,'2018-09-05','PM'),(2,'2018-09-07','AM'),(2,'2018-09-13','PM'),(2,'2018-09-17','AM'),(2,'2018-09-20','PM'),(2,'2018-09-22','AM'),(2,'2018-09-23','PM'),(2,'2018-09-25','AM'),(2,'2018-09-28','PM'),(3,'2018-09-01','AM'),(3,'2018-09-02','PM'),(3,'2018-09-05','AM'),(3,'2018-09-10','PM'),(3,'2018-09-14','AM'),(3,'2018-09-16','PM'),(3,'2018-09-22','AM'),(3,'2018-09-24','PM'),(3,'2018-09-27','AM'),(3,'2018-09-28','PM'),(4,'2018-09-03','AM'),(4,'2018-09-04','PM'),(4,'2018-09-05','AM'),(4,'2018-09-12','PM'),(4,'2018-09-14','AM'),(4,'2018-09-15','PM'),(4,'2018-09-17','AM'),(4,'2018-09-28','PM'),(4,'2018-09-29','AM'),(4,'2018-09-30','PM'),(5,'2018-09-01','AM'),(5,'2018-09-06','PM'),(5,'2018-09-09','AM'),(5,'2018-09-10','PM'),(5,'2018-09-14','AM'),(5,'2018-09-15','PM'),(5,'2018-09-17','AM'),(5,'2018-09-19','PM'),(5,'2018-09-23','AM'),(5,'2018-09-28','PM'),(6,'2018-09-02','AM'),(6,'2018-09-06','PM'),(6,'2018-09-07','AM'),(6,'2018-09-11','PM'),(6,'2018-09-14','AM'),(6,'2018-09-17','PM'),(6,'2018-09-18','AM'),(6,'2018-09-23','PM'),(6,'2018-09-24','AM'),(6,'2018-09-29','PM'),(7,'2018-09-04','AM'),(7,'2018-09-05','PM'),(7,'2018-09-09','AM'),(7,'2018-09-13','PM'),(7,'2018-09-14','AM'),(7,'2018-09-15','PM'),(7,'2018-09-16','AM'),(7,'2018-09-22','PM'),(7,'2018-09-23','AM'),(7,'2018-09-27','PM'),(8,'2018-09-04','AM'),(8,'2018-09-10','PM'),(8,'2018-09-11','AM'),(8,'2018-09-12','PM'),(8,'2018-09-13','AM'),(8,'2018-09-14','PM'),(8,'2018-09-19','AM'),(8,'2018-09-21','PM'),(8,'2018-09-28','AM'),(8,'2018-09-29','PM'),(9,'2018-09-07','AM'),(9,'2018-09-10','PM'),(9,'2018-09-12','AM'),(9,'2018-09-16','PM'),(9,'2018-09-20','AM'),(9,'2018-09-21','PM'),(9,'2018-09-23','AM'),(9,'2018-09-25','PM'),(9,'2018-09-28','AM'),(9,'2018-09-29','PM'),(10,'2018-09-02','AM'),(10,'2018-09-05','PM'),(10,'2018-09-10','AM'),(10,'2018-09-14','PM'),(10,'2018-09-18','AM'),(10,'2018-09-20','PM'),(10,'2018-09-26','AM'),(10,'2018-09-27','PM'),(10,'2018-09-28','AM'),(10,'2018-09-29','PM');
/*!40000 ALTER TABLE `bus_blackout` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bus_driver`
--

DROP TABLE IF EXISTS `bus_driver`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bus_driver` (
  `driverID` tinyint(1) NOT NULL,
  `userID` tinyint(4) NOT NULL,
  `name` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `homePhone` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `cellPhone` varchar(45) DEFAULT NULL,
  `email` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`driverID`,`userID`),
  KEY `BUS_DRIVER_USERS_userID_idx` (`userID`),
  CONSTRAINT `FK_BUS_DRIVER_USERS_userID` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bus_driver`
--

LOCK TABLES `bus_driver` WRITE;
/*!40000 ALTER TABLE `bus_driver` DISABLE KEYS */;
INSERT INTO `bus_driver` VALUES (1,1,'John',NULL,NULL,NULL,NULL),(2,2,'Bill',NULL,NULL,NULL,NULL),(3,3,'Joe',NULL,NULL,NULL,NULL),(4,4,NULL,NULL,NULL,NULL,NULL),(5,5,NULL,NULL,NULL,NULL,NULL),(6,6,NULL,NULL,NULL,NULL,NULL),(7,7,NULL,NULL,NULL,NULL,NULL),(8,8,NULL,NULL,NULL,NULL,NULL),(9,9,NULL,NULL,NULL,NULL,NULL),(10,10,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `bus_driver` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bus_schedule`
--

DROP TABLE IF EXISTS `bus_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bus_schedule` (
  `driverID` tinyint(1) NOT NULL,
  `date` date NOT NULL,
  `timeOfDay` char(2) CHARACTER SET latin1 NOT NULL,
  `role` varchar(8) CHARACTER SET latin1 NOT NULL,
  `congID` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`driverID`,`date`,`timeOfDay`,`role`),
  KEY `BUS_SCHEDULE_CONGREGATION_congID_idx` (`congID`),
  CONSTRAINT `BUS_SCHEDULE_BUS_DRIVER_driverID` FOREIGN KEY (`driverID`) REFERENCES `bus_driver` (`driverID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `BUS_SCHEDULE_CONGREGATION_congID` FOREIGN KEY (`congID`) REFERENCES `congregation` (`congID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bus_schedule`
--

LOCK TABLES `bus_schedule` WRITE;
/*!40000 ALTER TABLE `bus_schedule` DISABLE KEYS */;
/*!40000 ALTER TABLE `bus_schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `congregation`
--

DROP TABLE IF EXISTS `congregation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `congregation` (
  `congID` tinyint(1) NOT NULL,
  `congName` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `congAddress` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `comments` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `lastDateServed` date DEFAULT NULL,
  `lastHolidayServed` date DEFAULT NULL,
  PRIMARY KEY (`congID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `congregation`
--

LOCK TABLES `congregation` WRITE;
/*!40000 ALTER TABLE `congregation` DISABLE KEYS */;
INSERT INTO `congregation` VALUES (1,'DUPC','123 Fake Street',NULL,'2017-12-03','2017-09-03'),(2,'First Presbyterian Pittsford','222 Street Street',NULL,'2017-12-10',NULL),(3,'St. Paul\'s Episcopal','654 Fake Road',NULL,'2017-11-26','2017-05-28'),(4,'Two Saints','555 Fake Lane',NULL,'2017-12-17',NULL),(5,'First Universalist',NULL,NULL,'2018-01-07',NULL),(6,'Incarnate World',NULL,NULL,'2018-01-21','2017-04-09'),(7,'Assumption',NULL,NULL,'2018-02-11',NULL),(8,'Asbury Methodist',NULL,NULL,'2017-12-31','2017-12-31'),(9,'Mary Magdalene',NULL,NULL,'2018-01-28','2017-04-16'),(10,'First Unitarian',NULL,NULL,'2018-01-14','2017-07-02'),(11,'Temple Sinai',NULL,NULL,'2017-12-24','2017-12-24'),(12,'Third Presbyterian',NULL,NULL,'2018-02-18','2017-11-19'),(13,'New Hope Free Methodist',NULL,NULL,'2018-02-04',NULL);
/*!40000 ALTER TABLE `congregation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `congregation_blackout`
--

DROP TABLE IF EXISTS `congregation_blackout`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `congregation_blackout` (
  `congID` tinyint(1) NOT NULL,
  `weekNumber` tinyint(1) NOT NULL,
  `startDate` date NOT NULL,
  `rotation_number` int(11) NOT NULL,
  PRIMARY KEY (`congID`,`weekNumber`,`startDate`),
  KEY `CONGREGATION_BLACKOUT_CONGREGATION_idx` (`congID`),
  KEY `fk_CONGREGATION_BLACKOUT_DATE_RANGE1_idx` (`weekNumber`,`startDate`),
  CONSTRAINT `CONGREGATION_BLACKOUT_CONGREGATION_congID` FOREIGN KEY (`congID`) REFERENCES `congregation` (`congID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `CONGREGATION_BLACKOUT_DATE_RANGE_startDate_weekNumber` FOREIGN KEY (`weekNumber`, `startDate`) REFERENCES `date_range` (`weekNumber`, `startDate`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `congregation_blackout`
--

LOCK TABLES `congregation_blackout` WRITE;
/*!40000 ALTER TABLE `congregation_blackout` DISABLE KEYS */;
INSERT INTO `congregation_blackout` VALUES (1,4,'2018-03-18',53),(1,4,'2018-06-17',54),(1,4,'2018-09-16',55),(1,5,'2018-03-25',53),(1,5,'2018-06-24',54),(1,5,'2018-09-23',55),(1,6,'2018-04-01',53),(1,6,'2018-07-01',54),(1,6,'2018-09-30',55),(1,7,'2018-04-08',53),(1,7,'2018-07-08',54),(1,7,'2018-10-07',55),(1,8,'2018-04-15',53),(1,8,'2018-07-15',54),(1,8,'2018-10-14',55),(1,9,'2018-04-22',53),(1,9,'2018-07-22',54),(1,9,'2018-10-21',55),(1,10,'2018-04-29',53),(1,10,'2018-07-29',54),(1,10,'2018-10-28',55),(1,11,'2018-05-06',53),(1,11,'2018-08-05',54),(1,11,'2018-11-04',55),(1,12,'2018-05-13',53),(1,12,'2018-08-12',54),(1,12,'2018-11-11',55),(1,13,'2018-05-20',53),(1,13,'2018-08-19',54),(1,13,'2018-11-18',55),(2,4,'2018-03-18',53),(2,4,'2018-06-17',54),(2,4,'2018-09-16',55),(2,5,'2018-03-25',53),(2,5,'2018-06-24',54),(2,5,'2018-09-23',55),(2,6,'2018-04-01',53),(2,6,'2018-07-01',54),(2,6,'2018-09-30',55),(2,7,'2018-04-08',53),(2,7,'2018-07-08',54),(2,7,'2018-10-07',55),(2,8,'2018-04-15',53),(2,8,'2018-07-15',54),(2,8,'2018-10-14',55),(2,9,'2018-04-22',53),(2,9,'2018-07-22',54),(2,9,'2018-10-21',55),(2,10,'2018-04-29',53),(2,10,'2018-07-29',54),(2,10,'2018-10-28',55),(2,11,'2018-05-06',53),(2,11,'2018-08-05',54),(2,11,'2018-11-04',55),(2,12,'2018-05-13',53),(2,12,'2018-08-12',54),(2,12,'2018-11-11',55),(2,13,'2018-05-20',53),(2,13,'2018-08-19',54),(2,13,'2018-11-18',55),(3,4,'2018-03-18',53),(3,4,'2018-06-17',54),(3,4,'2018-09-16',55),(3,5,'2018-03-25',53),(3,5,'2018-06-24',54),(3,5,'2018-09-23',55),(3,6,'2018-04-01',53),(3,6,'2018-07-01',54),(3,6,'2018-09-30',55),(3,7,'2018-04-08',53),(3,7,'2018-07-08',54),(3,7,'2018-10-07',55),(3,8,'2018-04-15',53),(3,8,'2018-07-15',54),(3,8,'2018-10-14',55),(3,9,'2018-04-22',53),(3,9,'2018-07-22',54),(3,9,'2018-10-21',55),(3,10,'2018-04-29',53),(3,10,'2018-07-29',54),(3,10,'2018-10-28',55),(3,11,'2018-05-06',53),(3,11,'2018-08-05',54),(3,11,'2018-11-04',55),(3,12,'2018-05-13',53),(3,12,'2018-08-12',54),(3,12,'2018-11-11',55),(3,13,'2018-05-20',53),(3,13,'2018-08-19',54),(3,13,'2018-11-18',55),(4,1,'2018-02-25',53),(4,1,'2018-05-27',54),(4,1,'2018-08-26',55),(4,2,'2018-03-04',53),(4,2,'2018-06-03',54),(4,2,'2018-09-02',55),(4,3,'2018-03-11',53),(4,3,'2018-06-10',54),(4,3,'2018-09-09',55),(4,5,'2018-03-25',53),(4,5,'2018-06-24',54),(4,5,'2018-09-23',55),(4,6,'2018-04-01',53),(4,6,'2018-07-01',54),(4,6,'2018-09-30',55),(4,7,'2018-04-08',53),(4,7,'2018-07-08',54),(4,7,'2018-10-07',55),(4,8,'2018-04-15',53),(4,8,'2018-07-15',54),(4,8,'2018-10-14',55),(4,9,'2018-04-22',53),(4,9,'2018-07-22',54),(4,9,'2018-10-21',55),(4,10,'2018-04-29',53),(4,10,'2018-07-29',54),(4,10,'2018-10-28',55),(4,11,'2018-05-06',53),(4,11,'2018-08-05',54),(4,11,'2018-11-04',55),(4,12,'2018-05-13',53),(4,12,'2018-08-12',54),(4,12,'2018-11-11',55),(4,13,'2018-05-20',53),(4,13,'2018-08-19',54),(4,13,'2018-11-18',55),(5,1,'2018-02-25',53),(5,1,'2018-05-27',54),(5,1,'2018-08-26',55),(5,2,'2018-03-04',53),(5,2,'2018-06-03',54),(5,2,'2018-09-02',55),(5,3,'2018-03-11',53),(5,3,'2018-06-10',54),(5,3,'2018-09-09',55),(5,4,'2018-03-18',53),(5,4,'2018-06-17',54),(5,4,'2018-09-16',55),(5,8,'2018-04-15',53),(5,8,'2018-07-15',54),(5,8,'2018-10-14',55),(5,9,'2018-04-22',53),(5,9,'2018-07-22',54),(5,9,'2018-10-21',55),(5,10,'2018-04-29',53),(5,10,'2018-07-29',54),(5,10,'2018-10-28',55),(5,11,'2018-05-06',53),(5,11,'2018-08-05',54),(5,11,'2018-11-04',55),(5,12,'2018-05-13',53),(5,12,'2018-08-12',54),(5,12,'2018-11-11',55),(5,13,'2018-05-20',53),(5,13,'2018-08-19',54),(5,13,'2018-11-18',55),(6,1,'2018-02-25',53),(6,1,'2018-05-27',54),(6,1,'2018-08-26',55),(6,2,'2018-03-04',53),(6,2,'2018-06-03',54),(6,2,'2018-09-02',55),(6,3,'2018-03-11',53),(6,3,'2018-06-10',54),(6,3,'2018-09-09',55),(6,4,'2018-03-18',53),(6,4,'2018-06-17',54),(6,4,'2018-09-16',55),(6,8,'2018-04-15',53),(6,8,'2018-07-15',54),(6,8,'2018-10-14',55),(6,9,'2018-04-22',53),(6,9,'2018-07-22',54),(6,9,'2018-10-21',55),(6,10,'2018-04-29',53),(6,10,'2018-07-29',54),(6,10,'2018-10-28',55),(6,11,'2018-05-06',53),(6,11,'2018-08-05',54),(6,11,'2018-11-04',55),(6,12,'2018-05-13',53),(6,12,'2018-08-12',54),(6,12,'2018-11-11',55),(6,13,'2018-05-20',53),(6,13,'2018-08-19',54),(6,13,'2018-11-18',55),(7,1,'2018-02-25',53),(7,1,'2018-05-27',54),(7,1,'2018-08-26',55),(7,2,'2018-03-04',53),(7,2,'2018-06-03',54),(7,2,'2018-09-02',55),(7,3,'2018-03-11',53),(7,3,'2018-06-10',54),(7,3,'2018-09-09',55),(7,4,'2018-03-18',53),(7,4,'2018-06-17',54),(7,4,'2018-09-16',55),(7,5,'2018-03-25',53),(7,5,'2018-06-24',54),(7,5,'2018-09-23',55),(7,6,'2018-04-01',53),(7,6,'2018-07-01',54),(7,6,'2018-09-30',55),(7,7,'2018-04-08',53),(7,7,'2018-07-08',54),(7,7,'2018-10-07',55),(7,8,'2018-04-15',53),(7,8,'2018-07-15',54),(7,8,'2018-10-14',55),(7,9,'2018-04-22',53),(7,9,'2018-07-22',54),(7,9,'2018-10-21',55),(7,10,'2018-04-29',53),(7,10,'2018-07-29',54),(7,10,'2018-10-28',55),(8,1,'2018-02-25',53),(8,1,'2018-05-27',54),(8,1,'2018-08-26',55),(8,2,'2018-03-04',53),(8,2,'2018-06-03',54),(8,2,'2018-09-02',55),(8,3,'2018-03-11',53),(8,3,'2018-06-10',54),(8,3,'2018-09-09',55),(8,4,'2018-03-18',53),(8,4,'2018-06-17',54),(8,4,'2018-09-16',55),(8,5,'2018-03-25',53),(8,5,'2018-06-24',54),(8,5,'2018-09-23',55),(8,6,'2018-04-01',53),(8,6,'2018-07-01',54),(8,6,'2018-09-30',55),(8,7,'2018-04-08',53),(8,7,'2018-07-08',54),(8,7,'2018-10-07',55),(8,11,'2018-05-06',53),(8,11,'2018-08-05',54),(8,11,'2018-11-04',55),(8,12,'2018-05-13',53),(8,12,'2018-08-12',54),(8,12,'2018-11-11',55),(8,13,'2018-05-20',53),(8,13,'2018-08-19',54),(8,13,'2018-11-18',55),(9,1,'2018-02-25',53),(9,1,'2018-05-27',54),(9,1,'2018-08-26',55),(9,2,'2018-03-04',53),(9,2,'2018-06-03',54),(9,2,'2018-09-02',55),(9,3,'2018-03-11',53),(9,3,'2018-06-10',54),(9,3,'2018-09-09',55),(9,4,'2018-03-18',53),(9,4,'2018-06-17',54),(9,4,'2018-09-16',55),(9,5,'2018-03-25',53),(9,5,'2018-06-24',54),(9,5,'2018-09-23',55),(9,6,'2018-04-01',53),(9,6,'2018-07-01',54),(9,6,'2018-09-30',55),(9,7,'2018-04-08',53),(9,7,'2018-07-08',54),(9,7,'2018-10-07',55),(9,11,'2018-05-06',53),(9,11,'2018-08-05',54),(9,11,'2018-11-04',55),(9,12,'2018-05-13',53),(9,12,'2018-08-12',54),(9,12,'2018-11-11',55),(9,13,'2018-05-20',53),(9,13,'2018-08-19',54),(9,13,'2018-11-18',55),(10,1,'2018-02-25',53),(10,1,'2018-05-27',54),(10,1,'2018-08-26',55),(10,2,'2018-03-04',53),(10,2,'2018-06-03',54),(10,2,'2018-09-02',55),(10,3,'2018-03-11',53),(10,3,'2018-06-10',54),(10,3,'2018-09-09',55),(10,4,'2018-03-18',53),(10,4,'2018-06-17',54),(10,4,'2018-09-16',55),(10,5,'2018-03-25',53),(10,5,'2018-06-24',54),(10,5,'2018-09-23',55),(10,6,'2018-04-01',53),(10,6,'2018-07-01',54),(10,6,'2018-09-30',55),(10,7,'2018-04-08',53),(10,7,'2018-07-08',54),(10,7,'2018-10-07',55),(10,11,'2018-05-06',53),(10,11,'2018-08-05',54),(10,11,'2018-11-04',55),(10,12,'2018-05-13',53),(10,12,'2018-08-12',54),(10,12,'2018-11-11',55),(10,13,'2018-05-20',53),(10,13,'2018-08-19',54),(10,13,'2018-11-18',55),(11,1,'2018-02-25',53),(11,1,'2018-05-27',54),(11,1,'2018-08-26',55),(11,2,'2018-03-04',53),(11,2,'2018-06-03',54),(11,2,'2018-09-02',55),(11,3,'2018-03-11',53),(11,3,'2018-06-10',54),(11,3,'2018-09-09',55),(11,4,'2018-03-18',53),(11,4,'2018-06-17',54),(11,4,'2018-09-16',55),(11,8,'2018-04-15',53),(11,8,'2018-07-15',54),(11,8,'2018-10-14',55),(11,9,'2018-04-22',53),(11,9,'2018-07-22',54),(11,9,'2018-10-21',55),(11,10,'2018-04-29',53),(11,10,'2018-07-29',54),(11,10,'2018-10-28',55),(11,11,'2018-05-06',53),(11,11,'2018-08-05',54),(11,11,'2018-11-04',55),(11,12,'2018-05-13',53),(11,12,'2018-08-12',54),(11,12,'2018-11-11',55),(11,13,'2018-05-20',53),(11,13,'2018-08-19',54),(11,13,'2018-11-18',55),(12,1,'2018-02-25',53),(12,1,'2018-05-27',54),(12,1,'2018-08-26',55),(12,2,'2018-03-04',53),(12,2,'2018-06-03',54),(12,2,'2018-09-02',55),(12,3,'2018-03-11',53),(12,3,'2018-06-10',54),(12,3,'2018-09-09',55),(12,4,'2018-03-18',53),(12,4,'2018-06-17',54),(12,4,'2018-09-16',55),(12,5,'2018-03-25',53),(12,5,'2018-06-24',54),(12,5,'2018-09-23',55),(12,6,'2018-04-01',53),(12,6,'2018-07-01',54),(12,6,'2018-09-30',55),(12,7,'2018-04-08',53),(12,7,'2018-07-08',54),(12,7,'2018-10-07',55),(12,8,'2018-04-15',53),(12,8,'2018-07-15',54),(12,8,'2018-10-14',55),(12,9,'2018-04-22',53),(12,9,'2018-07-22',54),(12,9,'2018-10-21',55),(12,10,'2018-04-29',53),(12,10,'2018-07-29',54),(12,10,'2018-10-28',55),(13,1,'2018-02-25',53),(13,1,'2018-05-27',54),(13,1,'2018-08-26',55),(13,2,'2018-03-04',53),(13,2,'2018-06-03',54),(13,2,'2018-09-02',55),(13,3,'2018-03-11',53),(13,3,'2018-06-10',54),(13,3,'2018-09-09',55),(13,4,'2018-03-18',53),(13,4,'2018-06-17',54),(13,4,'2018-09-16',55),(13,5,'2018-03-25',53),(13,5,'2018-06-24',54),(13,5,'2018-09-23',55),(13,6,'2018-04-01',53),(13,6,'2018-07-01',54),(13,6,'2018-09-30',55),(13,7,'2018-04-08',53),(13,7,'2018-07-08',54),(13,7,'2018-10-07',55),(13,8,'2018-04-15',53),(13,8,'2018-07-15',54),(13,8,'2018-10-14',55),(13,9,'2018-04-22',53),(13,9,'2018-07-22',54),(13,9,'2018-10-21',55),(13,10,'2018-04-29',53),(13,10,'2018-07-29',54),(13,10,'2018-10-28',55);
/*!40000 ALTER TABLE `congregation_blackout` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `congregation_coordinator`
--

DROP TABLE IF EXISTS `congregation_coordinator`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `congregation_coordinator` (
  `congID` tinyint(4) NOT NULL,
  `userID` tinyint(4) NOT NULL,
  `coordinatorName` varchar(100) DEFAULT NULL,
  `coordinatorPhone` varchar(20) DEFAULT NULL,
  `coordinatorEmail` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`congID`,`userID`),
  KEY `FK_CONGREGATION_COORDINATOR_USERS_userID_idx` (`userID`),
  CONSTRAINT `FK_CONGREGATION_COORDINATOR_CONGREGATION` FOREIGN KEY (`congID`) REFERENCES `congregation` (`congID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_CONGREGATION_COORDINATOR_USERS_userID` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `congregation_coordinator`
--

LOCK TABLES `congregation_coordinator` WRITE;
/*!40000 ALTER TABLE `congregation_coordinator` DISABLE KEYS */;
INSERT INTO `congregation_coordinator` VALUES (1,14,'dupc coordinator','','dupc@gmail.com'),(2,15,'first presbyterian coordinator','','fpp@gmail.com'),(3,16,'st pauls coordinator','','stpauls@gmail.com'),(4,17,'two saints coordinator','','twosaints@gmail.com'),(5,18,'first universalist coordinator','','firstuniversalist@gmail.com'),(6,19,'incarnate world coordinator','','incarnateworld@gmail.com'),(7,20,'assumption coordinator','','assumption@gmail.com'),(8,21,'asbury methodist coordinator','','asbury@gmail.com'),(9,22,'mary magdalene coordinator','','marymagdalene@gmail.com'),(10,23,'first unitarian coordinator','','firstunitarian@gmail.com'),(11,24,'temple sinai coordinator','','templesanai@gmail.com'),(12,25,'third presbyterian coordinator','','thirdpres@gmail.com'),(13,26,'new hope coordinator','','newhope@gmail.com');
/*!40000 ALTER TABLE `congregation_coordinator` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `congregation_schedule`
--

DROP TABLE IF EXISTS `congregation_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `congregation_schedule` (
  `congID` tinyint(1) NOT NULL,
  `startDate` date NOT NULL,
  `weekNumber` tinyint(1) NOT NULL,
  `rotationNumber` int(11) NOT NULL,
  `holiday` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`congID`,`startDate`,`weekNumber`,`rotationNumber`),
  KEY `CONGREGATION_SCHEDULE_ROTATION_DATE_rotationNumber_idx` (`rotationNumber`),
  KEY `CONGREGATION_SCHEDULE_CONGREGATION_congID_idx` (`congID`),
  KEY `fk_CONGREGATION_SCHEDULE_DATE_RANGE1_idx` (`weekNumber`,`startDate`),
  KEY `DATE_RANGE_startDate_idx` (`startDate`),
  CONSTRAINT `CONGREGATION_SCHEDULE_CONGREGATION_congID` FOREIGN KEY (`congID`) REFERENCES `congregation` (`congID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `CONGREGATION_SCHEDULE_DATE_RANGE_startDate_weekNumber` FOREIGN KEY (`weekNumber`, `startDate`) REFERENCES `date_range` (`weekNumber`, `startDate`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `congregation_schedule`
--

LOCK TABLES `congregation_schedule` WRITE;
/*!40000 ALTER TABLE `congregation_schedule` DISABLE KEYS */;
INSERT INTO `congregation_schedule` VALUES (1,'2018-02-25',1,53,0),(2,'2018-03-04',2,53,0),(3,'2018-03-11',3,53,0),(4,'2018-03-18',4,53,0),(5,'2018-03-25',5,53,1),(6,'2018-04-01',6,53,1),(7,'2018-05-06',11,53,0),(8,'2018-04-15',8,53,0),(9,'2018-04-22',9,53,0),(10,'2018-04-29',10,53,0),(11,'2018-04-08',7,53,0),(12,'2018-05-13',12,53,0),(13,'2018-05-20',13,53,0);
/*!40000 ALTER TABLE `congregation_schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `date_range`
--

DROP TABLE IF EXISTS `date_range`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `date_range` (
  `weekNumber` tinyint(1) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date DEFAULT NULL,
  `holiday` tinyint(1) DEFAULT NULL,
  `rotation_number` int(11) NOT NULL,
  PRIMARY KEY (`weekNumber`,`startDate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `date_range`
--

LOCK TABLES `date_range` WRITE;
/*!40000 ALTER TABLE `date_range` DISABLE KEYS */;
INSERT INTO `date_range` VALUES (1,'2018-02-25','2018-03-03',0,53),(1,'2018-05-27','2018-06-02',1,54),(1,'2018-08-26','2018-09-01',0,55),(1,'2018-11-25','2018-12-01',0,56),(1,'2019-02-24','2019-03-02',0,57),(1,'2019-05-26','2019-06-01',1,58),(1,'2019-08-25','2019-08-31',0,59),(1,'2019-11-24','2019-11-30',1,60),(1,'2020-02-23','2020-02-29',0,61),(1,'2020-05-24','2020-05-30',1,62),(1,'2020-08-23','2020-08-29',0,63),(2,'2018-03-04','2018-03-10',0,53),(2,'2018-06-03','2018-06-09',0,54),(2,'2018-09-02','2018-09-08',1,55),(2,'2018-12-02','2018-12-08',0,56),(2,'2019-03-03','2019-03-09',0,57),(2,'2019-06-02','2019-06-08',0,58),(2,'2019-09-01','2019-09-07',1,59),(2,'2019-12-01','2019-12-07',0,60),(2,'2020-03-01','2020-03-07',0,61),(2,'2020-05-31','2020-06-06',0,62),(2,'2020-08-30','2020-09-05',0,63),(3,'2018-03-11','2018-03-17',0,53),(3,'2018-06-10','2018-06-16',0,54),(3,'2018-09-09','2018-09-15',0,55),(3,'2018-12-09','2018-12-15',0,56),(3,'2019-03-10','2019-03-16',0,57),(3,'2019-06-09','2019-06-15',0,58),(3,'2019-09-08','2019-09-14',0,59),(3,'2019-12-08','2019-12-14',0,60),(3,'2020-03-08','2020-03-14',0,61),(3,'2020-06-07','2020-06-13',0,62),(3,'2020-09-06','2020-09-12',1,63),(4,'2018-03-18','2018-03-24',0,53),(4,'2018-06-17','2018-06-23',0,54),(4,'2018-09-16','2018-09-22',0,55),(4,'2018-12-16','2018-12-22',0,56),(4,'2019-03-17','2019-03-23',0,57),(4,'2019-06-16','2019-06-22',0,58),(4,'2019-09-15','2019-09-21',0,59),(4,'2019-12-15','2019-12-21',0,60),(4,'2020-03-15','2020-03-21',0,61),(4,'2020-06-14','2020-06-20',0,62),(4,'2020-09-13','2020-09-19',0,63),(5,'2018-03-25','2018-03-31',1,53),(5,'2018-06-24','2018-06-30',0,54),(5,'2018-09-23','2018-09-29',0,55),(5,'2018-12-23','2018-12-29',1,56),(5,'2019-03-24','2019-03-30',0,57),(5,'2019-06-23','2019-06-29',0,58),(5,'2019-09-22','2019-09-28',0,59),(5,'2019-12-22','2019-12-28',1,60),(5,'2020-03-22','2020-03-28',0,61),(5,'2020-06-21','2020-06-27',0,62),(5,'2020-09-20','2020-09-26',0,63),(6,'2018-04-01','2018-04-07',1,53),(6,'2018-07-01','2018-07-07',1,54),(6,'2018-09-30','2018-10-06',0,55),(6,'2018-12-30','2019-01-05',1,56),(6,'2019-03-31','2019-04-06',0,57),(6,'2019-06-30','2019-07-06',1,58),(6,'2019-09-29','2019-10-05',0,59),(6,'2019-12-29','2020-01-04',1,60),(6,'2020-03-29','2020-04-04',0,61),(6,'2020-06-28','2020-07-04',1,62),(6,'2020-09-27','2020-10-03',0,63),(7,'2018-04-08','2018-04-14',0,53),(7,'2018-07-08','2018-07-14',0,54),(7,'2018-10-07','2018-10-13',0,55),(7,'2019-01-06','2019-01-12',0,56),(7,'2019-04-07','2019-04-13',0,57),(7,'2019-07-07','2019-07-13',0,58),(7,'2019-10-06','2019-10-12',0,59),(7,'2020-01-05','2020-01-11',0,60),(7,'2020-04-05','2020-04-11',1,61),(7,'2020-07-05','2020-07-11',0,62),(7,'2020-10-04','2020-10-10',0,63),(8,'2018-04-15','2018-04-21',0,53),(8,'2018-07-15','2018-07-21',0,54),(8,'2018-10-14','2018-10-20',0,55),(8,'2019-01-13','2019-01-19',0,56),(8,'2019-04-14','2019-04-20',1,57),(8,'2019-07-14','2019-07-20',0,58),(8,'2019-10-13','2019-10-19',0,59),(8,'2020-01-12','2020-01-18',0,60),(8,'2020-04-12','2020-04-18',1,61),(8,'2020-07-12','2020-07-18',0,62),(8,'2020-10-11','2020-10-17',0,63),(9,'2018-04-22','2018-04-28',0,53),(9,'2018-07-22','2018-07-28',0,54),(9,'2018-10-21','2018-10-27',0,55),(9,'2019-01-20','2019-01-26',0,56),(9,'2019-04-21','2019-04-27',1,57),(9,'2019-07-21','2019-07-27',0,58),(9,'2019-10-20','2019-10-26',0,59),(9,'2020-01-19','2020-01-25',0,60),(9,'2020-04-19','2020-04-25',0,61),(9,'2020-07-19','2020-07-25',0,62),(9,'2020-10-18','2020-10-24',0,63),(10,'2018-04-29','2018-05-05',0,53),(10,'2018-07-29','2018-08-04',0,54),(10,'2018-10-28','2018-11-03',0,55),(10,'2019-01-27','2019-02-02',0,56),(10,'2019-04-28','2019-05-04',0,57),(10,'2019-07-28','2019-08-03',0,58),(10,'2019-10-27','2019-11-02',0,59),(10,'2020-01-26','2020-02-01',0,60),(10,'2020-04-26','2020-05-02',0,61),(10,'2020-07-26','2020-08-01',0,62),(10,'2020-10-25','2020-10-31',0,63),(11,'2018-05-06','2018-05-12',0,53),(11,'2018-08-05','2018-08-11',0,54),(11,'2018-11-04','2018-11-10',0,55),(11,'2019-02-03','2019-02-09',0,56),(11,'2019-05-05','2019-05-11',0,57),(11,'2019-08-04','2019-08-10',0,58),(11,'2019-11-03','2019-11-09',0,59),(11,'2020-02-02','2020-02-08',0,60),(11,'2020-05-03','2020-05-09',0,61),(11,'2020-08-02','2020-08-08',0,62),(11,'2020-11-01','2020-11-07',0,63),(12,'2018-05-13','2018-05-19',0,53),(12,'2018-08-12','2018-08-18',0,54),(12,'2018-11-11','2018-11-17',0,55),(12,'2019-02-10','2019-02-16',0,56),(12,'2019-05-12','2019-05-18',0,57),(12,'2019-08-11','2019-08-17',0,58),(12,'2019-11-10','2019-11-16',0,59),(12,'2020-02-09','2020-02-15',0,60),(12,'2020-05-10','2020-05-16',0,61),(12,'2020-08-09','2020-08-15',0,62),(12,'2020-11-08','2020-11-14',0,63),(13,'2018-05-20','2018-05-26',0,53),(13,'2018-08-19','2018-08-25',0,54),(13,'2018-11-18','2018-11-24',1,55),(13,'2019-02-17','2019-02-23',0,56),(13,'2019-05-19','2019-05-25',0,57),(13,'2019-08-18','2019-08-24',0,58),(13,'2019-11-17','2019-11-23',0,59),(13,'2020-02-16','2020-02-22',0,60),(13,'2020-05-17','2020-05-23',0,61),(13,'2020-08-16','2020-08-22',0,62),(13,'2020-11-15','2020-11-21',0,63);
/*!40000 ALTER TABLE `date_range` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `legacy_host_blackout`
--

DROP TABLE IF EXISTS `legacy_host_blackout`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `legacy_host_blackout` (
  `congID` int(11) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date DEFAULT NULL,
  `holiday` tinyint(1) DEFAULT NULL,
  `rotation_number` int(11) DEFAULT NULL,
  PRIMARY KEY (`congID`,`startDate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `legacy_host_blackout`
--

LOCK TABLES `legacy_host_blackout` WRITE;
/*!40000 ALTER TABLE `legacy_host_blackout` DISABLE KEYS */;
INSERT INTO `legacy_host_blackout` VALUES (1,'2017-03-12',NULL,0,49),(1,'2017-06-11',NULL,0,50),(1,'2017-09-03',NULL,1,51),(1,'2017-12-03',NULL,0,52),(2,'2017-03-05',NULL,0,49),(2,'2017-06-04',NULL,0,50),(2,'2017-09-10',NULL,0,51),(2,'2017-12-10',NULL,0,52),(3,'2017-02-26',NULL,0,49),(3,'2017-05-28',NULL,1,50),(3,'2017-08-27',NULL,0,51),(3,'2017-11-26',NULL,0,52),(4,'2017-03-19',NULL,0,49),(4,'2017-06-18',NULL,0,50),(4,'2017-09-17',NULL,0,51),(4,'2017-12-17',NULL,0,52),(5,'2017-03-26',NULL,0,49),(5,'2017-06-25',NULL,0,50),(5,'2017-09-24',NULL,0,51),(5,'2018-01-07',NULL,0,52),(6,'2017-04-09',NULL,1,49),(6,'2017-07-16',NULL,0,50),(6,'2017-10-22',NULL,0,51),(6,'2018-01-21',NULL,0,52),(7,'2017-05-21',NULL,0,49),(7,'2017-08-20',NULL,0,50),(7,'2017-11-12',NULL,0,51),(7,'2018-02-11',NULL,0,52),(8,'2017-04-30',NULL,0,49),(8,'2017-07-23',NULL,0,50),(8,'2017-10-15',NULL,0,51),(8,'2017-12-31',NULL,1,52),(9,'2017-04-16',NULL,1,49),(9,'2017-07-30',NULL,0,50),(9,'2017-10-29',NULL,0,51),(9,'2018-01-28',NULL,0,52),(10,'2017-04-23',NULL,0,49),(10,'2017-07-02',NULL,1,50),(10,'2017-10-08',NULL,0,51),(10,'2018-01-14',NULL,0,52),(11,'2017-04-02',NULL,0,49),(11,'2017-07-09',NULL,0,50),(11,'2017-10-01',NULL,0,51),(11,'2017-12-24',NULL,1,52),(12,'2017-05-14',NULL,0,49),(12,'2017-08-13',NULL,0,50),(12,'2017-11-19',NULL,1,51),(12,'2018-02-18',NULL,0,52),(13,'2017-05-07',NULL,0,49),(13,'2017-08-06',NULL,0,50),(13,'2017-11-05',NULL,0,51),(13,'2018-02-04',NULL,0,52);
/*!40000 ALTER TABLE `legacy_host_blackout` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rotation_date`
--

DROP TABLE IF EXISTS `rotation_date`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rotation_date` (
  `rotationNumber` int(11) NOT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rotation_date`
--

LOCK TABLES `rotation_date` WRITE;
/*!40000 ALTER TABLE `rotation_date` DISABLE KEYS */;
INSERT INTO `rotation_date` VALUES (53,'2018-02-25','2018-05-26'),(54,'2018-05-27','2018-08-25'),(55,'2018-08-26','2018-11-24'),(56,'2018-11-25','2019-02-23'),(57,'2019-02-24','2019-05-25'),(58,'2019-05-26','2019-08-24'),(59,'2019-08-25','2019-11-23'),(60,'2019-11-24','2020-02-22'),(61,'2020-02-23','2020-05-23'),(62,'2020-05-24','2020-08-22'),(63,'2020-08-23','2020-11-21'),(53,'2018-02-25','2018-05-26'),(54,'2018-05-27','2018-08-25'),(55,'2018-08-26','2018-11-24'),(56,'2018-11-25','2019-02-23'),(57,'2019-02-24','2019-05-25'),(58,'2019-05-26','2019-08-24'),(59,'2019-08-25','2019-11-23'),(60,'2019-11-24','2020-02-22'),(61,'2020-02-23','2020-05-23'),(62,'2020-05-24','2020-08-22'),(63,'2020-08-23','2020-11-21'),(53,'2018-02-25','2018-05-26'),(54,'2018-05-27','2018-08-25'),(55,'2018-08-26','2018-11-24'),(56,'2018-11-25','2019-02-23'),(57,'2019-02-24','2019-05-25'),(58,'2019-05-26','2019-08-24'),(59,'2019-08-25','2019-11-23'),(60,'2019-11-24','2020-02-22'),(61,'2020-02-23','2020-05-23'),(62,'2020-05-24','2020-08-22'),(63,'2020-08-23','2020-11-21'),(53,'2018-02-25','2018-05-26'),(54,'2018-05-27','2018-08-25'),(55,'2018-08-26','2018-11-24'),(56,'2018-11-25','2019-02-23'),(57,'2019-02-24','2019-05-25'),(58,'2019-05-26','2019-08-24'),(59,'2019-08-25','2019-11-23'),(60,'2019-11-24','2020-02-22'),(61,'2020-02-23','2020-05-23'),(62,'2020-05-24','2020-08-22'),(63,'2020-08-23','2020-11-21');
/*!40000 ALTER TABLE `rotation_date` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `userID` tinyint(4) NOT NULL,
  `userType` varchar(25) CHARACTER SET latin1 DEFAULT NULL,
  `email` varchar(30) CHARACTER SET latin1 DEFAULT NULL,
  `password` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `salt` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Bus Driver','driver1@gmail.com','password',''),(2,'Bus Driver','driver2@gmail.com','1a46f40f7ea7355ccf0dbbdf94e8e2062e3b06729a40f9e7be9ebd6f85efdfe9','38802f83f9676a00'),(3,'Bus Driver','driver3@gmail.com','password',NULL),(4,'Bus Driver',NULL,NULL,NULL),(5,'Bus Driver',NULL,NULL,NULL),(6,'Bus Driver',NULL,NULL,NULL),(7,'Bus Driver',NULL,NULL,NULL),(8,'Bus Driver',NULL,NULL,NULL),(9,'Bus Driver',NULL,NULL,NULL),(10,'Bus Driver',NULL,'',NULL),(11,'Admin','bxp9452@g.rit.edu','65203af38b014791cbb00e1d639a6ce77cecdac01a7c185c875a55aaec475a2f','4efee1db462e22d4eb'),(12,'Congregation','brypickering@gmail.com','796452303ebaff5442b548577e396a46535409993fb77086e4f7dc7dadc49468','9c6aa3d7108ed5aa'),(14,'Congregation','dupc@gmail.com','93d1c21637da57d60ff46ec2545b2605a526e1dfcf6ed7889cf55ba6a38e2520','dd6786fa04235dee'),(15,'Congregation','fpp@gmail.com','80eb0da7732576e67da739672b57cd459dec14cfb382b1e34c8e127a703ebd41','5961e403f3876383'),(16,'Congregation','stpauls@gmail.com','ac7fea8cfc2f1ceadff616feb01795ec23b0731865f01bf517cd7ce6ee1b6959','a7edeb36d65a691c95ba47'),(17,'Congregation','twosaints@gmail.com','ca4f3e12b425b64d785c71521e6b441f55b58546af1e4bdc0bbdbcc20e135372','169a728cf115ea7a9395bb66e3'),(18,'Congregation','firstuniversalist@gmail.com','99b3249ecb8f54c23f7c1fd3525bde5c4e8c4f6614571f46ee3ec790004072a1','3033492aa647aab1315a1e37785663'),(19,'Congregation','incarnateworld@gmail.com','83d6120dd10493abfeb5328ef833768dcb43cf2300cb63d4413586232ce94a00','666f924026a6530dd3a6b8b12c'),(20,'Congregation','assumption@gmail.com','9bec887d02bffb1ea2139ba608bd8e111990ce7001e4f1f51c2fe5307a3392b7','61617d4597851bf828b32db76468'),(21,'Congregation','asbury@gmail.com','77c2c5c4f5f8b04329ac706cdb6c7845a2062704e7246c5303ee026f61e06844','6e26e6d7c2760a00ce9a'),(22,'Congregation','marymagdalene@gmail.com','ffc46d6a95d2eb2308fa7f42e40652358bc291ac40d2e8eb41850398f1e4785c','e04703acb627c72e685040'),(23,'Congregation','firstunitarian@gmail.com','dd4ca50a6815801861c1c6a8583639e2423e092d85b8666e88a5f28005527bb3','8c78b48850c856038a37d8b59de98bb57d76'),(24,'Congregation','templesanai@gmail.com','0499de4e6607375f4b49cfd57246588de6caf5b0cf0d83fefcc4ada2335d808b','3d80efd815e88b74d91fc73afcd342'),(25,'Congregation','thirdpres@gmail.com','eb49549cadb81642da443c2a6e939c9caf96b2c76d7638080f2678cd51e026d1','fb5dd6b799ac6c7fd666dbe8e3'),(26,'Congregation','newhope@gmail.com','4138e4beb5e55c898b349548059019f2b041430747954cb6ff0b60508f322c9b','80a9f5cef7877c425d9c64');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-10-01  2:23:10

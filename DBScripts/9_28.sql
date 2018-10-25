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
INSERT INTO `congregation_blackout` VALUES (1,5,'2018-03-25'),(1,6,'2018-04-01'),(2,5,'2018-03-25'),(2,7,'2018-04-08'),(3,7,'2018-04-08'),(3,8,'2018-04-15'),(4,11,'2018-05-06'),(4,12,'2018-05-13'),(5,8,'2018-04-15'),(5,9,'2018-04-22'),(6,3,'2018-03-11'),(6,4,'2018-03-18'),(7,8,'2018-04-15'),(7,9,'2018-04-22'),(8,6,'2018-04-01'),(8,7,'2018-04-08'),(9,12,'2018-05-13'),(9,13,'2018-05-20'),(10,1,'2018-02-25'),(10,2,'2018-03-04'),(11,8,'2018-04-15'),(11,9,'2018-04-22'),(13,4,'2018-03-18'),(13,5,'2018-03-25');
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
INSERT INTO `congregation_schedule` VALUES (1,'2018-04-15',8,53,0),(2,'2018-04-22',9,53,0),(3,'2018-03-25',5,53,1),(4,'2018-04-08',7,53,0),(5,'2018-03-18',4,53,0),(6,'2018-04-01',6,53,1),(7,'2018-05-13',12,53,0),(8,'2018-03-11',3,53,0),(9,'2018-05-06',11,53,0),(10,'2018-05-20',13,53,0),(11,'2018-03-04',2,53,0);
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

-- Dump completed on 2018-09-28  0:23:17

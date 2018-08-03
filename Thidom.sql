CREATE DATABASE  IF NOT EXISTS `thidom` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `thidom`;
-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: my-rpi.no-ip.info    Database: thidom
-- ------------------------------------------------------
-- Server version	5.5.57-0+deb8u1

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
-- Table structure for table `ConnectLog`
--

DROP TABLE IF EXISTS `ConnectLog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ConnectLog` (
  `IP` varchar(50) NOT NULL,
  `City` varchar(20) NOT NULL,
  `Region` varchar(20) NOT NULL,
  `Country` varchar(20) NOT NULL,
  `Date` datetime NOT NULL,
  `IndentificationType` varchar(45) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Device`
--

DROP TABLE IF EXISTS `Device`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Device` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Nom` varchar(100) COLLATE utf8_bin NOT NULL,
  `CarteId` varchar(11) COLLATE utf8_bin NOT NULL,
  `Configuration` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `Lieux_Id` int(11) DEFAULT NULL,
  `Type_Id` int(11) NOT NULL,
  `Visible` tinyint(1) DEFAULT NULL,
  `History` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `FK_Device` (`Lieux_Id`),
  KEY `FK_Device_Type_Device_Id` (`Type_Id`),
  CONSTRAINT `FK_Device` FOREIGN KEY (`Lieux_Id`) REFERENCES `Lieux` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_Device_Type_Device_Id` FOREIGN KEY (`Type_Id`) REFERENCES `Type_Device` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Lieux`
--

DROP TABLE IF EXISTS `Lieux`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Lieux` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Img` varchar(100) NOT NULL,
  `Nom` varchar(50) NOT NULL,
  `Backgd` varchar(100) NOT NULL,
  `Position` int(11) NOT NULL,
  `Visible` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `Nom` (`Nom`)
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Log`
--

DROP TABLE IF EXISTS `Log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Log` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `DeviceId` int(11) NOT NULL,
  `Date` datetime NOT NULL,
  `Action` varchar(500) NOT NULL,
  `Message` varchar(100) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Planning`
--

DROP TABLE IF EXISTS `Planning`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Planning` (
  `Id` int(100) NOT NULL AUTO_INCREMENT,
  `CmdDevice_Id` int(100) NOT NULL,
  `Date` date DEFAULT NULL,
  `Days` varchar(10) DEFAULT NULL,
  `Hours` time NOT NULL,
  `Status` int(10) NOT NULL,
  `Activate` int(11) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Scenario`
--

DROP TABLE IF EXISTS `Scenario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Scenario` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `XmlId` int(11) NOT NULL,
  `Conditions` text NOT NULL,
  `Actions` text NOT NULL,
  `SequenceNo` int(11) NOT NULL,
  `LastTimeEvents` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `NextTimeEvents` datetime DEFAULT NULL,
  `NextActionEvents` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `XmlID` (`XmlId`),
  CONSTRAINT `FK_Scenario_Xml_Scenario_XmlId` FOREIGN KEY (`XmlId`) REFERENCES `Scenario_Xml` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Scenario_Xml`
--

DROP TABLE IF EXISTS `Scenario_Xml`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Scenario_Xml` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(200) NOT NULL,
  `XML` text NOT NULL,
  `Status` int(11) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `ID` (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Temperature`
--

DROP TABLE IF EXISTS `Temperature`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Temperature` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `temp` float NOT NULL DEFAULT '0',
  `Lieux_Id` int(11) NOT NULL DEFAULT '0',
  `cmd_device_Id` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Id`),
  KEY `Lieux_Id` (`Lieux_Id`),
  KEY `cmd_device_Id` (`cmd_device_Id`),
  KEY `date` (`date`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Temperature_Temp`
--

DROP TABLE IF EXISTS `Temperature_Temp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Temperature_Temp` (
  `Date` datetime NOT NULL,
  `Temp` float NOT NULL,
  `Lieux` varchar(50) NOT NULL,
  `Lieux_Id` int(11) NOT NULL,
  `Cmd_device_Id` int(11) NOT NULL,
  KEY `Etat_IO_ID` (`Cmd_device_Id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Type_Device`
--

DROP TABLE IF EXISTS `Type_Device`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Type_Device` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Type` varchar(100) NOT NULL,
  `Widget_Id` varchar(100) NOT NULL,
  `Template` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `Type` (`Type`),
  KEY `widget` (`Widget_Id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Type_Device`
--

LOCK TABLES `Type_Device` WRITE;
/*!40000 ALTER TABLE `Type_Device` DISABLE KEYS */;
INSERT INTO `Type_Device` VALUES (0,'Temperature','5','{\"icons\":\"Temperature\"}'),(1,'Lampe','1','{\"icons\":\"Light\"}'),(2,'Chauffage','6','{\"icons\":\"Chauffage\"}'),(5,'Alerte','4','{\"icons\":\"Alerte\"}'),(6,'Lampe RGB','3','{\"icons\":\"RGB_Light\"}'),(8,'Plugins','7','{\"icons\":\"Plugins\"}'),(9,'Lampe2','1','{\"icons\":\"Light2\"}'),(11,'Switch','1','{\"icons\":\"Switch\"}'),(12,'Door','9','{\"icons\":\"Door\"}'),(13,'BackgroundColor','3','{\"icons\":\"BackgroundColor\"}'),(14,'Presence','4','{\"icons\":\"Motion\"}'),(15,'Webcam','8','{\"icons\":\"Webcam\"}');
/*!40000 ALTER TABLE `Type_Device` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User`
--

DROP TABLE IF EXISTS `User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `UserName` varchar(20) NOT NULL,
  `UserPass` varchar(80) NOT NULL,
  `LastLog` varchar(40) NOT NULL,
  `Background` varchar(100) NOT NULL,
  `UserHash` varchar(32) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User`
--

LOCK TABLES `User` WRITE;
/*!40000 ALTER TABLE `User` DISABLE KEYS */;
INSERT INTO `User` VALUES (1,'admin','8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918','','','c3d67c82e7e145b950d2e8413448170d');
/*!40000 ALTER TABLE `User` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cmd_device`
--

DROP TABLE IF EXISTS `cmd_device`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmd_device` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Nom` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Device_Id` int(11) NOT NULL,
  `DeviceId` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `sensor_attachId` int(11) NOT NULL DEFAULT '-1',
  `Request` varchar(500) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `Value` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Etat` varchar(4) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `Date` datetime NOT NULL,
  `Alert_Time` datetime DEFAULT NULL,
  `RAZ` int(11) DEFAULT NULL,
  `Type_Id` int(11) DEFAULT NULL,
  `Visible` tinyint(1) DEFAULT NULL,
  `Type` varchar(10) DEFAULT NULL,
  `Unite` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `FK_cmd_device_Device_Id` (`Device_Id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `Module_Type`
--

DROP TABLE IF EXISTS `Module_Type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Module_Type` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `ModuleName` varchar(45) NOT NULL,
  `ModuleType` varchar(45) NOT NULL,
  PRIMARY KEY (`Id`,`ModuleName`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Module_Type`
--

LOCK TABLES `Module_Type` WRITE;
/*!40000 ALTER TABLE `Module_Type` DISABLE KEYS */;
INSERT INTO `Module_Type` VALUES (1,'NRF24',''),(2,'Domogeek','Plugins'),(3,'Livebox','Plugins'),(4,'Webcam','Plugins'),(5,'Telegram','Plugins'),(6,'Virtuel','Virtuel');
/*!40000 ALTER TABLE `Module_Type` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `widget`
--

DROP TABLE IF EXISTS `widget`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `widget` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(20) NOT NULL,
  `Type` varchar(20) DEFAULT NULL,
  `ModuleType_Id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `widget`
--

LOCK TABLES `widget` WRITE;
/*!40000 ALTER TABLE `widget` DISABLE KEYS */;
INSERT INTO `widget` VALUES (0,'Temperature','Text',',1,'),(1,'Relay','Binary',',1,11,99,6,'),(2,'Dimmer','Slider',',1,22,11,'),(3,'RGB','Color',',1,'),(4,'Motion','Binary',',1,'),(5,'Thermostat','Slider',',1,'),(6,'Humidity','Text',',1,');

/*!40000 ALTER TABLE `widget` ENABLE KEYS */;
UNLOCK TABLES;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-09-12 10:37:52

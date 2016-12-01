-- MySQL dump 10.13  Distrib 5.5.52, for debian-linux-gnu (armv7l)
--
-- Host: localhost    Database: thidom
-- ------------------------------------------------------
-- Server version	5.5.52-0+deb8u1

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
-- Table structure for table `Device`
--

DROP TABLE IF EXISTS `Device`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Device` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nom` varchar(100) COLLATE utf8_bin NOT NULL,
  `CarteID` varchar(11) COLLATE utf8_bin NOT NULL,
  `Configuration` varchar(200) COLLATE utf8_bin NOT NULL,
  `Carte_ID_OLD_433` varchar(11) COLLATE utf8_bin NOT NULL,
  `Lieux_ID` int(11) NOT NULL,
  `Type_ID` int(11) NOT NULL,
  `Visible` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=94 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Etat_IO`
--

DROP TABLE IF EXISTS `Etat_IO`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Etat_IO` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DeviceID` varchar(100) NOT NULL,
  `Carte_ID` varchar(11) NOT NULL,
  `Carte_ID_OLD_433` varchar(11) NOT NULL,
  `sensor_attachID` int(11) NOT NULL,
  `Request` varchar(100) DEFAULT NULL,
  `Nom` varchar(100) NOT NULL,
  `Type` varchar(100) NOT NULL,
  `TypeID` int(11) NOT NULL,
  `Lieux` varchar(20) NOT NULL,
  `LieuxID` int(11) NOT NULL,
  `Value` varchar(200) NOT NULL,
  `Etat` int(4) DEFAULT NULL,
  `Date` datetime NOT NULL,
  `widget` varchar(20) NOT NULL,
  `Alert_Time` datetime NOT NULL,
  `RAZ` int(11) DEFAULT NULL,
  `Visible` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `DeviceID_Carte_ID` (`DeviceID`,`Carte_ID`),
  KEY `sensor_attachID` (`sensor_attachID`),
  KEY `Nom` (`Nom`),
  KEY `Type` (`Type`),
  KEY `TypeID` (`TypeID`),
  KEY `Lieux` (`Lieux`)
) ENGINE=MyISAM AUTO_INCREMENT=72 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Lieux`
--

DROP TABLE IF EXISTS `Lieux`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Lieux` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Img` varchar(100) NOT NULL,
  `Nom` varchar(50) NOT NULL,
  `Backgd` varchar(100) NOT NULL,
  `Position` int(11) NOT NULL,
  `Visible` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `Nom` (`Nom`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Log`
--

DROP TABLE IF EXISTS `Log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Log` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DeviceID` int(11) NOT NULL,
  `DATE` datetime NOT NULL,
  `ACTION` varchar(500) NOT NULL,
  `Message` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `DATE` (`DATE`),
  KEY `ACTION` (`ACTION`)
) ENGINE=MyISAM AUTO_INCREMENT=69425 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Scenario`
--

DROP TABLE IF EXISTS `Scenario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Scenario` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `XmlID` int(11) NOT NULL,
  `Conditions` text NOT NULL,
  `Actions` text NOT NULL,
  `SequenceNo` int(11) NOT NULL,
  `LastTimeEvents` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `NextTimeEvents` datetime DEFAULT NULL,
  `NextActionEvents` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `XmlID` (`XmlID`)
) ENGINE=MyISAM AUTO_INCREMENT=762 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Scenario_Xml`
--

DROP TABLE IF EXISTS `Scenario_Xml`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Scenario_Xml` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(200) NOT NULL,
  `XML` text NOT NULL,
  `Status` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Temperature`
--

DROP TABLE IF EXISTS `Temperature`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Temperature` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `temp` float NOT NULL,
  `lieux` varchar(20) NOT NULL,
  `Lieux_ID` varchar(5) NOT NULL,
  `cmd_device_ID` int(5) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Lieux_ID` (`Lieux_ID`),
  KEY `date` (`date`)
) ENGINE=MyISAM AUTO_INCREMENT=320605 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Temperature_Temp`
--

DROP TABLE IF EXISTS `Temperature_Temp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Temperature_Temp` (
  `date` datetime NOT NULL,
  `temp` float NOT NULL,
  `lieux` varchar(50) NOT NULL,
  `Lieux_ID` int(11) NOT NULL,
  `cmd_device_ID` int(11) NOT NULL,
  KEY `Etat_IO_ID` (`cmd_device_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Type_Device`
--

DROP TABLE IF EXISTS `Type_Device`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Type_Device` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Type` varchar(100) NOT NULL,
  `widget` varchar(100) NOT NULL,
  `Action` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Type` (`Type`),
  KEY `widget` (`widget`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO Type_Device values ("1","Lampe","Light","Action");
INSERT INTO Type_Device values ("2","Chauffage_old","plus_moins","Slider");
INSERT INTO Type_Device values ("0","Temperaturepiece","Temperature","Temperature");
INSERT INTO Type_Device values ("4","Temperatureobjet","Temperature_objet","Temperature");
INSERT INTO Type_Device values ("5","Alerte","Alerte","Alerte");
INSERT INTO Type_Device values ("6","LampeRGB","RGB","Action");
INSERT INTO Type_Device values ("8","Plugins","Plugins","Plugins");
INSERT INTO Type_Device values ("9","Lampe2","Light","Action");
INSERT INTO Type_Device values ("10","Chauffage","Chauffage","Slider");
INSERT INTO Type_Device values ("11","Switch","Switch","Action");
INSERT INTO Type_Device values ("12","OpeningDetector","OpeningDetector","Info");
INSERT INTO Type_Device values ("13","BackgroundColor","BackgroundColor","Info");
INSERT INTO Type_Device values ("14","Presence","Presence","Info");

--
-- Table structure for table `User`
--

DROP TABLE IF EXISTS `User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `USERNAME` varchar(20) NOT NULL,
  `USERPASS` varchar(80) NOT NULL,
  `BACKGROUND` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmd_device`
--

DROP TABLE IF EXISTS `cmd_device`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmd_device` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nom` varchar(100) NOT NULL,
  `Device_ID` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `DeviceId` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `sensor_attachID` int(11) NOT NULL,
  `Request` varchar(500) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `Value` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Etat` varchar(4) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `Date` datetime NOT NULL,
  `Alert_Time` datetime NOT NULL,
  `RAZ` int(11) DEFAULT NULL,
  `Type_ID` int(11) DEFAULT NULL,
  `Visible` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=147 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `planning`
--

DROP TABLE IF EXISTS `planning`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `planning` (
  `ID` int(100) NOT NULL AUTO_INCREMENT,
  `ETAT_IO_ID` int(100) NOT NULL,
  `DATE` datetime DEFAULT NULL,
  `DAYS` varchar(10) DEFAULT NULL,
  `HOURS` time NOT NULL,
  `STATUS` int(10) NOT NULL,
  `ACTIVATE` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=97 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `test`
--

DROP TABLE IF EXISTS `test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `test` (
  `date` datetime NOT NULL,
  `temp` float NOT NULL,
  `lieux` varchar(50) NOT NULL,
  `Etat_IO_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `widget`
--

DROP TABLE IF EXISTS `widget`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `widget` (
  `name` varchar(20) NOT NULL,
  `type` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;



INSERT INTO widget values ("Lampe","on_off");
INSERT INTO widget values ("button","button");
INSERT INTO widget values ("plus_moins","button");
INSERT INTO widget values ("Alerte","Alerte");
INSERT INTO widget values ("RGB","on_off");
INSERT INTO widget values ("Light","Light");
INSERT INTO widget values ("Window","Window");
INSERT INTO widget values ("Temperature","Temperature");
INSERT INTO widget values ("Thermostat","Slider");

-- Dump completed on 2016-11-25 12:04:57

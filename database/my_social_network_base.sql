DROP DATABASE IF EXISTS `my_social_network_base`;
CREATE DATABASE  IF NOT EXISTS `my_social_network_base` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `my_social_network_base`;
-- MySQL dump 10.13  Distrib 5.6.13, for osx10.6 (i386)
--
-- Host: 127.0.0.1    Database: my_social_network_base
-- ------------------------------------------------------
-- Server version	5.6.25

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
-- Table structure for table `Members`
--

DROP TABLE IF EXISTS `Members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Members` (
  `pseudo` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `profile` text,
  `picture_path` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`pseudo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Members`
--


/*!40000 ALTER TABLE `Members` DISABLE KEYS */;
INSERT INTO `Members` VALUES ('admin','c6aa01bd261e501b1fea93c41fe46dc7','Je suis l\'admin.','admin1510834604.png'),('ben','cc4902e2506fc6de54e53489314c615a','Je suis benoit13.','ben1510834588.png'),('bob','6bc8d5a0ad1818c0924255f5e56e68c6',NULL,NULL),('caro','e82d99e3aaa83e1746bda2a58b99ba17',NULL,NULL),('fred','90598d58045d3548866f853df199fb55',NULL,NULL),('guest','b6384a74aaf072666c8fd7c9ce58c428',NULL,NULL);
/*!40000 ALTER TABLE `Members` ENABLE KEYS */;


--
-- Table structure for table `Follows`
--

DROP TABLE IF EXISTS `Follows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Follows` (
  `follower` varchar(128) NOT NULL,
  `followee` varchar(128) NOT NULL,
  PRIMARY KEY (`follower`,`followee`),
  KEY `members_followee_fk` (`followee`),
  CONSTRAINT `members_followee_fk` FOREIGN KEY (`followee`) REFERENCES `Members` (`pseudo`),
  CONSTRAINT `members_follower_fk` FOREIGN KEY (`follower`) REFERENCES `Members` (`pseudo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Follows`
--


/*!40000 ALTER TABLE `Follows` DISABLE KEYS */;
INSERT INTO `Follows` VALUES ('ben','admin'),('admin','ben'),('bob','ben'),('caro','ben'),('ben','caro'),('ben','fred'),('caro','fred'),('admin','guest'),('ben','guest');
/*!40000 ALTER TABLE `Follows` ENABLE KEYS */;



--
-- Table structure for table `Messages`
--

DROP TABLE IF EXISTS `Messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Messages` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `author` varchar(128) NOT NULL,
  `recipient` varchar(128) NOT NULL,
  `body` text NOT NULL,
  `private` tinyint(1) NOT NULL DEFAULT '0',
  `date_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`post_id`),
  KEY `members_author_fk` (`author`),
  KEY `members_recipient_fk` (`recipient`),
  CONSTRAINT `members_author_fk` FOREIGN KEY (`author`) REFERENCES `Members` (`pseudo`),
  CONSTRAINT `members_recipient_fk` FOREIGN KEY (`recipient`) REFERENCES `Members` (`pseudo`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Messages`
--


/*!40000 ALTER TABLE `Messages` DISABLE KEYS */;
INSERT INTO `Messages` VALUES (2,'ben','ben','message 1',0,'2015-07-09 10:11:33'),(3,'ben','ben','message 2',0,'2015-07-09 10:12:59'),(5,'caro','ben','message de caro',0,'2015-07-09 10:14:03'),(8,'ben','ben','test',1,'2015-07-09 10:58:10'),(9,'ben','ben','test',0,'2015-07-09 10:58:15'),(19,'caro','caro','myself',0,'2015-07-09 11:29:20'),(47,'ben','caro','a longer message for caro in order to see how it wrapped around in the message table.',0,'2015-07-09 11:34:44'),(48,'ben','fred','this is a message to fred',0,'2015-07-09 18:15:27'),(49,'ben','fred','this is a private message to fred',1,'2015-07-09 18:15:36'),(58,'ben','fred','hello',0,'2015-07-19 00:16:01'),(59,'ben','fred','aaa',0,'2015-07-19 00:17:41'),(61,'admin','admin','test',0,'2015-10-30 11:32:37'),(86,'ben','caro','ben to caro',0,'2015-12-16 12:50:29');
/*!40000 ALTER TABLE `Messages` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-12-18 12:29:20

-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: dmt_db
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activities`
--

DROP TABLE IF EXISTS `activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `action` varchar(100) NOT NULL,
  `document_id` bigint(20) unsigned DEFAULT NULL,
  `final_approval` varchar(10) NOT NULL DEFAULT '0',
  `document_control_number` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `from_user_id` varchar(10) DEFAULT NULL,
  `routed_to` bigint(20) unsigned DEFAULT NULL,
  `to_external` varchar(10) NOT NULL DEFAULT '0',
  `final_remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activities_document_id_foreign` (`document_id`),
  KEY `activities_user_id_foreign` (`user_id`),
  CONSTRAINT `activities_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`document_id`) ON DELETE CASCADE,
  CONSTRAINT `activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=1669 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activities`
--

LOCK TABLES `activities` WRITE;
/*!40000 ALTER TABLE `activities` DISABLE KEYS */;
INSERT INTO `activities` VALUES (1570,'upload',174,'0','22122025-00001',22,'22',NULL,'0',NULL,'2025-12-22 02:23:20','2025-12-22 02:23:20'),(1571,'view',174,'0','22122025-00001',22,NULL,NULL,'0',NULL,'2025-12-22 02:23:48','2025-12-22 02:23:48'),(1572,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-22 03:14:18','2025-12-22 03:14:18'),(1573,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-22 03:14:37','2025-12-22 03:14:37'),(1574,'view',174,'0','22122025-00001',18,NULL,NULL,'0',NULL,'2025-12-22 03:23:16','2025-12-22 03:23:16'),(1575,'view',174,'0','22122025-00001',18,NULL,NULL,'0',NULL,'2025-12-22 03:24:05','2025-12-22 03:24:05'),(1576,'view',174,'0','22122025-00001',18,NULL,NULL,'0',NULL,'2025-12-22 03:28:34','2025-12-22 03:28:34'),(1577,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-22 03:30:53','2025-12-22 03:30:53'),(1578,'upload',175,'0','22122025-00002',17,'17',NULL,'0','qweqwe','2025-12-22 03:31:14','2025-12-22 03:31:14'),(1579,'view',175,'0','22122025-00002',17,NULL,NULL,'0',NULL,'2025-12-22 03:31:23','2025-12-22 03:31:23'),(1580,'view',175,'0','22122025-00002',17,NULL,NULL,'0',NULL,'2025-12-22 03:35:54','2025-12-22 03:35:54'),(1581,'view',174,'0','22122025-00001',34,NULL,NULL,'0',NULL,'2025-12-22 03:36:05','2025-12-22 03:36:05'),(1582,'view',174,'0','22122025-00001',34,NULL,NULL,'0',NULL,'2025-12-22 03:36:24','2025-12-22 03:36:24'),(1583,'view',175,'0','22122025-00002',17,NULL,NULL,'0',NULL,'2025-12-22 07:00:12','2025-12-22 07:00:12'),(1584,'view',175,'0','22122025-00002',17,NULL,NULL,'0',NULL,'2025-12-22 07:17:56','2025-12-22 07:17:56'),(1585,'upload',176,'0','22122025-00003',21,'21',NULL,'0',NULL,'2025-12-22 07:19:22','2025-12-22 07:19:22'),(1586,'view',176,'0','22122025-00003',17,NULL,NULL,'0',NULL,'2025-12-22 07:19:29','2025-12-22 07:19:29'),(1587,'upload',177,'0','22122025-00004',21,'21',NULL,'0','asdada','2025-12-22 07:19:51','2025-12-22 07:19:51'),(1588,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-22 07:20:12','2025-12-22 07:20:12'),(1589,'confirm',174,'0','22122025-00001',17,'22',NULL,'0',NULL,'2025-12-22 07:20:14','2025-12-22 07:20:14'),(1590,'view',177,'0','22122025-00004',17,NULL,NULL,'0',NULL,'2025-12-22 07:20:30','2025-12-22 07:20:30'),(1591,'confirm',177,'0','22122025-00004',17,'21',NULL,'0',NULL,'2025-12-22 07:20:31','2025-12-22 07:20:31'),(1592,'view',177,'0','22122025-00004',21,NULL,NULL,'0',NULL,'2025-12-22 07:20:37','2025-12-22 07:20:37'),(1593,'view',177,'0','22122025-00004',21,NULL,NULL,'0',NULL,'2025-12-22 07:20:49','2025-12-22 07:20:49'),(1594,'route',177,'1','22122025-00004',21,'21',34,'1','fasdadasd','2025-12-22 07:21:07','2025-12-22 07:21:07'),(1595,'route',177,'1','22122025-00004',21,'21',34,'1','fasdadasd','2025-12-22 07:21:07','2025-12-22 07:21:07'),(1596,'route',177,'1','22122025-00004',21,'21',34,'1','fasdadasd','2025-12-22 07:21:07','2025-12-22 07:21:07'),(1597,'view',177,'0','22122025-00004',34,NULL,NULL,'0',NULL,'2025-12-22 07:21:35','2025-12-22 07:21:35'),(1598,'for-discussion',177,'0','22122025-00004',34,'34',NULL,'0',NULL,'2025-12-22 07:21:48','2025-12-22 07:21:48'),(1599,'view',177,'0','22122025-00004',17,NULL,NULL,'0',NULL,'2025-12-22 07:21:57','2025-12-22 07:21:57'),(1600,'route',177,'1','22122025-00004',17,'17',NULL,'1','approved by the ddg','2025-12-22 07:22:15','2025-12-22 07:22:15'),(1601,'view',177,'0','22122025-00004',23,NULL,NULL,'0',NULL,'2025-12-22 07:41:40','2025-12-22 07:41:40'),(1602,'view',177,'0','22122025-00004',23,NULL,NULL,'0',NULL,'2025-12-22 07:41:42','2025-12-22 07:41:42'),(1603,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-22 07:51:55','2025-12-22 07:51:55'),(1604,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-22 08:02:00','2025-12-22 08:02:00'),(1605,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-22 08:02:06','2025-12-22 08:02:06'),(1606,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-22 08:06:40','2025-12-22 08:06:40'),(1607,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-22 08:07:18','2025-12-22 08:07:18'),(1608,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-22 08:10:41','2025-12-22 08:10:41'),(1609,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-22 08:14:17','2025-12-22 08:14:17'),(1610,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-22 08:18:41','2025-12-22 08:18:41'),(1611,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-22 08:18:42','2025-12-22 08:18:42'),(1612,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-22 08:24:41','2025-12-22 08:24:41'),(1613,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-22 08:25:01','2025-12-22 08:25:01'),(1614,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-22 08:25:47','2025-12-22 08:25:47'),(1615,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-22 08:36:03','2025-12-22 08:36:03'),(1616,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-22 08:37:53','2025-12-22 08:37:53'),(1617,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-22 08:42:11','2025-12-22 08:42:11'),(1618,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-22 08:42:36','2025-12-22 08:42:36'),(1619,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-22 08:44:38','2025-12-22 08:44:38'),(1620,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-22 08:45:21','2025-12-22 08:45:21'),(1621,'route',174,'0','22122025-00001',17,'17',34,'1','asdadasad','2025-12-22 08:45:51','2025-12-22 08:45:51'),(1622,'view',174,'0','22122025-00001',34,NULL,NULL,'0',NULL,'2025-12-22 08:46:15','2025-12-22 08:46:15'),(1623,'for-discussion',174,'0','22122025-00001',34,'34',NULL,'0',NULL,'2025-12-22 08:46:25','2025-12-22 08:46:25'),(1624,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-22 08:46:29','2025-12-22 08:46:29'),(1625,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-22 08:53:18','2025-12-22 08:53:18'),(1626,'view',177,'0','22122025-00004',22,NULL,NULL,'0',NULL,'2025-12-23 00:23:50','2025-12-23 00:23:50'),(1627,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-23 00:24:31','2025-12-23 00:24:31'),(1628,'view',175,'0','22122025-00002',17,NULL,NULL,'0',NULL,'2025-12-23 00:24:47','2025-12-23 00:24:47'),(1629,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-23 00:42:18','2025-12-23 00:42:18'),(1630,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-23 00:42:30','2025-12-23 00:42:30'),(1631,'view',175,'0','22122025-00002',17,NULL,NULL,'0',NULL,'2025-12-23 00:42:40','2025-12-23 00:42:40'),(1632,'confirm',175,'0','22122025-00002',17,'17',NULL,'0',NULL,'2025-12-23 00:42:41','2025-12-23 00:42:41'),(1633,'view',175,'0','22122025-00002',17,NULL,NULL,'0',NULL,'2025-12-23 01:11:13','2025-12-23 01:11:13'),(1634,'view',175,'0','22122025-00002',17,NULL,NULL,'0',NULL,'2025-12-23 01:39:57','2025-12-23 01:39:57'),(1635,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-23 02:41:16','2025-12-23 02:41:16'),(1636,'view',198,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-23 03:06:34','2025-12-23 03:06:34'),(1637,'view',200,'0','22122025-00003',17,NULL,NULL,'0',NULL,'2025-12-23 03:06:38','2025-12-23 03:06:38'),(1638,'view',195,'0','22122025-00002',17,NULL,NULL,'0',NULL,'2025-12-23 03:06:41','2025-12-23 03:06:41'),(1639,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-23 03:22:55','2025-12-23 03:22:55'),(1640,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-23 03:24:18','2025-12-23 03:24:18'),(1641,'view',179,'0','22122025-00002',17,NULL,NULL,'0',NULL,'2025-12-23 06:24:23','2025-12-23 06:24:23'),(1642,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-23 06:24:27','2025-12-23 06:24:27'),(1643,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-23 06:24:35','2025-12-23 06:24:35'),(1644,'upload',206,'0','23122025-00001',17,'17',NULL,'0','sidfhisudhfisfhfhuisdfh','2025-12-23 06:25:24','2025-12-23 06:25:24'),(1645,'view',206,'0','23122025-00001',17,NULL,NULL,'0',NULL,'2025-12-23 06:25:35','2025-12-23 06:25:35'),(1646,'view',206,'0','23122025-00001',17,NULL,NULL,'0',NULL,'2025-12-23 06:26:19','2025-12-23 06:26:19'),(1647,'view',179,'0','22122025-00002',17,NULL,NULL,'0',NULL,'2025-12-23 06:38:15','2025-12-23 06:38:15'),(1648,'view',178,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-23 06:38:17','2025-12-23 06:38:17'),(1649,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-23 06:38:18','2025-12-23 06:38:18'),(1650,'view',178,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-23 06:40:18','2025-12-23 06:40:18'),(1651,'view',182,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-23 06:40:19','2025-12-23 06:40:19'),(1652,'view',186,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-23 06:42:09','2025-12-23 06:42:09'),(1653,'view',190,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-23 06:42:11','2025-12-23 06:42:11'),(1654,'view',194,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-23 07:49:07','2025-12-23 07:49:07'),(1655,'view',198,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2025-12-23 07:49:08','2025-12-23 07:49:08'),(1656,'view',179,'0','22122025-00002',17,NULL,NULL,'0',NULL,'2025-12-23 07:49:12','2025-12-23 07:49:12'),(1657,'view',179,'0','22122025-00002',17,NULL,NULL,'0',NULL,'2025-12-23 07:49:15','2025-12-23 07:49:15'),(1658,'view',206,'0','23122025-00001',17,NULL,NULL,'0',NULL,'2025-12-23 07:49:20','2025-12-23 07:49:20'),(1659,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2026-01-05 01:42:14','2026-01-05 01:42:14'),(1660,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2026-01-05 01:42:18','2026-01-05 01:42:18'),(1661,'view',174,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2026-01-05 01:42:22','2026-01-05 01:42:22'),(1662,'route',174,'0','22122025-00001',17,'17',34,'1','discussed and approved verbally','2026-01-05 01:43:01','2026-01-05 01:43:01'),(1663,'view',174,'0','22122025-00001',34,NULL,NULL,'0',NULL,'2026-01-05 01:43:38','2026-01-05 01:43:38'),(1664,'approved',174,'1','22122025-00001',34,'34',NULL,'0','sige ok na apaka angasss','2026-01-05 01:43:52','2026-01-05 01:43:52'),(1665,'view',177,'0','22122025-00004',34,NULL,NULL,'0',NULL,'2026-01-05 01:44:14','2026-01-05 01:44:14'),(1666,'view',178,'0','22122025-00001',17,NULL,NULL,'0',NULL,'2026-01-05 01:45:23','2026-01-05 01:45:23'),(1667,'route',178,'0','22122025-00001',17,'17',19,'1','for pre approval po ty','2026-01-05 01:45:47','2026-01-05 01:45:47'),(1668,'upload',207,'0','05012026-00001',17,'17',NULL,'0','adsads','2026-01-05 02:05:27','2026-01-05 02:05:27');
/*!40000 ALTER TABLE `activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `approval_table`
--

DROP TABLE IF EXISTS `approval_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `approval_table` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `document_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `approval_type` varchar(100) NOT NULL,
  `remarks` varchar(1000) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `approval_table_document_id_foreign` (`document_id`),
  CONSTRAINT `approval_table_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`document_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `approval_table`
--

LOCK TABLES `approval_table` WRITE;
/*!40000 ALTER TABLE `approval_table` DISABLE KEYS */;
INSERT INTO `approval_table` VALUES (64,177,34,'final-approval','For Discussion',1,'2025-12-22 07:21:07','2025-12-22 07:21:48'),(65,177,34,'final-approval','fasdadasd',0,'2025-12-22 07:21:07','2025-12-22 07:21:07'),(66,177,34,'final-approval','fasdadasd',0,'2025-12-22 07:21:07','2025-12-22 07:21:07'),(67,174,34,'final-approval','For Discussion',1,'2025-12-22 08:45:51','2025-12-22 08:46:25'),(68,174,34,'final-approval',NULL,1,'2026-01-05 01:43:01','2026-01-05 01:43:52'),(69,178,19,'pre-approval','for pre approval po ty',0,'2026-01-05 01:45:47','2026-01-05 01:45:47');
/*!40000 ALTER TABLE `approval_table` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `document_types`
--

DROP TABLE IF EXISTS `document_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `document_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `document_type` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `document_types`
--

LOCK TABLES `document_types` WRITE;
/*!40000 ALTER TABLE `document_types` DISABLE KEYS */;
INSERT INTO `document_types` VALUES (1,'MEMO',NULL,'2025-11-13 18:05:50','2025-11-13 18:05:50'),(3,'CSW',NULL,'2025-11-23 20:51:25','2025-11-23 20:51:25'),(4,'TESDA ORDER',NULL,'2025-11-23 20:51:37','2025-11-23 20:51:37'),(5,'TESDA CIRCULAR',NULL,'2025-11-23 20:51:43','2025-11-23 20:51:43'),(6,'ROUTE SLIP',NULL,'2025-11-23 20:51:50','2025-11-23 20:51:50'),(7,'TOR',NULL,'2025-11-23 20:51:56','2025-11-23 20:51:56'),(8,'INVITATION LETTERS & CONFORME',NULL,'2025-11-23 20:52:02','2025-11-23 20:52:02'),(9,'POSITION PAPER',NULL,'2025-11-23 20:52:33','2025-11-23 20:52:33'),(10,'LETTER',NULL,'2025-11-23 20:52:41','2025-11-23 20:52:41'),(11,'BRIEFING NOTE',NULL,'2025-11-23 20:52:47','2025-11-23 20:52:47'),(12,'MESSAGE',NULL,'2025-11-23 20:52:52','2025-11-23 20:52:52'),(13,'CERTIFICATES',NULL,'2025-11-23 20:52:57','2025-11-23 20:52:57'),(14,'ORS, DV\'s',NULL,'2025-11-23 20:53:03','2025-11-23 20:53:03');
/*!40000 ALTER TABLE `document_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents` (
  `document_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `document_control_number` varchar(255) NOT NULL,
  `document_code` varchar(1000) NOT NULL,
  `date_received` datetime DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `particular` text NOT NULL,
  `office_origin` varchar(100) NOT NULL,
  `destination_office` varchar(100) DEFAULT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `sender_id` int(11) NOT NULL DEFAULT 0,
  `recipient_id` varchar(10) DEFAULT NULL,
  `document_form` varchar(50) NOT NULL,
  `document_type` varchar(50) NOT NULL,
  `date_of_document` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `signatory` varchar(100) NOT NULL,
  `date_forwarded` datetime DEFAULT NULL,
  `receipt_confirmation` int(11) NOT NULL DEFAULT 0,
  `receipt_confirmed_by` int(11) NOT NULL DEFAULT 0,
  `involved_office` varchar(255) DEFAULT NULL,
  `action_taken` text DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `revision_status` int(11) NOT NULL DEFAULT 0,
  `remarks` varchar(255) DEFAULT NULL,
  `confidentiality` varchar(255) NOT NULL DEFAULT 'None',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`document_id`)
) ENGINE=InnoDB AUTO_INCREMENT=208 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
INSERT INTO `documents` VALUES (174,'22122025-00001','test','2025-12-22 00:00:00',NULL,'test','EOD-PO','ODDG-PP',22,17,NULL,'PDF','CSW',NULL,NULL,'test','2026-01-05 09:43:52',1,17,'[\"EOD-PO\",\"EOD-PO\",\"ODDG-PP\"]',NULL,'Approved',0,NULL,'Normal','2025-12-22 02:23:20','2026-01-05 01:43:52'),(175,'22122025-00002','qwewe','2025-12-22 00:00:00',NULL,'qweqwe','ODDG-PP','ODDG-PP',17,0,'17','PDF','MEMO',NULL,NULL,'qwe','2025-12-20 11:31:14',1,17,'[\"ODDG-PP\",\"ODDG-PP\"]',NULL,'Complete',0,'qweqwe','Normal','2025-12-20 03:31:14','2025-12-23 00:42:41'),(176,'22122025-00003','etetst','2025-12-22 00:00:00',NULL,'awdasda','ODDG-PP','ODDG-PP',21,0,NULL,'PDF','TESDA ORDER',NULL,NULL,'asdad','2025-12-22 15:19:22',0,0,'[\"ODDG-PP\",\"ODDG-PP\"]',NULL,'Pending',0,NULL,'Normal','2025-12-22 07:19:22','2025-12-22 07:19:22'),(177,'22122025-00004','saad','2025-12-22 00:00:00',NULL,'asdas','ODDG-PP','PO',21,17,NULL,'PDF','TESDA ORDER',NULL,NULL,'asdad','2025-12-22 15:22:15',0,0,'[\"ODDG-PP\",\"ODDG-PP\"]',NULL,'Remanded',0,'asdada','Normal','2025-12-22 07:19:51','2025-12-22 07:22:15'),(178,'22122025-00001','test','2025-12-22 00:00:00',NULL,'test','EOD-PO','ODDG-PP',22,17,'19','PDF','CSW',NULL,NULL,'test','2026-01-05 09:45:47',1,17,'[\"EOD-PO\",\"EOD-PO\",\"ODDG-PP\"]',NULL,'For Approval',0,NULL,'Normal','2025-12-22 02:23:20','2026-01-05 01:45:47'),(179,'22122025-00002','qwewe','2025-12-22 00:00:00',NULL,'qweqwe','ODDG-PP','ODDG-PP',17,0,NULL,'PDF','MEMO',NULL,NULL,'qwe','2025-12-20 11:31:14',0,0,'[\"ODDG-PP\",\"ODDG-PP\"]',NULL,'Pending',0,'qweqwe','Normal','2025-12-20 03:31:14','2025-12-20 03:31:14'),(180,'22122025-00003','etetst','2025-12-22 00:00:00',NULL,'awdasda','ODDG-PP','ODDG-PP',21,0,NULL,'PDF','TESDA ORDER',NULL,NULL,'asdad','2025-12-22 15:19:22',0,0,'[\"ODDG-PP\",\"ODDG-PP\"]',NULL,'Pending',0,NULL,'Normal','2025-12-22 07:19:22','2025-12-22 07:19:22'),(181,'22122025-00004','saad','2025-12-22 00:00:00',NULL,'asdas','ODDG-PP','PO',21,17,NULL,'PDF','TESDA ORDER',NULL,NULL,'asdad','2025-12-22 15:22:15',0,0,'[\"ODDG-PP\",\"ODDG-PP\"]',NULL,'Remanded',0,'asdada','Normal','2025-12-22 07:19:51','2025-12-22 07:22:15'),(182,'22122025-00001','test','2025-12-22 00:00:00',NULL,'test','EOD-PO','ODDG-PP',22,17,NULL,'PDF','CSW',NULL,NULL,'test','2025-12-22 16:46:25',1,17,'[\"EOD-PO\",\"EOD-PO\",\"ODDG-PP\"]',NULL,'For Discussion',0,NULL,'Normal','2025-12-22 02:23:20','2025-12-22 08:46:25'),(183,'22122025-00002','qwewe','2025-12-22 00:00:00',NULL,'qweqwe','ODDG-PP','ODDG-PP',17,0,NULL,'PDF','MEMO',NULL,NULL,'qwe','2025-12-20 11:31:14',0,0,'[\"ODDG-PP\",\"ODDG-PP\"]',NULL,'Pending',0,'qweqwe','Normal','2025-12-20 03:31:14','2025-12-20 03:31:14'),(184,'22122025-00003','etetst','2025-12-22 00:00:00',NULL,'awdasda','ODDG-PP','ODDG-PP',21,0,NULL,'PDF','TESDA ORDER',NULL,NULL,'asdad','2025-12-22 15:19:22',0,0,'[\"ODDG-PP\",\"ODDG-PP\"]',NULL,'Pending',0,NULL,'Normal','2025-12-22 07:19:22','2025-12-22 07:19:22'),(185,'22122025-00004','saad','2025-12-22 00:00:00',NULL,'asdas','ODDG-PP','PO',21,17,NULL,'PDF','TESDA ORDER',NULL,NULL,'asdad','2025-12-22 15:22:15',0,0,'[\"ODDG-PP\",\"ODDG-PP\"]',NULL,'Remanded',0,'asdada','Normal','2025-12-22 07:19:51','2025-12-22 07:22:15'),(186,'22122025-00001','test','2025-12-22 00:00:00',NULL,'test','EOD-PO','ODDG-PP',22,17,NULL,'PDF','CSW',NULL,NULL,'test','2025-12-22 16:46:25',1,17,'[\"EOD-PO\",\"EOD-PO\",\"ODDG-PP\"]',NULL,'For Discussion',0,NULL,'Normal','2025-12-22 02:23:20','2025-12-22 08:46:25'),(187,'22122025-00002','qwewe','2025-12-22 00:00:00',NULL,'qweqwe','ODDG-PP','ODDG-PP',17,0,NULL,'PDF','MEMO',NULL,NULL,'qwe','2025-12-20 11:31:14',0,0,'[\"ODDG-PP\",\"ODDG-PP\"]',NULL,'Pending',0,'qweqwe','Normal','2025-12-20 03:31:14','2025-12-20 03:31:14'),(188,'22122025-00003','etetst','2025-12-22 00:00:00',NULL,'awdasda','ODDG-PP','ODDG-PP',21,0,NULL,'PDF','TESDA ORDER',NULL,NULL,'asdad','2025-12-22 15:19:22',0,0,'[\"ODDG-PP\",\"ODDG-PP\"]',NULL,'Pending',0,NULL,'Normal','2025-12-22 07:19:22','2025-12-22 07:19:22'),(189,'22122025-00004','saad','2025-12-22 00:00:00',NULL,'asdas','ODDG-PP','PO',21,17,NULL,'PDF','TESDA ORDER',NULL,NULL,'asdad','2025-12-22 15:22:15',0,0,'[\"ODDG-PP\",\"ODDG-PP\"]',NULL,'Remanded',0,'asdada','Normal','2025-12-22 07:19:51','2025-12-22 07:22:15'),(190,'22122025-00001','test','2025-12-22 00:00:00',NULL,'test','EOD-PO','ODDG-PP',22,17,NULL,'PDF','CSW',NULL,NULL,'test','2025-12-22 16:46:25',1,17,'[\"EOD-PO\",\"EOD-PO\",\"ODDG-PP\"]',NULL,'For Discussion',0,NULL,'Normal','2025-12-22 02:23:20','2025-12-22 08:46:25'),(191,'22122025-00002','qwewe','2025-12-22 00:00:00',NULL,'qweqwe','ODDG-PP','ODDG-PP',17,0,NULL,'PDF','MEMO',NULL,NULL,'qwe','2025-12-20 11:31:14',0,0,'[\"ODDG-PP\",\"ODDG-PP\"]',NULL,'Pending',0,'qweqwe','Normal','2025-12-20 03:31:14','2025-12-20 03:31:14'),(192,'22122025-00003','etetst','2025-12-22 00:00:00',NULL,'awdasda','ODDG-PP','ODDG-PP',21,0,NULL,'PDF','TESDA ORDER',NULL,NULL,'asdad','2025-12-22 15:19:22',0,0,'[\"ODDG-PP\",\"ODDG-PP\"]',NULL,'Pending',0,NULL,'Normal','2025-12-22 07:19:22','2025-12-22 07:19:22'),(193,'22122025-00004','saad','2025-12-22 00:00:00',NULL,'asdas','ODDG-PP','PO',21,17,NULL,'PDF','TESDA ORDER',NULL,NULL,'asdad','2025-12-22 15:22:15',0,0,'[\"ODDG-PP\",\"ODDG-PP\"]',NULL,'Remanded',0,'asdada','Normal','2025-12-22 07:19:51','2025-12-22 07:22:15'),(194,'22122025-00001','test','2025-12-22 00:00:00',NULL,'test','EOD-PO','ODDG-PP',22,17,NULL,'PDF','CSW',NULL,NULL,'test','2025-12-22 16:46:25',1,17,'[\"EOD-PO\",\"EOD-PO\",\"ODDG-PP\"]',NULL,'For Discussion',0,NULL,'Normal','2025-12-22 02:23:20','2025-12-22 08:46:25'),(195,'22122025-00002','qwewe','2025-12-22 00:00:00',NULL,'qweqwe','ODDG-PP','ODDG-PP',17,0,NULL,'PDF','MEMO',NULL,NULL,'qwe','2025-12-20 11:31:14',0,0,'[\"ODDG-PP\",\"ODDG-PP\"]',NULL,'Pending',0,'qweqwe','Normal','2025-12-20 03:31:14','2025-12-20 03:31:14'),(196,'22122025-00003','etetst','2025-12-22 00:00:00',NULL,'awdasda','ODDG-PP','ODDG-PP',21,0,NULL,'PDF','TESDA ORDER',NULL,NULL,'asdad','2025-12-22 15:19:22',0,0,'[\"ODDG-PP\",\"ODDG-PP\"]',NULL,'Pending',0,NULL,'Normal','2025-12-22 07:19:22','2025-12-22 07:19:22'),(197,'22122025-00004','saad','2025-12-22 00:00:00',NULL,'asdas','ODDG-PP','PO',21,17,NULL,'PDF','TESDA ORDER',NULL,NULL,'asdad','2025-12-22 15:22:15',0,0,'[\"ODDG-PP\",\"ODDG-PP\"]',NULL,'Remanded',0,'asdada','Normal','2025-12-22 07:19:51','2025-12-22 07:22:15'),(198,'22122025-00001','test','2025-12-22 00:00:00',NULL,'test','EOD-PO','ODDG-PP',22,17,NULL,'PDF','CSW',NULL,NULL,'test','2025-12-22 16:46:25',1,17,'[\"EOD-PO\",\"EOD-PO\",\"ODDG-PP\"]',NULL,'For Discussion',0,NULL,'Normal','2025-12-22 02:23:20','2025-12-22 08:46:25'),(199,'22122025-00002','qwewe','2025-12-22 00:00:00',NULL,'qweqwe','ODDG-PP','ODDG-PP',17,0,NULL,'PDF','MEMO',NULL,NULL,'qwe','2025-12-20 11:31:14',0,0,'[\"ODDG-PP\",\"ODDG-PP\"]',NULL,'Pending',0,'qweqwe','Normal','2025-12-20 03:31:14','2025-12-20 03:31:14'),(200,'22122025-00003','etetst','2025-12-22 00:00:00',NULL,'awdasda','ODDG-PP','ODDG-PP',21,0,NULL,'PDF','TESDA ORDER',NULL,NULL,'asdad','2025-12-22 15:19:22',0,0,'[\"ODDG-PP\",\"ODDG-PP\"]',NULL,'Pending',0,NULL,'Normal','2025-12-22 07:19:22','2025-12-22 07:19:22'),(201,'22122025-00004','saad','2025-12-22 00:00:00',NULL,'asdas','ODDG-PP','PO',21,17,NULL,'PDF','TESDA ORDER',NULL,NULL,'asdad','2025-12-22 15:22:15',0,0,'[\"ODDG-PP\",\"ODDG-PP\"]',NULL,'Remanded',0,'asdada','Normal','2025-12-22 07:19:51','2025-12-22 07:22:15'),(202,'22122025-00001','test','2025-12-22 00:00:00',NULL,'test','EOD-PO','ODDG-PP',22,17,NULL,'PDF','CSW',NULL,NULL,'test','2025-12-22 16:46:25',1,17,'[\"EOD-PO\",\"EOD-PO\",\"ODDG-PP\"]',NULL,'For Discussion',0,NULL,'Normal','2025-12-22 02:23:20','2025-12-22 08:46:25'),(203,'22122025-00002','qwewe','2025-12-22 00:00:00',NULL,'qweqwe','ODDG-PP','ODDG-PP',17,0,NULL,'PDF','MEMO',NULL,NULL,'qwe','2025-12-20 11:31:14',0,0,'[\"ODDG-PP\",\"ODDG-PP\"]',NULL,'Pending',0,'qweqwe','Normal','2025-12-20 03:31:14','2025-12-20 03:31:14'),(204,'22122025-00003','etetst','2025-12-22 00:00:00',NULL,'awdasda','ODDG-PP','ODDG-PP',21,0,NULL,'PDF','TESDA ORDER',NULL,NULL,'asdad','2025-12-22 15:19:22',0,0,'[\"ODDG-PP\",\"ODDG-PP\"]',NULL,'Pending',0,NULL,'Normal','2025-12-22 07:19:22','2025-12-22 07:19:22'),(205,'22122025-00004','saad','2025-12-22 00:00:00',NULL,'asdas','ODDG-PP','PO',21,17,NULL,'PDF','TESDA ORDER',NULL,NULL,'asdad','2025-12-22 15:22:15',0,0,'[\"ODDG-PP\",\"ODDG-PP\"]',NULL,'Remanded',0,'asdada','Normal','2025-12-22 07:19:51','2025-12-22 07:22:15'),(206,'23122025-00001','12313','2025-12-23 00:00:00',NULL,'dsdhdigfdhgiudhgfuihdigfdfhgdihgudfudhgiuhuhfdhuihughuidfhgdhgudigidfhghidfughdfuigihdfgh','ODDG-PP','ODDG-PP',17,0,NULL,'PDF','MEMO',NULL,NULL,'fdsfsfihshidfiisudhfs','2025-12-23 14:25:24',0,0,'[\"ODDG-PP\",\"ODDG-PP\"]',NULL,'Pending',0,'sidfhisudhfisfhfhuisdfh','Normal','2025-12-23 06:25:24','2025-12-23 06:25:24'),(207,'05012026-00001','sdadsa','2026-01-05 00:00:00',NULL,'asda','ODDG-PP','PO',17,0,NULL,'PDF','MEMO','2025-12-31','2026-02-07','asdad','2026-01-05 10:05:27',0,0,'[\"ODDG-PP\",\"ODDG-PP\",\"PO\"]',NULL,'Routed',0,'adsads','None','2026-01-05 02:05:27','2026-01-05 02:05:27');
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `files` (
  `file_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `document_id` bigint(20) unsigned NOT NULL,
  `file_name` varchar(1000) DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_password` varchar(255) DEFAULT NULL,
  `uploading_office` varchar(255) NOT NULL,
  `uploaded_by` bigint(20) unsigned NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`file_id`),
  KEY `files_document_id_foreign` (`document_id`),
  KEY `files_uploaded_by_foreign` (`uploaded_by`),
  CONSTRAINT `files_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`document_id`) ON DELETE CASCADE,
  CONSTRAINT `files_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=156 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `files`
--

LOCK TABLES `files` WRITE;
/*!40000 ALTER TABLE `files` DISABLE KEYS */;
INSERT INTO `files` VALUES (150,174,'Untitled_design_(10).pdf','storage/assets/documents/EOD-PO/pdf/6948ab98b7e15-Untitled_design_(10).pdf',NULL,'EOD-PO',22,'2025-12-22 02:23:20'),(151,175,'Untitled_design_(10).pdf','storage/assets/documents/ODDG-PP/pdf/6948bb829ee1b-Untitled_design_(10).pdf',NULL,'ODDG-PP',17,'2025-12-22 03:31:14'),(152,176,'Untitled_design_(10).pdf','storage/assets/documents/ODDG-PP/pdf/6948f0fa5fb17-Untitled_design_(10).pdf',NULL,'ODDG-PP',21,'2025-12-22 07:19:22'),(153,177,'Untitled_design_(10).pdf','storage/assets/documents/ODDG-PP/pdf/6948f117ccf98-Untitled_design_(10).pdf',NULL,'ODDG-PP',21,'2025-12-22 07:19:51'),(154,206,'Untitled_design_(10).pdf','storage/assets/documents/ODDG-PP/pdf/694a35d48279e-Untitled_design_(10).pdf',NULL,'ODDG-PP',17,'2025-12-23 06:25:24'),(155,207,'Untitled_design_(10).pdf','storage/assets/documents/ODDG-PP/pdf/695b1c67e463e-Untitled_design_(10).pdf',NULL,'ODDG-PP',17,'2026-01-05 02:05:27');
/*!40000 ALTER TABLE `files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `listing_photos`
--

DROP TABLE IF EXISTS `listing_photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `listing_photos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `listing_photos`
--

LOCK TABLES `listing_photos` WRITE;
/*!40000 ALTER TABLE `listing_photos` DISABLE KEYS */;
/*!40000 ALTER TABLE `listing_photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `listings`
--

DROP TABLE IF EXISTS `listings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `listings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `property_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `status` enum('Active','Pending','Sold') NOT NULL DEFAULT 'Active',
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `listings`
--

LOCK TABLES `listings` WRITE;
/*!40000 ALTER TABLE `listings` DISABLE KEYS */;
/*!40000 ALTER TABLE `listings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mailer_settings`
--

DROP TABLE IF EXISTS `mailer_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mailer_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mail_mailer` varchar(255) NOT NULL DEFAULT 'smtp',
  `mail_host` varchar(255) DEFAULT NULL,
  `mail_port` varchar(255) DEFAULT NULL,
  `mail_username` varchar(255) DEFAULT NULL,
  `mail_password` varchar(255) DEFAULT NULL,
  `mail_encryption` varchar(255) DEFAULT NULL,
  `mail_from_address` varchar(255) DEFAULT NULL,
  `mail_from_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mailer_settings`
--

LOCK TABLES `mailer_settings` WRITE;
/*!40000 ALTER TABLE `mailer_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `mailer_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2025_10_01_211517_add_role_to_users_table',1),(6,'2025_10_01_211540_create_nav_menus_table',1),(7,'2025_10_01_211604_create_themes_table',1),(8,'2025_10_02_161929_add_role_id_to_users_table',1),(9,'2025_10_02_163004_create_table_for_settings_role',1),(10,'2025_10_02_163732_rename_title_in_posts_table',1),(11,'2025_10_02_163754_rename_title_in_posts_table',1),(12,'2025_10_03_180527_add_parentmenu',1),(13,'2025_10_04_144632_create_mailer_settings_table',1),(14,'2025_10_27_011437_update_role_idcolumn_from_users',1),(15,'2025_10_28_125539_create_listings_table',1),(16,'2025_10_28_135008_create_listing_photos_table',1),(25,'2025_11_05_010550_create_office_table',2),(26,'2025_11_05_010843_create_userconfig_table',2),(27,'2025_11_05_034143_add_office_id_to_users_table',2),(28,'2025_11_05_051201_instert_user_status',3),(30,'2025_11_06_035725_insert_order_in_nav_menus',4),(35,'2025_11_11_034055_create_documents_table',5),(36,'2025_11_11_034126_create_files_table',5),(37,'2025_11_11_034136_create_modifications_table',5),(38,'2025_11_11_034145_create_notifications_table',5),(39,'2025_11_11_072346_create_activities_table',6),(40,'2025_11_11_085126_update_document_table_column_control_number_to_varchar',7),(41,'2025_11_13_010728_add_document_code_to_document_table',8),(42,'2025_11_14_013954_create_document_types_table',9),(43,'2025_11_14_035018_update_activities_column_document_control_number_to_varchar',10),(44,'2025_11_14_061351_add_remarks_to_documents_table',11),(45,'2025_11_14_061419_add_routed_to_and_final_remarks_to_activities_table',11),(46,'2025_11_14_070758_add_office_origin_destination_routed_to_to_notifications_table',12),(47,'2025_11_14_080109_add_due_date_to_document_table',13),(48,'2025_11_14_081811_update_date_of_docs_to_nullable',14),(49,'2025_11_17_014429_add_file_name_to_files_table',15),(50,'2025_11_19_053136_add_final_approval_column_to_activities_table',16),(51,'2025_11_20_070853_add_to_external_column_to_activities',17),(52,'2025_11_20_073528_add_recepient_id_to_document_table',18),(53,'2025_11_21_003414_add_from_user_id_to_notification_table',19),(54,'2025_11_21_003910_add_fromuserid_to_activities_table',20),(55,'2025_11_21_015324_create_approval_table',21),(56,'2025_11_21_033628_add_status_column_to_approval_table',22),(57,'2025_11_24_031110_add_allowed_office_to_nav_menus',23),(58,'2025_11_24_053210_add_label_to_documents_table',24),(59,'2025_11_24_075644_update_document_id_from_approval_table',25),(60,'2025_11_25_091039_add_receipt_confirmation_column_to_document_table',26),(62,'2025_11_26_004903_update_date_forwarded_to_datetime',27),(63,'2025_12_17_203302_add_sender_id_to_document_table',28),(64,'2025_12_17_220040_add_revised_status_in_documents_table',28),(65,'2025_12_22_153803_alter_allowed_office_column_on_nav_menus_table',29),(66,'2025_12_25_150806_update_default_value_on_your_table',30);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modifications`
--

DROP TABLE IF EXISTS `modifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modifications` (
  `modification_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `document_id` bigint(20) unsigned NOT NULL,
  `modified_by` bigint(20) unsigned NOT NULL,
  `modification_type` varchar(50) NOT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`modification_id`),
  KEY `modifications_document_id_foreign` (`document_id`),
  KEY `modifications_modified_by_foreign` (`modified_by`),
  CONSTRAINT `modifications_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`document_id`) ON DELETE CASCADE,
  CONSTRAINT `modifications_modified_by_foreign` FOREIGN KEY (`modified_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modifications`
--

LOCK TABLES `modifications` WRITE;
/*!40000 ALTER TABLE `modifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `modifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nav_menus`
--

DROP TABLE IF EXISTS `nav_menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nav_menus` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `link` varchar(255) NOT NULL,
  `allowed_roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`allowed_roles`)),
  `allowed_office` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`allowed_office`)),
  `parent_menu` int(11) NOT NULL DEFAULT 0,
  `menu_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nav_menus`
--

LOCK TABLES `nav_menus` WRITE;
/*!40000 ALTER TABLE `nav_menus` DISABLE KEYS */;
INSERT INTO `nav_menus` VALUES (1,'Dashboard','fas fa-home','/page_dashboard','[\"Developer\",\"ADMIN\",\"TESDS\",\"EA\",\"AED\",\"ED\",\"DDG\",\"DIVISION-CHIEF\",\"DIVISION-ADMIN\",\"DIVISION-SupervisingTESDS\",\"Sr-TESDS\"]','[\"DEV\",\"ODDG-PP\",\"PO\",\"QSO\",\"NITESD\",\"OSEC\",\"ODDG-TESDO\",\"ODDG-PL\",\"ODDG-SC\",\"ODDG-AI\",\"ODDG-FLA\",\"TBS\",\"IAD\",\"PMO\",\"SIPTVET\",\"PIO\",\"PIAD\",\"CCU\",\"SMO\",\"SMD-SMO\",\"FSTP\",\"TDI\",\"PO-PRED\",\"PO-PPD\",\"PO-FRPDD\",\"PO-LMID\",\"PO-KMQAD\",\"NITESD-TRDD\",\"NITESD-LDD\",\"QSO-CSDD\",\"QSO-CPSDD\",\"QSO-CTADD\",\"ROMO\",\"ROMO-ROMD\",\"NTTA\",\"CO\",\"CO-CAD\",\"CO-PRD\",\"HRDC\",\"NSOWPS\",\"PLO\",\"PLO-PNAD\",\"PLO-PAID\",\"TWC\",\"ICTO\",\"ICTO-ITOD\",\"ICTO-ITPMD\",\"e-TESDA\",\"FMS\",\"FMS-AD\",\"FMS-BD\"]',0,1,'2025-11-03 22:19:19','2025-12-22 07:40:09'),(2,'User Management','fas fa-users','/page_usermanagement','[\"Developer\",\"ADMIN\",\"TESDS\",\"EA\",\"AED\",\"ED\",\"DDG\",\"DIVISION-CHIEF\",\"DIVISION-ADMIN\",\"DIVISION-SupervisingTESDS\",\"Sr-TESDS\"]','[\"DEV\",\"ODDG-PP\"]',0,4,'2025-11-03 22:19:19','2025-11-23 20:14:50'),(3,'Developer Option','fas fa-users','#','[\"Developer\"]','[\"DEV\",\"ODDG-PP\",\"EOD-PO\",\"EOD-QSO\",\"EOD-NITESD\",\"FOCAL-TEST\",\"FOCAL-TEST2\"]',0,6,'2025-11-03 22:19:19','2025-11-23 20:50:44'),(4,'Mailer',NULL,'/page_mailer','[\"Developer\"]','[\"DEV\",\"ODDG-PP\",\"EOD-PO\",\"EOD-QSO\",\"EOD-NITESD\",\"FOCAL-TEST\",\"FOCAL-TEST2\"]',3,1,'2025-11-03 22:19:19','2025-11-23 20:50:44'),(5,'Menus',NULL,'/page_menus','[\"Developer\"]','[\"DEV\",\"ODDG-PP\",\"EOD-PO\",\"EOD-QSO\",\"EOD-NITESD\",\"FOCAL-TEST\",\"FOCAL-TEST2\"]',3,2,'2025-11-03 22:19:19','2025-11-23 20:50:44'),(6,'Documents',NULL,'/page_documents','[\"Developer\",\"ADMIN\",\"TESDS\",\"EA\",\"AED\",\"ED\",\"DDG\",\"DIVISION-CHIEF\",\"DIVISION-ADMIN\",\"DIVISION-SupervisingTESDS\",\"Sr-TESDS\"]','[\"DEV\",\"ODDG-PP\",\"PO\",\"QSO\",\"NITESD\",\"OSEC\",\"ODDG-TESDO\",\"ODDG-PL\",\"ODDG-SC\",\"ODDG-AI\",\"ODDG-FLA\",\"TBS\",\"IAD\",\"PMO\",\"SIPTVET\",\"PIO\",\"PIAD\",\"CCU\",\"SMO\",\"SMD-SMO\",\"FSTP\",\"TDI\",\"PO-PRED\",\"PO-PPD\",\"PO-FRPDD\",\"PO-LMID\",\"PO-KMQAD\",\"NITESD-TRDD\",\"NITESD-LDD\",\"QSO-CSDD\",\"QSO-CPSDD\",\"QSO-CTADD\",\"ROMO\",\"ROMO-ROMD\",\"NTTA\",\"CO\",\"CO-CAD\",\"CO-PRD\",\"HRDC\",\"NSOWPS\",\"PLO\",\"PLO-PNAD\",\"PLO-PAID\",\"TWC\",\"ICTO\",\"ICTO-ITOD\",\"ICTO-ITPMD\",\"e-TESDA\",\"FMS\",\"FMS-AD\",\"FMS-BD\"]',0,3,'2025-11-04 01:04:07','2025-12-22 07:40:59'),(7,'Reports',NULL,'#','[\"Developer\",\"ADMIN\",\"TESDS\",\"EA\",\"AED\",\"ED\",\"DDG\",\"DIVISION-CHIEF\",\"DIVISION-ADMIN\",\"DIVISION-SupervisingTESDS\",\"Sr-TESDS\"]','[\"DEV\",\"ODDG-PP\",\"PO\",\"QSO\",\"NITESD\",\"OSEC\",\"ODDG-TESDO\",\"ODDG-PL\",\"ODDG-SC\",\"ODDG-AI\",\"ODDG-FLA\",\"TBS\",\"IAD\",\"PMO\",\"SIPTVET\",\"PIO\",\"PIAD\",\"CCU\",\"SMO\",\"SMD-SMO\",\"FSTP\",\"TDI\",\"PO-PRED\",\"PO-PPD\",\"PO-FRPDD\",\"PO-LMID\",\"PO-KMQAD\",\"NITESD-TRDD\",\"NITESD-LDD\",\"QSO-CSDD\",\"QSO-CPSDD\",\"QSO-CTADD\",\"ROMO\",\"ROMO-ROMD\",\"NTTA\",\"CO\",\"CO-CAD\",\"CO-PRD\",\"HRDC\",\"NSOWPS\",\"PLO\",\"PLO-PNAD\",\"PLO-PAID\",\"TWC\",\"ICTO\",\"ICTO-ITOD\",\"ICTO-ITPMD\",\"e-TESDA\",\"FMS\",\"FMS-AD\",\"FMS-BD\"]',0,5,'2025-11-04 01:04:47','2025-12-22 07:41:20'),(8,'For Approval',NULL,'/page_approvals','[\"Developer\",\"TESDS\",\"EA\",\"AED\",\"ED\",\"DDG\",\"DIVISION-CHIEF\",\"DIVISION-SupervisingTESDS\",\"Sr-TESDS\"]','[\"DEV\",\"ODDG-PP\",\"PO\",\"QSO\",\"NITESD\",\"OSEC\",\"ODDG-TESDO\",\"ODDG-PL\",\"ODDG-SC\",\"ODDG-AI\",\"ODDG-FLA\",\"TBS\",\"IAD\",\"PMO\",\"SIPTVET\",\"PIO\",\"PIAD\",\"CCU\",\"SMO\",\"SMD-SMO\",\"FSTP\",\"TDI\",\"PO-PRED\",\"PO-PPD\",\"PO-FRPDD\",\"PO-LMID\",\"PO-KMQAD\",\"NITESD-TRDD\",\"NITESD-LDD\",\"QSO-CSDD\",\"QSO-CPSDD\",\"QSO-CTADD\",\"ROMO\",\"ROMO-ROMD\",\"NTTA\",\"CO\",\"CO-CAD\",\"CO-PRD\",\"HRDC\",\"NSOWPS\",\"PLO\",\"PLO-PNAD\",\"PLO-PAID\",\"TWC\",\"ICTO\",\"ICTO-ITOD\",\"ICTO-ITPMD\",\"e-TESDA\",\"FMS\",\"FMS-AD\",\"FMS-BD\"]',0,2,'2025-11-04 01:05:11','2025-12-22 07:40:52'),(9,'User Report',NULL,'/page_reports_users','[\"Developer\",\"ADMIN\",\"TESDS\",\"EA\",\"AED\",\"ED\",\"DDG\",\"DIVISION-CHIEF\",\"DIVISION-ADMIN\",\"DIVISION-SupervisingTESDS\",\"Sr-TESDS\"]','[\"DEV\",\"ODDG-PP\",\"PO\",\"QSO\",\"NITESD\",\"OSEC\",\"ODDG-TESDO\",\"ODDG-PL\",\"ODDG-SC\",\"ODDG-AI\",\"ODDG-FLA\",\"TBS\",\"IAD\",\"PMO\",\"SIPTVET\",\"PIO\",\"PIAD\",\"CCU\",\"SMO\",\"SMD-SMO\",\"FSTP\",\"TDI\",\"PO-PRED\",\"PO-PPD\",\"PO-FRPDD\",\"PO-LMID\",\"PO-KMQAD\",\"NITESD-TRDD\",\"NITESD-LDD\",\"QSO-CSDD\",\"QSO-CPSDD\",\"QSO-CTADD\",\"ROMO\",\"ROMO-ROMD\",\"NTTA\",\"CO\",\"CO-CAD\",\"CO-PRD\",\"HRDC\",\"NSOWPS\",\"PLO\",\"PLO-PNAD\",\"PLO-PAID\",\"TWC\",\"ICTO\",\"ICTO-ITOD\",\"ICTO-ITPMD\",\"e-TESDA\",\"FMS\",\"FMS-AD\",\"FMS-BD\"]',7,1,'2025-11-04 01:05:48','2025-12-22 07:41:20'),(10,'Document Report',NULL,'/page_reports_documents','[\"Developer\",\"ADMIN\",\"TESDS\",\"EA\",\"AED\",\"ED\",\"DDG\",\"DIVISION-CHIEF\",\"DIVISION-ADMIN\",\"DIVISION-SupervisingTESDS\",\"Sr-TESDS\"]','[\"DEV\",\"ODDG-PP\",\"PO\",\"QSO\",\"NITESD\",\"OSEC\",\"ODDG-TESDO\",\"ODDG-PL\",\"ODDG-SC\",\"ODDG-AI\",\"ODDG-FLA\",\"TBS\",\"IAD\",\"PMO\",\"SIPTVET\",\"PIO\",\"PIAD\",\"CCU\",\"SMO\",\"SMD-SMO\",\"FSTP\",\"TDI\",\"PO-PRED\",\"PO-PPD\",\"PO-FRPDD\",\"PO-LMID\",\"PO-KMQAD\",\"NITESD-TRDD\",\"NITESD-LDD\",\"QSO-CSDD\",\"QSO-CPSDD\",\"QSO-CTADD\",\"ROMO\",\"ROMO-ROMD\",\"NTTA\",\"CO\",\"CO-CAD\",\"CO-PRD\",\"HRDC\",\"NSOWPS\",\"PLO\",\"PLO-PNAD\",\"PLO-PAID\",\"TWC\",\"ICTO\",\"ICTO-ITOD\",\"ICTO-ITPMD\",\"e-TESDA\",\"FMS\",\"FMS-AD\",\"FMS-BD\"]',7,2,'2025-11-04 01:06:25','2025-12-22 07:41:20'),(11,'Settings',NULL,'/page_settings','[\"Developer\"]','[\"DEV\",\"ODDG-PP\",\"EOD-PO\",\"EOD-QSO\",\"EOD-NITESD\",\"FOCAL-TEST\",\"FOCAL-TEST2\"]',3,3,'2025-11-04 17:02:48','2025-11-23 20:50:44');
/*!40000 ALTER TABLE `nav_menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `office_origin` varchar(255) DEFAULT NULL,
  `destination_office` varchar(255) DEFAULT NULL,
  `routed_to` int(11) DEFAULT NULL,
  `document_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `from_user_id` varchar(10) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_document_id_foreign` (`document_id`),
  KEY `notifications_user_id_foreign` (`user_id`),
  CONSTRAINT `notifications_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`document_id`) ON DELETE CASCADE,
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=552 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (521,'EOD-PO','ODDG-PP',NULL,174,17,'22','New document uploaded: test',1,'2025-12-22 02:23:20','2026-01-05 01:45:16'),(522,'EOD-PO','ODDG-PP',NULL,174,18,'22','New document uploaded: test',1,'2025-12-22 02:23:20','2025-12-22 03:26:04'),(523,'ODDG-PP','ODDG-PP',NULL,175,17,'17','New document uploaded: qwewe',1,'2025-12-22 03:31:14','2026-01-05 01:45:16'),(524,'ODDG-PP','ODDG-PP',NULL,175,18,'17','New document uploaded: qwewe',0,'2025-12-22 03:31:14','2025-12-22 03:31:14'),(525,'ODDG-PP','ODDG-PP',NULL,176,17,'21','New document uploaded: etetst',1,'2025-12-22 07:19:22','2026-01-05 01:45:16'),(526,'ODDG-PP','ODDG-PP',NULL,176,18,'21','New document uploaded: etetst',0,'2025-12-22 07:19:22','2025-12-22 07:19:22'),(527,'ODDG-PP','ODDG-PP',NULL,177,17,'21','New document uploaded: saad',1,'2025-12-22 07:19:51','2026-01-05 01:45:16'),(528,'ODDG-PP','ODDG-PP',NULL,177,18,'21','New document uploaded: saad',0,'2025-12-22 07:19:51','2025-12-22 07:19:51'),(529,'EOD-PO','ODDG-PP',NULL,174,22,'17','17 has confirmed receipt of the document',1,'2025-12-22 07:20:14','2025-12-22 08:59:51'),(530,'ODDG-PP','ODDG-PP',NULL,177,21,'17','17 has confirmed receipt of the document',1,'2025-12-22 07:20:31','2025-12-22 07:20:47'),(531,'ODDG-PP','ODDG-PP',NULL,177,34,'21','22122025-00004 has been routed you by  test.ppsrtesds for approval',1,'2025-12-22 07:21:06','2026-01-05 01:43:38'),(532,'ODDG-PP','ODDG-PP',NULL,177,34,'21','22122025-00004 has been routed you by  test.ppsrtesds for approval',1,'2025-12-22 07:21:06','2026-01-05 01:43:38'),(533,'ODDG-PP','ODDG-PP',NULL,177,34,'21','22122025-00004 has been routed you by  test.ppsrtesds for approval',1,'2025-12-22 07:21:07','2026-01-05 01:43:38'),(534,'ODDG-PP','ODDG-PP',NULL,177,17,'34','34 is requesting discussion for this document saad.',1,'2025-12-22 07:21:48','2026-01-05 01:45:16'),(535,'ODDG-PP','ODDG-PP',NULL,177,18,'34','34 is requesting discussion for this document saad.',0,'2025-12-22 07:21:48','2025-12-22 07:21:48'),(536,'ODDG-PP','ODDG-PP',NULL,177,21,'34','34 is requesting discussion for this document saad.',0,'2025-12-22 07:21:48','2025-12-22 07:21:48'),(537,'ODDG-PP','PO',NULL,177,22,'17','22122025-00004 has been routed to PO by  test.ppadmin01',1,'2025-12-22 07:22:15','2025-12-22 08:59:51'),(538,'ODDG-PP','PO',NULL,177,23,'17','22122025-00004 has been routed to PO by  test.ppadmin01',1,'2025-12-22 07:22:15','2025-12-22 07:41:39'),(539,'EOD-PO','ODDG-PP',NULL,174,34,'17','22122025-00001 has been routed you by  test.ppadmin01 for approval',1,'2025-12-22 08:45:51','2026-01-05 01:43:38'),(540,'EOD-PO','ODDG-PP',NULL,174,17,'34','34 is requesting discussion for this document test.',1,'2025-12-22 08:46:25','2026-01-05 01:45:16'),(541,'EOD-PO','ODDG-PP',NULL,174,18,'34','34 is requesting discussion for this document test.',0,'2025-12-22 08:46:25','2025-12-22 08:46:25'),(542,'EOD-PO','ODDG-PP',NULL,174,22,'34','34 is requesting discussion for this document test.',1,'2025-12-22 08:46:25','2025-12-22 08:59:51'),(543,'ODDG-PP','ODDG-PP',NULL,175,17,'17','17 has confirmed receipt of the document',1,'2025-12-23 00:42:41','2026-01-05 01:45:16'),(544,'ODDG-PP','ODDG-PP',NULL,206,17,'17','New document uploaded: 12313',1,'2025-12-23 06:25:24','2026-01-05 01:45:16'),(545,'ODDG-PP','ODDG-PP',NULL,206,18,'17','New document uploaded: 12313',0,'2025-12-23 06:25:24','2025-12-23 06:25:24'),(546,'EOD-PO','ODDG-PP',NULL,174,34,'17','22122025-00001 has been routed you by  test.ppadmin01 for approval',1,'2026-01-05 01:43:00','2026-01-05 01:43:38'),(547,'EOD-PO','ODDG-PP',NULL,174,17,'34','test Has been approved. you may route this to the origin office',1,'2026-01-05 01:43:52','2026-01-05 01:45:16'),(548,'EOD-PO','ODDG-PP',NULL,174,18,'34','test Has been approved. you may route this to the origin office',0,'2026-01-05 01:43:52','2026-01-05 01:43:52'),(549,'EOD-PO','ODDG-PP',NULL,178,19,'17','22122025-00001 has been routed you by  test.ppadmin01 for approval',0,'2026-01-05 01:45:47','2026-01-05 01:45:47'),(550,'ODDG-PP','PO',NULL,207,22,'17','New document uploaded: sdadsa',0,'2026-01-05 02:05:27','2026-01-05 02:05:27'),(551,'ODDG-PP','PO',NULL,207,23,'17','New document uploaded: sdadsa',0,'2026-01-05 02:05:27','2026-01-05 02:05:27');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `office_table`
--

DROP TABLE IF EXISTS `office_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `office_table` (
  `office_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `office_name` varchar(100) NOT NULL,
  `office_code` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`office_id`),
  UNIQUE KEY `office_table_office_code_unique` (`office_code`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `office_table`
--

LOCK TABLES `office_table` WRITE;
/*!40000 ALTER TABLE `office_table` DISABLE KEYS */;
INSERT INTO `office_table` VALUES (5,'DEV','DEV','2025-11-24 02:28:10'),(6,'ODDG-PP','ODDG-PP','2025-11-23 18:35:37'),(7,'PO','PO','2025-11-23 18:35:51'),(8,'EOD-QSO','QSO','2025-11-23 18:36:01'),(9,'EOD-NITESD','NITESD','2025-11-23 18:36:12'),(13,'OSEC/ODG','OSEC','2025-12-22 04:52:05'),(14,'ODDG-TESDO','ODDG-TESDO','2025-12-22 04:52:05'),(15,'ODDG-PL','ODDG-PL','2025-12-22 04:52:05'),(16,'ODDG-SC','ODDG-SC','2025-12-22 04:52:05'),(17,'ODDG-AI','ODDG-AI','2025-12-22 04:52:05'),(18,'ODDG-FLA','ODDG-FLA','2025-12-22 04:52:05'),(19,'TBS','TBS','2025-12-22 04:52:05'),(20,'IAD','IAD','2025-12-22 04:52:05'),(21,'PMO','PMO','2025-12-22 04:52:05'),(22,'SIPTVET','SIPTVET','2025-12-22 04:52:05'),(23,'PIO','PIO','2025-12-22 04:52:05'),(24,'PIAD','PIAD','2025-12-22 04:52:05'),(25,'CCU','CCU','2025-12-22 04:52:05'),(26,'SMO','SMO','2025-12-22 04:52:05'),(27,'SMD-SMO','SMD-SMO','2025-12-22 04:52:05'),(28,'FSTP','FSTP','2025-12-22 04:52:05'),(29,'TDI','TDI','2025-12-22 04:52:05'),(30,'PO-PRED','PO-PRED','2025-12-22 04:52:05'),(31,'PO-PPD','PO-PPD','2025-12-22 04:52:05'),(32,'PO-FRPDD','PO-FRPDD','2025-12-22 04:52:05'),(33,'PO-LMID','PO-LMID','2025-12-22 04:52:05'),(34,'PO-KMQAD','PO-KMQAD','2025-12-22 04:52:05'),(36,'NITESD-TRDD','NITESD-TRDD','2025-12-22 04:52:30'),(37,'NITESD-LDD','NITESD-LDD','2025-12-22 04:52:30'),(39,'QSO-CSDD','QSO-CSDD','2025-12-22 04:52:35'),(40,'QSO-CPSDD','QSO-CPSDD','2025-12-22 04:52:35'),(41,'QSO-CTADD','QSO-CTADD','2025-12-22 04:52:35'),(42,'ROMO','ROMO','2025-12-22 04:52:35'),(43,'ROMO-ROMD','ROMO-ROMD','2025-12-22 04:52:35'),(44,'NTTA','NTTA','2025-12-22 04:52:35'),(45,'CO','CO','2025-12-22 04:52:35'),(46,'CO-CAD','CO-CAD','2025-12-22 04:52:35'),(47,'CO-PRD','CO-PRD','2025-12-22 04:52:35'),(48,'HRDC','HRDC','2025-12-22 04:52:35'),(49,'NSOWPS','NSOWPS','2025-12-22 04:52:35'),(50,'PLO','PLO','2025-12-22 04:52:35'),(51,'PLO-PNAD','PLO-PNAD','2025-12-22 04:52:35'),(52,'PLO-PAID','PLO-PAID','2025-12-22 04:52:35'),(53,'TWC','TWC','2025-12-22 04:52:35'),(54,'ICTO','ICTO','2025-12-22 04:52:35'),(55,'ICTO-ITOD','ICTO-ITOD','2025-12-22 04:52:35'),(56,'ICTO-ITPMD','ICTO-ITPMD','2025-12-22 04:52:35'),(57,'e-TESDA','e-TESDA','2025-12-22 04:52:35'),(58,'FMS','FMS','2025-12-22 04:52:35'),(59,'FMS-AD','FMS-AD','2025-12-22 04:52:35'),(60,'FMS-BD','FMS-BD','2025-12-22 04:52:35');
/*!40000 ALTER TABLE `office_table` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `setting_role`
--

DROP TABLE IF EXISTS `setting_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `setting_role` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `setting_role`
--

LOCK TABLES `setting_role` WRITE;
/*!40000 ALTER TABLE `setting_role` DISABLE KEYS */;
INSERT INTO `setting_role` VALUES (1,'superadmin'),(2,'admin'),(3,'user'),(4,'developer');
/*!40000 ALTER TABLE `setting_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `themes`
--

DROP TABLE IF EXISTS `themes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `themes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `logo` varchar(255) DEFAULT NULL,
  `primary_color` varchar(255) NOT NULL DEFAULT '#1d4ed8',
  `secondary_color` varchar(255) NOT NULL DEFAULT '#64748b',
  `highlight_color` varchar(255) NOT NULL DEFAULT '#f59e0b',
  `accent_color` varchar(255) NOT NULL DEFAULT '#10b981',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `themes`
--

LOCK TABLES `themes` WRITE;
/*!40000 ALTER TABLE `themes` DISABLE KEYS */;
INSERT INTO `themes` VALUES (1,'logo.png','#1d4ed8','#64748b','#f59e0b','#10b981',NULL,NULL);
/*!40000 ALTER TABLE `themes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userconfig_table`
--

DROP TABLE IF EXISTS `userconfig_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `userconfig_table` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `designation` varchar(100) NOT NULL,
  `approval_type` enum('pre-approval','final-approval','routing') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userconfig_table`
--

LOCK TABLES `userconfig_table` WRITE;
/*!40000 ALTER TABLE `userconfig_table` DISABLE KEYS */;
INSERT INTO `userconfig_table` VALUES (13,'Developer','routing',NULL,NULL),(14,'ADMIN','routing','2025-11-23 18:36:30','2025-11-23 18:36:30'),(15,'TESDS','pre-approval','2025-11-23 18:36:45','2025-11-23 18:36:45'),(16,'EA','pre-approval','2025-11-23 18:36:56','2025-11-23 18:36:56'),(17,'AED','pre-approval','2025-11-23 18:37:02','2025-11-23 18:37:02'),(18,'ED','final-approval','2025-11-23 18:37:13','2025-11-23 18:37:13'),(19,'DDG','final-approval','2025-11-23 18:37:19','2025-11-23 18:37:19'),(20,'DIVISION-CHIEF','final-approval','2025-11-23 18:40:23','2025-11-23 18:40:23'),(21,'DIVISION-ADMIN','routing','2025-11-23 18:40:31','2025-11-23 18:40:31'),(23,'DIVISION-SupervisingTESDS','pre-approval','2025-11-23 18:41:38','2025-11-23 18:41:38'),(24,'Sr-TESDS','pre-approval','2025-11-23 18:48:21','2025-11-23 18:48:21');
/*!40000 ALTER TABLE `userconfig_table` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `role_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `office_id` bigint(20) unsigned DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (16,'Developer','dev@email.com',NULL,'$2y$12$yT3r2NqonCYaWZXW0g1yH.mFjT3OHjfDDp/2sA3goMvX6jAAI4tDW',NULL,NULL,NULL,'Developer',13,'active',5),(17,'test.ppadmin01','test.ppadmin01@email.com',NULL,'$2y$12$yT3r2NqonCYaWZXW0g1yH.mFjT3OHjfDDp/2sA3goMvX6jAAI4tDW',NULL,'2025-11-23 18:42:48','2025-11-23 19:03:19','ADMIN',14,'active',6),(18,'test.ppadmin02','test.ppadmin02@email.com',NULL,'$2y$12$vuOFF1ezbJUR2SmWkEaI8ejL1LgD9DHDTK3ReF8aybncbDHPRZ/XC','4VksIzArPmAAkG9tCUe4Xw2dBXWL2lbe2Ke3jJMNpWU4fBteFRTTQcrlRYCn','2025-11-23 18:43:38','2025-11-23 18:43:38','ADMIN',14,'active',6),(19,'test.pptesds','test.pptesds@email.com',NULL,'$2y$12$UDClpn40rMoi.ehSIs8P6.pkZ6rA0FzsxwkRRzM8gwOfdVoggZYT2',NULL,'2025-11-23 18:46:53','2025-11-23 18:46:53','TESDS',15,'active',6),(20,'test.ppea','test.ppea@email.com',NULL,'$2y$12$AeKor24wVdeCCXBbLj/Rc.6KGmgTO7.HU5wgxrOEm2yGnZUmJziRC',NULL,'2025-11-23 18:47:18','2025-11-23 18:47:18','EA',16,'active',6),(21,'test.ppsrtesds','test.ppsrtesds@email.com',NULL,'$2y$12$a40g6rhhvns/zpzEQzHMyOqpI2Lyfq5piaDuMwf4UP14y6YQ.mP7u',NULL,'2025-11-23 18:49:02','2025-11-23 18:49:02','Sr-TESDS',24,'active',6),(22,'test.poadmin01','test.poadmin01@email.com',NULL,'$2y$12$Z7U/Iz8EX14H12ScKKGlWui/sDQwyjg8uQY6sUd1tFqFSCyUG5DYa',NULL,'2025-11-23 18:49:19','2025-11-23 18:49:19','ADMIN',14,'active',7),(23,'test.poadmin02','test.poadmin02@email.com',NULL,'$2y$12$X1/s.sOXV8.Lzx.GSui3hezVPrpMyqVc9YwXmmPZM/y7sIW83kwjW',NULL,'2025-11-23 18:49:37','2025-11-23 18:49:37','ADMIN',14,'active',7),(24,'test.poaed','test.poaed@email.com',NULL,'$2y$12$t5ag0dK6EpPaM3o2/xo3c.s04.mm6dYQH2vKlT4Grg64BCEXg8O5a',NULL,'2025-11-23 18:50:08','2025-11-23 18:50:08','AED',17,'active',7),(25,'test.poed','test.poed@email.com',NULL,'$2y$12$UC1ZhGGEKYw6bkD4JCOPn.QSrFr.uw/XrCUAiqlvFIoVDneqWYGjO',NULL,'2025-11-23 18:50:23','2025-11-23 18:50:23','ED',18,'active',7),(26,'test.qsoadmin01','test.qsoadmin01@email.com',NULL,'$2y$12$1kLDjOhqZAJYhE6oiI/H1un8o03ooIzxtma9oUpKY5rz5LA0u5.xO',NULL,'2025-11-23 18:50:48','2025-11-23 18:50:48','ADMIN',14,'active',8),(27,'test.qsoadmin02','test.qsoadmin02@email.com',NULL,'$2y$12$oXhh6ogNfQvWSUBJFRIUr./6Ynp7E8tK8r5S9eYCXXsIYjALflXTi',NULL,'2025-11-23 18:51:04','2025-11-23 18:51:04','ADMIN',14,'active',8),(28,'test.qsoaed','test.qsoaed@email.com',NULL,'$2y$12$8vEfv2x3dHhX3aXQEavjoubcVAxmSYy1iSTfYN13S0DXipysRoUf2',NULL,'2025-11-23 18:51:21','2025-11-23 18:51:21','AED',17,'active',8),(29,'test.qsoed','test.qsoed@email.com',NULL,'$2y$12$BWCbYdVulKEC4o7hR2IxeO2e8aF95Spt4xQsNMb4nbfUmC4pBGqqe',NULL,'2025-11-23 18:51:38','2025-11-23 18:51:38','ED',18,'active',8),(30,'test.nitesdadmin01','test.nitesdadmin01@email.com',NULL,'$2y$12$Gw9cKJI0edGLvmKnkhTlGuS1X9PIezjl8Pt0mCz1sowChIo7jRT5.',NULL,'2025-11-23 18:51:56','2025-11-23 18:51:56','ADMIN',14,'active',9),(31,'test.nitesdadmin02','test.nitesdadmin02@email.com',NULL,'$2y$12$hGE7PnhSzgEU09RA5gMx0.8y3jsPND.Pb76UxHxOrq8igaE6UJwLa',NULL,'2025-11-23 18:52:20','2025-11-23 18:52:20','ADMIN',14,'active',9),(32,'test.nitesdaed','test.nitesdaed@email.com',NULL,'$2y$12$ngmAnAZYMHKHg36s/fKmJ.TX2G2i/Z8o..kjtFIjT580cRCLAURvu',NULL,'2025-11-23 18:53:58','2025-11-23 18:53:58','AED',17,'active',9),(33,'test.nitesded','test.nitesded@email.com',NULL,'$2y$12$ljj50YKMacjC4Eu/FDhmPOWILMt0BJtY/gl0eYAZejUeF40VmXfvy',NULL,'2025-11-23 18:54:15','2025-11-23 18:54:15','ED',18,'active',9),(34,'test.ppddg','test.ppddg@email.com',NULL,'$2y$12$dk2.ssG.RacpoWTsmKoIT.yM.F4U86yCR9bCOqizE5tza22We83wO',NULL,'2025-11-24 21:33:38','2025-11-24 21:33:38','DDG',19,'active',6);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'dmt_db'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-05 12:45:47

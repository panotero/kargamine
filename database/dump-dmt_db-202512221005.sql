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
) ENGINE=InnoDB AUTO_INCREMENT=1560 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activities`
--

LOCK TABLES `activities` WRITE;
/*!40000 ALTER TABLE `activities` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `approval_table`
--

LOCK TABLES `approval_table` WRITE;
/*!40000 ALTER TABLE `approval_table` DISABLE KEYS */;
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
  `confidentiality` varchar(50) NOT NULL DEFAULT 'Normal',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`document_id`)
) ENGINE=InnoDB AUTO_INCREMENT=164 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=140 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `files`
--

LOCK TABLES `files` WRITE;
/*!40000 ALTER TABLE `files` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2025_10_01_211517_add_role_to_users_table',1),(6,'2025_10_01_211540_create_nav_menus_table',1),(7,'2025_10_01_211604_create_themes_table',1),(8,'2025_10_02_161929_add_role_id_to_users_table',1),(9,'2025_10_02_163004_create_table_for_settings_role',1),(10,'2025_10_02_163732_rename_title_in_posts_table',1),(11,'2025_10_02_163754_rename_title_in_posts_table',1),(12,'2025_10_03_180527_add_parentmenu',1),(13,'2025_10_04_144632_create_mailer_settings_table',1),(14,'2025_10_27_011437_update_role_idcolumn_from_users',1),(15,'2025_10_28_125539_create_listings_table',1),(16,'2025_10_28_135008_create_listing_photos_table',1),(25,'2025_11_05_010550_create_office_table',2),(26,'2025_11_05_010843_create_userconfig_table',2),(27,'2025_11_05_034143_add_office_id_to_users_table',2),(28,'2025_11_05_051201_instert_user_status',3),(30,'2025_11_06_035725_insert_order_in_nav_menus',4),(35,'2025_11_11_034055_create_documents_table',5),(36,'2025_11_11_034126_create_files_table',5),(37,'2025_11_11_034136_create_modifications_table',5),(38,'2025_11_11_034145_create_notifications_table',5),(39,'2025_11_11_072346_create_activities_table',6),(40,'2025_11_11_085126_update_document_table_column_control_number_to_varchar',7),(41,'2025_11_13_010728_add_document_code_to_document_table',8),(42,'2025_11_14_013954_create_document_types_table',9),(43,'2025_11_14_035018_update_activities_column_document_control_number_to_varchar',10),(44,'2025_11_14_061351_add_remarks_to_documents_table',11),(45,'2025_11_14_061419_add_routed_to_and_final_remarks_to_activities_table',11),(46,'2025_11_14_070758_add_office_origin_destination_routed_to_to_notifications_table',12),(47,'2025_11_14_080109_add_due_date_to_document_table',13),(48,'2025_11_14_081811_update_date_of_docs_to_nullable',14),(49,'2025_11_17_014429_add_file_name_to_files_table',15),(50,'2025_11_19_053136_add_final_approval_column_to_activities_table',16),(51,'2025_11_20_070853_add_to_external_column_to_activities',17),(52,'2025_11_20_073528_add_recepient_id_to_document_table',18),(53,'2025_11_21_003414_add_from_user_id_to_notification_table',19),(54,'2025_11_21_003910_add_fromuserid_to_activities_table',20),(55,'2025_11_21_015324_create_approval_table',21),(56,'2025_11_21_033628_add_status_column_to_approval_table',22),(57,'2025_11_24_031110_add_allowed_office_to_nav_menus',23),(58,'2025_11_24_053210_add_label_to_documents_table',24),(59,'2025_11_24_075644_update_document_id_from_approval_table',25),(60,'2025_11_25_091039_add_receipt_confirmation_column_to_document_table',26),(62,'2025_11_26_004903_update_date_forwarded_to_datetime',27),(63,'2025_12_17_203302_add_sender_id_to_document_table',28),(64,'2025_12_17_220040_add_revised_status_in_documents_table',28);
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
  `allowed_office` varchar(255) DEFAULT NULL,
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
INSERT INTO `nav_menus` VALUES (1,'Dashboard','fas fa-home','/page_dashboard','[\"Developer\",\"ADMIN\",\"TESDS\",\"EA\",\"AED\",\"ED\",\"DDG\",\"DIVISION-CHIEF\",\"DIVISION-ADMIN\",\"DIVISION-SupervisingTESDS\",\"Sr-TESDS\"]','[\"DEV\",\"ODDG-PP\",\"EOD-PO\",\"EOD-QSO\",\"EOD-NITESD\",\"FOCAL-TEST\",\"FOCAL-TEST2\"]',0,1,'2025-11-03 22:19:19','2025-11-23 20:05:20'),(2,'User Management','fas fa-users','/page_usermanagement','[\"Developer\",\"ADMIN\",\"TESDS\",\"EA\",\"AED\",\"ED\",\"DDG\",\"DIVISION-CHIEF\",\"DIVISION-ADMIN\",\"DIVISION-SupervisingTESDS\",\"Sr-TESDS\"]','[\"DEV\",\"ODDG-PP\"]',0,4,'2025-11-03 22:19:19','2025-11-23 20:14:50'),(3,'Developer Option','fas fa-users','#','[\"Developer\"]','[\"DEV\",\"ODDG-PP\",\"EOD-PO\",\"EOD-QSO\",\"EOD-NITESD\",\"FOCAL-TEST\",\"FOCAL-TEST2\"]',0,6,'2025-11-03 22:19:19','2025-11-23 20:50:44'),(4,'Mailer',NULL,'/page_mailer','[\"Developer\"]','[\"DEV\",\"ODDG-PP\",\"EOD-PO\",\"EOD-QSO\",\"EOD-NITESD\",\"FOCAL-TEST\",\"FOCAL-TEST2\"]',3,1,'2025-11-03 22:19:19','2025-11-23 20:50:44'),(5,'Menus',NULL,'/page_menus','[\"Developer\"]','[\"DEV\",\"ODDG-PP\",\"EOD-PO\",\"EOD-QSO\",\"EOD-NITESD\",\"FOCAL-TEST\",\"FOCAL-TEST2\"]',3,2,'2025-11-03 22:19:19','2025-11-23 20:50:44'),(6,'Documents',NULL,'/page_documents','[\"Developer\",\"ADMIN\",\"TESDS\",\"EA\",\"AED\",\"ED\",\"DDG\",\"DIVISION-CHIEF\",\"DIVISION-ADMIN\",\"DIVISION-SupervisingTESDS\",\"Sr-TESDS\"]','[\"DEV\",\"ODDG-PP\",\"EOD-PO\",\"EOD-QSO\",\"EOD-NITESD\",\"FOCAL-TEST\",\"FOCAL-TEST2\"]',0,3,'2025-11-04 01:04:07','2025-11-23 20:14:27'),(7,'Reports',NULL,'#','[\"Developer\",\"ADMIN\",\"TESDS\",\"EA\",\"AED\",\"ED\",\"DDG\",\"DIVISION-CHIEF\",\"DIVISION-ADMIN\",\"DIVISION-SupervisingTESDS\",\"Sr-TESDS\"]','[\"DEV\",\"ODDG-PP\",\"EOD-PO\",\"EOD-QSO\",\"EOD-NITESD\",\"FOCAL-TEST\",\"FOCAL-TEST2\"]',0,5,'2025-11-04 01:04:47','2025-11-23 20:30:42'),(8,'For Approval',NULL,'/page_approvals','[\"Developer\",\"TESDS\",\"EA\",\"AED\",\"ED\",\"DDG\",\"DIVISION-CHIEF\",\"DIVISION-SupervisingTESDS\",\"Sr-TESDS\"]','[\"DEV\",\"ODDG-PP\",\"EOD-PO\",\"EOD-QSO\",\"EOD-NITESD\",\"FOCAL-TEST\",\"FOCAL-TEST2\"]',0,2,'2025-11-04 01:05:11','2025-11-23 20:13:58'),(9,'User Report',NULL,'/page_reports_users','[\"Developer\",\"ADMIN\",\"TESDS\",\"EA\",\"AED\",\"ED\",\"DDG\",\"DIVISION-CHIEF\",\"DIVISION-ADMIN\",\"DIVISION-SupervisingTESDS\",\"Sr-TESDS\"]','[\"DEV\",\"ODDG-PP\",\"EOD-PO\",\"EOD-QSO\",\"EOD-NITESD\",\"FOCAL-TEST\",\"FOCAL-TEST2\"]',7,1,'2025-11-04 01:05:48','2025-11-23 20:30:42'),(10,'Document Report',NULL,'/page_reports_documents','[\"Developer\",\"ADMIN\",\"TESDS\",\"EA\",\"AED\",\"ED\",\"DDG\",\"DIVISION-CHIEF\",\"DIVISION-ADMIN\",\"DIVISION-SupervisingTESDS\",\"Sr-TESDS\"]','[\"DEV\",\"ODDG-PP\",\"EOD-PO\",\"EOD-QSO\",\"EOD-NITESD\",\"FOCAL-TEST\",\"FOCAL-TEST2\"]',7,2,'2025-11-04 01:06:25','2025-11-23 20:30:42'),(11,'Settings',NULL,'/page_settings','[\"Developer\"]','[\"DEV\",\"ODDG-PP\",\"EOD-PO\",\"EOD-QSO\",\"EOD-NITESD\",\"FOCAL-TEST\",\"FOCAL-TEST2\"]',3,3,'2025-11-04 17:02:48','2025-11-23 20:50:44');
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
) ENGINE=InnoDB AUTO_INCREMENT=505 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `office_table`
--

LOCK TABLES `office_table` WRITE;
/*!40000 ALTER TABLE `office_table` DISABLE KEYS */;
INSERT INTO `office_table` VALUES (5,'DEV','DEV','2025-11-24 02:28:10'),(6,'ODDG-PP','PP','2025-11-23 18:35:37'),(7,'EOD-PO','PO','2025-11-23 18:35:51'),(8,'EOD-QSO','QSO','2025-11-23 18:36:01'),(9,'EOD-NITESD','NITESD','2025-11-23 18:36:12'),(10,'FOCAL-TEST','FOCTEST','2025-11-23 18:39:52'),(11,'FOCAL-TEST2','FOCTEST2','2025-11-23 18:41:54');
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
INSERT INTO `users` VALUES (16,'Developer','dev@email.com',NULL,'$2y$12$yT3r2NqonCYaWZXW0g1yH.mFjT3OHjfDDp/2sA3goMvX6jAAI4tDW',NULL,NULL,NULL,'Developer',13,'active',5),(17,'test.ppadmin01','test.ppadmin01@email.com',NULL,'$2y$12$yT3r2NqonCYaWZXW0g1yH.mFjT3OHjfDDp/2sA3goMvX6jAAI4tDW',NULL,'2025-11-23 18:42:48','2025-11-23 19:03:19','ADMIN',14,'active',6),(18,'test.ppadmin02','test.ppadmin02@email.com',NULL,'$2y$12$vuOFF1ezbJUR2SmWkEaI8ejL1LgD9DHDTK3ReF8aybncbDHPRZ/XC','UVjPXO4WBj82I4Lfbe0Silu3ZXKKwXspMfMqurW7ZVeNRk6cqTYcPtx58t9x','2025-11-23 18:43:38','2025-11-23 18:43:38','ADMIN',14,'active',6),(19,'test.pptesds','test.pptesds@email.com',NULL,'$2y$12$UDClpn40rMoi.ehSIs8P6.pkZ6rA0FzsxwkRRzM8gwOfdVoggZYT2',NULL,'2025-11-23 18:46:53','2025-11-23 18:46:53','TESDS',15,'active',6),(20,'test.ppea','test.ppea@email.com',NULL,'$2y$12$AeKor24wVdeCCXBbLj/Rc.6KGmgTO7.HU5wgxrOEm2yGnZUmJziRC',NULL,'2025-11-23 18:47:18','2025-11-23 18:47:18','EA',16,'active',6),(21,'test.ppsrtesds','test.ppsrtesds@email.com',NULL,'$2y$12$a40g6rhhvns/zpzEQzHMyOqpI2Lyfq5piaDuMwf4UP14y6YQ.mP7u',NULL,'2025-11-23 18:49:02','2025-11-23 18:49:02','Sr-TESDS',24,'active',6),(22,'test.poadmin01','test.poadmin01@email.com',NULL,'$2y$12$Z7U/Iz8EX14H12ScKKGlWui/sDQwyjg8uQY6sUd1tFqFSCyUG5DYa',NULL,'2025-11-23 18:49:19','2025-11-23 18:49:19','ADMIN',14,'active',7),(23,'test.poadmin02','test.poadmin02@email.com',NULL,'$2y$12$X1/s.sOXV8.Lzx.GSui3hezVPrpMyqVc9YwXmmPZM/y7sIW83kwjW',NULL,'2025-11-23 18:49:37','2025-11-23 18:49:37','ADMIN',14,'active',7),(24,'test.poaed','test.poaed@email.com',NULL,'$2y$12$t5ag0dK6EpPaM3o2/xo3c.s04.mm6dYQH2vKlT4Grg64BCEXg8O5a',NULL,'2025-11-23 18:50:08','2025-11-23 18:50:08','AED',17,'active',7),(25,'test.poed','test.poed@email.com',NULL,'$2y$12$UC1ZhGGEKYw6bkD4JCOPn.QSrFr.uw/XrCUAiqlvFIoVDneqWYGjO',NULL,'2025-11-23 18:50:23','2025-11-23 18:50:23','ED',18,'active',7),(26,'test.qsoadmin01','test.qsoadmin01@email.com',NULL,'$2y$12$1kLDjOhqZAJYhE6oiI/H1un8o03ooIzxtma9oUpKY5rz5LA0u5.xO',NULL,'2025-11-23 18:50:48','2025-11-23 18:50:48','ADMIN',14,'active',8),(27,'test.qsoadmin02','test.qsoadmin02@email.com',NULL,'$2y$12$oXhh6ogNfQvWSUBJFRIUr./6Ynp7E8tK8r5S9eYCXXsIYjALflXTi',NULL,'2025-11-23 18:51:04','2025-11-23 18:51:04','ADMIN',14,'active',8),(28,'test.qsoaed','test.qsoaed@email.com',NULL,'$2y$12$8vEfv2x3dHhX3aXQEavjoubcVAxmSYy1iSTfYN13S0DXipysRoUf2',NULL,'2025-11-23 18:51:21','2025-11-23 18:51:21','AED',17,'active',8),(29,'test.qsoed','test.qsoed@email.com',NULL,'$2y$12$BWCbYdVulKEC4o7hR2IxeO2e8aF95Spt4xQsNMb4nbfUmC4pBGqqe',NULL,'2025-11-23 18:51:38','2025-11-23 18:51:38','ED',18,'active',8),(30,'test.nitesdadmin01','test.nitesdadmin01@email.com',NULL,'$2y$12$Gw9cKJI0edGLvmKnkhTlGuS1X9PIezjl8Pt0mCz1sowChIo7jRT5.',NULL,'2025-11-23 18:51:56','2025-11-23 18:51:56','ADMIN',14,'active',9),(31,'test.nitesdadmin02','test.nitesdadmin02@email.com',NULL,'$2y$12$hGE7PnhSzgEU09RA5gMx0.8y3jsPND.Pb76UxHxOrq8igaE6UJwLa',NULL,'2025-11-23 18:52:20','2025-11-23 18:52:20','ADMIN',14,'active',9),(32,'test.nitesdaed','test.nitesdaed@email.com',NULL,'$2y$12$ngmAnAZYMHKHg36s/fKmJ.TX2G2i/Z8o..kjtFIjT580cRCLAURvu',NULL,'2025-11-23 18:53:58','2025-11-23 18:53:58','AED',17,'active',9),(33,'test.nitesded','test.nitesded@email.com',NULL,'$2y$12$ljj50YKMacjC4Eu/FDhmPOWILMt0BJtY/gl0eYAZejUeF40VmXfvy',NULL,'2025-11-23 18:54:15','2025-11-23 18:54:15','ED',18,'active',9),(34,'test.ppddg','test.ppddg@email.com',NULL,'$2y$12$dk2.ssG.RacpoWTsmKoIT.yM.F4U86yCR9bCOqizE5tza22We83wO',NULL,'2025-11-24 21:33:38','2025-11-24 21:33:38','DDG',19,'active',6);
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

-- Dump completed on 2025-12-22 10:05:27

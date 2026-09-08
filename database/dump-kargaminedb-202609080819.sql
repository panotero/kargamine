-- MySQL dump 10.13  Distrib 5.5.62, for Win64 (AMD64)
--
-- Host: localhost    Database: kargaminedb
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.24-MariaDB

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
-- Table structure for table `app_information_settings`
--

DROP TABLE IF EXISTS `app_information_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_information_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `app_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Management System',
  `logo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_information_settings`
--

LOCK TABLES `app_information_settings` WRITE;
/*!40000 ALTER TABLE `app_information_settings` DISABLE KEYS */;
INSERT INTO `app_information_settings` VALUES (1,'Kargamine',NULL,NULL,'2026-08-12 13:23:43','2026-08-13 16:26:12');
/*!40000 ALTER TABLE `app_information_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_theme_settings`
--

DROP TABLE IF EXISTS `app_theme_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_theme_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `main_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'blue',
  `accent_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'orange',
  `button_secondary_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'slate',
  `button_danger_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'red',
  `dark_mode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'system',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_theme_settings`
--

LOCK TABLES `app_theme_settings` WRITE;
/*!40000 ALTER TABLE `app_theme_settings` DISABLE KEYS */;
INSERT INTO `app_theme_settings` VALUES (1,'blue','orange','slate','red','system','2026-08-12 13:23:56','2026-08-12 13:23:56');
/*!40000 ALTER TABLE `app_theme_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bill_of_ladings`
--

DROP TABLE IF EXISTS `bill_of_ladings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bill_of_ladings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) unsigned NOT NULL,
  `bol_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `issued_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `issued_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bill_of_ladings_bol_number_unique` (`bol_number`),
  KEY `bill_of_ladings_booking_id_foreign` (`booking_id`),
  KEY `bill_of_ladings_issued_by_foreign` (`issued_by`),
  CONSTRAINT `bill_of_ladings_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE,
  CONSTRAINT `bill_of_ladings_issued_by_foreign` FOREIGN KEY (`issued_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bill_of_ladings`
--

LOCK TABLES `bill_of_ladings` WRITE;
/*!40000 ALTER TABLE `bill_of_ladings` DISABLE KEYS */;
INSERT INTO `bill_of_ladings` VALUES (1,10,'BOL-2026-0001','2026-08-24 16:42:35',1,'2026-08-24 16:42:35','2026-08-24 16:42:35'),(2,11,'BOL-2026-0002','2026-08-24 16:50:03',1,'2026-08-24 16:50:03','2026-08-24 16:50:03'),(3,12,'BOL-2026-0003','2026-08-25 07:23:32',1,'2026-08-25 07:23:32','2026-08-25 07:23:32'),(4,13,'BOL-2026-0004','2026-08-25 13:05:30',1,'2026-08-25 13:05:30','2026-08-25 13:05:30'),(5,14,'BOL-2026-0005','2026-08-25 14:24:24',1,'2026-08-25 14:24:24','2026-08-25 14:24:24');
/*!40000 ALTER TABLE `bill_of_ladings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `billed_details`
--

DROP TABLE IF EXISTS `billed_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `billed_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned NOT NULL,
  `billed_to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tin_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `billed_details_company_id_foreign` (`company_id`),
  CONSTRAINT `billed_details_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `company_info_master` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `billed_details`
--

LOCK TABLES `billed_details` WRITE;
/*!40000 ALTER TABLE `billed_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `billed_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_container_eir_records`
--

DROP TABLE IF EXISTS `booking_container_eir_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_container_eir_records` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_container_unit_id` bigint(20) unsigned NOT NULL,
  `direction` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `damage_codes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `damage_remarks` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `convan_checklist_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `damage_photo_paths` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`damage_photo_paths`)),
  `convan_class_id` bigint(20) unsigned DEFAULT NULL,
  `shipper_representative_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `driver_id_photo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issued_by` bigint(20) unsigned DEFAULT NULL,
  `issued_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_container_eir_records_convan_class_id_foreign` (`convan_class_id`),
  KEY `booking_container_eir_records_issued_by_foreign` (`issued_by`),
  KEY `booking_container_eir_unit_direction_index` (`booking_container_unit_id`,`direction`),
  CONSTRAINT `booking_container_eir_records_booking_container_unit_id_foreign` FOREIGN KEY (`booking_container_unit_id`) REFERENCES `booking_container_units` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_container_eir_records_convan_class_id_foreign` FOREIGN KEY (`convan_class_id`) REFERENCES `container_class` (`id`) ON DELETE SET NULL,
  CONSTRAINT `booking_container_eir_records_issued_by_foreign` FOREIGN KEY (`issued_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_container_eir_records`
--

LOCK TABLES `booking_container_eir_records` WRITE;
/*!40000 ALTER TABLE `booking_container_eir_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `booking_container_eir_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_container_units`
--

DROP TABLE IF EXISTS `booking_container_units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_container_units` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_line_id` bigint(20) unsigned NOT NULL,
  `booking_id` bigint(20) unsigned NOT NULL,
  `unit_index` int(10) unsigned NOT NULL,
  `gate_pass_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `container_asset_id` bigint(20) unsigned DEFAULT NULL,
  `seal_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proforma_bl_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `waybill_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gate_pass_out_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actual_gate_out_at` timestamp NULL DEFAULT NULL,
  `gate_out_scanned_by` bigint(20) unsigned DEFAULT NULL,
  `actual_gate_in_at` timestamp NULL DEFAULT NULL,
  `gate_in_scanned_by` bigint(20) unsigned DEFAULT NULL,
  `vessel_voyage_id` bigint(20) unsigned DEFAULT NULL,
  `equivalent_teu` decimal(8,2) DEFAULT NULL,
  `relay_port_id` bigint(20) unsigned DEFAULT NULL,
  `shut_out_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `origin_port_id` bigint(20) unsigned NOT NULL,
  `destination_port_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `booking_container_units_gate_pass_code_unique` (`gate_pass_code`),
  UNIQUE KEY `booking_container_units_gate_pass_out_number_unique` (`gate_pass_out_number`),
  KEY `booking_container_units_booking_line_id_foreign` (`booking_line_id`),
  KEY `booking_container_units_container_asset_id_foreign` (`container_asset_id`),
  KEY `booking_container_units_origin_port_id_foreign` (`origin_port_id`),
  KEY `booking_container_units_destination_port_id_foreign` (`destination_port_id`),
  KEY `booking_container_units_booking_id_index` (`booking_id`),
  KEY `booking_container_units_gate_out_scanned_by_foreign` (`gate_out_scanned_by`),
  KEY `booking_container_units_gate_in_scanned_by_foreign` (`gate_in_scanned_by`),
  KEY `booking_container_units_vessel_voyage_id_foreign` (`vessel_voyage_id`),
  KEY `booking_container_units_relay_port_id_foreign` (`relay_port_id`),
  CONSTRAINT `booking_container_units_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE,
  CONSTRAINT `booking_container_units_booking_line_id_foreign` FOREIGN KEY (`booking_line_id`) REFERENCES `booking_lines` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_container_units_container_asset_id_foreign` FOREIGN KEY (`container_asset_id`) REFERENCES `container_assets` (`id`) ON DELETE SET NULL,
  CONSTRAINT `booking_container_units_destination_port_id_foreign` FOREIGN KEY (`destination_port_id`) REFERENCES `ports` (`port_id`),
  CONSTRAINT `booking_container_units_gate_in_scanned_by_foreign` FOREIGN KEY (`gate_in_scanned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `booking_container_units_gate_out_scanned_by_foreign` FOREIGN KEY (`gate_out_scanned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `booking_container_units_origin_port_id_foreign` FOREIGN KEY (`origin_port_id`) REFERENCES `ports` (`port_id`),
  CONSTRAINT `booking_container_units_relay_port_id_foreign` FOREIGN KEY (`relay_port_id`) REFERENCES `ports` (`port_id`) ON DELETE SET NULL,
  CONSTRAINT `booking_container_units_vessel_voyage_id_foreign` FOREIGN KEY (`vessel_voyage_id`) REFERENCES `vessel_voyages` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_container_units`
--

LOCK TABLES `booking_container_units` WRITE;
/*!40000 ALTER TABLE `booking_container_units` DISABLE KEYS */;
INSERT INTO `booking_container_units` VALUES (42,16,10,1,'GP-BK-2026-0001-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-22 15:58:05','2026-08-22 16:58:08'),(43,16,10,2,'GP-BK-2026-0001-02',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-22 15:58:05','2026-08-22 15:58:05'),(44,16,10,3,'GP-BK-2026-0001-03',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-22 15:58:05','2026-08-22 15:58:05'),(45,17,10,1,'GP-BK-2026-0001-04',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-22 15:58:05','2026-08-22 15:58:05'),(46,17,10,2,'GP-BK-2026-0001-05',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-22 15:58:05','2026-08-22 15:58:05'),(47,18,10,1,'GP-BK-2026-0001-06',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-22 15:58:05','2026-08-22 15:58:05'),(48,18,10,2,'GP-BK-2026-0001-07',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-22 15:58:05','2026-08-22 15:58:05'),(49,19,10,1,'GP-BK-2026-0001-08',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-22 15:58:05','2026-08-22 15:58:05'),(50,19,10,2,'GP-BK-2026-0001-09',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-22 15:58:05','2026-08-22 15:58:05'),(51,19,10,3,'GP-BK-2026-0001-10',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-22 15:58:05','2026-08-22 15:58:05'),(52,19,10,4,'GP-BK-2026-0001-11',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-22 15:58:05','2026-08-22 15:58:05'),(53,19,10,5,'GP-BK-2026-0001-12',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-22 15:58:05','2026-08-22 15:58:05'),(54,19,10,6,'GP-BK-2026-0001-13',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-22 15:58:05','2026-08-22 15:58:05'),(55,19,10,7,'GP-BK-2026-0001-14',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-22 15:58:05','2026-08-22 15:58:05'),(56,19,10,8,'GP-BK-2026-0001-15',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-22 15:58:05','2026-08-22 15:58:05'),(57,19,10,9,'GP-BK-2026-0001-16',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-22 15:58:05','2026-08-22 15:58:05'),(58,19,10,10,'GP-BK-2026-0001-17',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-22 15:58:05','2026-08-22 15:58:05'),(59,19,10,11,'GP-BK-2026-0001-18',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-22 15:58:05','2026-08-22 15:58:05'),(60,20,11,1,'GP-BK-2026-0002-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-24 16:49:36','2026-08-24 16:49:36'),(61,20,11,2,'GP-BK-2026-0002-02',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-24 16:49:36','2026-08-24 16:49:36'),(62,21,12,1,'GP-BK-2026-0003-01',NULL,'test','test','test',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-25 07:23:03','2026-08-25 07:32:50'),(63,21,12,2,'GP-BK-2026-0003-02',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-25 07:23:03','2026-08-25 07:23:03'),(64,21,12,3,'GP-BK-2026-0003-03',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-25 07:23:03','2026-08-25 07:23:03'),(65,21,12,4,'GP-BK-2026-0003-04',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-25 07:23:03','2026-08-25 07:23:03'),(66,21,12,5,'GP-BK-2026-0003-05',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-25 07:23:03','2026-08-25 07:23:03'),(67,22,12,1,'GP-BK-2026-0003-06',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-25 07:23:03','2026-08-25 07:23:03'),(68,23,13,1,'GP-BK-2026-0004-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-25 13:05:11','2026-08-25 13:05:11'),(69,23,13,2,'GP-BK-2026-0004-02',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-25 13:05:11','2026-08-25 13:05:11'),(70,24,13,1,'GP-BK-2026-0004-03',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-25 13:05:11','2026-08-25 13:05:11'),(71,24,13,2,'GP-BK-2026-0004-04',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-25 13:05:11','2026-08-25 13:05:11'),(72,24,13,3,'GP-BK-2026-0004-05',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-25 13:05:11','2026-08-25 13:05:11'),(73,24,13,4,'GP-BK-2026-0004-06',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-25 13:05:11','2026-08-25 13:05:11'),(75,26,14,1,'GP-BK-2026-0005-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,7,32,'2026-08-25 14:24:24','2026-08-25 14:24:24'),(116,31,15,1,'GP-BK-2026-0006-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,29,19,'2026-09-04 17:40:32','2026-09-07 07:02:04'),(117,31,15,2,'GP-BK-2026-0006-02',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,29,19,'2026-09-04 17:40:32','2026-09-04 17:40:32'),(118,31,15,3,'GP-BK-2026-0006-03',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,29,19,'2026-09-04 17:40:32','2026-09-04 17:40:32'),(119,31,15,4,'GP-BK-2026-0006-04',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,29,19,'2026-09-04 17:40:32','2026-09-04 17:40:32'),(120,31,15,5,'GP-BK-2026-0006-05',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,29,19,'2026-09-04 17:40:32','2026-09-04 17:40:32'),(121,31,15,6,'GP-BK-2026-0006-06',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,29,19,'2026-09-04 17:40:32','2026-09-04 17:40:32'),(122,31,15,7,'GP-BK-2026-0006-07',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,29,19,'2026-09-04 17:40:32','2026-09-04 17:40:32'),(123,31,15,8,'GP-BK-2026-0006-08',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,29,19,'2026-09-04 17:40:32','2026-09-04 17:40:32'),(124,31,15,9,'GP-BK-2026-0006-09',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,29,19,'2026-09-04 17:40:32','2026-09-04 17:40:32'),(125,31,15,10,'GP-BK-2026-0006-10',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,29,19,'2026-09-04 17:40:32','2026-09-04 17:40:32');
/*!40000 ALTER TABLE `booking_container_units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_dispatch_documents`
--

DROP TABLE IF EXISTS `booking_dispatch_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_dispatch_documents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_line_id` bigint(20) unsigned NOT NULL,
  `booking_id` bigint(20) unsigned NOT NULL,
  `document_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `generated_by` bigint(20) unsigned DEFAULT NULL,
  `generated_at` timestamp NULL DEFAULT NULL,
  `is_single_pickup` tinyint(1) NOT NULL DEFAULT 0,
  `is_advance_pull_out` tinyint(1) NOT NULL DEFAULT 0,
  `trip_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trailer_capacity` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `convan_count` int(10) unsigned DEFAULT NULL,
  `convan_size` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authorized_trucker` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plate_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authorized_driver` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `helper` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coordinator_checker` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cy_empty_pull_out_at` timestamp NULL DEFAULT NULL,
  `cy_stuffing_activity_at` timestamp NULL DEFAULT NULL,
  `cy_stripping_activity_at` timestamp NULL DEFAULT NULL,
  `cy_delivery_of_cargo_at` timestamp NULL DEFAULT NULL,
  `estimated_departure_at` timestamp NULL DEFAULT NULL,
  `estimated_arrival_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `booking_dispatch_documents_booking_line_id_unique` (`booking_line_id`),
  UNIQUE KEY `booking_dispatch_documents_document_number_unique` (`document_number`),
  KEY `booking_dispatch_documents_generated_by_foreign` (`generated_by`),
  KEY `booking_dispatch_documents_booking_id_index` (`booking_id`),
  CONSTRAINT `booking_dispatch_documents_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE,
  CONSTRAINT `booking_dispatch_documents_booking_line_id_foreign` FOREIGN KEY (`booking_line_id`) REFERENCES `booking_lines` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_dispatch_documents_generated_by_foreign` FOREIGN KEY (`generated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_dispatch_documents`
--

LOCK TABLES `booking_dispatch_documents` WRITE;
/*!40000 ALTER TABLE `booking_dispatch_documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `booking_dispatch_documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_invoices`
--

DROP TABLE IF EXISTS `booking_invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `due_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `booking_invoices_invoice_number_unique` (`invoice_number`),
  KEY `booking_invoices_booking_id_foreign` (`booking_id`),
  KEY `booking_invoices_client_id_foreign` (`client_id`),
  CONSTRAINT `booking_invoices_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE,
  CONSTRAINT `booking_invoices_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `client_masters` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_invoices`
--

LOCK TABLES `booking_invoices` WRITE;
/*!40000 ALTER TABLE `booking_invoices` DISABLE KEYS */;
INSERT INTO `booking_invoices` VALUES (1,10,1,'INV-2026-0001',1,90462.40,'2026-09-21','2026-08-24 16:42:35','2026-08-24 16:42:35'),(2,11,1,'INV-2026-0002',1,24080.00,'2026-09-23','2026-08-24 16:50:03','2026-08-24 16:50:03'),(3,12,1,'INV-2026-0003',1,68230.40,'2026-09-24','2026-08-25 07:23:32','2026-08-25 07:23:32'),(4,13,1,'INV-2026-0004',1,61241.60,'2026-09-24','2026-08-25 13:05:30','2026-08-25 13:05:30'),(5,14,1,'INV-2026-0005',1,12880.00,'2026-09-24','2026-08-25 14:24:24','2026-08-25 14:24:24');
/*!40000 ALTER TABLE `booking_invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_lines`
--

DROP TABLE IF EXISTS `booking_lines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_lines` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) unsigned NOT NULL,
  `origin_port_id` bigint(20) unsigned DEFAULT NULL,
  `destination_port_id` bigint(20) unsigned DEFAULT NULL,
  `origin_area_id` bigint(20) unsigned DEFAULT NULL,
  `destination_area_id` bigint(20) unsigned DEFAULT NULL,
  `delivery_type_id` bigint(20) unsigned DEFAULT NULL,
  `origin_pier_handling` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destination_pier_handling` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lane_id` bigint(20) unsigned DEFAULT NULL,
  `tariff_rate_id` bigint(20) unsigned DEFAULT NULL,
  `container_id` bigint(20) unsigned NOT NULL,
  `container_class_id` bigint(20) unsigned DEFAULT NULL,
  `container_size_id` bigint(20) unsigned DEFAULT NULL,
  `container_variant_id` bigint(20) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL DEFAULT 1,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight_kg` decimal(12,2) DEFAULT NULL,
  `volume_cbm` decimal(12,2) DEFAULT NULL,
  `is_hazardous` tinyint(1) NOT NULL DEFAULT 0,
  `is_fragile` tinyint(1) NOT NULL DEFAULT 0,
  `hazardous_document_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `minimum_temperature` decimal(5,1) DEFAULT NULL,
  `frt_snapshot` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_type_snapshot` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_value_snapshot` decimal(12,2) NOT NULL DEFAULT 0.00,
  `frt_after_discount_snapshot` decimal(12,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `trucking_snapshot` decimal(12,2) NOT NULL DEFAULT 0.00,
  `consignee_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `consignee_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `consignee_contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `consignee_contact_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other_cargo_details` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `declared_value` decimal(14,2) DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `delivery_date_notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_delivery_date` date DEFAULT NULL,
  `last_delivery_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_lines_container_id_foreign` (`container_id`),
  KEY `booking_lines_container_class_id_foreign` (`container_class_id`),
  KEY `booking_lines_container_size_id_foreign` (`container_size_id`),
  KEY `booking_lines_container_variant_id_foreign` (`container_variant_id`),
  KEY `booking_lines_booking_id_index` (`booking_id`),
  KEY `booking_lines_origin_port_id_foreign` (`origin_port_id`),
  KEY `booking_lines_destination_port_id_foreign` (`destination_port_id`),
  KEY `booking_lines_origin_area_id_foreign` (`origin_area_id`),
  KEY `booking_lines_destination_area_id_foreign` (`destination_area_id`),
  KEY `booking_lines_delivery_type_id_foreign` (`delivery_type_id`),
  KEY `booking_lines_lane_id_foreign` (`lane_id`),
  KEY `booking_lines_tariff_rate_id_foreign` (`tariff_rate_id`),
  CONSTRAINT `booking_lines_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE,
  CONSTRAINT `booking_lines_container_class_id_foreign` FOREIGN KEY (`container_class_id`) REFERENCES `container_class` (`id`),
  CONSTRAINT `booking_lines_container_id_foreign` FOREIGN KEY (`container_id`) REFERENCES `containers` (`id`),
  CONSTRAINT `booking_lines_container_size_id_foreign` FOREIGN KEY (`container_size_id`) REFERENCES `container_size` (`id`),
  CONSTRAINT `booking_lines_container_variant_id_foreign` FOREIGN KEY (`container_variant_id`) REFERENCES `container_variants` (`id`),
  CONSTRAINT `booking_lines_delivery_type_id_foreign` FOREIGN KEY (`delivery_type_id`) REFERENCES `delivery_types` (`delivery_type_id`),
  CONSTRAINT `booking_lines_destination_area_id_foreign` FOREIGN KEY (`destination_area_id`) REFERENCES `serviceable_areas` (`area_id`),
  CONSTRAINT `booking_lines_destination_port_id_foreign` FOREIGN KEY (`destination_port_id`) REFERENCES `ports` (`port_id`),
  CONSTRAINT `booking_lines_lane_id_foreign` FOREIGN KEY (`lane_id`) REFERENCES `lanes` (`lane_id`),
  CONSTRAINT `booking_lines_origin_area_id_foreign` FOREIGN KEY (`origin_area_id`) REFERENCES `serviceable_areas` (`area_id`),
  CONSTRAINT `booking_lines_origin_port_id_foreign` FOREIGN KEY (`origin_port_id`) REFERENCES `ports` (`port_id`),
  CONSTRAINT `booking_lines_tariff_rate_id_foreign` FOREIGN KEY (`tariff_rate_id`) REFERENCES `lane_tariff_rates` (`rate_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_lines`
--

LOCK TABLES `booking_lines` WRITE;
/*!40000 ALTER TABLE `booking_lines` DISABLE KEYS */;
INSERT INTO `booking_lines` VALUES (16,10,7,32,11,216,1,NULL,NULL,14,14,3,NULL,5,9,3,'test',123.00,123.00,0,0,NULL,NULL,8000.00,'percentage',1.00,7920.00,23760.00,1500.00,'test','test','test','test','test','test',123.00,NULL,NULL,NULL,NULL,'2026-08-22 15:58:05','2026-08-22 15:58:05'),(17,10,7,32,11,216,1,NULL,NULL,14,14,1,NULL,1,1,2,'test',123.00,123.00,0,0,NULL,NULL,10000.00,NULL,0.00,10000.00,20000.00,1500.00,'test','test','test','test','test','test',123.00,NULL,NULL,NULL,NULL,'2026-08-22 15:58:05','2026-08-22 15:58:05'),(18,10,7,32,11,216,1,NULL,NULL,14,14,2,NULL,3,7,2,'test',123.00,123.00,0,0,NULL,123.0,15000.00,'fixed',100.00,14900.00,29800.00,1500.00,'test','test','test','test','test','test',123.00,NULL,NULL,NULL,NULL,'2026-08-22 15:58:05','2026-08-22 15:58:05'),(19,10,7,32,11,216,1,NULL,NULL,14,14,5,5,NULL,13,11,'test',123.00,123.00,0,0,NULL,NULL,110.00,NULL,0.00,110.00,1210.00,1500.00,'test','test','test','test','test','test',123.00,NULL,NULL,NULL,NULL,'2026-08-22 15:58:05','2026-08-22 15:58:05'),(20,11,7,32,11,217,1,NULL,NULL,14,14,1,NULL,1,1,2,'test',123.00,123.00,1,0,'http://kargamine_prototype.test/uploads/booking/hazmat/fef4d2f659dbc56ee13e42b874477cf58e610b9c04af9c70a32bc96584ddee36.png',NULL,10000.00,NULL,0.00,10000.00,20000.00,1500.00,'test','test','test','123','123','test',123.00,'2026-08-01','test','2026-08-01','2026-08-01','2026-08-24 16:49:36','2026-08-24 16:49:36'),(21,12,7,32,14,219,1,NULL,NULL,14,14,1,NULL,1,1,5,NULL,NULL,NULL,0,0,NULL,NULL,10000.00,NULL,0.00,10000.00,50000.00,1500.00,'test','test','test','123','General Merchandise',NULL,5000.00,'2026-08-29','test','2026-08-30','2026-08-31','2026-08-25 07:23:03','2026-08-25 12:48:11'),(22,12,7,32,14,219,1,NULL,NULL,14,14,3,NULL,5,9,1,'test',123.00,123.00,0,0,NULL,NULL,8000.00,'percentage',1.00,7920.00,7920.00,1500.00,'test','test','test','123','test','test',123.00,'2026-08-29','test','2026-08-30','2026-08-31','2026-08-25 07:23:03','2026-08-25 07:23:03'),(23,13,7,32,9,217,1,NULL,NULL,14,14,1,NULL,1,1,2,'test',123.00,123.00,0,0,NULL,NULL,10000.00,NULL,0.00,10000.00,20000.00,1500.00,'test','test','test','test','test','test',123.00,NULL,NULL,NULL,NULL,'2026-08-25 13:05:11','2026-08-25 13:05:11'),(24,13,7,32,9,217,1,NULL,NULL,14,14,3,NULL,5,9,4,'test',123.00,123.00,0,0,NULL,NULL,8000.00,'percentage',1.00,7920.00,31680.00,1500.00,'test','test','test','test','test','test',123.00,NULL,NULL,NULL,NULL,'2026-08-25 13:05:11','2026-08-25 13:05:11'),(26,14,7,32,9,216,1,NULL,NULL,14,14,1,NULL,1,1,1,'test',123.00,123.00,0,0,NULL,NULL,10000.00,NULL,0.00,10000.00,10000.00,1500.00,'test','test','test','123123','test','test',123.00,'2026-08-29','test','2026-08-29','2026-09-02','2026-08-25 14:24:24','2026-08-25 14:24:24'),(31,15,29,19,157,79,1,NULL,NULL,1,1,1,NULL,1,1,10,'test',123.00,123.00,0,0,NULL,NULL,9000.00,NULL,0.00,9000.00,90000.00,3000.00,'test','test','test','test','test','test',123.00,'2026-09-05','test','2026-09-12','2026-09-13','2026-09-04 17:40:32','2026-09-04 17:40:32');
/*!40000 ALTER TABLE `booking_lines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_port_charges`
--

DROP TABLE IF EXISTS `booking_port_charges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_port_charges` (
  `booking_port_charge_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) unsigned NOT NULL,
  `booking_line_id` bigint(20) unsigned DEFAULT NULL,
  `port_id` bigint(20) unsigned NOT NULL,
  `charge_type_id` bigint(20) unsigned NOT NULL,
  `role` enum('ORIGIN','DESTINATION') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount_snapshot` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`booking_port_charge_id`),
  KEY `booking_port_charges_port_id_foreign` (`port_id`),
  KEY `booking_port_charges_charge_type_id_foreign` (`charge_type_id`),
  KEY `booking_port_charges_booking_id_role_index` (`booking_id`,`role`),
  KEY `booking_port_charges_booking_line_id_foreign` (`booking_line_id`),
  CONSTRAINT `booking_port_charges_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `booking_port_charges_booking_line_id_foreign` FOREIGN KEY (`booking_line_id`) REFERENCES `booking_lines` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_port_charges_charge_type_id_foreign` FOREIGN KEY (`charge_type_id`) REFERENCES `charge_types` (`charge_type_id`) ON UPDATE CASCADE,
  CONSTRAINT `booking_port_charges_port_id_foreign` FOREIGN KEY (`port_id`) REFERENCES `ports` (`port_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_port_charges`
--

LOCK TABLES `booking_port_charges` WRITE;
/*!40000 ALTER TABLE `booking_port_charges` DISABLE KEYS */;
INSERT INTO `booking_port_charges` VALUES (67,15,31,29,1,'ORIGIN',500.00,'2026-09-04 17:40:32','2026-09-04 17:40:32'),(68,15,31,29,2,'ORIGIN',800.00,'2026-09-04 17:40:32','2026-09-04 17:40:32'),(69,15,31,29,3,'ORIGIN',1200.00,'2026-09-04 17:40:32','2026-09-04 17:40:32'),(70,15,31,19,1,'DESTINATION',500.00,'2026-09-04 17:40:32','2026-09-04 17:40:32'),(71,15,31,19,2,'DESTINATION',800.00,'2026-09-04 17:40:32','2026-09-04 17:40:32'),(72,15,31,19,3,'DESTINATION',1200.00,'2026-09-04 17:40:32','2026-09-04 17:40:32');
/*!40000 ALTER TABLE `booking_port_charges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_status_history`
--

DROP TABLE IF EXISTS `booking_status_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_status_history` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) unsigned NOT NULL,
  `from_status` tinyint(3) unsigned DEFAULT NULL,
  `to_status` tinyint(3) unsigned NOT NULL,
  `changed_by` bigint(20) unsigned DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_status_history_changed_by_foreign` (`changed_by`),
  KEY `booking_status_history_booking_id_changed_at_index` (`booking_id`,`changed_at`),
  CONSTRAINT `booking_status_history_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE,
  CONSTRAINT `booking_status_history_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_status_history`
--

LOCK TABLES `booking_status_history` WRITE;
/*!40000 ALTER TABLE `booking_status_history` DISABLE KEYS */;
INSERT INTO `booking_status_history` VALUES (6,10,NULL,1,1,NULL,'2026-08-22 15:56:57','2026-08-22 15:56:57','2026-08-22 15:56:57'),(7,10,1,2,1,NULL,'2026-08-24 16:42:35','2026-08-24 16:42:35','2026-08-24 16:42:35'),(8,11,NULL,1,1,NULL,'2026-08-24 16:49:36','2026-08-24 16:49:36','2026-08-24 16:49:36'),(9,11,1,2,1,NULL,'2026-08-24 16:50:03','2026-08-24 16:50:03','2026-08-24 16:50:03'),(10,12,NULL,1,1,NULL,'2026-08-25 07:23:03','2026-08-25 07:23:03','2026-08-25 07:23:03'),(11,12,1,2,1,NULL,'2026-08-25 07:23:32','2026-08-25 07:23:32','2026-08-25 07:23:32'),(12,13,NULL,1,1,NULL,'2026-08-25 13:05:11','2026-08-25 13:05:11','2026-08-25 13:05:11'),(13,13,1,2,1,NULL,'2026-08-25 13:05:30','2026-08-25 13:05:30','2026-08-25 13:05:30'),(14,14,NULL,1,1,NULL,'2026-08-25 14:08:11','2026-08-25 14:08:11','2026-08-25 14:08:11'),(15,14,1,2,1,NULL,'2026-08-25 14:24:24','2026-08-25 14:24:24','2026-08-25 14:24:24'),(16,15,NULL,1,1,NULL,'2026-09-04 14:40:16','2026-09-04 14:40:16','2026-09-04 14:40:16'),(17,14,2,6,1,'client requested to cancel and manager approved it.','2026-09-06 18:07:17','2026-09-06 18:07:17','2026-09-06 18:07:17');
/*!40000 ALTER TABLE `booking_status_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bookings` (
  `booking_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_id` bigint(20) unsigned DEFAULT NULL,
  `client_contract_id` bigint(20) unsigned DEFAULT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `vat_rate_id` bigint(20) unsigned NOT NULL,
  `contract_id` bigint(20) unsigned DEFAULT NULL,
  `contract_rate_id` bigint(20) unsigned DEFAULT NULL,
  `trucking_snapshot` decimal(12,2) NOT NULL DEFAULT 0.00,
  `vat_amount_snapshot` decimal(12,2) NOT NULL DEFAULT 0.00,
  `grand_total_snapshot` decimal(12,2) NOT NULL DEFAULT 0.00,
  `booking_date` date NOT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`booking_id`),
  UNIQUE KEY `bookings_uuid_unique` (`uuid`),
  UNIQUE KEY `bookings_code_unique` (`code`),
  KEY `bookings_vat_rate_id_foreign` (`vat_rate_id`),
  KEY `bookings_contract_id_foreign` (`contract_id`),
  KEY `bookings_contract_rate_id_foreign` (`contract_rate_id`),
  KEY `bookings_created_by_foreign` (`created_by`),
  KEY `bookings_lane_id_booking_date_index` (`booking_date`),
  KEY `bookings_client_id_foreign` (`client_id`),
  KEY `bookings_client_contract_id_foreign` (`client_contract_id`),
  CONSTRAINT `bookings_client_contract_id_foreign` FOREIGN KEY (`client_contract_id`) REFERENCES `client_contracts` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bookings_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `client_masters` (`id`),
  CONSTRAINT `bookings_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`),
  CONSTRAINT `bookings_contract_rate_id_foreign` FOREIGN KEY (`contract_rate_id`) REFERENCES `contract_rates` (`id`),
  CONSTRAINT `bookings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bookings_vat_rate_id_foreign` FOREIGN KEY (`vat_rate_id`) REFERENCES `vat_rates` (`vat_rate_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (10,'a2907af2-8b9e-41e0-bf49-e8ae9d4aba0c','BK-2026-0001',1,1,2,3,NULL,NULL,6000.00,9692.40,90462.40,'2026-08-22',1,'2026-08-22 15:56:57','2026-08-24 16:42:35'),(11,'a29493be-e79d-4de4-93b5-c09b8b797d3f','BK-2026-0002',1,1,2,3,NULL,NULL,1500.00,2580.00,24080.00,'2026-08-24',1,'2026-08-24 16:49:36','2026-08-24 16:50:03'),(12,'a295cc1e-aecd-42ee-afd7-b8c2980c7c1c','BK-2026-0003',1,1,2,3,NULL,NULL,3000.00,7310.40,68230.40,'2026-08-25',1,'2026-08-25 07:23:03','2026-08-25 07:23:32'),(13,'a2964679-970c-486d-9712-90484bde5694','BK-2026-0004',1,1,2,3,NULL,NULL,3000.00,6561.60,61241.60,'2026-08-25',1,'2026-08-25 13:05:11','2026-08-25 13:05:30'),(14,'a2965d01-26ad-432b-a45e-61873a248c78','BK-2026-0005',1,1,6,3,NULL,NULL,1500.00,1380.00,12880.00,'2026-08-25',1,'2026-08-25 14:08:11','2026-09-06 18:07:17'),(15,'a2aa8650-fe10-4f32-be84-9bb4d485aac3','BK-2026-0006',2,NULL,1,3,NULL,NULL,3000.00,11940.00,111440.00,'2026-09-01',1,'2026-09-04 14:40:15','2026-09-04 17:40:32');
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('management_system_cache_a3affa0d1e1a3c72b78aa984c3367a05','i:3;',1787385605),('management_system_cache_a3affa0d1e1a3c72b78aa984c3367a05:timer','i:1787385605;',1787385605),('management_system_cache_d2bfa8e8b749d2772a21edee7b70a2b3','i:4;',1788664188),('management_system_cache_d2bfa8e8b749d2772a21edee7b70a2b3:timer','i:1788664188;',1788664188),('management_system_cache_df21bfa12c4e294c70f64916c0fbc9a5','i:1;',1787369828),('management_system_cache_df21bfa12c4e294c70f64916c0fbc9a5:timer','i:1787369828;',1787369828),('management_system_cache_e7cf66797159dc3cd3e85f72e15bb551','i:3;',1788757188),('management_system_cache_e7cf66797159dc3cd3e85f72e15bb551:timer','i:1788757188;',1788757188),('management_system_cache_f1f70ec40aaa556905d4a030501c0ba4','i:1;',1788826831),('management_system_cache_f1f70ec40aaa556905d4a030501c0ba4:timer','i:1788826831;',1788826831);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cargo_yards`
--

DROP TABLE IF EXISTS `cargo_yards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cargo_yards` (
  `cargo_yard_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`cargo_yard_id`),
  UNIQUE KEY `cargo_yards_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cargo_yards`
--

LOCK TABLES `cargo_yards` WRITE;
/*!40000 ALTER TABLE `cargo_yards` DISABLE KEYS */;
INSERT INTO `cargo_yards` VALUES (1,'Batangas - Bauan',1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(2,'CDO - Villanueva',1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(3,'Cebu - Talisay',1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(4,'Masbate - Mobo',1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(5,'Palawan - Brooke\'s Point',1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(6,'Palawan - Coron',1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(7,'Palawan - Puerto Princesa',1,'2026-08-12 13:23:27','2026-08-12 13:23:27');
/*!40000 ALTER TABLE `cargo_yards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `charge_types`
--

DROP TABLE IF EXISTS `charge_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `charge_types` (
  `charge_type_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `applicable_to` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PORT',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`charge_type_id`),
  UNIQUE KEY `charge_types_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `charge_types`
--

LOCK TABLES `charge_types` WRITE;
/*!40000 ALTER TABLE `charge_types` DISABLE KEYS */;
INSERT INTO `charge_types` VALUES (1,'WHARF','Wharfage','PORT',1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(2,'ARRASTRE','Arrastre Charge','PORT',1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(3,'THC','Terminal Handling Charge','PORT',1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(4,'DOC_FEE','Documentation Fee','GENERAL',1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(5,'INS_FEE','Insurance Fee','GENERAL',1,'2026-08-12 13:23:27','2026-08-12 13:23:27');
/*!40000 ALTER TABLE `charge_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_addresses`
--

DROP TABLE IF EXISTS `client_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_addresses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned NOT NULL,
  `address_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `address_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_building` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_street` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_barangay` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_town_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_province` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Philippines',
  `address_postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_addresses_client_id_foreign` (`client_id`),
  CONSTRAINT `client_addresses_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `client_masters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_addresses`
--

LOCK TABLES `client_addresses` WRITE;
/*!40000 ALTER TABLE `client_addresses` DISABLE KEYS */;
INSERT INTO `client_addresses` VALUES (1,1,'Branch',1,NULL,NULL,NULL,'Manghinao Uno','Bauan','Batangas','Philippines',NULL,'2026-08-22 18:10:45','2026-08-22 18:10:45'),(4,2,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,'Philippines',NULL,'2026-09-04 05:10:38','2026-09-04 05:10:38');
/*!40000 ALTER TABLE `client_addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_ancillary_services`
--

DROP TABLE IF EXISTS `client_ancillary_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_ancillary_services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned NOT NULL,
  `required_service` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `quantity` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_ancillary_services_client_id_foreign` (`client_id`),
  CONSTRAINT `client_ancillary_services_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `client_masters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_ancillary_services`
--

LOCK TABLES `client_ancillary_services` WRITE;
/*!40000 ALTER TABLE `client_ancillary_services` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_ancillary_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_billing`
--

DROP TABLE IF EXISTS `client_billing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_billing` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned NOT NULL,
  `billed_to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `client_billing_client_id_unique` (`client_id`),
  CONSTRAINT `client_billing_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `client_masters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_billing`
--

LOCK TABLES `client_billing` WRITE;
/*!40000 ALTER TABLE `client_billing` DISABLE KEYS */;
INSERT INTO `client_billing` VALUES (1,1,'qwe','qwe','qwe','123','2026-07-27 11:25:45','2026-07-27 11:25:45'),(2,4,'test','test','test','123123','2026-07-28 16:46:05','2026-07-28 16:46:05'),(3,5,'JolleeMc','JolleeMc','San Francisco','001-125-745-001','2026-08-01 00:18:23','2026-08-01 00:18:23'),(4,6,'JolleeMc','JolleeMc','San Francisco','001-125-745-001','2026-08-01 22:31:29','2026-08-01 22:31:29');
/*!40000 ALTER TABLE `client_billing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_commodity_declared_values`
--

DROP TABLE IF EXISTS `client_commodity_declared_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_commodity_declared_values` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned NOT NULL,
  `commodity_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `max_declared_value` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_commodity_declared_values_client_id_foreign` (`client_id`),
  CONSTRAINT `client_commodity_declared_values_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `client_masters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_commodity_declared_values`
--

LOCK TABLES `client_commodity_declared_values` WRITE;
/*!40000 ALTER TABLE `client_commodity_declared_values` DISABLE KEYS */;
INSERT INTO `client_commodity_declared_values` VALUES (1,1,'Assorted Parlor Equipment',500000.00,'2026-08-22 18:14:42','2026-08-22 18:14:42');
/*!40000 ALTER TABLE `client_commodity_declared_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_contact_addresses`
--

DROP TABLE IF EXISTS `client_contact_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_contact_addresses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `contact_id` bigint(20) unsigned NOT NULL,
  `address_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `address_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_building` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_street` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_barangay` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_town_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_province` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Philippines',
  `address_postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_contact_addresses_contact_id_foreign` (`contact_id`),
  CONSTRAINT `client_contact_addresses_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `client_contacts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_contact_addresses`
--

LOCK TABLES `client_contact_addresses` WRITE;
/*!40000 ALTER TABLE `client_contact_addresses` DISABLE KEYS */;
INSERT INTO `client_contact_addresses` VALUES (1,1,NULL,1,NULL,NULL,NULL,NULL,'Mabini','Batangas','Philippines',NULL,'2026-08-22 18:12:37','2026-08-22 18:12:37'),(2,2,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,'Philippines',NULL,'2026-09-04 05:06:34','2026-09-04 05:06:34');
/*!40000 ALTER TABLE `client_contact_addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_contacts`
--

DROP TABLE IF EXISTS `client_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_contacts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned NOT NULL,
  `contact_department` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `landline_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `landline_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_contacts_client_id_foreign` (`client_id`),
  CONSTRAINT `client_contacts_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `client_masters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_contacts`
--

LOCK TABLES `client_contacts` WRITE;
/*!40000 ALTER TABLE `client_contacts` DISABLE KEYS */;
INSERT INTO `client_contacts` VALUES (1,1,'Sales','2026-08-22 18:12:37','2026-08-22 18:12:37','Mrs.','Kyla','Magnayi','Female','Customer Service',NULL,NULL,'09175217241','business',NULL,NULL),(2,2,NULL,'2026-09-04 05:06:34','2026-09-04 05:06:34',NULL,'Juan',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `client_contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_contract_rates`
--

DROP TABLE IF EXISTS `client_contract_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_contract_rates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `contract_id` bigint(20) unsigned NOT NULL,
  `origin_port_id` bigint(20) unsigned NOT NULL,
  `destination_port_id` bigint(20) unsigned NOT NULL,
  `container_id` bigint(20) unsigned NOT NULL,
  `container_class_id` bigint(20) unsigned DEFAULT NULL,
  `container_size_id` bigint(20) unsigned DEFAULT NULL,
  `container_variant_id` bigint(20) unsigned NOT NULL,
  `min_van_qty` int(10) unsigned DEFAULT NULL,
  `base_rate` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_type` enum('percentage','fixed','increase_percentage','increase_fixed') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_value` decimal(12,2) NOT NULL DEFAULT 0.00,
  `final_rate` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_contract_rates_contract_id_foreign` (`contract_id`),
  KEY `client_contract_rates_origin_port_id_foreign` (`origin_port_id`),
  KEY `client_contract_rates_destination_port_id_foreign` (`destination_port_id`),
  KEY `client_contract_rates_container_id_foreign` (`container_id`),
  KEY `client_contract_rates_container_class_id_foreign` (`container_class_id`),
  KEY `client_contract_rates_container_size_id_foreign` (`container_size_id`),
  KEY `client_contract_rates_container_variant_id_foreign` (`container_variant_id`),
  CONSTRAINT `client_contract_rates_container_class_id_foreign` FOREIGN KEY (`container_class_id`) REFERENCES `container_class` (`id`),
  CONSTRAINT `client_contract_rates_container_id_foreign` FOREIGN KEY (`container_id`) REFERENCES `containers` (`id`),
  CONSTRAINT `client_contract_rates_container_size_id_foreign` FOREIGN KEY (`container_size_id`) REFERENCES `container_size` (`id`),
  CONSTRAINT `client_contract_rates_container_variant_id_foreign` FOREIGN KEY (`container_variant_id`) REFERENCES `container_variants` (`id`),
  CONSTRAINT `client_contract_rates_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `client_contracts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `client_contract_rates_destination_port_id_foreign` FOREIGN KEY (`destination_port_id`) REFERENCES `ports` (`port_id`),
  CONSTRAINT `client_contract_rates_origin_port_id_foreign` FOREIGN KEY (`origin_port_id`) REFERENCES `ports` (`port_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_contract_rates`
--

LOCK TABLES `client_contract_rates` WRITE;
/*!40000 ALTER TABLE `client_contract_rates` DISABLE KEYS */;
INSERT INTO `client_contract_rates` VALUES (1,1,7,32,1,NULL,1,1,NULL,10000.00,NULL,0.00,10000.00,'2026-08-22 18:20:46','2026-08-22 18:20:46'),(2,1,7,32,3,NULL,5,9,NULL,8000.00,'percentage',1.00,7920.00,'2026-08-22 18:20:46','2026-08-22 18:20:46'),(3,1,7,32,2,NULL,3,7,NULL,15000.00,'fixed',100.00,14900.00,'2026-08-22 18:20:46','2026-08-22 18:20:46'),(4,1,7,32,4,4,NULL,12,NULL,150.00,NULL,0.00,150.00,'2026-08-22 18:20:46','2026-08-22 18:20:46'),(5,1,7,32,5,5,NULL,13,NULL,110.00,NULL,0.00,110.00,'2026-08-22 18:20:46','2026-08-22 18:20:46');
/*!40000 ALTER TABLE `client_contract_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_contracts`
--

DROP TABLE IF EXISTS `client_contracts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_contracts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `client_proposal_id` bigint(20) unsigned DEFAULT NULL,
  `signed_date` date DEFAULT NULL,
  `valid_from` date NOT NULL,
  `valid_to` date NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT 2,
  `signed_document_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `terminated_reason` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `terminated_by` bigint(20) unsigned DEFAULT NULL,
  `terminated_at` timestamp NULL DEFAULT NULL,
  `decided_by` bigint(20) unsigned DEFAULT NULL,
  `decided_at` timestamp NULL DEFAULT NULL,
  `decision_remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `client_contracts_uuid_unique` (`uuid`),
  UNIQUE KEY `client_contracts_code_unique` (`code`),
  KEY `client_contracts_client_id_foreign` (`client_id`),
  KEY `client_contracts_client_proposal_id_foreign` (`client_proposal_id`),
  KEY `client_contracts_created_by_foreign` (`created_by`),
  KEY `client_contracts_terminated_by_foreign` (`terminated_by`),
  KEY `client_contracts_decided_by_foreign` (`decided_by`),
  CONSTRAINT `client_contracts_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `client_masters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `client_contracts_client_proposal_id_foreign` FOREIGN KEY (`client_proposal_id`) REFERENCES `client_proposals` (`id`) ON DELETE SET NULL,
  CONSTRAINT `client_contracts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `client_contracts_decided_by_foreign` FOREIGN KEY (`decided_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `client_contracts_terminated_by_foreign` FOREIGN KEY (`terminated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_contracts`
--

LOCK TABLES `client_contracts` WRITE;
/*!40000 ALTER TABLE `client_contracts` DISABLE KEYS */;
INSERT INTO `client_contracts` VALUES (1,'26b9b425-31ec-42bf-9a15-cbe51a3a4fe1','CCT-202608-0001',1,2,'2026-08-22','2026-08-22','2026-12-31',2,NULL,NULL,NULL,NULL,5,'2026-08-22 18:24:05',NULL,5,'2026-08-22 18:20:46','2026-08-22 18:24:05');
/*!40000 ALTER TABLE `client_contracts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_finance`
--

DROP TABLE IF EXISTS `client_finance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_finance` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned NOT NULL,
  `client_business_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credit_terms` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tin_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tin_registered_address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registered_tax_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `withholding_tax_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trade_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tin_registration_date` date DEFAULT NULL,
  `line_of_business` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_percent` decimal(5,2) DEFAULT NULL,
  `withholding_tax_percent` decimal(5,2) DEFAULT NULL,
  `mode_of_payment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cro` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `client_finance_client_id_unique` (`client_id`),
  CONSTRAINT `client_finance_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `client_masters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_finance`
--

LOCK TABLES `client_finance` WRITE;
/*!40000 ALTER TABLE `client_finance` DISABLE KEYS */;
INSERT INTO `client_finance` VALUES (1,1,'Partners Too',NULL,'2026-08-22 18:14:42','2026-08-22 18:14:42','1001748593215000',NULL,'VAT Inclusive',NULL,NULL,'2026-08-01',NULL,12.00,NULL,'Cash','Automatic'),(2,2,'QA Test Client Corp',NULL,'2026-09-04 05:06:45','2026-09-04 05:06:45',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `client_finance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_masters`
--

DROP TABLE IF EXISTS `client_masters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_masters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lead_id` bigint(20) unsigned DEFAULT NULL,
  `customer_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_mnemonic` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_classification` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `always_route_atw` tinyint(1) NOT NULL DEFAULT 0,
  `industry` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_rep_id` bigint(20) unsigned DEFAULT NULL,
  `account_manager_id` bigint(20) unsigned DEFAULT NULL,
  `current_stage` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `is_complete` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `client_masters_uuid_unique` (`uuid`),
  UNIQUE KEY `client_masters_customer_code_unique` (`customer_code`),
  UNIQUE KEY `client_masters_client_mnemonic_unique` (`client_mnemonic`),
  KEY `client_masters_sales_rep_id_foreign` (`sales_rep_id`),
  KEY `client_masters_created_by_foreign` (`created_by`),
  KEY `client_masters_lead_id_foreign` (`lead_id`),
  KEY `client_masters_account_manager_id_foreign` (`account_manager_id`),
  CONSTRAINT `client_masters_account_manager_id_foreign` FOREIGN KEY (`account_manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `client_masters_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `client_masters_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `crm_leads` (`id`) ON DELETE SET NULL,
  CONSTRAINT `client_masters_sales_rep_id_foreign` FOREIGN KEY (`sales_rep_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_masters`
--

LOCK TABLES `client_masters` WRITE;
/*!40000 ALTER TABLE `client_masters` DISABLE KEYS */;
INSERT INTO `client_masters` VALUES (1,'a28f68f0-3fdd-4fc7-9df8-1f7a6c006a76',4,'CM-2026-0001','Partners Too','KaMagnayi',NULL,NULL,0,NULL,5,5,4,1,5,'2026-08-22 18:10:45','2026-08-22 18:14:44'),(2,'a2a9b909-ead5-4bff-9839-4b119d7bb1e9',NULL,'CM-2026-0002','QA Test Client Corp','TESTQA',NULL,NULL,0,NULL,3,4,4,1,3,'2026-09-04 05:06:16','2026-09-04 05:06:54');
/*!40000 ALTER TABLE `client_masters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_proposal_rates`
--

DROP TABLE IF EXISTS `client_proposal_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_proposal_rates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_id` bigint(20) unsigned NOT NULL,
  `origin_port_id` bigint(20) unsigned NOT NULL,
  `destination_port_id` bigint(20) unsigned NOT NULL,
  `container_id` bigint(20) unsigned NOT NULL,
  `container_class_id` bigint(20) unsigned DEFAULT NULL,
  `container_size_id` bigint(20) unsigned DEFAULT NULL,
  `container_variant_id` bigint(20) unsigned NOT NULL,
  `min_van_qty` int(10) unsigned DEFAULT NULL,
  `base_rate` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_type` enum('percentage','fixed','increase_percentage','increase_fixed') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_value` decimal(12,2) NOT NULL DEFAULT 0.00,
  `final_rate` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_proposal_rates_proposal_id_foreign` (`proposal_id`),
  KEY `client_proposal_rates_origin_port_id_foreign` (`origin_port_id`),
  KEY `client_proposal_rates_destination_port_id_foreign` (`destination_port_id`),
  KEY `client_proposal_rates_container_id_foreign` (`container_id`),
  KEY `client_proposal_rates_container_class_id_foreign` (`container_class_id`),
  KEY `client_proposal_rates_container_size_id_foreign` (`container_size_id`),
  KEY `client_proposal_rates_container_variant_id_foreign` (`container_variant_id`),
  CONSTRAINT `client_proposal_rates_container_class_id_foreign` FOREIGN KEY (`container_class_id`) REFERENCES `container_class` (`id`),
  CONSTRAINT `client_proposal_rates_container_id_foreign` FOREIGN KEY (`container_id`) REFERENCES `containers` (`id`),
  CONSTRAINT `client_proposal_rates_container_size_id_foreign` FOREIGN KEY (`container_size_id`) REFERENCES `container_size` (`id`),
  CONSTRAINT `client_proposal_rates_container_variant_id_foreign` FOREIGN KEY (`container_variant_id`) REFERENCES `container_variants` (`id`),
  CONSTRAINT `client_proposal_rates_destination_port_id_foreign` FOREIGN KEY (`destination_port_id`) REFERENCES `ports` (`port_id`),
  CONSTRAINT `client_proposal_rates_origin_port_id_foreign` FOREIGN KEY (`origin_port_id`) REFERENCES `ports` (`port_id`),
  CONSTRAINT `client_proposal_rates_proposal_id_foreign` FOREIGN KEY (`proposal_id`) REFERENCES `client_proposals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_proposal_rates`
--

LOCK TABLES `client_proposal_rates` WRITE;
/*!40000 ALTER TABLE `client_proposal_rates` DISABLE KEYS */;
INSERT INTO `client_proposal_rates` VALUES (1,1,7,19,1,NULL,1,1,NULL,15000.00,'fixed',1000.00,14000.00,'2026-08-21 00:49:51','2026-08-21 00:49:51'),(2,2,7,32,1,NULL,1,1,NULL,10000.00,NULL,0.00,10000.00,'2026-08-22 18:06:57','2026-08-22 18:06:57'),(3,2,7,32,3,NULL,5,9,NULL,8000.00,'percentage',1.00,7920.00,'2026-08-22 18:06:57','2026-08-22 18:06:57'),(4,2,7,32,2,NULL,3,7,NULL,15000.00,'fixed',100.00,14900.00,'2026-08-22 18:06:57','2026-08-22 18:06:57'),(5,2,7,32,4,4,NULL,12,NULL,150.00,NULL,0.00,150.00,'2026-08-22 18:06:57','2026-08-22 18:06:57'),(6,2,7,32,5,5,NULL,13,NULL,110.00,NULL,0.00,110.00,'2026-08-22 18:06:57','2026-08-22 18:06:57'),(7,3,1,2,1,NULL,1,1,NULL,0.00,NULL,0.00,0.00,'2026-09-02 17:40:44','2026-09-02 17:40:44'),(8,3,1,2,1,NULL,1,1,NULL,0.00,NULL,0.00,0.00,'2026-09-02 17:40:44','2026-09-02 17:40:44'),(9,3,1,2,1,NULL,1,1,NULL,0.00,NULL,0.00,0.00,'2026-09-02 17:40:44','2026-09-02 17:40:44'),(10,3,1,2,1,NULL,1,1,NULL,0.00,NULL,0.00,0.00,'2026-09-02 17:40:44','2026-09-02 17:40:44'),(11,3,1,2,1,NULL,1,1,NULL,0.00,NULL,0.00,0.00,'2026-09-02 17:40:44','2026-09-02 17:40:44'),(12,3,1,2,1,NULL,1,1,NULL,0.00,NULL,0.00,0.00,'2026-09-02 17:40:44','2026-09-02 17:40:44');
/*!40000 ALTER TABLE `client_proposal_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_proposals`
--

DROP TABLE IF EXISTS `client_proposals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_proposals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` bigint(20) unsigned DEFAULT NULL,
  `lead_id` bigint(20) unsigned DEFAULT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `signed_document_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signed_at` timestamp NULL DEFAULT NULL,
  `decided_by` bigint(20) unsigned DEFAULT NULL,
  `decided_at` timestamp NULL DEFAULT NULL,
  `decision_remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `client_proposals_uuid_unique` (`uuid`),
  UNIQUE KEY `client_proposals_code_unique` (`code`),
  KEY `client_proposals_client_id_foreign` (`client_id`),
  KEY `client_proposals_created_by_foreign` (`created_by`),
  KEY `client_proposals_decided_by_foreign` (`decided_by`),
  KEY `client_proposals_lead_id_foreign` (`lead_id`),
  CONSTRAINT `client_proposals_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `client_masters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `client_proposals_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `client_proposals_decided_by_foreign` FOREIGN KEY (`decided_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `client_proposals_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `crm_leads` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_proposals`
--

LOCK TABLES `client_proposals` WRITE;
/*!40000 ALTER TABLE `client_proposals` DISABLE KEYS */;
INSERT INTO `client_proposals` VALUES (1,'f7221e48-f69d-4b6f-8088-cc69e32c3290','CPR-202608-0001',NULL,3,2,NULL,NULL,4,'2026-08-22 06:29:11',NULL,5,'2026-08-21 00:49:51','2026-08-22 06:29:11'),(2,'512fd92a-f5ff-4bcb-b477-4ad983d5be1d','CPR-202608-0002',1,4,4,'[\"https:\\/\\/kargamine.synxcel.com\\/uploads\\/doc\\/pdf\\/cd15d39f1385fdcbe9cf6390075777af772305300b179f527aec6e3d565b34e6.pdf\"]','2026-08-22 18:09:40',5,'2026-08-22 18:07:42',NULL,5,'2026-08-22 18:06:57','2026-08-22 18:10:46'),(3,'22c2a08c-5d58-4c6b-a56d-a1ef210d7463','CPR-202609-0001',NULL,1,2,NULL,NULL,1,'2026-09-04 04:27:43',NULL,3,'2026-09-02 17:40:44','2026-09-04 04:27:43');
/*!40000 ALTER TABLE `client_proposals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_trade_references`
--

DROP TABLE IF EXISTS `client_trade_references`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_trade_references` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned NOT NULL,
  `business_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `relationship` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person_mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_trade_references_client_id_foreign` (`client_id`),
  CONSTRAINT `client_trade_references_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `client_masters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_trade_references`
--

LOCK TABLES `client_trade_references` WRITE;
/*!40000 ALTER TABLE `client_trade_references` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_trade_references` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_finance`
--

DROP TABLE IF EXISTS `company_finance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_finance` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned NOT NULL,
  `credit_terms` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_mode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_handling` tinyint(1) NOT NULL DEFAULT 0,
  `billing_summary_report` tinyint(1) NOT NULL DEFAULT 0,
  `other_requests` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `company_finance_company_id_foreign` (`company_id`),
  CONSTRAINT `company_finance_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `company_info_master` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_finance`
--

LOCK TABLES `company_finance` WRITE;
/*!40000 ALTER TABLE `company_finance` DISABLE KEYS */;
/*!40000 ALTER TABLE `company_finance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_info_master`
--

DROP TABLE IF EXISTS `company_info_master`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_info_master` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customer_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `registered_address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_number_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_number_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `industry` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_identification_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_start_date` date DEFAULT NULL,
  `number_of_employees` int(11) DEFAULT NULL,
  `synkar` tinyint(1) NOT NULL DEFAULT 0,
  `estimated_annual_revenue` decimal(15,2) DEFAULT NULL,
  `estimated_annual_net_income` decimal(15,2) DEFAULT NULL,
  `company_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_type` enum('shipper','consignee','both') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company_info_master_customer_code_unique` (`customer_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_info_master`
--

LOCK TABLES `company_info_master` WRITE;
/*!40000 ALTER TABLE `company_info_master` DISABLE KEYS */;
/*!40000 ALTER TABLE `company_info_master` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_info`
--

DROP TABLE IF EXISTS `contact_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_info` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned NOT NULL,
  `contact_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_info_company_id_foreign` (`company_id`),
  CONSTRAINT `contact_info_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `company_info_master` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_info`
--

LOCK TABLES `contact_info` WRITE;
/*!40000 ALTER TABLE `contact_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `container_asset_location_history`
--

DROP TABLE IF EXISTS `container_asset_location_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `container_asset_location_history` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `container_asset_id` bigint(20) unsigned NOT NULL,
  `port_id` bigint(20) unsigned NOT NULL,
  `pier_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_at_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recorded_by` bigint(20) unsigned DEFAULT NULL,
  `recorded_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `container_asset_location_history_port_id_foreign` (`port_id`),
  KEY `container_asset_location_history_recorded_by_foreign` (`recorded_by`),
  KEY `cont_asset_loc_hist_asset_recorded_idx` (`container_asset_id`,`recorded_at`),
  CONSTRAINT `container_asset_location_history_container_asset_id_foreign` FOREIGN KEY (`container_asset_id`) REFERENCES `container_assets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `container_asset_location_history_port_id_foreign` FOREIGN KEY (`port_id`) REFERENCES `ports` (`port_id`),
  CONSTRAINT `container_asset_location_history_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `container_asset_location_history`
--

LOCK TABLES `container_asset_location_history` WRITE;
/*!40000 ALTER TABLE `container_asset_location_history` DISABLE KEYS */;
INSERT INTO `container_asset_location_history` VALUES (1,5,29,NULL,'Booked','booking_assignment',1,'2026-08-22 11:48:53','2026-08-22 11:48:53','2026-08-22 11:48:53'),(3,4,29,NULL,'Booked','booking_assignment',1,'2026-08-22 13:16:48','2026-08-22 13:16:48','2026-08-22 13:16:48'),(4,2,29,NULL,'Booked','booking_assignment',1,'2026-08-22 13:16:48','2026-08-22 13:16:48','2026-08-22 13:16:48'),(5,5,29,NULL,'Booked','booking_assignment',1,'2026-08-22 13:16:48','2026-08-22 13:16:48','2026-08-22 13:16:48'),(6,53,29,NULL,'Booked','booking_assignment',1,'2026-08-22 15:06:15','2026-08-22 15:06:15','2026-08-22 15:06:15'),(7,65,7,NULL,'Booked','booking_assignment',1,'2026-08-22 16:57:52','2026-08-22 16:57:52','2026-08-22 16:57:52'),(8,65,7,NULL,'Available','booking_release',1,'2026-08-22 16:58:08','2026-08-22 16:58:08','2026-08-22 16:58:08'),(9,77,29,NULL,'Available','manual_relocation',3,'2026-09-04 18:05:33','2026-09-04 18:05:33','2026-09-04 18:05:33'),(10,77,29,NULL,'Damaged','manual_relocation',3,'2026-09-04 18:06:03','2026-09-04 18:06:03','2026-09-04 18:06:03'),(11,51,27,NULL,'Out of Service','manual_relocation',3,'2026-09-04 18:13:15','2026-09-04 18:13:15','2026-09-04 18:13:15'),(12,5,29,NULL,'Booked','booking_assignment',1,'2026-09-06 18:07:42','2026-09-06 18:07:42','2026-09-06 18:07:42'),(13,5,29,NULL,'Available','booking_release',1,'2026-09-07 04:41:49','2026-09-07 04:41:49','2026-09-07 04:41:49'),(14,2,18,NULL,'Booked','booking_assignment',6,'2026-09-07 04:58:48','2026-09-07 04:58:48','2026-09-07 04:58:48'),(15,2,18,NULL,'Available','booking_release',NULL,'2026-09-07 04:59:44','2026-09-07 04:59:44','2026-09-07 04:59:44'),(16,2,18,NULL,'Booked','booking_assignment',1,'2026-09-07 05:17:54','2026-09-07 05:17:54','2026-09-07 05:17:54'),(17,2,18,NULL,'Available','booking_release',1,'2026-09-07 05:19:10','2026-09-07 05:19:10','2026-09-07 05:19:10'),(18,2,18,NULL,'Booked','booking_assignment',1,'2026-09-07 05:40:55','2026-09-07 05:40:55','2026-09-07 05:40:55'),(19,2,18,NULL,'Available','booking_release',1,'2026-09-07 05:41:21','2026-09-07 05:41:21','2026-09-07 05:41:21'),(20,2,18,NULL,'Booked','booking_assignment',1,'2026-09-07 06:14:56','2026-09-07 06:14:56','2026-09-07 06:14:56'),(21,2,18,NULL,'Available','booking_release',1,'2026-09-07 06:15:06','2026-09-07 06:15:06','2026-09-07 06:15:06'),(22,2,18,NULL,'Booked','booking_assignment',1,'2026-09-07 07:00:23','2026-09-07 07:00:23','2026-09-07 07:00:23'),(24,2,18,NULL,'Available','booking_release',1,'2026-09-07 07:02:04','2026-09-07 07:02:04','2026-09-07 07:02:04');
/*!40000 ALTER TABLE `container_asset_location_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `container_assets`
--

DROP TABLE IF EXISTS `container_assets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `container_assets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `container_variant_id` bigint(20) unsigned NOT NULL,
  `container_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `current_port_id` bigint(20) unsigned DEFAULT NULL,
  `current_pier_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_movement_at` timestamp NULL DEFAULT NULL,
  `condition_notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `container_assets_container_no_unique` (`container_no`),
  KEY `container_assets_container_variant_id_foreign` (`container_variant_id`),
  KEY `container_assets_current_port_id_foreign` (`current_port_id`),
  CONSTRAINT `container_assets_container_variant_id_foreign` FOREIGN KEY (`container_variant_id`) REFERENCES `container_variants` (`id`),
  CONSTRAINT `container_assets_current_port_id_foreign` FOREIGN KEY (`current_port_id`) REFERENCES `ports` (`port_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `container_assets`
--

LOCK TABLES `container_assets` WRITE;
/*!40000 ALTER TABLE `container_assets` DISABLE KEYS */;
INSERT INTO `container_assets` VALUES (1,1,'MAEU0000001',3,12,NULL,'2026-08-12 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(2,1,'CMAU0000002',1,18,NULL,'2026-09-07 07:02:04',NULL,'2026-08-13 05:17:40','2026-09-07 07:02:04'),(3,1,'HLXU0000003',3,19,NULL,'2026-08-10 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(4,1,'ONEU0000004',1,20,NULL,'2026-08-22 13:16:48',NULL,'2026-08-13 05:17:40','2026-08-22 13:18:49'),(5,1,'EGHU0000005',1,29,NULL,'2026-09-07 04:41:49',NULL,'2026-08-13 05:17:40','2026-09-07 04:41:49'),(6,1,'TCLU0000006',4,3,NULL,'2026-08-07 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(7,1,'OOLU0000007',5,12,NULL,'2026-08-06 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(8,1,'MSCU0000008',1,18,NULL,'2026-08-05 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-22 13:15:56'),(9,2,'MAEU0000009',3,19,NULL,'2026-08-04 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(10,2,'CMAU0000010',1,20,NULL,'2026-08-03 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(11,2,'HLXU0000011',2,29,NULL,'2026-08-02 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(12,2,'ONEU0000012',1,3,NULL,'2026-08-01 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(13,2,'EGHU0000013',2,12,NULL,'2026-07-31 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(14,2,'TCLU0000014',1,18,NULL,'2026-07-30 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(15,2,'OOLU0000015',1,19,NULL,'2026-07-29 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(16,2,'MSCU0000016',1,20,NULL,'2026-07-28 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(49,7,'MAEU0000049',1,12,NULL,'2026-07-25 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(50,7,'CMAU0000050',1,18,NULL,'2026-07-24 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(51,7,'HLXU0000051',6,27,NULL,'2026-09-04 18:13:15',NULL,'2026-08-13 05:17:40','2026-09-04 18:13:15'),(52,7,'ONEU0000052',2,20,NULL,'2026-07-22 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(53,7,'EGHU0000053',1,29,NULL,'2026-08-22 15:06:15',NULL,'2026-08-13 05:17:40','2026-08-22 15:06:32'),(54,7,'TCLU0000054',6,3,NULL,'2026-07-20 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(55,7,'OOLU0000055',5,12,NULL,'2026-07-19 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(56,7,'MSCU0000056',1,18,NULL,'2026-07-18 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(57,8,'MAEU0000057',2,19,NULL,'2026-07-17 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(58,8,'CMAU0000058',1,20,NULL,'2026-07-16 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(59,8,'HLXU0000059',1,29,NULL,'2026-07-15 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(60,8,'ONEU0000060',1,3,NULL,'2026-08-13 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(61,8,'EGHU0000061',1,12,NULL,'2026-08-12 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(62,8,'TCLU0000062',1,18,NULL,'2026-08-11 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(63,8,'OOLU0000063',1,19,NULL,'2026-08-10 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(64,8,'MSCU0000064',1,20,NULL,'2026-08-09 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(65,9,'MAEU0000065',1,29,NULL,'2026-08-22 16:58:08',NULL,'2026-08-13 05:17:40','2026-08-22 16:58:29'),(66,9,'CMAU0000066',1,3,NULL,'2026-08-07 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(67,9,'HLXU0000067',1,12,NULL,'2026-08-06 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(68,9,'ONEU0000068',1,18,NULL,'2026-08-05 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(69,9,'EGHU0000069',2,19,NULL,'2026-08-04 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(70,9,'TCLU0000070',1,20,NULL,'2026-08-03 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(71,9,'OOLU0000071',2,29,NULL,'2026-08-02 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(72,9,'MSCU0000072',1,3,NULL,'2026-08-01 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(73,10,'MAEU0000073',3,12,NULL,'2026-07-31 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(74,10,'CMAU0000074',3,18,NULL,'2026-07-30 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(75,10,'HLXU0000075',1,19,NULL,'2026-07-29 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(76,10,'ONEU0000076',1,20,NULL,'2026-07-28 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(77,10,'EGHU0000077',5,29,NULL,'2026-09-04 18:06:03','Dented left side panel found during yard inspection.','2026-08-13 05:17:40','2026-09-04 18:06:03'),(78,10,'TCLU0000078',1,3,NULL,'2026-07-26 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(79,10,'OOLU0000079',1,12,NULL,'2026-07-25 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40'),(80,10,'MSCU0000080',2,18,NULL,'2026-07-24 05:17:40',NULL,'2026-08-13 05:17:40','2026-08-13 05:17:40');
/*!40000 ALTER TABLE `container_assets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `container_class`
--

DROP TABLE IF EXISTS `container_class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `container_class` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `container_id` bigint(20) unsigned NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `container_class_container_id_class_unique` (`container_id`,`class`),
  CONSTRAINT `container_class_container_id_foreign` FOREIGN KEY (`container_id`) REFERENCES `containers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `container_class`
--

LOCK TABLES `container_class` WRITE;
/*!40000 ALTER TABLE `container_class` DISABLE KEYS */;
INSERT INTO `container_class` VALUES (3,4,'MT','2026-08-13 06:12:55','2026-08-13 06:12:55'),(4,4,'CBM','2026-08-13 06:12:55','2026-08-13 06:12:55'),(5,5,'MT','2026-08-13 06:12:55','2026-08-13 06:12:55'),(6,5,'CBM','2026-08-13 06:12:55','2026-08-13 06:12:55'),(8,1,'a','2026-08-19 19:22:07','2026-08-19 19:22:07'),(9,1,'b','2026-08-19 19:22:07','2026-08-19 19:22:07'),(10,1,'c','2026-08-19 19:22:07','2026-08-19 19:22:07'),(11,1,'d','2026-08-19 19:22:07','2026-08-19 19:22:07'),(12,2,'A','2026-08-22 15:11:46','2026-08-22 15:11:46'),(13,2,'V','2026-08-22 15:11:46','2026-08-22 15:11:46');
/*!40000 ALTER TABLE `container_class` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `container_size`
--

DROP TABLE IF EXISTS `container_size`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `container_size` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `container_id` bigint(20) unsigned NOT NULL,
  `size` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `container_size_container_id_size_unique` (`container_id`,`size`),
  CONSTRAINT `container_size_container_id_foreign` FOREIGN KEY (`container_id`) REFERENCES `containers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `container_size`
--

LOCK TABLES `container_size` WRITE;
/*!40000 ALTER TABLE `container_size` DISABLE KEYS */;
INSERT INTO `container_size` VALUES (1,1,'20-GP','2026-08-12 19:49:56','2026-08-15 08:09:39'),(2,1,'40FT','2026-08-12 19:49:56','2026-08-12 19:49:56'),(3,2,'20FT','2026-08-12 19:49:56','2026-08-12 19:49:56'),(4,2,'40FT','2026-08-12 19:49:56','2026-08-12 19:49:56'),(5,3,'20FT','2026-08-12 19:49:56','2026-08-12 19:49:56'),(6,3,'40FT','2026-08-12 19:49:56','2026-08-12 19:49:56');
/*!40000 ALTER TABLE `container_size` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `container_type`
--

DROP TABLE IF EXISTS `container_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `container_type` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `container_type`
--

LOCK TABLES `container_type` WRITE;
/*!40000 ALTER TABLE `container_type` DISABLE KEYS */;
INSERT INTO `container_type` VALUES (1,'CONVAN',NULL,'2026-08-12 19:49:55'),(2,'FLATRACK (PLATFORM)',NULL,'2026-08-12 19:49:55'),(3,'REEFER',NULL,'2026-08-12 19:49:55'),(4,'HIGH CUBE',NULL,'2026-08-12 19:49:55'),(5,'CATTLE VAN',NULL,'2026-08-12 19:49:55'),(6,'TANK (ISO TANK)',NULL,'2026-08-12 19:49:55'),(7,'ROLLING CARGO',NULL,'2026-08-12 19:49:55'),(8,'SPECIAL CONTAINERS',NULL,'2026-08-12 19:49:55'),(9,'OPEN-TOP VAN',NULL,'2026-08-12 19:49:55');
/*!40000 ALTER TABLE `container_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `container_variants`
--

DROP TABLE IF EXISTS `container_variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `container_variants` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `container_id` bigint(20) unsigned NOT NULL,
  `container_class_id` bigint(20) unsigned DEFAULT NULL,
  `container_size_id` bigint(20) unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `container_variants_unique` (`container_id`,`container_class_id`,`container_size_id`),
  KEY `container_variants_container_class_id_foreign` (`container_class_id`),
  KEY `container_variants_container_size_id_foreign` (`container_size_id`),
  CONSTRAINT `container_variants_container_class_id_foreign` FOREIGN KEY (`container_class_id`) REFERENCES `container_class` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `container_variants_container_id_foreign` FOREIGN KEY (`container_id`) REFERENCES `containers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `container_variants_container_size_id_foreign` FOREIGN KEY (`container_size_id`) REFERENCES `container_size` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `container_variants`
--

LOCK TABLES `container_variants` WRITE;
/*!40000 ALTER TABLE `container_variants` DISABLE KEYS */;
INSERT INTO `container_variants` VALUES (1,1,NULL,1,1,'2026-08-12 19:49:56','2026-08-12 19:49:56'),(2,1,NULL,2,1,'2026-08-12 19:49:56','2026-08-12 19:49:56'),(7,2,NULL,3,1,'2026-08-12 19:49:56','2026-08-12 19:49:56'),(8,2,NULL,4,1,'2026-08-12 19:49:56','2026-08-12 19:49:56'),(9,3,NULL,5,1,'2026-08-12 19:49:56','2026-08-12 19:49:56'),(10,3,NULL,6,1,'2026-08-12 19:49:56','2026-08-12 19:49:56'),(11,4,3,NULL,1,'2026-08-13 06:12:55','2026-08-13 06:12:55'),(12,4,4,NULL,1,'2026-08-13 06:12:55','2026-08-13 06:12:55'),(13,5,5,NULL,1,'2026-08-13 06:12:55','2026-08-13 06:12:55'),(14,5,6,NULL,1,'2026-08-13 06:12:55','2026-08-13 06:12:55'),(17,1,8,1,1,'2026-08-19 19:22:07','2026-08-19 19:22:07'),(18,1,9,1,1,'2026-08-19 19:22:07','2026-08-19 19:22:07'),(19,1,10,1,1,'2026-08-19 19:22:07','2026-08-19 19:22:07'),(20,1,11,1,1,'2026-08-19 19:22:07','2026-08-19 19:22:07'),(21,1,8,2,1,'2026-08-19 19:22:07','2026-08-19 19:22:07'),(22,1,9,2,1,'2026-08-19 19:22:07','2026-08-19 19:22:07'),(23,1,10,2,1,'2026-08-19 19:22:07','2026-08-19 19:22:07'),(24,1,11,2,1,'2026-08-19 19:22:07','2026-08-19 19:22:07'),(25,2,12,3,1,'2026-08-22 15:11:46','2026-08-22 15:11:46'),(26,2,13,3,1,'2026-08-22 15:11:46','2026-08-22 15:11:46'),(27,2,12,4,1,'2026-08-22 15:11:46','2026-08-22 15:11:46'),(28,2,13,4,1,'2026-08-22 15:11:46','2026-08-22 15:11:46');
/*!40000 ALTER TABLE `container_variants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `containers`
--

DROP TABLE IF EXISTS `containers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `containers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `containers_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `containers`
--

LOCK TABLES `containers` WRITE;
/*!40000 ALTER TABLE `containers` DISABLE KEYS */;
INSERT INTO `containers` VALUES (1,'CV','Container Van',1,'2026-08-12 19:49:56','2026-08-12 19:49:56'),(2,'RF','Reefer Van',1,'2026-08-12 19:49:56','2026-08-12 19:49:56'),(3,'FR','Flat Rack',1,'2026-08-12 19:49:56','2026-08-12 19:49:56'),(4,'LC','Loose Cargo',1,'2026-08-13 06:12:55','2026-08-13 06:12:55'),(5,'RC','Rolling Cargo',1,'2026-08-13 06:12:55','2026-08-13 06:12:55');
/*!40000 ALTER TABLE `containers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contract_rates`
--

DROP TABLE IF EXISTS `contract_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contract_rates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `contract_id` bigint(20) unsigned NOT NULL,
  `route_from` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `route_to` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_van_qty` int(11) NOT NULL,
  `container_class` int(11) NOT NULL,
  `container_type` int(11) NOT NULL,
  `container_size` int(11) NOT NULL,
  `origin_service_type` int(11) NOT NULL,
  `destination_service_type` int(11) NOT NULL,
  `discount_type` enum('PERCENTAGE','FIXED') COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_value` decimal(12,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contract_rates_unique_line` (`contract_id`,`route_from`,`route_to`,`container_class`,`container_type`,`container_size`,`origin_service_type`,`destination_service_type`),
  KEY `contract_rates_route_from_route_to_index` (`route_from`,`route_to`),
  CONSTRAINT `contract_rates_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contract_rates`
--

LOCK TABLES `contract_rates` WRITE;
/*!40000 ALTER TABLE `contract_rates` DISABLE KEYS */;
/*!40000 ALTER TABLE `contract_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contracts`
--

DROP TABLE IF EXISTS `contracts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contracts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `proposal_id` bigint(20) unsigned NOT NULL,
  `lead_id` bigint(20) unsigned NOT NULL,
  `signed_date` date DEFAULT NULL,
  `valid_from` date NOT NULL,
  `valid_to` date NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `signed_document_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contracts_uuid_unique` (`uuid`),
  UNIQUE KEY `contracts_code_unique` (`code`),
  KEY `contracts_proposal_id_foreign` (`proposal_id`),
  KEY `contracts_created_by_foreign` (`created_by`),
  KEY `contracts_lead_id_status_index` (`lead_id`,`status`),
  KEY `contracts_valid_from_valid_to_index` (`valid_from`,`valid_to`),
  CONSTRAINT `contracts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `contracts_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `crm_leads` (`id`),
  CONSTRAINT `contracts_proposal_id_foreign` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contracts`
--

LOCK TABLES `contracts` WRITE;
/*!40000 ALTER TABLE `contracts` DISABLE KEYS */;
/*!40000 ALTER TABLE `contracts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `courier_invoice`
--

DROP TABLE IF EXISTS `courier_invoice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `courier_invoice` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned NOT NULL,
  `invoice_contact` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_contact_number` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_courier_address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `courier_invoice_company_id_foreign` (`company_id`),
  CONSTRAINT `courier_invoice_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `company_info_master` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courier_invoice`
--

LOCK TABLES `courier_invoice` WRITE;
/*!40000 ALTER TABLE `courier_invoice` DISABLE KEYS */;
/*!40000 ALTER TABLE `courier_invoice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crm_activities`
--

DROP TABLE IF EXISTS `crm_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crm_activities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` bigint(20) unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `crm_activities_lead_id_foreign` (`lead_id`),
  KEY `crm_activities_created_by_foreign` (`created_by`),
  CONSTRAINT `crm_activities_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `crm_activities_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `crm_leads` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_activities`
--

LOCK TABLES `crm_activities` WRITE;
/*!40000 ALTER TABLE `crm_activities` DISABLE KEYS */;
INSERT INTO `crm_activities` VALUES (1,5,'Call','Called client about Q4 volume.',NULL,3,'2026-09-02 12:43:51','2026-09-02 12:43:51'),(2,5,'Status Change','Status changed to OPPORTUNITY',NULL,3,'2026-09-02 18:58:33','2026-09-02 18:58:33'),(3,5,'Call','test',NULL,3,'2026-09-02 18:59:04','2026-09-02 18:59:04'),(4,4,'Call','call',NULL,1,'2026-09-04 00:16:38','2026-09-04 00:16:38'),(5,7,'Email','sent email for introduction to the products',NULL,1,'2026-09-07 23:54:15','2026-09-07 23:54:15');
/*!40000 ALTER TABLE `crm_activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crm_company_info`
--

DROP TABLE IF EXISTS `crm_company_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crm_company_info` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` bigint(20) unsigned NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_of_business` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `industry_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authorized_signatory_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authorized_signatory_first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authorized_signatory_middle_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authorized_signatory_last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authorized_signatory_gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authorized_signatory_position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authorized_signatory_mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authorized_signatory_mobile_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authorized_signatory_landline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authorized_signatory_landline_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authorized_signatory_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authorized_signatory_email_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `crm_company_info_lead_id_foreign` (`lead_id`),
  CONSTRAINT `crm_company_info_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `crm_leads` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_company_info`
--

LOCK TABLES `crm_company_info` WRITE;
/*!40000 ALTER TABLE `crm_company_info` DISABLE KEYS */;
INSERT INTO `crm_company_info` VALUES (1,1,'test','Distributor',NULL,'Mr.','test',NULL,'testt',NULL,'tesdt',NULL,NULL,NULL,NULL,'test@email.com','personal','2026-08-19 19:16:46','2026-08-19 19:28:13'),(2,2,'test',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-20 13:45:26','2026-08-20 13:45:26'),(3,3,'ABC Company','Trading','construction materials','Mrs.','Laarni',NULL,'Mayayo','Female','General Manager',NULL,NULL,NULL,NULL,'laarni.mayayo@gmail.com','business','2026-08-20 22:15:40','2026-08-20 22:15:40'),(4,4,'Partners Too','Others','Parlor','Mr.','Toni',NULL,'Aguois','Male','Manager',NULL,NULL,NULL,NULL,'partners@too.gmail.com','business','2026-08-22 17:55:50','2026-08-22 17:55:50'),(5,5,'test',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-22 05:49:06','2026-08-22 05:49:06'),(6,6,'test','Exporter','test','Mr.','test',NULL,'test',NULL,'test',NULL,NULL,NULL,NULL,'test@email.com','personal','2026-08-22 08:36:27','2026-08-22 08:36:27'),(7,7,'Golden Horizon Logistics','Distributor',NULL,'Mr.','Ramon',NULL,'dela Cruz',NULL,'Logistics Manager','0917 123 4567','personal',NULL,NULL,'ramon@goldenhorizon.ph','personal','2026-09-02 20:10:47','2026-09-07 23:53:19'),(8,8,'Golden Horizon Logistics','Distributor',NULL,NULL,'Ramon',NULL,'dela Cruz',NULL,NULL,'0917 123 4567',NULL,NULL,NULL,'ramon@goldenhorizon.ph',NULL,'2026-09-02 20:13:58','2026-09-02 20:13:58');
/*!40000 ALTER TABLE `crm_company_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crm_lead_addresses`
--

DROP TABLE IF EXISTS `crm_lead_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crm_lead_addresses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` bigint(20) unsigned NOT NULL,
  `address_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `address_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_building` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_street` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_barangay` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_town_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_province` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Philippines',
  `address_postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `crm_lead_addresses_lead_id_foreign` (`lead_id`),
  CONSTRAINT `crm_lead_addresses_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `crm_leads` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_lead_addresses`
--

LOCK TABLES `crm_lead_addresses` WRITE;
/*!40000 ALTER TABLE `crm_lead_addresses` DISABLE KEYS */;
INSERT INTO `crm_lead_addresses` VALUES (2,1,'Branch',1,NULL,NULL,NULL,NULL,'Bangued','Abra','Philippines',NULL,'2026-08-19 19:28:13','2026-08-19 19:28:13'),(3,2,'Office',1,'test',NULL,NULL,'Agtangao','Bangued','Abra','Philippines','test','2026-08-20 13:45:26','2026-08-20 13:45:26'),(4,3,'Office',1,NULL,NULL,NULL,'Banaybanay','City of Lipa','Batangas','Philippines',NULL,'2026-08-20 22:15:40','2026-08-20 22:15:40'),(6,4,'Branch',1,NULL,NULL,NULL,'Manghinao Uno','Bauan','Batangas','Philippines',NULL,'2026-08-22 17:56:52','2026-08-22 17:56:52'),(7,5,'Branch',1,NULL,NULL,NULL,NULL,'Bangued','Abra','Philippines',NULL,'2026-08-22 05:49:06','2026-08-22 05:49:06'),(8,6,'Branch',1,NULL,NULL,NULL,NULL,'Boliney','Abra','Philippines',NULL,'2026-08-22 08:36:27','2026-08-22 08:36:27'),(10,8,'Branch',1,NULL,NULL,NULL,NULL,'Bangued','Abra','Philippines',NULL,'2026-09-02 20:13:58','2026-09-02 20:13:58'),(13,7,'Branch',1,NULL,NULL,NULL,NULL,'Bangued','Abra','Philippines',NULL,'2026-09-07 23:53:19','2026-09-07 23:53:19');
/*!40000 ALTER TABLE `crm_lead_addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crm_lead_containers`
--

DROP TABLE IF EXISTS `crm_lead_containers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crm_lead_containers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` bigint(20) unsigned NOT NULL,
  `container_type` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `origin_port_id` bigint(20) unsigned DEFAULT NULL,
  `destination_port_id` bigint(20) unsigned DEFAULT NULL,
  `origin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destination` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_unit_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `declared_value_per_unit` decimal(15,2) DEFAULT NULL,
  `frequency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `general_cargo_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `convan_class` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `container_class_id` bigint(20) unsigned DEFAULT NULL,
  `convan_size` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `container_size_id` bigint(20) unsigned DEFAULT NULL,
  `minimum_temperature` decimal(5,2) DEFAULT NULL,
  `estimated_cbm` decimal(12,2) DEFAULT NULL,
  `estimated_ton` decimal(12,2) DEFAULT NULL,
  `service_mode_origin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_mode_destination` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dangerous_cargo` tinyint(1) NOT NULL DEFAULT 0,
  `dg_documentary_requirement` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `special_requirements` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `special_notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `crm_lead_containers_lead_id_foreign` (`lead_id`),
  KEY `crm_lead_containers_origin_port_id_foreign` (`origin_port_id`),
  KEY `crm_lead_containers_destination_port_id_foreign` (`destination_port_id`),
  KEY `crm_lead_containers_container_class_id_foreign` (`container_class_id`),
  KEY `crm_lead_containers_container_size_id_foreign` (`container_size_id`),
  CONSTRAINT `crm_lead_containers_container_class_id_foreign` FOREIGN KEY (`container_class_id`) REFERENCES `container_class` (`id`) ON DELETE SET NULL,
  CONSTRAINT `crm_lead_containers_container_size_id_foreign` FOREIGN KEY (`container_size_id`) REFERENCES `container_size` (`id`) ON DELETE SET NULL,
  CONSTRAINT `crm_lead_containers_destination_port_id_foreign` FOREIGN KEY (`destination_port_id`) REFERENCES `ports` (`port_id`) ON DELETE SET NULL,
  CONSTRAINT `crm_lead_containers_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `crm_leads` (`id`) ON DELETE CASCADE,
  CONSTRAINT `crm_lead_containers_origin_port_id_foreign` FOREIGN KEY (`origin_port_id`) REFERENCES `ports` (`port_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_lead_containers`
--

LOCK TABLES `crm_lead_containers` WRITE;
/*!40000 ALTER TABLE `crm_lead_containers` DISABLE KEYS */;
INSERT INTO `crm_lead_containers` VALUES (2,1,'CV',1,2,NULL,NULL,'Container Van (CV)',12,NULL,NULL,'test',NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,'DOOR','DOOR',NULL,0,NULL,NULL,NULL,'2026-08-19 19:28:14','2026-08-19 19:28:14'),(3,2,'CV',1,2,NULL,NULL,'Container Van (CV)',123,123.00,'daily','test',NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,'DOOR','DOOR',NULL,0,NULL,NULL,NULL,'2026-08-20 13:45:55','2026-08-20 13:45:55'),(4,3,'CV',7,19,NULL,NULL,'Container Van (CV)',30,500000.00,'Monthly','beverages',NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,'DOOR','DOOR',NULL,0,NULL,NULL,NULL,'2026-08-20 23:11:11','2026-08-20 23:11:11'),(6,4,'CV',7,32,NULL,NULL,'Container Van (CV)',40,500000.00,'Monthly','parlor equipments',NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,'DOOR','DOOR',NULL,0,NULL,NULL,NULL,'2026-08-22 17:56:54','2026-08-22 17:56:54'),(7,4,'FR',7,32,NULL,NULL,'Flatrack (FR)',5,NULL,'Monthly','deformed bars',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'PIER','PIER',NULL,0,NULL,NULL,NULL,'2026-08-22 18:01:36','2026-08-22 18:01:36'),(8,4,'RF',7,32,NULL,NULL,'Reefer Van (RF)',2,NULL,'Monthly','frozen meat',NULL,NULL,NULL,NULL,NULL,16.00,NULL,NULL,'DOOR','DOOR',NULL,0,NULL,NULL,NULL,'2026-08-22 18:02:12','2026-08-22 18:02:12'),(9,4,'LC',7,32,NULL,NULL,'Loose Cargo (LC)',1,NULL,NULL,'generator',NULL,NULL,NULL,NULL,NULL,NULL,130.00,10.00,'PIER','PIER',NULL,0,NULL,NULL,NULL,'2026-08-22 18:03:08','2026-08-22 18:03:08'),(10,4,'RC',7,32,NULL,NULL,'Rolling Cargo (RC)',1,NULL,NULL,'10W truck',NULL,NULL,NULL,NULL,NULL,NULL,180.00,15.00,'PIER','PIER',NULL,0,NULL,NULL,NULL,'2026-08-22 18:04:42','2026-08-22 18:04:42'),(11,5,'CV',1,2,NULL,NULL,'Container Van (CV)',123,123.00,'daily','test',NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,'DOOR','DOOR',NULL,0,NULL,NULL,NULL,'2026-08-22 05:49:37','2026-08-22 05:49:37'),(12,6,'CV',1,1,NULL,NULL,'Container Van (CV)',12,123.00,NULL,'test',NULL,NULL,8,NULL,1,NULL,NULL,NULL,'DOOR','DOOR',NULL,0,NULL,NULL,NULL,'2026-08-22 08:36:56','2026-08-22 08:36:56'),(13,5,'CV',1,1,NULL,NULL,'Container Van (CV)',5,NULL,'daily','Test cargo for verification','Documents / Parcels',NULL,NULL,NULL,1,NULL,NULL,NULL,'PIER','PIER',NULL,0,NULL,NULL,NULL,'2026-09-02 17:33:41','2026-09-02 17:33:41'),(16,7,'CV',1,3,NULL,NULL,'Container Van (CV)',12,NULL,'daily','test','Dry Bulk',NULL,NULL,NULL,1,NULL,NULL,NULL,'DOOR','DOOR',NULL,0,NULL,NULL,NULL,'2026-09-07 23:53:20','2026-09-07 23:53:20');
/*!40000 ALTER TABLE `crm_lead_containers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crm_leads`
--

DROP TABLE IF EXISTS `crm_leads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crm_leads` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `landline_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `landline_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `customer_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `middle_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_stage` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `is_complete` tinyint(1) NOT NULL DEFAULT 0,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assigned_to` bigint(20) unsigned DEFAULT NULL,
  `estimated_value` decimal(12,2) DEFAULT NULL,
  `expected_close_date` date DEFAULT NULL,
  `status_updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `crm_leads_uuid_unique` (`uuid`),
  UNIQUE KEY `crm_leads_customer_code_unique` (`customer_code`),
  KEY `crm_leads_assigned_to_foreign` (`assigned_to`),
  CONSTRAINT `crm_leads_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_leads`
--

LOCK TABLES `crm_leads` WRITE;
/*!40000 ALTER TABLE `crm_leads` DISABLE KEYS */;
INSERT INTO `crm_leads` VALUES (1,'a28ab974-b984-48b0-a6c0-4b229036b1fe',NULL,NULL,'1231 23',NULL,'personal',NULL,NULL,3,NULL,'corporate',NULL,'test',NULL,'test',NULL,2,1,'Referral',3,NULL,NULL,'2026-08-19 19:28:13','2026-08-19 19:16:46','2026-08-19 19:28:14'),(2,'a28b0414-d24c-40a3-8e63-22bdbb9ab051',NULL,NULL,'1231 23',NULL,'personal',NULL,NULL,3,NULL,'individual',NULL,'test',NULL,'test',NULL,2,1,'Referral',1,NULL,NULL,'2026-08-20 13:45:55','2026-08-20 13:45:26','2026-08-20 13:45:55'),(3,'a28bba8d-dfa1-45ca-a059-a3b034e7832d',NULL,NULL,'0916 254 8269',NULL,'business',NULL,NULL,3,NULL,'corporate','Mr.','Jeris',NULL,'De Leon','Male',2,1,'Referral',5,NULL,NULL,'2026-08-20 23:11:11','2026-08-20 22:15:40','2026-08-20 23:11:11'),(4,'a28f6399-bb23-431f-b24b-7719044b0b71',NULL,NULL,'0916 548 5896',NULL,'business',NULL,NULL,5,'CM-2026-0001','corporate','Mrs.','Kyla',NULL,'Magnayi','Female',2,1,'Website',5,NULL,NULL,'2026-08-22 18:10:46','2026-08-22 17:55:50','2026-08-22 18:10:46'),(5,'a28fa190-d3be-447c-8c3d-b8157591a988',NULL,NULL,'1231 23',NULL,'personal',NULL,NULL,3,NULL,'individual','Mrs.','test',NULL,'test',NULL,2,1,'Cold Call',3,NULL,NULL,'2026-08-22 05:49:37','2026-08-22 05:49:06','2026-09-02 17:33:41'),(6,'a28fdd6a-c3e6-4a9e-8d61-a0b3d3f85e15',NULL,NULL,'1231 23',NULL,'personal',NULL,NULL,3,NULL,'corporate',NULL,'test',NULL,'test',NULL,2,1,'Cold Call',1,NULL,NULL,'2026-08-22 08:36:56','2026-08-22 08:36:27','2026-08-22 08:36:56'),(7,'a2a6f68c-b5a6-44f3-935d-567d00294103','ramon@goldenhorizon.ph',NULL,'0917 123 4567',NULL,'personal',NULL,'Logistics Manager',3,NULL,'corporate',NULL,'Ramon',NULL,'dela Cruz',NULL,2,1,'Cold Call',3,NULL,NULL,'2026-09-07 23:53:19','2026-09-02 20:10:47','2026-09-07 23:53:20'),(8,'a2a6f7b1-d550-490f-8dc9-d5e046850277','ramon@goldenhorizon.ph',NULL,'0917 123 4567',NULL,NULL,NULL,NULL,1,NULL,'corporate',NULL,'Ramon',NULL,'dela Cruz',NULL,1,0,'Cold Call',3,NULL,NULL,'2026-09-02 20:13:58','2026-09-02 20:13:58','2026-09-02 20:13:58');
/*!40000 ALTER TABLE `crm_leads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crm_notes`
--

DROP TABLE IF EXISTS `crm_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crm_notes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` bigint(20) unsigned NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `crm_notes_lead_id_foreign` (`lead_id`),
  KEY `crm_notes_created_by_foreign` (`created_by`),
  CONSTRAINT `crm_notes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `crm_notes_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `crm_leads` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_notes`
--

LOCK TABLES `crm_notes` WRITE;
/*!40000 ALTER TABLE `crm_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `crm_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crm_status`
--

DROP TABLE IF EXISTS `crm_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crm_status` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_status`
--

LOCK TABLES `crm_status` WRITE;
/*!40000 ALTER TABLE `crm_status` DISABLE KEYS */;
INSERT INTO `crm_status` VALUES (1,'LEAD','New incoming lead','2026-08-12 13:23:27','2026-08-12 13:23:27'),(2,'QUALIFIED','Lead is qualified and potential','2026-08-12 13:23:27','2026-08-12 13:23:27'),(3,'OPPORTUNITY','Converted into sales opportunity','2026-08-12 13:23:27','2026-08-12 13:23:27'),(4,'NEGOTIATION','In negotiation stage','2026-08-12 13:23:27','2026-08-12 13:23:27'),(5,'WIN','Final stage: won or lost','2026-08-12 13:23:27','2026-08-12 13:23:27'),(6,'LOST','Final stage: won or lost','2026-08-12 13:23:27','2026-08-12 13:23:27');
/*!40000 ALTER TABLE `crm_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_type`
--

DROP TABLE IF EXISTS `customer_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer_type` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_type`
--

LOCK TABLES `customer_type` WRITE;
/*!40000 ALTER TABLE `customer_type` DISABLE KEYS */;
INSERT INTO `customer_type` VALUES (1,'SHIPPER',NULL,'2026-08-12 19:49:55'),(2,'CONSIGNEE',NULL,'2026-08-12 19:49:55'),(3,'SHIPPER-CONSIGNEE',NULL,'2026-08-12 19:49:55');
/*!40000 ALTER TABLE `customer_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `delivery_types`
--

DROP TABLE IF EXISTS `delivery_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `delivery_types` (
  `delivery_type_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `includes_origin_trucking` tinyint(1) NOT NULL DEFAULT 0,
  `includes_destination_trucking` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`delivery_type_id`),
  UNIQUE KEY `delivery_types_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `delivery_types`
--

LOCK TABLES `delivery_types` WRITE;
/*!40000 ALTER TABLE `delivery_types` DISABLE KEYS */;
INSERT INTO `delivery_types` VALUES (1,'DD','Door-Door',1,1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(2,'DP','Door-Pier',1,0,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(3,'PD','Pier-Door',0,1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(4,'PP','Pier-Pier',0,0,'2026-08-12 13:23:27','2026-08-12 13:23:27');
/*!40000 ALTER TABLE `delivery_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `e_invoice`
--

DROP TABLE IF EXISTS `e_invoice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `e_invoice` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned NOT NULL,
  `invoice_email_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_email_cc_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_email_bcc_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `e_invoice_company_id_foreign` (`company_id`),
  CONSTRAINT `e_invoice_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `company_info_master` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `e_invoice`
--

LOCK TABLES `e_invoice` WRITE;
/*!40000 ALTER TABLE `e_invoice` DISABLE KEYS */;
/*!40000 ALTER TABLE `e_invoice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `general_charges`
--

DROP TABLE IF EXISTS `general_charges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `general_charges` (
  `general_charge_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `charge_type_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `effective_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`general_charge_id`),
  UNIQUE KEY `general_charges_charge_type_id_effective_date_unique` (`charge_type_id`,`effective_date`),
  KEY `general_charges_charge_type_id_is_active_index` (`charge_type_id`,`is_active`),
  CONSTRAINT `general_charges_charge_type_id_foreign` FOREIGN KEY (`charge_type_id`) REFERENCES `charge_types` (`charge_type_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `general_charges`
--

LOCK TABLES `general_charges` WRITE;
/*!40000 ALTER TABLE `general_charges` DISABLE KEYS */;
INSERT INTO `general_charges` VALUES (1,4,250.00,'2026-07-27',NULL,1,'2026-08-12 13:23:28','2026-08-12 13:23:28'),(2,5,150.00,'2026-07-27',NULL,1,'2026-08-12 13:23:28','2026-08-12 13:23:28');
/*!40000 ALTER TABLE `general_charges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `handling_fees`
--

DROP TABLE IF EXISTS `handling_fees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `handling_fees` (
  `handling_fee_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `port_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `effective_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`handling_fee_id`),
  UNIQUE KEY `handling_fees_port_id_effective_date_unique` (`port_id`,`effective_date`),
  KEY `handling_fees_port_id_is_active_index` (`port_id`,`is_active`),
  CONSTRAINT `handling_fees_port_id_foreign` FOREIGN KEY (`port_id`) REFERENCES `ports` (`port_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `handling_fees`
--

LOCK TABLES `handling_fees` WRITE;
/*!40000 ALTER TABLE `handling_fees` DISABLE KEYS */;
INSERT INTO `handling_fees` VALUES (1,3,750.00,'2026-07-27',NULL,1,'2026-08-13 05:17:32','2026-08-13 05:17:32'),(2,12,750.00,'2026-07-27',NULL,1,'2026-08-13 05:17:32','2026-08-13 05:17:32'),(3,18,750.00,'2026-07-27',NULL,1,'2026-08-13 05:17:32','2026-08-13 05:17:32'),(4,19,750.00,'2026-07-27',NULL,1,'2026-08-13 05:17:32','2026-08-13 05:17:32'),(5,20,750.00,'2026-07-27',NULL,1,'2026-08-13 05:17:32','2026-08-13 05:17:32'),(6,29,750.00,'2026-07-27',NULL,1,'2026-08-13 05:17:32','2026-08-13 05:17:32');
/*!40000 ALTER TABLE `handling_fees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (4,'default','{\"uuid\":\"0be9e45c-6f28-433e-91c0-2023aebde048\",\"displayName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"command\":\"O:31:\\\"App\\\\Jobs\\\\SendApplicationMailJob\\\":2:{s:10:\\\"\\u0000*\\u0000payload\\\";a:4:{s:7:\\\"subject\\\";s:35:\\\"Approval needed — CPR-202609-0001\\\";s:5:\\\"title\\\";s:31:\\\"Proposal awaiting your approval\\\";s:7:\\\"message\\\";s:52:\\\"CPR-202609-0001 for Minton Diaz needs your approval.\\\";s:6:\\\"button\\\";a:2:{s:3:\\\"url\\\";s:46:\\\"http:\\/\\/kargamine_prototype.test\\/page_proposals\\\";s:4:\\\"text\\\";s:13:\\\"View Proposal\\\";}}s:9:\\\"\\u0000*\\u0000userId\\\";i:4;}\"}}',0,NULL,1788370844,1788370844),(5,'default','{\"uuid\":\"3472a1c4-d083-46cb-a325-ea9a8eccb8e2\",\"displayName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"command\":\"O:31:\\\"App\\\\Jobs\\\\SendApplicationMailJob\\\":2:{s:10:\\\"\\u0000*\\u0000payload\\\";a:4:{s:7:\\\"subject\\\";s:35:\\\"Approval needed — CPR-202609-0001\\\";s:5:\\\"title\\\";s:31:\\\"Proposal awaiting your approval\\\";s:7:\\\"message\\\";s:52:\\\"CPR-202609-0001 for Minton Diaz needs your approval.\\\";s:6:\\\"button\\\";a:2:{s:3:\\\"url\\\";s:46:\\\"http:\\/\\/kargamine_prototype.test\\/page_proposals\\\";s:4:\\\"text\\\";s:13:\\\"View Proposal\\\";}}s:9:\\\"\\u0000*\\u0000userId\\\";i:5;}\"}}',0,NULL,1788370845,1788370845),(6,'default','{\"uuid\":\"85baeafd-c38d-42f7-b856-fd5416e26d0d\",\"displayName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"command\":\"O:31:\\\"App\\\\Jobs\\\\SendApplicationMailJob\\\":2:{s:10:\\\"\\u0000*\\u0000payload\\\";a:4:{s:7:\\\"subject\\\";s:28:\\\"New Lead — Ramon dela Cruz\\\";s:5:\\\"title\\\";s:16:\\\"New lead created\\\";s:7:\\\"message\\\";s:49:\\\"Minton Diaz added a new lead — Ramon dela Cruz.\\\";s:6:\\\"button\\\";a:2:{s:3:\\\"url\\\";s:40:\\\"http:\\/\\/kargamine_prototype.test\\/page_crm\\\";s:4:\\\"text\\\";s:11:\\\"View in CRM\\\";}}s:9:\\\"\\u0000*\\u0000userId\\\";i:4;}\"}}',0,NULL,1788379847,1788379847),(7,'default','{\"uuid\":\"8c87c36f-d8b6-4947-bcd5-54d8055574da\",\"displayName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"command\":\"O:31:\\\"App\\\\Jobs\\\\SendApplicationMailJob\\\":2:{s:10:\\\"\\u0000*\\u0000payload\\\";a:4:{s:7:\\\"subject\\\";s:28:\\\"New Lead — Ramon dela Cruz\\\";s:5:\\\"title\\\";s:16:\\\"New lead created\\\";s:7:\\\"message\\\";s:49:\\\"Minton Diaz added a new lead — Ramon dela Cruz.\\\";s:6:\\\"button\\\";a:2:{s:3:\\\"url\\\";s:40:\\\"http:\\/\\/kargamine_prototype.test\\/page_crm\\\";s:4:\\\"text\\\";s:11:\\\"View in CRM\\\";}}s:9:\\\"\\u0000*\\u0000userId\\\";i:5;}\"}}',0,NULL,1788379847,1788379847),(8,'default','{\"uuid\":\"73c2c984-0b97-47bf-9a12-edd23759695a\",\"displayName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"command\":\"O:31:\\\"App\\\\Jobs\\\\SendApplicationMailJob\\\":2:{s:10:\\\"\\u0000*\\u0000payload\\\";a:4:{s:7:\\\"subject\\\";s:28:\\\"New Lead — Ramon dela Cruz\\\";s:5:\\\"title\\\";s:16:\\\"New lead created\\\";s:7:\\\"message\\\";s:49:\\\"Minton Diaz added a new lead — Ramon dela Cruz.\\\";s:6:\\\"button\\\";a:2:{s:3:\\\"url\\\";s:40:\\\"http:\\/\\/kargamine_prototype.test\\/page_crm\\\";s:4:\\\"text\\\";s:11:\\\"View in CRM\\\";}}s:9:\\\"\\u0000*\\u0000userId\\\";i:4;}\"}}',0,NULL,1788380039,1788380039),(9,'default','{\"uuid\":\"94bdb511-e494-4bca-a91e-f02b377b5b1e\",\"displayName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"command\":\"O:31:\\\"App\\\\Jobs\\\\SendApplicationMailJob\\\":2:{s:10:\\\"\\u0000*\\u0000payload\\\";a:4:{s:7:\\\"subject\\\";s:28:\\\"New Lead — Ramon dela Cruz\\\";s:5:\\\"title\\\";s:16:\\\"New lead created\\\";s:7:\\\"message\\\";s:49:\\\"Minton Diaz added a new lead — Ramon dela Cruz.\\\";s:6:\\\"button\\\";a:2:{s:3:\\\"url\\\";s:40:\\\"http:\\/\\/kargamine_prototype.test\\/page_crm\\\";s:4:\\\"text\\\";s:11:\\\"View in CRM\\\";}}s:9:\\\"\\u0000*\\u0000userId\\\";i:5;}\"}}',0,NULL,1788380039,1788380039),(10,'default','{\"uuid\":\"754511c2-d656-4cf2-acbe-966afb97a083\",\"displayName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"command\":\"O:31:\\\"App\\\\Jobs\\\\SendApplicationMailJob\\\":2:{s:10:\\\"\\u0000*\\u0000payload\\\";a:4:{s:7:\\\"subject\\\";s:46:\\\"Your proposal was approved — CPR-202609-0001\\\";s:5:\\\"title\\\";s:17:\\\"Proposal approved\\\";s:7:\\\"message\\\";s:43:\\\"CPR-202609-0001 was approved by Superadmin.\\\";s:6:\\\"button\\\";a:2:{s:3:\\\"url\\\";s:46:\\\"http:\\/\\/kargamine_prototype.test\\/page_proposals\\\";s:4:\\\"text\\\";s:13:\\\"View Proposal\\\";}}s:9:\\\"\\u0000*\\u0000userId\\\";i:3;}\"}}',0,NULL,1788496064,1788496064);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lane_tariff_rate_prices`
--

DROP TABLE IF EXISTS `lane_tariff_rate_prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lane_tariff_rate_prices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lane_tariff_rate_id` bigint(20) unsigned NOT NULL,
  `container_variant_id` bigint(20) unsigned NOT NULL,
  `frt` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lane_tariff_rate_prices_unique` (`lane_tariff_rate_id`,`container_variant_id`),
  KEY `lane_tariff_rate_prices_container_variant_id_foreign` (`container_variant_id`),
  CONSTRAINT `lane_tariff_rate_prices_container_variant_id_foreign` FOREIGN KEY (`container_variant_id`) REFERENCES `container_variants` (`id`),
  CONSTRAINT `lane_tariff_rate_prices_lane_tariff_rate_id_foreign` FOREIGN KEY (`lane_tariff_rate_id`) REFERENCES `lane_tariff_rates` (`rate_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lane_tariff_rate_prices`
--

LOCK TABLES `lane_tariff_rate_prices` WRITE;
/*!40000 ALTER TABLE `lane_tariff_rate_prices` DISABLE KEYS */;
INSERT INTO `lane_tariff_rate_prices` VALUES (1,1,1,9000.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(2,1,2,14400.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(7,1,7,12600.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(8,1,8,20200.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(9,1,9,9900.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(10,1,10,15800.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(11,2,1,9000.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(12,2,2,14400.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(17,2,7,12600.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(18,2,8,20200.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(19,2,9,9900.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(20,2,10,15800.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(21,3,1,4000.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(22,3,2,6400.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(27,3,7,5600.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(28,3,8,9000.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(29,3,9,4400.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(30,3,10,7000.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(31,4,1,4000.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(32,4,2,6400.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(37,4,7,5600.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(38,4,8,9000.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(39,4,9,4400.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(40,4,10,7000.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(41,5,1,16000.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(42,5,2,25600.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(47,5,7,22400.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(48,5,8,35800.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(49,5,9,17600.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(50,5,10,28200.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(51,6,1,16000.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(52,6,2,25600.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(57,6,7,22400.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(58,6,8,35800.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(59,6,9,17600.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(60,6,10,28200.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(61,7,1,8000.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(62,7,2,12800.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(67,7,7,11200.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(68,7,8,17900.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(69,7,9,8800.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(70,7,10,14100.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(71,8,1,8000.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(72,8,2,12800.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(77,8,7,11200.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(78,8,8,17900.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(79,8,9,8800.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(80,8,10,14100.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(81,9,1,15000.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(82,9,2,24000.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(87,9,7,21000.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(88,9,8,33600.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(89,9,9,16500.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(90,9,10,26400.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(91,10,1,15000.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(92,10,2,24000.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(97,10,7,21000.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(98,10,8,33600.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(99,10,9,16500.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(100,10,10,26400.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(101,11,1,7000.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(102,11,2,11200.00,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(107,11,7,9800.00,'2026-08-13 05:17:25','2026-08-13 05:17:25'),(108,11,8,15700.00,'2026-08-13 05:17:25','2026-08-13 05:17:25'),(109,11,9,7700.00,'2026-08-13 05:17:25','2026-08-13 05:17:25'),(110,11,10,12300.00,'2026-08-13 05:17:25','2026-08-13 05:17:25'),(111,12,1,7000.00,'2026-08-13 05:17:25','2026-08-13 05:17:25'),(112,12,2,11200.00,'2026-08-13 05:17:25','2026-08-13 05:17:25'),(117,12,7,9800.00,'2026-08-13 05:17:25','2026-08-13 05:17:25'),(118,12,8,15700.00,'2026-08-13 05:17:25','2026-08-13 05:17:25'),(119,12,9,7700.00,'2026-08-13 05:17:25','2026-08-13 05:17:25'),(120,12,10,12300.00,'2026-08-13 05:17:25','2026-08-13 05:17:25'),(121,13,1,15000.00,'2026-08-21 00:48:38','2026-08-21 00:48:38'),(122,14,1,10000.00,'2026-08-22 17:59:28','2026-08-22 17:59:28'),(123,14,2,20000.00,'2026-08-22 17:59:28','2026-08-22 17:59:28'),(124,14,7,15000.00,'2026-08-22 17:59:28','2026-08-22 17:59:28'),(125,14,8,30000.00,'2026-08-22 17:59:28','2026-08-22 17:59:28'),(126,14,9,8000.00,'2026-08-22 17:59:28','2026-08-22 17:59:28'),(127,14,11,130.00,'2026-08-22 17:59:28','2026-08-22 17:59:28'),(128,14,12,150.00,'2026-08-22 17:59:28','2026-08-22 17:59:28'),(129,14,13,110.00,'2026-08-22 17:59:28','2026-08-22 17:59:28'),(130,14,14,130.00,'2026-08-22 17:59:28','2026-08-22 17:59:28');
/*!40000 ALTER TABLE `lane_tariff_rate_prices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lane_tariff_rates`
--

DROP TABLE IF EXISTS `lane_tariff_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lane_tariff_rates` (
  `rate_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lane_id` bigint(20) unsigned NOT NULL,
  `effective_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`rate_id`),
  UNIQUE KEY `lane_tariff_rates_lane_id_effective_date_unique` (`lane_id`,`effective_date`),
  KEY `lane_tariff_rates_lane_id_is_active_index` (`lane_id`,`is_active`),
  CONSTRAINT `lane_tariff_rates_lane_id_foreign` FOREIGN KEY (`lane_id`) REFERENCES `lanes` (`lane_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lane_tariff_rates`
--

LOCK TABLES `lane_tariff_rates` WRITE;
/*!40000 ALTER TABLE `lane_tariff_rates` DISABLE KEYS */;
INSERT INTO `lane_tariff_rates` VALUES (1,1,'2026-07-27',NULL,1,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(2,2,'2026-07-27',NULL,1,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(3,3,'2026-07-27',NULL,1,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(4,4,'2026-07-27',NULL,1,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(5,5,'2026-07-27',NULL,1,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(6,6,'2026-07-27',NULL,1,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(7,7,'2026-07-27',NULL,1,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(8,8,'2026-07-27',NULL,1,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(9,9,'2026-07-27',NULL,1,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(10,10,'2026-07-27',NULL,1,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(11,11,'2026-07-27',NULL,1,'2026-08-13 05:17:24','2026-08-13 05:17:24'),(12,12,'2026-07-27',NULL,1,'2026-08-13 05:17:25','2026-08-13 05:17:25'),(13,13,'2026-08-20',NULL,1,'2026-08-21 00:48:38','2026-08-21 00:48:38'),(14,14,'2026-08-22',NULL,1,'2026-08-22 17:59:28','2026-08-22 17:59:28');
/*!40000 ALTER TABLE `lane_tariff_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lanes`
--

DROP TABLE IF EXISTS `lanes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lanes` (
  `lane_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `origin_port_id` bigint(20) unsigned NOT NULL,
  `destination_port_id` bigint(20) unsigned NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`lane_id`),
  UNIQUE KEY `lanes_origin_port_id_destination_port_id_unique` (`origin_port_id`,`destination_port_id`),
  KEY `lanes_destination_port_id_foreign` (`destination_port_id`),
  CONSTRAINT `lanes_destination_port_id_foreign` FOREIGN KEY (`destination_port_id`) REFERENCES `ports` (`port_id`) ON UPDATE CASCADE,
  CONSTRAINT `lanes_origin_port_id_foreign` FOREIGN KEY (`origin_port_id`) REFERENCES `ports` (`port_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lanes`
--

LOCK TABLES `lanes` WRITE;
/*!40000 ALTER TABLE `lanes` DISABLE KEYS */;
INSERT INTO `lanes` VALUES (1,29,19,1,'2026-08-13 05:17:20','2026-08-13 05:17:20'),(2,19,29,1,'2026-08-13 05:17:20','2026-08-13 05:17:20'),(3,29,12,1,'2026-08-13 05:17:20','2026-08-13 05:17:20'),(4,12,29,1,'2026-08-13 05:17:20','2026-08-13 05:17:20'),(5,29,20,1,'2026-08-13 05:17:20','2026-08-13 05:17:20'),(6,20,29,1,'2026-08-13 05:17:20','2026-08-13 05:17:20'),(7,29,3,1,'2026-08-13 05:17:20','2026-08-13 05:17:20'),(8,3,29,1,'2026-08-13 05:17:20','2026-08-13 05:17:20'),(9,29,18,1,'2026-08-13 05:17:20','2026-08-13 05:17:20'),(10,18,29,1,'2026-08-13 05:17:20','2026-08-13 05:17:20'),(11,19,20,1,'2026-08-13 05:17:20','2026-08-13 05:17:20'),(12,20,19,1,'2026-08-13 05:17:20','2026-08-13 05:17:20'),(13,7,19,1,'2026-08-21 00:48:38','2026-08-21 00:48:38'),(14,7,32,1,'2026-08-22 17:59:28','2026-08-22 17:59:28');
/*!40000 ALTER TABLE `lanes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `list_of_values_table`
--

DROP TABLE IF EXISTS `list_of_values_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `list_of_values_table` (
  `lov_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lov_optionId` bigint(20) unsigned NOT NULL,
  `lov_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lov_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lov_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`lov_id`),
  UNIQUE KEY `list_of_values_table_lov_optionid_lov_name_unique` (`lov_optionId`,`lov_name`),
  KEY `list_of_values_table_lov_optionid_index` (`lov_optionId`),
  KEY `list_of_values_table_lov_name_index` (`lov_name`),
  CONSTRAINT `list_of_values_table_lov_optionid_foreign` FOREIGN KEY (`lov_optionId`) REFERENCES `options_table` (`option_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `list_of_values_table`
--

LOCK TABLES `list_of_values_table` WRITE;
/*!40000 ALTER TABLE `list_of_values_table` DISABLE KEYS */;
INSERT INTO `list_of_values_table` VALUES (1,1,'OFF','Office',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(2,1,'WAR','Warehouse',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(3,1,'BRA','Branch',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(4,1,'STO','Storage Facility',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(5,2,'REF','Referral',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(6,2,'WEB','Website',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(7,2,'WAL','Walk-in',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(8,2,'COL','Cold Call',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(9,2,'SOC','Social Media',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(10,2,'OTH','Other',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(11,3,'IMP','Importer',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(12,3,'EXP','Exporter',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(13,3,'MAN','Manufacturer',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(14,3,'TRA','Trading',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(15,3,'RET','Retail',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(16,3,'DIS','Distributor',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(17,3,'OTH','Others',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(18,4,'MAN','Manufacturing',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(19,4,'RET','Retail',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(20,4,'LOG','Logistics & Freight',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(21,4,'CON','Construction',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(22,4,'AGR','Agriculture',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(23,4,'INF','Information Technology',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(24,4,'FOO','Food & Beverage',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(25,4,'PHA','Pharmaceuticals',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(26,5,'SOL','Sole Proprietorship',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(27,5,'PAR','Partnership',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(28,5,'COR','Corporation',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(29,5,'COO','Cooperative',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(30,5,'GOV','Government',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(31,5,'NON','Non-Profit',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(32,6,'DIR','Direct Client',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(33,6,'BRO','Broker / Agent',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(34,6,'COR','Corporate Account',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(35,6,'GOV','Government Account',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(36,6,'WAL','Walk-in Client',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(37,7,'REG','Regular',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(38,7,'KEY','Key Account',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(39,7,'VIP','VIP',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(40,7,'STR','Strategic Partner',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(41,7,'NEW','New Client',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(42,8,'CBM','cbm/s',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(43,8,'DAY','day/s',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(44,8,'HOU','hour/s',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(45,8,'LIT','liter/s',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(46,8,'MON','month/s',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(47,8,'MOV','move/s',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(48,8,'OCC','occurrence/s',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(49,8,'OTH','others',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(50,8,'PIE','piece/s',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(51,8,'SER','service/s',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(52,8,'SET','set/s',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(53,8,'TON','ton/s',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(54,8,'TRI','trip/s',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(55,8,'UNI','unit/s',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(56,9,'GEN','General Cargo',NULL,'2026-09-01 10:58:29','2026-09-01 10:58:29'),(57,9,'PER','Perishable Goods',NULL,'2026-09-01 10:58:29','2026-09-01 10:58:29'),(58,9,'FRA','Fragile / Breakable',NULL,'2026-09-01 10:58:29','2026-09-01 10:58:29'),(59,9,'LIQ','Liquid Bulk',NULL,'2026-09-01 10:58:29','2026-09-01 10:58:29'),(60,9,'DRY','Dry Bulk',NULL,'2026-09-01 10:58:29','2026-09-01 10:58:29'),(61,9,'MAC','Machinery & Equipment',NULL,'2026-09-01 10:58:29','2026-09-01 10:58:29'),(62,9,'LIV','Livestock',NULL,'2026-09-01 10:58:29','2026-09-01 10:58:29'),(63,9,'DOC','Documents / Parcels',NULL,'2026-09-01 10:58:29','2026-09-01 10:58:29'),(64,9,'FRO','Frozen / Chilled Goods',NULL,'2026-09-01 10:58:29','2026-09-01 10:58:29');
/*!40000 ALTER TABLE `list_of_values_table` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `locations` (
  `location_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`location_id`),
  UNIQUE KEY `locations_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locations`
--

LOCK TABLES `locations` WRITE;
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;
INSERT INTO `locations` VALUES (1,'Aklan',1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(2,'Bacolod',1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(3,'Bataan',1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(4,'Batangas',1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(5,'Bicol',1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(6,'Butuan',1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(7,'CDO',1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(8,'Cebu',1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(9,'Davao',1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(10,'Dinagat',1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(11,'La Union',1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(12,'Leyte',1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(13,'Manila',1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(14,'Masbate',1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(15,'Palawan',1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(16,'Pangasinan',1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(17,'Romblon',1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(18,'Roxas',1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(19,'Surigao',1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(20,'Tacloban',1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(21,'Zambales',1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(22,'Zamboanga',1,'2026-08-13 05:17:11','2026-08-13 05:17:11');
/*!40000 ALTER TABLE `locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mailer_settings`
--

DROP TABLE IF EXISTS `mailer_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mailer_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mail_mailer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'smtp',
  `mail_host` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_port` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_encryption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_from_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_from_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=164 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2025_10_01_211540_create_nav_menus_table',1),(6,'2025_10_02_163004_create_table_for_settings_role',1),(7,'2025_10_03_180527_add_parentmenu',1),(8,'2025_10_04_144632_create_mailer_settings_table',1),(9,'2025_11_06_035725_insert_order_in_nav_menus',1),(10,'2026_01_24_221131_create_sessions_table',1),(11,'2026_01_24_221645_add_session_id_to_users_table',1),(12,'2026_01_24_223037_create_cache_table',1),(13,'2026_01_26_110424_create_jobs_table',1),(14,'2026_01_26_110425_modify_columns_of_user_table',1),(15,'2026_05_01_011537_options_table',1),(16,'2026_05_01_011632_list_of_value_table',1),(17,'2026_05_05_052405_create_company_info_master_table',1),(18,'2026_05_05_052441_create_contact_info_table',1),(19,'2026_05_05_052453_create_trade_references_table',1),(20,'2026_05_05_052548_create_services_info_table',1),(21,'2026_05_05_052612_create_company_finance_table',1),(22,'2026_05_05_052631_create_billed_details_table',1),(23,'2026_05_05_052656_create_sales_info_table',1),(24,'2026_05_05_052712_create_stages_info_table',1),(25,'2026_05_07_205424_create_e_invoice_table',1),(26,'2026_05_07_205616_create_courier_invoice_table',1),(27,'2026_06_05_045259_create_crm_status_table',1),(28,'2026_06_05_045351_create_crm_leads_table',1),(29,'2026_06_05_045416_create_company_info_table',1),(30,'2026_06_05_045430_create_crm_notes_table',1),(31,'2026_06_05_045450_create_crm_activities_table',1),(32,'2026_06_15_230541_proposals',1),(33,'2026_06_15_231224_proposal_rates',1),(34,'2026_06_15_233415_create_table_for_routes',1),(35,'2026_06_15_233633_create_table_service_type',1),(36,'2026_06_15_233806_create_proposal_status',1),(37,'2026_06_15_233848_create_customer_type',1),(38,'2026_06_26_195636_create_container_type',1),(39,'2026_06_26_195710_create_container_class',1),(40,'2026_06_26_195725_create_container_size',1),(41,'2026_07_02_221007_add_new_columns_to_user_table',1),(42,'2026_07_02_222100_create_user_department',1),(43,'2026_07_02_222118_create_user_status',1),(44,'2026_07_04_000001_create_ports_table',1),(45,'2026_07_04_000002_create_serviceable_areas_table',1),(46,'2026_07_04_000003_create_delivery_types_table',1),(47,'2026_07_04_000004_create_charge_types_table',1),(48,'2026_07_04_000005_create_lanes_table',1),(49,'2026_07_04_000006_create_lane_tariff_rates_table',1),(50,'2026_07_04_000007_create_port_charges_table',1),(51,'2026_07_04_000008_create_handling_fees_table',1),(52,'2026_07_04_000009_create_trucking_tariffs_table',1),(53,'2026_07_04_000010_create_vat_rates_table',1),(54,'2026_07_04_000011_create_contracts_table',1),(55,'2026_07_04_000012_create_contract_rates_table',1),(56,'2026_07_04_000013_create_bookings_table',1),(57,'2026_07_04_000014_create_booking_port_charges_table',1),(58,'2026_07_07_000001_add_applicable_to_to_charge_types_table',1),(59,'2026_07_07_000002_create_general_charges_table',1),(60,'2026_07_07_124225_drop_bsc_ra_gri_from_lane_tariff_rates_table',1),(61,'2026_07_07_124800_add_rate_type_and_rate_value_to_proposals_rates_table',1),(62,'2026_07_08_175248_create_client_masters_table',1),(63,'2026_07_08_175319_create_client_contacts_table',1),(64,'2026_07_08_175429_create_client_trade_references_table',1),(65,'2026_07_08_175448_create_client_finance_table',1),(66,'2026_07_08_175528_create_client_billing_table',1),(67,'2026_07_08_221636_create_containers_table',1),(68,'2026_07_08_221704_create_container_variants_table',1),(69,'2026_07_08_221727_create_lane_tariff_rate_prices_table',1),(70,'2026_07_09_000939_drop_column_from_container_table',1),(71,'2026_07_09_001926_drop_column_from_lane_tariff_rates_table',1),(72,'2026_07_10_212311_create_client_proposals_table',1),(73,'2026_07_10_212810_create_client_proposal_rates_table',1),(74,'2026_07_10_212846_create_client_contracts_table',1),(75,'2026_07_10_212946_create_client_contract_rates_table',1),(76,'2026_07_11_152530_add_lead_id_to_client_masters_table',1),(77,'2026_07_13_182230_add_workflow_columns_to_client_proposals_table',1),(78,'2026_07_14_021740_add_progress_columns_to_crm_leads_table',1),(79,'2026_07_14_021824_add_address_fields_to_crm_company_info_table',1),(80,'2026_07_14_021949_create_crm_lead_containers_table',1),(81,'2026_07_14_030206_add_lookup_columns_to_crm_lead_containers_table',1),(82,'2026_07_14_044418_drop_company_address_from_crm_company_info',1),(83,'2026_07_22_003454_add_lead_id_to_client_proposals_table',1),(84,'2026_07_22_020031_add_attachment_to_crm_activities_table',1),(85,'2026_07_22_031438_add_client_type_and_contact_fields_to_crm_leads_table',1),(86,'2026_07_22_031439_create_crm_lead_addresses_table',1),(87,'2026_07_22_031439_migrate_crm_company_address_to_lead_addresses_and_drop_columns',1),(88,'2026_07_22_031440_add_industry_description_to_crm_company_info_table',1),(89,'2026_07_22_040302_remove_lookup_values_nav_menu_entry',1),(90,'2026_07_22_045002_add_customer_code_to_crm_leads_table',1),(91,'2026_07_22_045002_create_client_addresses_table',1),(92,'2026_07_22_045003_migrate_client_registered_address_and_drop_column',1),(93,'2026_07_22_045004_add_type_fields_to_client_contacts_table',1),(94,'2026_07_23_010845_create_app_theme_settings_table',1),(95,'2026_07_23_010846_add_theme_nav_menu_entry',1),(96,'2026_07_25_031024_create_notifications_table',1),(97,'2026_07_25_035333_create_teams_table',1),(98,'2026_07_25_035334_add_team_columns_to_users_table',1),(99,'2026_07_27_100000_add_coordinates_to_ports_table',1),(100,'2026_07_27_100100_create_container_assets_table',1),(101,'2026_07_27_100200_create_container_asset_location_history_table',1),(102,'2026_07_27_110000_add_is_system_to_setting_role_table',1),(103,'2026_07_27_110100_create_permissions_table',1),(104,'2026_07_27_110200_create_role_permission_table',1),(105,'2026_07_27_120000_add_booking_header_fields_to_bookings_table',1),(106,'2026_07_27_120100_create_booking_lines_table',1),(107,'2026_07_27_120200_create_booking_status_history_table',1),(108,'2026_07_27_120300_create_booking_container_units_table',1),(109,'2026_07_27_120400_create_booking_invoices_table',1),(110,'2026_07_27_120500_create_bill_of_ladings_table',1),(111,'2026_07_27_130000_add_termination_fields_to_client_contracts_table',1),(112,'2026_07_28_090000_create_nav_icons_table',1),(113,'2026_07_28_185238_move_route_delivery_to_booking_lines',1),(114,'2026_07_28_204127_add_min_van_qty_to_client_rate_tables',1),(115,'2026_07_29_003717_split_contact_name_on_crm_leads_table',1),(116,'2026_07_29_003752_rename_required_temperature_on_crm_lead_containers_table',1),(117,'2026_07_29_003808_add_authorized_signatory_fields_to_crm_company_info_table',1),(118,'2026_07_29_131824_add_transaction_details_to_booking_lines_table',1),(119,'2026_07_29_173304_add_always_route_atw_to_client_masters_table',1),(120,'2026_07_29_173305_add_cv_assignment_fields_to_booking_container_units_table',1),(121,'2026_07_29_173306_create_booking_dispatch_documents_table',1),(122,'2026_07_29_181324_add_gate_pass_fields_to_booking_container_units_table',1),(123,'2026_07_29_183320_create_booking_container_eir_records_table',1),(124,'2026_07_29_195201_create_vessel_voyages_table',1),(125,'2026_07_29_195202_add_voyage_fields_to_booking_container_units_table',1),(126,'2026_08_03_100001_add_client_mnemonic_and_account_manager_to_client_masters_table',1),(127,'2026_08_03_100002_restructure_client_finance_table',1),(128,'2026_08_03_100003_restructure_client_contacts_table',1),(129,'2026_08_03_100004_create_client_contact_addresses_table',1),(130,'2026_08_03_100005_create_client_ancillary_services_table',1),(131,'2026_08_03_100006_drop_client_billing_table',1),(132,'2026_08_04_100001_restructure_client_masters_company_info_fields',1),(133,'2026_08_04_120001_restructure_client_finance_cro_and_declared_value',1),(134,'2026_08_05_100001_create_special_charges_table',1),(135,'2026_08_05_100002_create_cargo_yards_table',1),(136,'2026_08_06_100001_simplify_client_ancillary_services_table',1),(137,'2026_08_06_120000_add_workflow_columns_to_client_contracts_table',1),(138,'2026_08_06_120100_add_tax_type_to_vat_rates_table',1),(139,'2026_08_06_120200_rename_tax_status_to_registered_tax_type_on_client_finance_table',1),(140,'2026_08_07_100000_add_must_change_password_to_users_table',1),(141,'2026_08_11_090000_create_locations_table',1),(142,'2026_08_11_090100_add_location_id_to_ports_table',1),(143,'2026_08_12_031524_add_profile_photo_path_to_users_table',1),(144,'2026_08_12_042434_create_app_information_settings_table',1),(145,'2026_08_12_042435_add_app_information_nav_menu_entry',1),(146,'2026_08_12_192648_scope_container_class_and_size_to_container',1),(147,'2026_08_13_033349_scope_serviceable_areas_to_location',2),(148,'2026_08_13_135908_make_container_variants_size_nullable',3),(149,'2026_08_19_120000_make_container_class_and_size_nullable_on_rate_tables',4),(150,'2026_08_22_164450_widen_discount_type_enum_for_client_proposal_and_contract_rates',5),(151,'2026_08_22_170000_add_hazardous_document_path_to_booking_lines_table',6),(152,'2026_08_22_180000_add_minimum_temperature_to_booking_lines_table',7),(153,'2026_08_23_000001_create_vessels_table',8),(154,'2026_08_23_000002_create_vessel_status_histories_table',8),(155,'2026_08_23_000003_create_vessel_maintenance_records_table',8),(156,'2026_08_23_000004_replace_vessel_name_with_vessel_id_on_vessel_voyages_table',8),(157,'2026_09_01_000000_add_nav_layout_to_users_table',9),(158,'2026_09_01_000001_add_cargo_type_to_crm_lead_containers_table',10),(159,'2026_09_04_090000_add_pier_handling_to_booking_lines_table',11),(163,'2026_09_06_020000_create_userconfig_table',13);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nav_icons`
--

DROP TABLE IF EXISTS `nav_icons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nav_icons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `svg` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nav_icons_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nav_icons`
--

LOCK TABLES `nav_icons` WRITE;
/*!40000 ALTER TABLE `nav_icons` DISABLE KEYS */;
INSERT INTO `nav_icons` VALUES (1,'home','Home','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(2,'users','Users','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-4.5 0 2.625 2.625 0 014.5 0z\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(3,'user','User','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(4,'bell','Bell','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(5,'magnifying-glass','Search','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"m21 21-5.2-5.2m0 0A7.5 7.5 0 1 0 5.3 5.3a7.5 7.5 0 0 0 10.5 10.5Z\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(6,'x-mark','Close (X)','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M6 18L18 6M6 6l12 12\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(7,'check-circle','Check Circle','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(8,'document-text','Document','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(9,'sparkles','Sparkles','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.456-2.456L14.25 6l1.035-.259a3.375 3.375 0 002.456-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(10,'cube','Box / Cube','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(11,'envelope','Envelope','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(12,'bars-3','Menu (3 lines)','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(13,'cog-6-tooth','Settings (gear)','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.28zM15 12a3 3 0 11-6 0 3 3 0 016 0z\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(14,'chevron-down','Chevron Down','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M19.5 8.25l-7.5 7.5-7.5-7.5\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(15,'chevron-up','Chevron Up','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M4.5 15.75l7.5-7.5 7.5 7.5\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(16,'chevron-left','Chevron Left','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M15.75 19.5L8.25 12l7.5-7.5\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(17,'chevron-right','Chevron Right','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M8.25 4.5l7.5 7.5-7.5 7.5\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(18,'plus','Plus','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M12 4.5v15m7.5-7.5h-15\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(19,'minus','Minus','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M5 12h14\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(20,'arrow-path','Refresh','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(21,'arrow-up-tray','Upload','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(22,'arrow-down-tray','Download','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(23,'trash','Trash','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(24,'pencil','Edit (pencil)','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(25,'eye','Eye','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .638C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178zM15 12a3 3 0 11-6 0 3 3 0 016 0z\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(26,'eye-slash','Eye Slash','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M3.98 8.223A10.477 10.477 0 001.934 12c1.832 4.068 5.728 7 10.066 7 1.676 0 3.285-.37 4.712-1.034M6.228 6.228A10.45 10.45 0 0112 5c4.38 0 8.293 2.953 10.07 7.063a10.522 10.522 0 01-4.517 4.92M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.878 9.878\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(27,'clock','Clock','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(28,'calendar','Calendar','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(29,'truck','Truck','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(30,'archive-box','Archive Box','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(31,'banknotes','Finance (banknotes)','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(32,'building-office','Building / Office','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(33,'globe-alt','Globe','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(34,'shield-check','Shield Check','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(35,'exclamation-triangle','Warning Triangle','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(36,'information-circle','Info Circle','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(37,'flag','Flag','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M3 3v1.5M3 21v-6m0 0l2.77-.693a9 9 0 016.208.682l.108.054a9 9 0 006.086.71l3.114-.732a48.524 48.524 0 01-.005-10.499l-3.11.732a9 9 0 01-6.085-.711l-.108-.054a9 9 0 00-6.208-.682L3 4.5M3 15V4.5\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(38,'star','Star','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.562.562 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(39,'heart','Heart','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(40,'tag','Tag','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3zM6 6h.008v.008H6V6z\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(41,'folder-open','Folder','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 015.25 3.75h4.5c.55 0 1.02.398 1.11.94l.213 1.28c.089.542.559.94 1.11.94h4.567a2.25 2.25 0 012.25 2.25v.616\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(42,'briefcase','Briefcase','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18a48.424 48.424 0 01-6.378.42c-2.162 0-4.291-.143-6.378-.42-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(43,'map-pin','Map Pin','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M15 10.5a3 3 0 11-6 0 3 3 0 016 0z\" /><path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(44,'link','Link','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(45,'paper-airplane','Send (paper airplane)','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(46,'printer','Printer','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(47,'square','Square','<rect x=\"3.75\" y=\"3.75\" width=\"16.5\" height=\"16.5\" rx=\"2\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(48,'circle','Circle','<circle cx=\"12\" cy=\"12\" r=\"8.25\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(49,'squares-2x2','Grid','<rect x=\"3.75\" y=\"3.75\" width=\"7.5\" height=\"7.5\" rx=\"1.25\" /><rect x=\"12.75\" y=\"3.75\" width=\"7.5\" height=\"7.5\" rx=\"1.25\" /><rect x=\"3.75\" y=\"12.75\" width=\"7.5\" height=\"7.5\" rx=\"1.25\" /><rect x=\"12.75\" y=\"12.75\" width=\"7.5\" height=\"7.5\" rx=\"1.25\" />','2026-08-12 13:23:25','2026-08-12 13:23:25'),(50,'list-bullet','List','<circle cx=\"4.5\" cy=\"6\" r=\"1\" fill=\"currentColor\" stroke=\"none\" /><circle cx=\"4.5\" cy=\"12\" r=\"1\" fill=\"currentColor\" stroke=\"none\" /><circle cx=\"4.5\" cy=\"18\" r=\"1\" fill=\"currentColor\" stroke=\"none\" /><line x1=\"8.25\" y1=\"6\" x2=\"20.25\" y2=\"6\" stroke-linecap=\"round\" /><line x1=\"8.25\" y1=\"12\" x2=\"20.25\" y2=\"12\" stroke-linecap=\"round\" /><line x1=\"8.25\" y1=\"18\" x2=\"20.25\" y2=\"18\" stroke-linecap=\"round\" />','2026-08-12 13:23:26','2026-08-12 13:23:26');
/*!40000 ALTER TABLE `nav_icons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nav_menus`
--

DROP TABLE IF EXISTS `nav_menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nav_menus` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `allowed_roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`allowed_roles`)),
  `parent_menu` int(11) NOT NULL DEFAULT 0,
  `menu_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nav_menus`
--

LOCK TABLES `nav_menus` WRITE;
/*!40000 ALTER TABLE `nav_menus` DISABLE KEYS */;
INSERT INTO `nav_menus` VALUES (1,'Theme','squares-2x2','/page_theme','[\"4\",\"1\"]',14,3,'2026-08-12 13:23:17','2026-08-12 13:23:26'),(2,'Application Information','information-circle','/page_app_information','[\"4\",\"1\"]',14,5,'2026-08-12 13:23:22','2026-08-12 13:23:26'),(3,'Dashboard','home','/page_dashboard','[\"2\",\"5\",\"4\",\"6\",\"1\",\"3\"]',0,0,'2026-08-12 13:23:26','2026-09-05 17:46:07'),(4,'CRM','flag','/page_crm','[\"2\",\"5\",\"4\",\"6\",\"1\",\"3\"]',0,1,'2026-08-12 13:23:26','2026-09-05 17:46:07'),(5,'Proposals','paper-airplane','page_proposals','[\"2\",\"5\",\"4\",\"6\",\"1\",\"3\"]',0,2,'2026-08-12 13:23:26','2026-09-05 17:46:07'),(6,'Clients','building-office','page_clientMasters','[\"2\",\"5\",\"4\",\"6\",\"1\",\"3\"]',0,3,'2026-08-12 13:23:26','2026-09-05 17:46:07'),(7,'Booking','squares-2x2','page_booking','[\"2\",\"4\",\"5\",\"6\",\"1\",\"3\"]',0,4,'2026-08-12 13:23:26','2026-09-05 17:46:07'),(8,'Contracts','document-text','/page_contracts','[\"2\",\"5\",\"4\",\"6\",\"1\",\"3\"]',0,5,'2026-08-12 13:23:26','2026-09-05 17:46:07'),(9,'Bookings','list-bullet','/page_booking','[\"2\",\"5\",\"4\",\"6\",\"1\",\"3\"]',0,6,'2026-08-12 13:23:26','2026-09-05 17:46:07'),(10,'Cargo Build-Up','archive-box','/page_cargo_build_up','[\"2\",\"5\",\"4\",\"6\",\"1\",\"3\"]',0,7,'2026-08-12 13:23:26','2026-09-05 17:46:07'),(11,'Pier Check-In','check-circle','/page_pier_checkin','[\"2\",\"5\",\"4\",\"6\",\"1\",\"3\"]',0,9,'2026-08-12 13:23:26','2026-09-05 17:46:07'),(12,'Users','users','/page_users','[\"4\",\"1\"]',0,10,'2026-08-12 13:23:26','2026-09-06 03:21:40'),(13,'Settings','cog-6-tooth','#','[\"2\",\"5\",\"4\",\"6\",\"1\",\"3\"]',0,11,'2026-08-12 13:23:26','2026-09-05 17:46:07'),(14,'Developer Option','shield-check','#','[\"4\",\"1\"]',0,12,'2026-08-12 13:23:26','2026-09-05 17:46:07'),(15,'App Settings','cog-6-tooth','/page_maintenance','[\"2\",\"5\",\"4\",\"6\",\"1\",\"3\"]',13,1,'2026-08-12 13:23:26','2026-09-05 17:46:07'),(16,'Team Management','briefcase','/page_team_management','[\"2\",\"5\",\"4\",\"6\",\"1\",\"3\"]',13,2,'2026-08-12 13:23:26','2026-09-05 17:46:07'),(17,'Container Inventory','cube','/page_container_inventory','[\"2\",\"5\",\"4\",\"6\",\"1\",\"3\"]',13,8,'2026-08-12 13:23:26','2026-09-05 17:46:07'),(18,'Mailer','envelope','/page_mailer','[\"4\",\"1\"]',14,1,'2026-08-12 13:23:26','2026-08-12 13:23:26'),(19,'Menus','bars-3','/page_menus','[\"4\",\"1\"]',14,2,'2026-08-12 13:23:26','2026-08-12 13:23:26'),(20,'Notification Test','bell','/page_notification_test','[\"4\",\"1\"]',14,4,'2026-08-12 13:23:26','2026-08-12 13:23:26'),(21,'Container Assignment','cube','/page_container_assignment','[\"2\",\"5\",\"4\",\"6\",\"1\",\"3\"]',0,8,'2026-08-22 16:50:54','2026-09-05 17:46:07'),(22,'Vessel Management','truck','/page_vessel_management','[\"2\",\"5\",\"4\",\"6\",\"1\",\"3\"]',0,13,'2026-08-22 17:44:08','2026-09-05 17:46:07'),(23,'Voyage Schedule','map-pin','/page_voyage_schedule','[\"2\",\"5\",\"4\",\"6\",\"1\",\"3\"]',0,14,'2026-08-22 17:44:08','2026-09-05 17:46:07');
/*!40000 ALTER TABLE `nav_menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notifiable_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `from_user_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`),
  KEY `notifications_from_user_id_foreign` (`from_user_id`),
  KEY `notifications_user_id_is_read_index` (`user_id`,`is_read`),
  CONSTRAINT `notifications_from_user_id_foreign` FOREIGN KEY (`from_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (1,'proposal.approved','App\\Models\\ClientProposal',2,5,5,'Proposal approved','CPR-202608-0002 was approved by Eden Palma.','View Proposal','/page_proposals','{\"modal_fn\":\"openProposalModal\",\"modal_args\":[2]}',0,'2026-08-22 18:07:42','2026-08-22 18:07:42'),(2,'crm.lead_created','App\\Models\\CrmLead',5,5,3,'New lead created','Minton Diaz added a new lead — Mrs. test test.','View in CRM','/page_crm',NULL,0,'2026-08-22 05:49:06','2026-08-22 05:49:06'),(3,'proposal.approved','App\\Models\\ClientProposal',1,5,4,'Proposal approved','CPR-202608-0001 was approved by Fritzie Tangan.','View Proposal','/page_proposals','{\"modal_fn\":\"openProposalModal\",\"modal_args\":[1]}',0,'2026-08-22 06:29:11','2026-08-22 06:29:11'),(4,'proposal.pending','App\\Models\\ClientProposal',3,4,3,'Proposal awaiting your approval','CPR-202609-0001 for Minton Diaz needs your approval.','View Proposal','/page_proposals','{\"modal_fn\":\"openProposalModal\",\"modal_args\":[3]}',0,'2026-09-02 17:40:44','2026-09-02 17:40:44'),(5,'proposal.pending','App\\Models\\ClientProposal',3,5,3,'Proposal awaiting your approval','CPR-202609-0001 for Minton Diaz needs your approval.','View Proposal','/page_proposals','{\"modal_fn\":\"openProposalModal\",\"modal_args\":[3]}',0,'2026-09-02 17:40:44','2026-09-02 17:40:44'),(6,'crm.lead_created','App\\Models\\CrmLead',7,4,3,'New lead created','Minton Diaz added a new lead — Ramon dela Cruz.','View in CRM','/page_crm',NULL,0,'2026-09-02 20:10:47','2026-09-02 20:10:47'),(7,'crm.lead_created','App\\Models\\CrmLead',7,5,3,'New lead created','Minton Diaz added a new lead — Ramon dela Cruz.','View in CRM','/page_crm',NULL,0,'2026-09-02 20:10:47','2026-09-02 20:10:47'),(8,'crm.lead_created','App\\Models\\CrmLead',8,4,3,'New lead created','Minton Diaz added a new lead — Ramon dela Cruz.','View in CRM','/page_crm',NULL,0,'2026-09-02 20:13:59','2026-09-02 20:13:59'),(9,'crm.lead_created','App\\Models\\CrmLead',8,5,3,'New lead created','Minton Diaz added a new lead — Ramon dela Cruz.','View in CRM','/page_crm',NULL,0,'2026-09-02 20:13:59','2026-09-02 20:13:59'),(10,'proposal.approved','App\\Models\\ClientProposal',3,3,1,'Proposal approved','CPR-202609-0001 was approved by Superadmin.','View Proposal','/page_proposals','{\"modal_fn\":\"openProposalModal\",\"modal_args\":[3]}',0,'2026-09-04 04:27:43','2026-09-04 04:27:43'),(11,'test.manual',NULL,NULL,3,1,'test','test',NULL,NULL,NULL,0,'2026-09-06 16:39:41','2026-09-06 16:39:41'),(12,'test.manual',NULL,NULL,1,1,'test','test',NULL,NULL,NULL,1,'2026-09-06 16:39:49','2026-09-06 16:40:18');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `options_table`
--

DROP TABLE IF EXISTS `options_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `options_table` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `options_table_option_name_unique` (`option_name`),
  KEY `options_table_option_name_index` (`option_name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `options_table`
--

LOCK TABLES `options_table` WRITE;
/*!40000 ALTER TABLE `options_table` DISABLE KEYS */;
INSERT INTO `options_table` VALUES (1,'Address Type',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(2,'Lead Source',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(3,'Type of Business',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(4,'Industry',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(5,'Type of Organization',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(6,'Client Category',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(7,'Client Classification',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(8,'Unit',NULL,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(9,'Cargo Type',NULL,'2026-09-01 10:58:29','2026-09-01 10:58:29');
/*!40000 ALTER TABLE `options_table` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'roles.manage','Manage roles & permissions','Roles','2026-08-12 13:23:27','2026-08-12 13:23:27'),(2,'booking.create','Create a booking','Booking','2026-08-12 13:23:27','2026-08-12 13:23:27'),(3,'booking.confirm','Confirm a booking','Booking','2026-08-12 13:23:27','2026-08-12 13:23:27'),(4,'booking.cancel','Cancel a booking','Booking','2026-08-12 13:23:27','2026-08-12 13:23:27'),(5,'booking.advance-status','Advance a booking\'s status','Booking','2026-08-12 13:23:27','2026-08-12 13:23:27'),(6,'booking.generate-dispatch-document','Generate a booking line\'s ATW/CAN','Booking','2026-08-12 13:23:27','2026-08-12 13:23:27'),(7,'booking.assign-cv','Assign ConVan/Proforma BL/Waybill/Seal to container unit','Booking','2026-08-12 13:23:27','2026-08-12 13:23:27'),(8,'booking.gate-scan','Scan a container in/out at gate (Pier Check-In)','Booking','2026-08-12 13:23:27','2026-08-12 13:23:27'),(9,'booking.issue-eir','Issue a container\'s EIR Out/In','Booking','2026-08-12 13:23:27','2026-08-12 13:23:27'),(10,'booking.assign-voyage','Assign or shut out container\'s vessel voyage','Booking','2026-08-12 13:23:27','2026-08-12 13:23:27'),(11,'booking.generate-loadlist','Generate a vessel voyage\'s loadlist','Booking','2026-08-12 13:23:27','2026-08-12 13:23:27'),(12,'contract.create','Create a contract from an accepted proposal','Contract','2026-08-12 13:23:27','2026-08-12 13:23:27'),(13,'contract.terminate','Terminate a contract','Contract','2026-08-12 13:23:27','2026-08-12 13:23:27');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `port_charges`
--

DROP TABLE IF EXISTS `port_charges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `port_charges` (
  `port_charge_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `port_id` bigint(20) unsigned NOT NULL,
  `charge_type_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `effective_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`port_charge_id`),
  UNIQUE KEY `port_charges_unique` (`port_id`,`charge_type_id`,`effective_date`),
  KEY `port_charges_charge_type_id_foreign` (`charge_type_id`),
  KEY `port_charges_port_id_is_active_index` (`port_id`,`is_active`),
  CONSTRAINT `port_charges_charge_type_id_foreign` FOREIGN KEY (`charge_type_id`) REFERENCES `charge_types` (`charge_type_id`) ON UPDATE CASCADE,
  CONSTRAINT `port_charges_port_id_foreign` FOREIGN KEY (`port_id`) REFERENCES `ports` (`port_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `port_charges`
--

LOCK TABLES `port_charges` WRITE;
/*!40000 ALTER TABLE `port_charges` DISABLE KEYS */;
INSERT INTO `port_charges` VALUES (1,3,1,500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:28','2026-08-13 05:17:28'),(2,3,2,800.00,'2026-07-27',NULL,1,'2026-08-13 05:17:28','2026-08-13 05:17:28'),(3,3,3,1200.00,'2026-07-27',NULL,1,'2026-08-13 05:17:28','2026-08-13 05:17:28'),(4,12,1,500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:28','2026-08-13 05:17:28'),(5,12,2,800.00,'2026-07-27',NULL,1,'2026-08-13 05:17:28','2026-08-13 05:17:28'),(6,12,3,1200.00,'2026-07-27',NULL,1,'2026-08-13 05:17:28','2026-08-13 05:17:28'),(7,18,1,500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:28','2026-08-13 05:17:28'),(8,18,2,800.00,'2026-07-27',NULL,1,'2026-08-13 05:17:28','2026-08-13 05:17:28'),(9,18,3,1200.00,'2026-07-27',NULL,1,'2026-08-13 05:17:28','2026-08-13 05:17:28'),(10,19,1,500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:28','2026-08-13 05:17:28'),(11,19,2,800.00,'2026-07-27',NULL,1,'2026-08-13 05:17:28','2026-08-13 05:17:28'),(12,19,3,1200.00,'2026-07-27',NULL,1,'2026-08-13 05:17:28','2026-08-13 05:17:28'),(13,20,1,500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:28','2026-08-13 05:17:28'),(14,20,2,800.00,'2026-07-27',NULL,1,'2026-08-13 05:17:28','2026-08-13 05:17:28'),(15,20,3,1200.00,'2026-07-27',NULL,1,'2026-08-13 05:17:28','2026-08-13 05:17:28'),(16,29,1,500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:28','2026-08-13 05:17:28'),(17,29,2,800.00,'2026-07-27',NULL,1,'2026-08-13 05:17:28','2026-08-13 05:17:28'),(18,29,3,1200.00,'2026-07-27',NULL,1,'2026-08-13 05:17:28','2026-08-13 05:17:28');
/*!40000 ALTER TABLE `port_charges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ports`
--

DROP TABLE IF EXISTS `ports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ports` (
  `port_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` bigint(20) unsigned NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`port_id`),
  KEY `ports_location_id_foreign` (`location_id`),
  CONSTRAINT `ports_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ports`
--

LOCK TABLES `ports` WRITE;
/*!40000 ALTER TABLE `ports` DISABLE KEYS */;
INSERT INTO `ports` VALUES (1,1,'Caticlan Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(2,1,'Dumaguit Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(3,2,'Banogo Power Plant Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(4,3,'Mariveles Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(5,3,'Orion Dockyard',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(6,4,'BIPI',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(7,4,'Frabelle, Bauan',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(8,4,'JGSP',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(9,4,'Kepco Ilijan Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(10,4,'San Juan Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(11,4,'Sem Calaca Power Plant',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(12,4,'Tabangao Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(13,5,'Legaspi Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(14,5,'Tabaco Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(15,6,'Port of Masao',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(16,7,'BBASI Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(17,7,'Gracia Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(18,7,'Oro Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(19,8,'KTC Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(20,9,'Sasa Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(21,9,'Tibungco Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(22,10,'Dinagat Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(23,11,'Poro, San Fernando',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(24,11,'Sto. Tomas Fish Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(25,12,'Liloan Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(26,12,'San Ricardo Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(27,13,'Harbour Center',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(28,13,'Navotas Fish Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(29,13,'North Harbour',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(30,13,'Vitas Pier 18',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(31,14,'Algimar Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(32,15,'Coron Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(33,15,'Port of Brooke\'s Point',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(34,15,'Puerto Princesa Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(35,15,'San Vicente Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(36,15,'Taytay Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(37,15,'Villa Marcelo, Puerto Princesa',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(38,16,'Sual Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(39,17,'Romblon Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(40,18,'Culasi Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(41,19,'Nonoc Island Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(42,19,'Surigao Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(43,20,'Tacloban Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(44,21,'Rivera Port, Subic',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(45,21,'Alpha Water Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(46,21,'Masinloc Power Partners Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(47,22,'Lamao Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11'),(48,22,'Liloy Port',NULL,NULL,1,'2026-08-13 05:17:11','2026-08-13 05:17:11');
/*!40000 ALTER TABLE `ports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proposal_status`
--

DROP TABLE IF EXISTS `proposal_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposal_status` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposal_status`
--

LOCK TABLES `proposal_status` WRITE;
/*!40000 ALTER TABLE `proposal_status` DISABLE KEYS */;
INSERT INTO `proposal_status` VALUES (1,'Pending',NULL,'2026-08-12 19:49:55'),(2,'Approved',NULL,'2026-08-12 19:49:55'),(3,'Disapproved',NULL,'2026-08-12 19:49:55'),(4,'Accepted',NULL,'2026-08-12 19:49:55'),(5,'Rejected',NULL,'2026-08-12 19:49:55'),(6,'On-Hold',NULL,'2026-08-12 19:49:55');
/*!40000 ALTER TABLE `proposal_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proposals`
--

DROP TABLE IF EXISTS `proposals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lead_id` bigint(20) unsigned NOT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `proposals_uuid_unique` (`uuid`),
  KEY `proposals_lead_id_foreign` (`lead_id`),
  KEY `proposals_created_by_foreign` (`created_by`),
  CONSTRAINT `proposals_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `proposals_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `crm_leads` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposals`
--

LOCK TABLES `proposals` WRITE;
/*!40000 ALTER TABLE `proposals` DISABLE KEYS */;
/*!40000 ALTER TABLE `proposals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proposals_rates`
--

DROP TABLE IF EXISTS `proposals_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposals_rates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_id` bigint(20) unsigned NOT NULL,
  `proposed_rate` int(11) NOT NULL,
  `rate_type` enum('fixed','percentage') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed',
  `route_from` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `route_to` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_van_qty` int(11) NOT NULL,
  `container_class` int(11) NOT NULL,
  `container_type` int(11) NOT NULL,
  `container_size` int(11) NOT NULL,
  `origin_service_type` int(11) NOT NULL,
  `destination_service_type` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `proposals_rates_proposal_id_foreign` (`proposal_id`),
  CONSTRAINT `proposals_rates_proposal_id_foreign` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposals_rates`
--

LOCK TABLES `proposals_rates` WRITE;
/*!40000 ALTER TABLE `proposals_rates` DISABLE KEYS */;
/*!40000 ALTER TABLE `proposals_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permission`
--

DROP TABLE IF EXISTS `role_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_permission` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) unsigned NOT NULL,
  `permission_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_permission_role_id_permission_id_unique` (`role_id`,`permission_id`),
  KEY `role_permission_permission_id_foreign` (`permission_id`),
  CONSTRAINT `role_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_permission_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `setting_role` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permission`
--

LOCK TABLES `role_permission` WRITE;
/*!40000 ALTER TABLE `role_permission` DISABLE KEYS */;
INSERT INTO `role_permission` VALUES (13,1,1),(6,1,2),(5,1,3),(4,1,4),(1,1,5),(8,1,6),(2,1,7),(7,1,8),(10,1,9),(3,1,10),(9,1,11),(11,1,12),(12,1,13),(65,4,1),(58,4,2),(57,4,3),(56,4,4),(53,4,5),(59,4,6),(54,4,7),(62,4,8),(61,4,9),(55,4,10),(60,4,11),(63,4,12),(64,4,13),(91,7,1),(133,7,2),(83,7,3),(82,7,4),(79,7,5),(85,7,6),(80,7,7),(88,7,8),(87,7,9),(81,7,10),(86,7,11),(89,7,12),(131,7,13),(104,8,1),(97,8,2),(96,8,3),(95,8,4),(92,8,5),(98,8,6),(93,8,7),(101,8,8),(100,8,9),(94,8,10),(99,8,11),(102,8,12),(103,8,13),(117,9,1),(110,9,2),(109,9,3),(108,9,4),(105,9,5),(111,9,6),(106,9,7),(114,9,8),(113,9,9),(107,9,10),(112,9,11),(115,9,12),(116,9,13),(130,10,1),(123,10,2),(122,10,3),(121,10,4),(118,10,5),(124,10,6),(119,10,7),(127,10,8),(126,10,9),(120,10,10),(125,10,11),(128,10,12),(129,10,13);
/*!40000 ALTER TABLE `role_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `routes`
--

DROP TABLE IF EXISTS `routes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `routes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `route` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `port` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `routes`
--

LOCK TABLES `routes` WRITE;
/*!40000 ALTER TABLE `routes` DISABLE KEYS */;
INSERT INTO `routes` VALUES (1,'BUTUAN','BUTUAN',NULL,'2026-08-12 19:49:55'),(2,'CEBU','CEBU',NULL,'2026-08-12 19:49:55'),(3,'CAGAYAN','CAGAYAN',NULL,'2026-08-12 19:49:55'),(4,'DAVAO','DAVAO',NULL,'2026-08-12 19:49:55'),(5,'DUMAGUETE','DUMAGUETE',NULL,'2026-08-12 19:49:55'),(6,'GEN SAN','GEN SAN',NULL,'2026-08-12 19:49:55'),(7,'ILIGAN','ILIGAN',NULL,'2026-08-12 19:49:55'),(8,'ILOILO','ILOILO',NULL,'2026-08-12 19:49:55'),(9,'OSAMIS','OSAMIS',NULL,'2026-08-12 19:49:55'),(10,'CORON','CORON',NULL,'2026-08-12 19:49:55'),(11,'ROXAS','ROXAS',NULL,'2026-08-12 19:49:55'),(12,'CATICLAN','CATICLAN',NULL,'2026-08-12 19:49:55'),(13,'ORMOC','ORMOC',NULL,'2026-08-12 19:49:55'),(14,'TAGBILARAN','TAGBILARAN',NULL,'2026-08-12 19:49:55'),(15,'TACLOBAN','TACLOBAN',NULL,'2026-08-12 19:49:55'),(16,'ZAMBOANGA','ZAMBOANGA',NULL,'2026-08-12 19:49:55'),(17,'PUERTO PRINCESSA','PUERTO PRINCESSA',NULL,'2026-08-12 19:49:55'),(18,'SURIGAO','SURIGAO',NULL,'2026-08-12 19:49:55'),(19,'COTABATO','COTABATO',NULL,'2026-08-12 19:49:55'),(20,'BATANGAS','BATANGAS',NULL,'2026-08-12 19:49:55'),(21,'MANILA','MANILA',NULL,'2026-08-12 19:49:55');
/*!40000 ALTER TABLE `routes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales_info`
--

DROP TABLE IF EXISTS `sales_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales_info` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned NOT NULL,
  `account_owner` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sales_info_company_id_foreign` (`company_id`),
  CONSTRAINT `sales_info_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `company_info_master` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales_info`
--

LOCK TABLES `sales_info` WRITE;
/*!40000 ALTER TABLE `sales_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `sales_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_type`
--

DROP TABLE IF EXISTS `service_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `service_type` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_type`
--

LOCK TABLES `service_type` WRITE;
/*!40000 ALTER TABLE `service_type` DISABLE KEYS */;
INSERT INTO `service_type` VALUES (1,'ORIGIN','DOOR',NULL,'2026-08-12 19:49:55'),(2,'ORIGIN','PIER-STUFFING',NULL,'2026-08-12 19:49:55'),(3,'ORIGIN','PIER-VANOUT',NULL,'2026-08-12 19:49:55'),(4,'DESTINATION','DOOR',NULL,'2026-08-12 19:49:55'),(5,'DESTINATION','PIER-STRIPPING',NULL,'2026-08-12 19:49:55'),(6,'DESTINATION','PIER-VAN OUT',NULL,'2026-08-12 19:49:55');
/*!40000 ALTER TABLE `service_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `serviceable_areas`
--

DROP TABLE IF EXISTS `serviceable_areas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `serviceable_areas` (
  `area_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` bigint(20) unsigned NOT NULL,
  `area_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`area_id`),
  UNIQUE KEY `serviceable_areas_location_id_area_name_unique` (`location_id`,`area_name`),
  CONSTRAINT `serviceable_areas_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=256 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `serviceable_areas`
--

LOCK TABLES `serviceable_areas` WRITE;
/*!40000 ALTER TABLE `serviceable_areas` DISABLE KEYS */;
INSERT INTO `serviceable_areas` VALUES (1,1,'Caticlan',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(2,1,'Dumaguit',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(3,1,'Kalibo',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(4,2,'Bacolod',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(5,2,'Banago',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(6,2,'Pulupandan',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(7,3,'Mariveles',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(8,3,'Orion',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(9,4,'Alaminos',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(10,4,'BIPI, Bauan',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(11,4,'Balagtas',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(12,4,'Balayan',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(13,4,'Banaybanay',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(14,4,'Batangas City',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(15,4,'Bauan',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(16,4,'Binan',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(17,4,'Cabuyao',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(18,4,'Calaca',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(19,4,'Calamba',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(20,4,'Calatagan',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(21,4,'Candelaria',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(22,4,'Canlubang',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(23,4,'Frabelle, Bauan',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(24,4,'Gulod',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(25,4,'Ibaan',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(26,4,'Ilijan',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(27,4,'Lemery',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(28,4,'Lian',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(29,4,'Lipa City',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(30,4,'Lipa-Malvar',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(31,4,'Los Banos',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(32,4,'Lubang',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(33,4,'Lucena',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(34,4,'Maapaz',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(35,4,'Mabini',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(36,4,'Mahabang Parang',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(37,4,'Malvar',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(38,4,'PNOC',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(39,4,'Padre Garcia',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(40,4,'Rosario',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(41,4,'Roxas',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(42,4,'San Jose',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(43,4,'San Juan',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(44,4,'San Pablo',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(45,4,'San Pascual',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(46,4,'San Pedro',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(47,4,'Sariaya',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(48,4,'Simlong',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(49,4,'Sorosoro',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(50,4,'Sta. Clara',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(51,4,'Sta. Rosa',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(52,4,'Sto. Tomas',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(53,4,'Tabangao',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(54,4,'Tabangao Port',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(55,4,'Talisay',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(56,4,'Tayabas',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(57,4,'Taysan',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(58,4,'Tiaong',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(59,5,'Albay',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(60,5,'Daet',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(61,5,'Legaspi',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(62,5,'Milaor',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(63,5,'Naga',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(64,5,'Sorsogon',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(65,5,'Tabaco City Port',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(66,6,'Butuan',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(67,6,'Masao',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(68,7,'BBASI Port',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(69,7,'Bulua',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(70,7,'CDO Bugo',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(71,7,'CDO Mitimco',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(72,7,'El Salvador',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(73,7,'Gracia Port',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(74,7,'Iligan City',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(75,7,'Malasag, Cugman',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(76,7,'Opol',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(77,7,'Tablon',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(78,7,'Villanueva',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(79,8,'Banilad',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(80,8,'Basak, Mandaue',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(81,8,'Canduman, Mandaue',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(82,8,'Cebu',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(83,8,'Compostela',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(84,8,'Consolacion',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(85,8,'Cordova',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(86,8,'Danao',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(87,8,'KTC Port',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(88,8,'Labangon',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(89,8,'Lapu-Lapu City',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(90,8,'Lilo-an',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(91,8,'Mabolo',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(92,8,'Malabuyoc',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(93,8,'Mambaling',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(94,8,'Mandaue',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(95,8,'Minglanilla',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(96,8,'Naga',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(97,8,'Opao, Mandaue',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(98,8,'Pagsabungan, Mandaue',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(99,8,'Pakna-an, Mandaue',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(100,8,'San Fernando',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(101,8,'Tabok, Mandaue',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(102,8,'Talisay',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(103,8,'Tawason, Mandaue',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(104,8,'Tayud, Consolacion',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(105,8,'Tingub, Mandaue',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(106,8,'Tipolo, Mandaue',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(107,9,'AO Panabo',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(108,9,'Agdao',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(109,9,'Buhangin',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(110,9,'Buhisan',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(111,9,'Bunawan',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(112,9,'Cagangohan, Panabo',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(113,9,'Coronan',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(114,9,'Darong',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(115,9,'Davao',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(116,9,'Davao Del Sur',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(117,9,'Digos',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(118,9,'General Santos',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(119,9,'KTC Port',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(120,9,'Kidapawan',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(121,9,'Lanang',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(122,9,'Lanao',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(123,9,'Lapu-Lapu',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(124,9,'Maa',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(125,9,'Malitbog, Panabo',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(126,9,'Manay, Panabo',1,'2026-08-13 05:17:15','2026-08-13 05:17:15'),(127,9,'Mati',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(128,9,'Matina',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(129,9,'Nanyo, Panabo',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(130,9,'Obrero',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(131,9,'Panabo',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(132,9,'Panacan',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(133,9,'Sasa',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(134,9,'South Cotabato',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(135,9,'Sto. Tomas',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(136,9,'Tagpore, Panabo',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(137,9,'Tagum',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(138,9,'Talomo',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(139,9,'Tibungco',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(140,9,'Toril',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(141,9,'Ulas',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(142,10,'Dinagat',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(143,11,'Bauang',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(144,11,'Cauayan',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(145,11,'La Trinidad',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(146,11,'Magsingal',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(147,11,'San Fernando',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(148,11,'Sto.Tomas',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(149,12,'Baybay',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(150,12,'Benit',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(151,12,'Carigara',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(152,12,'Maasin',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(153,12,'Ormoc',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(154,12,'Poblacion',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(155,12,'San Ricardo',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(156,13,'Angeles',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(157,13,'Antipolo',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(158,13,'Bacoor',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(159,13,'Bagong Ilog',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(160,13,'Balintawak',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(161,13,'Bicutan',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(162,13,'Cainta',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(163,13,'Caloocan',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(164,13,'Calumpit',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(165,13,'Capas',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(166,13,'Carmona',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(167,13,'Cavite',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(168,13,'Dasmarinas',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(169,13,'General Trias',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(170,13,'Guiguinto',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(171,13,'Harbour Center',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(172,13,'Imus',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(173,13,'Kawit',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(174,13,'Lagro',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(175,13,'Las Pinas',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(176,13,'Libis',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(177,13,'Mabalacat',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(178,13,'Makati',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(179,13,'Malabon',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(180,13,'Mandaluyong',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(181,13,'Manila',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(182,13,'Marikina',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(183,13,'Marilao',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(184,13,'Meycauayan',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(185,13,'Multipurpose Terminal',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(186,13,'Muntinlupa',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(187,13,'Navotas',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(188,13,'North Harbour',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(189,13,'Paco',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(190,13,'Paranaque',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(191,13,'Pasay',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(192,13,'Pasig',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(193,13,'Pinagbuhatan',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(194,13,'Pulilan',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(195,13,'Quezon City',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(196,13,'Rizal',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(197,13,'Rodriguez',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(198,13,'Rosario',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(199,13,'San Fernando',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(200,13,'San Ildefonso',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(201,13,'San Isidro',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(202,13,'San Juan',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(203,13,'San Rafael',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(204,13,'Silang',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(205,13,'Sta. Maria',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(206,13,'Sucat',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(207,13,'Taguig',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(208,13,'Tanza',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(209,13,'Taytay',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(210,13,'Valenzuela',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(211,13,'Vitas Pier 18',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(212,14,'Masbate',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(213,14,'Masbate Port',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(214,14,'Ticao',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(215,14,'Tugbo',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(216,15,'Balabac',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(217,15,'Bataraza',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(218,15,'Brooke\'s Point',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(219,15,'Coron',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(220,15,'El Nido',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(221,15,'Narra',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(222,15,'Pamalican',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(223,15,'Puerto Princesa',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(224,15,'Punta Baja, Rizal',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(225,15,'Quezon',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(226,15,'Ransang, Rizal',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(227,15,'Retayir Port',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(228,15,'Rio Tuba',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(229,15,'Rizal',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(230,15,'Roxas',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(231,15,'San Manuel',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(232,15,'San Vicente',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(233,15,'Taytay',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(234,16,'Lingayen',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(235,16,'Magalianes',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(236,16,'San Fernando',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(237,16,'San Nicolas',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(238,16,'Sual',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(239,17,'Romblon',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(240,18,'Dumangas',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(241,18,'Iloilo City',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(242,18,'Roxas City',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(243,18,'San Miguel',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(244,19,'Nonoc Island',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(245,19,'Surigao',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(246,20,'Calbayog',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(247,20,'Sta. Fe',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(248,20,'Tacloban',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(249,20,'Tacloban Port',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(250,20,'Tanauan',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(251,21,'Iba',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(252,21,'Masinloc',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(253,21,'Subic',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(254,22,'Lamao',1,'2026-08-13 05:17:16','2026-08-13 05:17:16'),(255,22,'Liloy',1,'2026-08-13 05:17:16','2026-08-13 05:17:16');
/*!40000 ALTER TABLE `serviceable_areas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services_info`
--

DROP TABLE IF EXISTS `services_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services_info` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned NOT NULL,
  `product` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `origin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destination` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `services_info_company_id_foreign` (`company_id`),
  CONSTRAINT `services_info_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `company_info_master` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services_info`
--

LOCK TABLES `services_info` WRITE;
/*!40000 ALTER TABLE `services_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `services_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('7y0zljNdHZ6UZ2UV9EWpXTR7guuCFSm6dVnqqixF',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZFN4VjNucnlESVJKUjYzczdCSDl6UGdzdEpCRDIyM3ZwdzZjWkNVVSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NjI6Imh0dHA6Ly9rYXJnYW1pbmVfcHJvdG90eXBlLnRlc3QvYXBpL25vdGlmaWNhdGlvbnMvdW5yZWFkLWNvdW50Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1788826771),('bYakvqZeMW4VsnHOrQ5DhS0VSaS9ah7ST2jIp5y7',6,'127.0.0.1','curl/8.12.1','YToyOntzOjY6Il90b2tlbiI7czo0MDoiV0NCZmU5aTF3UVBkTGJFaHZKWlpEc0M5bmZZbTl6ODFoUzhoUjBWTiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1788757168),('BZMm8b1jbhjxNGRu8mte2auQJek9AqgdVcimMnqZ',NULL,'127.0.0.1','curl/8.12.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoidnlBenF3QmdZa1JCeHNTYkJiTWo2YzhQU1YzM2NmNDl2NzF0elBOWSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9rYXJnYW1pbmVfcHJvdG90eXBlLnRlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1788255216),('CRLJzFcTWp3O3LLJVMfJpBX5T6slgUSITZ0qENeD',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNDh0UUVUZ0gycjZYdVBCSXNjaWxwdUJSMlpETE01N2JpcVhKNVRGeCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly9rYXJnYW1pbmVfcHJvdG90eXBlLnRlc3QvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjM6InVybCI7YToxOntzOjg6ImludGVuZGVkIjtzOjM1OiJodHRwOi8va2FyZ2FtaW5lX3Byb3RvdHlwZS50ZXN0L2FwcCI7fX0=',1788824731),('Kj1A0bcwwGHev4djySDUlzx2Qz2sqvFykVveKuzN',6,'127.0.0.1','curl/8.12.1','YToyOntzOjY6Il90b2tlbiI7czo0MDoiT2NKSWZOTVBFZkNMenlTcFdnRURZN2FjM3JHVXNhYjZwSUlvRFpyayI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1788757128),('LDR1kQLcoP0Rf4gbC4s4PdCD55HzIc5leAtbHsE0',6,'127.0.0.1','curl/8.12.1','YToyOntzOjY6Il90b2tlbiI7czo0MDoiYlp5amF5SW9ocm9NM201dGJ0d1hBYUk0MWlNczlmZVJYM1NWQ3p5VSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1788757151),('OxNFkgZ6z4kv6UV1kaYVNQedBhWLvMuckddCloSn',NULL,'127.0.0.1','curl/8.12.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoieGlBdXVFSmw1TW9jSzU4OExlZGwzOFo5YUxkTDZMSDBVUXd0SjVYYiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODEyMy9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1788629531),('PLenNaIfUMdTemoo16YyKJHIU1oILsepmgKuLBjb',NULL,'127.0.0.1','curl/8.12.1','YToyOntzOjY6Il90b2tlbiI7czo0MDoiMzZMRXlOOEZ3RW9ueWdLZGxpRXpzcHA4YXdFbU1BbW9XdXdPSkN0VyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1788757151),('SmOCe1MYxbzpJC62eXK1H8a9icquWl3M7yXmAlQy',NULL,'127.0.0.1','curl/8.12.1','YToyOntzOjY6Il90b2tlbiI7czo0MDoianl4THMwMG51aGRlQWk1WTFnWk9iVjZrbDNvMDkxeTVnVjRLS0FhRCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1788255216),('XVbeUY70PqQeoJBpOO4WAwulVvjcKwOtZe0dQO45',NULL,'127.0.0.1','curl/8.12.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoid3ZUV2l1TDZMOXhYWmFSNnZuN243dEY1RDA5SU9PTXlpQ3UzV2tlZyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODEyMy9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1788629529);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `setting_role`
--

DROP TABLE IF EXISTS `setting_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `setting_role` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_system` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `setting_role`
--

LOCK TABLES `setting_role` WRITE;
/*!40000 ALTER TABLE `setting_role` DISABLE KEYS */;
INSERT INTO `setting_role` VALUES (1,'superadmin',1),(4,'developer',1),(7,'Operation Staff',0),(8,'Customer Service Representative',0),(9,'Operations Manager',0),(10,'Relationship Manager',0);
/*!40000 ALTER TABLE `setting_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `special_charges`
--

DROP TABLE IF EXISTS `special_charges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `special_charges` (
  `special_charge_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_value` decimal(15,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`special_charge_id`),
  UNIQUE KEY `special_charges_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `special_charges`
--

LOCK TABLES `special_charges` WRITE;
/*!40000 ALTER TABLE `special_charges` DISABLE KEYS */;
INSERT INTO `special_charges` VALUES (1,'Amendment Fee',1500.00,1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(2,'Backhoe Rental',3500.00,1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(3,'Bullet Seal',1234567.50,1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(4,'Container Van Rental',5000.00,1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(5,'Crane Rental',8000.00,1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(6,'Demurrage',2000.00,1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(7,'Documentation Assistance Fee',1000.00,1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(8,'Double Handling Fee',2500.00,1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(9,'Driver Assistance Fee',800.00,1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(10,'Forklift Rental',3000.00,1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(11,'Foul Trip',1500.00,1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(12,'Genset Rental',2500.00,1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(13,'Hustling',1200.00,1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(14,'Inspection Fee',1000.00,1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(15,'Lashing Fee',1500.00,1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(16,'Lashing Materials',800.00,1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(17,'Lift On Lift Off',3500.00,1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(18,'Loader Rental',3000.00,1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(19,'Overweight Fee',2000.00,1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(20,'Port Charges',1500.00,1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(21,'Reimbursement',1000.00,1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(22,'Storage',500.00,1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(23,'Stripping',2500.00,1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(24,'Stuffing',2500.00,1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(25,'Trucking',3500.00,1,'2026-08-12 13:23:27','2026-08-12 13:23:27'),(26,'Valuation Fee',1000.00,1,'2026-08-12 13:23:27','2026-08-12 13:23:27');
/*!40000 ALTER TABLE `special_charges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stages_info`
--

DROP TABLE IF EXISTS `stages_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stages_info` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned NOT NULL,
  `stage` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proposal_requested_date` date DEFAULT NULL,
  `proposal_submitted_date` date DEFAULT NULL,
  `negotiation_date` date DEFAULT NULL,
  `won_awarded_date` date DEFAULT NULL,
  `lost_closed_date` date DEFAULT NULL,
  `monthly_sales_forecast` decimal(15,2) DEFAULT NULL,
  `forecast_transaction_month` date DEFAULT NULL,
  `potential_volume_month` int(11) DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stages_info_company_id_foreign` (`company_id`),
  CONSTRAINT `stages_info_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `company_info_master` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stages_info`
--

LOCK TABLES `stages_info` WRITE;
/*!40000 ALTER TABLE `stages_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `stages_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teams` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teams_parent_id_foreign` (`parent_id`),
  CONSTRAINT `teams_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `teams` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teams`
--

LOCK TABLES `teams` WRITE;
/*!40000 ALTER TABLE `teams` DISABLE KEYS */;
INSERT INTO `teams` VALUES (2,'Operations',NULL,NULL,1,'2026-08-22 17:47:15','2026-08-22 17:47:15'),(3,'Sales',NULL,2,1,'2026-08-25 08:06:31','2026-08-25 08:06:31'),(4,'Dispatch Team',NULL,2,1,'2026-08-25 08:06:45','2026-08-25 08:06:45'),(5,'Sales Team 1',NULL,3,1,'2026-08-25 08:07:07','2026-08-25 08:07:07'),(6,'Sales Team 2',NULL,3,1,'2026-08-25 08:07:16','2026-08-25 08:07:16');
/*!40000 ALTER TABLE `teams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trade_references`
--

DROP TABLE IF EXISTS `trade_references`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trade_references` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned NOT NULL,
  `business_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `relationship` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trade_references_company_id_foreign` (`company_id`),
  CONSTRAINT `trade_references_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `company_info_master` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trade_references`
--

LOCK TABLES `trade_references` WRITE;
/*!40000 ALTER TABLE `trade_references` DISABLE KEYS */;
/*!40000 ALTER TABLE `trade_references` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trucking_tariffs`
--

DROP TABLE IF EXISTS `trucking_tariffs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trucking_tariffs` (
  `trucking_tariff_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `area_id` bigint(20) unsigned NOT NULL,
  `delivery_type_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `effective_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`trucking_tariff_id`),
  UNIQUE KEY `trucking_tariffs_unique` (`area_id`,`delivery_type_id`,`effective_date`),
  KEY `trucking_tariffs_delivery_type_id_foreign` (`delivery_type_id`),
  KEY `trucking_tariffs_area_id_is_active_index` (`area_id`,`is_active`),
  CONSTRAINT `trucking_tariffs_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `serviceable_areas` (`area_id`) ON UPDATE CASCADE,
  CONSTRAINT `trucking_tariffs_delivery_type_id_foreign` FOREIGN KEY (`delivery_type_id`) REFERENCES `delivery_types` (`delivery_type_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=550 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trucking_tariffs`
--

LOCK TABLES `trucking_tariffs` WRITE;
/*!40000 ALTER TABLE `trucking_tariffs` DISABLE KEYS */;
INSERT INTO `trucking_tariffs` VALUES (1,4,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(2,4,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(3,4,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(4,5,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(5,5,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(6,5,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(7,6,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(8,6,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(9,6,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(10,9,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(11,9,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(12,9,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(13,10,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(14,10,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(15,10,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(16,11,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(17,11,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(18,11,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(19,12,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(20,12,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(21,12,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(22,13,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(23,13,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(24,13,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(25,14,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(26,14,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(27,14,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(28,15,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(29,15,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(30,15,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(31,16,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(32,16,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(33,16,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(34,17,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(35,17,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(36,17,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(37,18,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(38,18,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(39,18,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(40,19,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(41,19,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(42,19,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(43,20,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(44,20,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(45,20,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(46,21,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(47,21,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(48,21,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(49,22,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(50,22,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(51,22,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(52,23,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(53,23,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(54,23,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(55,24,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(56,24,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(57,24,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(58,25,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(59,25,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(60,25,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(61,26,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(62,26,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(63,26,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(64,27,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(65,27,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(66,27,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(67,28,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(68,28,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(69,28,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(70,29,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(71,29,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(72,29,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(73,30,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(74,30,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(75,30,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(76,31,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(77,31,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(78,31,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(79,32,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(80,32,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(81,32,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(82,33,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(83,33,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(84,33,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(85,34,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(86,34,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(87,34,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(88,35,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(89,35,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(90,35,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(91,36,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(92,36,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(93,36,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(94,37,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(95,37,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(96,37,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(97,38,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(98,38,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(99,38,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(100,39,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(101,39,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(102,39,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(103,40,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(104,40,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(105,40,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(106,41,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(107,41,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(108,41,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(109,42,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(110,42,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(111,42,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(112,43,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(113,43,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(114,43,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(115,44,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(116,44,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(117,44,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(118,45,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(119,45,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(120,45,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(121,46,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(122,46,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(123,46,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(124,47,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(125,47,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(126,47,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(127,48,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(128,48,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(129,48,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(130,49,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(131,49,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(132,49,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(133,50,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(134,50,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(135,50,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(136,51,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(137,51,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(138,51,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(139,52,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(140,52,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(141,52,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(142,53,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(143,53,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(144,53,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(145,54,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(146,54,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(147,54,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(148,55,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(149,55,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(150,55,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(151,56,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(152,56,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(153,56,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(154,57,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(155,57,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(156,57,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(157,58,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(158,58,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(159,58,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(160,68,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(161,68,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(162,68,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(163,69,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(164,69,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(165,69,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(166,70,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(167,70,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(168,70,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(169,71,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(170,71,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(171,71,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(172,72,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(173,72,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(174,72,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(175,73,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(176,73,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(177,73,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(178,74,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(179,74,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(180,74,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(181,75,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(182,75,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(183,75,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(184,76,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(185,76,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(186,76,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(187,77,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(188,77,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(189,77,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(190,78,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(191,78,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(192,78,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(193,79,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(194,79,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(195,79,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(196,80,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(197,80,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(198,80,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(199,81,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(200,81,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(201,81,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(202,82,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(203,82,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(204,82,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(205,83,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(206,83,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(207,83,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(208,84,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(209,84,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(210,84,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(211,85,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(212,85,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(213,85,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(214,86,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(215,86,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(216,86,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(217,87,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(218,87,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(219,87,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(220,88,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(221,88,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(222,88,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(223,89,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(224,89,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(225,89,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(226,90,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(227,90,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(228,90,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(229,91,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(230,91,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(231,91,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(232,92,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(233,92,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(234,92,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(235,93,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(236,93,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(237,93,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(238,94,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(239,94,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(240,94,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(241,95,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(242,95,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(243,95,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(244,96,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(245,96,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(246,96,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(247,97,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(248,97,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(249,97,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(250,98,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(251,98,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(252,98,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(253,99,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(254,99,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(255,99,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(256,100,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(257,100,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(258,100,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(259,101,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(260,101,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(261,101,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(262,102,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(263,102,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(264,102,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(265,103,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(266,103,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(267,103,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(268,104,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(269,104,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(270,104,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(271,105,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(272,105,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(273,105,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(274,106,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(275,106,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(276,106,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(277,107,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(278,107,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(279,107,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(280,108,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(281,108,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(282,108,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(283,109,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(284,109,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(285,109,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(286,110,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(287,110,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(288,110,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(289,111,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(290,111,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(291,111,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(292,112,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(293,112,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(294,112,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(295,113,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(296,113,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(297,113,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(298,114,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(299,114,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(300,114,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(301,115,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(302,115,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(303,115,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(304,116,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(305,116,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(306,116,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(307,117,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(308,117,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(309,117,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(310,118,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(311,118,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(312,118,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(313,119,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(314,119,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(315,119,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(316,120,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(317,120,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(318,120,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(319,121,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(320,121,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(321,121,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(322,122,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(323,122,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(324,122,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(325,123,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(326,123,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(327,123,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(328,124,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(329,124,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(330,124,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(331,125,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(332,125,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(333,125,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(334,126,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(335,126,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(336,126,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(337,127,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(338,127,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(339,127,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(340,128,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(341,128,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(342,128,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(343,129,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(344,129,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(345,129,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(346,130,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(347,130,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(348,130,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(349,131,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:36','2026-08-13 05:17:36'),(350,131,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(351,131,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(352,132,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(353,132,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(354,132,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(355,133,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(356,133,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(357,133,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(358,134,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(359,134,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(360,134,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(361,135,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(362,135,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(363,135,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(364,136,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(365,136,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(366,136,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(367,137,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(368,137,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(369,137,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(370,138,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(371,138,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(372,138,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(373,139,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(374,139,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(375,139,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(376,140,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(377,140,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(378,140,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(379,141,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(380,141,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(381,141,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(382,156,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(383,156,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(384,156,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(385,157,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(386,157,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(387,157,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(388,158,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(389,158,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(390,158,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(391,159,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(392,159,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(393,159,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(394,160,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(395,160,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(396,160,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(397,161,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(398,161,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(399,161,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(400,162,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(401,162,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(402,162,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(403,163,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(404,163,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(405,163,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(406,164,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(407,164,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(408,164,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(409,165,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(410,165,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(411,165,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(412,166,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(413,166,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(414,166,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(415,167,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(416,167,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(417,167,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(418,168,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(419,168,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(420,168,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(421,169,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(422,169,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(423,169,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(424,170,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(425,170,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(426,170,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(427,171,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(428,171,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(429,171,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(430,172,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(431,172,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(432,172,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(433,173,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(434,173,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(435,173,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(436,174,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(437,174,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(438,174,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(439,175,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(440,175,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(441,175,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(442,176,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(443,176,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(444,176,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(445,177,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(446,177,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(447,177,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(448,178,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(449,178,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(450,178,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(451,179,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(452,179,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(453,179,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(454,180,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(455,180,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(456,180,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(457,181,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(458,181,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(459,181,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(460,182,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(461,182,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(462,182,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(463,183,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(464,183,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(465,183,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(466,184,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(467,184,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(468,184,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(469,185,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(470,185,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(471,185,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(472,186,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(473,186,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(474,186,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(475,187,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(476,187,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(477,187,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(478,188,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(479,188,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(480,188,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(481,189,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(482,189,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(483,189,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(484,190,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(485,190,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(486,190,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(487,191,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(488,191,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(489,191,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(490,192,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(491,192,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(492,192,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(493,193,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(494,193,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(495,193,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(496,194,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(497,194,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(498,194,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(499,195,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(500,195,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(501,195,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(502,196,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(503,196,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(504,196,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(505,197,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(506,197,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(507,197,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(508,198,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(509,198,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(510,198,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(511,199,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(512,199,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(513,199,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(514,200,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(515,200,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(516,200,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(517,201,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(518,201,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(519,201,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(520,202,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(521,202,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(522,202,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(523,203,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(524,203,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(525,203,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(526,204,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(527,204,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(528,204,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(529,205,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(530,205,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(531,205,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(532,206,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(533,206,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(534,206,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(535,207,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(536,207,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(537,207,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(538,208,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(539,208,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(540,208,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(541,209,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(542,209,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(543,209,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(544,210,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(545,210,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(546,210,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(547,211,1,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(548,211,2,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37'),(549,211,3,1500.00,'2026-07-27',NULL,1,'2026-08-13 05:17:37','2026-08-13 05:17:37');
/*!40000 ALTER TABLE `trucking_tariffs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_department`
--

DROP TABLE IF EXISTS `user_department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_department` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_department`
--

LOCK TABLES `user_department` WRITE;
/*!40000 ALTER TABLE `user_department` DISABLE KEYS */;
INSERT INTO `user_department` VALUES (1,'Sales Department','2026-08-12 13:23:27','2026-08-12 13:23:27'),(2,'Operations Department','2026-08-12 13:23:27','2026-08-12 13:23:27');
/*!40000 ALTER TABLE `user_department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_status`
--

DROP TABLE IF EXISTS `user_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_status` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_status`
--

LOCK TABLES `user_status` WRITE;
/*!40000 ALTER TABLE `user_status` DISABLE KEYS */;
INSERT INTO `user_status` VALUES (1,'Active','2026-08-12 13:23:27','2026-08-12 13:23:27'),(2,'Inactive','2026-08-12 13:23:27','2026-08-12 13:23:27'),(3,'Suspended','2026-08-12 13:23:27','2026-08-12 13:23:27'),(4,'Pending','2026-08-12 13:23:27','2026-08-12 13:23:27');
/*!40000 ALTER TABLE `user_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userconfig_table`
--

DROP TABLE IF EXISTS `userconfig_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userconfig_table` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `designation` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `approval_type` enum('routing','pre-approval','final-approval') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userconfig_table`
--

LOCK TABLES `userconfig_table` WRITE;
/*!40000 ALTER TABLE `userconfig_table` DISABLE KEYS */;
/*!40000 ALTER TABLE `userconfig_table` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_photo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nav_layout` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'side',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `must_change_password` tinyint(1) NOT NULL DEFAULT 0,
  `department_id` int(11) DEFAULT NULL,
  `role_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `team_id` bigint(20) unsigned DEFAULT NULL,
  `is_team_leader` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_team_id_foreign` (`team_id`),
  CONSTRAINT `users_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Superadmin','superadmin@email.com',NULL,'top',NULL,'$2y$12$WLNOrm.WIUeMqRtLYS8IGe8QBAY6aebEeMF8m8v8gOx50L/pNS7R6',0,NULL,'1',0,NULL,NULL,'2026-08-12 13:23:26','2026-09-07 23:48:22',NULL,0),(2,'Credit Officer','creditofficer@email.com',NULL,'side',NULL,'$2y$12$v.C6mumC2SzzI3YaC6U0dOL4xfMOCJQJ24rGiR9uoQ4KqhSufhAJW',0,NULL,'5',0,NULL,NULL,'2026-08-12 13:23:26','2026-08-12 13:23:26',NULL,0),(3,'Minton Diaz','minton.diaz@email.com',NULL,'side',NULL,'$2y$12$0qe2jj5vJPEknm/HBaKahuyOiFwPcSu6CaWgj5A4mZ64j8H7fdAv2',0,NULL,'7',0,NULL,NULL,'2026-08-12 13:23:26','2026-09-02 20:52:37',2,0),(4,'Fritzie Tangan','fritzie.tangan@kargamine.com.ph',NULL,'side',NULL,'$2y$12$PHKK2tD0/kqThlh/MXAUT.52eRungvfaw7v4nuAaG9feGImOfWCv6',0,NULL,'7',0,NULL,NULL,'2026-08-12 13:23:26','2026-09-06 16:38:17',2,1),(5,'Eden Palma','eden.palma@karga-container.com',NULL,'side',NULL,'$2y$12$mV/Q9Gk00R8BfR2YJ9zzZ.CyCZWtcrpwu6.KOwYPiQNnFDNdUwnva',0,NULL,'9',0,'C7Zs286fwGAjIGCVVRviNTS4gb5aNKJw3BdN7cKCkOWawaVkXNzfZcVOdA3A',NULL,'2026-08-12 13:23:27','2026-08-25 08:07:56',2,1),(6,'Device Integrations','device-integrations@kargamine.internal',NULL,'side',NULL,'$2y$12$rEIyhV5hyB58KKXdpfjaw.X5yqyyXoPmpGKnaqDeq0/gN0E/Smt9K',0,NULL,NULL,0,NULL,NULL,'2026-09-07 04:58:22','2026-09-07 04:58:22',NULL,0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `van_class`
--

DROP TABLE IF EXISTS `van_class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `van_class` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `class` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `van_class`
--

LOCK TABLES `van_class` WRITE;
/*!40000 ALTER TABLE `van_class` DISABLE KEYS */;
INSERT INTO `van_class` VALUES (1,'A',NULL,NULL),(2,'B',NULL,NULL),(3,'C',NULL,NULL),(4,'D',NULL,NULL);
/*!40000 ALTER TABLE `van_class` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `van_size`
--

DROP TABLE IF EXISTS `van_size`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `van_size` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `size` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `van_size`
--

LOCK TABLES `van_size` WRITE;
/*!40000 ALTER TABLE `van_size` DISABLE KEYS */;
INSERT INTO `van_size` VALUES (1,'10-FOOTER',NULL,NULL),(2,'20-FOOTER',NULL,NULL),(3,'40-FOOTER STD',NULL,NULL),(4,'40-FOOTER HC',NULL,NULL);
/*!40000 ALTER TABLE `van_size` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `van_type`
--

DROP TABLE IF EXISTS `van_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `van_type` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `van_type`
--

LOCK TABLES `van_type` WRITE;
/*!40000 ALTER TABLE `van_type` DISABLE KEYS */;
INSERT INTO `van_type` VALUES (1,'DRY VAN/CON VAN',NULL,NULL),(2,'FLATRACK (PLATFORM)',NULL,NULL),(3,'REEFER',NULL,NULL),(4,'HIGH CUBE',NULL,NULL),(5,'CATTLE VAN',NULL,NULL),(6,'TANK (ISO TANK)',NULL,NULL),(7,'ROLLING CARGO',NULL,NULL),(8,'SPECIAL CONTAINERS',NULL,NULL),(9,'OPEN-TOP VAN',NULL,NULL);
/*!40000 ALTER TABLE `van_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vat_rates`
--

DROP TABLE IF EXISTS `vat_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vat_rates` (
  `vat_rate_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `rate_percent` decimal(5,2) NOT NULL,
  `effective_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tax_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`vat_rate_id`),
  UNIQUE KEY `vat_rates_tax_type_effective_date_unique` (`tax_type`,`effective_date`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vat_rates`
--

LOCK TABLES `vat_rates` WRITE;
/*!40000 ALTER TABLE `vat_rates` DISABLE KEYS */;
INSERT INTO `vat_rates` VALUES (1,20.00,'2026-07-02',NULL,1,'2026-08-12 13:23:28','2026-08-12 13:23:28','General'),(2,12.00,'2026-07-27',NULL,1,'2026-08-12 13:23:28','2026-08-12 13:23:28','General'),(3,12.00,'2026-08-05',NULL,1,'2026-08-12 13:23:28','2026-08-12 13:23:28','General'),(4,12.00,'2026-08-06',NULL,1,'2026-08-12 13:23:28','2026-08-12 13:23:28','VAT Inclusive'),(5,0.00,'2026-08-06',NULL,1,'2026-08-12 13:23:28','2026-08-12 13:23:28','VAT Exempt'),(6,3.00,'2026-08-06',NULL,1,'2026-08-12 13:23:28','2026-08-12 13:23:28','Non-VAT');
/*!40000 ALTER TABLE `vat_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vessel_maintenance_records`
--

DROP TABLE IF EXISTS `vessel_maintenance_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vessel_maintenance_records` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `vessel_id` bigint(20) unsigned NOT NULL,
  `maintenance_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `performed_at` date NOT NULL,
  `completed_at` date DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `performed_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cost` decimal(12,2) DEFAULT NULL,
  `next_due_at` date DEFAULT NULL,
  `recorded_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vessel_maintenance_records_recorded_by_foreign` (`recorded_by`),
  KEY `vessel_maint_rec_vessel_performed_idx` (`vessel_id`,`performed_at`),
  CONSTRAINT `vessel_maintenance_records_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `vessel_maintenance_records_vessel_id_foreign` FOREIGN KEY (`vessel_id`) REFERENCES `vessels` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vessel_maintenance_records`
--

LOCK TABLES `vessel_maintenance_records` WRITE;
/*!40000 ALTER TABLE `vessel_maintenance_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `vessel_maintenance_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vessel_status_histories`
--

DROP TABLE IF EXISTS `vessel_status_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vessel_status_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `vessel_id` bigint(20) unsigned NOT NULL,
  `from_status` tinyint(3) unsigned DEFAULT NULL,
  `to_status` tinyint(3) unsigned NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recorded_by` bigint(20) unsigned DEFAULT NULL,
  `recorded_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vessel_status_histories_recorded_by_foreign` (`recorded_by`),
  KEY `vessel_status_hist_vessel_recorded_idx` (`vessel_id`,`recorded_at`),
  CONSTRAINT `vessel_status_histories_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `vessel_status_histories_vessel_id_foreign` FOREIGN KEY (`vessel_id`) REFERENCES `vessels` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vessel_status_histories`
--

LOCK TABLES `vessel_status_histories` WRITE;
/*!40000 ALTER TABLE `vessel_status_histories` DISABLE KEYS */;
INSERT INTO `vessel_status_histories` VALUES (1,1,NULL,1,'Vessel added to fleet.',1,'2026-08-22 17:49:20','2026-08-22 17:49:20','2026-08-22 17:49:20');
/*!40000 ALTER TABLE `vessel_status_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vessel_voyages`
--

DROP TABLE IF EXISTS `vessel_voyages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vessel_voyages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `vessel_id` bigint(20) unsigned DEFAULT NULL,
  `voyage_mnemonic` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `voyage_leg` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `origin_port_id` bigint(20) unsigned NOT NULL,
  `destination_port_id` bigint(20) unsigned NOT NULL,
  `estimated_departure_at` timestamp NULL DEFAULT NULL,
  `estimated_arrival_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vessel_voyages_voyage_mnemonic_unique` (`voyage_mnemonic`),
  KEY `vessel_voyages_origin_port_id_foreign` (`origin_port_id`),
  KEY `vessel_voyages_destination_port_id_foreign` (`destination_port_id`),
  KEY `vessel_voyages_vessel_id_foreign` (`vessel_id`),
  CONSTRAINT `vessel_voyages_destination_port_id_foreign` FOREIGN KEY (`destination_port_id`) REFERENCES `ports` (`port_id`),
  CONSTRAINT `vessel_voyages_origin_port_id_foreign` FOREIGN KEY (`origin_port_id`) REFERENCES `ports` (`port_id`),
  CONSTRAINT `vessel_voyages_vessel_id_foreign` FOREIGN KEY (`vessel_id`) REFERENCES `vessels` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vessel_voyages`
--

LOCK TABLES `vessel_voyages` WRITE;
/*!40000 ALTER TABLE `vessel_voyages` DISABLE KEYS */;
INSERT INTO `vessel_voyages` VALUES (1,1,'Lady Callista 84-B','B',31,45,'2026-08-26 01:00:00','2026-08-26 07:00:00','2026-08-23 05:20:04','2026-08-23 05:20:04'),(2,1,'Lady Callista 84-A','A',6,31,'2026-08-25 00:00:00','2026-08-25 06:00:00','2026-08-23 05:20:04','2026-08-23 05:20:04'),(3,1,'Lady Callista 84-C','C',7,32,NULL,NULL,'2026-08-25 07:31:25','2026-08-25 07:31:25');
/*!40000 ALTER TABLE `vessel_voyages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vessels`
--

DROP TABLE IF EXISTS `vessels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vessels` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vessel_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vessel_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `imo_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `call_sign` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mmsi_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `flag_state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `engine_count` int(10) unsigned DEFAULT NULL,
  `engine_power_hp` decimal(10,2) DEFAULT NULL,
  `date_manufactured` date DEFAULT NULL,
  `gross_tonnage` decimal(12,2) DEFAULT NULL,
  `deadweight_tonnage` decimal(12,2) DEFAULT NULL,
  `capacity_teu` int(10) unsigned DEFAULT NULL,
  `length_overall_m` decimal(8,2) DEFAULT NULL,
  `beam_m` decimal(8,2) DEFAULT NULL,
  `draft_m` decimal(8,2) DEFAULT NULL,
  `max_speed_knots` decimal(6,2) DEFAULT NULL,
  `home_port_id` bigint(20) unsigned DEFAULT NULL,
  `owner_operator` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `classification_society` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vessels_vessel_code_unique` (`vessel_code`),
  KEY `vessels_home_port_id_foreign` (`home_port_id`),
  CONSTRAINT `vessels_home_port_id_foreign` FOREIGN KEY (`home_port_id`) REFERENCES `ports` (`port_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vessels`
--

LOCK TABLES `vessels` WRITE;
/*!40000 ALTER TABLE `vessels` DISABLE KEYS */;
INSERT INTO `vessels` VALUES (1,'Lady Callista 84',NULL,'Container Ship',NULL,NULL,NULL,NULL,1,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,6,NULL,NULL,NULL,'2026-08-22 17:49:20','2026-08-22 17:49:20');
/*!40000 ALTER TABLE `vessels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'kargaminedb'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-08  8:19:44

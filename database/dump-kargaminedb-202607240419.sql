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
INSERT INTO `app_theme_settings` VALUES (1,'orange','sky','red','red','light','2026-07-22 17:18:29','2026-07-22 21:03:51');
/*!40000 ALTER TABLE `app_theme_settings` ENABLE KEYS */;
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
-- Table structure for table `booking_port_charges`
--

DROP TABLE IF EXISTS `booking_port_charges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_port_charges` (
  `booking_port_charge_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) unsigned NOT NULL,
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
  CONSTRAINT `booking_port_charges_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `booking_port_charges_charge_type_id_foreign` FOREIGN KEY (`charge_type_id`) REFERENCES `charge_types` (`charge_type_id`) ON UPDATE CASCADE,
  CONSTRAINT `booking_port_charges_port_id_foreign` FOREIGN KEY (`port_id`) REFERENCES `ports` (`port_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_port_charges`
--

LOCK TABLES `booking_port_charges` WRITE;
/*!40000 ALTER TABLE `booking_port_charges` DISABLE KEYS */;
/*!40000 ALTER TABLE `booking_port_charges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bookings` (
  `booking_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lane_id` bigint(20) unsigned NOT NULL,
  `origin_area_id` bigint(20) unsigned NOT NULL,
  `destination_area_id` bigint(20) unsigned NOT NULL,
  `delivery_type_id` bigint(20) unsigned NOT NULL,
  `tariff_rate_id` bigint(20) unsigned NOT NULL,
  `vat_rate_id` bigint(20) unsigned NOT NULL,
  `contract_id` bigint(20) unsigned DEFAULT NULL,
  `contract_rate_id` bigint(20) unsigned DEFAULT NULL,
  `frt_snapshot` decimal(12,2) NOT NULL DEFAULT 0.00,
  `bsc_snapshot` decimal(12,2) NOT NULL DEFAULT 0.00,
  `ra_snapshot` decimal(12,2) NOT NULL DEFAULT 0.00,
  `gri_snapshot` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_type_snapshot` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_value_snapshot` decimal(12,2) NOT NULL DEFAULT 0.00,
  `frt_after_discount_snapshot` decimal(12,2) NOT NULL DEFAULT 0.00,
  `art_snapshot` decimal(12,2) NOT NULL DEFAULT 0.00,
  `trucking_snapshot` decimal(12,2) NOT NULL DEFAULT 0.00,
  `vat_amount_snapshot` decimal(12,2) NOT NULL DEFAULT 0.00,
  `grand_total_snapshot` decimal(12,2) NOT NULL DEFAULT 0.00,
  `booking_date` date NOT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`booking_id`),
  KEY `bookings_origin_area_id_foreign` (`origin_area_id`),
  KEY `bookings_destination_area_id_foreign` (`destination_area_id`),
  KEY `bookings_delivery_type_id_foreign` (`delivery_type_id`),
  KEY `bookings_tariff_rate_id_foreign` (`tariff_rate_id`),
  KEY `bookings_vat_rate_id_foreign` (`vat_rate_id`),
  KEY `bookings_contract_id_foreign` (`contract_id`),
  KEY `bookings_contract_rate_id_foreign` (`contract_rate_id`),
  KEY `bookings_created_by_foreign` (`created_by`),
  KEY `bookings_lane_id_booking_date_index` (`lane_id`,`booking_date`),
  CONSTRAINT `bookings_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`),
  CONSTRAINT `bookings_contract_rate_id_foreign` FOREIGN KEY (`contract_rate_id`) REFERENCES `contract_rates` (`id`),
  CONSTRAINT `bookings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bookings_delivery_type_id_foreign` FOREIGN KEY (`delivery_type_id`) REFERENCES `delivery_types` (`delivery_type_id`) ON UPDATE CASCADE,
  CONSTRAINT `bookings_destination_area_id_foreign` FOREIGN KEY (`destination_area_id`) REFERENCES `serviceable_areas` (`area_id`) ON UPDATE CASCADE,
  CONSTRAINT `bookings_lane_id_foreign` FOREIGN KEY (`lane_id`) REFERENCES `lanes` (`lane_id`) ON UPDATE CASCADE,
  CONSTRAINT `bookings_origin_area_id_foreign` FOREIGN KEY (`origin_area_id`) REFERENCES `serviceable_areas` (`area_id`) ON UPDATE CASCADE,
  CONSTRAINT `bookings_tariff_rate_id_foreign` FOREIGN KEY (`tariff_rate_id`) REFERENCES `lane_tariff_rates` (`rate_id`) ON UPDATE CASCADE,
  CONSTRAINT `bookings_vat_rate_id_foreign` FOREIGN KEY (`vat_rate_id`) REFERENCES `vat_rates` (`vat_rate_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
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
INSERT INTO `cache` VALUES ('management_system_cache_a3affa0d1e1a3c72b78aa984c3367a05','i:29;',1784837495),('management_system_cache_a3affa0d1e1a3c72b78aa984c3367a05:timer','i:1784837495;',1784837495),('management_system_cache_f1f70ec40aaa556905d4a030501c0ba4','i:11;',1784140198),('management_system_cache_f1f70ec40aaa556905d4a030501c0ba4:timer','i:1784140198;',1784140198);
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `charge_types`
--

LOCK TABLES `charge_types` WRITE;
/*!40000 ALTER TABLE `charge_types` DISABLE KEYS */;
INSERT INTO `charge_types` VALUES (1,'DOC_STAMP','Port Doc Stamp','PORT',1,'2026-07-05 16:55:20','2026-07-05 16:55:20'),(2,'GATE_FEE','Port Gate Fee','PORT',1,'2026-07-05 17:26:08','2026-07-05 17:26:08'),(3,'BSC','Bunker Surcharge','GENERAL',1,'2026-07-07 04:38:34','2026-07-08 15:20:15'),(4,'test','test','GENERAL',1,'2026-07-08 15:20:08','2026-07-08 15:20:08');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_addresses`
--

LOCK TABLES `client_addresses` WRITE;
/*!40000 ALTER TABLE `client_addresses` DISABLE KEYS */;
INSERT INTO `client_addresses` VALUES (1,12,NULL,1,NULL,NULL,'awdasdasdasdd',NULL,NULL,NULL,'Philippines',NULL,'2026-07-21 21:05:33','2026-07-21 21:05:33'),(2,14,'Branch',1,'12','12','12',NULL,NULL,'Abra','Philippines','123123','2026-07-21 21:06:05','2026-07-21 21:06:05'),(3,27,'Warehouse',1,'test','test','test','Poblacion 3','Buenavista','Agusan del Norte','Philippines','test','2026-07-21 21:11:06','2026-07-21 21:11:06');
/*!40000 ALTER TABLE `client_addresses` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_billing`
--

LOCK TABLES `client_billing` WRITE;
/*!40000 ALTER TABLE `client_billing` DISABLE KEYS */;
INSERT INTO `client_billing` VALUES (9,12,'asdadadasd','asdasd','asdadsa','1231231232','2026-07-21 16:55:31','2026-07-21 16:55:31'),(10,14,'qweqwe','qweqew','qweqwe','123123','2026-07-21 21:06:40','2026-07-21 21:06:40'),(11,27,'qweqqwe','qweqew','qweqwe','123123','2026-07-21 21:11:41','2026-07-21 21:11:41');
/*!40000 ALTER TABLE `client_billing` ENABLE KEYS */;
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
  `contact_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_number_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_email_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_contacts_client_id_foreign` (`client_id`),
  CONSTRAINT `client_contacts_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `client_masters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_contacts`
--

LOCK TABLES `client_contacts` WRITE;
/*!40000 ALTER TABLE `client_contacts` DISABLE KEYS */;
INSERT INTO `client_contacts` VALUES (8,12,'qweqe','123',NULL,'qweqqwe@email.com',NULL,'qwe','wqeq','2026-07-21 16:55:09','2026-07-21 16:55:09'),(9,14,'Minton Cenina Diaz','09476353766','mobile','Minton@123','personal','qweqwe','qweqweqw','2026-07-21 21:06:17','2026-07-21 21:06:17'),(10,27,'Minton Cenina Diaz','09476353766','mobile','Minton@123','personal','weqqweqew','qweqewqwe','2026-07-21 21:11:21','2026-07-21 21:11:21');
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
  `container_class_id` bigint(20) unsigned NOT NULL,
  `container_size_id` bigint(20) unsigned NOT NULL,
  `container_variant_id` bigint(20) unsigned NOT NULL,
  `base_rate` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_type` enum('percentage','fixed') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_contract_rates`
--

LOCK TABLES `client_contract_rates` WRITE;
/*!40000 ALTER TABLE `client_contract_rates` DISABLE KEYS */;
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
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `client_contracts_uuid_unique` (`uuid`),
  UNIQUE KEY `client_contracts_code_unique` (`code`),
  KEY `client_contracts_client_id_foreign` (`client_id`),
  KEY `client_contracts_client_proposal_id_foreign` (`client_proposal_id`),
  KEY `client_contracts_created_by_foreign` (`created_by`),
  CONSTRAINT `client_contracts_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `client_masters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `client_contracts_client_proposal_id_foreign` FOREIGN KEY (`client_proposal_id`) REFERENCES `client_proposals` (`id`) ON DELETE SET NULL,
  CONSTRAINT `client_contracts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_contracts`
--

LOCK TABLES `client_contracts` WRITE;
/*!40000 ALTER TABLE `client_contracts` DISABLE KEYS */;
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
  `credit_terms` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `standard_billing_service` tinyint(1) NOT NULL DEFAULT 0,
  `invoice_submission` enum('electronic','courier') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_email_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_courier_recipient` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_courier_contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_courier_address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` enum('check_pickup','direct_remittance') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_pickup_address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_handling` tinyint(1) NOT NULL DEFAULT 0,
  `billing_summary_report` tinyint(1) NOT NULL DEFAULT 0,
  `other_requests` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `client_finance_client_id_unique` (`client_id`),
  CONSTRAINT `client_finance_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `client_masters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_finance`
--

LOCK TABLES `client_finance` WRITE;
/*!40000 ALTER TABLE `client_finance` DISABLE KEYS */;
INSERT INTO `client_finance` VALUES (9,12,'qweqwe','qweqewqw',1,'electronic','qweqeqwq@email.com',NULL,NULL,NULL,'check_pickup','qweasdawd',NULL,NULL,1,0,'asdadasda','2026-07-21 16:55:31','2026-07-21 16:55:31'),(10,14,'qweqwe','qweqew',1,'electronic','qweqqweqw@email.com',NULL,NULL,NULL,'check_pickup','qweqweqeqw',NULL,NULL,1,0,'qqweqweqe','2026-07-21 21:06:40','2026-07-21 21:06:40'),(11,27,'wqeqweq','qweqwe',1,'electronic','qweqqweqw@email.com',NULL,NULL,NULL,'check_pickup','qweqqqeqwe',NULL,NULL,1,0,'qweqwe','2026-07-21 21:11:41','2026-07-21 21:11:41');
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
  `contact_number_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_number_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `industry` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_start_date` date DEFAULT NULL,
  `estimated_annual_revenue` decimal(15,2) DEFAULT NULL,
  `company_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_rep_id` bigint(20) unsigned DEFAULT NULL,
  `current_stage` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `is_complete` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `client_masters_uuid_unique` (`uuid`),
  UNIQUE KEY `client_masters_customer_code_unique` (`customer_code`),
  KEY `client_masters_sales_rep_id_foreign` (`sales_rep_id`),
  KEY `client_masters_created_by_foreign` (`created_by`),
  KEY `client_masters_lead_id_foreign` (`lead_id`),
  CONSTRAINT `client_masters_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `client_masters_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `crm_leads` (`id`) ON DELETE SET NULL,
  CONSTRAINT `client_masters_sales_rep_id_foreign` FOREIGN KEY (`sales_rep_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_masters`
--

LOCK TABLES `client_masters` WRITE;
/*!40000 ALTER TABLE `client_masters` DISABLE KEYS */;
INSERT INTO `client_masters` VALUES (12,'a24602c3-ab12-4d31-a7b2-5219bd833b39',24,'TESTCODE','test','1231 231 32','12312312','Importer','qwqeqqeqewqwe','123123',NULL,12313212.00,'https://awdasdadasd.com',6,3,1,4,'2026-07-16 15:29:37','2026-07-21 16:57:25'),(13,'a2507e36-d673-481e-b37d-4ad573653113',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,0,4,'2026-07-21 20:33:06','2026-07-21 20:33:07'),(14,'a2508a03-76ca-4a47-b068-6e3b0ea72f6f',27,'CM-2026-0001','qweqe','123qweqeqq',NULL,'qqqweqew','qweq','123123','2026-07-01',123123.00,'https://awdasdadasd.com',4,3,1,4,'2026-07-21 21:06:05','2026-07-21 21:06:40'),(27,'a2508bcd-da88-4c34-96ae-87b0e4085e5b',24,'CM-2026-0002','test','1231 231 32',NULL,'Importer','asd','123123','2026-07-01',123.00,'https://awdasdadasd.com',4,3,1,4,'2026-07-21 21:11:06','2026-07-21 21:11:41');
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
  `container_class_id` bigint(20) unsigned NOT NULL,
  `container_size_id` bigint(20) unsigned NOT NULL,
  `container_variant_id` bigint(20) unsigned NOT NULL,
  `base_rate` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_type` enum('percentage','fixed') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_proposal_rates`
--

LOCK TABLES `client_proposal_rates` WRITE;
/*!40000 ALTER TABLE `client_proposal_rates` DISABLE KEYS */;
INSERT INTO `client_proposal_rates` VALUES (18,7,22,2,4,6,5,50,789.00,NULL,0.00,789.00,'2026-07-21 18:53:05','2026-07-21 18:53:05'),(19,7,1,2,4,6,7,54,1112.00,NULL,0.00,1112.00,'2026-07-21 18:53:05','2026-07-21 18:53:05'),(20,8,22,2,4,6,5,50,789.00,'fixed',150.00,639.00,'2026-07-21 18:53:58','2026-07-21 18:53:58'),(21,8,1,2,4,6,7,54,1112.00,'percentage',10.00,1000.80,'2026-07-21 18:53:58','2026-07-21 18:53:58'),(22,9,22,2,4,6,7,54,789.00,NULL,0.00,789.00,'2026-07-21 21:04:49','2026-07-21 21:04:49');
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_proposals`
--

LOCK TABLES `client_proposals` WRITE;
/*!40000 ALTER TABLE `client_proposals` DISABLE KEYS */;
INSERT INTO `client_proposals` VALUES (7,'48967428-d428-4b62-9767-7b7124670575','CPR-202607-0001',NULL,24,4,'[\"http:\\/\\/kargamine_prototype.test\\/uploads\\/doc\\/pdf\\/c952b8ba6049ba19bd9b40a3c3a30e0268f82527e2b22ed8b9abdf5093ec027d.pdf\"]','2026-07-21 21:10:06',4,'2026-07-21 21:09:56',NULL,4,'2026-07-21 18:53:05','2026-07-21 21:10:06'),(8,'ca2217f7-b173-47cf-b1f6-b545cfdad4f8','CPR-202607-0002',NULL,24,4,'[\"http:\\/\\/kargamine_prototype.test\\/uploads\\/doc\\/pdf\\/f165249cd4b53502d5291afc01d00d00088f4514201e0041b03a821186690bbf.pdf\"]','2026-07-21 18:55:30',4,'2026-07-21 18:54:09',NULL,4,'2026-07-21 18:53:58','2026-07-21 18:55:30'),(9,'7c89b498-e402-4b7f-869d-685876c04b34','CPR-202607-0003',NULL,27,4,'[\"http:\\/\\/kargamine_prototype.test\\/uploads\\/doc\\/pdf\\/d7dd82263d5ac05d0ba93cf40b07d616e235144d2625f1055f93d70b3ea9b37a.pdf\"]','2026-07-21 21:05:06',4,'2026-07-21 21:04:56',NULL,4,'2026-07-21 21:04:49','2026-07-21 21:05:06');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_trade_references`
--

LOCK TABLES `client_trade_references` WRITE;
/*!40000 ALTER TABLE `client_trade_references` DISABLE KEYS */;
INSERT INTO `client_trade_references` VALUES (1,12,'asdasd','asdasd','asdasd','123123','qweqwe','asdasdad@email.com','2026-07-21 16:55:09','2026-07-21 16:55:09');
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
-- Table structure for table `container_class`
--

DROP TABLE IF EXISTS `container_class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `container_class` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `class` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `container_class`
--

LOCK TABLES `container_class` WRITE;
/*!40000 ALTER TABLE `container_class` DISABLE KEYS */;
INSERT INTO `container_class` VALUES (6,'A','2026-07-13 17:33:27','2026-07-13 17:33:27'),(7,'B','2026-07-13 17:33:31','2026-07-13 17:33:31'),(8,'C','2026-07-13 17:33:35','2026-07-13 17:33:35'),(9,'D','2026-07-13 17:33:39','2026-07-13 17:33:39'),(10,'0','2026-07-13 17:33:44','2026-07-13 17:33:44'),(11,'A',NULL,NULL),(12,'B',NULL,NULL),(13,'C',NULL,NULL),(14,'D',NULL,NULL);
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
  `size` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `container_size`
--

LOCK TABLES `container_size` WRITE;
/*!40000 ALTER TABLE `container_size` DISABLE KEYS */;
INSERT INTO `container_size` VALUES (5,'40','2026-07-13 17:34:05','2026-07-13 17:34:05'),(6,'10-FOOTER',NULL,NULL),(7,'20-FOOTER',NULL,NULL),(8,'40-FOOTER STD',NULL,NULL),(9,'40-FOOTER HC',NULL,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `container_type`
--

LOCK TABLES `container_type` WRITE;
/*!40000 ALTER TABLE `container_type` DISABLE KEYS */;
INSERT INTO `container_type` VALUES (1,'DRY VAN/CON VAN',NULL,NULL),(2,'FLATRACK (PLATFORM)',NULL,NULL),(3,'REEFER',NULL,NULL),(4,'HIGH CUBE',NULL,NULL),(5,'CATTLE VAN',NULL,NULL),(6,'TANK (ISO TANK)',NULL,NULL),(7,'ROLLING CARGO',NULL,NULL),(8,'SPECIAL CONTAINERS',NULL,NULL),(9,'OPEN-TOP VAN',NULL,NULL),(10,'CONVAN',NULL,NULL),(11,'FLATRACK (PLATFORM)',NULL,NULL),(12,'REEFER',NULL,NULL),(13,'HIGH CUBE',NULL,NULL),(14,'CATTLE VAN',NULL,NULL),(15,'TANK (ISO TANK)',NULL,NULL),(16,'ROLLING CARGO',NULL,NULL),(17,'SPECIAL CONTAINERS',NULL,NULL),(18,'OPEN-TOP VAN',NULL,NULL);
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
  `container_class_id` bigint(20) unsigned NOT NULL,
  `container_size_id` bigint(20) unsigned NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `container_variants`
--

LOCK TABLES `container_variants` WRITE;
/*!40000 ALTER TABLE `container_variants` DISABLE KEYS */;
INSERT INTO `container_variants` VALUES (33,7,10,5,1,'2026-07-13 17:37:18','2026-07-13 17:37:18'),(34,7,6,5,1,'2026-07-13 17:37:18','2026-07-13 17:37:18'),(35,7,7,5,1,'2026-07-13 17:37:18','2026-07-13 17:37:18'),(36,7,9,5,1,'2026-07-13 17:37:18','2026-07-13 17:37:18'),(37,5,6,5,1,'2026-07-13 17:37:23','2026-07-13 17:37:23'),(38,5,7,5,1,'2026-07-13 17:37:23','2026-07-13 17:37:23'),(39,5,8,5,1,'2026-07-13 17:37:23','2026-07-13 17:37:23'),(40,5,9,5,1,'2026-07-13 17:37:23','2026-07-13 17:37:23'),(41,5,10,5,1,'2026-07-13 17:37:23','2026-07-13 17:37:23'),(42,6,6,5,1,'2026-07-13 17:38:36','2026-07-13 17:38:36'),(43,6,7,5,1,'2026-07-13 17:38:36','2026-07-13 17:38:36'),(44,6,8,5,1,'2026-07-13 17:38:36','2026-07-13 17:38:36'),(45,6,9,5,1,'2026-07-13 17:38:36','2026-07-13 17:38:36'),(46,6,10,5,1,'2026-07-13 17:38:36','2026-07-13 17:38:36'),(47,8,6,5,1,'2026-07-13 17:39:14','2026-07-13 17:39:14'),(48,8,7,5,1,'2026-07-13 17:39:14','2026-07-13 17:39:14'),(49,8,9,5,1,'2026-07-13 17:39:14','2026-07-13 17:39:14'),(50,4,6,5,1,'2026-07-16 15:25:59','2026-07-16 15:25:59'),(51,4,7,5,1,'2026-07-16 15:25:59','2026-07-16 15:25:59'),(52,4,8,5,1,'2026-07-16 15:25:59','2026-07-16 15:25:59'),(53,4,9,5,1,'2026-07-16 15:25:59','2026-07-16 15:25:59'),(54,4,6,7,1,'2026-07-16 15:25:59','2026-07-16 15:25:59');
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `containers`
--

LOCK TABLES `containers` WRITE;
/*!40000 ALTER TABLE `containers` DISABLE KEYS */;
INSERT INTO `containers` VALUES (4,'CV','Container Van',1,'2026-07-13 17:34:52','2026-07-13 17:34:52'),(5,'FR','Flatrack',1,'2026-07-13 17:35:45','2026-07-13 17:37:23'),(6,'RF','Reefer Van',1,'2026-07-13 17:36:18','2026-07-13 17:38:36'),(7,'LC','Loose Cargo',1,'2026-07-13 17:37:18','2026-07-13 17:37:18'),(8,'RC','Rolling Cargo',1,'2026-07-13 17:39:14','2026-07-13 17:39:14');
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_activities`
--

LOCK TABLES `crm_activities` WRITE;
/*!40000 ALTER TABLE `crm_activities` DISABLE KEYS */;
INSERT INTO `crm_activities` VALUES (15,24,'test','test','http://kargamine_prototype.test/uploads/crm/activities/88d8e6859844624accf391c28a655fca9afc13b44ba931a33eb93417da202c2e.pdf',4,'2026-07-22 19:22:19','2026-07-22 19:22:19');
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
  `authorized_signatory_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authorized_signatory_position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `crm_company_info_lead_id_foreign` (`lead_id`),
  CONSTRAINT `crm_company_info_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `crm_leads` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_company_info`
--

LOCK TABLES `crm_company_info` WRITE;
/*!40000 ALTER TABLE `crm_company_info` DISABLE KEYS */;
INSERT INTO `crm_company_info` VALUES (21,24,'test','Importer',NULL,NULL,NULL,'2026-07-13 21:35:19','2026-07-13 21:35:19'),(22,25,'test company','Importer',NULL,NULL,NULL,'2026-07-16 15:20:00','2026-07-16 15:20:00'),(23,26,NULL,NULL,NULL,NULL,NULL,'2026-07-21 19:31:06','2026-07-21 19:31:06'),(24,27,NULL,NULL,NULL,NULL,NULL,'2026-07-21 21:04:14','2026-07-21 21:04:14');
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_lead_addresses`
--

LOCK TABLES `crm_lead_addresses` WRITE;
/*!40000 ALTER TABLE `crm_lead_addresses` DISABLE KEYS */;
INSERT INTO `crm_lead_addresses` VALUES (1,24,NULL,1,'test','test','test','Poblacion 3','Buenavista','Agusan del Norte','test','test','2026-07-21 19:27:50','2026-07-21 19:27:50'),(2,25,NULL,1,'12','12','qweqwe','Agtangao','Bangued','Abra','Philippines','1940','2026-07-21 19:27:50','2026-07-21 19:27:50'),(3,26,'Branch',1,'123','123','123','Abilan','Buenavista','Agusan del Norte','Philippines','132123','2026-07-21 19:31:06','2026-07-21 19:31:06'),(4,26,'Warehouse',0,'qaeq123','123','13123','Ableg','Daguioman','Abra','Philippines','123123','2026-07-21 19:31:06','2026-07-21 19:31:06'),(5,27,'Branch',1,'12','12','12','Agtangao','Bangued','Abra','Philippines','123123','2026-07-21 21:04:14','2026-07-21 21:04:14');
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
  `convan_class` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `container_class_id` bigint(20) unsigned DEFAULT NULL,
  `convan_size` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `container_size_id` bigint(20) unsigned DEFAULT NULL,
  `required_temperature` decimal(5,2) DEFAULT NULL,
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
INSERT INTO `crm_lead_containers` VALUES (13,24,'CV',22,2,NULL,NULL,'Container Van (CV)',123,123.00,'Monthly','qqqwqeqwe',NULL,6,NULL,5,NULL,NULL,NULL,'DOOR','DOOR',NULL,0,NULL,'qweqwe','qweqwe','2026-07-13 21:50:20','2026-07-13 21:50:20'),(14,24,'CV',1,2,NULL,NULL,'Container Van (CV)',11,123.00,'Weekly','asdadads',NULL,6,NULL,7,NULL,NULL,NULL,'DOOR','DOOR',NULL,1,'http://kargamine_prototype.test/uploads/crm/dg-documents/94ea868172cf046ac6c20e5f4da7b4d01acbe18150152a0f549223d61aada096.pdf','asdada','asdadssa','2026-07-21 18:27:20','2026-07-21 18:27:20'),(15,26,'CV',22,2,NULL,NULL,'Container Van (CV)',123,123.00,'Weekly','asdawda',NULL,6,NULL,7,NULL,NULL,NULL,'DOOR','DOOR',NULL,1,'http://kargamine_prototype.test/uploads/crm/dg-documents/25c857cca385af4f49e22ae7f57e29e6c840f836397872f88b4faf3eedd856c2.pdf','qweqeqe','qweqeqe','2026-07-21 19:31:28','2026-07-21 19:31:28'),(16,27,'CV',22,2,NULL,NULL,'Container Van (CV)',12,123.00,'Weekly','asdadw',NULL,6,NULL,7,NULL,NULL,NULL,'DOOR','DOOR',NULL,0,NULL,'qqweqew','qweqwe','2026-07-21 21:04:43','2026-07-21 21:04:43');
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
  `contact_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_leads`
--

LOCK TABLES `crm_leads` WRITE;
/*!40000 ALTER TABLE `crm_leads` DISABLE KEYS */;
INSERT INTO `crm_leads` VALUES (24,'a2407c98-4b4c-499f-aa3d-877422eee2bb','test','test@email.com',NULL,'1231 231 32',NULL,NULL,NULL,'test',3,'CM-2026-0002','corporate',2,1,'test',6,NULL,NULL,'2026-07-21 21:11:06','2026-07-13 21:35:19','2026-07-22 19:22:19'),(25,'a245ff52-bda9-43c1-9e46-adfe4421c028','test name','testemail@email.com',NULL,'1234 556 6778',NULL,NULL,NULL,'test position',1,NULL,'corporate',1,0,'facebook',4,NULL,NULL,'2026-07-16 15:20:00','2026-07-16 15:20:00','2026-07-16 15:20:00'),(26,'a250680b-d3f2-4b40-b2a2-6b73b1f06cae','notnim','awdadawd@email.com','personal','1315 123 123','123123131','personal','personal','asdadawd',3,NULL,'individual',2,1,'Cold Call',4,NULL,NULL,'2026-07-21 19:31:28','2026-07-21 19:31:06','2026-07-21 19:31:28'),(27,'a2508959-c5a7-4e6e-94aa-1b97ab24b0f8','teste','qweqwe@email.com','personal','123','qweqew','personal','personal','test',3,'CM-2026-0001','individual',2,1,'Cold Call',4,NULL,NULL,'2026-07-21 21:06:40','2026-07-21 21:04:14','2026-07-21 21:06:40');
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_notes`
--

LOCK TABLES `crm_notes` WRITE;
/*!40000 ALTER TABLE `crm_notes` DISABLE KEYS */;
INSERT INTO `crm_notes` VALUES (15,24,'test note please',4,'2026-07-22 19:22:54','2026-07-22 19:22:54');
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
INSERT INTO `crm_status` VALUES (1,'LEAD','New incoming lead',NULL,NULL),(2,'QUALIFIED','Lead is qualified and potential',NULL,NULL),(3,'OPPORTUNITY','Converted into sales opportunity',NULL,NULL),(4,'NEGOTIATION','In negotiation stage',NULL,NULL),(5,'WIN','Final stage: won or lost',NULL,NULL),(6,'LOST','Final stage: won or lost',NULL,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_type`
--

LOCK TABLES `customer_type` WRITE;
/*!40000 ALTER TABLE `customer_type` DISABLE KEYS */;
INSERT INTO `customer_type` VALUES (1,'SHIPPER',NULL,NULL),(2,'CONSIGNEE',NULL,NULL),(3,'SHIPPER-CONSIGNEE',NULL,NULL),(4,'SHIPPER',NULL,NULL),(5,'CONSIGNEE',NULL,NULL),(6,'SHIPPER-CONSIGNEE',NULL,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `delivery_types`
--

LOCK TABLES `delivery_types` WRITE;
/*!40000 ALTER TABLE `delivery_types` DISABLE KEYS */;
INSERT INTO `delivery_types` VALUES (1,'DD','Doorr - Door',1,1,'2026-07-05 17:18:56','2026-07-05 17:18:56');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
INSERT INTO `failed_jobs` VALUES (1,'b59bc15c-a51c-4331-ba39-2684a381c62a','database','default','{\"uuid\":\"b59bc15c-a51c-4331-ba39-2684a381c62a\",\"displayName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"command\":\"O:31:\\\"App\\\\Jobs\\\\SendApplicationMailJob\\\":2:{s:10:\\\"\\u0000*\\u0000payload\\\";a:8:{s:7:\\\"subject\\\";s:12:\\\"New Proposal\\\";s:5:\\\"title\\\";s:19:\\\"New Proposal Upload\\\";s:7:\\\"message\\\";s:59:\\\"There is new proposal uploaded for your review and approval\\\";s:6:\\\"Header\\\";s:13:\\\"TESTPRCODE123\\\";s:8:\\\"app_name\\\";s:24:\\\"Document Monitoring Tool\\\";s:4:\\\"logo\\\";s:45:\\\"https:\\/\\/kargamine.synxcel.com\\/images\\/logo.png\\\";s:6:\\\"button\\\";a:2:{s:3:\\\"url\\\";s:33:\\\"https:\\/\\/kargamine.synxcel.com\\/app\\\";s:4:\\\"text\\\";s:15:\\\"Go to Dashboard\\\";}s:6:\\\"footer\\\";s:45:\\\"Please do not reply to this email. Thank you.\\\";}s:9:\\\"\\u0000*\\u0000userId\\\";s:1:\\\"5\\\";}\"}}','Illuminate\\Queue\\TimeoutExceededException: App\\Jobs\\SendApplicationMailJob has timed out. in /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Queue/TimeoutExceededException.php:15\nStack trace:\n#0 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(796): Illuminate\\Queue\\TimeoutExceededException::forJob()\n#1 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(217): Illuminate\\Queue\\Worker->timeoutExceededException()\n#2 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/symfony/mailer/Transport/Smtp/Stream/SocketStream.php(154): Illuminate\\Queue\\Worker->Illuminate\\Queue\\{closure}()\n#3 [internal function]: Symfony\\Component\\Mailer\\Transport\\Smtp\\Stream\\SocketStream->Symfony\\Component\\Mailer\\Transport\\Smtp\\Stream\\{closure}()\n#4 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/symfony/mailer/Transport/Smtp/Stream/SocketStream.php(157): stream_socket_client()\n#5 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/symfony/mailer/Transport/Smtp/SmtpTransport.php(275): Symfony\\Component\\Mailer\\Transport\\Smtp\\Stream\\SocketStream->initialize()\n#6 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/symfony/mailer/Transport/Smtp/SmtpTransport.php(210): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->start()\n#7 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/symfony/mailer/Transport/AbstractTransport.php(69): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->doSend()\n#8 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/symfony/mailer/Transport/Smtp/SmtpTransport.php(137): Symfony\\Component\\Mailer\\Transport\\AbstractTransport->send()\n#9 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Mail/Mailer.php(573): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->send()\n#10 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Mail/Mailer.php(335): Illuminate\\Mail\\Mailer->sendSymfonyMessage()\n#11 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Mail/Mailable.php(205): Illuminate\\Mail\\Mailer->send()\n#12 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Support/Traits/Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#13 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Mail/Mailable.php(198): Illuminate\\Mail\\Mailable->withLocale()\n#14 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Mail/Mailer.php(357): Illuminate\\Mail\\Mailable->send()\n#15 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Mail/Mailer.php(301): Illuminate\\Mail\\Mailer->sendMailable()\n#16 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Mail/PendingMail.php(124): Illuminate\\Mail\\Mailer->send()\n#17 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/app/Jobs/SendApplicationMailJob.php(55): Illuminate\\Mail\\PendingMail->send()\n#18 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\SendApplicationMailJob->handle()\n#19 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Container/Util.php(41): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#20 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure()\n#21 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#22 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Container/Container.php(662): Illuminate\\Container\\BoundMethod::call()\n#23 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(128): Illuminate\\Container\\Container->call()\n#24 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(144): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#25 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(119): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#26 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Pipeline\\Pipeline->then()\n#27 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(123): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#28 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(144): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#29 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(119): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#30 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(122): Illuminate\\Pipeline\\Pipeline->then()\n#31 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#32 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#33 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(439): Illuminate\\Queue\\Jobs\\Job->fire()\n#34 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(389): Illuminate\\Queue\\Worker->process()\n#35 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(176): Illuminate\\Queue\\Worker->runJob()\n#36 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(137): Illuminate\\Queue\\Worker->daemon()\n#37 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(120): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#38 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#39 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Container/Util.php(41): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#40 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure()\n#41 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#42 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Container/Container.php(662): Illuminate\\Container\\BoundMethod::call()\n#43 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#44 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/symfony/console/Command/Command.php(326): Illuminate\\Console\\Command->execute()\n#45 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#46 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/symfony/console/Application.php(1096): Illuminate\\Console\\Command->run()\n#47 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/symfony/console/Application.php(324): Symfony\\Component\\Console\\Application->doRunCommand()\n#48 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/symfony/console/Application.php(175): Symfony\\Component\\Console\\Application->doRun()\n#49 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(201): Symfony\\Component\\Console\\Application->run()\n#50 /home/rqd3gkojy4x1/public_html/kargamine.synxcel.com/app_core/artisan(35): Illuminate\\Foundation\\Console\\Kernel->handle()\n#51 {main}','2026-07-01 05:22:02');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `general_charges`
--

LOCK TABLES `general_charges` WRITE;
/*!40000 ALTER TABLE `general_charges` DISABLE KEYS */;
INSERT INTO `general_charges` VALUES (1,3,278.00,'2026-07-01',NULL,1,'2026-07-08 15:20:28','2026-07-08 15:20:28');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `handling_fees`
--

LOCK TABLES `handling_fees` WRITE;
/*!40000 ALTER TABLE `handling_fees` DISABLE KEYS */;
INSERT INTO `handling_fees` VALUES (1,2,150.00,'2026-07-05',NULL,1,'2026-07-05 17:25:07','2026-07-05 17:25:07');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `frt` decimal(12,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lane_tariff_rate_prices_unique` (`lane_tariff_rate_id`,`container_variant_id`),
  KEY `lane_tariff_rate_prices_container_variant_id_foreign` (`container_variant_id`),
  CONSTRAINT `lane_tariff_rate_prices_container_variant_id_foreign` FOREIGN KEY (`container_variant_id`) REFERENCES `container_variants` (`id`),
  CONSTRAINT `lane_tariff_rate_prices_lane_tariff_rate_id_foreign` FOREIGN KEY (`lane_tariff_rate_id`) REFERENCES `lane_tariff_rates` (`rate_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=135 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lane_tariff_rate_prices`
--

LOCK TABLES `lane_tariff_rate_prices` WRITE;
/*!40000 ALTER TABLE `lane_tariff_rate_prices` DISABLE KEYS */;
INSERT INTO `lane_tariff_rate_prices` VALUES (91,27,50,'2026-07-21 17:03:46','2026-07-21 17:03:46',1111.00),(92,27,54,'2026-07-21 17:03:46','2026-07-21 17:03:46',1112.00),(93,27,51,'2026-07-21 17:03:46','2026-07-21 17:03:46',1113.00),(94,27,52,'2026-07-21 17:03:46','2026-07-21 17:03:46',1114.00),(95,27,53,'2026-07-21 17:03:46','2026-07-21 17:03:46',11115.00),(96,27,37,'2026-07-21 17:03:46','2026-07-21 17:03:46',123.00),(97,27,38,'2026-07-21 17:03:46','2026-07-21 17:03:46',123.00),(98,27,39,'2026-07-21 17:03:46','2026-07-21 17:03:46',123.00),(99,27,40,'2026-07-21 17:03:46','2026-07-21 17:03:46',123.00),(100,27,41,'2026-07-21 17:03:46','2026-07-21 17:03:46',123.00),(101,27,42,'2026-07-21 17:03:46','2026-07-21 17:03:46',123.00),(102,27,43,'2026-07-21 17:03:46','2026-07-21 17:03:46',123.00),(103,27,44,'2026-07-21 17:03:46','2026-07-21 17:03:46',123.00),(104,27,45,'2026-07-21 17:03:46','2026-07-21 17:03:46',123.00),(105,27,46,'2026-07-21 17:03:46','2026-07-21 17:03:46',123.00),(106,27,34,'2026-07-21 17:03:46','2026-07-21 17:03:46',123.00),(107,27,35,'2026-07-21 17:03:46','2026-07-21 17:03:46',123.00),(108,27,36,'2026-07-21 17:03:46','2026-07-21 17:03:46',123.00),(109,27,33,'2026-07-21 17:03:46','2026-07-21 17:03:46',123.00),(110,27,47,'2026-07-21 17:03:46','2026-07-21 17:03:46',123.00),(111,27,48,'2026-07-21 17:03:46','2026-07-21 17:03:46',123.00),(112,27,49,'2026-07-21 17:03:46','2026-07-21 17:03:46',123.00),(113,28,50,'2026-07-21 18:52:57','2026-07-21 18:52:57',789.00),(114,28,54,'2026-07-21 18:52:57','2026-07-21 18:52:57',789.00),(115,28,51,'2026-07-21 18:52:57','2026-07-21 18:52:57',789.00),(116,28,52,'2026-07-21 18:52:57','2026-07-21 18:52:57',789.00),(117,28,53,'2026-07-21 18:52:57','2026-07-21 18:52:57',789.00),(118,28,37,'2026-07-21 18:52:57','2026-07-21 18:52:57',789.00),(119,28,38,'2026-07-21 18:52:57','2026-07-21 18:52:57',789.00),(120,28,39,'2026-07-21 18:52:57','2026-07-21 18:52:57',789.00),(121,28,40,'2026-07-21 18:52:57','2026-07-21 18:52:57',789.00),(122,28,41,'2026-07-21 18:52:57','2026-07-21 18:52:57',789.00),(123,28,42,'2026-07-21 18:52:57','2026-07-21 18:52:57',789.00),(124,28,43,'2026-07-21 18:52:57','2026-07-21 18:52:57',789.00),(125,28,44,'2026-07-21 18:52:57','2026-07-21 18:52:57',789.00),(126,28,45,'2026-07-21 18:52:57','2026-07-21 18:52:57',789.00),(127,28,46,'2026-07-21 18:52:57','2026-07-21 18:52:57',789.00),(128,28,34,'2026-07-21 18:52:57','2026-07-21 18:52:57',789.00),(129,28,35,'2026-07-21 18:52:57','2026-07-21 18:52:57',789.00),(130,28,36,'2026-07-21 18:52:57','2026-07-21 18:52:57',789.00),(131,28,33,'2026-07-21 18:52:57','2026-07-21 18:52:57',789.00),(132,28,47,'2026-07-21 18:52:57','2026-07-21 18:52:57',789.00),(133,28,48,'2026-07-21 18:52:57','2026-07-21 18:52:57',789.00),(134,28,49,'2026-07-21 18:52:57','2026-07-21 18:52:57',789.00);
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
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lane_tariff_rates`
--

LOCK TABLES `lane_tariff_rates` WRITE;
/*!40000 ALTER TABLE `lane_tariff_rates` DISABLE KEYS */;
INSERT INTO `lane_tariff_rates` VALUES (27,1,'2026-07-01','2026-08-08',1,'2026-07-21 17:03:46','2026-07-21 17:03:46'),(28,3,'2026-07-01','2026-07-31',1,'2026-07-21 18:52:57','2026-07-21 18:52:57');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lanes`
--

LOCK TABLES `lanes` WRITE;
/*!40000 ALTER TABLE `lanes` DISABLE KEYS */;
INSERT INTO `lanes` VALUES (1,1,2,1,'2026-07-05 17:22:54','2026-07-05 17:22:54'),(2,1,4,1,'2026-07-06 01:27:34','2026-07-06 01:27:34'),(3,22,2,1,'2026-07-21 18:52:27','2026-07-21 18:52:27');
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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `list_of_values_table`
--

LOCK TABLES `list_of_values_table` WRITE;
/*!40000 ALTER TABLE `list_of_values_table` DISABLE KEYS */;
INSERT INTO `list_of_values_table` VALUES (1,1,'IMP','Importer',NULL,'2026-07-13 18:32:30','2026-07-13 18:32:30'),(2,1,'EXP','Exporter',NULL,'2026-07-13 18:32:30','2026-07-13 18:32:30'),(3,1,'MAN','Manufacturer',NULL,'2026-07-13 18:32:30','2026-07-13 18:32:30'),(4,1,'TRA','Trading',NULL,'2026-07-13 18:32:30','2026-07-13 18:32:30'),(5,1,'RET','Retail',NULL,'2026-07-13 18:32:30','2026-07-13 18:32:30'),(6,1,'DIS','Distributor',NULL,'2026-07-13 18:32:30','2026-07-13 18:32:30'),(7,1,'OTH','Others',NULL,'2026-07-13 18:32:30','2026-07-13 18:32:30'),(8,2,'OFF','Office',NULL,'2026-07-21 19:27:57','2026-07-21 19:27:57'),(9,2,'WAR','Warehouse',NULL,'2026-07-21 19:27:57','2026-07-21 19:27:57'),(10,2,'BRA','Branch',NULL,'2026-07-21 19:27:57','2026-07-21 19:27:57'),(11,2,'STO','Storage Facility',NULL,'2026-07-21 19:27:57','2026-07-21 19:27:57'),(12,3,'REF','Referral',NULL,'2026-07-21 19:28:03','2026-07-21 19:28:03'),(13,3,'WEB','Website',NULL,'2026-07-21 19:28:03','2026-07-21 19:28:03'),(14,3,'WAL','Walk-in',NULL,'2026-07-21 19:28:03','2026-07-21 19:28:03'),(15,3,'COL','Cold Call',NULL,'2026-07-21 19:28:03','2026-07-21 19:28:03'),(16,3,'SOC','Social Media',NULL,'2026-07-21 19:28:03','2026-07-21 19:28:03'),(17,3,'OTH','Other',NULL,'2026-07-21 19:28:03','2026-07-21 19:28:03');
/*!40000 ALTER TABLE `list_of_values_table` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mailer_settings`
--

LOCK TABLES `mailer_settings` WRITE;
/*!40000 ALTER TABLE `mailer_settings` DISABLE KEYS */;
INSERT INTO `mailer_settings` VALUES (1,'smtp','localhost','25','minton.diaz@synxcel.com','R$8wZ1^kNq5!Ty3','tls','noreply@synxcel.com','Synxcel Notification','2026-07-01 05:16:45','2026-07-20 16:22:57');
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
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2025_10_01_211540_create_nav_menus_table',1),(6,'2025_10_02_163004_create_table_for_settings_role',1),(7,'2025_10_03_180527_add_parentmenu',1),(8,'2025_10_04_144632_create_mailer_settings_table',1),(9,'2025_11_06_035725_insert_order_in_nav_menus',1),(10,'2026_01_24_221131_create_sessions_table',1),(11,'2026_01_24_221645_add_session_id_to_users_table',1),(12,'2026_01_24_223037_create_cache_table',1),(13,'2026_01_26_110424_create_jobs_table',1),(14,'2026_02_03_122024_modify_columns_of_user_table',1),(15,'2026_05_01_011537_options_table',1),(16,'2026_05_01_011632_list_of_value_table',1),(17,'2026_05_05_052405_create_company_info_master_table',1),(18,'2026_05_05_052441_create_contact_info_table',1),(19,'2026_05_05_052453_create_trade_references_table',1),(20,'2026_05_05_052548_create_services_info_table',1),(21,'2026_05_05_052612_create_company_finance_table',1),(22,'2026_05_05_052631_create_billed_details_table',1),(23,'2026_05_05_052656_create_sales_info_table',1),(24,'2026_05_05_052712_create_stages_info_table',1),(25,'2026_05_07_205424_create_e_invoice_table',1),(26,'2026_05_07_205616_create_courier_invoice_table',1),(27,'2026_06_05_045259_create_crm_status_table',1),(28,'2026_06_05_045351_create_crm_leads_table',1),(29,'2026_06_05_045416_create_company_info_table',1),(30,'2026_06_05_045430_create_crm_notes_table',1),(31,'2026_06_05_045450_create_crm_activities_table',1),(32,'2026_06_15_230541_proposals',1),(33,'2026_06_15_231224_proposal_rates',1),(34,'2026_06_15_233415_create_table_for_routes',1),(35,'2026_06_15_233633_create_table_service_type',1),(36,'2026_06_15_233806_create_proposal_status',1),(37,'2026_06_15_233848_create_customer_type',1),(38,'2026_06_26_195636_create_container_type',1),(39,'2026_06_26_195710_create_container_class',1),(40,'2026_06_26_195725_create_container_size',1),(44,'2026_07_02_221007_add_new_columns_to_user_table',2),(45,'2026_07_02_222100_create_user_department',2),(46,'2026_07_02_222118_create_user_status',2),(47,'2026_07_04_000001_create_ports_table',3),(48,'2026_07_04_000002_create_serviceable_areas_table',3),(49,'2026_07_04_000003_create_delivery_types_table',3),(50,'2026_07_04_000004_create_charge_types_table',3),(51,'2026_07_04_000005_create_lanes_table',3),(52,'2026_07_04_000006_create_lane_tariff_rates_table',3),(53,'2026_07_04_000007_create_port_charges_table',3),(54,'2026_07_04_000008_create_handling_fees_table',3),(55,'2026_07_04_000009_create_trucking_tariffs_table',3),(56,'2026_07_04_000010_create_vat_rates_table',3),(57,'2026_07_04_000011_create_contracts_table',3),(58,'2026_07_04_000012_create_contract_rates_table',3),(59,'2026_07_04_000013_create_bookings_table',3),(60,'2026_07_04_000014_create_booking_port_charges_table',3),(67,'2026_07_07_000001_add_applicable_to_to_charge_types_table',4),(68,'2026_07_07_000002_create_general_charges_table',4),(69,'2026_07_07_124225_drop_bsc_ra_gri_from_lane_tariff_rates_table',4),(70,'2026_07_07_124800_add_rate_type_and_rate_value_to_proposals_rates_table',4),(71,'2026_07_08_175248_create_client_masters_table',5),(72,'2026_07_08_175319_create_client_contacts_table',5),(73,'2026_07_08_175429_create_client_trade_references_table',5),(74,'2026_07_08_175448_create_client_finance_table',5),(75,'2026_07_08_175528_create_client_billing_table',5),(76,'2026_07_08_221636_create_containers_table',6),(77,'2026_07_08_221704_create_container_variants_table',6),(78,'2026_07_08_221727_create_lane_tariff_rate_prices_table',6),(79,'2026_07_09_000939_drop_column_from_container_table',7),(81,'2026_07_09_001926_drop_column_from_lane_tariff_rates_table',8),(82,'2026_07_10_212311_create_client_proposals_table',9),(83,'2026_07_10_212810_create_client_proposal_rates_table',9),(84,'2026_07_10_212846_create_client_contracts_table',9),(85,'2026_07_10_212946_create_client_contract_rates_table',9),(86,'2026_07_11_152530_add_lead_id_to_client_masters_table',10),(87,'2026_07_13_182230_add_workflow_columns_to_client_proposals_table',11),(88,'2026_07_14_021740_add_progress_columns_to_crm_leads_table',12),(89,'2026_07_14_021824_add_address_fields_to_crm_company_info_table',12),(90,'2026_07_14_021949_create_crm_lead_containers_table',12),(91,'2026_07_14_030206_add_lookup_columns_to_crm_lead_containers_table',13),(92,'2026_07_14_044418_drop_company_address_from_crm_company_info',14),(93,'2026_01_26_110425_modify_columns_of_user_table',15),(94,'2026_07_22_003454_add_lead_id_to_client_proposals_table',16),(95,'2026_07_22_020031_add_attachment_to_crm_activities_table',16),(96,'2026_07_22_031438_add_client_type_and_contact_fields_to_crm_leads_table',17),(97,'2026_07_22_031439_create_crm_lead_addresses_table',17),(98,'2026_07_22_031439_migrate_crm_company_address_to_lead_addresses_and_drop_columns',17),(99,'2026_07_22_031440_add_industry_description_to_crm_company_info_table',17),(100,'2026_07_22_040302_remove_lookup_values_nav_menu_entry',18),(101,'2026_07_22_045002_add_customer_code_to_crm_leads_table',18),(102,'2026_07_22_045002_create_client_addresses_table',18),(103,'2026_07_22_045003_migrate_client_registered_address_and_drop_column',18),(104,'2026_07_22_045004_add_type_fields_to_client_contacts_table',18),(105,'2026_07_23_010845_create_app_theme_settings_table',19),(106,'2026_07_23_010846_add_theme_nav_menu_entry',19);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nav_menus`
--

LOCK TABLES `nav_menus` WRITE;
/*!40000 ALTER TABLE `nav_menus` DISABLE KEYS */;
INSERT INTO `nav_menus` VALUES (1,'Dashboard','fas fa-home','/page_dashboard','[\"1\"]',0,0,'2026-06-28 15:57:34','2026-06-28 15:57:34'),(3,'Developer Option','fas fa-users','#','[\"1\"]',0,8,'2026-06-28 15:57:34','2026-07-15 17:59:49'),(4,'Mailer','','/page_mailer','[\"1\"]',3,1,'2026-06-28 15:57:34','2026-06-28 15:57:34'),(5,'Menus','','/page_menus','[\"1\"]',3,2,'2026-06-28 15:57:34','2026-06-28 15:57:34'),(6,'Settings','','#','[\"1\",\"2\",\"3\",\"4\"]',0,7,'2026-06-28 15:57:34','2026-07-15 17:59:49'),(8,'Clients',NULL,'page_clientMasters','[\"1\",\"2\",\"3\",\"4\"]',0,6,'2026-06-28 15:57:34','2026-07-08 10:03:28'),(9,'Contracts','','/page_contracts','[\"1\",\"2\",\"3\",\"4\"]',0,4,'2026-06-28 15:57:34','2026-07-15 17:59:49'),(12,'CRM','','/page_crm','[\"1\"]',0,2,'2026-06-28 15:57:34','2026-07-15 17:59:49'),(13,'Proposals',NULL,'page_proposals','[\"1\",\"2\",\"3\",\"4\"]',0,2,'2026-07-01 00:40:38','2026-07-01 00:40:38'),(17,'Users',NULL,'page_usermanagement','[\"1\",\"2\",\"3\",\"4\"]',0,10,'2026-07-01 16:57:16','2026-07-01 16:57:54'),(18,'Maintenance',NULL,'page_maintenance','[\"1\",\"2\",\"3\",\"4\"]',6,11,'2026-07-05 16:07:58','2026-07-05 16:08:59'),(20,'App Settings','','/page_maintenance','[\"1\",\"2\",\"3\",\"4\"]',6,1,'2026-07-15 17:59:49','2026-07-15 17:59:49'),(22,'Theme','','/page_theme','[\"1\"]',3,3,'2026-07-22 17:16:27','2026-07-22 17:16:27');
/*!40000 ALTER TABLE `nav_menus` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `options_table`
--

LOCK TABLES `options_table` WRITE;
/*!40000 ALTER TABLE `options_table` DISABLE KEYS */;
INSERT INTO `options_table` VALUES (1,'Type of Business',NULL,'2026-07-13 18:32:30','2026-07-13 18:32:30'),(2,'Address Type',NULL,'2026-07-21 19:27:57','2026-07-21 19:27:57'),(3,'Lead Source',NULL,'2026-07-21 19:28:03','2026-07-21 19:28:03');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `port_charges`
--

LOCK TABLES `port_charges` WRITE;
/*!40000 ALTER TABLE `port_charges` DISABLE KEYS */;
INSERT INTO `port_charges` VALUES (1,2,1,50.00,'2026-06-29',NULL,1,'2026-07-05 17:25:33','2026-07-05 17:25:33'),(2,2,2,80.00,'2026-06-29',NULL,1,'2026-07-05 17:26:19','2026-07-05 17:26:19'),(3,6,1,150.00,'2026-07-06',NULL,1,'2026-07-06 01:26:21','2026-07-06 01:26:21');
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
  `code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`port_id`),
  UNIQUE KEY `ports_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=381 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ports`
--

LOCK TABLES `ports` WRITE;
/*!40000 ALTER TABLE `ports` DISABLE KEYS */;
INSERT INTO `ports` VALUES (1,'MNL','MANILA',1,'2026-07-05 16:26:56','2026-07-05 23:51:39'),(2,'BCD','BACOLOD PORT',1,'2026-07-05 17:22:43','2026-07-05 17:22:43'),(3,'BUT','BUTUAN',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(4,'CEB','CEBU',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(5,'CGY','CAGAYAN',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(6,'DVO','DAVAO',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(7,'DGT','DUMAGUETE',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(8,'GES','GEN SAN',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(9,'ILG','ILIGAN',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(10,'ILO','ILOILO',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(11,'OZM','OSAMIS',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(12,'CRN','CORON',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(13,'ROX','ROXAS',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(14,'CTC','CATICLAN',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(15,'ORM','ORMOC',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(16,'TAG','TAGBILARAN',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(17,'TAC','TACLOBAN',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(18,'ZAM','ZAMBOANGA',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(19,'PPS','PUERTO PRINCESSA',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(20,'SUR','SURIGAO',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(21,'COT','COTABATO',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(22,'BTG','BATANGAS',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(202,'P023','PORT 23',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(203,'P024','PORT 24',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(204,'P025','PORT 25',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(205,'P026','PORT 26',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(206,'P027','PORT 27',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(207,'P028','PORT 28',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(208,'P029','PORT 29',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(209,'P030','PORT 30',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(210,'P031','PORT 31',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(211,'P032','PORT 32',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(212,'P033','PORT 33',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(213,'P034','PORT 34',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(214,'P035','PORT 35',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(215,'P036','PORT 36',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(216,'P037','PORT 37',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(217,'P038','PORT 38',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(218,'P039','PORT 39',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(219,'P040','PORT 40',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(220,'P041','PORT 41',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(221,'P042','PORT 42',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(222,'P043','PORT 43',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(223,'P044','PORT 44',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(224,'P045','PORT 45',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(225,'P046','PORT 46',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(226,'P047','PORT 47',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(227,'P048','PORT 48',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(228,'P049','PORT 49',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(229,'P050','PORT 50',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(230,'P051','PORT 51',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(231,'P052','PORT 52',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(232,'P053','PORT 53',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(233,'P054','PORT 54',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(234,'P055','PORT 55',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(235,'P056','PORT 56',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(236,'P057','PORT 57',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(237,'P058','PORT 58',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(238,'P059','PORT 59',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(239,'P060','PORT 60',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(240,'P061','PORT 61',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(241,'P062','PORT 62',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(242,'P063','PORT 63',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(243,'P064','PORT 64',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(244,'P065','PORT 65',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(245,'P066','PORT 66',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(246,'P067','PORT 67',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(247,'P068','PORT 68',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(248,'P069','PORT 69',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(249,'P070','PORT 70',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(250,'P071','PORT 71',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(251,'P072','PORT 72',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(252,'P073','PORT 73',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(253,'P074','PORT 74',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(254,'P075','PORT 75',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(255,'P076','PORT 76',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(256,'P077','PORT 77',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(257,'P078','PORT 78',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(258,'P079','PORT 79',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(259,'P080','PORT 80',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(260,'P081','PORT 81',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(261,'P082','PORT 82',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(262,'P083','PORT 83',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(263,'P084','PORT 84',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(264,'P085','PORT 85',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(265,'P086','PORT 86',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(266,'P087','PORT 87',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(267,'P088','PORT 88',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(268,'P089','PORT 89',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(269,'P090','PORT 90',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(270,'P091','PORT 91',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(271,'P092','PORT 92',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(272,'P093','PORT 93',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(273,'P094','PORT 94',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(274,'P095','PORT 95',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(275,'P096','PORT 96',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(276,'P097','PORT 97',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(277,'P098','PORT 98',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(278,'P099','PORT 99',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(280,'P101','PORT 101',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(281,'P102','PORT 102',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(282,'P103','PORT 103',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(283,'P104','PORT 104',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(284,'P105','PORT 105',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(285,'P106','PORT 106',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(286,'P107','PORT 107',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(287,'P108','PORT 108',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(288,'P109','PORT 109',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(289,'P110','PORT 110',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(290,'P111','PORT 111',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(291,'P112','PORT 112',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(292,'P113','PORT 113',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(293,'P114','PORT 114',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(294,'P115','PORT 115',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(295,'P116','PORT 116',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(296,'P117','PORT 117',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(297,'P118','PORT 118',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(298,'P119','PORT 119',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(299,'P120','PORT 120',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(300,'P121','PORT 121',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(301,'P122','PORT 122',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(302,'P123','PORT 123',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(303,'P124','PORT 124',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(304,'P125','PORT 125',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(305,'P126','PORT 126',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(306,'P127','PORT 127',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(307,'P128','PORT 128',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(308,'P129','PORT 129',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(309,'P130','PORT 130',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(310,'P131','PORT 131',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(311,'P132','PORT 132',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(312,'P133','PORT 133',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(313,'P134','PORT 134',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(314,'P135','PORT 135',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(315,'P136','PORT 136',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(316,'P137','PORT 137',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(317,'P138','PORT 138',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(318,'P139','PORT 139',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(319,'P140','PORT 140',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(320,'P141','PORT 141',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(321,'P142','PORT 142',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(322,'P143','PORT 143',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(323,'P144','PORT 144',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(324,'P145','PORT 145',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(325,'P146','PORT 146',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(326,'P147','PORT 147',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(327,'P148','PORT 148',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(328,'P149','PORT 149',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(329,'P150','PORT 150',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(330,'P151','PORT 151',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(331,'P152','PORT 152',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(332,'P153','PORT 153',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(333,'P154','PORT 154',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(334,'P155','PORT 155',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(335,'P156','PORT 156',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(336,'P157','PORT 157',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(337,'P158','PORT 158',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(338,'P159','PORT 159',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(339,'P160','PORT 160',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(340,'P161','PORT 161',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(341,'P162','PORT 162',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(342,'P163','PORT 163',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(343,'P164','PORT 164',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(344,'P165','PORT 165',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(345,'P166','PORT 166',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(346,'P167','PORT 167',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(347,'P168','PORT 168',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(348,'P169','PORT 169',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(349,'P170','PORT 170',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(350,'P171','PORT 171',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(351,'P172','PORT 172',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(352,'P173','PORT 173',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(353,'P174','PORT 174',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(354,'P175','PORT 175',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(355,'P176','PORT 176',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(356,'P177','PORT 177',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(357,'P178','PORT 178',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(358,'P179','PORT 179',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(359,'P180','PORT 180',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(360,'P181','PORT 181',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(361,'P182','PORT 182',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(362,'P183','PORT 183',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(363,'P184','PORT 184',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(364,'P185','PORT 185',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(365,'P186','PORT 186',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(366,'P187','PORT 187',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(367,'P188','PORT 188',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(368,'P189','PORT 189',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(369,'P190','PORT 190',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(370,'P191','PORT 191',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(371,'P192','PORT 192',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(372,'P193','PORT 193',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(373,'P194','PORT 194',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(374,'P195','PORT 195',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(375,'P196','PORT 196',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(376,'P197','PORT 197',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(377,'P198','PORT 198',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(378,'P199','PORT 199',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(379,'P200','PORT 200',1,'2026-07-06 06:01:22','2026-07-06 06:01:22'),(380,'P100','PORT 100',1,'2026-07-15 17:59:49','2026-07-15 17:59:49');
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposal_status`
--

LOCK TABLES `proposal_status` WRITE;
/*!40000 ALTER TABLE `proposal_status` DISABLE KEYS */;
INSERT INTO `proposal_status` VALUES (1,'Pending',NULL,NULL),(2,'Approved',NULL,NULL),(3,'Disapproved',NULL,NULL),(4,'Accepted',NULL,NULL),(5,'Rejected',NULL,NULL),(6,'On-Hold',NULL,NULL),(7,'Pending',NULL,NULL),(8,'Approved',NULL,NULL),(9,'Disapproved',NULL,NULL),(10,'Accepted',NULL,NULL),(11,'Rejected',NULL,NULL),(12,'On-Hold',NULL,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposals_rates`
--

LOCK TABLES `proposals_rates` WRITE;
/*!40000 ALTER TABLE `proposals_rates` DISABLE KEYS */;
/*!40000 ALTER TABLE `proposals_rates` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `routes`
--

LOCK TABLES `routes` WRITE;
/*!40000 ALTER TABLE `routes` DISABLE KEYS */;
INSERT INTO `routes` VALUES (1,'BUTUAN','BUTUAN',NULL,NULL),(2,'CEBU','CEBU',NULL,NULL),(3,'CAGAYAN','CAGAYAN',NULL,NULL),(4,'DAVAO','DAVAO',NULL,NULL),(5,'DUMAGUETE','DUMAGUETE',NULL,NULL),(6,'GEN SAN','GEN SAN',NULL,NULL),(7,'ILIGAN','ILIGAN',NULL,NULL),(8,'ILOILO','ILOILO',NULL,NULL),(9,'OSAMIS','OSAMIS',NULL,NULL),(10,'CORON','CORON',NULL,NULL),(11,'ROXAS','ROXAS',NULL,NULL),(12,'CATICLAN','CATICLAN',NULL,NULL),(13,'ORMOC','ORMOC',NULL,NULL),(14,'TAGBILARAN','TAGBILARAN',NULL,NULL),(15,'TACLOBAN','TACLOBAN',NULL,NULL),(16,'ZAMBOANGA','ZAMBOANGA',NULL,NULL),(17,'PUERTO PRINCESSA','PUERTO PRINCESSA',NULL,NULL),(18,'SURIGAO','SURIGAO',NULL,NULL),(19,'COTABATO','COTABATO',NULL,NULL),(20,'BATANGAS','BATANGAS',NULL,NULL),(21,'MANILA','MANILA',NULL,NULL),(22,'BUTUAN','BUTUAN',NULL,NULL),(23,'CEBU','CEBU',NULL,NULL),(24,'CAGAYAN','CAGAYAN',NULL,NULL),(25,'DAVAO','DAVAO',NULL,NULL),(26,'DUMAGUETE','DUMAGUETE',NULL,NULL),(27,'GEN SAN','GEN SAN',NULL,NULL),(28,'ILIGAN','ILIGAN',NULL,NULL),(29,'ILOILO','ILOILO',NULL,NULL),(30,'OSAMIS','OSAMIS',NULL,NULL),(31,'CORON','CORON',NULL,NULL),(32,'ROXAS','ROXAS',NULL,NULL),(33,'CATICLAN','CATICLAN',NULL,NULL),(34,'ORMOC','ORMOC',NULL,NULL),(35,'TAGBILARAN','TAGBILARAN',NULL,NULL),(36,'TACLOBAN','TACLOBAN',NULL,NULL),(37,'ZAMBOANGA','ZAMBOANGA',NULL,NULL),(38,'PUERTO PRINCESSA','PUERTO PRINCESSA',NULL,NULL),(39,'SURIGAO','SURIGAO',NULL,NULL),(40,'COTABATO','COTABATO',NULL,NULL),(41,'BATANGAS','BATANGAS',NULL,NULL),(42,'MANILA','MANILA',NULL,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_type`
--

LOCK TABLES `service_type` WRITE;
/*!40000 ALTER TABLE `service_type` DISABLE KEYS */;
INSERT INTO `service_type` VALUES (1,'ORIGIN','DOOR',NULL,NULL),(2,'ORIGIN','PIER-STUFFING',NULL,NULL),(3,'ORIGIN','PIER-VANOUT',NULL,NULL),(4,'DESTINATION','DOOR',NULL,NULL),(5,'DESTINATION','PIER-STRIPPING',NULL,NULL),(6,'DESTINATION','PIER-VAN OUT',NULL,NULL),(7,'ORIGIN','DOOR',NULL,NULL),(8,'ORIGIN','PIER-STUFFING',NULL,NULL),(9,'ORIGIN','PIER-VANOUT',NULL,NULL),(10,'DESTINATION','DOOR',NULL,NULL),(11,'DESTINATION','PIER-STRIPPING',NULL,NULL),(12,'DESTINATION','PIER-VAN OUT',NULL,NULL);
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
  `port_id` bigint(20) unsigned NOT NULL,
  `area_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`area_id`),
  UNIQUE KEY `serviceable_areas_port_id_area_name_unique` (`port_id`,`area_name`),
  CONSTRAINT `serviceable_areas_port_id_foreign` FOREIGN KEY (`port_id`) REFERENCES `ports` (`port_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `serviceable_areas`
--

LOCK TABLES `serviceable_areas` WRITE;
/*!40000 ALTER TABLE `serviceable_areas` DISABLE KEYS */;
INSERT INTO `serviceable_areas` VALUES (1,1,'Manila City',1,'2026-07-05 17:22:06','2026-07-05 17:22:06'),(2,2,'Bacolod City',1,'2026-07-05 17:23:06','2026-07-05 17:23:06'),(3,1,'Pasay',1,'2026-07-05 17:23:14','2026-07-05 17:23:14'),(4,1,'Caloocan',1,'2026-07-06 01:33:46','2026-07-06 01:33:46'),(5,1,'Quezon City',1,'2026-07-06 01:34:14','2026-07-06 01:34:14'),(6,22,'Bauan',1,'2026-07-06 01:37:22','2026-07-06 01:37:22'),(7,22,'Batangas City',1,'2026-07-06 01:37:32','2026-07-06 01:37:32');
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
INSERT INTO `sessions` VALUES ('AfjjhwnzrsKYqevPNXprn28WatAMDhUO5u2n83I8',NULL,'127.0.0.1','Symfony','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMm5rQ2ltNDZQVW0xOFZTSVdsV1lUdEtrbWxxWXlNRGV0U2ZxUldPMyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTY6Imh0dHA6Ly9sb2NhbGhvc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1784742944),('GCDX1nEueoyWAzItGPArSMpl9r6mE9s1RtVuefJu',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVldzbWRaajlXa3J1bERYbm5ycDBiTTRrRjVkdVFzYjF5N1JUdWN5RSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly9rYXJnYW1pbmVfcHJvdG90eXBlLnRlc3QvbG9naW4iO319',1784837489),('ipplfFgO9HI6N3mESg0sEnEprVqWqVinH4MOa9eS',NULL,'127.0.0.1','Symfony','YTo0OntzOjY6Il90b2tlbiI7czo0MDoicjN2S21iRXp5VE1qYkszSzZlc3phbHlPWkFqeWtNOTBuRXMwRkhpYSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNzoiaHR0cDovL2xvY2FsaG9zdC9hcGkvb3B0aW9ucy8xL3ZhbHVlcyI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM3OiJodHRwOi8vbG9jYWxob3N0L2FwaS9vcHRpb25zLzEvdmFsdWVzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1784665652),('jeQnFeLzy4OYVlJeIL9UF55sin6F64qXFBINTobs',NULL,'127.0.0.1','Symfony','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRFR0OHVTNzNmRk1JbjAxVGxQanpwZTRvT2tEMFBxV1c1bW9JSGJ6TiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTY6Imh0dHA6Ly9sb2NhbGhvc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1784742300),('On8c7yfq8jj0QGznkrOqpSz1y03YiRaeyQeXTiP4',NULL,'127.0.0.1','curl/8.12.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoianpwdWh1OXA1aWNBOU1zdFBvNkF2akh1Q3MzZDdyRWdvQXVQTXZIcyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly9rYXJnYW1pbmVfcHJvdG90eXBlLnRlc3QvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1784736919),('OtqRFNLDRPU4Aq5y6VhTN2RYFG9kOoy9amcPRwUE',NULL,'127.0.0.1','Symfony','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUUNOc0ZmQVBkMjl5UzdkOGFiOTV0NjhPVDFJTXpmdVN6a2VHYjFUciI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTY6Imh0dHA6Ly9sb2NhbGhvc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1784743391),('qN3LcwcNeirTTZEkYNEVfjCq2JzWNeftuTPyaNVv',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoicVdvbzdsT3F3ZXpHN0tjU1ZSbm93cFBPRHltSWk5bnZOeFl1VWZENSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly9rYXJnYW1pbmVfcHJvdG90eXBlLnRlc3QvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1784737247),('rIc3bgZZRszvEqZqBKM18Yb0S2i3fUrxjvx8RlGN',NULL,'127.0.0.1','Symfony','YTozOntzOjY6Il90b2tlbiI7czo0MDoiV0JGZHk5NmRGRUN5ZWs3RnBXYlhITzluQWlrT2J4YXdObGo4MnRRTCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTY6Imh0dHA6Ly9sb2NhbGhvc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1784742534),('wWT4iHGnGIOXp4hmMu0AND0t4hl7vd4idqqznnGS',NULL,'127.0.0.1','Symfony','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSkM5YjQ2N09KbzY5aGR0czdCY0xEdFlYajRWS0p2SndUbnJLT0ZGSCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTY6Imh0dHA6Ly9sb2NhbGhvc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1784740369),('xJKXcUBH9lUUvtgUbgm4kjNhBhwKzNyYGE0Mw7eb',NULL,'127.0.0.1','Symfony','YTozOntzOjY6Il90b2tlbiI7czo0MDoicVRnM2dKTXZyQWljZGJXR3F0cHlqNTBWV25CNXBZY0x1RnpkejBjSSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTY6Imh0dHA6Ly9sb2NhbGhvc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1784741631),('Xltjvazjz9B2D1KZ9qNHVfvyj4MceJlVhNHYwi3d',NULL,'127.0.0.1','Symfony','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaXVNZ0Rhckl4UUJzT1c5NmVMcnVKY29UNkpsTjlyR3JDY2RPMVBvQyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTY6Imh0dHA6Ly9sb2NhbGhvc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1784743300),('yCVdNKN3z0OH1uUsyWFNKGhkYn246UVwLqrUbWZG',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVldDZzBZUnNsVXJTajZwUjc5MjRwOUo3Y3FUNU1RVktnRDFHRVRhYyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly9rYXJnYW1pbmVfcHJvdG90eXBlLnRlc3QvP2hlcmQ9cHJldmlldyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1784737247);
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trucking_tariffs`
--

LOCK TABLES `trucking_tariffs` WRITE;
/*!40000 ALTER TABLE `trucking_tariffs` DISABLE KEYS */;
INSERT INTO `trucking_tariffs` VALUES (1,2,1,22700.00,'2026-07-05',NULL,1,'2026-07-05 17:24:27','2026-07-05 17:24:27'),(2,3,1,50000.00,'2026-07-06',NULL,1,'2026-07-06 01:34:55','2026-07-06 01:34:55');
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_department`
--

LOCK TABLES `user_department` WRITE;
/*!40000 ALTER TABLE `user_department` DISABLE KEYS */;
INSERT INTO `user_department` VALUES (1,'Sales Department','2026-07-02 14:44:02','2026-07-02 14:44:02'),(2,'Operations Department','2026-07-02 14:44:02','2026-07-02 14:44:02'),(3,'Sales Department','2026-07-15 17:59:49','2026-07-15 17:59:49'),(4,'Operations Department','2026-07-15 17:59:49','2026-07-15 17:59:49');
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_status`
--

LOCK TABLES `user_status` WRITE;
/*!40000 ALTER TABLE `user_status` DISABLE KEYS */;
INSERT INTO `user_status` VALUES (1,'Active','2026-07-02 14:44:24','2026-07-02 14:44:24'),(2,'Inactive','2026-07-02 14:44:24','2026-07-02 14:44:24'),(3,'Suspended','2026-07-02 14:44:24','2026-07-02 14:44:24'),(4,'Pending','2026-07-02 14:44:24','2026-07-02 14:44:24'),(5,'Active','2026-07-15 17:59:49','2026-07-15 17:59:49'),(6,'Inactive','2026-07-15 17:59:49','2026-07-15 17:59:49'),(7,'Suspended','2026-07-15 17:59:49','2026-07-15 17:59:49'),(8,'Pending','2026-07-15 17:59:49','2026-07-15 17:59:49');
/*!40000 ALTER TABLE `user_status` ENABLE KEYS */;
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
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Developer','superadmin@email.com',NULL,'$2y$12$xTR2YTyiLHr9R8JlOF9Xb.oKJWVMaPCpXHs52w7QuegslbVpYHCsm','1',0,NULL,NULL,'2026-06-28 15:57:34','2026-07-15 18:02:51'),(2,'Synxcel Gabby','gabriel.david@email.com',NULL,'$2y$12$xTR2YTyiLHr9R8JlOF9Xb.oKJWVMaPCpXHs52w7QuegslbVpYHCsm','1',0,NULL,NULL,'2026-06-28 15:57:34','2026-06-28 15:57:34'),(4,'Synxcel Minton','minton.diaz@email.com',NULL,'$2y$12$xTR2YTyiLHr9R8JlOF9Xb.oKJWVMaPCpXHs52w7QuegslbVpYHCsm','1',0,NULL,NULL,'2026-06-28 15:57:34','2026-07-15 18:29:01'),(6,'Kargamine User','user.kargamine@email.com',NULL,'$2y$12$xTR2YTyiLHr9R8JlOF9Xb.oKJWVMaPCpXHs52w7QuegslbVpYHCsm','1',0,NULL,NULL,'2026-06-28 15:57:34','2026-06-28 15:57:34');
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
  PRIMARY KEY (`vat_rate_id`),
  UNIQUE KEY `vat_rates_effective_date_unique` (`effective_date`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vat_rates`
--

LOCK TABLES `vat_rates` WRITE;
/*!40000 ALTER TABLE `vat_rates` DISABLE KEYS */;
INSERT INTO `vat_rates` VALUES (1,10.00,'2026-07-05',NULL,1,'2026-07-05 17:24:53','2026-07-05 17:24:53');
/*!40000 ALTER TABLE `vat_rates` ENABLE KEYS */;
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

-- Dump completed on 2026-07-24  4:19:29

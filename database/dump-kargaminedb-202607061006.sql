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
INSERT INTO `cache` VALUES ('management_system_cache_d2bfa8e8b749d2772a21edee7b70a2b3','i:1;',1782808759),('management_system_cache_d2bfa8e8b749d2772a21edee7b70a2b3:timer','i:1782808759;',1782808759),('management_system_cache_df21bfa12c4e294c70f64916c0fbc9a5:timer','i:1782837462;',1782837462),('management_system_cache_e7cf66797159dc3cd3e85f72e15bb551','i:8;',1783303318),('management_system_cache_e7cf66797159dc3cd3e85f72e15bb551:timer','i:1783303318;',1783303318),('management_system_cache_f1f70ec40aaa556905d4a030501c0ba4','i:8;',1782828430),('management_system_cache_f1f70ec40aaa556905d4a030501c0ba4:timer','i:1782828430;',1782828430);
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
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`charge_type_id`),
  UNIQUE KEY `charge_types_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `charge_types`
--

LOCK TABLES `charge_types` WRITE;
/*!40000 ALTER TABLE `charge_types` DISABLE KEYS */;
INSERT INTO `charge_types` VALUES (1,'DOC_STAMP','Port Doc Stamp',1,'2026-07-05 16:55:20','2026-07-05 16:55:20'),(2,'GATE_FEE','Port Gate Fee',1,'2026-07-05 17:26:08','2026-07-05 17:26:08');
/*!40000 ALTER TABLE `charge_types` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `container_class`
--

LOCK TABLES `container_class` WRITE;
/*!40000 ALTER TABLE `container_class` DISABLE KEYS */;
INSERT INTO `container_class` VALUES (1,'A',NULL,NULL),(2,'B',NULL,NULL),(3,'C',NULL,NULL),(4,'D',NULL,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `container_size`
--

LOCK TABLES `container_size` WRITE;
/*!40000 ALTER TABLE `container_size` DISABLE KEYS */;
INSERT INTO `container_size` VALUES (1,'10-FOOTER',NULL,NULL),(2,'20-FOOTER',NULL,NULL),(3,'40-FOOTER STD',NULL,NULL),(4,'40-FOOTER HC',NULL,NULL);
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
INSERT INTO `container_type` VALUES (1,'DRY VAN/CON VAN',NULL,NULL),(2,'FLATRACK (PLATFORM)',NULL,NULL),(3,'REEFER',NULL,NULL),(4,'HIGH CUBE',NULL,NULL),(5,'CATTLE VAN',NULL,NULL),(6,'TANK (ISO TANK)',NULL,NULL),(7,'ROLLING CARGO',NULL,NULL),(8,'SPECIAL CONTAINERS',NULL,NULL),(9,'OPEN-TOP VAN',NULL,NULL);
/*!40000 ALTER TABLE `container_type` ENABLE KEYS */;
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
INSERT INTO `contract_rates` VALUES (1,4,'Manila','Cebu',1,1,1,20,1,2,'PERCENTAGE',10.00,1,'2026-07-03 19:00:07','2026-07-03 19:00:07'),(2,4,'Manila','Davao',2,2,1,40,1,1,'FIXED',1500.00,1,'2026-07-03 19:00:07','2026-07-03 19:00:07'),(3,11,'Manila','Cebu',1,1,1,20,1,2,'PERCENTAGE',10.00,1,'2026-07-03 19:00:38','2026-07-03 19:00:38'),(4,11,'Manila','Davao',2,2,1,40,1,1,'FIXED',1500.00,1,'2026-07-03 19:00:38','2026-07-03 19:00:38'),(5,12,'21','2',6,1,1,2,1,4,'PERCENTAGE',11.00,1,'2026-07-06 00:21:33','2026-07-06 00:21:33'),(6,12,'20','10',2,2,2,2,2,5,'PERCENTAGE',11.00,1,'2026-07-06 00:21:33','2026-07-06 00:21:33'),(7,13,'21','2',6,1,1,2,1,4,'PERCENTAGE',10.00,1,'2026-07-06 01:23:20','2026-07-06 01:23:20'),(8,13,'20','10',2,2,2,2,2,5,'PERCENTAGE',10.00,1,'2026-07-06 01:23:20','2026-07-06 01:23:20');
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
INSERT INTO `contracts` VALUES (4,'a22c2740-28a9-49a0-a6f8-ff2f5dff2e30','PR-202606-0001',1,1,'2026-07-04','2026-07-04','2027-07-03',1,NULL,6,'2026-07-03 19:00:07','2026-07-03 19:00:07'),(11,'a22c276e-50a4-4e46-8e0c-3e51ea958336','PR-202606-0002',1,1,'2026-07-04','2026-07-04','2027-07-03',1,NULL,6,'2026-07-03 19:00:38','2026-07-03 19:00:38'),(12,'333ea153-dcb1-4932-adf9-aad660d75572','CTR-202607-0001',8,15,'2026-07-02','2026-07-02','2026-07-16',2,'test',6,'2026-07-06 00:21:33','2026-07-06 00:21:33'),(13,'3f5b5c55-c299-429f-a2dd-95c6a86fd9c7','CTR-202607-0002',8,15,'2026-07-01','2026-07-01','2027-07-01',2,'test',6,'2026-07-06 01:23:20','2026-07-06 01:23:20');
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
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `crm_activities_lead_id_foreign` (`lead_id`),
  KEY `crm_activities_created_by_foreign` (`created_by`),
  CONSTRAINT `crm_activities_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `crm_activities_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `crm_leads` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_activities`
--

LOCK TABLES `crm_activities` WRITE;
/*!40000 ALTER TABLE `crm_activities` DISABLE KEYS */;
INSERT INTO `crm_activities` VALUES (1,15,'Proposal Status Change','Proposal with code: PR-202606-0008 has been Approved by:Kargamine User',6,'2026-07-01 00:43:15','2026-07-01 00:43:15'),(2,10,'Proposal Status Change','Proposal with code: PR-202606-0006 has been Approved by:Kargamine User',6,'2026-07-01 00:43:29','2026-07-01 00:43:29'),(3,10,'Proposal Status Change','Proposal with code: PR-202606-0006 has been Disapproved by:Kargamine User',6,'2026-07-01 00:43:34','2026-07-01 00:43:34'),(4,5,'Proposal Status Change','Proposal with code: PR-202606-0003 has been Approved by:Kargamine User',6,'2026-07-01 00:45:10','2026-07-01 00:45:10'),(5,4,'Proposal Status Change','Proposal with code: PR-202606-0002 has been Approved by:Kargamine User',6,'2026-07-01 00:46:03','2026-07-01 00:46:03'),(6,4,'Proposal Status Change','Proposal with code: PR-202606-0002 has been Approved by:Kargamine User',6,'2026-07-01 00:48:22','2026-07-01 00:48:22'),(7,4,'Proposal Status Change','Proposal with code: PR-202606-0002 has been Approved by:Kargamine User',6,'2026-07-01 00:52:32','2026-07-01 00:52:32'),(8,5,'Proposal Status Change','Proposal with code: PR-202606-0003 has been Approved by:Kargamine User',6,'2026-07-01 00:55:10','2026-07-01 00:55:10'),(9,10,'Proposal Status Change','Proposal with code: PR-202606-0006 has been Approved by:Kargamine User',6,'2026-07-01 00:55:15','2026-07-01 00:55:15'),(10,15,'Proposal Status Change','Proposal with code: PR-202606-0008 has been Approved by:Kargamine User',6,'2026-07-01 00:55:18','2026-07-01 00:55:18');
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
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authorized_signatory_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authorized_signatory_position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `crm_company_info_lead_id_foreign` (`lead_id`),
  CONSTRAINT `crm_company_info_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `crm_leads` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_company_info`
--

LOCK TABLES `crm_company_info` WRITE;
/*!40000 ALTER TABLE `crm_company_info` DISABLE KEYS */;
INSERT INTO `crm_company_info` VALUES (1,1,'synxcel','pasig  city','gabriel david','CEO','2026-06-29 06:59:41','2026-06-29 07:01:03'),(3,3,'XYZ',NULL,NULL,NULL,'2026-06-29 07:08:45','2026-06-29 07:08:45'),(4,4,'ZYX','pasig city','Kim Domingez','Manager','2026-06-29 07:09:52','2026-06-29 07:11:07'),(5,5,'Bento Mac.','San Pascual Batangas','Shaina Magdayao','Jean Garcia','2026-06-29 23:33:42','2026-06-29 23:35:58'),(8,8,'test',NULL,NULL,NULL,'2026-06-30 02:50:51','2026-06-30 02:50:51'),(9,9,'test1',NULL,NULL,NULL,'2026-06-30 03:10:35','2026-06-30 03:10:35'),(10,10,'LazShopee','P Burgos Manila','Mario Jose','Marketing Director','2026-06-30 21:21:10','2026-06-30 21:23:18'),(15,15,'Lazshopee','San Pedro Laguna','Vito Cruz','Sales Director','2026-06-30 23:52:32','2026-06-30 23:57:11');
/*!40000 ALTER TABLE `crm_company_info` ENABLE KEYS */;
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
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assigned_to` bigint(20) unsigned DEFAULT NULL,
  `estimated_value` decimal(12,2) DEFAULT NULL,
  `expected_close_date` date DEFAULT NULL,
  `status_updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `crm_leads_uuid_unique` (`uuid`),
  KEY `crm_leads_assigned_to_foreign` (`assigned_to`),
  CONSTRAINT `crm_leads_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_leads`
--

LOCK TABLES `crm_leads` WRITE;
/*!40000 ALTER TABLE `crm_leads` DISABLE KEYS */;
INSERT INTO `crm_leads` VALUES (1,'a221d7cc-d4ab-409e-9d42-a754572211d7','Minton Diaz','minton@email.com','0912 345 6768',NULL,3,'facebook',1,100000.00,'2026-07-05','2026-06-29 06:59:41','2026-06-29 06:59:41','2026-06-29 07:01:03'),(3,'a221db0a-9529-4810-83ad-f74b9ddd54e4','Notnim Zaid','testemail@email.com','0987 654 2312',NULL,1,'Event',1,250000.00,'2026-07-06','2026-06-29 07:08:45','2026-06-29 07:08:45','2026-06-29 07:08:45'),(4,'a221db71-93f8-4f80-b595-da147dedc5e0','Zaid Notnim','ziad@email.com','0901 234 5678',NULL,3,'Inquiry',1,50000.00,'2026-07-06','2026-06-29 07:09:52','2026-06-29 07:09:52','2026-06-29 07:11:07'),(5,'a2233b49-ca98-4708-8f29-ecd64d415a9a','Marjorie Batero','Marj@Batero.com','0915 248 5965',NULL,3,'FB Page',1,1000000.00,'2026-07-06','2026-06-29 23:33:42','2026-06-29 23:33:42','2026-06-29 23:35:58'),(8,'a22381cb-c8d2-43ad-ab3d-7ce803c03a59','test','test@email.com','1234 56',NULL,1,'test',1,123456.00,'2026-07-06','2026-06-30 02:50:51','2026-06-30 02:50:51','2026-06-30 02:50:51'),(9,'a22388da-1c1f-4f45-ac7b-86bb35efa0a3','test1','test1@email.com','1231 23',NULL,1,'test1',1,123123.00,'2026-07-06','2026-06-30 03:10:35','2026-06-30 03:10:35','2026-06-30 03:10:35'),(10,'a2250edf-df9b-49f9-ba21-7a1d1e21839d','Marie Chan','Marie@chan.com','0909 090 9090',NULL,3,'Instagram',1,5000000.00,'2026-07-07','2026-06-30 21:21:10','2026-06-30 21:21:10','2026-06-30 21:23:18'),(15,'a2254501-61c3-44d3-8f7b-84cd12c8bf72','Jose Marie','Jose@marie.com','0915 325 9845',NULL,3,'FB Page',1,2500000.00,'2026-07-07','2026-06-30 23:52:32','2026-06-30 23:52:32','2026-06-30 23:52:32');
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_notes`
--

LOCK TABLES `crm_notes` WRITE;
/*!40000 ALTER TABLE `crm_notes` DISABLE KEYS */;
INSERT INTO `crm_notes` VALUES (1,1,NULL,1,'2026-06-29 06:59:41','2026-06-29 06:59:41'),(2,4,'asking for quotation',1,'2026-06-29 07:09:52','2026-06-29 07:09:52'),(3,5,'2 convan\r\n1 flatrack\r\n2 loose',1,'2026-06-29 23:33:42','2026-06-29 23:33:42'),(6,8,'test',1,'2026-06-30 02:50:51','2026-06-30 02:50:51'),(7,9,'test1',1,'2026-06-30 03:10:35','2026-06-30 03:10:35'),(8,10,'perfumes',1,'2026-06-30 21:21:10','2026-06-30 21:21:10'),(12,15,'6 convans',1,'2026-06-30 23:52:32','2026-06-30 23:52:32');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_type`
--

LOCK TABLES `customer_type` WRITE;
/*!40000 ALTER TABLE `customer_type` DISABLE KEYS */;
INSERT INTO `customer_type` VALUES (1,'SHIPPER',NULL,NULL),(2,'CONSIGNEE',NULL,NULL),(3,'SHIPPER-CONSIGNEE',NULL,NULL);
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
-- Table structure for table `lane_tariff_rates`
--

DROP TABLE IF EXISTS `lane_tariff_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lane_tariff_rates` (
  `rate_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lane_id` bigint(20) unsigned NOT NULL,
  `frt` decimal(12,2) NOT NULL DEFAULT 0.00,
  `bsc` decimal(12,2) NOT NULL DEFAULT 0.00,
  `ra` decimal(12,2) NOT NULL DEFAULT 0.00,
  `gri` decimal(12,2) NOT NULL DEFAULT 0.00,
  `effective_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`rate_id`),
  UNIQUE KEY `lane_tariff_rates_lane_id_effective_date_unique` (`lane_id`,`effective_date`),
  KEY `lane_tariff_rates_lane_id_is_active_index` (`lane_id`,`is_active`),
  CONSTRAINT `lane_tariff_rates_lane_id_foreign` FOREIGN KEY (`lane_id`) REFERENCES `lanes` (`lane_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lane_tariff_rates`
--

LOCK TABLES `lane_tariff_rates` WRITE;
/*!40000 ALTER TABLE `lane_tariff_rates` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lanes`
--

LOCK TABLES `lanes` WRITE;
/*!40000 ALTER TABLE `lanes` DISABLE KEYS */;
INSERT INTO `lanes` VALUES (1,1,2,1,'2026-07-05 17:22:54','2026-07-05 17:22:54'),(2,1,4,1,'2026-07-06 01:27:34','2026-07-06 01:27:34');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `list_of_values_table`
--

LOCK TABLES `list_of_values_table` WRITE;
/*!40000 ALTER TABLE `list_of_values_table` DISABLE KEYS */;
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
INSERT INTO `mailer_settings` VALUES (1,'smtp','smtp.office365.com','587','minton.diaz@synxcel.com','R$8wZ1^kNq5!Ty3','tls','no-reply@notification.synxcel.com','Synxcel Notification','2026-07-01 05:16:45','2026-07-01 07:27:12');
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
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2025_10_01_211540_create_nav_menus_table',1),(6,'2025_10_02_163004_create_table_for_settings_role',1),(7,'2025_10_03_180527_add_parentmenu',1),(8,'2025_10_04_144632_create_mailer_settings_table',1),(9,'2025_11_06_035725_insert_order_in_nav_menus',1),(10,'2026_01_24_221131_create_sessions_table',1),(11,'2026_01_24_221645_add_session_id_to_users_table',1),(12,'2026_01_24_223037_create_cache_table',1),(13,'2026_01_26_110424_create_jobs_table',1),(14,'2026_02_03_122024_modify_columns_of_user_table',1),(15,'2026_05_01_011537_options_table',1),(16,'2026_05_01_011632_list_of_value_table',1),(17,'2026_05_05_052405_create_company_info_master_table',1),(18,'2026_05_05_052441_create_contact_info_table',1),(19,'2026_05_05_052453_create_trade_references_table',1),(20,'2026_05_05_052548_create_services_info_table',1),(21,'2026_05_05_052612_create_company_finance_table',1),(22,'2026_05_05_052631_create_billed_details_table',1),(23,'2026_05_05_052656_create_sales_info_table',1),(24,'2026_05_05_052712_create_stages_info_table',1),(25,'2026_05_07_205424_create_e_invoice_table',1),(26,'2026_05_07_205616_create_courier_invoice_table',1),(27,'2026_06_05_045259_create_crm_status_table',1),(28,'2026_06_05_045351_create_crm_leads_table',1),(29,'2026_06_05_045416_create_company_info_table',1),(30,'2026_06_05_045430_create_crm_notes_table',1),(31,'2026_06_05_045450_create_crm_activities_table',1),(32,'2026_06_15_230541_proposals',1),(33,'2026_06_15_231224_proposal_rates',1),(34,'2026_06_15_233415_create_table_for_routes',1),(35,'2026_06_15_233633_create_table_service_type',1),(36,'2026_06_15_233806_create_proposal_status',1),(37,'2026_06_15_233848_create_customer_type',1),(38,'2026_06_26_195636_create_container_type',1),(39,'2026_06_26_195710_create_container_class',1),(40,'2026_06_26_195725_create_container_size',1),(44,'2026_07_02_221007_add_new_columns_to_user_table',2),(45,'2026_07_02_222100_create_user_department',2),(46,'2026_07_02_222118_create_user_status',2),(47,'2026_07_04_000001_create_ports_table',3),(48,'2026_07_04_000002_create_serviceable_areas_table',3),(49,'2026_07_04_000003_create_delivery_types_table',3),(50,'2026_07_04_000004_create_charge_types_table',3),(51,'2026_07_04_000005_create_lanes_table',3),(52,'2026_07_04_000006_create_lane_tariff_rates_table',3),(53,'2026_07_04_000007_create_port_charges_table',3),(54,'2026_07_04_000008_create_handling_fees_table',3),(55,'2026_07_04_000009_create_trucking_tariffs_table',3),(56,'2026_07_04_000010_create_vat_rates_table',3),(57,'2026_07_04_000011_create_contracts_table',3),(58,'2026_07_04_000012_create_contract_rates_table',3),(59,'2026_07_04_000013_create_bookings_table',3),(60,'2026_07_04_000014_create_booking_port_charges_table',3);
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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nav_menus`
--

LOCK TABLES `nav_menus` WRITE;
/*!40000 ALTER TABLE `nav_menus` DISABLE KEYS */;
INSERT INTO `nav_menus` VALUES (1,'Dashboard','fas fa-home','/page_dashboard','[\"1\"]',0,0,'2026-06-28 15:57:34','2026-06-28 15:57:34'),(2,'User Management','fas fa-users','/page_users','[]',0,9,'2026-06-28 15:57:34','2026-07-01 00:41:14'),(3,'Developer Option','fas fa-users','#','[\"1\"]',0,13,'2026-06-28 15:57:34','2026-06-28 15:57:34'),(4,'Mailer','','/page_mailer','[\"1\"]',3,1,'2026-06-28 15:57:34','2026-06-28 15:57:34'),(5,'Menus','','/page_menus','[\"1\"]',3,2,'2026-06-28 15:57:34','2026-06-28 15:57:34'),(6,'Settings',NULL,'#','[\"1\",\"2\",\"3\",\"4\"]',0,12,'2026-06-28 15:57:34','2026-07-05 16:08:31'),(8,'Clients',NULL,'/page_clients','[\"1\",\"2\",\"3\",\"4\"]',0,6,'2026-06-28 15:57:34','2026-07-01 12:09:58'),(9,'Contracts',NULL,'/page_contracts','[\"1\",\"2\",\"3\",\"4\"]',0,3,'2026-06-28 15:57:34','2026-07-06 00:24:55'),(10,'Reports',NULL,'/page_reports','[\"1\",\"2\",\"3\",\"4\"]',0,7,'2026-06-28 15:57:34','2026-07-01 00:41:11'),(11,'Lookup Values','','/page_lookupValues','[\"1\"]',3,3,'2026-06-28 15:57:34','2026-06-28 15:57:34'),(12,'CRM',NULL,'/page_crm','[\"1\",\"2\",\"3\",\"4\"]',0,1,'2026-06-28 15:57:34','2026-07-01 00:41:27'),(13,'Proposals',NULL,'page_proposals','[\"1\",\"2\",\"3\",\"4\"]',0,2,'2026-07-01 00:40:38','2026-07-01 00:40:38'),(17,'Users',NULL,'page_usermanagement','[\"1\",\"2\",\"3\",\"4\"]',0,10,'2026-07-01 16:57:16','2026-07-01 16:57:54'),(18,'Maintenance',NULL,'page_maintenance','[\"1\",\"2\",\"3\",\"4\"]',6,11,'2026-07-05 16:07:58','2026-07-05 16:08:59');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `options_table`
--

LOCK TABLES `options_table` WRITE;
/*!40000 ALTER TABLE `options_table` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=202 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ports`
--

LOCK TABLES `ports` WRITE;
/*!40000 ALTER TABLE `ports` DISABLE KEYS */;
INSERT INTO `ports` VALUES (1,'MNL','MANILA',1,'2026-07-05 16:26:56','2026-07-05 23:51:39'),(2,'BCD','BACOLOD PORT',1,'2026-07-05 17:22:43','2026-07-05 17:22:43'),(3,'BUT','BUTUAN',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(4,'CEB','CEBU',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(5,'CGY','CAGAYAN',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(6,'DVO','DAVAO',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(7,'DGT','DUMAGUETE',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(8,'GES','GEN SAN',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(9,'ILG','ILIGAN',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(10,'ILO','ILOILO',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(11,'OZM','OSAMIS',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(12,'CRN','CORON',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(13,'ROX','ROXAS',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(14,'CTC','CATICLAN',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(15,'ORM','ORMOC',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(16,'TAG','TAGBILARAN',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(17,'TAC','TACLOBAN',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(18,'ZAM','ZAMBOANGA',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(19,'PPS','PUERTO PRINCESSA',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(20,'SUR','SURIGAO',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(21,'COT','COTABATO',1,'2026-07-05 23:51:39','2026-07-05 23:51:39'),(22,'BTG','BATANGAS',1,'2026-07-05 23:51:39','2026-07-05 23:51:39');
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
INSERT INTO `proposal_status` VALUES (1,'Pending',NULL,NULL),(2,'Approved',NULL,NULL),(3,'Disapproved',NULL,NULL),(4,'Accepted',NULL,NULL),(5,'Rejected',NULL,NULL),(6,'On-Hold',NULL,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposals`
--

LOCK TABLES `proposals` WRITE;
/*!40000 ALTER TABLE `proposals` DISABLE KEYS */;
INSERT INTO `proposals` VALUES (1,'a221d84a-4376-4584-bb08-2ccf2ac18b38','PR-202606-0001',1,1,1,'2026-06-29 07:01:03','2026-06-29 07:01:03'),(2,'a221dbe3-e29d-4d75-b055-d1772a6fa2a7','PR-202606-0002',4,1,2,'2026-06-29 07:11:07','2026-07-01 00:52:32'),(3,'a2233c19-8ef2-48c4-95bc-6bced986115f','PR-202606-0003',5,1,2,'2026-06-29 23:35:58','2026-07-01 00:55:10'),(6,'a2250fa2-c4b0-482a-b792-b818c70f933e','PR-202606-0006',10,1,2,'2026-06-30 21:23:18','2026-07-01 00:55:15'),(8,'a22546ac-173a-4e5f-bc55-148ffeca83f8','PR-202606-0008',15,1,2,'2026-06-30 23:57:11','2026-07-01 00:55:18');
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposals_rates`
--

LOCK TABLES `proposals_rates` WRITE;
/*!40000 ALTER TABLE `proposals_rates` DISABLE KEYS */;
INSERT INTO `proposals_rates` VALUES (1,1,45000,'21','2',5,1,4,2,1,4,'2026-06-29 07:01:03','2026-06-29 07:01:03'),(2,2,12000,'21','8',4,1,1,2,1,4,'2026-06-29 07:11:07','2026-06-29 07:11:07'),(3,3,20000,'21','2',1,1,1,2,1,4,'2026-06-29 23:35:58','2026-06-29 23:35:58'),(4,3,50000,'20','17',2,3,1,4,2,5,'2026-06-29 23:37:34','2026-06-29 23:37:34'),(7,6,30000,'21','3',2,1,1,2,1,4,'2026-06-30 21:23:18','2026-06-30 21:23:18'),(9,8,30000,'21','2',6,1,1,2,1,4,'2026-06-30 23:57:11','2026-06-30 23:57:11'),(10,8,15000,'20','10',2,2,2,2,2,5,'2026-06-30 23:59:02','2026-06-30 23:59:02');
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `routes`
--

LOCK TABLES `routes` WRITE;
/*!40000 ALTER TABLE `routes` DISABLE KEYS */;
INSERT INTO `routes` VALUES (1,'BUTUAN','BUTUAN',NULL,NULL),(2,'CEBU','CEBU',NULL,NULL),(3,'CAGAYAN','CAGAYAN',NULL,NULL),(4,'DAVAO','DAVAO',NULL,NULL),(5,'DUMAGUETE','DUMAGUETE',NULL,NULL),(6,'GEN SAN','GEN SAN',NULL,NULL),(7,'ILIGAN','ILIGAN',NULL,NULL),(8,'ILOILO','ILOILO',NULL,NULL),(9,'OSAMIS','OSAMIS',NULL,NULL),(10,'CORON','CORON',NULL,NULL),(11,'ROXAS','ROXAS',NULL,NULL),(12,'CATICLAN','CATICLAN',NULL,NULL),(13,'ORMOC','ORMOC',NULL,NULL),(14,'TAGBILARAN','TAGBILARAN',NULL,NULL),(15,'TACLOBAN','TACLOBAN',NULL,NULL),(16,'ZAMBOANGA','ZAMBOANGA',NULL,NULL),(17,'PUERTO PRINCESSA','PUERTO PRINCESSA',NULL,NULL),(18,'SURIGAO','SURIGAO',NULL,NULL),(19,'COTABATO','COTABATO',NULL,NULL),(20,'BATANGAS','BATANGAS',NULL,NULL),(21,'MANILA','MANILA',NULL,NULL);
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
INSERT INTO `service_type` VALUES (1,'ORIGIN','DOOR',NULL,NULL),(2,'ORIGIN','PIER-STUFFING',NULL,NULL),(3,'ORIGIN','PIER-VANOUT',NULL,NULL),(4,'DESTINATION','DOOR',NULL,NULL),(5,'DESTINATION','PIER-STRIPPING',NULL,NULL),(6,'DESTINATION','PIER-VAN OUT',NULL,NULL);
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
INSERT INTO `sessions` VALUES ('0Hh3vuJxSOlO71vN6DemElM361fn4fIAyJz3tAzU',NULL,'103.36.18.156','Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUnlEZWtRWW5qb0d5ZzlaVGIzTnQ2eHlHTnFjTjVMZTEyU05lTnFhUyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODA6Imh0dHBzOi8va2FyZ2FtaW5lLnN5bnhjZWwuY29tL2FwaS9jcm0vbGVhZHMvYTIyMzg4ZGEtMWMxZi00ZjQ1LWFjN2ItODZiYjM1ZWZhMGEzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1782739008),('2w7pSe9xSH0XeTU983TtyKmJo3Bn7G1ErTizzaXi',NULL,'180.190.174.72','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWTNPNHBRMEhSWmhSY05qN0xnZDRVT1I0M3NVSTBCUUkyRkpUMFpQWSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHBzOi8va2FyZ2FtaW5lLnN5bnhjZWwuY29tL2xvZ2luIjt9fQ==',1782811913),('8z04zU4sqLmMZ9r9plijWvuNZuRvM8pyCJlPEn3F',NULL,'3.217.128.115','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaUg3Tk43cnRaOWlnS0U3UWc1Y1ZpRmJicmF0bU55V2ptcUFhTmlNbSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8va2FyZ2FtaW5lLnN5bnhjZWwuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1782744559),('gxz6EW3o3H8SeVjBjdzsQZv7AakGOwFxYEqBRdAa',NULL,'34.224.5.136','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiU0xSMVd3VFBUdDk4Y1NxdzJ5SWVKRU42YzRIa3NtdVUzTGFoZzR3WCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly9rYXJnYW1pbmUuc3lueGNlbC5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1782856443),('jkX8CK3HCnnWutERHZhISHvjk5xlwykrWHjSbq0o',NULL,'34.224.5.136','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOU1acnNKOFR2aXhDZ0FOU0dNcGZjM2xaSjh6V3lRalJtaFpxR2ttUiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly9rYXJnYW1pbmUuc3lueGNlbC5jb20vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1782856443),('o8BE5ca4XvmUYOVZ34AjuUTPB88W9rUNXMkLJu42',3,'180.190.174.72','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoibFJtbURYblQ5SXZiOE40emxPYUtLbUZnY0hRNlV2ZWtSYlFiS1dEUCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ0OiJodHRwczovL2thcmdhbWluZS5zeW54Y2VsLmNvbS9wYWdlX2Rhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7fQ==',1782808699),('RxptRrmOmxJeNyb5LMfmyNpARwsT3uCJ55OnqRot',5,'103.36.18.156','Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRWpPQXF1cU9aS3Rxc2xOaUgyZmhaSkF3eXF6Y2tlUmFGbjdYRlpvVSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8va2FyZ2FtaW5lLnN5bnhjZWwuY29tL3BhZ2VfbWFpbGVyIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTt9',1782837462),('SXtJdMky4Ol5rpUu1z6Nuaj5KhkYoq5a5TRGwHxb',NULL,'74.7.227.156','Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; GPTBot/1.4; +https://openai.com/gptbot)','YTozOntzOjY6Il90b2tlbiI7czo0MDoibzNibjAzZlZsMWljTkpnTGtLdWVMYmdNZ01raVcxWUNSOTBvclI2aCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHBzOi8va2FyZ2FtaW5lLnN5bnhjZWwuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1782793593),('U3jEDfVAKrhPlaX33eHaqczfr1xyGDxwvhzdP5pg',6,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiRDk1aTI2TGE0V0RWQnVOc2VmN3FiazBXWG1tWmw4dHc2aUxKZUlZYiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ1OiJodHRwOi8va2FyZ2FtaW5lX3Byb3RvdHlwZS50ZXN0L2FwaS9jcm0vbGVhZHMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo2O30=',1783303283),('XpzvIeUDEpeJHrM7w6WBjPejsU2KjvL6fOOVYF2z',NULL,'3.217.128.115','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUmFMYXVRdWM1alplYXZXMXFxbE5BclpleDdTQ2hjVG5HaGQ5a0M1WSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHBzOi8va2FyZ2FtaW5lLnN5bnhjZWwuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1782744559);
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_department`
--

LOCK TABLES `user_department` WRITE;
/*!40000 ALTER TABLE `user_department` DISABLE KEYS */;
INSERT INTO `user_department` VALUES (1,'Sales Department','2026-07-02 14:44:02','2026-07-02 14:44:02'),(2,'Operations Department','2026-07-02 14:44:02','2026-07-02 14:44:02');
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
INSERT INTO `user_status` VALUES (1,'Active','2026-07-02 14:44:24','2026-07-02 14:44:24'),(2,'Inactive','2026-07-02 14:44:24','2026-07-02 14:44:24'),(3,'Suspended','2026-07-02 14:44:24','2026-07-02 14:44:24'),(4,'Pending','2026-07-02 14:44:24','2026-07-02 14:44:24');
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
  `department_id` int(11) DEFAULT NULL,
  `role_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
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
INSERT INTO `users` VALUES (1,'Developer','superadmin@email.com',NULL,'$2y$12$xTR2YTyiLHr9R8JlOF9Xb.oKJWVMaPCpXHs52w7QuegslbVpYHCsm',NULL,'1',1,NULL,NULL,'2026-06-28 15:57:34','2026-06-28 15:57:34'),(2,'Synxcel Gabby','gabriel.david@email.com',NULL,'$2y$12$xTR2YTyiLHr9R8JlOF9Xb.oKJWVMaPCpXHs52w7QuegslbVpYHCsm',NULL,'1',1,NULL,NULL,'2026-06-28 15:57:34','2026-06-28 15:57:34'),(4,'Synxcel Minton','minton.diaz@email.com',NULL,'$2y$12$xTR2YTyiLHr9R8JlOF9Xb.oKJWVMaPCpXHs52w7QuegslbVpYHCsm',NULL,'1',1,NULL,NULL,'2026-06-28 15:57:34','2026-06-28 15:57:34'),(6,'Kargamine User','user.kargamine@email.com',NULL,'$2y$12$xTR2YTyiLHr9R8JlOF9Xb.oKJWVMaPCpXHs52w7QuegslbVpYHCsm',NULL,'1',1,NULL,NULL,'2026-06-28 15:57:34','2026-06-28 15:57:34');
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

-- Dump completed on 2026-07-06 10:06:48

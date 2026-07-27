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
INSERT INTO `app_theme_settings` VALUES (1,'blue','orange','slate','red','light','2026-07-27 10:36:09','2026-07-27 11:08:03');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bill_of_ladings`
--

LOCK TABLES `bill_of_ladings` WRITE;
/*!40000 ALTER TABLE `bill_of_ladings` DISABLE KEYS */;
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
  `status` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `origin_port_id` bigint(20) unsigned NOT NULL,
  `destination_port_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `booking_container_units_gate_pass_code_unique` (`gate_pass_code`),
  KEY `booking_container_units_booking_line_id_foreign` (`booking_line_id`),
  KEY `booking_container_units_container_asset_id_foreign` (`container_asset_id`),
  KEY `booking_container_units_origin_port_id_foreign` (`origin_port_id`),
  KEY `booking_container_units_destination_port_id_foreign` (`destination_port_id`),
  KEY `booking_container_units_booking_id_index` (`booking_id`),
  CONSTRAINT `booking_container_units_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE,
  CONSTRAINT `booking_container_units_booking_line_id_foreign` FOREIGN KEY (`booking_line_id`) REFERENCES `booking_lines` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_container_units_container_asset_id_foreign` FOREIGN KEY (`container_asset_id`) REFERENCES `container_assets` (`id`) ON DELETE SET NULL,
  CONSTRAINT `booking_container_units_destination_port_id_foreign` FOREIGN KEY (`destination_port_id`) REFERENCES `ports` (`port_id`),
  CONSTRAINT `booking_container_units_origin_port_id_foreign` FOREIGN KEY (`origin_port_id`) REFERENCES `ports` (`port_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_container_units`
--

LOCK TABLES `booking_container_units` WRITE;
/*!40000 ALTER TABLE `booking_container_units` DISABLE KEYS */;
/*!40000 ALTER TABLE `booking_container_units` ENABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_invoices`
--

LOCK TABLES `booking_invoices` WRITE;
/*!40000 ALTER TABLE `booking_invoices` DISABLE KEYS */;
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
  `container_id` bigint(20) unsigned NOT NULL,
  `container_class_id` bigint(20) unsigned NOT NULL,
  `container_size_id` bigint(20) unsigned NOT NULL,
  `container_variant_id` bigint(20) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL DEFAULT 1,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight_kg` decimal(12,2) DEFAULT NULL,
  `volume_cbm` decimal(12,2) DEFAULT NULL,
  `is_hazardous` tinyint(1) NOT NULL DEFAULT 0,
  `is_fragile` tinyint(1) NOT NULL DEFAULT 0,
  `frt_snapshot` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_type_snapshot` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_value_snapshot` decimal(12,2) NOT NULL DEFAULT 0.00,
  `frt_after_discount_snapshot` decimal(12,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_lines_container_id_foreign` (`container_id`),
  KEY `booking_lines_container_class_id_foreign` (`container_class_id`),
  KEY `booking_lines_container_size_id_foreign` (`container_size_id`),
  KEY `booking_lines_container_variant_id_foreign` (`container_variant_id`),
  KEY `booking_lines_booking_id_index` (`booking_id`),
  CONSTRAINT `booking_lines_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE,
  CONSTRAINT `booking_lines_container_class_id_foreign` FOREIGN KEY (`container_class_id`) REFERENCES `container_class` (`id`),
  CONSTRAINT `booking_lines_container_id_foreign` FOREIGN KEY (`container_id`) REFERENCES `containers` (`id`),
  CONSTRAINT `booking_lines_container_size_id_foreign` FOREIGN KEY (`container_size_id`) REFERENCES `container_size` (`id`),
  CONSTRAINT `booking_lines_container_variant_id_foreign` FOREIGN KEY (`container_variant_id`) REFERENCES `container_variants` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_lines`
--

LOCK TABLES `booking_lines` WRITE;
/*!40000 ALTER TABLE `booking_lines` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_status_history`
--

LOCK TABLES `booking_status_history` WRITE;
/*!40000 ALTER TABLE `booking_status_history` DISABLE KEYS */;
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
  `lane_id` bigint(20) unsigned NOT NULL,
  `origin_area_id` bigint(20) unsigned NOT NULL,
  `destination_area_id` bigint(20) unsigned NOT NULL,
  `delivery_type_id` bigint(20) unsigned NOT NULL,
  `tariff_rate_id` bigint(20) unsigned NOT NULL,
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
  KEY `bookings_origin_area_id_foreign` (`origin_area_id`),
  KEY `bookings_destination_area_id_foreign` (`destination_area_id`),
  KEY `bookings_delivery_type_id_foreign` (`delivery_type_id`),
  KEY `bookings_tariff_rate_id_foreign` (`tariff_rate_id`),
  KEY `bookings_vat_rate_id_foreign` (`vat_rate_id`),
  KEY `bookings_contract_id_foreign` (`contract_id`),
  KEY `bookings_contract_rate_id_foreign` (`contract_rate_id`),
  KEY `bookings_created_by_foreign` (`created_by`),
  KEY `bookings_lane_id_booking_date_index` (`lane_id`,`booking_date`),
  KEY `bookings_client_id_foreign` (`client_id`),
  KEY `bookings_client_contract_id_foreign` (`client_contract_id`),
  CONSTRAINT `bookings_client_contract_id_foreign` FOREIGN KEY (`client_contract_id`) REFERENCES `client_contracts` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bookings_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `client_masters` (`id`),
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
INSERT INTO `cache` VALUES ('management_system_cache_7f3072bf378b98d6bbf2f013cff3e287','i:2;',1785169898),('management_system_cache_7f3072bf378b98d6bbf2f013cff3e287:timer','i:1785169898;',1785169898),('management_system_cache_a3affa0d1e1a3c72b78aa984c3367a05','i:3;',1785170172),('management_system_cache_a3affa0d1e1a3c72b78aa984c3367a05:timer','i:1785170172;',1785170172),('management_system_cache_d2bfa8e8b749d2772a21edee7b70a2b3','i:1;',1785171127),('management_system_cache_d2bfa8e8b749d2772a21edee7b70a2b3:timer','i:1785171127;',1785171127),('management_system_cache_de226f3f5dc0c66a464effdc07ca6b1f','i:1;',1785170219),('management_system_cache_de226f3f5dc0c66a464effdc07ca6b1f:timer','i:1785170219;',1785170219);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `charge_types`
--

LOCK TABLES `charge_types` WRITE;
/*!40000 ALTER TABLE `charge_types` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_addresses`
--

LOCK TABLES `client_addresses` WRITE;
/*!40000 ALTER TABLE `client_addresses` DISABLE KEYS */;
INSERT INTO `client_addresses` VALUES (1,1,'Branch',1,'12313','qweqwe','qweqew',NULL,NULL,'Abra','Philippines','123123','2026-07-27 11:24:59','2026-07-27 11:24:59');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_billing`
--

LOCK TABLES `client_billing` WRITE;
/*!40000 ALTER TABLE `client_billing` DISABLE KEYS */;
INSERT INTO `client_billing` VALUES (1,1,'qwe','qwe','qwe','123','2026-07-27 11:25:45','2026-07-27 11:25:45');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_contacts`
--

LOCK TABLES `client_contacts` WRITE;
/*!40000 ALTER TABLE `client_contacts` DISABLE KEYS */;
INSERT INTO `client_contacts` VALUES (1,1,'qweqew','09476353766','mobile','Minton@123','personal','qweqwe','qweqewqe','2026-07-27 11:25:23','2026-07-27 11:25:23');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_contract_rates`
--

LOCK TABLES `client_contract_rates` WRITE;
/*!40000 ALTER TABLE `client_contract_rates` DISABLE KEYS */;
INSERT INTO `client_contract_rates` VALUES (1,1,2,22,2,1,2,5,123.00,'percentage',12.00,108.24,'2026-07-27 11:26:36','2026-07-27 11:26:36'),(2,1,2,22,2,1,4,6,123.00,NULL,0.00,123.00,'2026-07-27 11:26:36','2026-07-27 11:26:36');
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
  CONSTRAINT `client_contracts_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `client_masters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `client_contracts_client_proposal_id_foreign` FOREIGN KEY (`client_proposal_id`) REFERENCES `client_proposals` (`id`) ON DELETE SET NULL,
  CONSTRAINT `client_contracts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `client_contracts_terminated_by_foreign` FOREIGN KEY (`terminated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_contracts`
--

LOCK TABLES `client_contracts` WRITE;
/*!40000 ALTER TABLE `client_contracts` DISABLE KEYS */;
INSERT INTO `client_contracts` VALUES (1,'2f1fad93-041e-43a6-aee6-1b7ed87f8dcf','CCT-202607-0001',1,1,'2026-07-01','2026-07-01','2026-07-31',2,NULL,NULL,NULL,NULL,3,'2026-07-27 11:26:36','2026-07-27 11:26:36');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_finance`
--

LOCK TABLES `client_finance` WRITE;
/*!40000 ALTER TABLE `client_finance` DISABLE KEYS */;
INSERT INTO `client_finance` VALUES (1,1,'1','1',1,'electronic','qweqeqwq@email.com',NULL,NULL,NULL,'check_pickup','qwe',NULL,NULL,1,0,'qwe','2026-07-27 11:25:45','2026-07-27 11:25:45');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_masters`
--

LOCK TABLES `client_masters` WRITE;
/*!40000 ALTER TABLE `client_masters` DISABLE KEYS */;
INSERT INTO `client_masters` VALUES (1,'a25bcc19-dc8b-4d2a-ab19-c5bb32415679',1,'CM-2026-0001','qwe','1231 23','qwe','qwe',NULL,'123',NULL,123123.00,'https://awdasdadasd.com',3,3,1,3,'2026-07-27 11:24:59','2026-07-27 11:25:45');
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_proposal_rates`
--

LOCK TABLES `client_proposal_rates` WRITE;
/*!40000 ALTER TABLE `client_proposal_rates` DISABLE KEYS */;
INSERT INTO `client_proposal_rates` VALUES (1,1,2,22,2,1,2,5,123.00,NULL,0.00,123.00,'2026-07-27 11:23:30','2026-07-27 11:23:30'),(2,1,2,22,2,1,4,6,123.00,NULL,0.00,123.00,'2026-07-27 11:23:30','2026-07-27 11:23:30'),(3,2,2,22,2,1,2,5,123.00,NULL,0.00,123.00,'2026-07-27 14:02:40','2026-07-27 14:02:40'),(4,2,2,22,2,1,4,6,123.00,NULL,0.00,123.00,'2026-07-27 14:02:40','2026-07-27 14:02:40'),(5,2,2,22,2,1,2,5,123.00,'percentage',20.00,98.40,'2026-07-27 14:06:26','2026-07-27 14:06:26'),(6,4,2,22,2,1,2,5,123.00,NULL,0.00,123.00,'2026-07-27 15:12:09','2026-07-27 15:12:09'),(7,6,2,22,2,1,2,5,123.00,NULL,0.00,123.00,'2026-07-27 15:38:19','2026-07-27 15:38:19'),(8,8,2,22,2,1,2,5,123.00,NULL,0.00,123.00,'2026-07-27 15:47:51','2026-07-27 15:47:51');
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_proposals`
--

LOCK TABLES `client_proposals` WRITE;
/*!40000 ALTER TABLE `client_proposals` DISABLE KEYS */;
INSERT INTO `client_proposals` VALUES (1,'adf2c81c-2089-4e3e-ae59-4b33261d6c56','CPR-202607-0001',1,1,4,'[\"http:\\/\\/kargamine_prototype.test\\/uploads\\/doc\\/pdf\\/eaef76984b4ba26a68a970f69973301a5a4c8bad476b1c46d411187d518ca430.pdf\"]','2026-07-27 11:24:01',3,'2026-07-27 11:23:47',NULL,3,'2026-07-27 11:23:30','2026-07-27 11:24:59'),(2,'8d605c08-5842-45b9-b0cb-97c0f802e2e9','CPR-202607-0002',NULL,1,1,NULL,NULL,NULL,NULL,NULL,3,'2026-07-27 14:02:40','2026-07-27 14:02:40'),(4,'25333739-a055-4b77-9a43-9547d2aed26a','CPR-202607-0003',NULL,4,1,NULL,NULL,NULL,NULL,NULL,4,'2026-07-27 15:12:09','2026-07-27 15:12:09'),(6,'56f280d7-4425-48a4-9124-3e99412558ae','CPR-202607-0004',NULL,6,1,NULL,NULL,NULL,NULL,NULL,4,'2026-07-27 15:38:19','2026-07-27 15:38:19'),(8,'cf8434d0-1ebe-41de-991c-9da09e000f50','CPR-202607-0005',NULL,6,2,NULL,NULL,14,'2026-07-27 16:20:35',NULL,4,'2026-07-27 15:47:51','2026-07-27 16:20:35');
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
INSERT INTO `client_trade_references` VALUES (1,1,'qweq','qweqe','qweqew','123','123123','qweqwe@email.com','2026-07-27 11:25:23','2026-07-27 11:25:23');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `container_asset_location_history`
--

LOCK TABLES `container_asset_location_history` WRITE;
/*!40000 ALTER TABLE `container_asset_location_history` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `container_assets`
--

LOCK TABLES `container_assets` WRITE;
/*!40000 ALTER TABLE `container_assets` DISABLE KEYS */;
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
INSERT INTO `container_class` VALUES (1,'A',NULL,'2026-07-27 10:35:58'),(2,'B',NULL,'2026-07-27 10:35:58'),(3,'C',NULL,'2026-07-27 10:35:58'),(4,'D',NULL,'2026-07-27 10:35:58');
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
INSERT INTO `container_size` VALUES (1,'10-FOOTER',NULL,'2026-07-27 10:35:58'),(2,'20-FOOTER',NULL,'2026-07-27 10:35:58'),(3,'40-FOOTER STD',NULL,'2026-07-27 10:35:58'),(4,'40-FOOTER HC',NULL,'2026-07-27 10:35:58');
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
INSERT INTO `container_type` VALUES (1,'CONVAN',NULL,'2026-07-27 10:35:58'),(2,'FLATRACK (PLATFORM)',NULL,'2026-07-27 10:35:58'),(3,'REEFER',NULL,'2026-07-27 10:35:58'),(4,'HIGH CUBE',NULL,'2026-07-27 10:35:58'),(5,'CATTLE VAN',NULL,'2026-07-27 10:35:58'),(6,'TANK (ISO TANK)',NULL,'2026-07-27 10:35:58'),(7,'ROLLING CARGO',NULL,'2026-07-27 10:35:58'),(8,'SPECIAL CONTAINERS',NULL,'2026-07-27 10:35:58'),(9,'OPEN-TOP VAN',NULL,'2026-07-27 10:35:58');
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `container_variants`
--

LOCK TABLES `container_variants` WRITE;
/*!40000 ALTER TABLE `container_variants` DISABLE KEYS */;
INSERT INTO `container_variants` VALUES (4,2,1,1,1,'2026-07-27 11:23:06','2026-07-27 11:23:06'),(5,2,1,2,1,'2026-07-27 11:23:06','2026-07-27 11:23:06'),(6,2,1,4,1,'2026-07-27 11:23:06','2026-07-27 11:23:06'),(7,2,1,3,1,'2026-07-27 11:23:06','2026-07-27 11:23:06');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `containers`
--

LOCK TABLES `containers` WRITE;
/*!40000 ALTER TABLE `containers` DISABLE KEYS */;
INSERT INTO `containers` VALUES (2,'CV','Container Van',1,'2026-07-27 11:23:06','2026-07-27 11:23:06');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_activities`
--

LOCK TABLES `crm_activities` WRITE;
/*!40000 ALTER TABLE `crm_activities` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_company_info`
--

LOCK TABLES `crm_company_info` WRITE;
/*!40000 ALTER TABLE `crm_company_info` DISABLE KEYS */;
INSERT INTO `crm_company_info` VALUES (1,1,NULL,NULL,NULL,NULL,NULL,'2026-07-27 11:19:32','2026-07-27 11:19:32'),(2,4,'qweqwe','Distributor','qweqwe',NULL,NULL,'2026-07-27 15:09:13','2026-07-27 15:09:50'),(3,6,NULL,NULL,NULL,NULL,NULL,'2026-07-27 15:37:16','2026-07-27 15:37:16'),(6,10,NULL,NULL,NULL,NULL,NULL,'2026-07-27 15:59:50','2026-07-27 15:59:50');
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_lead_addresses`
--

LOCK TABLES `crm_lead_addresses` WRITE;
/*!40000 ALTER TABLE `crm_lead_addresses` DISABLE KEYS */;
INSERT INTO `crm_lead_addresses` VALUES (1,1,'Branch',1,'12313','qweqwe','qweqew','Agtangao','Bangued','Abra','Philippines','123123','2026-07-27 11:19:32','2026-07-27 11:19:32'),(8,4,NULL,1,'123','qwe','qwe','Amti','Boliney','Abra','Philippines','123','2026-07-27 15:11:56','2026-07-27 15:11:56'),(9,6,NULL,1,'12','12','12','Agtangao','Bangued','Abra','Philippines','123123','2026-07-27 15:37:16','2026-07-27 15:37:16'),(10,10,'Branch',1,'123','qwe','qwe','Agtangao','Bangued','Abra','Philippines','123','2026-07-27 15:59:50','2026-07-27 15:59:50');
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_lead_containers`
--

LOCK TABLES `crm_lead_containers` WRITE;
/*!40000 ALTER TABLE `crm_lead_containers` DISABLE KEYS */;
INSERT INTO `crm_lead_containers` VALUES (1,1,'CV',2,22,NULL,NULL,'Container Van (CV)',12,123.00,'Weekly','qwe',NULL,1,NULL,2,NULL,NULL,NULL,'DOOR','DOOR',NULL,0,NULL,'qweqwe','qweqwe','2026-07-27 11:19:55','2026-07-27 11:19:55'),(2,1,'CV',2,22,NULL,NULL,'Container Van (CV)',12,123.00,'Weekly','qweqew',NULL,1,NULL,4,NULL,NULL,NULL,'DOOR','DOOR',NULL,0,NULL,'qweqwe','qweqwe','2026-07-27 11:20:20','2026-07-27 11:20:20'),(10,4,'CV',2,22,NULL,NULL,'Container Van (CV)',12,123.00,'Weekly','qwe',NULL,1,NULL,2,NULL,NULL,NULL,'DOOR','DOOR',NULL,0,NULL,'qwe','qwe','2026-07-27 15:11:57','2026-07-27 15:11:57'),(11,6,'CV',2,22,NULL,NULL,'Container Van (CV)',12,123.00,'Weekly','qweqwe',NULL,1,NULL,2,NULL,NULL,NULL,'PIER','DOOR',NULL,0,NULL,'qweqew','qweqwe','2026-07-27 15:37:35','2026-07-27 15:37:35'),(12,10,'CV',2,22,NULL,NULL,'Container Van (CV)',12,123.00,'Weekly','qqweq',NULL,2,NULL,2,NULL,NULL,NULL,'DOOR','DOOR',NULL,0,NULL,'qwe','qwe','2026-07-27 16:00:13','2026-07-27 16:00:13');
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_leads`
--

LOCK TABLES `crm_leads` WRITE;
/*!40000 ALTER TABLE `crm_leads` DISABLE KEYS */;
INSERT INTO `crm_leads` VALUES (1,'a25bca26-3c5c-48ab-8742-10e13d16e47c','qweqwe','qweqeq@email.com','personal','1231 23','qweqwe','personal','personal','qweqwe',5,'CM-2026-0001','individual',2,1,'Cold Call',3,NULL,NULL,'2026-07-27 11:24:59','2026-07-27 11:19:32','2026-07-27 11:24:59'),(4,'a25c1c4a-3b02-4888-b4e9-dd7beda00663','qweqwe','qqweqweq@email.com','personal','1231 23','123123','personal','personal','wqeqqew',3,NULL,'corporate',2,1,'Cold Call',4,NULL,NULL,'2026-07-27 15:11:56','2026-07-27 15:09:13','2026-07-27 15:11:57'),(6,'a25c2653-3bd6-4efc-b8f5-197f778730f1','qweqew','qqweqweq@email.com','personal','1231 23','123123','personal','personal','qqwqeqweqwe',3,NULL,'individual',2,1,'qweqwe',4,NULL,NULL,'2026-07-27 15:37:35','2026-07-27 15:37:16','2026-07-27 15:37:35'),(10,'a25c2e64-d5e1-41fe-85a2-5924822a9999','test','qqweqweq@email.com','personal','123','test','personal','personal','test',3,NULL,'individual',2,1,'test',4,NULL,NULL,'2026-07-27 16:00:13','2026-07-27 15:59:50','2026-07-27 16:00:13');
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
INSERT INTO `crm_status` VALUES (1,'LEAD','New incoming lead','2026-07-27 10:35:58','2026-07-27 10:35:58'),(2,'QUALIFIED','Lead is qualified and potential','2026-07-27 10:35:58','2026-07-27 10:35:58'),(3,'OPPORTUNITY','Converted into sales opportunity','2026-07-27 10:35:58','2026-07-27 10:35:58'),(4,'NEGOTIATION','In negotiation stage','2026-07-27 10:35:58','2026-07-27 10:35:58'),(5,'WIN','Final stage: won or lost','2026-07-27 10:35:58','2026-07-27 10:35:58'),(6,'LOST','Final stage: won or lost','2026-07-27 10:35:58','2026-07-27 10:35:58');
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
INSERT INTO `customer_type` VALUES (1,'SHIPPER',NULL,'2026-07-27 10:35:58'),(2,'CONSIGNEE',NULL,'2026-07-27 10:35:58'),(3,'SHIPPER-CONSIGNEE',NULL,'2026-07-27 10:35:58');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `delivery_types`
--

LOCK TABLES `delivery_types` WRITE;
/*!40000 ALTER TABLE `delivery_types` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `general_charges`
--

LOCK TABLES `general_charges` WRITE;
/*!40000 ALTER TABLE `general_charges` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `handling_fees`
--

LOCK TABLES `handling_fees` WRITE;
/*!40000 ALTER TABLE `handling_fees` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (8,'default','{\"uuid\":\"88999e41-93ae-4235-9f93-cc445f7cf64a\",\"displayName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"command\":\"O:31:\\\"App\\\\Jobs\\\\SendApplicationMailJob\\\":2:{s:10:\\\"\\u0000*\\u0000payload\\\";a:4:{s:7:\\\"subject\\\";s:17:\\\"New Lead — test\\\";s:5:\\\"title\\\";s:16:\\\"New lead created\\\";s:7:\\\"message\\\";s:41:\\\"Kargamine User added a new lead — test.\\\";s:6:\\\"button\\\";a:2:{s:3:\\\"url\\\";s:40:\\\"http:\\/\\/kargamine_prototype.test\\/page_crm\\\";s:4:\\\"text\\\";s:11:\\\"View in CRM\\\";}}s:9:\\\"\\u0000*\\u0000userId\\\";i:14;}\"}}',0,NULL,1785167990,1785167990),(9,'default','{\"uuid\":\"d00e6ae7-7978-4e0a-9e75-a6314f4504b0\",\"displayName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"command\":\"O:31:\\\"App\\\\Jobs\\\\SendApplicationMailJob\\\":2:{s:10:\\\"\\u0000*\\u0000payload\\\";a:4:{s:7:\\\"subject\\\";s:46:\\\"Your proposal was approved — CPR-202607-0005\\\";s:5:\\\"title\\\";s:17:\\\"Proposal approved\\\";s:7:\\\"message\\\";s:43:\\\"CPR-202607-0005 was approved by Eden Palma.\\\";s:6:\\\"button\\\";a:2:{s:3:\\\"url\\\";s:46:\\\"http:\\/\\/kargamine_prototype.test\\/page_proposals\\\";s:4:\\\"text\\\";s:13:\\\"View Proposal\\\";}}s:9:\\\"\\u0000*\\u0000userId\\\";i:4;}\"}}',0,NULL,1785169236,1785169236);
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lane_tariff_rate_prices`
--

LOCK TABLES `lane_tariff_rate_prices` WRITE;
/*!40000 ALTER TABLE `lane_tariff_rate_prices` DISABLE KEYS */;
INSERT INTO `lane_tariff_rate_prices` VALUES (3,2,4,123.00,'2026-07-27 11:23:21','2026-07-27 11:23:21'),(4,2,5,123.00,'2026-07-27 11:23:21','2026-07-27 11:23:21'),(5,2,6,123.00,'2026-07-27 11:23:21','2026-07-27 11:23:21'),(6,2,7,123.00,'2026-07-27 11:23:21','2026-07-27 11:23:21');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lane_tariff_rates`
--

LOCK TABLES `lane_tariff_rates` WRITE;
/*!40000 ALTER TABLE `lane_tariff_rates` DISABLE KEYS */;
INSERT INTO `lane_tariff_rates` VALUES (2,1,'2026-07-01','2026-07-31',1,'2026-07-27 11:23:21','2026-07-27 11:23:21');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lanes`
--

LOCK TABLES `lanes` WRITE;
/*!40000 ALTER TABLE `lanes` DISABLE KEYS */;
INSERT INTO `lanes` VALUES (1,2,22,1,'2026-07-27 11:21:32','2026-07-27 11:21:32');
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
INSERT INTO `list_of_values_table` VALUES (1,1,'OFF','Office',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(2,1,'WAR','Warehouse',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(3,1,'BRA','Branch',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(4,1,'STO','Storage Facility',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(5,2,'REF','Referral',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(6,2,'WEB','Website',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(7,2,'WAL','Walk-in',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(8,2,'COL','Cold Call',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(9,2,'SOC','Social Media',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(10,2,'OTH','Other',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(11,3,'IMP','Importer',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(12,3,'EXP','Exporter',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(13,3,'MAN','Manufacturer',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(14,3,'TRA','Trading',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(15,3,'RET','Retail',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(16,3,'DIS','Distributor',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(17,3,'OTH','Others',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mailer_settings`
--

LOCK TABLES `mailer_settings` WRITE;
/*!40000 ALTER TABLE `mailer_settings` DISABLE KEYS */;
INSERT INTO `mailer_settings` VALUES (2,'smtp','localhost','25',NULL,NULL,NULL,'no-reply@notification.synxcel.com','Synxcel Notification','2026-07-27 16:35:01','2026-07-27 16:35:01');
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
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2025_10_01_211540_create_nav_menus_table',1),(6,'2025_10_02_163004_create_table_for_settings_role',1),(7,'2025_10_03_180527_add_parentmenu',1),(8,'2025_10_04_144632_create_mailer_settings_table',1),(9,'2025_11_06_035725_insert_order_in_nav_menus',1),(10,'2026_01_24_221131_create_sessions_table',1),(11,'2026_01_24_221645_add_session_id_to_users_table',1),(12,'2026_01_24_223037_create_cache_table',1),(13,'2026_01_26_110424_create_jobs_table',1),(14,'2026_01_26_110425_modify_columns_of_user_table',1),(15,'2026_05_01_011537_options_table',1),(16,'2026_05_01_011632_list_of_value_table',1),(17,'2026_05_05_052405_create_company_info_master_table',1),(18,'2026_05_05_052441_create_contact_info_table',1),(19,'2026_05_05_052453_create_trade_references_table',1),(20,'2026_05_05_052548_create_services_info_table',1),(21,'2026_05_05_052612_create_company_finance_table',1),(22,'2026_05_05_052631_create_billed_details_table',1),(23,'2026_05_05_052656_create_sales_info_table',1),(24,'2026_05_05_052712_create_stages_info_table',1),(25,'2026_05_07_205424_create_e_invoice_table',1),(26,'2026_05_07_205616_create_courier_invoice_table',1),(27,'2026_06_05_045259_create_crm_status_table',1),(28,'2026_06_05_045351_create_crm_leads_table',1),(29,'2026_06_05_045416_create_company_info_table',1),(30,'2026_06_05_045430_create_crm_notes_table',1),(31,'2026_06_05_045450_create_crm_activities_table',1),(32,'2026_06_15_230541_proposals',1),(33,'2026_06_15_231224_proposal_rates',1),(34,'2026_06_15_233415_create_table_for_routes',1),(35,'2026_06_15_233633_create_table_service_type',1),(36,'2026_06_15_233806_create_proposal_status',1),(37,'2026_06_15_233848_create_customer_type',1),(38,'2026_06_26_195636_create_container_type',1),(39,'2026_06_26_195710_create_container_class',1),(40,'2026_06_26_195725_create_container_size',1),(41,'2026_07_02_221007_add_new_columns_to_user_table',1),(42,'2026_07_02_222100_create_user_department',1),(43,'2026_07_02_222118_create_user_status',1),(44,'2026_07_04_000001_create_ports_table',1),(45,'2026_07_04_000002_create_serviceable_areas_table',1),(46,'2026_07_04_000003_create_delivery_types_table',1),(47,'2026_07_04_000004_create_charge_types_table',1),(48,'2026_07_04_000005_create_lanes_table',1),(49,'2026_07_04_000006_create_lane_tariff_rates_table',1),(50,'2026_07_04_000007_create_port_charges_table',1),(51,'2026_07_04_000008_create_handling_fees_table',1),(52,'2026_07_04_000009_create_trucking_tariffs_table',1),(53,'2026_07_04_000010_create_vat_rates_table',1),(54,'2026_07_04_000011_create_contracts_table',1),(55,'2026_07_04_000012_create_contract_rates_table',1),(56,'2026_07_04_000013_create_bookings_table',1),(57,'2026_07_04_000014_create_booking_port_charges_table',1),(58,'2026_07_07_000001_add_applicable_to_to_charge_types_table',1),(59,'2026_07_07_000002_create_general_charges_table',1),(60,'2026_07_07_124225_drop_bsc_ra_gri_from_lane_tariff_rates_table',1),(61,'2026_07_07_124800_add_rate_type_and_rate_value_to_proposals_rates_table',1),(62,'2026_07_08_175248_create_client_masters_table',1),(63,'2026_07_08_175319_create_client_contacts_table',1),(64,'2026_07_08_175429_create_client_trade_references_table',1),(65,'2026_07_08_175448_create_client_finance_table',1),(66,'2026_07_08_175528_create_client_billing_table',1),(67,'2026_07_08_221636_create_containers_table',1),(68,'2026_07_08_221704_create_container_variants_table',1),(69,'2026_07_08_221727_create_lane_tariff_rate_prices_table',1),(70,'2026_07_09_000939_drop_column_from_container_table',1),(71,'2026_07_09_001926_drop_column_from_lane_tariff_rates_table',1),(72,'2026_07_10_212311_create_client_proposals_table',1),(73,'2026_07_10_212810_create_client_proposal_rates_table',1),(74,'2026_07_10_212846_create_client_contracts_table',1),(75,'2026_07_10_212946_create_client_contract_rates_table',1),(76,'2026_07_11_152530_add_lead_id_to_client_masters_table',1),(77,'2026_07_13_182230_add_workflow_columns_to_client_proposals_table',1),(78,'2026_07_14_021740_add_progress_columns_to_crm_leads_table',1),(79,'2026_07_14_021824_add_address_fields_to_crm_company_info_table',1),(80,'2026_07_14_021949_create_crm_lead_containers_table',1),(81,'2026_07_14_030206_add_lookup_columns_to_crm_lead_containers_table',1),(82,'2026_07_14_044418_drop_company_address_from_crm_company_info',1),(83,'2026_07_22_003454_add_lead_id_to_client_proposals_table',1),(84,'2026_07_22_020031_add_attachment_to_crm_activities_table',1),(85,'2026_07_22_031438_add_client_type_and_contact_fields_to_crm_leads_table',1),(86,'2026_07_22_031439_create_crm_lead_addresses_table',1),(87,'2026_07_22_031439_migrate_crm_company_address_to_lead_addresses_and_drop_columns',1),(88,'2026_07_22_031440_add_industry_description_to_crm_company_info_table',1),(89,'2026_07_22_040302_remove_lookup_values_nav_menu_entry',1),(90,'2026_07_22_045002_add_customer_code_to_crm_leads_table',1),(91,'2026_07_22_045002_create_client_addresses_table',1),(92,'2026_07_22_045003_migrate_client_registered_address_and_drop_column',1),(93,'2026_07_22_045004_add_type_fields_to_client_contacts_table',1),(94,'2026_07_23_010845_create_app_theme_settings_table',1),(95,'2026_07_23_010846_add_theme_nav_menu_entry',1),(96,'2026_07_25_031024_create_notifications_table',1),(97,'2026_07_25_035333_create_teams_table',1),(98,'2026_07_25_035334_add_team_columns_to_users_table',1),(99,'2026_07_27_100000_add_coordinates_to_ports_table',1),(100,'2026_07_27_100100_create_container_assets_table',1),(101,'2026_07_27_100200_create_container_asset_location_history_table',1),(102,'2026_07_27_110000_add_is_system_to_setting_role_table',1),(103,'2026_07_27_110100_create_permissions_table',1),(104,'2026_07_27_110200_create_role_permission_table',1),(105,'2026_07_27_120000_add_booking_header_fields_to_bookings_table',1),(106,'2026_07_27_120100_create_booking_lines_table',1),(107,'2026_07_27_120200_create_booking_status_history_table',1),(108,'2026_07_27_120300_create_booking_container_units_table',1),(109,'2026_07_27_120400_create_booking_invoices_table',1),(110,'2026_07_27_120500_create_bill_of_ladings_table',1),(111,'2026_07_27_130000_add_termination_fields_to_client_contracts_table',1);
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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nav_menus`
--

LOCK TABLES `nav_menus` WRITE;
/*!40000 ALTER TABLE `nav_menus` DISABLE KEYS */;
INSERT INTO `nav_menus` VALUES (1,'Theme','','/page_theme','[\"1\"]',9,3,'2026-07-27 10:35:49','2026-07-27 10:35:57'),(2,'Dashboard','fas fa-home','/page_dashboard','[\"2\",\"4\",\"5\",\"6\",\"1\",\"3\"]',0,0,'2026-07-27 10:35:57','2026-07-27 14:59:53'),(3,'CRM',NULL,'/page_crm','[\"2\",\"4\",\"5\",\"6\",\"1\",\"3\"]',0,2,'2026-07-27 10:35:57','2026-07-27 14:59:57'),(4,'Clients',NULL,'page_clientMasters','[\"2\",\"4\",\"5\",\"6\",\"1\",\"3\"]',0,4,'2026-07-27 10:35:57','2026-07-27 15:00:06'),(5,'Contracts',NULL,'/page_contracts','[\"2\",\"4\",\"5\",\"6\",\"1\",\"3\"]',0,6,'2026-07-27 10:35:57','2026-07-27 15:00:55'),(6,'Users','fas fa-users','/page_usermanagement','[\"2\",\"5\",\"1\"]',0,7,'2026-07-27 10:35:57','2026-07-27 15:00:38'),(7,'Proposals',NULL,'page_proposals','[\"2\",\"4\",\"5\",\"6\",\"1\",\"3\"]',0,8,'2026-07-27 10:35:57','2026-07-27 15:00:26'),(8,'Settings','','#','[\"1\",\"2\",\"3\",\"4\"]',0,9,'2026-07-27 10:35:57','2026-07-27 14:21:21'),(9,'Developer Option','fas fa-users','#','[\"1\"]',0,10,'2026-07-27 10:35:57','2026-07-27 14:21:21'),(10,'Mailer','','/page_mailer','[\"1\"]',9,1,'2026-07-27 10:35:57','2026-07-27 10:35:57'),(11,'Menus','','/page_menus','[\"1\"]',9,2,'2026-07-27 10:35:57','2026-07-27 10:35:57'),(12,'Notification Test','','/page_notification_test','[\"1\"]',9,4,'2026-07-27 10:35:57','2026-07-27 10:35:57'),(13,'App Settings','','/page_maintenance','[\"1\",\"2\",\"3\",\"4\"]',8,1,'2026-07-27 10:35:57','2026-07-27 10:35:57'),(14,'Team Management','','/page_team_management','[\"1\"]',8,2,'2026-07-27 10:35:57','2026-07-27 10:35:57'),(15,'Container Inventory','fas fa-box','/page_container_inventory','[\"4\",\"5\",\"1\",\"3\"]',0,5,'2026-07-27 10:55:27','2026-07-27 15:00:16'),(16,'Booking',NULL,'page_booking','[\"2\",\"4\",\"5\",\"6\",\"1\",\"3\"]',0,9,'2026-07-27 10:56:08','2026-07-27 15:00:48');
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (3,'crm.lead_created','App\\Models\\CrmLead',6,14,4,'New lead created','Kargamine User added a new lead — qweqew.','View in CRM','/page_crm',NULL,1,'2026-07-27 15:37:16','2026-07-27 16:06:44'),(4,'proposal.pending','App\\Models\\ClientProposal',6,14,4,'Proposal awaiting your approval','CPR-202607-0004 for Kargamine User needs your approval.','View Proposal','/page_proposals',NULL,1,'2026-07-27 15:38:19','2026-07-27 16:06:44'),(6,'proposal.pending','App\\Models\\ClientProposal',8,14,4,'Proposal awaiting your approval','CPR-202607-0005 for Kargamine User needs your approval.','View Proposal','/page_proposals','{\"modal_fn\":\"openProposalModal\",\"modal_args\":[8]}',1,'2026-07-27 15:47:51','2026-07-27 16:20:32'),(7,'crm.lead_created','App\\Models\\CrmLead',10,14,4,'New lead created','Kargamine User added a new lead — test.','View in CRM','/page_crm',NULL,1,'2026-07-27 15:59:50','2026-07-27 16:20:29'),(8,'proposal.approved','App\\Models\\ClientProposal',8,4,14,'Proposal approved','CPR-202607-0005 was approved by Eden Palma.','View Proposal','/page_proposals','{\"modal_fn\":\"openProposalModal\",\"modal_args\":[8]}',1,'2026-07-27 16:20:35','2026-07-27 16:20:45');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `options_table`
--

LOCK TABLES `options_table` WRITE;
/*!40000 ALTER TABLE `options_table` DISABLE KEYS */;
INSERT INTO `options_table` VALUES (1,'Address Type',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(2,'Lead Source',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(3,'Type of Business',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59');
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'roles.manage','Manage roles & permissions','Roles','2026-07-27 10:35:58','2026-07-27 10:35:58'),(2,'booking.create','Create a booking','Booking','2026-07-27 10:35:58','2026-07-27 10:35:58'),(3,'booking.confirm','Confirm a booking','Booking','2026-07-27 10:35:58','2026-07-27 10:35:58'),(4,'booking.cancel','Cancel a booking','Booking','2026-07-27 10:35:58','2026-07-27 10:35:58'),(5,'booking.advance-status','Advance a booking\'s status','Booking','2026-07-27 10:35:58','2026-07-27 10:35:58'),(6,'contract.create','Create a contract from an accepted proposal','Contract','2026-07-27 10:35:58','2026-07-27 10:35:58'),(7,'contract.terminate','Terminate a contract','Contract','2026-07-27 10:35:58','2026-07-27 10:35:58');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `port_charges`
--

LOCK TABLES `port_charges` WRITE;
/*!40000 ALTER TABLE `port_charges` DISABLE KEYS */;
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
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`port_id`),
  UNIQUE KEY `ports_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ports`
--

LOCK TABLES `ports` WRITE;
/*!40000 ALTER TABLE `ports` DISABLE KEYS */;
INSERT INTO `ports` VALUES (1,'MNL','MANILA',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(2,'BCD','BACOLOD PORT',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(3,'BUT','BUTUAN',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(4,'CEB','CEBU',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(5,'CGY','CAGAYAN',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(6,'DVO','DAVAO',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(7,'DGT','DUMAGUETE',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(8,'GES','GEN SAN',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(9,'ILG','ILIGAN',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(10,'ILO','ILOILO',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(11,'OZM','OSAMIS',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(12,'CRN','CORON',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(13,'ROX','ROXAS',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(14,'CTC','CATICLAN',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(15,'ORM','ORMOC',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(16,'TAG','TAGBILARAN',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(17,'TAC','TACLOBAN',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(18,'ZAM','ZAMBOANGA',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(19,'PPS','PUERTO PRINCESSA',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(20,'SUR','SURIGAO',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(21,'COT','COTABATO',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(22,'BTG','BATANGAS',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(23,'P023','PORT 23',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(24,'P024','PORT 24',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(25,'P025','PORT 25',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(26,'P026','PORT 26',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(27,'P027','PORT 27',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(28,'P028','PORT 28',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(29,'P029','PORT 29',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(30,'P030','PORT 30',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(31,'P031','PORT 31',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(32,'P032','PORT 32',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(33,'P033','PORT 33',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(34,'P034','PORT 34',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(35,'P035','PORT 35',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(36,'P036','PORT 36',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(37,'P037','PORT 37',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(38,'P038','PORT 38',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(39,'P039','PORT 39',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(40,'P040','PORT 40',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(41,'P041','PORT 41',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(42,'P042','PORT 42',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(43,'P043','PORT 43',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(44,'P044','PORT 44',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(45,'P045','PORT 45',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(46,'P046','PORT 46',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(47,'P047','PORT 47',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(48,'P048','PORT 48',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(49,'P049','PORT 49',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(50,'P050','PORT 50',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(51,'P051','PORT 51',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(52,'P052','PORT 52',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(53,'P053','PORT 53',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(54,'P054','PORT 54',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(55,'P055','PORT 55',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(56,'P056','PORT 56',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(57,'P057','PORT 57',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(58,'P058','PORT 58',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(59,'P059','PORT 59',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(60,'P060','PORT 60',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(61,'P061','PORT 61',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(62,'P062','PORT 62',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(63,'P063','PORT 63',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(64,'P064','PORT 64',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(65,'P065','PORT 65',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(66,'P066','PORT 66',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(67,'P067','PORT 67',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(68,'P068','PORT 68',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(69,'P069','PORT 69',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(70,'P070','PORT 70',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(71,'P071','PORT 71',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(72,'P072','PORT 72',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(73,'P073','PORT 73',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(74,'P074','PORT 74',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(75,'P075','PORT 75',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(76,'P076','PORT 76',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(77,'P077','PORT 77',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(78,'P078','PORT 78',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(79,'P079','PORT 79',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(80,'P080','PORT 80',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(81,'P081','PORT 81',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(82,'P082','PORT 82',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(83,'P083','PORT 83',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(84,'P084','PORT 84',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(85,'P085','PORT 85',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(86,'P086','PORT 86',NULL,NULL,1,'2026-07-27 10:35:58','2026-07-27 10:35:58'),(87,'P087','PORT 87',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(88,'P088','PORT 88',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(89,'P089','PORT 89',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(90,'P090','PORT 90',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(91,'P091','PORT 91',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(92,'P092','PORT 92',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(93,'P093','PORT 93',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(94,'P094','PORT 94',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(95,'P095','PORT 95',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(96,'P096','PORT 96',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(97,'P097','PORT 97',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(98,'P098','PORT 98',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(99,'P099','PORT 99',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(100,'P100','PORT 100',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(101,'P101','PORT 101',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(102,'P102','PORT 102',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(103,'P103','PORT 103',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(104,'P104','PORT 104',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(105,'P105','PORT 105',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(106,'P106','PORT 106',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(107,'P107','PORT 107',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(108,'P108','PORT 108',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(109,'P109','PORT 109',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(110,'P110','PORT 110',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(111,'P111','PORT 111',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(112,'P112','PORT 112',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(113,'P113','PORT 113',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(114,'P114','PORT 114',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(115,'P115','PORT 115',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(116,'P116','PORT 116',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(117,'P117','PORT 117',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(118,'P118','PORT 118',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(119,'P119','PORT 119',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(120,'P120','PORT 120',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(121,'P121','PORT 121',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(122,'P122','PORT 122',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(123,'P123','PORT 123',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(124,'P124','PORT 124',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(125,'P125','PORT 125',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(126,'P126','PORT 126',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(127,'P127','PORT 127',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(128,'P128','PORT 128',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(129,'P129','PORT 129',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(130,'P130','PORT 130',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(131,'P131','PORT 131',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(132,'P132','PORT 132',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(133,'P133','PORT 133',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(134,'P134','PORT 134',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(135,'P135','PORT 135',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(136,'P136','PORT 136',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(137,'P137','PORT 137',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(138,'P138','PORT 138',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(139,'P139','PORT 139',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(140,'P140','PORT 140',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(141,'P141','PORT 141',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(142,'P142','PORT 142',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(143,'P143','PORT 143',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(144,'P144','PORT 144',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(145,'P145','PORT 145',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(146,'P146','PORT 146',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(147,'P147','PORT 147',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(148,'P148','PORT 148',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(149,'P149','PORT 149',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(150,'P150','PORT 150',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(151,'P151','PORT 151',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(152,'P152','PORT 152',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(153,'P153','PORT 153',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(154,'P154','PORT 154',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(155,'P155','PORT 155',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(156,'P156','PORT 156',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(157,'P157','PORT 157',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(158,'P158','PORT 158',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(159,'P159','PORT 159',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(160,'P160','PORT 160',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(161,'P161','PORT 161',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(162,'P162','PORT 162',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(163,'P163','PORT 163',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(164,'P164','PORT 164',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(165,'P165','PORT 165',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(166,'P166','PORT 166',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(167,'P167','PORT 167',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(168,'P168','PORT 168',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(169,'P169','PORT 169',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(170,'P170','PORT 170',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(171,'P171','PORT 171',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(172,'P172','PORT 172',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(173,'P173','PORT 173',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(174,'P174','PORT 174',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(175,'P175','PORT 175',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(176,'P176','PORT 176',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(177,'P177','PORT 177',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(178,'P178','PORT 178',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(179,'P179','PORT 179',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(180,'P180','PORT 180',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(181,'P181','PORT 181',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(182,'P182','PORT 182',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(183,'P183','PORT 183',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(184,'P184','PORT 184',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(185,'P185','PORT 185',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(186,'P186','PORT 186',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(187,'P187','PORT 187',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(188,'P188','PORT 188',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(189,'P189','PORT 189',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(190,'P190','PORT 190',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(191,'P191','PORT 191',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(192,'P192','PORT 192',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(193,'P193','PORT 193',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(194,'P194','PORT 194',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(195,'P195','PORT 195',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(196,'P196','PORT 196',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(197,'P197','PORT 197',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(198,'P198','PORT 198',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(199,'P199','PORT 199',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(200,'P200','PORT 200',NULL,NULL,1,'2026-07-27 10:35:59','2026-07-27 10:35:59');
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
INSERT INTO `proposal_status` VALUES (1,'Pending',NULL,'2026-07-27 10:35:58'),(2,'Approved',NULL,'2026-07-27 10:35:58'),(3,'Disapproved',NULL,'2026-07-27 10:35:58'),(4,'Accepted',NULL,'2026-07-27 10:35:58'),(5,'Rejected',NULL,'2026-07-27 10:35:58'),(6,'On-Hold',NULL,'2026-07-27 10:35:58');
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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permission`
--

LOCK TABLES `role_permission` WRITE;
/*!40000 ALTER TABLE `role_permission` DISABLE KEYS */;
INSERT INTO `role_permission` VALUES (7,1,1),(4,1,2),(3,1,3),(2,1,4),(1,1,5),(5,1,6),(6,1,7),(14,4,1),(11,4,2),(10,4,3),(9,4,4),(8,4,5),(12,4,6),(13,4,7),(18,5,2),(17,5,3),(16,5,4),(15,5,5),(19,5,6),(20,5,7),(24,6,2),(23,6,3),(22,6,4),(21,6,5),(25,6,6);
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
INSERT INTO `routes` VALUES (1,'BUTUAN','BUTUAN',NULL,'2026-07-27 10:35:58'),(2,'CEBU','CEBU',NULL,'2026-07-27 10:35:58'),(3,'CAGAYAN','CAGAYAN',NULL,'2026-07-27 10:35:58'),(4,'DAVAO','DAVAO',NULL,'2026-07-27 10:35:58'),(5,'DUMAGUETE','DUMAGUETE',NULL,'2026-07-27 10:35:58'),(6,'GEN SAN','GEN SAN',NULL,'2026-07-27 10:35:58'),(7,'ILIGAN','ILIGAN',NULL,'2026-07-27 10:35:58'),(8,'ILOILO','ILOILO',NULL,'2026-07-27 10:35:58'),(9,'OSAMIS','OSAMIS',NULL,'2026-07-27 10:35:58'),(10,'CORON','CORON',NULL,'2026-07-27 10:35:58'),(11,'ROXAS','ROXAS',NULL,'2026-07-27 10:35:58'),(12,'CATICLAN','CATICLAN',NULL,'2026-07-27 10:35:58'),(13,'ORMOC','ORMOC',NULL,'2026-07-27 10:35:58'),(14,'TAGBILARAN','TAGBILARAN',NULL,'2026-07-27 10:35:58'),(15,'TACLOBAN','TACLOBAN',NULL,'2026-07-27 10:35:58'),(16,'ZAMBOANGA','ZAMBOANGA',NULL,'2026-07-27 10:35:58'),(17,'PUERTO PRINCESSA','PUERTO PRINCESSA',NULL,'2026-07-27 10:35:58'),(18,'SURIGAO','SURIGAO',NULL,'2026-07-27 10:35:58'),(19,'COTABATO','COTABATO',NULL,'2026-07-27 10:35:58'),(20,'BATANGAS','BATANGAS',NULL,'2026-07-27 10:35:58'),(21,'MANILA','MANILA',NULL,'2026-07-27 10:35:58');
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
INSERT INTO `service_type` VALUES (1,'ORIGIN','DOOR',NULL,'2026-07-27 10:35:58'),(2,'ORIGIN','PIER-STUFFING',NULL,'2026-07-27 10:35:58'),(3,'ORIGIN','PIER-VANOUT',NULL,'2026-07-27 10:35:58'),(4,'DESTINATION','DOOR',NULL,'2026-07-27 10:35:58'),(5,'DESTINATION','PIER-STRIPPING',NULL,'2026-07-27 10:35:58'),(6,'DESTINATION','PIER-VAN OUT',NULL,'2026-07-27 10:35:58');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `serviceable_areas`
--

LOCK TABLES `serviceable_areas` WRITE;
/*!40000 ALTER TABLE `serviceable_areas` DISABLE KEYS */;
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
INSERT INTO `sessions` VALUES ('1JKk4YKg80k4zQ1RmWeE1mgtHw5PhxYiOVKEK5Bg',14,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZkdvZFV3YXFnUkdQQzZBc3pwaVVLQjlDUlFVaHV3Wk5BSWZpTUp4bSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjYyOiJodHRwOi8va2FyZ2FtaW5lX3Byb3RvdHlwZS50ZXN0L2FwaS9ub3RpZmljYXRpb25zL3VucmVhZC1jb3VudCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE0O30=',1785170159),('aVxlNIAFtC9uGjkt3Kwbp09azWjX3JXUGp94w3hK',13,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiYTlQbVp5MEVDamF0cHM5NjQ0WGFjT1lHUndaSndIVFhKeHJ3Z3JsSCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjYyOiJodHRwOi8va2FyZ2FtaW5lX3Byb3RvdHlwZS50ZXN0L2FwaS9ub3RpZmljYXRpb25zL3VucmVhZC1jb3VudCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjEzO30=',1785169858),('NBA8hGr3Q3JNzTSjOIXs1LHMyHMFlEOiZhB2Y9X6',4,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiYmdTS1VndzNNUVo3ZHU4QnRsc1hGRVJ0YUNTZ1hYN1NaMVRlYzhOTiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjYyOiJodHRwOi8va2FyZ2FtaW5lX3Byb3RvdHlwZS50ZXN0L2FwaS9ub3RpZmljYXRpb25zL3VucmVhZC1jb3VudCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjQ7fQ==',1785170159),('OX7AEwC36awLQ1c1jEd354KWpWTZdVP3OvjfxaPE',3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoic0pEdmtPMmNTNDFTQ0xqdEVRR0tQSzU1TENEUXByejhaa0xWVVlyeSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NjI6Imh0dHA6Ly9rYXJnYW1pbmVfcHJvdG90eXBlLnRlc3QvYXBpL25vdGlmaWNhdGlvbnMvdW5yZWFkLWNvdW50Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7fQ==',1785171067),('VTW9HaKPLhgJAdOEpJwNdSJMchLvqfei30rgVywP',NULL,'127.0.0.1','curl/8.12.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoid29CbnNpOERhYXJ2Zm1yaEg4WHpVSWY0NkZaYzNWUXVoaDBocVlRZiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9rYXJnYW1pbmVfcHJvdG90eXBlLnRlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1785149025);
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `setting_role`
--

LOCK TABLES `setting_role` WRITE;
/*!40000 ALTER TABLE `setting_role` DISABLE KEYS */;
INSERT INTO `setting_role` VALUES (1,'superadmin',1),(2,'admin',1),(3,'user',1),(4,'developer',1),(5,'Manager',0),(6,'Sales',0);
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teams`
--

LOCK TABLES `teams` WRITE;
/*!40000 ALTER TABLE `teams` DISABLE KEYS */;
INSERT INTO `teams` VALUES (8,'Operations','Operations Team',NULL,1,'2026-07-27 14:45:48','2026-07-27 14:45:48'),(9,'Sales','Sales Team 1',8,1,'2026-07-27 14:47:55','2026-07-27 14:47:55');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trucking_tariffs`
--

LOCK TABLES `trucking_tariffs` WRITE;
/*!40000 ALTER TABLE `trucking_tariffs` DISABLE KEYS */;
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
INSERT INTO `user_department` VALUES (1,'Sales Department','2026-07-27 10:35:59','2026-07-27 10:35:59'),(2,'Operations Department','2026-07-27 10:35:59','2026-07-27 10:35:59');
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
INSERT INTO `user_status` VALUES (1,'Active','2026-07-27 10:35:59','2026-07-27 10:35:59'),(2,'Inactive','2026-07-27 10:35:59','2026-07-27 10:35:59'),(3,'Suspended','2026-07-27 10:35:59','2026-07-27 10:35:59'),(4,'Pending','2026-07-27 10:35:59','2026-07-27 10:35:59');
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Developer','superadmin@email.com',NULL,'$2y$12$2VtSQQZw5lVRrpTC1YNAWOuc1gOp8yTRLlYupSJWM5VXlaKTgRuUm',NULL,'1',0,NULL,NULL,'2026-07-27 10:35:58','2026-07-27 14:46:00',NULL,0),(2,'Synxcel Gabby','gabriel.david@email.com',NULL,'$2y$12$wQ6iRZy5ZFCr995XoUPPKuiHXnXYvvqRDmHiA/saDk.EXY/wv/1JS',NULL,'1',0,NULL,NULL,'2026-07-27 10:35:58','2026-07-27 10:35:58',NULL,0),(3,'Synxcel Minton','minton.diaz@email.com',NULL,'$2y$12$KMUkx7bKXe.TWOUuZv.9P.38js6ezwRT0zn2sd1pT.FRwXQfKBwz2',NULL,'1',0,NULL,NULL,'2026-07-27 10:35:58','2026-07-27 10:35:58',NULL,0),(4,'Kargamine User','user.kargamine@email.com',NULL,'$2y$12$VcsMpZsGSbSjGM/v6INAyOTCKhJtwZbqyDFDW11Vi4LuV.rPF3RO2',NULL,'6',0,NULL,NULL,'2026-07-27 10:35:58','2026-07-27 15:26:54',9,0),(13,'Fritzie Tangan','fritzie.tangan@kargamine.com.ph',NULL,'$2y$12$2bzgVcIm8hfGezWfQAQOIuYdXhvPKiDsYGfQeKfunchllKH4sZdL2',NULL,'5',0,NULL,NULL,'2026-07-27 14:44:43','2026-07-27 14:46:05',8,1),(14,'Eden Palma','eden.palma@karga-container.com',NULL,'$2y$12$OiGLwi5kK78jstQKRm0F7Oo//KlZNpR.tssAFcsVM4l5SqQ2r93P2',NULL,'6',0,NULL,NULL,'2026-07-27 14:45:17','2026-07-27 14:48:03',9,1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vat_rates`
--

LOCK TABLES `vat_rates` WRITE;
/*!40000 ALTER TABLE `vat_rates` DISABLE KEYS */;
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

-- Dump completed on 2026-07-28  0:51:49

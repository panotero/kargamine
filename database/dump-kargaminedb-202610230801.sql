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
INSERT INTO `app_information_settings` VALUES (1,'Management System',NULL,NULL,'2026-09-17 09:50:01','2026-09-17 09:50:01');
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
INSERT INTO `app_theme_settings` VALUES (1,'blue','orange','slate','red','system','2026-09-17 13:23:52','2026-09-17 13:23:52');
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
INSERT INTO `cache` VALUES ('management_system_cache_1fd8e4cfeb25c473c174cced2fcfdfb7','i:24;',1792708951),('management_system_cache_1fd8e4cfeb25c473c174cced2fcfdfb7:timer','i:1792708951;',1792708951),('management_system_cache_21c7ea48997eeecf541f9afb4a8bfc81','i:8;',1792507562),('management_system_cache_21c7ea48997eeecf541f9afb4a8bfc81:timer','i:1792507562;',1792507562),('management_system_cache_7f3072bf378b98d6bbf2f013cff3e287','i:17;',1792526829),('management_system_cache_7f3072bf378b98d6bbf2f013cff3e287:timer','i:1792526829;',1792526829),('management_system_cache_830b3b63aeb7c393f1a75d0894c14921','i:4;',1792713677),('management_system_cache_830b3b63aeb7c393f1a75d0894c14921:timer','i:1792713677;',1792713677),('management_system_cache_a3affa0d1e1a3c72b78aa984c3367a05','i:35;',1791267216),('management_system_cache_a3affa0d1e1a3c72b78aa984c3367a05:timer','i:1791267216;',1791267216),('management_system_cache_ad4954e2e38bb42a3ba5cbc5eebbbdbc','i:18;',1792710029),('management_system_cache_ad4954e2e38bb42a3ba5cbc5eebbbdbc:timer','i:1792710029;',1792710029),('management_system_cache_b37e2b0b86a8368405df02e0b66d0b39','i:18;',1792474097),('management_system_cache_b37e2b0b86a8368405df02e0b66d0b39:timer','i:1792474097;',1792474097),('management_system_cache_d2bfa8e8b749d2772a21edee7b70a2b3','i:19;',1792435149),('management_system_cache_d2bfa8e8b749d2772a21edee7b70a2b3:timer','i:1792435149;',1792435149),('management_system_cache_de226f3f5dc0c66a464effdc07ca6b1f','i:2;',1792711744),('management_system_cache_de226f3f5dc0c66a464effdc07ca6b1f:timer','i:1792711744;',1792711744),('management_system_cache_e7cf66797159dc3cd3e85f72e15bb551','i:32;',1792710073),('management_system_cache_e7cf66797159dc3cd3e85f72e15bb551:timer','i:1792710073;',1792710073),('management_system_cache_f1f70ec40aaa556905d4a030501c0ba4','i:1;',1792707618),('management_system_cache_f1f70ec40aaa556905d4a030501c0ba4:timer','i:1792707618;',1792707618);
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
INSERT INTO `cargo_yards` VALUES (1,'Batangas - Bauan',1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(2,'CDO - Villanueva',1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(3,'Cebu - Talisay',1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(4,'Masbate - Mobo',1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(5,'Palawan - Brooke\'s Point',1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(6,'Palawan - Coron',1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(7,'Palawan - Puerto Princesa',1,'2026-09-17 00:59:29','2026-09-17 00:59:29');
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
INSERT INTO `charge_types` VALUES (1,'WHARF','Wharfage','PORT',1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(2,'ARRASTRE','Arrastre Charge','PORT',1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(3,'THC','Terminal Handling Charge','PORT',1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(4,'DOC_FEE','Documentation Fee','GENERAL',1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(5,'INS_FEE','Insurance Fee','GENERAL',1,'2026-09-17 00:59:29','2026-09-17 00:59:29');
/*!40000 ALTER TABLE `charge_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `charter_services`
--

DROP TABLE IF EXISTS `charter_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `charter_services` (
  `charter_service_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`charter_service_id`),
  UNIQUE KEY `charter_services_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `charter_services`
--

LOCK TABLES `charter_services` WRITE;
/*!40000 ALTER TABLE `charter_services` DISABLE KEYS */;
/*!40000 ALTER TABLE `charter_services` ENABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_addresses`
--

LOCK TABLES `client_addresses` WRITE;
/*!40000 ALTER TABLE `client_addresses` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_commodity_declared_values`
--

LOCK TABLES `client_commodity_declared_values` WRITE;
/*!40000 ALTER TABLE `client_commodity_declared_values` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_contact_addresses`
--

LOCK TABLES `client_contact_addresses` WRITE;
/*!40000 ALTER TABLE `client_contact_addresses` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_contacts`
--

LOCK TABLES `client_contacts` WRITE;
/*!40000 ALTER TABLE `client_contacts` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_finance`
--

LOCK TABLES `client_finance` WRITE;
/*!40000 ALTER TABLE `client_finance` DISABLE KEYS */;
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
  `prospect_id` bigint(20) unsigned DEFAULT NULL,
  `customer_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_mnemonic` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_classification` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `always_route_atw` tinyint(1) NOT NULL DEFAULT 0,
  `industry` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `industry_subcategory` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  KEY `client_masters_lead_id_foreign` (`prospect_id`),
  KEY `client_masters_account_manager_id_foreign` (`account_manager_id`),
  CONSTRAINT `client_masters_account_manager_id_foreign` FOREIGN KEY (`account_manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `client_masters_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `client_masters_lead_id_foreign` FOREIGN KEY (`prospect_id`) REFERENCES `prospects` (`id`) ON DELETE SET NULL,
  CONSTRAINT `client_masters_sales_rep_id_foreign` FOREIGN KEY (`sales_rep_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_masters`
--

LOCK TABLES `client_masters` WRITE;
/*!40000 ALTER TABLE `client_masters` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_masters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_proposal_rate_ancillary_services`
--

DROP TABLE IF EXISTS `client_proposal_rate_ancillary_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_proposal_rate_ancillary_services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_rate_id` bigint(20) unsigned NOT NULL,
  `required_service` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_proposal_rate_ancillary_services_proposal_rate_id_foreign` (`proposal_rate_id`),
  CONSTRAINT `client_proposal_rate_ancillary_services_proposal_rate_id_foreign` FOREIGN KEY (`proposal_rate_id`) REFERENCES `client_proposal_rates` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_proposal_rate_ancillary_services`
--

LOCK TABLES `client_proposal_rate_ancillary_services` WRITE;
/*!40000 ALTER TABLE `client_proposal_rate_ancillary_services` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_proposal_rate_ancillary_services` ENABLE KEYS */;
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
  `origin_pickup_area_id` bigint(20) unsigned DEFAULT NULL,
  `destination_port_id` bigint(20) unsigned NOT NULL,
  `destination_pickup_area_id` bigint(20) unsigned DEFAULT NULL,
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
  KEY `client_proposal_rates_origin_pickup_area_id_foreign` (`origin_pickup_area_id`),
  KEY `client_proposal_rates_destination_pickup_area_id_foreign` (`destination_pickup_area_id`),
  CONSTRAINT `client_proposal_rates_container_class_id_foreign` FOREIGN KEY (`container_class_id`) REFERENCES `container_class` (`id`),
  CONSTRAINT `client_proposal_rates_container_id_foreign` FOREIGN KEY (`container_id`) REFERENCES `containers` (`id`),
  CONSTRAINT `client_proposal_rates_container_size_id_foreign` FOREIGN KEY (`container_size_id`) REFERENCES `container_size` (`id`),
  CONSTRAINT `client_proposal_rates_container_variant_id_foreign` FOREIGN KEY (`container_variant_id`) REFERENCES `container_variants` (`id`),
  CONSTRAINT `client_proposal_rates_destination_pickup_area_id_foreign` FOREIGN KEY (`destination_pickup_area_id`) REFERENCES `serviceable_areas` (`area_id`) ON DELETE SET NULL,
  CONSTRAINT `client_proposal_rates_destination_port_id_foreign` FOREIGN KEY (`destination_port_id`) REFERENCES `ports` (`port_id`),
  CONSTRAINT `client_proposal_rates_origin_pickup_area_id_foreign` FOREIGN KEY (`origin_pickup_area_id`) REFERENCES `serviceable_areas` (`area_id`) ON DELETE SET NULL,
  CONSTRAINT `client_proposal_rates_origin_port_id_foreign` FOREIGN KEY (`origin_port_id`) REFERENCES `ports` (`port_id`),
  CONSTRAINT `client_proposal_rates_proposal_id_foreign` FOREIGN KEY (`proposal_id`) REFERENCES `client_proposals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_proposal_rates`
--

LOCK TABLES `client_proposal_rates` WRITE;
/*!40000 ALTER TABLE `client_proposal_rates` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_proposal_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_proposal_signed_rates`
--

DROP TABLE IF EXISTS `client_proposal_signed_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_proposal_signed_rates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_proposal_id` bigint(20) unsigned NOT NULL,
  `source_rate_id` bigint(20) unsigned DEFAULT NULL,
  `origin_port_id` bigint(20) unsigned NOT NULL,
  `origin_pickup_area_id` bigint(20) unsigned DEFAULT NULL,
  `destination_port_id` bigint(20) unsigned NOT NULL,
  `destination_pickup_area_id` bigint(20) unsigned DEFAULT NULL,
  `container_id` bigint(20) unsigned NOT NULL,
  `container_class_id` bigint(20) unsigned DEFAULT NULL,
  `container_size_id` bigint(20) unsigned DEFAULT NULL,
  `container_variant_id` bigint(20) unsigned NOT NULL,
  `min_van_qty` int(11) DEFAULT NULL,
  `base_rate` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_value` decimal(12,2) NOT NULL DEFAULT 0.00,
  `final_rate` decimal(12,2) NOT NULL DEFAULT 0.00,
  `include_special_charges` tinyint(1) NOT NULL DEFAULT 0,
  `include_port_charges` tinyint(1) NOT NULL DEFAULT 0,
  `include_handling_fee` tinyint(1) NOT NULL DEFAULT 0,
  `include_general_charges` tinyint(1) NOT NULL DEFAULT 0,
  `ancillary_services` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`ancillary_services`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_proposal_signed_rates_client_proposal_id_foreign` (`client_proposal_id`),
  CONSTRAINT `client_proposal_signed_rates_client_proposal_id_foreign` FOREIGN KEY (`client_proposal_id`) REFERENCES `client_proposals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_proposal_signed_rates`
--

LOCK TABLES `client_proposal_signed_rates` WRITE;
/*!40000 ALTER TABLE `client_proposal_signed_rates` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_proposal_signed_rates` ENABLE KEYS */;
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
  `prospect_id` bigint(20) unsigned DEFAULT NULL,
  `proposal_request_id` bigint(20) unsigned DEFAULT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `signed_document_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signed_at` timestamp NULL DEFAULT NULL,
  `signature_requested_at` timestamp NULL DEFAULT NULL,
  `include_special_charges` tinyint(1) NOT NULL DEFAULT 0,
  `include_port_charges` tinyint(1) NOT NULL DEFAULT 0,
  `include_handling_fee` tinyint(1) NOT NULL DEFAULT 0,
  `include_general_charges` tinyint(1) NOT NULL DEFAULT 0,
  `decided_by` bigint(20) unsigned DEFAULT NULL,
  `decided_at` timestamp NULL DEFAULT NULL,
  `decision_remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rm_approved_by` bigint(20) unsigned DEFAULT NULL,
  `rm_approved_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `client_proposals_uuid_unique` (`uuid`),
  UNIQUE KEY `client_proposals_code_unique` (`code`),
  KEY `client_proposals_client_id_foreign` (`client_id`),
  KEY `client_proposals_created_by_foreign` (`created_by`),
  KEY `client_proposals_decided_by_foreign` (`decided_by`),
  KEY `client_proposals_lead_id_foreign` (`prospect_id`),
  KEY `client_proposals_rm_approved_by_foreign` (`rm_approved_by`),
  KEY `client_proposals_proposal_request_id_foreign` (`proposal_request_id`),
  CONSTRAINT `client_proposals_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `client_masters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `client_proposals_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `client_proposals_decided_by_foreign` FOREIGN KEY (`decided_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `client_proposals_lead_id_foreign` FOREIGN KEY (`prospect_id`) REFERENCES `prospects` (`id`) ON DELETE SET NULL,
  CONSTRAINT `client_proposals_proposal_request_id_foreign` FOREIGN KEY (`proposal_request_id`) REFERENCES `proposal_requests` (`id`) ON DELETE SET NULL,
  CONSTRAINT `client_proposals_rm_approved_by_foreign` FOREIGN KEY (`rm_approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_proposals`
--

LOCK TABLES `client_proposals` WRITE;
/*!40000 ALTER TABLE `client_proposals` DISABLE KEYS */;
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
  `container_id` bigint(20) unsigned NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `container_class_container_id_class_unique` (`container_id`,`class`),
  CONSTRAINT `container_class_container_id_foreign` FOREIGN KEY (`container_id`) REFERENCES `containers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `container_class`
--

LOCK TABLES `container_class` WRITE;
/*!40000 ALTER TABLE `container_class` DISABLE KEYS */;
INSERT INTO `container_class` VALUES (1,1,'Standard','2026-09-17 00:59:29','2026-09-17 00:59:29'),(2,1,'High Cube','2026-09-17 00:59:29','2026-09-17 00:59:29'),(3,2,'Standard','2026-09-17 00:59:29','2026-09-17 00:59:29'),(4,2,'High Cube','2026-09-17 00:59:29','2026-09-17 00:59:29'),(5,3,'Standard','2026-09-17 00:59:29','2026-09-17 00:59:29'),(6,3,'Collapsible','2026-09-17 00:59:29','2026-09-17 00:59:29'),(7,4,'MT','2026-09-17 00:59:29','2026-09-17 00:59:29'),(8,4,'CBM','2026-09-17 00:59:29','2026-09-17 00:59:29'),(9,5,'MT','2026-09-17 00:59:29','2026-09-17 00:59:29'),(10,5,'CBM','2026-09-17 00:59:29','2026-09-17 00:59:29');
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `container_size`
--

LOCK TABLES `container_size` WRITE;
/*!40000 ALTER TABLE `container_size` DISABLE KEYS */;
INSERT INTO `container_size` VALUES (1,1,'20FT','2026-09-17 00:59:29','2026-09-17 00:59:29'),(2,1,'40FT','2026-09-17 00:59:29','2026-09-17 00:59:29'),(3,2,'20FT','2026-09-17 00:59:29','2026-09-17 00:59:29'),(4,2,'40FT','2026-09-17 00:59:29','2026-09-17 00:59:29'),(5,3,'20FT','2026-09-17 00:59:29','2026-09-17 00:59:29'),(6,3,'40FT','2026-09-17 00:59:29','2026-09-17 00:59:29');
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
INSERT INTO `container_type` VALUES (1,'CONVAN',NULL,'2026-09-17 00:59:27'),(2,'FLATRACK (PLATFORM)',NULL,'2026-09-17 00:59:27'),(3,'REEFER',NULL,'2026-09-17 00:59:27'),(4,'HIGH CUBE',NULL,'2026-09-17 00:59:27'),(5,'CATTLE VAN',NULL,'2026-09-17 00:59:27'),(6,'TANK (ISO TANK)',NULL,'2026-09-17 00:59:27'),(7,'ROLLING CARGO',NULL,'2026-09-17 00:59:27'),(8,'SPECIAL CONTAINERS',NULL,'2026-09-17 00:59:27'),(9,'OPEN-TOP VAN',NULL,'2026-09-17 00:59:27');
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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `container_variants`
--

LOCK TABLES `container_variants` WRITE;
/*!40000 ALTER TABLE `container_variants` DISABLE KEYS */;
INSERT INTO `container_variants` VALUES (1,1,NULL,1,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(2,1,NULL,2,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(3,1,1,1,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(4,1,2,1,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(5,1,1,2,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(6,1,2,2,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(7,2,NULL,3,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(8,2,NULL,4,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(9,2,3,3,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(10,2,4,3,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(11,2,3,4,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(12,2,4,4,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(13,3,NULL,5,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(14,3,NULL,6,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(15,3,5,5,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(16,3,6,5,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(17,3,5,6,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(18,3,6,6,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(19,4,7,NULL,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(20,4,8,NULL,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(21,5,9,NULL,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(22,5,10,NULL,1,'2026-09-17 00:59:29','2026-09-17 00:59:29');
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `containers`
--

LOCK TABLES `containers` WRITE;
/*!40000 ALTER TABLE `containers` DISABLE KEYS */;
INSERT INTO `containers` VALUES (1,'CV','Container Van',1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(2,'RF','Reefer Van',1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(3,'FR','Flat Rack',1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(4,'LC','Loose Cargo',1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(5,'RC','Rolling Cargo',1,'2026-09-17 00:59:29','2026-09-17 00:59:29');
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
  `prospect_id` bigint(20) unsigned NOT NULL,
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
  KEY `contracts_lead_id_status_index` (`prospect_id`,`status`),
  KEY `contracts_valid_from_valid_to_index` (`valid_from`,`valid_to`),
  CONSTRAINT `contracts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `contracts_lead_id_foreign` FOREIGN KEY (`prospect_id`) REFERENCES `prospects` (`id`),
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
INSERT INTO `crm_status` VALUES (1,'PROSPECT','New incoming lead','2026-09-17 00:59:27','2026-09-17 00:59:27'),(2,'QUALIFIED','Lead is qualified and potential','2026-09-17 00:59:27','2026-09-17 00:59:27'),(3,'OPPORTUNITY','Converted into sales opportunity','2026-09-17 00:59:27','2026-09-17 00:59:27'),(4,'NEGOTIATION','In negotiation stage','2026-09-17 00:59:27','2026-09-17 00:59:27'),(5,'WIN','Final stage: won or lost','2026-09-17 00:59:27','2026-09-17 00:59:27'),(6,'LOST','Final stage: won or lost','2026-09-17 00:59:27','2026-09-17 00:59:27');
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
INSERT INTO `customer_type` VALUES (1,'SHIPPER',NULL,'2026-09-17 00:59:27'),(2,'CONSIGNEE',NULL,'2026-09-17 00:59:27'),(3,'SHIPPER-CONSIGNEE',NULL,'2026-09-17 00:59:27');
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
INSERT INTO `delivery_types` VALUES (1,'DD','Door-Door',1,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(2,'DP','Door-Pier',1,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(3,'PD','Pier-Door',0,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(4,'PP','Pier-Pier',0,0,'2026-09-17 00:59:28','2026-09-17 00:59:28');
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
) ENGINE=InnoDB AUTO_INCREMENT=149 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
  `frt` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lane_tariff_rate_prices_unique` (`lane_tariff_rate_id`,`container_variant_id`),
  KEY `lane_tariff_rate_prices_container_variant_id_foreign` (`container_variant_id`),
  CONSTRAINT `lane_tariff_rate_prices_container_variant_id_foreign` FOREIGN KEY (`container_variant_id`) REFERENCES `container_variants` (`id`),
  CONSTRAINT `lane_tariff_rate_prices_lane_tariff_rate_id_foreign` FOREIGN KEY (`lane_tariff_rate_id`) REFERENCES `lane_tariff_rates` (`rate_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lane_tariff_rate_prices`
--

LOCK TABLES `lane_tariff_rate_prices` WRITE;
/*!40000 ALTER TABLE `lane_tariff_rate_prices` DISABLE KEYS */;
INSERT INTO `lane_tariff_rate_prices` VALUES (1,1,1,9000.00,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(2,1,2,14400.00,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(3,1,3,9000.00,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(4,1,4,10400.00,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(5,1,5,14400.00,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(6,1,6,16600.00,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(7,1,7,12600.00,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(8,1,8,20200.00,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(9,1,9,12600.00,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(10,1,10,14500.00,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(11,1,11,20200.00,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(12,1,12,23200.00,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(13,1,13,9900.00,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(14,1,14,15800.00,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(15,1,15,9900.00,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(16,1,16,9900.00,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(17,1,17,15800.00,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(18,1,18,15800.00,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(19,2,1,123.00,'2026-10-20 06:51:10','2026-10-20 06:51:10'),(20,2,2,123.00,'2026-10-20 06:51:10','2026-10-20 06:51:10'),(21,2,3,123.00,'2026-10-20 06:51:10','2026-10-20 06:51:10'),(22,2,5,123.00,'2026-10-20 06:51:10','2026-10-20 06:51:10'),(23,2,4,123.00,'2026-10-20 06:51:10','2026-10-20 06:51:10'),(24,2,6,123.00,'2026-10-20 06:51:10','2026-10-20 06:51:10'),(25,2,7,123.00,'2026-10-20 06:51:10','2026-10-20 06:51:10'),(26,2,8,123.00,'2026-10-20 06:51:10','2026-10-20 06:51:10'),(27,2,13,123.00,'2026-10-20 06:51:10','2026-10-20 06:51:10'),(28,2,14,123.00,'2026-10-20 06:51:10','2026-10-20 06:51:10'),(29,2,19,123.00,'2026-10-20 06:51:10','2026-10-20 06:51:10'),(30,2,20,123.00,'2026-10-20 06:51:10','2026-10-20 06:51:10'),(31,2,21,123.00,'2026-10-20 06:51:10','2026-10-20 06:51:10'),(32,2,22,123.00,'2026-10-20 06:51:10','2026-10-20 06:51:10'),(33,3,1,5000.00,'2026-10-22 21:08:39','2026-10-22 21:08:39'),(34,3,2,6000.00,'2026-10-22 21:08:39','2026-10-22 21:08:39'),(35,3,7,5000.00,'2026-10-22 21:08:39','2026-10-22 21:08:39'),(36,3,8,6000.00,'2026-10-22 21:08:39','2026-10-22 21:08:39'),(37,3,13,5000.00,'2026-10-22 21:08:39','2026-10-22 21:08:39'),(38,3,14,6000.00,'2026-10-22 21:08:39','2026-10-22 21:08:39'),(39,3,19,5000.00,'2026-10-22 21:08:39','2026-10-22 21:08:39'),(40,3,20,6000.00,'2026-10-22 21:08:39','2026-10-22 21:08:39'),(41,3,21,5000.00,'2026-10-22 21:08:39','2026-10-22 21:08:39'),(42,3,22,6000.00,'2026-10-22 21:08:39','2026-10-22 21:08:39'),(43,4,1,4500.00,'2026-10-23 13:52:46','2026-10-23 13:52:46'),(44,4,2,4500.00,'2026-10-23 13:52:46','2026-10-23 13:52:46'),(45,4,7,4500.00,'2026-10-23 13:52:46','2026-10-23 13:52:46'),(46,4,8,4500.00,'2026-10-23 13:52:46','2026-10-23 13:52:46'),(47,4,13,4500.00,'2026-10-23 13:52:46','2026-10-23 13:52:46'),(48,4,14,4500.00,'2026-10-23 13:52:46','2026-10-23 13:52:46'),(49,4,19,4500.00,'2026-10-23 13:52:46','2026-10-23 13:52:46'),(50,4,20,4500.00,'2026-10-23 13:52:46','2026-10-23 13:52:46'),(51,4,21,4500.00,'2026-10-23 13:52:46','2026-10-23 13:52:46'),(52,4,22,4500.00,'2026-10-23 13:52:46','2026-10-23 13:52:46');
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lane_tariff_rates`
--

LOCK TABLES `lane_tariff_rates` WRITE;
/*!40000 ALTER TABLE `lane_tariff_rates` DISABLE KEYS */;
INSERT INTO `lane_tariff_rates` VALUES (1,1,'2026-07-27',NULL,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(2,13,'2026-10-20',NULL,1,'2026-10-20 06:51:10','2026-10-20 06:51:10'),(3,14,'2026-10-22',NULL,1,'2026-10-22 21:08:39','2026-10-22 21:08:39'),(4,15,'2026-10-22',NULL,1,'2026-10-23 13:52:46','2026-10-23 13:52:46');
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lanes`
--

LOCK TABLES `lanes` WRITE;
/*!40000 ALTER TABLE `lanes` DISABLE KEYS */;
INSERT INTO `lanes` VALUES (1,29,19,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(2,19,29,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(3,29,12,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(4,12,29,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(5,29,20,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(6,20,29,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(7,29,3,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(8,3,29,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(9,29,18,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(10,18,29,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(11,19,20,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(12,20,19,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(13,31,45,1,'2026-10-20 06:51:10','2026-10-20 06:51:10'),(14,1,27,1,'2026-10-22 21:08:38','2026-10-22 21:08:38'),(15,27,2,1,'2026-10-23 13:52:46','2026-10-23 13:52:46');
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
  `parent_lov_id` bigint(20) unsigned DEFAULT NULL,
  `lov_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lov_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lov_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lov_is_individual` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`lov_id`),
  UNIQUE KEY `list_of_values_table_lov_optionid_lov_name_unique` (`lov_optionId`,`lov_name`),
  KEY `list_of_values_table_lov_optionid_index` (`lov_optionId`),
  KEY `list_of_values_table_lov_name_index` (`lov_name`),
  KEY `list_of_values_table_parent_lov_id_foreign` (`parent_lov_id`),
  CONSTRAINT `list_of_values_table_lov_optionid_foreign` FOREIGN KEY (`lov_optionId`) REFERENCES `options_table` (`option_id`) ON DELETE CASCADE,
  CONSTRAINT `list_of_values_table_parent_lov_id_foreign` FOREIGN KEY (`parent_lov_id`) REFERENCES `list_of_values_table` (`lov_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=185 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `list_of_values_table`
--

LOCK TABLES `list_of_values_table` WRITE;
/*!40000 ALTER TABLE `list_of_values_table` DISABLE KEYS */;
INSERT INTO `list_of_values_table` VALUES (1,1,NULL,'IND','Individual',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(2,1,NULL,'MAI','Main Office',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(3,1,NULL,'BRA','Branch Office',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(4,1,NULL,'HEA','Head Office',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(5,1,NULL,'POR','Port',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(6,1,NULL,'YAR','Yard',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(7,1,NULL,'WAR','Warehouse',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(8,2,NULL,'COL','Cold Call',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(9,2,NULL,'REF','Referral',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(10,2,NULL,'SOC','Social Media',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(11,2,NULL,'WAL','Walk-In',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(12,2,NULL,'OTH','Others',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(13,3,NULL,'MR.','Mr.',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(14,3,NULL,'MRS','Mrs.',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(15,3,NULL,'MS.','Ms.',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(16,3,NULL,'ARC','Arch.',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(17,3,NULL,'ATT','Atty.',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(18,3,NULL,'DR.','Dr.',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(19,3,NULL,'ENG','Engr.',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(20,3,NULL,'PRO','Prof.',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(21,3,NULL,'FR.','Fr.',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(22,3,NULL,'BR.','Br.',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(23,3,NULL,'SR.','Sr.',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(24,4,NULL,'GEN','General Cargo',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(25,4,NULL,'PER','Perishable Goods',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(26,4,NULL,'FRA','Fragile / Breakable',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(27,4,NULL,'LIQ','Liquid Bulk',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(28,4,NULL,'DRY','Dry Bulk',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(29,4,NULL,'MAC','Machinery & Equipment',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(30,4,NULL,'LIV','Livestock',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(31,4,NULL,'DOC','Documents / Parcels',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(32,4,NULL,'FRO','Frozen / Chilled Goods',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(33,5,NULL,'ACC','Accommodation and Food Service Activities',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(34,5,NULL,'ACT','Activities of Extraterritorial Organizations and Bodies',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(35,5,NULL,'ACT','Activities of Households as Employers; Undifferentiated Goods- and Services-Producing Activities of Households for own use',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(36,5,NULL,'ADM','Administrative and Support Service Activities',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(37,5,NULL,'AGR','Agriculture, Forestry and Fishing',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(38,5,NULL,'ART','Arts, Sports and Recreation',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(39,5,NULL,'CON','Construction',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(40,5,NULL,'EDU','Education',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(41,5,NULL,'ELE','Electricity, Gas, Steam and Air Conditioning Supply',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(42,5,NULL,'FIN','Financial and Insurance Activities',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(43,5,NULL,'HUM','Human Health and Social Work Activities',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(44,5,NULL,'MAN','Manufacturing',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(45,5,NULL,'MIN','Mining and Quarrying',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(46,5,NULL,'OTH','Other Service Activities',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(47,5,NULL,'PRO','Professional, Scientific, and Technical Activities',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(48,5,NULL,'PUB','Public Administration and Defense; Compulsory Social Security',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(49,5,NULL,'PUB','Publishing, Broadcasting, and Content Production and Distribution Activities',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(50,5,NULL,'REA','Real Estate Activities',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(51,5,NULL,'TEL','Telecommunications, Computer Programming, Consultancy, Computing Infrastructure, and Other Information Service Activities',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(52,5,NULL,'TRA','Transportation and Storage',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(53,5,NULL,'WAT','Water Supply; Sewerage, Waste Management and Remediation',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(54,5,NULL,'WHO','Wholesale and Retail Trade',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(55,6,NULL,'FOO','Food & Beverage',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(56,6,NULL,'AGR','Agriculture & Fisheries',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(57,6,NULL,'RET','Retail & E-commerce',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(58,6,NULL,'WHO','Wholesale & Distribution',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(59,6,NULL,'MAN','Manufacturing',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(60,6,NULL,'CON','Construction & Building Materials',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(61,6,NULL,'AUT','Automotive & Transportation',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(62,6,NULL,'CON','Consumer Goods / FMCG',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(63,6,NULL,'ELE','Electronics & Appliances',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(64,6,NULL,'PHA','Pharmaceuticals & Healthcare',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(65,6,NULL,'IND','Industrial & Machinery',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(66,6,NULL,'CHE','Chemicals & Industrial Supplies',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(67,6,NULL,'TEX','Textiles, Apparel & Footwear',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(68,6,NULL,'FUR','Furniture & Home Furnishings',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(69,6,NULL,'MIN','Mining, Metals & Aggregates',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(70,6,NULL,'ENE','Energy & Utilities',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(71,6,NULL,'HOS','Hospitality & Food Service',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(72,6,NULL,'GOV','Government & Institutional',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(73,6,NULL,'LOG','Logistics & Transportation',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(74,6,NULL,'OTH','Other Services / Other',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(75,7,55,'BEV','Beverages',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(76,7,55,'PAC','Packaged Foods',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(77,7,55,'FRE','Fresh & Perishable',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(78,7,55,'DAI','Dairy Products',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(79,7,56,'CRO','Crop Production',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(80,7,56,'LIV','Livestock & Poultry',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(81,7,56,'FIS','Fisheries & Aquaculture',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(82,7,57,'ONL','Online Marketplace',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(83,7,57,'BRI','Brick & Mortar Retail',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(84,7,57,'DEP','Department Stores',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(85,7,58,'GEN','General Trading',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(86,7,58,'BUL','Bulk Distribution',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(87,7,58,'IMP','Import/Export Trading',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(88,7,59,'LIG','Light Manufacturing',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(89,7,59,'HEA','Heavy Manufacturing',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(90,7,59,'CON','Contract Manufacturing',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(91,7,60,'RES','Residential Construction',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(92,7,60,'COM','Commercial Construction',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(93,7,60,'BUI','Building Supplies',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(94,7,61,'VEH','Vehicle Parts & Accessories',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(95,7,61,'AUT','Automotive Dealership',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(96,7,61,'FLE','Fleet Services',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(97,7,62,'PER','Personal Care',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(98,7,62,'HOU','Household Products',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(99,7,62,'PAC','Packaged Consumer Goods',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(100,7,63,'CON','Consumer Electronics',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(101,7,63,'HOM','Home Appliances',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(102,7,63,'ELE','Electronic Components',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(103,7,64,'PHA','Pharmaceutical Manufacturing',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(104,7,64,'MED','Medical Devices',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(105,7,64,'HOS','Hospitals & Clinics',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(106,7,65,'HEA','Heavy Equipment',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(107,7,65,'IND','Industrial Tools',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(108,7,65,'MAC','Machine Parts',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(109,7,66,'IND','Industrial Chemicals',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(110,7,66,'AGR','Agrochemicals',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(111,7,66,'SAF','Safety & Supplies',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(112,7,67,'APP','Apparel Manufacturing',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(113,7,67,'FOO','Footwear',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(114,7,67,'TEX','Textile Materials',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(115,7,68,'FUR','Furniture Manufacturing',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(116,7,68,'HOM','Home Decor',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(117,7,68,'OFF','Office Furniture',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(118,7,69,'MET','Metal Ores',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(119,7,69,'QUA','Quarrying & Aggregates',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(120,7,69,'MET','Metal Fabrication',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(121,7,70,'POW','Power Generation',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(122,7,70,'OIL','Oil & Gas',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(123,7,70,'REN','Renewable Energy',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(124,7,71,'HOT','Hotels & Resorts',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(125,7,71,'RES','Restaurants & Catering',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(126,7,71,'FOO','Food Service Supply',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(127,7,72,'NAT','National Government',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(128,7,72,'LOC','Local Government Units',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(129,7,72,'NGO','NGOs & Institutions',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(130,7,73,'FRE','Freight Forwarding',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(131,7,73,'WAR','Warehousing',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(132,7,73,'COU','Courier & Last-Mile',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(133,7,74,'PRO','Professional Services',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(134,7,74,'OTH','Other',NULL,0,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(135,8,NULL,'SOL','Sole Proprietorship',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(136,8,NULL,'PAR','Partnership',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(137,8,NULL,'COR','Corporation',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(138,8,NULL,'COO','Cooperative',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(139,8,NULL,'GOV','Government',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(140,8,NULL,'NON','Non-Profit',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(141,9,NULL,'COR','Corporate',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(142,9,NULL,'RET','Retail',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(143,9,NULL,'LOG','Logistics Provider',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(144,10,NULL,'SHI','Shipper',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(145,10,NULL,'CON','Consignee',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(146,10,NULL,'SHI','Shipper/Consignee (both)',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(147,11,NULL,'SAL','Sales',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(148,11,NULL,'OPE','Operations',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(149,11,NULL,'BIL','Billing - Collection',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(150,11,NULL,'BIL','Billing - Finance',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(151,11,NULL,'BIL','Billing - Liquidation',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(152,11,NULL,'BIL','Billing - Submission',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(153,11,NULL,'PRO','Procurement',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(154,12,NULL,'CBM','cbm/s',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(155,12,NULL,'DAY','day/s',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(156,12,NULL,'HOU','hour/s',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(157,12,NULL,'LIT','liter/s',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(158,12,NULL,'MON','month/s',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(159,12,NULL,'MOV','move/s',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(160,12,NULL,'OCC','occurrence/s',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(161,12,NULL,'OTH','others',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(162,12,NULL,'PIE','piece/s',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(163,12,NULL,'SER','service/s',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(164,12,NULL,'SET','set/s',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(165,12,NULL,'TON','ton/s',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(166,12,NULL,'TRI','trip/s',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(167,12,NULL,'UNI','unit/s',NULL,0,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(168,13,NULL,'FOO','20-Footer GP',NULL,0,'2026-09-17 14:59:02','2026-09-17 14:59:02'),(169,13,NULL,'FOO','20-Footer HC',NULL,0,'2026-09-17 14:59:02','2026-09-17 14:59:02'),(170,13,NULL,'FOO','40-Footer GP',NULL,0,'2026-09-17 14:59:02','2026-09-17 14:59:02'),(171,13,NULL,'FOO','40-Footer HC',NULL,0,'2026-09-17 14:59:02','2026-09-17 14:59:02'),(172,13,NULL,'REE','Reefer Van',NULL,0,'2026-09-17 14:59:02','2026-09-17 14:59:02'),(173,13,NULL,'FLA','Flatrack',NULL,0,'2026-09-17 14:59:02','2026-09-17 14:59:02'),(174,13,NULL,'ROL','Rolling Cargo',NULL,0,'2026-09-17 14:59:02','2026-09-17 14:59:02'),(175,13,NULL,'LOO','Loose Cargo',NULL,0,'2026-09-17 14:59:02','2026-09-17 14:59:02'),(176,13,NULL,'OTH','Others',NULL,0,'2026-09-17 14:59:02','2026-09-17 14:59:02'),(177,14,NULL,'TRU','Trucking',NULL,0,'2026-09-18 16:00:45','2026-09-18 16:00:45'),(178,14,NULL,'STO','Storage',NULL,0,'2026-09-18 16:00:45','2026-09-18 16:00:45'),(179,14,NULL,'LOA','Loading/Unloading',NULL,0,'2026-09-18 16:00:45','2026-09-18 16:00:45'),(180,14,NULL,'FUM','Fumigation',NULL,0,'2026-09-18 16:00:45','2026-09-18 16:00:45'),(181,14,NULL,'WEI','Weighing',NULL,0,'2026-09-18 16:00:45','2026-09-18 16:00:45'),(182,14,NULL,'DOC','Documentation',NULL,0,'2026-09-18 16:00:45','2026-09-18 16:00:45'),(183,14,NULL,'INS','Insurance',NULL,0,'2026-09-18 16:00:45','2026-09-18 16:00:45'),(184,14,NULL,'OTH','Others',NULL,0,'2026-09-18 16:00:45','2026-09-18 16:00:45');
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
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locations`
--

LOCK TABLES `locations` WRITE;
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;
INSERT INTO `locations` VALUES (1,'Aklan',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(2,'Bacolod',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(3,'Bataan',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(4,'Batangas',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(5,'Bicol',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(6,'Butuan',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(7,'CDO',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(8,'Cebu',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(9,'Davao',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(10,'Dinagat',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(11,'La Union',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(12,'Leyte',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(13,'Metro Manila (NCR)',1,'2026-09-17 00:59:28','2026-10-05 08:44:55'),(14,'Masbate',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(15,'Palawan',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(16,'Pangasinan',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(17,'Romblon',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(18,'Roxas',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(19,'Surigao',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(20,'Tacloban',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(21,'Zambales',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(22,'Zamboanga',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(23,'Abra',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(24,'Agusan del Norte',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(25,'Agusan del Sur',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(26,'Albay',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(27,'Antique',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(28,'Apayao',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(29,'Aurora',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(30,'Basilan',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(31,'Batanes',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(32,'Benguet',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(33,'Biliran',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(34,'Bohol',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(35,'Bukidnon',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(36,'Bulacan',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(37,'Cagayan',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(38,'Camarines Norte',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(39,'Camarines Sur',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(40,'Camiguin',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(41,'Capiz',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(42,'Catanduanes',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(43,'Cavite',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(44,'Cotabato',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(45,'Davao Occidental',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(46,'Davao Oriental',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(47,'Davao de Oro',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(48,'Davao del Norte',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(49,'Davao del Sur',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(50,'Dinagat Islands',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(51,'Eastern Samar',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(52,'Guimaras',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(53,'Ifugao',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(54,'Ilocos Norte',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(55,'Ilocos Sur',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(56,'Iloilo',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(57,'Isabela',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(58,'Kalinga',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(59,'Laguna',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(60,'Lanao del Norte',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(61,'Lanao del Sur',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(62,'Maguindanao del Norte',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(63,'Maguindanao del Sur',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(64,'Marinduque',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(65,'Misamis Occidental',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(66,'Misamis Oriental',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(67,'Mountain Province',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(68,'Negros Occidental',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(69,'Negros Oriental',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(70,'Northern Samar',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(71,'Nueva Ecija',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(72,'Nueva Vizcaya',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(73,'Occidental Mindoro',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(74,'Oriental Mindoro',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(75,'Pampanga',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(76,'Quezon',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(77,'Quirino',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(78,'Rizal',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(79,'Samar',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(80,'Sarangani',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(81,'Siquijor',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(82,'Sorsogon',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(83,'South Cotabato',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(84,'Southern Leyte',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(85,'Sultan Kudarat',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(86,'Sulu',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(87,'Surigao del Norte',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(88,'Surigao del Sur',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(89,'Tarlac',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(90,'Tawi-Tawi',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(91,'Zamboanga Sibugay',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(92,'Zamboanga del Norte',1,'2026-10-06 06:10:04','2026-10-06 06:10:04'),(93,'Zamboanga del Sur',1,'2026-10-06 06:10:04','2026-10-06 06:10:04');
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
) ENGINE=InnoDB AUTO_INCREMENT=232 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2025_10_01_211540_create_nav_menus_table',1),(6,'2025_10_02_163004_create_table_for_settings_role',1),(7,'2025_10_03_180527_add_parentmenu',1),(8,'2025_10_04_144632_create_mailer_settings_table',1),(9,'2025_11_06_035725_insert_order_in_nav_menus',1),(10,'2026_01_24_221131_create_sessions_table',1),(11,'2026_01_24_221645_add_session_id_to_users_table',1),(12,'2026_01_24_223037_create_cache_table',1),(13,'2026_01_26_110424_create_jobs_table',1),(14,'2026_01_26_110425_modify_columns_of_user_table',1),(15,'2026_05_01_011537_options_table',1),(16,'2026_05_01_011632_list_of_value_table',1),(17,'2026_05_05_052405_create_company_info_master_table',1),(18,'2026_05_05_052441_create_contact_info_table',1),(19,'2026_05_05_052453_create_trade_references_table',1),(20,'2026_05_05_052548_create_services_info_table',1),(21,'2026_05_05_052612_create_company_finance_table',1),(22,'2026_05_05_052631_create_billed_details_table',1),(23,'2026_05_05_052656_create_sales_info_table',1),(24,'2026_05_05_052712_create_stages_info_table',1),(25,'2026_05_07_205424_create_e_invoice_table',1),(26,'2026_05_07_205616_create_courier_invoice_table',1),(27,'2026_06_05_045259_create_crm_status_table',1),(28,'2026_06_05_045351_create_crm_leads_table',1),(29,'2026_06_05_045416_create_company_info_table',1),(30,'2026_06_05_045430_create_crm_notes_table',1),(31,'2026_06_05_045450_create_crm_activities_table',1),(32,'2026_06_15_230541_proposals',1),(33,'2026_06_15_231224_proposal_rates',1),(34,'2026_06_15_233415_create_table_for_routes',1),(35,'2026_06_15_233633_create_table_service_type',1),(36,'2026_06_15_233806_create_proposal_status',1),(37,'2026_06_15_233848_create_customer_type',1),(38,'2026_06_26_195636_create_container_type',1),(39,'2026_06_26_195710_create_container_class',1),(40,'2026_06_26_195725_create_container_size',1),(41,'2026_07_02_221007_add_new_columns_to_user_table',1),(42,'2026_07_02_222100_create_user_department',1),(43,'2026_07_02_222118_create_user_status',1),(44,'2026_07_04_000001_create_ports_table',1),(45,'2026_07_04_000002_create_serviceable_areas_table',1),(46,'2026_07_04_000003_create_delivery_types_table',1),(47,'2026_07_04_000004_create_charge_types_table',1),(48,'2026_07_04_000005_create_lanes_table',1),(49,'2026_07_04_000006_create_lane_tariff_rates_table',1),(50,'2026_07_04_000007_create_port_charges_table',1),(51,'2026_07_04_000008_create_handling_fees_table',1),(52,'2026_07_04_000009_create_trucking_tariffs_table',1),(53,'2026_07_04_000010_create_vat_rates_table',1),(54,'2026_07_04_000011_create_contracts_table',1),(55,'2026_07_04_000012_create_contract_rates_table',1),(56,'2026_07_04_000013_create_bookings_table',1),(57,'2026_07_04_000014_create_booking_port_charges_table',1),(58,'2026_07_07_000001_add_applicable_to_to_charge_types_table',1),(59,'2026_07_07_000002_create_general_charges_table',1),(60,'2026_07_07_124225_drop_bsc_ra_gri_from_lane_tariff_rates_table',1),(61,'2026_07_07_124800_add_rate_type_and_rate_value_to_proposals_rates_table',1),(62,'2026_07_08_175248_create_client_masters_table',1),(63,'2026_07_08_175319_create_client_contacts_table',1),(64,'2026_07_08_175429_create_client_trade_references_table',1),(65,'2026_07_08_175448_create_client_finance_table',1),(66,'2026_07_08_175528_create_client_billing_table',1),(67,'2026_07_08_221636_create_containers_table',1),(68,'2026_07_08_221704_create_container_variants_table',1),(69,'2026_07_08_221727_create_lane_tariff_rate_prices_table',1),(70,'2026_07_09_000939_drop_column_from_container_table',1),(71,'2026_07_09_001926_drop_column_from_lane_tariff_rates_table',1),(72,'2026_07_10_212311_create_client_proposals_table',1),(73,'2026_07_10_212810_create_client_proposal_rates_table',1),(74,'2026_07_10_212846_create_client_contracts_table',1),(75,'2026_07_10_212946_create_client_contract_rates_table',1),(76,'2026_07_11_152530_add_lead_id_to_client_masters_table',1),(77,'2026_07_13_182230_add_workflow_columns_to_client_proposals_table',1),(78,'2026_07_14_021740_add_progress_columns_to_crm_leads_table',1),(79,'2026_07_14_021824_add_address_fields_to_crm_company_info_table',1),(80,'2026_07_14_021949_create_crm_lead_containers_table',1),(81,'2026_07_14_030206_add_lookup_columns_to_crm_lead_containers_table',1),(82,'2026_07_14_044418_drop_company_address_from_crm_company_info',1),(83,'2026_07_22_003454_add_lead_id_to_client_proposals_table',1),(84,'2026_07_22_020031_add_attachment_to_crm_activities_table',1),(85,'2026_07_22_031438_add_client_type_and_contact_fields_to_crm_leads_table',1),(86,'2026_07_22_031439_create_crm_lead_addresses_table',1),(87,'2026_07_22_031439_migrate_crm_company_address_to_lead_addresses_and_drop_columns',1),(88,'2026_07_22_031440_add_industry_description_to_crm_company_info_table',1),(89,'2026_07_22_040302_remove_lookup_values_nav_menu_entry',1),(90,'2026_07_22_045002_add_customer_code_to_crm_leads_table',1),(91,'2026_07_22_045002_create_client_addresses_table',1),(92,'2026_07_22_045003_migrate_client_registered_address_and_drop_column',1),(93,'2026_07_22_045004_add_type_fields_to_client_contacts_table',1),(94,'2026_07_23_010845_create_app_theme_settings_table',1),(95,'2026_07_23_010846_add_theme_nav_menu_entry',1),(96,'2026_07_25_031024_create_notifications_table',1),(97,'2026_07_25_035333_create_teams_table',1),(98,'2026_07_25_035334_add_team_columns_to_users_table',1),(99,'2026_07_27_100000_add_coordinates_to_ports_table',1),(100,'2026_07_27_100100_create_container_assets_table',1),(101,'2026_07_27_100200_create_container_asset_location_history_table',1),(102,'2026_07_27_110000_add_is_system_to_setting_role_table',1),(103,'2026_07_27_110100_create_permissions_table',1),(104,'2026_07_27_110200_create_role_permission_table',1),(105,'2026_07_27_120000_add_booking_header_fields_to_bookings_table',1),(106,'2026_07_27_120100_create_booking_lines_table',1),(107,'2026_07_27_120200_create_booking_status_history_table',1),(108,'2026_07_27_120300_create_booking_container_units_table',1),(109,'2026_07_27_120400_create_booking_invoices_table',1),(110,'2026_07_27_120500_create_bill_of_ladings_table',1),(111,'2026_07_27_130000_add_termination_fields_to_client_contracts_table',1),(112,'2026_07_28_090000_create_nav_icons_table',1),(113,'2026_07_28_185238_move_route_delivery_to_booking_lines',1),(114,'2026_07_28_204127_add_min_van_qty_to_client_rate_tables',1),(115,'2026_07_29_003717_split_contact_name_on_crm_leads_table',1),(116,'2026_07_29_003752_rename_required_temperature_on_crm_lead_containers_table',1),(117,'2026_07_29_003808_add_authorized_signatory_fields_to_crm_company_info_table',1),(118,'2026_07_29_131824_add_transaction_details_to_booking_lines_table',1),(119,'2026_07_29_173304_add_always_route_atw_to_client_masters_table',1),(120,'2026_07_29_173305_add_cv_assignment_fields_to_booking_container_units_table',1),(121,'2026_07_29_173306_create_booking_dispatch_documents_table',1),(122,'2026_07_29_181324_add_gate_pass_fields_to_booking_container_units_table',1),(123,'2026_07_29_183320_create_booking_container_eir_records_table',1),(124,'2026_07_29_195201_create_vessel_voyages_table',1),(125,'2026_07_29_195202_add_voyage_fields_to_booking_container_units_table',1),(126,'2026_08_03_100001_add_client_mnemonic_and_account_manager_to_client_masters_table',1),(127,'2026_08_03_100002_restructure_client_finance_table',1),(128,'2026_08_03_100003_restructure_client_contacts_table',1),(129,'2026_08_03_100004_create_client_contact_addresses_table',1),(130,'2026_08_03_100005_create_client_ancillary_services_table',1),(131,'2026_08_03_100006_drop_client_billing_table',1),(132,'2026_08_04_100001_restructure_client_masters_company_info_fields',1),(133,'2026_08_04_120001_restructure_client_finance_cro_and_declared_value',1),(134,'2026_08_05_100001_create_special_charges_table',1),(135,'2026_08_05_100002_create_cargo_yards_table',1),(136,'2026_08_06_100001_simplify_client_ancillary_services_table',1),(137,'2026_08_06_120000_add_workflow_columns_to_client_contracts_table',1),(138,'2026_08_06_120100_add_tax_type_to_vat_rates_table',1),(139,'2026_08_06_120200_rename_tax_status_to_registered_tax_type_on_client_finance_table',1),(140,'2026_08_07_100000_add_must_change_password_to_users_table',1),(141,'2026_08_11_090000_create_locations_table',1),(142,'2026_08_11_090100_add_location_id_to_ports_table',1),(143,'2026_08_12_031524_add_profile_photo_path_to_users_table',1),(144,'2026_08_12_042434_create_app_information_settings_table',1),(145,'2026_08_12_042435_add_app_information_nav_menu_entry',1),(146,'2026_08_12_192648_scope_container_class_and_size_to_container',1),(147,'2026_08_13_033349_scope_serviceable_areas_to_location',1),(148,'2026_08_13_135908_make_container_variants_size_nullable',1),(149,'2026_08_19_120000_make_container_class_and_size_nullable_on_rate_tables',1),(150,'2026_08_22_164450_widen_discount_type_enum_for_client_proposal_and_contract_rates',1),(151,'2026_08_22_170000_add_hazardous_document_path_to_booking_lines_table',1),(152,'2026_08_22_180000_add_minimum_temperature_to_booking_lines_table',1),(153,'2026_08_23_000001_create_vessels_table',1),(154,'2026_08_23_000002_create_vessel_status_histories_table',1),(155,'2026_08_23_000003_create_vessel_maintenance_records_table',1),(156,'2026_08_23_000004_replace_vessel_name_with_vessel_id_on_vessel_voyages_table',1),(157,'2026_09_01_000000_add_nav_layout_to_users_table',1),(158,'2026_09_01_000001_add_cargo_type_to_crm_lead_containers_table',1),(159,'2026_09_04_090000_add_pier_handling_to_booking_lines_table',1),(160,'2026_09_06_020000_create_userconfig_table',1),(161,'2026_09_10_000000_add_requires_proposal_to_crm_leads_table',1),(162,'2026_09_10_000001_add_signature_requested_at_to_client_proposals_table',1),(163,'2026_09_10_000002_add_parent_lov_id_to_list_of_values_table',1),(164,'2026_09_10_000003_add_industry_subcategory_to_client_masters_table',1),(165,'2026_09_10_000004_create_client_proposal_rate_ancillary_services_table',1),(166,'2026_09_10_000005_add_pickup_areas_to_client_proposal_rates_table',1),(167,'2026_09_10_000006_add_additional_charges_to_client_proposals_table',1),(168,'2026_09_17_000000_rename_crm_leads_to_prospects',1),(169,'2026_09_17_000001_rename_crm_company_info_to_prospect_company_info',1),(170,'2026_09_17_000002_rename_crm_lead_addresses_to_prospect_locations',1),(171,'2026_09_17_000003_rename_crm_lead_containers_to_prospect_containers_legacy',1),(172,'2026_09_17_000004_rename_crm_notes_to_prospect_notes',1),(173,'2026_09_17_000005_rename_crm_activities_to_prospect_activities',1),(174,'2026_09_17_000006_rename_lead_id_to_prospect_id_on_client_masters_table',1),(175,'2026_09_17_000007_rename_lead_id_to_prospect_id_on_client_proposals_table',1),(176,'2026_09_17_000008_rename_lead_id_to_prospect_id_on_proposals_table',1),(177,'2026_09_17_000009_rename_lead_id_to_prospect_id_on_contracts_table',1),(178,'2026_09_17_000010_update_crm_status_lead_to_prospect',1),(179,'2026_09_17_000011_add_relationship_manager_id_to_prospects_table',1),(180,'2026_09_17_000012_create_prospect_contacts_table',1),(181,'2026_09_17_000013_create_prospect_contact_channels_table',1),(182,'2026_09_17_000014_create_prospect_contact_addresses_table',1),(183,'2026_09_17_000015_create_proposal_requests_table',1),(184,'2026_09_17_000016_create_proposal_request_containers_table',1),(186,'2026_09_17_000017_simplify_proposal_request_containers_table',2),(187,'2026_09_17_000018_restructure_proposal_request_containers_for_product_fields',3),(188,'2026_09_17_000019_create_proposal_request_truckings_table',3),(189,'2026_09_17_000020_create_proposal_request_charters_table',3),(190,'2026_09_17_000021_create_proposal_request_charter_cargo_table',4),(191,'2026_09_17_000022_create_proposal_request_charter_ports_table',4),(192,'2026_09_17_000023_add_submitted_to_proposal_requests_table',5),(193,'2026_09_17_000024_create_proposal_request_company_details_table',5),(194,'2026_09_17_000025_create_proposal_request_signatories_table',5),(195,'2026_09_17_000026_create_proposal_request_locations_table',5),(196,'2026_09_18_000001_create_proposal_request_product_containers_table',6),(197,'2026_09_18_000002_create_proposal_request_product_rolling_cargo_table',6),(198,'2026_09_18_000003_create_proposal_request_product_loose_cargo_table',6),(199,'2026_09_18_000004_create_proposal_request_product_truckings_table',6),(200,'2026_09_18_000005_create_proposal_request_product_charters_table',6),(201,'2026_09_18_000006_create_proposal_request_product_charter_cargo_table',6),(202,'2026_09_18_000007_create_proposal_request_product_charter_ports_table',6),(203,'2026_09_18_000008_create_proposal_request_product_topload_cargo_table',6),(204,'2026_09_18_000009_create_proposal_request_product_ancillary_services_table',6),(205,'2026_09_19_000001_add_dispatch_mode_to_proposal_request_product_containers_table',7),(206,'2026_09_19_000002_allow_multiple_proposal_requests_per_prospect',8),(207,'2026_09_20_000001_decouple_prospect_requirements_from_proposal_requests',9),(208,'2026_09_25_000001_add_snapshot_fields_to_proposal_request_locations_table',10),(209,'2026_09_25_000002_add_category_to_nav_menus_table',11),(210,'2026_09_27_000001_add_service_type_to_proposal_request_container_tables',12),(211,'2026_09_27_000002_add_quantity_to_proposal_request_product_containers_and_truckings_tables',12),(212,'2026_09_29_000001_add_font_size_to_users_table',13),(213,'2026_10_02_000001_add_is_individual_to_list_of_values_table',14),(214,'2026_10_03_000001_add_rm_approval_columns_to_client_proposals_table',14),(215,'2026_10_03_000002_add_proposal_request_id_to_client_proposals_table',15),(216,'2026_10_03_000003_backfill_proposal_request_statuses',15),(217,'2026_10_05_000001_create_client_proposal_signed_rates_table',16),(218,'2026_10_06_000001_create_proposal_request_product_rates_table',17),(219,'2026_10_06_000002_create_proposal_request_ancillary_fees_table',17),(220,'2026_10_06_000003_add_terms_of_payment_to_proposal_requests_table',17),(221,'2026_10_12_230934_add_revenue_ton_unit_to_proposal_request_containers',18),(222,'2026_10_14_131007_add_snapshot_fields_to_proposal_request_signatories_table',19),(223,'2026_10_14_131029_create_proposal_request_terms_of_payment_table',20),(224,'2026_10_16_120000_add_pricing_fields_to_proposal_request_product_ancillary_services_table',21),(225,'2026_10_16_130000_add_created_by_to_prospects_table',22),(226,'2026_10_20_000001_create_charter_services_table',23),(227,'2026_10_20_000002_add_charter_service_id_to_charter_tables',23),(228,'2026_10_20_000003_create_special_charge_rates_table',24),(229,'2026_10_20_000004_add_owner_id_to_prospects_table',25),(230,'2026_10_22_184432_rework_trucking_tariffs_to_area_port_key',26),(231,'2026_10_22_000001_add_additional_notes_to_proposal_requests_table',27);
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
INSERT INTO `nav_icons` VALUES (1,'home','Home','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(2,'users','Users','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-4.5 0 2.625 2.625 0 014.5 0z\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(3,'user','User','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(4,'bell','Bell','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(5,'magnifying-glass','Search','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"m21 21-5.2-5.2m0 0A7.5 7.5 0 1 0 5.3 5.3a7.5 7.5 0 0 0 10.5 10.5Z\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(6,'x-mark','Close (X)','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M6 18L18 6M6 6l12 12\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(7,'check-circle','Check Circle','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(8,'document-text','Document','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(9,'sparkles','Sparkles','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.456-2.456L14.25 6l1.035-.259a3.375 3.375 0 002.456-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(10,'cube','Box / Cube','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(11,'envelope','Envelope','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(12,'bars-3','Menu (3 lines)','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(13,'cog-6-tooth','Settings (gear)','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.28zM15 12a3 3 0 11-6 0 3 3 0 016 0z\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(14,'chevron-down','Chevron Down','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M19.5 8.25l-7.5 7.5-7.5-7.5\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(15,'chevron-up','Chevron Up','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M4.5 15.75l7.5-7.5 7.5 7.5\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(16,'chevron-left','Chevron Left','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M15.75 19.5L8.25 12l7.5-7.5\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(17,'chevron-right','Chevron Right','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M8.25 4.5l7.5 7.5-7.5 7.5\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(18,'plus','Plus','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M12 4.5v15m7.5-7.5h-15\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(19,'minus','Minus','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M5 12h14\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(20,'arrow-path','Refresh','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(21,'arrow-up-tray','Upload','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(22,'arrow-down-tray','Download','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(23,'trash','Trash','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(24,'pencil','Edit (pencil)','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(25,'eye','Eye','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .638C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178zM15 12a3 3 0 11-6 0 3 3 0 016 0z\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(26,'eye-slash','Eye Slash','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M3.98 8.223A10.477 10.477 0 001.934 12c1.832 4.068 5.728 7 10.066 7 1.676 0 3.285-.37 4.712-1.034M6.228 6.228A10.45 10.45 0 0112 5c4.38 0 8.293 2.953 10.07 7.063a10.522 10.522 0 01-4.517 4.92M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.878 9.878\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(27,'clock','Clock','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(28,'calendar','Calendar','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(29,'truck','Truck','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(30,'archive-box','Archive Box','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(31,'banknotes','Finance (banknotes)','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(32,'building-office','Building / Office','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(33,'globe-alt','Globe','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(34,'shield-check','Shield Check','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(35,'exclamation-triangle','Warning Triangle','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(36,'information-circle','Info Circle','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(37,'flag','Flag','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M3 3v1.5M3 21v-6m0 0l2.77-.693a9 9 0 016.208.682l.108.054a9 9 0 006.086.71l3.114-.732a48.524 48.524 0 01-.005-10.499l-3.11.732a9 9 0 01-6.085-.711l-.108-.054a9 9 0 00-6.208-.682L3 4.5M3 15V4.5\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(38,'star','Star','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.562.562 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(39,'heart','Heart','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(40,'tag','Tag','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3zM6 6h.008v.008H6V6z\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(41,'folder-open','Folder','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 015.25 3.75h4.5c.55 0 1.02.398 1.11.94l.213 1.28c.089.542.559.94 1.11.94h4.567a2.25 2.25 0 012.25 2.25v.616\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(42,'briefcase','Briefcase','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18a48.424 48.424 0 01-6.378.42c-2.162 0-4.291-.143-6.378-.42-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(43,'map-pin','Map Pin','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M15 10.5a3 3 0 11-6 0 3 3 0 016 0z\" /><path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(44,'link','Link','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(45,'paper-airplane','Send (paper airplane)','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(46,'printer','Printer','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(47,'square','Square','<rect x=\"3.75\" y=\"3.75\" width=\"16.5\" height=\"16.5\" rx=\"2\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(48,'circle','Circle','<circle cx=\"12\" cy=\"12\" r=\"8.25\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(49,'squares-2x2','Grid','<rect x=\"3.75\" y=\"3.75\" width=\"7.5\" height=\"7.5\" rx=\"1.25\" /><rect x=\"12.75\" y=\"3.75\" width=\"7.5\" height=\"7.5\" rx=\"1.25\" /><rect x=\"3.75\" y=\"12.75\" width=\"7.5\" height=\"7.5\" rx=\"1.25\" /><rect x=\"12.75\" y=\"12.75\" width=\"7.5\" height=\"7.5\" rx=\"1.25\" />','2026-09-17 00:59:26','2026-09-17 00:59:26'),(50,'list-bullet','List','<circle cx=\"4.5\" cy=\"6\" r=\"1\" fill=\"currentColor\" stroke=\"none\" /><circle cx=\"4.5\" cy=\"12\" r=\"1\" fill=\"currentColor\" stroke=\"none\" /><circle cx=\"4.5\" cy=\"18\" r=\"1\" fill=\"currentColor\" stroke=\"none\" /><line x1=\"8.25\" y1=\"6\" x2=\"20.25\" y2=\"6\" stroke-linecap=\"round\" /><line x1=\"8.25\" y1=\"12\" x2=\"20.25\" y2=\"12\" stroke-linecap=\"round\" /><line x1=\"8.25\" y1=\"18\" x2=\"20.25\" y2=\"18\" stroke-linecap=\"round\" />','2026-09-17 00:59:26','2026-09-17 00:59:26');
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
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `allowed_roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`allowed_roles`)),
  `parent_menu` int(11) NOT NULL DEFAULT 0,
  `menu_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nav_menus`
--

LOCK TABLES `nav_menus` WRITE;
/*!40000 ALTER TABLE `nav_menus` DISABLE KEYS */;
INSERT INTO `nav_menus` VALUES (1,'Theme',NULL,'squares-2x2','/page_theme','[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]',17,3,'2026-09-17 00:59:05','2026-10-19 18:28:36'),(2,'Application Information',NULL,'information-circle','/page_app_information','[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]',17,5,'2026-09-17 00:59:09','2026-10-19 18:28:36'),(3,'Dashboard',NULL,'home','/page_dashboard','[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]',0,0,'2026-09-17 00:59:26','2026-10-19 18:28:36'),(4,'CRM','Leads','flag','/page_crm','[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]',0,1,'2026-09-17 00:59:26','2026-10-19 18:28:36'),(5,'Proposals','Leads','paper-airplane','#','[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]',0,2,'2026-09-17 00:59:26','2026-10-19 18:28:36'),(6,'Clients','Sales','building-office','page_clientMasters','[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]',0,3,'2026-09-17 00:59:26','2026-10-19 18:28:36'),(7,'Booking','Operations','squares-2x2','page_booking','[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]',0,4,'2026-09-17 00:59:26','2026-10-19 18:28:36'),(8,'Contracts','Operations','document-text','/page_contracts','[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]',0,5,'2026-09-17 00:59:26','2026-10-19 18:28:36'),(9,'Bookings','Operations','list-bullet','/page_booking','[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]',0,6,'2026-09-17 00:59:26','2026-10-19 18:28:36'),(10,'Cargo Build-Up','Operations','archive-box','/page_cargo_build_up','[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]',0,7,'2026-09-17 00:59:26','2026-10-19 18:28:36'),(11,'Pier Check-In','Operations','check-circle','/page_pier_checkin','[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]',0,9,'2026-09-17 00:59:26','2026-10-19 18:28:36'),(12,'Container Assignment','Operations','cube','/page_container_assignment','[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]',0,8,'2026-09-17 00:59:26','2026-10-19 18:28:36'),(13,'Vessel Management','Operations','truck','/page_vessel_management','[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]',0,12,'2026-09-17 00:59:26','2026-10-19 18:28:36'),(14,'Voyage Schedule','Operations','map-pin','/page_voyage_schedule','[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]',0,13,'2026-09-17 00:59:26','2026-10-19 18:28:36'),(15,'Users','Application','users','/page_users','[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]',0,10,'2026-09-17 00:59:26','2026-10-19 18:28:36'),(16,'Settings','Application','cog-6-tooth','#','[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]',0,11,'2026-09-17 00:59:26','2026-10-19 18:28:36'),(17,'Developer Option','Developers','shield-check','#','[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]',0,14,'2026-09-17 00:59:26','2026-10-19 18:28:36'),(18,'App Settings',NULL,'cog-6-tooth','/page_maintenance','[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]',16,1,'2026-09-17 00:59:26','2026-10-19 18:28:36'),(20,'Container Inventory',NULL,'cube','/page_container_inventory','[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]',16,8,'2026-09-17 00:59:26','2026-10-19 18:28:36'),(21,'Mailer',NULL,'envelope','/page_mailer','[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]',17,1,'2026-09-17 00:59:26','2026-10-19 18:28:36'),(22,'Menus',NULL,'bars-3','/page_menus','[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]',17,2,'2026-09-17 00:59:26','2026-10-19 18:28:36'),(23,'Notification Test',NULL,'bell','/page_notification_test','[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]',17,4,'2026-09-17 00:59:26','2026-10-19 18:28:36'),(24,'Requests',NULL,'paper-airplane','/page_proposal_requests','[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]',5,1,'2026-09-30 15:34:54','2026-10-21 17:12:22'),(27,'My Requests',NULL,'paper-airplane','/page_proposal_requests_mine','[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]',5,4,'2026-10-05 04:09:51','2026-10-19 18:28:36');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `options_table`
--

LOCK TABLES `options_table` WRITE;
/*!40000 ALTER TABLE `options_table` DISABLE KEYS */;
INSERT INTO `options_table` VALUES (1,'Address Type',NULL,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(2,'Lead Source',NULL,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(3,'Title',NULL,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(4,'Cargo Type',NULL,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(5,'Type of Business',NULL,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(6,'Industry',NULL,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(7,'Industry Sub-Category',NULL,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(8,'Type of Organization',NULL,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(9,'Client Category',NULL,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(10,'Client Classification',NULL,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(11,'Contact Department',NULL,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(12,'Unit',NULL,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(13,'Trucking Cargo Type',NULL,'2026-09-17 14:59:02','2026-09-17 14:59:02'),(14,'Ancillary Type',NULL,'2026-09-18 16:00:45','2026-09-18 16:00:45');
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'roles.manage','Manage roles & permissions','Roles','2026-09-17 00:59:27','2026-09-17 00:59:27'),(2,'booking.create','Create a booking','Booking','2026-09-17 00:59:27','2026-09-17 00:59:27'),(3,'booking.confirm','Confirm a booking','Booking','2026-09-17 00:59:27','2026-09-17 00:59:27'),(4,'booking.cancel','Cancel a booking','Booking','2026-09-17 00:59:27','2026-09-17 00:59:27'),(5,'booking.advance-status','Advance a booking\'s status','Booking','2026-09-17 00:59:27','2026-09-17 00:59:27'),(6,'booking.generate-dispatch-document','Generate a booking line\'s ATW/CAN','Booking','2026-09-17 00:59:27','2026-09-17 00:59:27'),(7,'booking.assign-cv','Assign ConVan/Proforma BL/Waybill/Seal to container unit','Booking','2026-09-17 00:59:27','2026-09-17 00:59:27'),(8,'booking.gate-scan','Scan a container in/out at gate (Pier Check-In)','Booking','2026-09-17 00:59:27','2026-09-17 00:59:27'),(9,'booking.issue-eir','Issue a container\'s EIR Out/In','Booking','2026-09-17 00:59:27','2026-09-17 00:59:27'),(10,'booking.assign-voyage','Assign or shut out container\'s vessel voyage','Booking','2026-09-17 00:59:27','2026-09-17 00:59:27'),(11,'booking.generate-loadlist','Generate a vessel voyage\'s loadlist','Booking','2026-09-17 00:59:27','2026-09-17 00:59:27'),(12,'contract.create','Create a contract from an accepted proposal','Contract','2026-09-17 00:59:27','2026-09-17 00:59:27'),(13,'contract.terminate','Terminate a contract','Contract','2026-09-17 00:59:27','2026-09-17 00:59:27'),(14,'proposal.approve.rm','Give the first (Relationship Manager) approval on a proposal','Proposal','2026-10-03 06:12:00','2026-10-03 06:12:00'),(15,'proposal.approve.manager','Give the final (Manager) approval/disapproval/rejection on a proposal','Proposal','2026-10-03 06:12:00','2026-10-03 06:12:00');
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
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ports`
--

LOCK TABLES `ports` WRITE;
/*!40000 ALTER TABLE `ports` DISABLE KEYS */;
INSERT INTO `ports` VALUES (1,1,'Caticlan Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(2,1,'Dumaguit Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(3,2,'Banogo Power Plant Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(4,3,'Mariveles Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(5,3,'Orion Dockyard',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(6,4,'BIPI',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(7,4,'Frabelle, Bauan',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(8,4,'JGSP',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(9,4,'Kepco Ilijan Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(10,4,'San Juan Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(11,4,'Sem Calaca Power Plant',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(12,4,'Tabangao Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(13,5,'Legaspi Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(14,5,'Tabaco Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(15,6,'Port of Masao',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(16,7,'BBASI Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(17,7,'Gracia Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(18,7,'Oro Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(19,8,'KTC Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(20,9,'Sasa Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(21,9,'Tibungco Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(22,10,'Dinagat Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(23,11,'Poro, San Fernando',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(24,11,'Sto. Tomas Fish Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(25,12,'Liloan Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(26,12,'San Ricardo Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(27,13,'Harbour Center',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(28,13,'Navotas Fish Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(29,13,'North Harbour',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(30,13,'Vitas Pier 18',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(31,14,'Algimar Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(32,15,'Coron Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(33,15,'Port of Brooke\'s Point',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(34,15,'Puerto Princesa Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(35,15,'San Vicente Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(36,15,'Taytay Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(37,15,'Villa Marcelo, Puerto Princesa',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(38,16,'Sual Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(39,17,'Romblon Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(40,18,'Culasi Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(41,19,'Nonoc Island Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(42,19,'Surigao Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(43,20,'Tacloban Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(44,21,'Rivera Port, Subic',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(45,21,'Alpha Water Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(46,21,'Masinloc Power Partners Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(47,22,'Lamao Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(48,22,'Liloy Port',NULL,NULL,1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(49,23,'ABRA PORT',NULL,NULL,1,'2026-10-22 16:19:06','2026-10-22 16:19:06');
/*!40000 ALTER TABLE `ports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proposal_request_ancillary_fees`
--

DROP TABLE IF EXISTS `proposal_request_ancillary_fees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposal_request_ancillary_fees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_request_id` bigint(20) unsigned NOT NULL,
  `section` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fee_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pr_ancillary_fees_request_section_service_unique` (`proposal_request_id`,`section`,`service_name`),
  CONSTRAINT `proposal_request_ancillary_fees_proposal_request_id_foreign` FOREIGN KEY (`proposal_request_id`) REFERENCES `proposal_requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposal_request_ancillary_fees`
--

LOCK TABLES `proposal_request_ancillary_fees` WRITE;
/*!40000 ALTER TABLE `proposal_request_ancillary_fees` DISABLE KEYS */;
/*!40000 ALTER TABLE `proposal_request_ancillary_fees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proposal_request_charter_cargo`
--

DROP TABLE IF EXISTS `proposal_request_charter_cargo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposal_request_charter_cargo` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_request_charter_id` bigint(20) unsigned NOT NULL,
  `cargo_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `general_cargo_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `special_requirements` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prc_cargo_charter_fk` (`proposal_request_charter_id`),
  CONSTRAINT `prc_cargo_charter_fk` FOREIGN KEY (`proposal_request_charter_id`) REFERENCES `proposal_request_charters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposal_request_charter_cargo`
--

LOCK TABLES `proposal_request_charter_cargo` WRITE;
/*!40000 ALTER TABLE `proposal_request_charter_cargo` DISABLE KEYS */;
/*!40000 ALTER TABLE `proposal_request_charter_cargo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proposal_request_charter_ports`
--

DROP TABLE IF EXISTS `proposal_request_charter_ports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposal_request_charter_ports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_request_charter_id` bigint(20) unsigned NOT NULL,
  `port_id` bigint(20) unsigned DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prc_port_charter_fk` (`proposal_request_charter_id`),
  KEY `prc_port_port_fk` (`port_id`),
  CONSTRAINT `prc_port_charter_fk` FOREIGN KEY (`proposal_request_charter_id`) REFERENCES `proposal_request_charters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `prc_port_port_fk` FOREIGN KEY (`port_id`) REFERENCES `ports` (`port_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposal_request_charter_ports`
--

LOCK TABLES `proposal_request_charter_ports` WRITE;
/*!40000 ALTER TABLE `proposal_request_charter_ports` DISABLE KEYS */;
/*!40000 ALTER TABLE `proposal_request_charter_ports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proposal_request_charters`
--

DROP TABLE IF EXISTS `proposal_request_charters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposal_request_charters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `charter_service_id` bigint(20) unsigned DEFAULT NULL,
  `prospect_id` bigint(20) unsigned DEFAULT NULL,
  `charter_start_date` date DEFAULT NULL,
  `charter_end_date` date DEFAULT NULL,
  `declared_value` decimal(15,2) DEFAULT NULL,
  `weight` decimal(12,2) DEFAULT NULL,
  `weight_unit` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `proposal_request_charters_prospect_id_foreign` (`prospect_id`),
  KEY `proposal_request_charters_charter_service_id_foreign` (`charter_service_id`),
  CONSTRAINT `proposal_request_charters_charter_service_id_foreign` FOREIGN KEY (`charter_service_id`) REFERENCES `charter_services` (`charter_service_id`) ON DELETE SET NULL,
  CONSTRAINT `proposal_request_charters_prospect_id_foreign` FOREIGN KEY (`prospect_id`) REFERENCES `prospects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposal_request_charters`
--

LOCK TABLES `proposal_request_charters` WRITE;
/*!40000 ALTER TABLE `proposal_request_charters` DISABLE KEYS */;
/*!40000 ALTER TABLE `proposal_request_charters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proposal_request_company_details`
--

DROP TABLE IF EXISTS `proposal_request_company_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposal_request_company_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_request_id` bigint(20) unsigned NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prospect_location_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `proposal_request_company_details_proposal_request_id_unique` (`proposal_request_id`),
  KEY `proposal_request_company_details_prospect_location_id_foreign` (`prospect_location_id`),
  CONSTRAINT `proposal_request_company_details_proposal_request_id_foreign` FOREIGN KEY (`proposal_request_id`) REFERENCES `proposal_requests` (`id`) ON DELETE CASCADE,
  CONSTRAINT `proposal_request_company_details_prospect_location_id_foreign` FOREIGN KEY (`prospect_location_id`) REFERENCES `prospect_locations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposal_request_company_details`
--

LOCK TABLES `proposal_request_company_details` WRITE;
/*!40000 ALTER TABLE `proposal_request_company_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `proposal_request_company_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proposal_request_containers`
--

DROP TABLE IF EXISTS `proposal_request_containers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposal_request_containers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `prospect_id` bigint(20) unsigned DEFAULT NULL,
  `container_type` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `container_size_id` bigint(20) unsigned DEFAULT NULL,
  `delivery_type_id` bigint(20) unsigned DEFAULT NULL,
  `service_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `origin_location_id` bigint(20) unsigned DEFAULT NULL,
  `destination_location_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `minimum_temperature` decimal(5,2) DEFAULT NULL,
  `revenue_ton` decimal(12,2) DEFAULT NULL,
  `revenue_ton_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo_measurement` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `general_cargo_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `special_requirements` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `declared_value_per_unit` decimal(15,2) DEFAULT NULL,
  `weight` decimal(12,2) DEFAULT NULL,
  `weight_unit` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `frequency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_unit_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `proposal_request_containers_container_size_id_foreign` (`container_size_id`),
  KEY `proposal_request_containers_delivery_type_id_foreign` (`delivery_type_id`),
  KEY `proposal_request_containers_origin_location_id_foreign` (`origin_location_id`),
  KEY `proposal_request_containers_destination_location_id_foreign` (`destination_location_id`),
  KEY `proposal_request_containers_prospect_id_foreign` (`prospect_id`),
  CONSTRAINT `proposal_request_containers_container_size_id_foreign` FOREIGN KEY (`container_size_id`) REFERENCES `container_size` (`id`) ON DELETE SET NULL,
  CONSTRAINT `proposal_request_containers_delivery_type_id_foreign` FOREIGN KEY (`delivery_type_id`) REFERENCES `delivery_types` (`delivery_type_id`) ON DELETE SET NULL,
  CONSTRAINT `proposal_request_containers_destination_location_id_foreign` FOREIGN KEY (`destination_location_id`) REFERENCES `locations` (`location_id`) ON DELETE SET NULL,
  CONSTRAINT `proposal_request_containers_origin_location_id_foreign` FOREIGN KEY (`origin_location_id`) REFERENCES `locations` (`location_id`) ON DELETE SET NULL,
  CONSTRAINT `proposal_request_containers_prospect_id_foreign` FOREIGN KEY (`prospect_id`) REFERENCES `prospects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposal_request_containers`
--

LOCK TABLES `proposal_request_containers` WRITE;
/*!40000 ALTER TABLE `proposal_request_containers` DISABLE KEYS */;
/*!40000 ALTER TABLE `proposal_request_containers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proposal_request_locations`
--

DROP TABLE IF EXISTS `proposal_request_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposal_request_locations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_request_id` bigint(20) unsigned NOT NULL,
  `prospect_location_id` bigint(20) unsigned DEFAULT NULL,
  `address_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_building` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_street` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_barangay` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_town_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_province` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Philippines',
  `address_postal_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `proposal_request_locations_proposal_request_id_index` (`proposal_request_id`),
  KEY `proposal_request_locations_prospect_location_id_foreign` (`prospect_location_id`),
  CONSTRAINT `proposal_request_locations_proposal_request_id_foreign` FOREIGN KEY (`proposal_request_id`) REFERENCES `proposal_requests` (`id`) ON DELETE CASCADE,
  CONSTRAINT `proposal_request_locations_prospect_location_id_foreign` FOREIGN KEY (`prospect_location_id`) REFERENCES `prospect_locations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposal_request_locations`
--

LOCK TABLES `proposal_request_locations` WRITE;
/*!40000 ALTER TABLE `proposal_request_locations` DISABLE KEYS */;
/*!40000 ALTER TABLE `proposal_request_locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proposal_request_product_ancillary_services`
--

DROP TABLE IF EXISTS `proposal_request_product_ancillary_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposal_request_product_ancillary_services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ancillaryable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ancillaryable_id` bigint(20) unsigned NOT NULL,
  `ancillary_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `special_charge_id` bigint(20) unsigned DEFAULT NULL,
  `base_value` decimal(12,2) DEFAULT NULL,
  `quantity` decimal(12,2) DEFAULT 1.00,
  `adjustment` decimal(12,2) DEFAULT 0.00,
  `total_value` decimal(12,2) DEFAULT NULL,
  `cargo_yard_id` bigint(20) unsigned DEFAULT NULL,
  `ancillary_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ancillary_remarks` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prpanc_ancillaryable_idx` (`ancillaryable_type`,`ancillaryable_id`),
  KEY `prpanc_yard_fk` (`cargo_yard_id`),
  KEY `prpanc_charge_fk` (`special_charge_id`),
  CONSTRAINT `prpanc_charge_fk` FOREIGN KEY (`special_charge_id`) REFERENCES `special_charges` (`special_charge_id`) ON DELETE SET NULL,
  CONSTRAINT `prpanc_yard_fk` FOREIGN KEY (`cargo_yard_id`) REFERENCES `cargo_yards` (`cargo_yard_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposal_request_product_ancillary_services`
--

LOCK TABLES `proposal_request_product_ancillary_services` WRITE;
/*!40000 ALTER TABLE `proposal_request_product_ancillary_services` DISABLE KEYS */;
/*!40000 ALTER TABLE `proposal_request_product_ancillary_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proposal_request_product_charter_cargo`
--

DROP TABLE IF EXISTS `proposal_request_product_charter_cargo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposal_request_product_charter_cargo` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_request_product_charter_id` bigint(20) unsigned NOT NULL,
  `cargo_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `special_requirements` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prpc_cargo_charter_fk` (`proposal_request_product_charter_id`),
  CONSTRAINT `prpc_cargo_charter_fk` FOREIGN KEY (`proposal_request_product_charter_id`) REFERENCES `proposal_request_product_charters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposal_request_product_charter_cargo`
--

LOCK TABLES `proposal_request_product_charter_cargo` WRITE;
/*!40000 ALTER TABLE `proposal_request_product_charter_cargo` DISABLE KEYS */;
/*!40000 ALTER TABLE `proposal_request_product_charter_cargo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proposal_request_product_charter_ports`
--

DROP TABLE IF EXISTS `proposal_request_product_charter_ports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposal_request_product_charter_ports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_request_product_charter_id` bigint(20) unsigned NOT NULL,
  `port_id` bigint(20) unsigned DEFAULT NULL,
  `port_charge_account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `port_charge_amount` decimal(15,2) DEFAULT NULL,
  `cargoes_for_loading` int(11) DEFAULT NULL,
  `cargo_measurement_for_loading` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revenue_ton_unit_loading` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargoes_for_unloading` int(11) DEFAULT NULL,
  `cargo_measurement_for_unloading` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revenue_ton_unit_unloading` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prpc_port_charter_fk` (`proposal_request_product_charter_id`),
  KEY `prpc_port_port_fk` (`port_id`),
  CONSTRAINT `prpc_port_charter_fk` FOREIGN KEY (`proposal_request_product_charter_id`) REFERENCES `proposal_request_product_charters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `prpc_port_port_fk` FOREIGN KEY (`port_id`) REFERENCES `ports` (`port_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposal_request_product_charter_ports`
--

LOCK TABLES `proposal_request_product_charter_ports` WRITE;
/*!40000 ALTER TABLE `proposal_request_product_charter_ports` DISABLE KEYS */;
/*!40000 ALTER TABLE `proposal_request_product_charter_ports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proposal_request_product_charters`
--

DROP TABLE IF EXISTS `proposal_request_product_charters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposal_request_product_charters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `charter_service_id` bigint(20) unsigned DEFAULT NULL,
  `proposal_request_id` bigint(20) unsigned NOT NULL,
  `vessel_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vessel_dead_weight` decimal(12,2) DEFAULT NULL,
  `charter_start_date` date DEFAULT NULL,
  `charter_end_date` date DEFAULT NULL,
  `loading_date` date DEFAULT NULL,
  `laytime_loading_days` int(11) DEFAULT NULL,
  `laytime_unloading_days` int(11) DEFAULT NULL,
  `demurrage_charges` tinyint(1) NOT NULL DEFAULT 0,
  `lashing_service` tinyint(1) NOT NULL DEFAULT 0,
  `insurance_services` tinyint(1) NOT NULL DEFAULT 0,
  `other_charges` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `terms_of_payment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo_manifest_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `declared_value` decimal(15,2) DEFAULT NULL,
  `weight` decimal(12,2) DEFAULT NULL,
  `weight_unit` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prpchr_request_fk` (`proposal_request_id`),
  KEY `proposal_request_product_charters_charter_service_id_foreign` (`charter_service_id`),
  CONSTRAINT `proposal_request_product_charters_charter_service_id_foreign` FOREIGN KEY (`charter_service_id`) REFERENCES `charter_services` (`charter_service_id`) ON DELETE SET NULL,
  CONSTRAINT `prpchr_request_fk` FOREIGN KEY (`proposal_request_id`) REFERENCES `proposal_requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposal_request_product_charters`
--

LOCK TABLES `proposal_request_product_charters` WRITE;
/*!40000 ALTER TABLE `proposal_request_product_charters` DISABLE KEYS */;
/*!40000 ALTER TABLE `proposal_request_product_charters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proposal_request_product_containers`
--

DROP TABLE IF EXISTS `proposal_request_product_containers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposal_request_product_containers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_request_id` bigint(20) unsigned NOT NULL,
  `container_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int(10) unsigned DEFAULT NULL,
  `container_size_id` bigint(20) unsigned DEFAULT NULL,
  `minimum_temperature` decimal(6,2) DEFAULT NULL,
  `delivery_type_id` bigint(20) unsigned DEFAULT NULL,
  `service_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `origin_prospect_location_id` bigint(20) unsigned DEFAULT NULL,
  `destination_prospect_location_id` bigint(20) unsigned DEFAULT NULL,
  `origin_port_id` bigint(20) unsigned DEFAULT NULL,
  `destination_port_id` bigint(20) unsigned DEFAULT NULL,
  `dispatch_mode_origin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dispatch_mode_destination` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `terms_of_payment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prpcon_request_fk` (`proposal_request_id`),
  KEY `prpcon_size_fk` (`container_size_id`),
  KEY `prpcon_delivery_fk` (`delivery_type_id`),
  KEY `prpcon_origin_loc_fk` (`origin_prospect_location_id`),
  KEY `prpcon_dest_loc_fk` (`destination_prospect_location_id`),
  KEY `prpcon_origin_port_fk` (`origin_port_id`),
  KEY `prpcon_dest_port_fk` (`destination_port_id`),
  CONSTRAINT `prpcon_delivery_fk` FOREIGN KEY (`delivery_type_id`) REFERENCES `delivery_types` (`delivery_type_id`) ON DELETE SET NULL,
  CONSTRAINT `prpcon_dest_loc_fk` FOREIGN KEY (`destination_prospect_location_id`) REFERENCES `prospect_locations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `prpcon_dest_port_fk` FOREIGN KEY (`destination_port_id`) REFERENCES `ports` (`port_id`) ON DELETE SET NULL,
  CONSTRAINT `prpcon_origin_loc_fk` FOREIGN KEY (`origin_prospect_location_id`) REFERENCES `prospect_locations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `prpcon_origin_port_fk` FOREIGN KEY (`origin_port_id`) REFERENCES `ports` (`port_id`) ON DELETE SET NULL,
  CONSTRAINT `prpcon_request_fk` FOREIGN KEY (`proposal_request_id`) REFERENCES `proposal_requests` (`id`) ON DELETE CASCADE,
  CONSTRAINT `prpcon_size_fk` FOREIGN KEY (`container_size_id`) REFERENCES `container_size` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposal_request_product_containers`
--

LOCK TABLES `proposal_request_product_containers` WRITE;
/*!40000 ALTER TABLE `proposal_request_product_containers` DISABLE KEYS */;
/*!40000 ALTER TABLE `proposal_request_product_containers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proposal_request_product_loose_cargo`
--

DROP TABLE IF EXISTS `proposal_request_product_loose_cargo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposal_request_product_loose_cargo` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_request_id` bigint(20) unsigned NOT NULL,
  `cargo_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo_details` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo_quantity` int(11) DEFAULT NULL,
  `cargo_units` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revenue_ton` decimal(12,2) DEFAULT NULL,
  `revenue_ton_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo_measurement` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_type_id` bigint(20) unsigned DEFAULT NULL,
  `service_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `origin_prospect_location_id` bigint(20) unsigned DEFAULT NULL,
  `destination_prospect_location_id` bigint(20) unsigned DEFAULT NULL,
  `origin_port_id` bigint(20) unsigned DEFAULT NULL,
  `destination_port_id` bigint(20) unsigned DEFAULT NULL,
  `terms_of_payment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prploo_request_fk` (`proposal_request_id`),
  KEY `prploo_delivery_fk` (`delivery_type_id`),
  KEY `prploo_origin_loc_fk` (`origin_prospect_location_id`),
  KEY `prploo_dest_loc_fk` (`destination_prospect_location_id`),
  KEY `prploo_origin_port_fk` (`origin_port_id`),
  KEY `prploo_dest_port_fk` (`destination_port_id`),
  CONSTRAINT `prploo_delivery_fk` FOREIGN KEY (`delivery_type_id`) REFERENCES `delivery_types` (`delivery_type_id`) ON DELETE SET NULL,
  CONSTRAINT `prploo_dest_loc_fk` FOREIGN KEY (`destination_prospect_location_id`) REFERENCES `prospect_locations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `prploo_dest_port_fk` FOREIGN KEY (`destination_port_id`) REFERENCES `ports` (`port_id`) ON DELETE SET NULL,
  CONSTRAINT `prploo_origin_loc_fk` FOREIGN KEY (`origin_prospect_location_id`) REFERENCES `prospect_locations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `prploo_origin_port_fk` FOREIGN KEY (`origin_port_id`) REFERENCES `ports` (`port_id`) ON DELETE SET NULL,
  CONSTRAINT `prploo_request_fk` FOREIGN KEY (`proposal_request_id`) REFERENCES `proposal_requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposal_request_product_loose_cargo`
--

LOCK TABLES `proposal_request_product_loose_cargo` WRITE;
/*!40000 ALTER TABLE `proposal_request_product_loose_cargo` DISABLE KEYS */;
/*!40000 ALTER TABLE `proposal_request_product_loose_cargo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proposal_request_product_rates`
--

DROP TABLE IF EXISTS `proposal_request_product_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposal_request_product_rates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_request_id` bigint(20) unsigned NOT NULL,
  `product_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `item_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_rate` decimal(12,2) NOT NULL DEFAULT 0.00,
  `adjustment` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_rate` decimal(12,2) NOT NULL DEFAULT 0.00,
  `fee_breakdown` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`fee_breakdown`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pr_product_rates_request_type_product_unique` (`proposal_request_id`,`product_type`,`product_id`),
  CONSTRAINT `proposal_request_product_rates_proposal_request_id_foreign` FOREIGN KEY (`proposal_request_id`) REFERENCES `proposal_requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposal_request_product_rates`
--

LOCK TABLES `proposal_request_product_rates` WRITE;
/*!40000 ALTER TABLE `proposal_request_product_rates` DISABLE KEYS */;
/*!40000 ALTER TABLE `proposal_request_product_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proposal_request_product_rolling_cargo`
--

DROP TABLE IF EXISTS `proposal_request_product_rolling_cargo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposal_request_product_rolling_cargo` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_request_id` bigint(20) unsigned NOT NULL,
  `cargo_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo_details` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo_quantity` int(11) DEFAULT NULL,
  `cargo_units` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revenue_ton` decimal(12,2) DEFAULT NULL,
  `revenue_ton_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo_measurement` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_type_id` bigint(20) unsigned DEFAULT NULL,
  `service_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `origin_prospect_location_id` bigint(20) unsigned DEFAULT NULL,
  `destination_prospect_location_id` bigint(20) unsigned DEFAULT NULL,
  `origin_port_id` bigint(20) unsigned DEFAULT NULL,
  `destination_port_id` bigint(20) unsigned DEFAULT NULL,
  `terms_of_payment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prprol_request_fk` (`proposal_request_id`),
  KEY `prprol_delivery_fk` (`delivery_type_id`),
  KEY `prprol_origin_loc_fk` (`origin_prospect_location_id`),
  KEY `prprol_dest_loc_fk` (`destination_prospect_location_id`),
  KEY `prprol_origin_port_fk` (`origin_port_id`),
  KEY `prprol_dest_port_fk` (`destination_port_id`),
  CONSTRAINT `prprol_delivery_fk` FOREIGN KEY (`delivery_type_id`) REFERENCES `delivery_types` (`delivery_type_id`) ON DELETE SET NULL,
  CONSTRAINT `prprol_dest_loc_fk` FOREIGN KEY (`destination_prospect_location_id`) REFERENCES `prospect_locations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `prprol_dest_port_fk` FOREIGN KEY (`destination_port_id`) REFERENCES `ports` (`port_id`) ON DELETE SET NULL,
  CONSTRAINT `prprol_origin_loc_fk` FOREIGN KEY (`origin_prospect_location_id`) REFERENCES `prospect_locations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `prprol_origin_port_fk` FOREIGN KEY (`origin_port_id`) REFERENCES `ports` (`port_id`) ON DELETE SET NULL,
  CONSTRAINT `prprol_request_fk` FOREIGN KEY (`proposal_request_id`) REFERENCES `proposal_requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposal_request_product_rolling_cargo`
--

LOCK TABLES `proposal_request_product_rolling_cargo` WRITE;
/*!40000 ALTER TABLE `proposal_request_product_rolling_cargo` DISABLE KEYS */;
/*!40000 ALTER TABLE `proposal_request_product_rolling_cargo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proposal_request_product_topload_cargo`
--

DROP TABLE IF EXISTS `proposal_request_product_topload_cargo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposal_request_product_topload_cargo` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_request_product_rolling_cargo_id` bigint(20) unsigned NOT NULL,
  `top_load_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `units` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revenue_ton` decimal(12,2) DEFAULT NULL,
  `revenue_ton_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `measurement` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prp_topload_rolling_fk` (`proposal_request_product_rolling_cargo_id`),
  CONSTRAINT `prp_topload_rolling_fk` FOREIGN KEY (`proposal_request_product_rolling_cargo_id`) REFERENCES `proposal_request_product_rolling_cargo` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposal_request_product_topload_cargo`
--

LOCK TABLES `proposal_request_product_topload_cargo` WRITE;
/*!40000 ALTER TABLE `proposal_request_product_topload_cargo` DISABLE KEYS */;
/*!40000 ALTER TABLE `proposal_request_product_topload_cargo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proposal_request_product_truckings`
--

DROP TABLE IF EXISTS `proposal_request_product_truckings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposal_request_product_truckings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_request_id` bigint(20) unsigned NOT NULL,
  `trucking_cargo_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int(10) unsigned DEFAULT NULL,
  `dispatch_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `origin_prospect_location_id` bigint(20) unsigned DEFAULT NULL,
  `destination_prospect_location_id` bigint(20) unsigned DEFAULT NULL,
  `cargo_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `terms_of_payment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prptrk_request_fk` (`proposal_request_id`),
  KEY `prptrk_origin_loc_fk` (`origin_prospect_location_id`),
  KEY `prptrk_dest_loc_fk` (`destination_prospect_location_id`),
  CONSTRAINT `prptrk_dest_loc_fk` FOREIGN KEY (`destination_prospect_location_id`) REFERENCES `prospect_locations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `prptrk_origin_loc_fk` FOREIGN KEY (`origin_prospect_location_id`) REFERENCES `prospect_locations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `prptrk_request_fk` FOREIGN KEY (`proposal_request_id`) REFERENCES `proposal_requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposal_request_product_truckings`
--

LOCK TABLES `proposal_request_product_truckings` WRITE;
/*!40000 ALTER TABLE `proposal_request_product_truckings` DISABLE KEYS */;
/*!40000 ALTER TABLE `proposal_request_product_truckings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proposal_request_signatories`
--

DROP TABLE IF EXISTS `proposal_request_signatories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposal_request_signatories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_request_id` bigint(20) unsigned NOT NULL,
  `prospect_contact_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pr_signatory_unique` (`proposal_request_id`,`prospect_contact_id`),
  KEY `proposal_request_signatories_prospect_contact_id_foreign` (`prospect_contact_id`),
  CONSTRAINT `proposal_request_signatories_proposal_request_id_foreign` FOREIGN KEY (`proposal_request_id`) REFERENCES `proposal_requests` (`id`) ON DELETE CASCADE,
  CONSTRAINT `proposal_request_signatories_prospect_contact_id_foreign` FOREIGN KEY (`prospect_contact_id`) REFERENCES `prospect_contacts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposal_request_signatories`
--

LOCK TABLES `proposal_request_signatories` WRITE;
/*!40000 ALTER TABLE `proposal_request_signatories` DISABLE KEYS */;
/*!40000 ALTER TABLE `proposal_request_signatories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proposal_request_terms_of_payment`
--

DROP TABLE IF EXISTS `proposal_request_terms_of_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposal_request_terms_of_payment` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `proposal_request_id` bigint(20) unsigned NOT NULL,
  `section` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `terms_of_payment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pr_terms_of_payment_unique` (`proposal_request_id`,`section`),
  CONSTRAINT `proposal_request_terms_of_payment_proposal_request_id_foreign` FOREIGN KEY (`proposal_request_id`) REFERENCES `proposal_requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposal_request_terms_of_payment`
--

LOCK TABLES `proposal_request_terms_of_payment` WRITE;
/*!40000 ALTER TABLE `proposal_request_terms_of_payment` DISABLE KEYS */;
/*!40000 ALTER TABLE `proposal_request_terms_of_payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proposal_request_truckings`
--

DROP TABLE IF EXISTS `proposal_request_truckings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposal_request_truckings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `prospect_id` bigint(20) unsigned DEFAULT NULL,
  `trucking_cargo_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `frequency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dispatch_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `origin_location_id` bigint(20) unsigned DEFAULT NULL,
  `destination_location_id` bigint(20) unsigned DEFAULT NULL,
  `declared_value_per_unit` decimal(15,2) DEFAULT NULL,
  `weight` decimal(12,2) DEFAULT NULL,
  `weight_unit` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `general_cargo_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `special_requirements` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `proposal_request_truckings_origin_location_id_foreign` (`origin_location_id`),
  KEY `proposal_request_truckings_destination_location_id_foreign` (`destination_location_id`),
  KEY `proposal_request_truckings_prospect_id_foreign` (`prospect_id`),
  CONSTRAINT `proposal_request_truckings_destination_location_id_foreign` FOREIGN KEY (`destination_location_id`) REFERENCES `locations` (`location_id`) ON DELETE SET NULL,
  CONSTRAINT `proposal_request_truckings_origin_location_id_foreign` FOREIGN KEY (`origin_location_id`) REFERENCES `locations` (`location_id`) ON DELETE SET NULL,
  CONSTRAINT `proposal_request_truckings_prospect_id_foreign` FOREIGN KEY (`prospect_id`) REFERENCES `prospects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposal_request_truckings`
--

LOCK TABLES `proposal_request_truckings` WRITE;
/*!40000 ALTER TABLE `proposal_request_truckings` DISABLE KEYS */;
/*!40000 ALTER TABLE `proposal_request_truckings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proposal_requests`
--

DROP TABLE IF EXISTS `proposal_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposal_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prospect_id` bigint(20) unsigned NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `submitted_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `additional_notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `proposal_requests_code_unique` (`code`),
  KEY `proposal_requests_created_by_foreign` (`created_by`),
  KEY `proposal_requests_submitted_by_foreign` (`submitted_by`),
  KEY `proposal_requests_prospect_id_index` (`prospect_id`),
  CONSTRAINT `proposal_requests_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `proposal_requests_prospect_id_foreign` FOREIGN KEY (`prospect_id`) REFERENCES `prospects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `proposal_requests_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proposal_requests`
--

LOCK TABLES `proposal_requests` WRITE;
/*!40000 ALTER TABLE `proposal_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `proposal_requests` ENABLE KEYS */;
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
INSERT INTO `proposal_status` VALUES (1,'Pending',NULL,'2026-09-17 00:59:27'),(2,'Approved',NULL,'2026-09-17 00:59:27'),(3,'Disapproved',NULL,'2026-09-17 00:59:27'),(4,'Accepted',NULL,'2026-09-17 00:59:27'),(5,'Rejected',NULL,'2026-09-17 00:59:27'),(6,'On-Hold',NULL,'2026-09-17 00:59:27');
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
  `prospect_id` bigint(20) unsigned NOT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `proposals_uuid_unique` (`uuid`),
  KEY `proposals_lead_id_foreign` (`prospect_id`),
  KEY `proposals_created_by_foreign` (`created_by`),
  CONSTRAINT `proposals_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `proposals_lead_id_foreign` FOREIGN KEY (`prospect_id`) REFERENCES `prospects` (`id`) ON DELETE CASCADE
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
-- Table structure for table `prospect_activities`
--

DROP TABLE IF EXISTS `prospect_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prospect_activities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `prospect_id` bigint(20) unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `crm_activities_lead_id_foreign` (`prospect_id`),
  KEY `crm_activities_created_by_foreign` (`created_by`),
  CONSTRAINT `crm_activities_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `crm_activities_lead_id_foreign` FOREIGN KEY (`prospect_id`) REFERENCES `prospects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prospect_activities`
--

LOCK TABLES `prospect_activities` WRITE;
/*!40000 ALTER TABLE `prospect_activities` DISABLE KEYS */;
/*!40000 ALTER TABLE `prospect_activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prospect_company_info`
--

DROP TABLE IF EXISTS `prospect_company_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prospect_company_info` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `prospect_id` bigint(20) unsigned NOT NULL,
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
  KEY `crm_company_info_lead_id_foreign` (`prospect_id`),
  CONSTRAINT `crm_company_info_lead_id_foreign` FOREIGN KEY (`prospect_id`) REFERENCES `prospects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prospect_company_info`
--

LOCK TABLES `prospect_company_info` WRITE;
/*!40000 ALTER TABLE `prospect_company_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `prospect_company_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prospect_contact_addresses`
--

DROP TABLE IF EXISTS `prospect_contact_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prospect_contact_addresses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `prospect_contact_id` bigint(20) unsigned NOT NULL,
  `prospect_location_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prospect_contact_addresses_prospect_contact_id_foreign` (`prospect_contact_id`),
  KEY `prospect_contact_addresses_prospect_location_id_foreign` (`prospect_location_id`),
  CONSTRAINT `prospect_contact_addresses_prospect_contact_id_foreign` FOREIGN KEY (`prospect_contact_id`) REFERENCES `prospect_contacts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `prospect_contact_addresses_prospect_location_id_foreign` FOREIGN KEY (`prospect_location_id`) REFERENCES `prospect_locations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prospect_contact_addresses`
--

LOCK TABLES `prospect_contact_addresses` WRITE;
/*!40000 ALTER TABLE `prospect_contact_addresses` DISABLE KEYS */;
/*!40000 ALTER TABLE `prospect_contact_addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prospect_contact_channels`
--

DROP TABLE IF EXISTS `prospect_contact_channels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prospect_contact_channels` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `prospect_contact_id` bigint(20) unsigned NOT NULL,
  `channel_type` enum('mobile','landline','email') COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_type` enum('personal','business') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prospect_contact_channels_prospect_contact_id_foreign` (`prospect_contact_id`),
  CONSTRAINT `prospect_contact_channels_prospect_contact_id_foreign` FOREIGN KEY (`prospect_contact_id`) REFERENCES `prospect_contacts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prospect_contact_channels`
--

LOCK TABLES `prospect_contact_channels` WRITE;
/*!40000 ALTER TABLE `prospect_contact_channels` DISABLE KEYS */;
/*!40000 ALTER TABLE `prospect_contact_channels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prospect_contacts`
--

DROP TABLE IF EXISTS `prospect_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prospect_contacts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `prospect_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prospect_contacts_prospect_id_foreign` (`prospect_id`),
  CONSTRAINT `prospect_contacts_prospect_id_foreign` FOREIGN KEY (`prospect_id`) REFERENCES `prospects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prospect_contacts`
--

LOCK TABLES `prospect_contacts` WRITE;
/*!40000 ALTER TABLE `prospect_contacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `prospect_contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prospect_containers_legacy`
--

DROP TABLE IF EXISTS `prospect_containers_legacy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prospect_containers_legacy` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `prospect_id` bigint(20) unsigned NOT NULL,
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
  KEY `crm_lead_containers_lead_id_foreign` (`prospect_id`),
  KEY `crm_lead_containers_origin_port_id_foreign` (`origin_port_id`),
  KEY `crm_lead_containers_destination_port_id_foreign` (`destination_port_id`),
  KEY `crm_lead_containers_container_class_id_foreign` (`container_class_id`),
  KEY `crm_lead_containers_container_size_id_foreign` (`container_size_id`),
  CONSTRAINT `crm_lead_containers_container_class_id_foreign` FOREIGN KEY (`container_class_id`) REFERENCES `container_class` (`id`) ON DELETE SET NULL,
  CONSTRAINT `crm_lead_containers_container_size_id_foreign` FOREIGN KEY (`container_size_id`) REFERENCES `container_size` (`id`) ON DELETE SET NULL,
  CONSTRAINT `crm_lead_containers_destination_port_id_foreign` FOREIGN KEY (`destination_port_id`) REFERENCES `ports` (`port_id`) ON DELETE SET NULL,
  CONSTRAINT `crm_lead_containers_lead_id_foreign` FOREIGN KEY (`prospect_id`) REFERENCES `prospects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `crm_lead_containers_origin_port_id_foreign` FOREIGN KEY (`origin_port_id`) REFERENCES `ports` (`port_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prospect_containers_legacy`
--

LOCK TABLES `prospect_containers_legacy` WRITE;
/*!40000 ALTER TABLE `prospect_containers_legacy` DISABLE KEYS */;
/*!40000 ALTER TABLE `prospect_containers_legacy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prospect_locations`
--

DROP TABLE IF EXISTS `prospect_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prospect_locations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `prospect_id` bigint(20) unsigned NOT NULL,
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
  KEY `crm_lead_addresses_lead_id_foreign` (`prospect_id`),
  CONSTRAINT `crm_lead_addresses_lead_id_foreign` FOREIGN KEY (`prospect_id`) REFERENCES `prospects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prospect_locations`
--

LOCK TABLES `prospect_locations` WRITE;
/*!40000 ALTER TABLE `prospect_locations` DISABLE KEYS */;
/*!40000 ALTER TABLE `prospect_locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prospect_notes`
--

DROP TABLE IF EXISTS `prospect_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prospect_notes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `prospect_id` bigint(20) unsigned NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `crm_notes_lead_id_foreign` (`prospect_id`),
  KEY `crm_notes_created_by_foreign` (`created_by`),
  CONSTRAINT `crm_notes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `crm_notes_lead_id_foreign` FOREIGN KEY (`prospect_id`) REFERENCES `prospects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prospect_notes`
--

LOCK TABLES `prospect_notes` WRITE;
/*!40000 ALTER TABLE `prospect_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `prospect_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prospects`
--

DROP TABLE IF EXISTS `prospects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prospects` (
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
  `relationship_manager_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `owner_id` bigint(20) unsigned DEFAULT NULL,
  `estimated_value` decimal(12,2) DEFAULT NULL,
  `requires_proposal` tinyint(1) NOT NULL DEFAULT 1,
  `expected_close_date` date DEFAULT NULL,
  `status_updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `crm_leads_uuid_unique` (`uuid`),
  UNIQUE KEY `crm_leads_customer_code_unique` (`customer_code`),
  KEY `crm_leads_assigned_to_foreign` (`assigned_to`),
  KEY `prospects_relationship_manager_id_foreign` (`relationship_manager_id`),
  KEY `prospects_created_by_foreign` (`created_by`),
  KEY `prospects_owner_id_foreign` (`owner_id`),
  CONSTRAINT `crm_leads_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `prospects_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `prospects_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `prospects_relationship_manager_id_foreign` FOREIGN KEY (`relationship_manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prospects`
--

LOCK TABLES `prospects` WRITE;
/*!40000 ALTER TABLE `prospects` DISABLE KEYS */;
/*!40000 ALTER TABLE `prospects` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permission`
--

LOCK TABLES `role_permission` WRITE;
/*!40000 ALTER TABLE `role_permission` DISABLE KEYS */;
INSERT INTO `role_permission` VALUES (13,1,1),(6,1,2),(5,1,3),(4,1,4),(1,1,5),(8,1,6),(2,1,7),(7,1,8),(10,1,9),(3,1,10),(9,1,11),(11,1,12),(12,1,13),(15,1,14),(14,1,15),(17,2,15),(16,7,14);
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
INSERT INTO `routes` VALUES (1,'BUTUAN','BUTUAN',NULL,'2026-09-17 00:59:27'),(2,'CEBU','CEBU',NULL,'2026-09-17 00:59:27'),(3,'CAGAYAN','CAGAYAN',NULL,'2026-09-17 00:59:27'),(4,'DAVAO','DAVAO',NULL,'2026-09-17 00:59:27'),(5,'DUMAGUETE','DUMAGUETE',NULL,'2026-09-17 00:59:27'),(6,'GEN SAN','GEN SAN',NULL,'2026-09-17 00:59:27'),(7,'ILIGAN','ILIGAN',NULL,'2026-09-17 00:59:27'),(8,'ILOILO','ILOILO',NULL,'2026-09-17 00:59:27'),(9,'OSAMIS','OSAMIS',NULL,'2026-09-17 00:59:27'),(10,'CORON','CORON',NULL,'2026-09-17 00:59:27'),(11,'ROXAS','ROXAS',NULL,'2026-09-17 00:59:27'),(12,'CATICLAN','CATICLAN',NULL,'2026-09-17 00:59:27'),(13,'ORMOC','ORMOC',NULL,'2026-09-17 00:59:27'),(14,'TAGBILARAN','TAGBILARAN',NULL,'2026-09-17 00:59:27'),(15,'TACLOBAN','TACLOBAN',NULL,'2026-09-17 00:59:27'),(16,'ZAMBOANGA','ZAMBOANGA',NULL,'2026-09-17 00:59:27'),(17,'PUERTO PRINCESSA','PUERTO PRINCESSA',NULL,'2026-09-17 00:59:27'),(18,'SURIGAO','SURIGAO',NULL,'2026-09-17 00:59:27'),(19,'COTABATO','COTABATO',NULL,'2026-09-17 00:59:27'),(20,'BATANGAS','BATANGAS',NULL,'2026-09-17 00:59:27'),(21,'MANILA','MANILA',NULL,'2026-09-17 00:59:27');
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
INSERT INTO `service_type` VALUES (1,'ORIGIN','DOOR',NULL,'2026-09-17 00:59:27'),(2,'ORIGIN','PIER-STUFFING',NULL,'2026-09-17 00:59:27'),(3,'ORIGIN','PIER-VANOUT',NULL,'2026-09-17 00:59:27'),(4,'DESTINATION','DOOR',NULL,'2026-09-17 00:59:27'),(5,'DESTINATION','PIER-STRIPPING',NULL,'2026-09-17 00:59:27'),(6,'DESTINATION','PIER-VAN OUT',NULL,'2026-09-17 00:59:27');
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
  CONSTRAINT `serviceable_areas_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`location_id`) ON DELETE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=259 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `serviceable_areas`
--

LOCK TABLES `serviceable_areas` WRITE;
/*!40000 ALTER TABLE `serviceable_areas` DISABLE KEYS */;
INSERT INTO `serviceable_areas` VALUES (1,1,'Aklan - Caticlan, Malay',1,'2026-09-17 00:59:28','2026-10-22 18:12:34'),(2,1,'Aklan - Dumaguit, New Washington',1,'2026-09-17 00:59:28','2026-10-22 18:12:34'),(3,1,'Aklan - Kalibo',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(4,2,'Negros Occidental - Bacolod',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(5,2,'Negros Occidental - Banago',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(6,2,'Negros Occidental - Pulupandan',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(7,3,'Bataan - Mariveles',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(8,3,'Bataan - Orion',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(9,4,'Laguna - Alaminos',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(10,4,'Batangas - BIPI, Bauan',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(11,4,'Bulacan - Balagtas',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(12,4,'Batangas - Balayan',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(13,4,'Batangas - Banaybanay',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(14,4,'Batangas - Batangas City',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(15,4,'Batangas - Bauan',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(16,4,'Laguna - Binan',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(17,4,'Laguna - Cabuyao',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(18,4,'Batangas - Calaca',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(19,4,'Laguna - Calamba',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(20,4,'Batangas - Calatagan',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(21,4,'Quezon - Candelaria',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(22,4,'Laguna - Canlubang',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(23,4,'Batangas - Frabelle, Bauan',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(24,4,'Batangas - Gulod',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(25,4,'Batangas - Ibaan',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(26,4,'Batangas - Ilijan',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(27,4,'Batangas - Lemery',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(28,4,'Batangas - Lian',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(29,4,'Batangas - Lipa City',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(30,4,'Batangas - Lipa-Malvar',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(31,4,'Laguna - Los Banos',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(32,4,'Occidental Mindoro - Lubang',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(33,4,'Quezon - Lucena',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(34,4,'Batangas - Maapaz',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(35,4,'Batangas - Mabini',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(36,4,'Batangas - Mahabang Parang',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(37,4,'Batangas - Malvar',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(38,4,'Batangas - PNOC',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(39,4,'Batangas - Padre Garcia',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(40,4,'Batangas - Rosario',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(41,4,'Oriental Mindoro - Roxas',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(42,4,'Batangas - San Jose',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(43,4,'Batangas - San Juan',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(44,4,'Laguna - San Pablo',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(45,4,'Batangas - San Pascual',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(46,4,'Laguna - San Pedro',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(47,4,'Quezon - Sariaya',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(48,4,'Batangas - Simlong',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(49,4,'Batangas - Sorosoro',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(50,4,'Batangas - Sta. Clara',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(51,4,'Laguna - Sta. Rosa',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(52,4,'Batangas - Sto. Tomas',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(53,4,'Batangas - Tabangao',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(54,4,'Batangas - Tabangao Port',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(55,4,'Batangas - Talisay',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(56,4,'Quezon - Tayabas',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(57,4,'Batangas - Taysan',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(58,4,'Quezon - Tiaong',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(59,5,'Albay',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(60,5,'Camarines Norte - Daet',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(61,5,'Albay - Legaspi',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(62,5,'Camarines Sur - Milaor',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(63,5,'Camarines Sur - Naga',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(64,5,'Sorsogon',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(65,5,'Albay - Tabaco City Port',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(66,6,'Agusan del Norte - Butuan',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(67,6,'Agusan del Norte - Masao',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(68,7,'Misamis Oriental - BBASI Port',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(69,7,'Misamis Oriental - Bulua',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(70,7,'Misamis Oriental - CDO Bugo',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(71,7,'Misamis Oriental - CDO Mitimco',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(72,7,'Misamis Oriental - El Salvador',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(73,7,'Misamis Oriental - Gracia Port',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(74,7,'Lanao del Norte - Iligan City',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(75,7,'Misamis Oriental - Malasag, Cugman',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(76,7,'Misamis Oriental - Opol',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(77,7,'Misamis Oriental - Tablon',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(78,7,'Misamis Oriental - Villanueva',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(79,8,'Cebu - Banilad',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(80,8,'Cebu - Basak, Mandaue',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(81,8,'Cebu - Canduman, Mandaue',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(82,8,'Cebu - Cebu',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(83,8,'Cebu - Compostela',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(84,8,'Cebu - Consolacion',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(85,8,'Cebu - Cordova',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(86,8,'Cebu - Danao',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(87,8,'Cebu - KTC Port',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(88,8,'Cebu - Labangon',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(89,8,'Cebu - Lapu-Lapu City',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(90,8,'Cebu - Lilo-an',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(91,8,'Cebu - Mabolo',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(92,8,'Cebu - Malabuyoc',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(93,8,'Cebu - Mambaling',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(94,8,'Cebu - Mandaue',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(95,8,'Cebu - Minglanilla',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(96,8,'Cebu - Naga',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(97,8,'Cebu - Opao, Mandaue',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(98,8,'Cebu - Pagsabungan, Mandaue',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(99,8,'Cebu - Pakna-an, Mandaue',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(100,8,'Cebu - San Fernando',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(101,8,'Cebu - Tabok, Mandaue',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(102,8,'Cebu - Talisay',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(103,8,'Cebu - Tawason, Mandaue',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(104,8,'Cebu - Tayud, Consolacion',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(105,8,'Cebu - Tingub, Mandaue',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(106,8,'Cebu - Tipolo, Mandaue',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(107,9,'Davao del Norte - AO Panabo',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(108,9,'Davao del Sur - Agdao',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(109,9,'Davao del Sur - Buhangin',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(110,9,'Davao del Sur - Buhisan',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(111,9,'Davao del Sur - Bunawan',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(112,9,'Davao del Norte - Cagangohan, Panabo',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(113,9,'Coronan',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(114,9,'Davao del Sur - Darong',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(115,9,'Davao del Sur - Davao',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(116,9,'Davao Del Sur',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(117,9,'Davao del Sur - Digos',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(118,9,'South Cotabato - General Santos',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(119,9,'KTC Port',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(120,9,'Cotabato - Kidapawan',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(121,9,'Davao del Sur - Lanang',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(122,9,'Lanao',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(123,9,'Davao del Sur - Lapu-Lapu',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(124,9,'Davao del Sur - Maa',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(125,9,'Davao del Norte - Malitbog, Panabo',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(126,9,'Davao del Norte - Manay, Panabo',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(127,9,'Davao Oriental - Mati',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(128,9,'Davao del Sur - Matina',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(129,9,'Davao del Norte - Nanyo, Panabo',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(130,9,'Davao del Sur - Obrero',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(131,9,'Davao del Norte - Panabo',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(132,9,'Davao del Sur - Panacan',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(133,9,'Davao del Sur - Sasa',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(134,9,'South Cotabato',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(135,9,'Davao del Norte - Sto. Tomas',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(136,9,'Davao del Norte - Tagpore, Panabo',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(137,9,'Davao del Norte - Tagum',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(138,9,'Davao del Sur - Talomo',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(139,9,'Davao del Sur - Tibungco',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(140,9,'Davao del Sur - Toril',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(141,9,'Davao del Sur - Ulas',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(142,10,'Dinagat Islands - Dinagat',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(143,11,'La Union - Bauang',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(144,11,'Cauayan',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(145,11,'Benguet - La Trinidad',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(146,11,'Ilocos Sur - Magsingal',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(147,11,'La Union - San Fernando',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(148,11,'La Union - Sto.Tomas',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(149,12,'Leyte - Baybay',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(150,12,'Benit',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(151,12,'Leyte - Carigara',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(152,12,'Southern Leyte - Maasin',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(153,12,'Leyte - Ormoc',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(154,12,'Poblacion',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(155,12,'Southern Leyte - San Ricardo',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(156,13,'Pampanga - Angeles',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(157,13,'Rizal - Antipolo',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(158,13,'Cavite - Bacoor',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(159,13,'Metro Manila (NCR) - Bagong Ilog',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(160,13,'Metro Manila (NCR) - Balintawak',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(161,13,'Metro Manila (NCR) - Bicutan',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(162,13,'Rizal - Cainta',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(163,13,'Metro Manila (NCR) - Caloocan',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(164,13,'Bulacan - Calumpit',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(165,13,'Tarlac - Capas',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(166,13,'Cavite - Carmona',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(167,13,'Cavite - Cavite',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(168,13,'Cavite - Dasmarinas',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(169,13,'Cavite - General Trias',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(170,13,'Bulacan - Guiguinto',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(171,13,'Metro Manila (NCR) - Harbour Center',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(172,13,'Cavite - Imus',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(173,13,'Cavite - Kawit',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(174,13,'Metro Manila (NCR) - Lagro',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(175,13,'Metro Manila (NCR) - Las Pinas',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(176,13,'Metro Manila (NCR) - Libis',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(177,13,'Pampanga - Mabalacat',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(178,13,'Metro Manila (NCR) - Makati',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(179,13,'Metro Manila (NCR) - Malabon',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(180,13,'Metro Manila (NCR) - Mandaluyong',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(181,13,'Metro Manila (NCR) - Manila',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(182,13,'Metro Manila (NCR) - Marikina',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(183,13,'Bulacan - Marilao',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(184,13,'Bulacan - Meycauayan',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(185,13,'Metro Manila (NCR) - Multipurpose Terminal',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(186,13,'Metro Manila (NCR) - Muntinlupa',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(187,13,'Metro Manila (NCR) - Navotas',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(188,13,'Metro Manila (NCR) - North Harbour',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(189,13,'Metro Manila (NCR) - Paco',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(190,13,'Metro Manila (NCR) - Paranaque',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(191,13,'Metro Manila (NCR) - Pasay',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(192,13,'Metro Manila (NCR) - Pasig',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(193,13,'Metro Manila (NCR) - Pinagbuhatan',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(194,13,'Bulacan - Pulilan',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(195,13,'Metro Manila (NCR) - Quezon City',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(196,13,'Rizal',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(197,13,'Rizal - Rodriguez',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(198,13,'Cavite - Rosario',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(199,13,'Pampanga - San Fernando',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(200,13,'Bulacan - San Ildefonso',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(201,13,'San Isidro',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(202,13,'Metro Manila (NCR) - San Juan',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(203,13,'Bulacan - San Rafael',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(204,13,'Cavite - Silang',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(205,13,'Bulacan - Sta. Maria',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(206,13,'Metro Manila (NCR) - Sucat',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(207,13,'Metro Manila (NCR) - Taguig',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(208,13,'Cavite - Tanza',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(209,13,'Rizal - Taytay',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(210,13,'Metro Manila (NCR) - Valenzuela',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(211,13,'Metro Manila (NCR) - Vitas Pier 18',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(212,14,'Masbate - Masbate',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(213,14,'Masbate - Masbate Port',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(214,14,'Masbate - Ticao',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(215,14,'Masbate - Tugbo',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(216,15,'Palawan - Balabac',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(217,15,'Palawan - Bataraza',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(218,15,'Palawan - Brooke\'s Point',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(219,15,'Palawan - Coron',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(220,15,'Palawan - El Nido',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(221,15,'Palawan - Narra',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(222,15,'Palawan - Pamalican',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(223,15,'Palawan - Puerto Princesa',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(224,15,'Palawan - Punta Baja, Rizal',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(225,15,'Palawan - Quezon',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(226,15,'Palawan - Ransang, Rizal',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(227,15,'Retayir Port',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(228,15,'Palawan - Rio Tuba',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(229,15,'Palawan - Rizal',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(230,15,'Palawan - Roxas',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(231,15,'Palawan - San Manuel',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(232,15,'Palawan - San Vicente',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(233,15,'Palawan - Taytay',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(234,16,'Pangasinan - Lingayen',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(235,16,'Magalianes',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(236,16,'San Fernando',1,'2026-09-17 00:59:28','2026-09-17 00:59:28'),(237,16,'Pangasinan - San Nicolas',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(238,16,'Pangasinan - Sual',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(239,17,'Romblon - Romblon',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(240,18,'Iloilo - Dumangas',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(241,18,'Iloilo - Iloilo City',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(242,18,'Capiz - Roxas City',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(243,18,'Iloilo - San Miguel',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(244,19,'Surigao del Norte - Nonoc Island',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(245,19,'Surigao del Norte - Surigao',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(246,20,'Samar - Calbayog',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(247,20,'Leyte - Sta. Fe',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(248,20,'Leyte - Tacloban',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(249,20,'Leyte - Tacloban Port',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(250,20,'Leyte - Tanauan',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(251,21,'Zambales - Iba',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(252,21,'Zambales - Masinloc',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(253,21,'Zambales - Subic',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(254,22,'Bataan - Lamao',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(255,22,'Zamboanga del Norte - Liloy',1,'2026-09-17 00:59:28','2026-10-22 17:32:48'),(257,23,'Tarlac - Anao',1,'2026-10-22 16:19:06','2026-10-22 16:19:06'),(258,23,'Tarlac - Bamban',1,'2026-10-22 16:19:06','2026-10-22 16:19:06');
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
INSERT INTO `sessions` VALUES ('gEw042mtnv9e12nRRxPsBobqejotiqJNKExLppoG',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.29.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNnRRdXpBMUV0NFVPeEZpRWFDY0djQUwyekpuV21hcFd6aERDd2tTbyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly9rYXJnYW1pbmVfcHJvdG90eXBlLnRlc3QvP2hlcmQ9cHJldmlldyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1792174429),('hYCrzQH77LzuRlDhOA2DoXHSYYwMNw7f1Lqmoffb',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.29.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiczlGV2drM1ZqMmRhbkJKbXd0c0F3ZFg5eEJ6OE1nWUprWGFMMG5uWSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly9rYXJnYW1pbmVfcHJvdG90eXBlLnRlc3QvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1792174429),('k6AcGgxddcSJdTGeSrX6csPMbyhNeIXkDbzAiqnI',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.29.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVXRkQk5nTWRvaXEwMFdKNFlXR05HdEtLbkw1VVBxYnpMaGw0czJHciI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly9rYXJnYW1pbmVfcHJvdG90eXBlLnRlc3QvP2hlcmQ9cHJldmlldyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1792174411),('l9tkLSM2RkAS1YKXnl4SdKzeqe9pXQzQb92LkjJ8',9,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoibUdleG1keElVZEpNam9mYXNXeXZnNTZpU1BkbkVVb05ja251T3RDYyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjYyOiJodHRwOi8va2FyZ2FtaW5lX3Byb3RvdHlwZS50ZXN0L2FwaS9ub3RpZmljYXRpb25zL3VucmVhZC1jb3VudCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjk7fQ==',1792713667),('RQqWEEi0Q3cmD0b0KgoTFbgry4sE9NSyXtmZTNVQ',14,'103.36.18.156','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVGs2bmIzdXVEelBGa2VCYVJPWnh2YXFhakIwd2U5Mnh3RlZOdUFFVCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NjA6Imh0dHBzOi8va2FyZ2FtaW5lLnN5bnhjZWwuY29tL2FwaS9ub3RpZmljYXRpb25zL3VucmVhZC1jb3VudCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE0O30=',1792711804),('rUJcwMkkJ3cmvn1FWr7O0Y11k6NSjPFXJIUQBJ6X',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.29.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoicmp3TlBXdnRJUVczREJLbjZWT1prZWlLek9lQVlyVldPQUVXa0N0ZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly9rYXJnYW1pbmVfcHJvdG90eXBlLnRlc3QvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1792174411),('srAvO5gHqIzaRTNjxn7QrjLFmgtnRhOYMBDYVbw0',NULL,'127.0.0.1','Symfony','YTo0OntzOjY6Il90b2tlbiI7czo0MDoibk51V29zUFZMeXB0bGJvM21CVGpaaGRsVDk5TllKa25uN2JSbTVmTiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNjoiaHR0cDovL2xvY2FsaG9zdC9hcGkvY2hhcnRlclNlcnZpY2VzIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9sb2NhbGhvc3QvYXBpL2NoYXJ0ZXJTZXJ2aWNlcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1792430817);
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `setting_role`
--

LOCK TABLES `setting_role` WRITE;
/*!40000 ALTER TABLE `setting_role` DISABLE KEYS */;
INSERT INTO `setting_role` VALUES (1,'superadmin',1),(2,'admin',1),(3,'user',1),(4,'developer',1),(5,'Credit Officer',0),(6,'Sales',0),(7,'Relationship Manager',0),(8,'Customer Service Representative',0);
/*!40000 ALTER TABLE `setting_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `special_charge_rates`
--

DROP TABLE IF EXISTS `special_charge_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `special_charge_rates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `special_charge_id` bigint(20) unsigned NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_value` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `special_charge_rates_special_charge_id_unit_unique` (`special_charge_id`,`unit`),
  CONSTRAINT `special_charge_rates_special_charge_id_foreign` FOREIGN KEY (`special_charge_id`) REFERENCES `special_charges` (`special_charge_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `special_charge_rates`
--

LOCK TABLES `special_charge_rates` WRITE;
/*!40000 ALTER TABLE `special_charge_rates` DISABLE KEYS */;
INSERT INTO `special_charge_rates` VALUES (1,1,'others',1500.00,'2026-10-19 17:33:36','2026-10-19 17:33:36'),(2,2,'day/s',3500.00,'2026-10-19 17:33:36','2026-10-22 18:33:27'),(3,3,'piece/s',1234567.50,'2026-10-19 17:33:36','2026-10-20 06:13:14'),(4,4,'others',5000.00,'2026-10-19 17:33:36','2026-10-19 17:33:36'),(5,5,'others',8000.00,'2026-10-19 17:33:36','2026-10-19 17:33:36'),(6,6,'others',2000.00,'2026-10-19 17:33:36','2026-10-19 17:33:36'),(7,7,'others',1000.00,'2026-10-19 17:33:36','2026-10-19 17:33:36'),(8,8,'others',2500.00,'2026-10-19 17:33:36','2026-10-19 17:33:36'),(9,9,'others',800.00,'2026-10-19 17:33:36','2026-10-19 17:33:36'),(10,10,'others',3000.00,'2026-10-19 17:33:36','2026-10-19 17:33:36'),(11,11,'others',1500.00,'2026-10-19 17:33:36','2026-10-19 17:33:36'),(12,12,'others',2500.00,'2026-10-19 17:33:36','2026-10-19 17:33:36'),(13,13,'others',1200.00,'2026-10-19 17:33:36','2026-10-19 17:33:36'),(14,14,'others',1000.00,'2026-10-19 17:33:36','2026-10-19 17:33:36'),(15,15,'others',1500.00,'2026-10-19 17:33:36','2026-10-19 17:33:36'),(16,16,'others',800.00,'2026-10-19 17:33:36','2026-10-19 17:33:36'),(17,17,'others',3500.00,'2026-10-19 17:33:36','2026-10-19 17:33:36'),(18,18,'others',3000.00,'2026-10-19 17:33:36','2026-10-19 17:33:36'),(19,19,'others',2000.00,'2026-10-19 17:33:36','2026-10-19 17:33:36'),(20,20,'others',1500.00,'2026-10-19 17:33:36','2026-10-19 17:33:36'),(21,21,'others',1000.00,'2026-10-19 17:33:36','2026-10-19 17:33:36'),(22,22,'others',500.00,'2026-10-19 17:33:36','2026-10-19 17:33:36'),(23,23,'others',2500.00,'2026-10-19 17:33:36','2026-10-19 17:33:36'),(24,24,'others',2500.00,'2026-10-19 17:33:36','2026-10-19 17:33:36'),(25,25,'others',3500.00,'2026-10-19 17:33:36','2026-10-19 17:33:36'),(26,26,'others',1000.00,'2026-10-19 17:33:36','2026-10-19 17:33:36'),(30,2,'hour/s',500.00,'2026-10-22 18:33:45','2026-10-22 18:33:45');
/*!40000 ALTER TABLE `special_charge_rates` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `special_charges`
--

LOCK TABLES `special_charges` WRITE;
/*!40000 ALTER TABLE `special_charges` DISABLE KEYS */;
INSERT INTO `special_charges` VALUES (1,'Amendment Fee',1500.00,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(2,'Backhoe Rental',3500.00,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(3,'Bullet Seal',1234567.50,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(4,'Container Van Rental',5000.00,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(5,'Crane Rental',8000.00,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(6,'Demurrage',2000.00,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(7,'Documentation Assistance Fee',1000.00,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(8,'Double Handling Fee',2500.00,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(9,'Driver Assistance Fee',800.00,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(10,'Forklift Rental',3000.00,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(11,'Foul Trip',1500.00,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(12,'Genset Rental',2500.00,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(13,'Hustling',1200.00,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(14,'Inspection Fee',1000.00,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(15,'Lashing Fee',1500.00,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(16,'Lashing Materials',800.00,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(17,'Lift On Lift Off',3500.00,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(18,'Loader Rental',3000.00,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(19,'Overweight Fee',2000.00,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(20,'Port Charges',1500.00,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(21,'Reimbursement',1000.00,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(22,'Storage',500.00,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(23,'Stripping',2500.00,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(24,'Stuffing',2500.00,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(25,'Trucking',3500.00,1,'2026-09-17 00:59:29','2026-09-17 00:59:29'),(26,'Valuation Fee',1000.00,1,'2026-09-17 00:59:29','2026-09-17 00:59:29');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teams`
--

LOCK TABLES `teams` WRITE;
/*!40000 ALTER TABLE `teams` DISABLE KEYS */;
INSERT INTO `teams` VALUES (1,'Management Team','Top-level management team for test/QA accounts.',NULL,1,'2026-10-19 17:24:16','2026-10-19 17:24:16'),(2,'Sales Team A','Test/QA sales sub-team under Management Team.',1,1,'2026-10-19 17:24:16','2026-10-19 17:24:16'),(3,'Sales Team B','Test/QA sales sub-team under Management Team.',1,1,'2026-10-19 17:24:16','2026-10-19 17:24:16');
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
  `port_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `effective_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`trucking_tariff_id`),
  UNIQUE KEY `trucking_tariffs_area_port_unique` (`area_id`,`port_id`,`effective_date`),
  KEY `trucking_tariffs_area_id_is_active_index` (`area_id`,`is_active`),
  KEY `trucking_tariffs_port_id_foreign` (`port_id`),
  CONSTRAINT `trucking_tariffs_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `serviceable_areas` (`area_id`) ON UPDATE CASCADE,
  CONSTRAINT `trucking_tariffs_port_id_foreign` FOREIGN KEY (`port_id`) REFERENCES `ports` (`port_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=716 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trucking_tariffs`
--

LOCK TABLES `trucking_tariffs` WRITE;
/*!40000 ALTER TABLE `trucking_tariffs` DISABLE KEYS */;
INSERT INTO `trucking_tariffs` VALUES (1,4,3,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(2,5,3,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(3,6,3,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(4,9,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(5,9,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(6,9,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(7,9,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(8,9,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(9,9,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(10,9,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(11,10,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(12,10,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(13,10,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(14,10,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(15,10,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(16,10,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(17,10,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 17:54:39'),(18,11,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(19,11,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(20,11,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(21,11,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(22,11,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(23,11,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(24,11,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(25,12,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(26,12,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(27,12,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(28,12,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(29,12,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(30,12,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(31,12,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(32,13,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(33,13,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(34,13,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(35,13,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(36,13,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(37,13,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(38,13,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(39,14,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(40,14,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(41,14,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(42,14,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(43,14,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(44,14,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(45,14,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(46,15,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(47,15,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(48,15,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(49,15,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(50,15,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(51,15,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(52,15,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(53,16,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(54,16,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(55,16,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(56,16,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(57,16,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(58,16,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(59,16,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(60,17,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(61,17,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(62,17,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(63,17,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(64,17,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(65,17,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(66,17,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(67,18,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(68,18,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(69,18,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(70,18,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(71,18,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(72,18,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(73,18,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(74,19,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(75,19,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(76,19,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(77,19,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(78,19,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(79,19,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(80,19,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(81,20,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(82,20,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(83,20,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(84,20,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(85,20,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(86,20,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(87,20,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(88,21,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(89,21,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(90,21,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(91,21,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(92,21,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(93,21,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(94,21,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(95,22,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(96,22,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(97,22,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(98,22,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(99,22,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(100,22,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(101,22,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(102,23,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(103,23,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(104,23,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(105,23,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(106,23,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(107,23,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(108,23,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(109,24,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(110,24,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(111,24,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(112,24,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(113,24,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(114,24,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(115,24,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(116,25,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(117,25,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(118,25,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(119,25,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(120,25,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(121,25,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(122,25,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(123,26,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(124,26,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(125,26,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(126,26,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(127,26,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(128,26,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(129,26,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(130,27,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(131,27,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(132,27,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(133,27,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(134,27,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(135,27,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(136,27,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(137,28,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(138,28,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(139,28,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(140,28,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(141,28,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(142,28,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(143,28,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(144,29,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(145,29,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(146,29,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(147,29,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(148,29,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(149,29,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(150,29,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(151,30,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(152,30,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(153,30,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(154,30,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(155,30,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(156,30,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(157,30,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(158,31,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(159,31,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(160,31,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(161,31,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(162,31,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(163,31,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(164,31,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(165,32,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(166,32,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(167,32,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(168,32,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(169,32,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(170,32,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(171,32,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(172,33,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(173,33,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(174,33,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(175,33,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(176,33,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(177,33,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(178,33,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(179,34,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(180,34,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(181,34,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(182,34,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(183,34,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(184,34,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(185,34,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(186,35,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(187,35,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(188,35,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(189,35,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(190,35,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(191,35,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(192,35,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(193,36,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(194,36,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(195,36,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(196,36,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(197,36,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(198,36,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(199,36,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(200,37,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(201,37,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(202,37,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(203,37,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(204,37,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(205,37,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(206,37,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(207,38,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(208,38,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(209,38,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(210,38,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(211,38,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(212,38,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(213,38,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(214,39,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(215,39,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(216,39,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(217,39,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(218,39,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(219,39,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(220,39,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(221,40,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(222,40,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(223,40,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(224,40,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(225,40,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(226,40,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(227,40,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(228,41,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(229,41,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(230,41,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(231,41,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(232,41,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(233,41,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(234,41,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(235,42,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(236,42,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(237,42,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(238,42,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(239,42,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(240,42,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(241,42,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(242,43,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(243,43,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(244,43,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(245,43,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(246,43,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(247,43,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(248,43,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(249,44,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(250,44,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(251,44,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(252,44,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(253,44,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(254,44,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(255,44,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(256,45,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(257,45,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(258,45,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(259,45,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(260,45,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(261,45,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(262,45,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(263,46,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(264,46,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(265,46,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(266,46,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(267,46,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(268,46,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(269,46,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(270,47,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(271,47,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(272,47,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(273,47,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(274,47,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(275,47,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(276,47,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(277,48,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(278,48,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(279,48,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(280,48,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(281,48,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(282,48,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(283,48,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(284,49,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(285,49,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(286,49,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(287,49,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(288,49,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(289,49,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(290,49,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(291,50,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(292,50,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(293,50,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(294,50,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(295,50,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(296,50,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(297,50,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(298,51,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(299,51,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(300,51,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(301,51,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(302,51,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(303,51,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(304,51,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(305,52,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(306,52,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(307,52,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(308,52,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(309,52,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(310,52,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(311,52,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(312,53,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(313,53,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(314,53,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(315,53,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(316,53,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(317,53,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(318,53,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(319,54,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(320,54,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(321,54,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(322,54,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(323,54,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(324,54,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(325,54,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(326,55,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(327,55,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(328,55,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(329,55,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(330,55,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(331,55,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(332,55,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(333,56,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(334,56,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(335,56,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(336,56,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(337,56,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(338,56,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(339,56,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(340,57,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(341,57,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(342,57,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(343,57,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(344,57,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(345,57,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:52','2026-10-22 16:49:52'),(346,57,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(347,58,6,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(348,58,7,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(349,58,8,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(350,58,9,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(351,58,10,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(352,58,11,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(353,58,12,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(354,68,16,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(355,68,17,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(356,68,18,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(357,69,16,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(358,69,17,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(359,69,18,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(360,70,16,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(361,70,17,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(362,70,18,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(363,71,16,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(364,71,17,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(365,71,18,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(366,72,16,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(367,72,17,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(368,72,18,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(369,73,16,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(370,73,17,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(371,73,18,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(372,74,16,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(373,74,17,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(374,74,18,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(375,75,16,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(376,75,17,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(377,75,18,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(378,76,16,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(379,76,17,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(380,76,18,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(381,77,16,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(382,77,17,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(383,77,18,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(384,78,16,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(385,78,17,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(386,78,18,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(387,79,19,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(388,80,19,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(389,81,19,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(390,82,19,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(391,83,19,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(392,84,19,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(393,85,19,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(394,86,19,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(395,87,19,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(396,88,19,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(397,89,19,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(398,90,19,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(399,91,19,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(400,92,19,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(401,93,19,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(402,94,19,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(403,95,19,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(404,96,19,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(405,97,19,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(406,98,19,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(407,99,19,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(408,100,19,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(409,101,19,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(410,102,19,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(411,103,19,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(412,104,19,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(413,105,19,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(414,106,19,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(415,107,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(416,107,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(417,108,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(418,108,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(419,109,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(420,109,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(421,110,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(422,110,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(423,111,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(424,111,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(425,112,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(426,112,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(427,113,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(428,113,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(429,114,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(430,114,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(431,115,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(432,115,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(433,116,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(434,116,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(435,117,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(436,117,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(437,118,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(438,118,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(439,119,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(440,119,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(441,120,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(442,120,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(443,121,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(444,121,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(445,122,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(446,122,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(447,123,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(448,123,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(449,124,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(450,124,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(451,125,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(452,125,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(453,126,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(454,126,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(455,127,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(456,127,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(457,128,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(458,128,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(459,129,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(460,129,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(461,130,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(462,130,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(463,131,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(464,131,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(465,132,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(466,132,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(467,133,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(468,133,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(469,134,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(470,134,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(471,135,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(472,135,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(473,136,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(474,136,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(475,137,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(476,137,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(477,138,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(478,138,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(479,139,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(480,139,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(481,140,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(482,140,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(483,141,20,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(484,141,21,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(485,156,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(486,156,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(487,156,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(488,156,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(490,157,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(491,157,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(492,157,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(493,158,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(494,158,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(495,158,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(496,158,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(497,159,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(498,159,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(499,159,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(500,159,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(501,160,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(502,160,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(503,160,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(504,160,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(505,161,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(506,161,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(507,161,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(508,161,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(509,162,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(510,162,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(511,162,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(512,162,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(513,163,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(514,163,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(515,163,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(516,163,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(517,164,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(518,164,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(519,164,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(520,164,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(521,165,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(522,165,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(523,165,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(524,165,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(525,166,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(526,166,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(527,166,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(528,166,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(529,167,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(530,167,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(531,167,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(532,167,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(533,168,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(534,168,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(535,168,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(536,168,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(537,169,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(538,169,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(539,169,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(540,169,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(541,170,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(542,170,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(543,170,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(544,170,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(545,171,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(546,171,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(547,171,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(548,171,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(549,172,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(550,172,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(551,172,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(552,172,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(553,173,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(554,173,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(555,173,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(556,173,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(557,174,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(558,174,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(559,174,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(560,174,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(561,175,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(562,175,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(563,175,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(564,175,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(565,176,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(566,176,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(567,176,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(568,176,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(569,177,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(570,177,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(571,177,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(572,177,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(573,178,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(574,178,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(575,178,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(576,178,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(577,179,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(578,179,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(579,179,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(580,179,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(581,180,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(582,180,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(583,180,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(584,180,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(585,181,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(586,181,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(587,181,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(588,181,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(589,182,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(590,182,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(591,182,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(592,182,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(593,183,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(594,183,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(595,183,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(596,183,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(597,184,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(598,184,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(599,184,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(600,184,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(601,185,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(602,185,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(603,185,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(604,185,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(605,186,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(606,186,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(607,186,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(608,186,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(609,187,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(610,187,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(611,187,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(612,187,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(613,188,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(614,188,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(615,188,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(616,188,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(617,189,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(618,189,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(619,189,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(620,189,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(621,190,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(622,190,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(623,190,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(624,190,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(625,191,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(626,191,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(627,191,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(628,191,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(630,192,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(631,192,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(632,192,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(633,193,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(634,193,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(635,193,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(636,193,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(637,194,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(638,194,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(639,194,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(640,194,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(641,195,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(642,195,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(643,195,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(644,195,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(645,196,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(646,196,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(647,196,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(648,196,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(649,197,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(650,197,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(651,197,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(652,197,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(653,198,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(654,198,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(655,198,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(656,198,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(657,199,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(658,199,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(659,199,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(660,199,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(661,200,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(662,200,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(663,200,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(664,200,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(665,201,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(666,201,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(667,201,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(668,201,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(669,202,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(670,202,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(671,202,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(672,202,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(673,203,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(674,203,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(675,203,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(676,203,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(677,204,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(678,204,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(679,204,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(680,204,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(681,205,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(682,205,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(683,205,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(684,205,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(685,206,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(686,206,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(687,206,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(688,206,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(689,207,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(690,207,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(691,207,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(692,207,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(693,208,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(694,208,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(695,208,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(696,208,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(697,209,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(698,209,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(699,209,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(700,209,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(701,210,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(702,210,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(703,210,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(704,210,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(705,211,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(706,211,28,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(707,211,29,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(708,211,30,1500.00,'2026-07-27',NULL,1,'2026-10-22 16:49:53','2026-10-22 16:49:53'),(713,157,27,1500.00,'2026-07-27',NULL,1,'2026-10-22 17:54:39','2026-10-22 17:54:39'),(714,192,27,2500.00,'2026-10-22',NULL,1,'2026-10-23 13:51:11','2026-10-23 13:51:11'),(715,3,2,6500.00,'2026-10-22',NULL,1,'2026-10-23 13:54:04','2026-10-23 13:54:04');
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
INSERT INTO `user_department` VALUES (1,'Sales Department','2026-09-17 00:59:28','2026-09-17 00:59:28'),(2,'Operations Department','2026-09-17 00:59:28','2026-09-17 00:59:28');
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
INSERT INTO `user_status` VALUES (1,'Active','2026-09-17 00:59:28','2026-09-17 00:59:28'),(2,'Inactive','2026-09-17 00:59:28','2026-09-17 00:59:28'),(3,'Suspended','2026-09-17 00:59:28','2026-09-17 00:59:28'),(4,'Pending','2026-09-17 00:59:28','2026-09-17 00:59:28');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
  `font_size` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Developer','superadmin@email.com',NULL,'side','large',NULL,'$2y$12$u745jmoUKjuNKEKjX0bD8e7o9fE7FGl13X5dobLy.lcx5mgGzhiPG',0,NULL,'1',0,NULL,NULL,'2026-09-17 00:59:26','2026-10-05 05:48:58',NULL,0),(2,'Credit Officer','creditofficer@email.com',NULL,'side','medium',NULL,'$2y$12$MdJK059zorXN.dI6vt6/G.AkJNwd20pXeAPwVjFdBtiCV98bdQ81m',0,NULL,'5',0,NULL,NULL,'2026-09-17 00:59:27','2026-09-17 00:59:27',NULL,0),(3,'Minton Diaz','minton.diaz@email.com',NULL,'side','extra_large',NULL,'$2y$12$SCOgw6l0J1tGYMjDlQZXQO8/6X1GQ1yV2CxOmQPF3bVWrADWuXbKS',0,NULL,'4',0,NULL,NULL,'2026-09-17 00:59:27','2026-10-12 15:36:04',NULL,0),(4,'Fritzie Tangan','fritzie.tangan@kargamine.com.ph',NULL,'side','medium',NULL,'$2y$12$fqK5EXxVyFUtoZXAw/geqOkgkHTBGi3sWWOvt33fIaLOM3ZmuNU.C',0,NULL,'2',0,NULL,NULL,'2026-09-17 00:59:27','2026-10-05 09:20:25',NULL,0),(5,'Eden Palma','eden.palma@karga-container.com',NULL,'side','medium',NULL,'$2y$12$YAsqvBWEfysHi.g2as0YgutyjH.R4TVuxou3DlrtmJ/h7Ex6wh/9S',1,NULL,'6',0,NULL,NULL,'2026-09-17 00:59:27','2026-09-17 00:59:27',NULL,0),(6,'Test Management One','mgmt1@test.kargamine',NULL,'side','medium',NULL,'$2y$12$wWAVnGDwd/gdn/jLZhP.K.aD3Y1YcaIllfoK.h/QluQWSqbqeOYQ6',0,NULL,'2',0,NULL,NULL,'2026-10-19 17:24:19','2026-10-19 17:24:19',1,1),(7,'Test Management Two','mgmt2@test.kargamine',NULL,'side','medium',NULL,'$2y$12$aB00Ay93y5ltNkq2qHKnj.DpnJjIKfWpG.jPsHSFd5VpdlUcSRe1m',0,NULL,'2',0,NULL,NULL,'2026-10-19 17:24:20','2026-10-19 17:24:20',1,0),(8,'Test Management Three','mgmt3@test.kargamine',NULL,'side','medium',NULL,'$2y$12$V0XHFl/YbsDPE.3R719Khe7cxW0Eb48AmDMCAvFJlkKyfAD6hWOB.',0,NULL,'2',0,NULL,NULL,'2026-10-19 17:24:20','2026-10-19 17:24:20',1,0),(9,'Test Relationship Manager One','rm1@test.kargamine',NULL,'side','medium',NULL,'$2y$12$tAL4qsb35xeWKfPNRIYgxedqWXh7EqazUHsPSG67zTliNut0wjOyC',0,NULL,'7',0,NULL,NULL,'2026-10-19 17:24:20','2026-10-19 17:24:20',2,1),(10,'Test Relationship Manager Two','rm2@test.kargamine',NULL,'side','medium',NULL,'$2y$12$rek8C6NLQFdbad9wNnHlw.kjz/fgofnRqOE0koCWnMkkuKsN09Yz6',0,NULL,'7',0,NULL,NULL,'2026-10-19 17:24:20','2026-10-19 17:24:20',2,0),(11,'Test Relationship Manager Three','rm3@test.kargamine',NULL,'side','medium',NULL,'$2y$12$ceSaxOQtHv4y5xSPMB1JleC5YHWQEOUzNpeTsPZXnt283LGfNiyKe',0,NULL,'7',0,NULL,NULL,'2026-10-19 17:24:21','2026-10-19 17:24:21',3,1),(12,'Test CSR One','csr1@test.kargamine',NULL,'side','medium',NULL,'$2y$12$doCDcUCXD1c8M47YjPpHGuMiWfwJGpYRo23G87Icll5Y/Z4EQ3eD2',0,NULL,'8',0,NULL,NULL,'2026-10-19 17:24:21','2026-10-19 17:24:21',2,0),(13,'Test CSR Two','csr2@test.kargamine',NULL,'side','medium',NULL,'$2y$12$P4p7Qa5mijeVOqKY9s53BuMx2Po9PjwcaV1doe38lEK.o5J9n8l5.',0,NULL,'8',0,NULL,NULL,'2026-10-19 17:24:21','2026-10-19 17:24:21',2,0),(14,'Test CSR Three','csr3@test.kargamine',NULL,'side','medium',NULL,'$2y$12$a2V7S4AS/X3KgG3SKnYth..NFSgndU/CZGdW0ndVOehDLNcv29NNq',0,NULL,'8',0,NULL,NULL,'2026-10-19 17:24:22','2026-10-19 17:24:22',3,0);
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
  `tax_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`vat_rate_id`),
  UNIQUE KEY `vat_rates_tax_type_effective_date_unique` (`tax_type`,`effective_date`)
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vessel_status_histories`
--

LOCK TABLES `vessel_status_histories` WRITE;
/*!40000 ALTER TABLE `vessel_status_histories` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vessel_voyages`
--

LOCK TABLES `vessel_voyages` WRITE;
/*!40000 ALTER TABLE `vessel_voyages` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vessels`
--

LOCK TABLES `vessels` WRITE;
/*!40000 ALTER TABLE `vessels` DISABLE KEYS */;
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

-- Dump completed on 2026-10-23  8:01:10

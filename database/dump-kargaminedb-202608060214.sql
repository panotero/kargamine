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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
  `lane_id` bigint(20) unsigned DEFAULT NULL,
  `tariff_rate_id` bigint(20) unsigned DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
INSERT INTO `cache` VALUES ('management_system_cache_7f3072bf378b98d6bbf2f013cff3e287','i:14;',1785767414),('management_system_cache_7f3072bf378b98d6bbf2f013cff3e287:timer','i:1785767414;',1785767414),('management_system_cache_a3affa0d1e1a3c72b78aa984c3367a05','i:5;',1785201511),('management_system_cache_a3affa0d1e1a3c72b78aa984c3367a05:timer','i:1785201511;',1785201511),('management_system_cache_d2bfa8e8b749d2772a21edee7b70a2b3','i:2;',1785953681),('management_system_cache_d2bfa8e8b749d2772a21edee7b70a2b3:timer','i:1785953681;',1785953681),('management_system_cache_de226f3f5dc0c66a464effdc07ca6b1f','i:1;',1785170219),('management_system_cache_de226f3f5dc0c66a464effdc07ca6b1f:timer','i:1785170219;',1785170219),('management_system_cache_f1f70ec40aaa556905d4a030501c0ba4','i:3;',1785953667),('management_system_cache_f1f70ec40aaa556905d4a030501c0ba4:timer','i:1785953667;',1785953667);
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
INSERT INTO `cargo_yards` VALUES (1,'Batangas - Bauan',1,'2026-08-05 06:41:56','2026-08-05 06:41:56'),(2,'CDO - Villanueva',1,'2026-08-05 06:41:56','2026-08-05 06:41:56'),(3,'Cebu - Talisay',1,'2026-08-05 06:41:56','2026-08-05 06:41:56'),(4,'Masbate - Mobo',1,'2026-08-05 06:41:56','2026-08-05 06:41:56'),(5,'Palawan - Brooke\'s Point',1,'2026-08-05 06:41:56','2026-08-05 06:41:56'),(6,'Palawan - Coron',1,'2026-08-05 06:41:56','2026-08-05 06:41:56'),(7,'Palawan - Puerto Princesa',1,'2026-08-05 06:41:56','2026-08-05 06:41:56');
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
INSERT INTO `charge_types` VALUES (1,'WHARF','Wharfage','PORT',1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(2,'ARRASTRE','Arrastre Charge','PORT',1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(3,'THC','Terminal Handling Charge','PORT',1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(4,'DOC_FEE','Documentation Fee','GENERAL',1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(5,'INS_FEE','Insurance Fee','GENERAL',1,'2026-07-28 11:39:43','2026-07-28 11:39:43');
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_addresses`
--

LOCK TABLES `client_addresses` WRITE;
/*!40000 ALTER TABLE `client_addresses` DISABLE KEYS */;
INSERT INTO `client_addresses` VALUES (7,8,'Branch',1,'123','qwe','qwe','Agtangao','Bangued','Abra','Philippines','123','2026-08-05 16:02:39','2026-08-05 16:02:39'),(9,10,'Branch',1,'123','123','qwe','Agtangao','Bangued','Abra','Philippines','123','2026-08-05 16:18:52','2026-08-05 16:18:52'),(11,12,NULL,1,'12','test','test','Agtangao','Bangued','Abra','Philippines','123','2026-08-05 17:56:39','2026-08-05 17:56:39');
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
  `quantity` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_ancillary_services_client_id_foreign` (`client_id`),
  CONSTRAINT `client_ancillary_services_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `client_masters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_ancillary_services`
--

LOCK TABLES `client_ancillary_services` WRITE;
/*!40000 ALTER TABLE `client_ancillary_services` DISABLE KEYS */;
INSERT INTO `client_ancillary_services` VALUES (3,10,'Amendment Fee','CDO - Villanueva','day/s',12.00,'2026-08-05 16:20:11','2026-08-05 16:20:11');
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_commodity_declared_values`
--

LOCK TABLES `client_commodity_declared_values` WRITE;
/*!40000 ALTER TABLE `client_commodity_declared_values` DISABLE KEYS */;
INSERT INTO `client_commodity_declared_values` VALUES (4,8,'test',123123.00,'2026-08-05 16:04:05','2026-08-05 16:04:05'),(5,8,'test',123123.00,'2026-08-05 16:04:05','2026-08-05 16:04:05'),(6,8,'test',123123.00,'2026-08-05 16:04:05','2026-08-05 16:04:05'),(7,10,'test',123.00,'2026-08-05 16:20:00','2026-08-05 16:20:00'),(8,10,'test',123.00,'2026-08-05 16:20:00','2026-08-05 16:20:00'),(9,10,'test',123.00,'2026-08-05 16:20:00','2026-08-05 16:20:00');
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
INSERT INTO `client_contact_addresses` VALUES (1,2,NULL,1,'123','123','qwe','Agtangao','Bangued','Abra','Philippines','123','2026-08-05 16:03:15','2026-08-05 16:03:15'),(2,3,'Branch',1,'123','123','testing','Abilan','Buenavista','Agusan del Norte','Philippines','TESTING','2026-08-05 16:19:33','2026-08-05 16:19:33');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_contacts`
--

LOCK TABLES `client_contacts` WRITE;
/*!40000 ALTER TABLE `client_contacts` DISABLE KEYS */;
INSERT INTO `client_contacts` VALUES (2,8,'test','2026-08-05 16:03:15','2026-08-05 16:03:15','Mr.','test','test','Male','test','123','personal','123','personal','test@email.com','personal'),(3,10,'test','2026-08-05 16:19:33','2026-08-05 16:19:33','Mr.','test','test','Male','test','123','personal','123','personal','test@email.com','personal');
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
  `min_van_qty` int(10) unsigned DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_contract_rates`
--

LOCK TABLES `client_contract_rates` WRITE;
/*!40000 ALTER TABLE `client_contract_rates` DISABLE KEYS */;
INSERT INTO `client_contract_rates` VALUES (3,2,2,22,2,1,1,4,12,123.00,'percentage',12.00,108.24,'2026-08-05 16:20:41','2026-08-05 16:20:41');
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_contracts`
--

LOCK TABLES `client_contracts` WRITE;
/*!40000 ALTER TABLE `client_contracts` DISABLE KEYS */;
INSERT INTO `client_contracts` VALUES (2,'060a6f0f-8f0b-4331-90fa-21e4f592b661','CCT-202608-0001',10,15,'2026-07-31','2026-07-31','2026-08-01',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2026-08-05 16:20:41','2026-08-05 16:20:41');
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_finance`
--

LOCK TABLES `client_finance` WRITE;
/*!40000 ALTER TABLE `client_finance` DISABLE KEYS */;
INSERT INTO `client_finance` VALUES (5,8,'test','7 Days','2026-08-05 16:04:05','2026-08-05 16:04:05','123','123qwe','123','123','qweqwe','2026-07-31','qweqwe',12.00,12.00,'Credit','Automatic'),(7,10,'test',NULL,'2026-08-05 16:20:00','2026-08-05 16:20:00','123','123123','123','123','123','2026-08-01','test',12.00,12.00,'Cash','Manual'),(8,12,'test',NULL,'2026-08-05 17:56:43','2026-08-05 17:56:43',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_masters`
--

LOCK TABLES `client_masters` WRITE;
/*!40000 ALTER TABLE `client_masters` DISABLE KEYS */;
INSERT INTO `client_masters` VALUES (8,'a26e4a41-823a-4a67-8c60-9f9045a5d6eb',10,'CM-2026-0004','test','testes','Direct Client','Key Account',0,'Agriculture',1,NULL,3,1,1,'2026-08-05 16:02:39','2026-08-05 16:04:05'),(10,'a26e500e-dffc-41de-ae5a-48006198ef53',14,'CM-2026-0005','test','trews','Broker / Agent','Key Account',0,'Agriculture',1,NULL,4,1,1,'2026-08-05 16:18:52','2026-08-05 16:20:11'),(12,'a26e7307-ca3d-4605-adb4-243bfd8ec080',13,'CM-2026-0002','test','test',NULL,NULL,0,'Agriculture',1,NULL,3,0,1,'2026-08-05 17:56:39','2026-08-05 17:56:43');
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
  `min_van_qty` int(10) unsigned DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_proposal_rates`
--

LOCK TABLES `client_proposal_rates` WRITE;
/*!40000 ALTER TABLE `client_proposal_rates` DISABLE KEYS */;
INSERT INTO `client_proposal_rates` VALUES (9,12,2,22,2,1,2,5,NULL,123.00,NULL,0.00,123.00,'2026-08-03 14:37:57','2026-08-03 14:37:57'),(11,14,2,22,2,1,1,4,12,123.00,'percentage',10.00,110.70,'2026-08-03 16:04:35','2026-08-03 16:04:35'),(12,15,2,22,2,1,1,4,12,123.00,'percentage',12.00,108.24,'2026-08-05 16:18:16','2026-08-05 16:18:16');
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_proposals`
--

LOCK TABLES `client_proposals` WRITE;
/*!40000 ALTER TABLE `client_proposals` DISABLE KEYS */;
INSERT INTO `client_proposals` VALUES (12,'f1e524ba-7e8c-4211-9ba3-681d0d307565','CPR-202608-0001',12,13,4,'[\"http:\\/\\/kargamine_prototype.test\\/uploads\\/doc\\/pdf\\/7edc0b70db0b8a995b95643dc22cbd965aac4b14dfd3162d2e6d20fe2408d5fb.pdf\"]','2026-08-03 14:38:26',1,'2026-08-03 14:38:05',NULL,1,'2026-08-03 14:37:56','2026-08-05 17:56:39'),(14,'df84cc3c-157d-4022-91ed-dfc5cfde8e00','CPR-202608-0003',8,10,4,'[\"http:\\/\\/kargamine_prototype.test\\/uploads\\/doc\\/pdf\\/a82202d6ba99361f33ef49773f46be5f7ff5dcd8854397756b71b86d43f44660.pdf\"]','2026-08-05 16:02:11',1,'2026-08-05 16:01:49',NULL,3,'2026-08-03 16:04:35','2026-08-05 16:02:39'),(15,'a4229dd3-163d-4ca5-a4e2-47d6300f783b','CPR-202608-0004',10,14,4,'[\"http:\\/\\/kargamine_prototype.test\\/uploads\\/doc\\/pdf\\/93313c68dd217a18db728f3f3d5048a84166cfee78b6046cf77ada4d36af054e.pdf\"]','2026-08-05 16:18:31',1,'2026-08-05 16:18:25',NULL,1,'2026-08-05 16:18:16','2026-08-05 16:18:52');
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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `container_asset_location_history`
--

LOCK TABLES `container_asset_location_history` WRITE;
/*!40000 ALTER TABLE `container_asset_location_history` DISABLE KEYS */;
INSERT INTO `container_asset_location_history` VALUES (1,84,22,NULL,'Under Repair','manual_relocation',1,'2026-07-28 12:19:08','2026-07-28 12:19:08','2026-07-28 12:19:08'),(2,84,22,NULL,'Available','manual_relocation',1,'2026-07-28 12:19:12','2026-07-28 12:19:12','2026-07-28 12:19:12'),(3,86,5,NULL,'Available','manual_relocation',1,'2026-07-28 12:19:15','2026-07-28 12:19:15','2026-07-28 12:19:15'),(4,75,6,NULL,'Under Repair','manual_relocation',1,'2026-07-28 12:19:46','2026-07-28 12:19:46','2026-07-28 12:19:46'),(5,75,6,NULL,'Available','manual_relocation',1,'2026-07-28 12:19:47','2026-07-28 12:19:47','2026-07-28 12:19:47'),(6,61,4,NULL,'Available','manual_relocation',1,'2026-07-28 12:19:55','2026-07-28 12:19:55','2026-07-28 12:19:55'),(7,29,1,NULL,'Available','manual_relocation',1,'2026-07-28 12:19:58','2026-07-28 12:19:58','2026-07-28 12:19:58'),(8,23,1,NULL,'Available','manual_relocation',1,'2026-07-28 12:20:01','2026-07-28 12:20:01','2026-07-28 12:20:01'),(9,19,4,NULL,'Available','manual_relocation',1,'2026-07-28 12:20:05','2026-07-28 12:20:05','2026-07-28 12:20:05'),(10,14,5,NULL,'Available','manual_relocation',1,'2026-07-28 12:20:08','2026-07-28 12:20:08','2026-07-28 12:20:08'),(11,6,22,NULL,'Available','manual_relocation',1,'2026-07-28 12:20:10','2026-07-28 12:20:10','2026-07-28 12:20:10'),(12,16,10,NULL,'Booked','booking_assignment',1,'2026-07-28 12:20:41','2026-07-28 12:20:41','2026-07-28 12:20:41'),(13,16,10,NULL,'Available','booking_release',1,'2026-07-28 12:20:45','2026-07-28 12:20:45','2026-07-28 12:20:45'),(14,15,6,NULL,'Booked','booking_assignment',1,'2026-07-28 12:20:45','2026-07-28 12:20:45','2026-07-28 12:20:45'),(15,15,6,NULL,'Available','booking_release',1,'2026-07-28 12:21:06','2026-07-28 12:21:06','2026-07-28 12:21:06'),(16,13,4,NULL,'Booked','booking_assignment',1,'2026-07-28 12:21:06','2026-07-28 12:21:06','2026-07-28 12:21:06'),(17,13,4,NULL,'Available','booking_release',1,'2026-07-28 12:22:32','2026-07-28 12:22:32','2026-07-28 12:22:32'),(18,12,22,NULL,'Booked','booking_assignment',1,'2026-07-28 12:22:32','2026-07-28 12:22:32','2026-07-28 12:22:32');
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
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `container_assets`
--

LOCK TABLES `container_assets` WRITE;
/*!40000 ALTER TABLE `container_assets` DISABLE KEYS */;
INSERT INTO `container_assets` VALUES (1,4,'MAEU0000001',1,4,NULL,'2026-07-27 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(2,4,'CMAU0000002',1,5,NULL,'2026-07-26 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(3,4,'HLXU0000003',5,6,NULL,'2026-07-25 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(4,4,'ONEU0000004',6,10,NULL,'2026-07-24 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(5,4,'EGHU0000005',1,1,NULL,'2026-07-23 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(6,4,'TCLU0000006',1,22,NULL,'2026-07-28 12:20:10',NULL,'2026-07-28 12:18:34','2026-07-28 12:20:10'),(7,4,'OOLU0000007',1,4,NULL,'2026-07-21 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(8,4,'MSCU0000008',1,5,NULL,'2026-07-20 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(9,5,'MAEU0000009',5,6,NULL,'2026-07-19 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(10,5,'CMAU0000010',2,10,NULL,'2026-07-18 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(11,5,'HLXU0000011',1,1,NULL,'2026-07-17 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(12,5,'ONEU0000012',2,22,NULL,'2026-07-28 12:22:32',NULL,'2026-07-28 12:18:34','2026-07-28 12:22:32'),(13,5,'EGHU0000013',1,4,NULL,'2026-07-28 12:22:32',NULL,'2026-07-28 12:18:34','2026-07-28 12:22:32'),(14,5,'TCLU0000014',1,5,NULL,'2026-07-28 12:20:08',NULL,'2026-07-28 12:18:34','2026-07-28 12:20:08'),(15,5,'OOLU0000015',1,6,NULL,'2026-07-28 12:21:06',NULL,'2026-07-28 12:18:34','2026-07-28 12:21:06'),(16,5,'MSCU0000016',1,10,NULL,'2026-07-28 12:20:45',NULL,'2026-07-28 12:18:34','2026-07-28 12:20:45'),(17,6,'MAEU0000017',2,1,NULL,'2026-07-11 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(18,6,'CMAU0000018',1,22,NULL,'2026-07-10 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(19,6,'HLXU0000019',1,4,NULL,'2026-07-28 12:20:05',NULL,'2026-07-28 12:18:34','2026-07-28 12:20:05'),(20,6,'ONEU0000020',1,5,NULL,'2026-07-08 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(21,6,'EGHU0000021',1,6,NULL,'2026-07-07 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(22,6,'TCLU0000022',1,10,NULL,'2026-07-06 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(23,6,'OOLU0000023',1,1,NULL,'2026-07-28 12:20:01',NULL,'2026-07-28 12:18:34','2026-07-28 12:20:01'),(24,6,'MSCU0000024',1,22,NULL,'2026-07-04 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(25,7,'MAEU0000025',1,4,NULL,'2026-07-03 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(26,7,'CMAU0000026',1,5,NULL,'2026-07-02 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(27,7,'HLXU0000027',1,6,NULL,'2026-07-01 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(28,7,'ONEU0000028',1,10,NULL,'2026-06-30 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(29,7,'EGHU0000029',1,1,NULL,'2026-07-28 12:19:58',NULL,'2026-07-28 12:18:34','2026-07-28 12:19:58'),(30,7,'TCLU0000030',1,22,NULL,'2026-07-28 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(31,7,'OOLU0000031',3,4,NULL,'2026-07-27 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(32,7,'MSCU0000032',1,5,NULL,'2026-07-26 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(33,8,'MAEU0000033',1,6,NULL,'2026-07-25 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(34,8,'CMAU0000034',1,10,NULL,'2026-07-24 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(35,8,'HLXU0000035',2,1,NULL,'2026-07-23 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(36,8,'ONEU0000036',2,22,NULL,'2026-07-22 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(37,8,'EGHU0000037',2,4,NULL,'2026-07-21 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(38,8,'TCLU0000038',1,5,NULL,'2026-07-20 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(39,8,'OOLU0000039',2,6,NULL,'2026-07-19 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(40,8,'MSCU0000040',1,10,NULL,'2026-07-18 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(41,9,'MAEU0000041',1,1,NULL,'2026-07-17 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(42,9,'CMAU0000042',1,22,NULL,'2026-07-16 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(43,9,'HLXU0000043',3,4,NULL,'2026-07-15 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(44,9,'ONEU0000044',1,5,NULL,'2026-07-14 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(45,9,'EGHU0000045',1,6,NULL,'2026-07-13 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(46,9,'TCLU0000046',1,10,NULL,'2026-07-12 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(47,9,'OOLU0000047',3,1,NULL,'2026-07-11 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(48,9,'MSCU0000048',1,22,NULL,'2026-07-10 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(49,10,'MAEU0000049',2,4,NULL,'2026-07-09 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(50,10,'CMAU0000050',2,5,NULL,'2026-07-08 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(51,10,'HLXU0000051',1,6,NULL,'2026-07-07 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(52,10,'ONEU0000052',1,10,NULL,'2026-07-06 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(53,10,'EGHU0000053',6,1,NULL,'2026-07-05 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(54,10,'TCLU0000054',2,22,NULL,'2026-07-04 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(55,10,'OOLU0000055',1,4,NULL,'2026-07-03 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(56,10,'MSCU0000056',1,5,NULL,'2026-07-02 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(57,11,'MAEU0000057',2,6,NULL,'2026-07-01 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(58,11,'CMAU0000058',1,10,NULL,'2026-06-30 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(59,11,'HLXU0000059',1,1,NULL,'2026-06-29 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(60,11,'ONEU0000060',3,22,NULL,'2026-07-28 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(61,11,'EGHU0000061',1,4,NULL,'2026-07-28 12:19:55',NULL,'2026-07-28 12:18:34','2026-07-28 12:19:55'),(62,11,'TCLU0000062',1,5,NULL,'2026-07-26 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(63,11,'OOLU0000063',1,6,NULL,'2026-07-25 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(64,11,'MSCU0000064',1,10,NULL,'2026-07-24 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(65,12,'MAEU0000065',1,1,NULL,'2026-07-23 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(66,12,'CMAU0000066',3,22,NULL,'2026-07-22 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(67,12,'HLXU0000067',2,4,NULL,'2026-07-21 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(68,12,'ONEU0000068',3,5,NULL,'2026-07-20 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(69,12,'EGHU0000069',2,6,NULL,'2026-07-19 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(70,12,'TCLU0000070',3,10,NULL,'2026-07-18 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(71,12,'OOLU0000071',1,1,NULL,'2026-07-17 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(72,12,'MSCU0000072',3,22,NULL,'2026-07-16 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(73,13,'MAEU0000073',3,4,NULL,'2026-07-15 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(74,13,'CMAU0000074',1,5,NULL,'2026-07-14 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(75,13,'HLXU0000075',1,6,NULL,'2026-07-28 12:19:47',NULL,'2026-07-28 12:18:34','2026-07-28 12:19:47'),(76,13,'ONEU0000076',1,10,NULL,'2026-07-12 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(77,13,'EGHU0000077',3,1,NULL,'2026-07-11 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(78,13,'TCLU0000078',1,22,NULL,'2026-07-10 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(79,13,'OOLU0000079',1,4,NULL,'2026-07-09 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(80,13,'MSCU0000080',1,5,NULL,'2026-07-08 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(81,14,'MAEU0000081',1,6,NULL,'2026-07-07 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(82,14,'CMAU0000082',2,10,NULL,'2026-07-06 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(83,14,'HLXU0000083',1,1,NULL,'2026-07-05 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(84,14,'ONEU0000084',1,22,NULL,'2026-07-28 12:19:12',NULL,'2026-07-28 12:18:34','2026-07-28 12:19:12'),(85,14,'EGHU0000085',2,4,NULL,'2026-07-03 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(86,14,'TCLU0000086',1,5,NULL,'2026-07-28 12:19:15',NULL,'2026-07-28 12:18:34','2026-07-28 12:19:15'),(87,14,'OOLU0000087',1,6,NULL,'2026-07-01 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34'),(88,14,'MSCU0000088',1,10,NULL,'2026-06-30 12:18:34',NULL,'2026-07-28 12:18:34','2026-07-28 12:18:34');
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `container_class`
--

LOCK TABLES `container_class` WRITE;
/*!40000 ALTER TABLE `container_class` DISABLE KEYS */;
INSERT INTO `container_class` VALUES (1,'A',NULL,'2026-07-28 12:18:33'),(2,'B',NULL,'2026-07-28 12:18:33'),(3,'C',NULL,'2026-07-28 12:18:33'),(4,'D',NULL,'2026-07-28 12:18:33'),(5,'Standard','2026-07-28 11:39:43','2026-07-28 11:39:43'),(6,'High Cube','2026-07-28 11:39:43','2026-07-28 11:39:43');
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `container_size`
--

LOCK TABLES `container_size` WRITE;
/*!40000 ALTER TABLE `container_size` DISABLE KEYS */;
INSERT INTO `container_size` VALUES (1,'10-FOOTER',NULL,'2026-07-28 12:18:33'),(2,'20-FOOTER',NULL,'2026-07-28 12:18:33'),(3,'40-FOOTER STD',NULL,'2026-07-28 12:18:33'),(4,'40-FOOTER HC',NULL,'2026-07-28 12:18:33'),(5,'20FT','2026-07-28 11:39:43','2026-07-28 11:39:43'),(6,'40FT','2026-07-28 11:39:43','2026-07-28 11:39:43');
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
INSERT INTO `container_type` VALUES (1,'CONVAN',NULL,'2026-07-28 12:18:33'),(2,'FLATRACK (PLATFORM)',NULL,'2026-07-28 12:18:33'),(3,'REEFER',NULL,'2026-07-28 12:18:33'),(4,'HIGH CUBE',NULL,'2026-07-28 12:18:33'),(5,'CATTLE VAN',NULL,'2026-07-28 12:18:33'),(6,'TANK (ISO TANK)',NULL,'2026-07-28 12:18:33'),(7,'ROLLING CARGO',NULL,'2026-07-28 12:18:33'),(8,'SPECIAL CONTAINERS',NULL,'2026-07-28 12:18:33'),(9,'OPEN-TOP VAN',NULL,'2026-07-28 12:18:33');
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `container_variants`
--

LOCK TABLES `container_variants` WRITE;
/*!40000 ALTER TABLE `container_variants` DISABLE KEYS */;
INSERT INTO `container_variants` VALUES (4,2,1,1,1,'2026-07-27 11:23:06','2026-07-27 11:23:06'),(5,2,1,2,1,'2026-07-27 11:23:06','2026-07-27 11:23:06'),(6,2,1,4,1,'2026-07-27 11:23:06','2026-07-27 11:23:06'),(7,2,1,3,1,'2026-07-27 11:23:06','2026-07-27 11:23:06'),(8,2,5,5,1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(9,2,5,6,1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(10,2,6,6,1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(11,3,5,5,1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(12,3,5,6,1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(13,4,5,5,1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(14,4,5,6,1,'2026-07-28 11:39:43','2026-07-28 11:39:43');
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `containers`
--

LOCK TABLES `containers` WRITE;
/*!40000 ALTER TABLE `containers` DISABLE KEYS */;
INSERT INTO `containers` VALUES (2,'CV','Container Van',1,'2026-07-27 11:23:06','2026-07-27 11:23:06'),(3,'RF','Reefer Van',1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(4,'FR','Flat Rack',1,'2026-07-28 11:39:43','2026-07-28 11:39:43');
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_company_info`
--

LOCK TABLES `crm_company_info` WRITE;
/*!40000 ALTER TABLE `crm_company_info` DISABLE KEYS */;
INSERT INTO `crm_company_info` VALUES (1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-07-27 11:19:32','2026-07-27 11:19:32'),(2,4,'qweqwe','Distributor','qweqwe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-07-27 15:09:13','2026-07-27 15:09:50'),(3,6,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-07-27 15:37:16','2026-07-27 15:37:16'),(6,10,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-07-27 15:59:50','2026-07-27 15:59:50'),(7,11,'test','Distributor','test',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-07-28 17:32:34','2026-07-28 17:32:34'),(8,12,'est','Distributor','test',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-07-28 17:50:48','2026-07-28 17:50:48'),(9,13,'test','Distributor','test','Mr.','test','test','test','Male','test','1231 23','personal','123123','personal','test@email.com','personal','2026-07-28 17:52:40','2026-07-28 17:54:34'),(10,14,'test',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-05 16:17:41','2026-08-05 16:17:41');
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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_lead_addresses`
--

LOCK TABLES `crm_lead_addresses` WRITE;
/*!40000 ALTER TABLE `crm_lead_addresses` DISABLE KEYS */;
INSERT INTO `crm_lead_addresses` VALUES (1,1,'Branch',1,'12313','qweqwe','qweqew','Agtangao','Bangued','Abra','Philippines','123123','2026-07-27 11:19:32','2026-07-27 11:19:32'),(8,4,NULL,1,'123','qwe','qwe','Amti','Boliney','Abra','Philippines','123','2026-07-27 15:11:56','2026-07-27 15:11:56'),(9,6,NULL,1,'12','12','12','Agtangao','Bangued','Abra','Philippines','123123','2026-07-27 15:37:16','2026-07-27 15:37:16'),(10,10,'Branch',1,'123','qwe','qwe','Agtangao','Bangued','Abra','Philippines','123','2026-07-27 15:59:50','2026-07-27 15:59:50'),(11,11,NULL,1,'12','test','test','Abilan','Buenavista','Agusan del Norte','Philippines','123','2026-07-28 17:32:34','2026-07-28 17:32:34'),(12,12,NULL,1,'12','test','test','Agtangao','Bangued','Abra','Philippines','123','2026-07-28 17:50:48','2026-07-28 17:50:48'),(16,13,NULL,1,'12','test','test','Agtangao','Bangued','Abra','Philippines','123','2026-07-28 17:54:34','2026-07-28 17:54:34'),(17,14,'Branch',1,'123','123','qwe','Agtangao','Bangued','Abra','Philippines','123','2026-08-05 16:17:41','2026-08-05 16:17:41');
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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_lead_containers`
--

LOCK TABLES `crm_lead_containers` WRITE;
/*!40000 ALTER TABLE `crm_lead_containers` DISABLE KEYS */;
INSERT INTO `crm_lead_containers` VALUES (1,1,'CV',2,22,NULL,NULL,'Container Van (CV)',12,123.00,'Weekly','qwe',NULL,1,NULL,2,NULL,NULL,NULL,'DOOR','DOOR',NULL,0,NULL,'qweqwe','qweqwe','2026-07-27 11:19:55','2026-07-27 11:19:55'),(2,1,'CV',2,22,NULL,NULL,'Container Van (CV)',12,123.00,'Weekly','qweqew',NULL,1,NULL,4,NULL,NULL,NULL,'DOOR','DOOR',NULL,0,NULL,'qweqwe','qweqwe','2026-07-27 11:20:20','2026-07-27 11:20:20'),(10,4,'CV',2,22,NULL,NULL,'Container Van (CV)',12,123.00,'Weekly','qwe',NULL,1,NULL,2,NULL,NULL,NULL,'DOOR','DOOR',NULL,0,NULL,'qwe','qwe','2026-07-27 15:11:57','2026-07-27 15:11:57'),(11,6,'CV',2,22,NULL,NULL,'Container Van (CV)',12,123.00,'Weekly','qweqwe',NULL,1,NULL,2,NULL,NULL,NULL,'PIER','DOOR',NULL,0,NULL,'qweqew','qweqwe','2026-07-27 15:37:35','2026-07-27 15:37:35'),(12,10,'CV',2,22,NULL,NULL,'Container Van (CV)',12,123.00,'Weekly','qqweq',NULL,2,NULL,2,NULL,NULL,NULL,'DOOR','DOOR',NULL,0,NULL,'qwe','qwe','2026-07-27 16:00:13','2026-07-27 16:00:13'),(13,11,'CV',NULL,NULL,NULL,NULL,'Container Van (CV)',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-07-28 17:32:36','2026-07-28 17:32:36'),(16,13,'CV',2,22,NULL,NULL,'Container Van (CV)',12,123.00,'Weekly','test',NULL,1,NULL,2,NULL,NULL,NULL,'DOOR','DOOR',NULL,0,NULL,'test','test','2026-07-28 17:54:35','2026-07-28 17:54:35'),(17,14,'CV',2,22,NULL,NULL,'Container Van (CV)',123,123.00,'Weekly','qweqeq',NULL,1,NULL,1,NULL,NULL,NULL,'DOOR','DOOR',NULL,0,NULL,'123qwe','qweqweqwe','2026-08-05 16:18:04','2026-08-05 16:18:04');
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_leads`
--

LOCK TABLES `crm_leads` WRITE;
/*!40000 ALTER TABLE `crm_leads` DISABLE KEYS */;
INSERT INTO `crm_leads` VALUES (1,'a25bca26-3c5c-48ab-8742-10e13d16e47c','qweqeq@email.com','personal','1231 23','qweqwe','personal','personal','qweqwe',5,'CM-2026-0001','individual',NULL,'qweqwe',NULL,NULL,NULL,2,1,'Cold Call',3,NULL,NULL,'2026-07-27 11:24:59','2026-07-27 11:19:32','2026-07-27 11:24:59'),(4,'a25c1c4a-3b02-4888-b4e9-dd7beda00663','qqweqweq@email.com','personal','1231 23','123123','personal','personal','wqeqqew',3,NULL,'corporate',NULL,'qweqwe',NULL,NULL,NULL,2,1,'Cold Call',4,NULL,NULL,'2026-07-27 15:11:56','2026-07-27 15:09:13','2026-07-27 15:11:57'),(6,'a25c2653-3bd6-4efc-b8f5-197f778730f1','qqweqweq@email.com','personal','1231 23','123123','personal','personal','qqwqeqweqwe',5,'CM-2026-0003','individual',NULL,'qweqew',NULL,NULL,NULL,2,1,'qweqwe',4,NULL,NULL,'2026-08-03 16:23:17','2026-07-27 15:37:16','2026-08-03 16:23:17'),(10,'a25c2e64-d5e1-41fe-85a2-5924822a9999','qqweqweq@email.com','personal','123','test','personal','personal','test',5,'CM-2026-0004','individual',NULL,'test',NULL,NULL,NULL,2,1,'test',4,NULL,NULL,'2026-08-05 16:02:39','2026-07-27 15:59:50','2026-08-05 16:02:39'),(11,'a25e528b-3cb0-4d9a-aa40-e6869f0fe3b8','test@email.com','personal','1231 23','test','personal','personal','test',3,NULL,'corporate','Mr.','test','test','test','Male',2,1,'Cold Call',1,NULL,NULL,'2026-07-28 17:32:36','2026-07-28 17:32:34','2026-07-28 17:32:36'),(12,'a25e5910-11cc-4da2-8b22-fd21dfce7a7d','test@email.com','personal','1231 231 23','123123','personal','personal','123',1,NULL,'corporate','Mr.','test','test','test','Rather not say',1,0,'Social Media',1,NULL,NULL,'2026-07-28 17:50:48','2026-07-28 17:50:48','2026-07-28 17:50:48'),(13,'a25e59ba-91c0-4b08-a043-1ef25be4f005','test@email.com',NULL,'1231 231 23',NULL,'personal',NULL,'test',5,'CM-2026-0002','corporate','Mr.','test','test','test','Female',2,1,'Cold Call',1,NULL,NULL,'2026-08-05 17:56:39','2026-07-28 17:52:40','2026-08-05 17:56:39'),(14,'a26e4fa2-b93c-4d10-880c-91a7f0761fa7','test@email.com','personal','123','123','personal','personal','test',5,'CM-2026-0005','individual','Mr.','test',NULL,'test','Male',2,1,'Cold Call',1,NULL,NULL,'2026-08-05 16:18:52','2026-08-05 16:17:41','2026-08-05 16:18:52');
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
INSERT INTO `customer_type` VALUES (1,'SHIPPER',NULL,'2026-07-28 12:18:33'),(2,'CONSIGNEE',NULL,'2026-07-28 12:18:33'),(3,'SHIPPER-CONSIGNEE',NULL,'2026-07-28 12:18:33');
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
INSERT INTO `delivery_types` VALUES (1,'DD','Door-Door',1,1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(2,'DP','Door-Pier',1,0,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(3,'PD','Pier-Door',0,1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(4,'PP','Pier-Pier',0,0,'2026-07-28 11:39:43','2026-07-28 11:39:43');
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
INSERT INTO `general_charges` VALUES (1,4,250.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(2,5,150.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44');
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `handling_fees`
--

LOCK TABLES `handling_fees` WRITE;
/*!40000 ALTER TABLE `handling_fees` DISABLE KEYS */;
INSERT INTO `handling_fees` VALUES (1,22,750.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(2,4,750.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(3,5,750.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(4,6,750.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(5,10,750.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(6,1,750.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44');
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (8,'default','{\"uuid\":\"88999e41-93ae-4235-9f93-cc445f7cf64a\",\"displayName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"command\":\"O:31:\\\"App\\\\Jobs\\\\SendApplicationMailJob\\\":2:{s:10:\\\"\\u0000*\\u0000payload\\\";a:4:{s:7:\\\"subject\\\";s:17:\\\"New Lead — test\\\";s:5:\\\"title\\\";s:16:\\\"New lead created\\\";s:7:\\\"message\\\";s:41:\\\"Kargamine User added a new lead — test.\\\";s:6:\\\"button\\\";a:2:{s:3:\\\"url\\\";s:40:\\\"http:\\/\\/kargamine_prototype.test\\/page_crm\\\";s:4:\\\"text\\\";s:11:\\\"View in CRM\\\";}}s:9:\\\"\\u0000*\\u0000userId\\\";i:14;}\"}}',0,NULL,1785167990,1785167990),(9,'default','{\"uuid\":\"d00e6ae7-7978-4e0a-9e75-a6314f4504b0\",\"displayName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"command\":\"O:31:\\\"App\\\\Jobs\\\\SendApplicationMailJob\\\":2:{s:10:\\\"\\u0000*\\u0000payload\\\";a:4:{s:7:\\\"subject\\\";s:46:\\\"Your proposal was approved — CPR-202607-0005\\\";s:5:\\\"title\\\";s:17:\\\"Proposal approved\\\";s:7:\\\"message\\\";s:43:\\\"CPR-202607-0005 was approved by Eden Palma.\\\";s:6:\\\"button\\\";a:2:{s:3:\\\"url\\\";s:46:\\\"http:\\/\\/kargamine_prototype.test\\/page_proposals\\\";s:4:\\\"text\\\";s:13:\\\"View Proposal\\\";}}s:9:\\\"\\u0000*\\u0000userId\\\";i:4;}\"}}',0,NULL,1785169236,1785169236),(10,'default','{\"uuid\":\"52117dea-6058-40f8-9fb2-93dd25e44f0f\",\"displayName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"command\":\"O:31:\\\"App\\\\Jobs\\\\SendApplicationMailJob\\\":2:{s:10:\\\"\\u0000*\\u0000payload\\\";a:4:{s:7:\\\"subject\\\";s:46:\\\"Your proposal was approved — CPR-202608-0001\\\";s:5:\\\"title\\\";s:17:\\\"Proposal approved\\\";s:7:\\\"message\\\";s:42:\\\"CPR-202608-0001 was approved by Developer.\\\";s:6:\\\"button\\\";a:2:{s:3:\\\"url\\\";s:46:\\\"http:\\/\\/kargamine_prototype.test\\/page_proposals\\\";s:4:\\\"text\\\";s:13:\\\"View Proposal\\\";}}s:9:\\\"\\u0000*\\u0000userId\\\";i:1;}\"}}',0,NULL,1785767885,1785767885),(11,'default','{\"uuid\":\"9b3b71fa-397a-4955-9a3a-b2e97cb697ea\",\"displayName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"command\":\"O:31:\\\"App\\\\Jobs\\\\SendApplicationMailJob\\\":2:{s:10:\\\"\\u0000*\\u0000payload\\\";a:4:{s:7:\\\"subject\\\";s:35:\\\"Approval needed — CPR-202608-0002\\\";s:5:\\\"title\\\";s:31:\\\"Proposal awaiting your approval\\\";s:7:\\\"message\\\";s:55:\\\"CPR-202608-0002 for Kargamine User needs your approval.\\\";s:6:\\\"button\\\";a:2:{s:3:\\\"url\\\";s:46:\\\"http:\\/\\/kargamine_prototype.test\\/page_proposals\\\";s:4:\\\"text\\\";s:13:\\\"View Proposal\\\";}}s:9:\\\"\\u0000*\\u0000userId\\\";i:14;}\"}}',0,NULL,1785772860,1785772860),(12,'default','{\"uuid\":\"53d4bf91-8be9-43fb-ba7c-0164b4cbe00c\",\"displayName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"command\":\"O:31:\\\"App\\\\Jobs\\\\SendApplicationMailJob\\\":2:{s:10:\\\"\\u0000*\\u0000payload\\\";a:4:{s:7:\\\"subject\\\";s:46:\\\"Your proposal was approved — CPR-202608-0002\\\";s:5:\\\"title\\\";s:17:\\\"Proposal approved\\\";s:7:\\\"message\\\";s:47:\\\"CPR-202608-0002 was approved by Synxcel Minton.\\\";s:6:\\\"button\\\";a:2:{s:3:\\\"url\\\";s:46:\\\"http:\\/\\/kargamine_prototype.test\\/page_proposals\\\";s:4:\\\"text\\\";s:13:\\\"View Proposal\\\";}}s:9:\\\"\\u0000*\\u0000userId\\\";i:3;}\"}}',0,NULL,1785772870,1785772870),(13,'default','{\"uuid\":\"af339602-23e3-497a-b2d5-942da8209ba0\",\"displayName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"command\":\"O:31:\\\"App\\\\Jobs\\\\SendApplicationMailJob\\\":2:{s:10:\\\"\\u0000*\\u0000payload\\\";a:4:{s:7:\\\"subject\\\";s:35:\\\"Approval needed — CPR-202608-0003\\\";s:5:\\\"title\\\";s:31:\\\"Proposal awaiting your approval\\\";s:7:\\\"message\\\";s:55:\\\"CPR-202608-0003 for Kargamine User needs your approval.\\\";s:6:\\\"button\\\";a:2:{s:3:\\\"url\\\";s:46:\\\"http:\\/\\/kargamine_prototype.test\\/page_proposals\\\";s:4:\\\"text\\\";s:13:\\\"View Proposal\\\";}}s:9:\\\"\\u0000*\\u0000userId\\\";i:14;}\"}}',0,NULL,1785773075,1785773075),(14,'default','{\"uuid\":\"d8b5855c-dc57-4abb-92a8-5d7e4bc76a4b\",\"displayName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"command\":\"O:31:\\\"App\\\\Jobs\\\\SendApplicationMailJob\\\":2:{s:10:\\\"\\u0000*\\u0000payload\\\";a:4:{s:7:\\\"subject\\\";s:46:\\\"Your proposal was approved — CPR-202608-0003\\\";s:5:\\\"title\\\";s:17:\\\"Proposal approved\\\";s:7:\\\"message\\\";s:42:\\\"CPR-202608-0003 was approved by Developer.\\\";s:6:\\\"button\\\";a:2:{s:3:\\\"url\\\";s:46:\\\"http:\\/\\/kargamine_prototype.test\\/page_proposals\\\";s:4:\\\"text\\\";s:13:\\\"View Proposal\\\";}}s:9:\\\"\\u0000*\\u0000userId\\\";i:3;}\"}}',0,NULL,1785945710,1785945710),(15,'default','{\"uuid\":\"99d9b2cf-6e05-470c-9b0a-b154781c09b5\",\"displayName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendApplicationMailJob\",\"command\":\"O:31:\\\"App\\\\Jobs\\\\SendApplicationMailJob\\\":2:{s:10:\\\"\\u0000*\\u0000payload\\\";a:4:{s:7:\\\"subject\\\";s:46:\\\"Your proposal was approved — CPR-202608-0004\\\";s:5:\\\"title\\\";s:17:\\\"Proposal approved\\\";s:7:\\\"message\\\";s:42:\\\"CPR-202608-0004 was approved by Developer.\\\";s:6:\\\"button\\\";a:2:{s:3:\\\"url\\\";s:46:\\\"http:\\/\\/kargamine_prototype.test\\/page_proposals\\\";s:4:\\\"text\\\";s:13:\\\"View Proposal\\\";}}s:9:\\\"\\u0000*\\u0000userId\\\";i:1;}\"}}',0,NULL,1785946705,1785946705);
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
) ENGINE=InnoDB AUTO_INCREMENT=150 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lane_tariff_rate_prices`
--

LOCK TABLES `lane_tariff_rate_prices` WRITE;
/*!40000 ALTER TABLE `lane_tariff_rate_prices` DISABLE KEYS */;
INSERT INTO `lane_tariff_rate_prices` VALUES (7,3,4,9000.00,'2026-07-28 11:39:43','2026-07-28 12:18:33'),(8,3,5,9000.00,'2026-07-28 11:39:43','2026-07-28 12:18:33'),(9,3,6,9000.00,'2026-07-28 11:39:43','2026-07-28 12:18:33'),(10,3,7,9000.00,'2026-07-28 11:39:43','2026-07-28 12:18:33'),(11,3,8,9000.00,'2026-07-28 11:39:43','2026-07-28 12:18:33'),(12,3,9,14400.00,'2026-07-28 11:39:43','2026-07-28 12:18:33'),(13,3,10,16600.00,'2026-07-28 11:39:43','2026-07-28 12:18:33'),(14,3,11,12600.00,'2026-07-28 11:39:43','2026-07-28 12:18:33'),(15,3,12,20200.00,'2026-07-28 11:39:43','2026-07-28 12:18:33'),(16,3,13,9900.00,'2026-07-28 11:39:43','2026-07-28 12:18:33'),(17,3,14,15800.00,'2026-07-28 11:39:43','2026-07-28 12:18:33'),(18,4,4,9000.00,'2026-07-28 11:39:43','2026-07-28 12:18:33'),(19,4,5,9000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(20,4,6,9000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(21,4,7,9000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(22,4,8,9000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(23,4,9,14400.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(24,4,10,16600.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(25,4,11,12600.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(26,4,12,20200.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(27,4,13,9900.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(28,4,14,15800.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(29,5,4,4000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(30,5,5,4000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(31,5,6,4000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(32,5,7,4000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(33,5,8,4000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(34,5,9,6400.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(35,5,10,7400.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(36,5,11,5600.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(37,5,12,9000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(38,5,13,4400.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(39,5,14,7000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(40,6,4,4000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(41,6,5,4000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(42,6,6,4000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(43,6,7,4000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(44,6,8,4000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(45,6,9,6400.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(46,6,10,7400.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(47,6,11,5600.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(48,6,12,9000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(49,6,13,4400.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(50,6,14,7000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(51,7,4,16000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(52,7,5,16000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(53,7,6,16000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(54,7,7,16000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(55,7,8,16000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(56,7,9,25600.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(57,7,10,29400.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(58,7,11,22400.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(59,7,12,35800.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(60,7,13,17600.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(61,7,14,28200.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(62,8,4,16000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(63,8,5,16000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(64,8,6,16000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(65,8,7,16000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(66,8,8,16000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(67,8,9,25600.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(68,8,10,29400.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(69,8,11,22400.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(70,8,12,35800.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(71,8,13,17600.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(72,8,14,28200.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(73,9,4,8000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(74,9,5,8000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(75,9,6,8000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(76,9,7,8000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(77,9,8,8000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(78,9,9,12800.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(79,9,10,14700.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(80,9,11,11200.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(81,9,12,17900.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(82,9,13,8800.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(83,9,14,14100.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(84,10,4,8000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(85,10,5,8000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(86,10,6,8000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(87,10,7,8000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(88,10,8,8000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(89,10,9,12800.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(90,10,10,14700.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(91,10,11,11200.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(92,10,12,17900.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(93,10,13,8800.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(94,10,14,14100.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(95,11,4,15000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(96,11,5,15000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(97,11,6,15000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(98,11,7,15000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(99,11,8,15000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(100,11,9,24000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(101,11,10,27600.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(102,11,11,21000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(103,11,12,33600.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(104,11,13,16500.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(105,11,14,26400.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(106,12,4,15000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(107,12,5,15000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(108,12,6,15000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(109,12,7,15000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(110,12,8,15000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(111,12,9,24000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(112,12,10,27600.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(113,12,11,21000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(114,12,12,33600.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(115,12,13,16500.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(116,12,14,26400.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(117,13,4,7000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(118,13,5,7000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(119,13,6,7000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(120,13,7,7000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(121,13,8,7000.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(122,13,9,11200.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(123,13,10,12900.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(124,13,11,9800.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(125,13,12,15700.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(126,13,13,7700.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(127,13,14,12300.00,'2026-07-28 11:39:43','2026-07-28 12:18:34'),(128,14,4,7000.00,'2026-07-28 11:39:44','2026-07-28 12:18:34'),(129,14,5,7000.00,'2026-07-28 11:39:44','2026-07-28 12:18:34'),(130,14,6,7000.00,'2026-07-28 11:39:44','2026-07-28 12:18:34'),(131,14,7,7000.00,'2026-07-28 11:39:44','2026-07-28 12:18:34'),(132,14,8,7000.00,'2026-07-28 11:39:44','2026-07-28 12:18:34'),(133,14,9,11200.00,'2026-07-28 11:39:44','2026-07-28 12:18:34'),(134,14,10,12900.00,'2026-07-28 11:39:44','2026-07-28 12:18:34'),(135,14,11,9800.00,'2026-07-28 11:39:44','2026-07-28 12:18:34'),(136,14,12,15700.00,'2026-07-28 11:39:44','2026-07-28 12:18:34'),(137,14,13,7700.00,'2026-07-28 11:39:44','2026-07-28 12:18:34'),(138,14,14,12300.00,'2026-07-28 11:39:44','2026-07-28 12:18:34'),(139,15,4,123.00,'2026-08-03 14:33:59','2026-08-03 14:33:59'),(140,15,5,123.00,'2026-08-03 14:33:59','2026-08-03 14:33:59'),(141,15,7,123.00,'2026-08-03 14:33:59','2026-08-03 14:33:59'),(142,15,6,123.00,'2026-08-03 14:33:59','2026-08-03 14:33:59'),(143,15,8,123.00,'2026-08-03 14:33:59','2026-08-03 14:33:59'),(144,15,9,123.00,'2026-08-03 14:33:59','2026-08-03 14:33:59'),(145,15,10,123.00,'2026-08-03 14:33:59','2026-08-03 14:33:59'),(146,15,11,123.00,'2026-08-03 14:33:59','2026-08-03 14:33:59'),(147,15,12,123.00,'2026-08-03 14:33:59','2026-08-03 14:33:59'),(148,15,13,123.00,'2026-08-03 14:33:59','2026-08-03 14:33:59'),(149,15,14,123.00,'2026-08-03 14:33:59','2026-08-03 14:33:59');
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lane_tariff_rates`
--

LOCK TABLES `lane_tariff_rates` WRITE;
/*!40000 ALTER TABLE `lane_tariff_rates` DISABLE KEYS */;
INSERT INTO `lane_tariff_rates` VALUES (3,2,'2026-07-27',NULL,1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(4,3,'2026-07-27',NULL,1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(5,4,'2026-07-27',NULL,1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(6,5,'2026-07-27',NULL,1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(7,6,'2026-07-27',NULL,1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(8,7,'2026-07-27',NULL,1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(9,8,'2026-07-27',NULL,1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(10,9,'2026-07-27',NULL,1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(11,10,'2026-07-27',NULL,1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(12,11,'2026-07-27',NULL,1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(13,12,'2026-07-27',NULL,1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(14,13,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(15,1,'2026-08-01','2026-08-31',1,'2026-08-03 14:33:59','2026-08-03 14:33:59');
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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lanes`
--

LOCK TABLES `lanes` WRITE;
/*!40000 ALTER TABLE `lanes` DISABLE KEYS */;
INSERT INTO `lanes` VALUES (1,2,22,1,'2026-07-27 11:21:32','2026-07-27 11:21:32'),(2,1,4,1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(3,4,1,1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(4,1,22,1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(5,22,1,1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(6,1,6,1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(7,6,1,1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(8,1,10,1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(9,10,1,1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(10,1,5,1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(11,5,1,1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(12,4,6,1,'2026-07-28 11:39:43','2026-07-28 11:39:43'),(13,6,4,1,'2026-07-28 11:39:43','2026-07-28 11:39:43');
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
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `list_of_values_table`
--

LOCK TABLES `list_of_values_table` WRITE;
/*!40000 ALTER TABLE `list_of_values_table` DISABLE KEYS */;
INSERT INTO `list_of_values_table` VALUES (1,1,'OFF','Office',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(2,1,'WAR','Warehouse',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(3,1,'BRA','Branch',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(4,1,'STO','Storage Facility',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(5,2,'REF','Referral',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(6,2,'WEB','Website',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(7,2,'WAL','Walk-in',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(8,2,'COL','Cold Call',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(9,2,'SOC','Social Media',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(10,2,'OTH','Other',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(11,3,'IMP','Importer',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(12,3,'EXP','Exporter',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(13,3,'MAN','Manufacturer',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(14,3,'TRA','Trading',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(15,3,'RET','Retail',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(16,3,'DIS','Distributor',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(17,3,'OTH','Others',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(18,4,'MAN','Manufacturing',NULL,'2026-08-03 15:16:47','2026-08-03 15:16:47'),(19,4,'RET','Retail',NULL,'2026-08-03 15:16:47','2026-08-03 15:16:47'),(20,4,'LOG','Logistics & Freight',NULL,'2026-08-03 15:16:47','2026-08-03 15:16:47'),(21,4,'CON','Construction',NULL,'2026-08-03 15:16:47','2026-08-03 15:16:47'),(22,4,'AGR','Agriculture',NULL,'2026-08-03 15:16:47','2026-08-03 15:16:47'),(23,4,'INF','Information Technology',NULL,'2026-08-03 15:16:47','2026-08-03 15:16:47'),(24,4,'FOO','Food & Beverage',NULL,'2026-08-03 15:16:47','2026-08-03 15:16:47'),(25,4,'PHA','Pharmaceuticals',NULL,'2026-08-03 15:16:47','2026-08-03 15:16:47'),(26,5,'SOL','Sole Proprietorship',NULL,'2026-08-03 15:16:48','2026-08-03 15:16:48'),(27,5,'PAR','Partnership',NULL,'2026-08-03 15:16:48','2026-08-03 15:16:48'),(28,5,'COR','Corporation',NULL,'2026-08-03 15:16:48','2026-08-03 15:16:48'),(29,5,'COO','Cooperative',NULL,'2026-08-03 15:16:48','2026-08-03 15:16:48'),(30,5,'GOV','Government',NULL,'2026-08-03 15:16:48','2026-08-03 15:16:48'),(31,5,'NON','Non-Profit',NULL,'2026-08-03 15:16:48','2026-08-03 15:16:48'),(32,6,'DIR','Direct Client',NULL,'2026-08-03 16:43:53','2026-08-03 16:43:53'),(33,6,'BRO','Broker / Agent',NULL,'2026-08-03 16:43:53','2026-08-03 16:43:53'),(34,6,'COR','Corporate Account',NULL,'2026-08-03 16:43:53','2026-08-03 16:43:53'),(35,6,'GOV','Government Account',NULL,'2026-08-03 16:43:53','2026-08-03 16:43:53'),(36,6,'WAL','Walk-in Client',NULL,'2026-08-03 16:43:53','2026-08-03 16:43:53'),(37,7,'REG','Regular',NULL,'2026-08-03 16:43:54','2026-08-03 16:43:54'),(38,7,'KEY','Key Account',NULL,'2026-08-03 16:43:54','2026-08-03 16:43:54'),(39,7,'VIP','VIP',NULL,'2026-08-03 16:43:54','2026-08-03 16:43:54'),(40,7,'STR','Strategic Partner',NULL,'2026-08-03 16:43:54','2026-08-03 16:43:54'),(41,7,'NEW','New Client',NULL,'2026-08-03 16:43:54','2026-08-03 16:43:54'),(42,8,'CBM','cbm/s',NULL,'2026-08-05 06:41:54','2026-08-05 06:41:54'),(43,8,'DAY','day/s',NULL,'2026-08-05 06:41:54','2026-08-05 06:41:54'),(44,8,'HOU','hour/s',NULL,'2026-08-05 06:41:54','2026-08-05 06:41:54'),(45,8,'LIT','liter/s',NULL,'2026-08-05 06:41:54','2026-08-05 06:41:54'),(46,8,'MON','month/s',NULL,'2026-08-05 06:41:54','2026-08-05 06:41:54'),(47,8,'MOV','move/s',NULL,'2026-08-05 06:41:54','2026-08-05 06:41:54'),(48,8,'OCC','occurrence/s',NULL,'2026-08-05 06:41:54','2026-08-05 06:41:54'),(49,8,'OTH','others',NULL,'2026-08-05 06:41:54','2026-08-05 06:41:54'),(50,8,'PIE','piece/s',NULL,'2026-08-05 06:41:54','2026-08-05 06:41:54'),(51,8,'SER','service/s',NULL,'2026-08-05 06:41:54','2026-08-05 06:41:54'),(52,8,'SET','set/s',NULL,'2026-08-05 06:41:54','2026-08-05 06:41:54'),(53,8,'TON','ton/s',NULL,'2026-08-05 06:41:54','2026-08-05 06:41:54'),(54,8,'TRI','trip/s',NULL,'2026-08-05 06:41:54','2026-08-05 06:41:54'),(55,8,'UNI','unit/s',NULL,'2026-08-05 06:41:54','2026-08-05 06:41:54');
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
) ENGINE=InnoDB AUTO_INCREMENT=140 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2025_10_01_211540_create_nav_menus_table',1),(6,'2025_10_02_163004_create_table_for_settings_role',1),(7,'2025_10_03_180527_add_parentmenu',1),(8,'2025_10_04_144632_create_mailer_settings_table',1),(9,'2025_11_06_035725_insert_order_in_nav_menus',1),(10,'2026_01_24_221131_create_sessions_table',1),(11,'2026_01_24_221645_add_session_id_to_users_table',1),(12,'2026_01_24_223037_create_cache_table',1),(13,'2026_01_26_110424_create_jobs_table',1),(14,'2026_01_26_110425_modify_columns_of_user_table',1),(15,'2026_05_01_011537_options_table',1),(16,'2026_05_01_011632_list_of_value_table',1),(17,'2026_05_05_052405_create_company_info_master_table',1),(18,'2026_05_05_052441_create_contact_info_table',1),(19,'2026_05_05_052453_create_trade_references_table',1),(20,'2026_05_05_052548_create_services_info_table',1),(21,'2026_05_05_052612_create_company_finance_table',1),(22,'2026_05_05_052631_create_billed_details_table',1),(23,'2026_05_05_052656_create_sales_info_table',1),(24,'2026_05_05_052712_create_stages_info_table',1),(25,'2026_05_07_205424_create_e_invoice_table',1),(26,'2026_05_07_205616_create_courier_invoice_table',1),(27,'2026_06_05_045259_create_crm_status_table',1),(28,'2026_06_05_045351_create_crm_leads_table',1),(29,'2026_06_05_045416_create_company_info_table',1),(30,'2026_06_05_045430_create_crm_notes_table',1),(31,'2026_06_05_045450_create_crm_activities_table',1),(32,'2026_06_15_230541_proposals',1),(33,'2026_06_15_231224_proposal_rates',1),(34,'2026_06_15_233415_create_table_for_routes',1),(35,'2026_06_15_233633_create_table_service_type',1),(36,'2026_06_15_233806_create_proposal_status',1),(37,'2026_06_15_233848_create_customer_type',1),(38,'2026_06_26_195636_create_container_type',1),(39,'2026_06_26_195710_create_container_class',1),(40,'2026_06_26_195725_create_container_size',1),(41,'2026_07_02_221007_add_new_columns_to_user_table',1),(42,'2026_07_02_222100_create_user_department',1),(43,'2026_07_02_222118_create_user_status',1),(44,'2026_07_04_000001_create_ports_table',1),(45,'2026_07_04_000002_create_serviceable_areas_table',1),(46,'2026_07_04_000003_create_delivery_types_table',1),(47,'2026_07_04_000004_create_charge_types_table',1),(48,'2026_07_04_000005_create_lanes_table',1),(49,'2026_07_04_000006_create_lane_tariff_rates_table',1),(50,'2026_07_04_000007_create_port_charges_table',1),(51,'2026_07_04_000008_create_handling_fees_table',1),(52,'2026_07_04_000009_create_trucking_tariffs_table',1),(53,'2026_07_04_000010_create_vat_rates_table',1),(54,'2026_07_04_000011_create_contracts_table',1),(55,'2026_07_04_000012_create_contract_rates_table',1),(56,'2026_07_04_000013_create_bookings_table',1),(57,'2026_07_04_000014_create_booking_port_charges_table',1),(58,'2026_07_07_000001_add_applicable_to_to_charge_types_table',1),(59,'2026_07_07_000002_create_general_charges_table',1),(60,'2026_07_07_124225_drop_bsc_ra_gri_from_lane_tariff_rates_table',1),(61,'2026_07_07_124800_add_rate_type_and_rate_value_to_proposals_rates_table',1),(62,'2026_07_08_175248_create_client_masters_table',1),(63,'2026_07_08_175319_create_client_contacts_table',1),(64,'2026_07_08_175429_create_client_trade_references_table',1),(65,'2026_07_08_175448_create_client_finance_table',1),(66,'2026_07_08_175528_create_client_billing_table',1),(67,'2026_07_08_221636_create_containers_table',1),(68,'2026_07_08_221704_create_container_variants_table',1),(69,'2026_07_08_221727_create_lane_tariff_rate_prices_table',1),(70,'2026_07_09_000939_drop_column_from_container_table',1),(71,'2026_07_09_001926_drop_column_from_lane_tariff_rates_table',1),(72,'2026_07_10_212311_create_client_proposals_table',1),(73,'2026_07_10_212810_create_client_proposal_rates_table',1),(74,'2026_07_10_212846_create_client_contracts_table',1),(75,'2026_07_10_212946_create_client_contract_rates_table',1),(76,'2026_07_11_152530_add_lead_id_to_client_masters_table',1),(77,'2026_07_13_182230_add_workflow_columns_to_client_proposals_table',1),(78,'2026_07_14_021740_add_progress_columns_to_crm_leads_table',1),(79,'2026_07_14_021824_add_address_fields_to_crm_company_info_table',1),(80,'2026_07_14_021949_create_crm_lead_containers_table',1),(81,'2026_07_14_030206_add_lookup_columns_to_crm_lead_containers_table',1),(82,'2026_07_14_044418_drop_company_address_from_crm_company_info',1),(83,'2026_07_22_003454_add_lead_id_to_client_proposals_table',1),(84,'2026_07_22_020031_add_attachment_to_crm_activities_table',1),(85,'2026_07_22_031438_add_client_type_and_contact_fields_to_crm_leads_table',1),(86,'2026_07_22_031439_create_crm_lead_addresses_table',1),(87,'2026_07_22_031439_migrate_crm_company_address_to_lead_addresses_and_drop_columns',1),(88,'2026_07_22_031440_add_industry_description_to_crm_company_info_table',1),(89,'2026_07_22_040302_remove_lookup_values_nav_menu_entry',1),(90,'2026_07_22_045002_add_customer_code_to_crm_leads_table',1),(91,'2026_07_22_045002_create_client_addresses_table',1),(92,'2026_07_22_045003_migrate_client_registered_address_and_drop_column',1),(93,'2026_07_22_045004_add_type_fields_to_client_contacts_table',1),(94,'2026_07_23_010845_create_app_theme_settings_table',1),(95,'2026_07_23_010846_add_theme_nav_menu_entry',1),(96,'2026_07_25_031024_create_notifications_table',1),(97,'2026_07_25_035333_create_teams_table',1),(98,'2026_07_25_035334_add_team_columns_to_users_table',1),(99,'2026_07_27_100000_add_coordinates_to_ports_table',1),(100,'2026_07_27_100100_create_container_assets_table',1),(101,'2026_07_27_100200_create_container_asset_location_history_table',1),(102,'2026_07_27_110000_add_is_system_to_setting_role_table',1),(103,'2026_07_27_110100_create_permissions_table',1),(104,'2026_07_27_110200_create_role_permission_table',1),(105,'2026_07_27_120000_add_booking_header_fields_to_bookings_table',1),(106,'2026_07_27_120100_create_booking_lines_table',1),(107,'2026_07_27_120200_create_booking_status_history_table',1),(108,'2026_07_27_120300_create_booking_container_units_table',1),(109,'2026_07_27_120400_create_booking_invoices_table',1),(110,'2026_07_27_120500_create_bill_of_ladings_table',1),(111,'2026_07_27_130000_add_termination_fields_to_client_contracts_table',1),(112,'2026_07_28_090000_create_nav_icons_table',2),(113,'2026_07_28_185238_move_route_delivery_to_booking_lines',3),(114,'2026_07_28_204127_add_min_van_qty_to_client_rate_tables',4),(115,'2026_07_29_003717_split_contact_name_on_crm_leads_table',5),(116,'2026_07_29_003752_rename_required_temperature_on_crm_lead_containers_table',5),(117,'2026_07_29_003808_add_authorized_signatory_fields_to_crm_company_info_table',6),(118,'2026_07_29_131824_add_transaction_details_to_booking_lines_table',7),(119,'2026_07_29_173304_add_always_route_atw_to_client_masters_table',8),(120,'2026_07_29_173305_add_cv_assignment_fields_to_booking_container_units_table',8),(121,'2026_07_29_173306_create_booking_dispatch_documents_table',8),(122,'2026_07_29_181324_add_gate_pass_fields_to_booking_container_units_table',9),(123,'2026_07_29_183320_create_booking_container_eir_records_table',10),(124,'2026_07_29_195201_create_vessel_voyages_table',11),(125,'2026_07_29_195202_add_voyage_fields_to_booking_container_units_table',11),(126,'2026_08_03_100001_add_client_mnemonic_and_account_manager_to_client_masters_table',12),(127,'2026_08_03_100002_restructure_client_finance_table',12),(128,'2026_08_03_100003_restructure_client_contacts_table',12),(129,'2026_08_03_100004_create_client_contact_addresses_table',12),(130,'2026_08_03_100005_create_client_ancillary_services_table',12),(131,'2026_08_03_100006_drop_client_billing_table',12),(132,'2026_08_04_100001_restructure_client_masters_company_info_fields',13),(133,'2026_08_04_120001_restructure_client_finance_cro_and_declared_value',14),(134,'2026_08_05_100001_create_special_charges_table',15),(135,'2026_08_05_100002_create_cargo_yards_table',15),(136,'2026_08_06_100001_simplify_client_ancillary_services_table',16),(137,'2026_08_06_120000_add_workflow_columns_to_client_contracts_table',17),(138,'2026_08_06_120100_add_tax_type_to_vat_rates_table',17),(139,'2026_08_06_120200_rename_tax_status_to_registered_tax_type_on_client_finance_table',17);
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
INSERT INTO `nav_icons` VALUES (1,'home','Home','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(2,'users','Users','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-4.5 0 2.625 2.625 0 014.5 0z\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(3,'user','User','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(4,'bell','Bell','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(5,'magnifying-glass','Search','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"m21 21-5.2-5.2m0 0A7.5 7.5 0 1 0 5.3 5.3a7.5 7.5 0 0 0 10.5 10.5Z\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(6,'x-mark','Close (X)','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M6 18L18 6M6 6l12 12\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(7,'check-circle','Check Circle','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(8,'document-text','Document','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(9,'sparkles','Sparkles','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.456-2.456L14.25 6l1.035-.259a3.375 3.375 0 002.456-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(10,'cube','Box / Cube','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(11,'envelope','Envelope','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(12,'bars-3','Menu (3 lines)','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(13,'cog-6-tooth','Settings (gear)','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.28zM15 12a3 3 0 11-6 0 3 3 0 016 0z\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(14,'chevron-down','Chevron Down','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M19.5 8.25l-7.5 7.5-7.5-7.5\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(15,'chevron-up','Chevron Up','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M4.5 15.75l7.5-7.5 7.5 7.5\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(16,'chevron-left','Chevron Left','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M15.75 19.5L8.25 12l7.5-7.5\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(17,'chevron-right','Chevron Right','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M8.25 4.5l7.5 7.5-7.5 7.5\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(18,'plus','Plus','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M12 4.5v15m7.5-7.5h-15\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(19,'minus','Minus','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M5 12h14\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(20,'arrow-path','Refresh','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(21,'arrow-up-tray','Upload','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(22,'arrow-down-tray','Download','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(23,'trash','Trash','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(24,'pencil','Edit (pencil)','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(25,'eye','Eye','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .638C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178zM15 12a3 3 0 11-6 0 3 3 0 016 0z\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(26,'eye-slash','Eye Slash','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M3.98 8.223A10.477 10.477 0 001.934 12c1.832 4.068 5.728 7 10.066 7 1.676 0 3.285-.37 4.712-1.034M6.228 6.228A10.45 10.45 0 0112 5c4.38 0 8.293 2.953 10.07 7.063a10.522 10.522 0 01-4.517 4.92M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.878 9.878\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(27,'clock','Clock','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(28,'calendar','Calendar','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(29,'truck','Truck','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(30,'archive-box','Archive Box','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(31,'banknotes','Finance (banknotes)','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(32,'building-office','Building / Office','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(33,'globe-alt','Globe','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(34,'shield-check','Shield Check','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(35,'exclamation-triangle','Warning Triangle','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(36,'information-circle','Info Circle','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(37,'flag','Flag','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M3 3v1.5M3 21v-6m0 0l2.77-.693a9 9 0 016.208.682l.108.054a9 9 0 006.086.71l3.114-.732a48.524 48.524 0 01-.005-10.499l-3.11.732a9 9 0 01-6.085-.711l-.108-.054a9 9 0 00-6.208-.682L3 4.5M3 15V4.5\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(38,'star','Star','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.562.562 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(39,'heart','Heart','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z\" />','2026-07-27 17:44:51','2026-07-27 17:44:51'),(40,'tag','Tag','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3zM6 6h.008v.008H6V6z\" />','2026-07-27 17:44:52','2026-07-27 17:44:52'),(41,'folder-open','Folder','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 015.25 3.75h4.5c.55 0 1.02.398 1.11.94l.213 1.28c.089.542.559.94 1.11.94h4.567a2.25 2.25 0 012.25 2.25v.616\" />','2026-07-27 17:44:52','2026-07-27 17:44:52'),(42,'briefcase','Briefcase','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18a48.424 48.424 0 01-6.378.42c-2.162 0-4.291-.143-6.378-.42-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0\" />','2026-07-27 17:44:52','2026-07-27 17:44:52'),(43,'map-pin','Map Pin','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M15 10.5a3 3 0 11-6 0 3 3 0 016 0z\" /><path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z\" />','2026-07-27 17:44:52','2026-07-27 17:44:52'),(44,'link','Link','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244\" />','2026-07-27 17:44:52','2026-07-27 17:44:52'),(45,'paper-airplane','Send (paper airplane)','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5\" />','2026-07-27 17:44:52','2026-07-27 17:44:52'),(46,'printer','Printer','<path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659\" />','2026-07-27 17:44:52','2026-07-27 17:44:52'),(47,'square','Square','<rect x=\"3.75\" y=\"3.75\" width=\"16.5\" height=\"16.5\" rx=\"2\" />','2026-07-27 17:44:52','2026-07-27 17:44:52'),(48,'circle','Circle','<circle cx=\"12\" cy=\"12\" r=\"8.25\" />','2026-07-27 17:44:52','2026-07-27 17:44:52'),(49,'squares-2x2','Grid','<rect x=\"3.75\" y=\"3.75\" width=\"7.5\" height=\"7.5\" rx=\"1.25\" /><rect x=\"12.75\" y=\"3.75\" width=\"7.5\" height=\"7.5\" rx=\"1.25\" /><rect x=\"3.75\" y=\"12.75\" width=\"7.5\" height=\"7.5\" rx=\"1.25\" /><rect x=\"12.75\" y=\"12.75\" width=\"7.5\" height=\"7.5\" rx=\"1.25\" />','2026-07-27 17:44:52','2026-07-27 17:44:52'),(50,'list-bullet','List','<circle cx=\"4.5\" cy=\"6\" r=\"1\" fill=\"currentColor\" stroke=\"none\" /><circle cx=\"4.5\" cy=\"12\" r=\"1\" fill=\"currentColor\" stroke=\"none\" /><circle cx=\"4.5\" cy=\"18\" r=\"1\" fill=\"currentColor\" stroke=\"none\" /><line x1=\"8.25\" y1=\"6\" x2=\"20.25\" y2=\"6\" stroke-linecap=\"round\" /><line x1=\"8.25\" y1=\"12\" x2=\"20.25\" y2=\"12\" stroke-linecap=\"round\" /><line x1=\"8.25\" y1=\"18\" x2=\"20.25\" y2=\"18\" stroke-linecap=\"round\" />','2026-07-27 17:44:52','2026-07-27 17:44:52');
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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nav_menus`
--

LOCK TABLES `nav_menus` WRITE;
/*!40000 ALTER TABLE `nav_menus` DISABLE KEYS */;
INSERT INTO `nav_menus` VALUES (1,'Theme','squares-2x2','/page_theme','[\"1\"]',9,3,'2026-07-27 10:35:49','2026-07-28 11:39:43'),(2,'Dashboard','home','/page_dashboard','[\"1\"]',0,0,'2026-07-27 10:35:57','2026-07-27 17:46:02'),(3,'CRM','flag','/page_crm','[\"1\"]',0,1,'2026-07-27 10:35:57','2026-07-28 11:39:43'),(4,'Clients','building-office','page_clientMasters','[\"1\",\"2\",\"3\",\"4\"]',0,3,'2026-07-27 10:35:57','2026-07-27 17:46:02'),(5,'Contracts','document-text','/page_contracts','[\"1\",\"2\",\"3\",\"4\"]',0,5,'2026-07-27 10:35:57','2026-07-28 11:39:43'),(6,'Users','users','/page_usermanagement','[\"1\"]',0,10,'2026-07-27 10:35:57','2026-07-27 17:46:02'),(7,'Proposals','paper-airplane','page_proposals','[\"1\",\"2\",\"3\",\"4\"]',0,2,'2026-07-27 10:35:57','2026-07-28 11:39:43'),(8,'Settings','cog-6-tooth','#','[\"1\",\"2\",\"3\",\"4\"]',0,11,'2026-07-27 10:35:57','2026-07-28 11:39:43'),(9,'Developer Option','shield-check','#','[\"1\"]',0,12,'2026-07-27 10:35:57','2026-07-28 11:39:43'),(10,'Mailer','envelope','/page_mailer','[\"1\"]',9,1,'2026-07-27 10:35:57','2026-07-28 11:39:43'),(11,'Menus','bars-3','/page_menus','[\"1\"]',9,2,'2026-07-27 10:35:57','2026-07-28 11:39:43'),(12,'Notification Test','bell','/page_notification_test','[\"1\"]',9,4,'2026-07-27 10:35:57','2026-07-28 11:39:43'),(13,'App Settings','cog-6-tooth','/page_maintenance','[\"1\",\"2\",\"3\",\"4\"]',8,1,'2026-07-27 10:35:57','2026-07-28 11:39:43'),(14,'Team Management','briefcase','/page_team_management','[\"1\"]',8,2,'2026-07-27 10:35:57','2026-07-28 11:39:43'),(15,'Container Inventory','cube','/page_container_inventory','[\"1\",\"2\",\"3\",\"4\"]',8,8,'2026-07-27 10:55:27','2026-07-29 14:21:27'),(16,'Booking','squares-2x2','page_booking','[\"2\",\"4\",\"5\",\"6\",\"1\",\"3\"]',0,4,'2026-07-27 10:56:08','2026-07-27 18:02:50'),(17,'Cargo Build-Up','archive-box','/page_cargo_build_up','[\"1\",\"2\",\"3\",\"4\"]',0,7,'2026-07-29 07:18:13','2026-07-29 07:18:13'),(18,'Pier Check-In','check-circle','/page_pier_checkin','[\"1\",\"2\",\"3\",\"4\"]',0,9,'2026-07-29 10:20:30','2026-07-29 10:20:30'),(19,'Bookings','list-bullet','/page_booking','[\"1\",\"2\",\"3\",\"4\"]',0,6,'2026-07-29 14:02:56','2026-07-29 14:02:56');
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (3,'crm.lead_created','App\\Models\\CrmLead',6,14,4,'New lead created','Kargamine User added a new lead — qweqew.','View in CRM','/page_crm',NULL,1,'2026-07-27 15:37:16','2026-07-27 16:06:44'),(4,'proposal.pending','App\\Models\\ClientProposal',6,14,4,'Proposal awaiting your approval','CPR-202607-0004 for Kargamine User needs your approval.','View Proposal','/page_proposals',NULL,1,'2026-07-27 15:38:19','2026-07-27 16:06:44'),(6,'proposal.pending','App\\Models\\ClientProposal',8,14,4,'Proposal awaiting your approval','CPR-202607-0005 for Kargamine User needs your approval.','View Proposal','/page_proposals','{\"modal_fn\":\"openProposalModal\",\"modal_args\":[8]}',1,'2026-07-27 15:47:51','2026-07-27 16:20:32'),(7,'crm.lead_created','App\\Models\\CrmLead',10,14,4,'New lead created','Kargamine User added a new lead — test.','View in CRM','/page_crm',NULL,1,'2026-07-27 15:59:50','2026-07-27 16:20:29'),(8,'proposal.approved','App\\Models\\ClientProposal',8,4,14,'Proposal approved','CPR-202607-0005 was approved by Eden Palma.','View Proposal','/page_proposals','{\"modal_fn\":\"openProposalModal\",\"modal_args\":[8]}',1,'2026-07-27 16:20:35','2026-07-27 16:20:45'),(9,'proposal.approved','App\\Models\\ClientProposal',12,1,1,'Proposal approved','CPR-202608-0001 was approved by Developer.','View Proposal','/page_proposals','{\"modal_fn\":\"openProposalModal\",\"modal_args\":[12]}',1,'2026-08-03 14:38:05','2026-08-03 16:48:17'),(10,'proposal.pending','App\\Models\\ClientProposal',13,14,4,'Proposal awaiting your approval','CPR-202608-0002 for Kargamine User needs your approval.','View Proposal','/page_proposals','{\"modal_fn\":\"openProposalModal\",\"modal_args\":[13]}',0,'2026-08-03 16:01:00','2026-08-03 16:01:00'),(11,'proposal.approved','App\\Models\\ClientProposal',13,3,3,'Proposal approved','CPR-202608-0002 was approved by Synxcel Minton.','View Proposal','/page_proposals','{\"modal_fn\":\"openProposalModal\",\"modal_args\":[13]}',0,'2026-08-03 16:01:10','2026-08-03 16:01:10'),(12,'proposal.pending','App\\Models\\ClientProposal',14,14,4,'Proposal awaiting your approval','CPR-202608-0003 for Kargamine User needs your approval.','View Proposal','/page_proposals','{\"modal_fn\":\"openProposalModal\",\"modal_args\":[14]}',0,'2026-08-03 16:04:35','2026-08-03 16:04:35'),(13,'proposal.approved','App\\Models\\ClientProposal',14,3,1,'Proposal approved','CPR-202608-0003 was approved by Developer.','View Proposal','/page_proposals','{\"modal_fn\":\"openProposalModal\",\"modal_args\":[14]}',0,'2026-08-05 16:01:49','2026-08-05 16:01:49'),(14,'proposal.approved','App\\Models\\ClientProposal',15,1,1,'Proposal approved','CPR-202608-0004 was approved by Developer.','View Proposal','/page_proposals','{\"modal_fn\":\"openProposalModal\",\"modal_args\":[15]}',0,'2026-08-05 16:18:25','2026-08-05 16:18:25');
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `options_table`
--

LOCK TABLES `options_table` WRITE;
/*!40000 ALTER TABLE `options_table` DISABLE KEYS */;
INSERT INTO `options_table` VALUES (1,'Address Type',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(2,'Lead Source',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(3,'Type of Business',NULL,'2026-07-27 10:35:59','2026-07-27 10:35:59'),(4,'Industry',NULL,'2026-08-03 15:16:47','2026-08-03 15:16:47'),(5,'Type of Organization',NULL,'2026-08-03 15:16:48','2026-08-03 15:16:48'),(6,'Client Category',NULL,'2026-08-03 16:43:53','2026-08-03 16:43:53'),(7,'Client Classification',NULL,'2026-08-03 16:43:54','2026-08-03 16:43:54'),(8,'Unit',NULL,'2026-08-05 06:41:54','2026-08-05 06:41:54');
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
INSERT INTO `permissions` VALUES (1,'roles.manage','Manage roles & permissions','Roles','2026-07-27 10:35:58','2026-07-27 10:35:58'),(2,'booking.create','Create a booking','Booking','2026-07-27 10:35:58','2026-07-27 10:35:58'),(3,'booking.confirm','Confirm a booking','Booking','2026-07-27 10:35:58','2026-07-27 10:35:58'),(4,'booking.cancel','Cancel a booking','Booking','2026-07-27 10:35:58','2026-07-27 10:35:58'),(5,'booking.advance-status','Advance a booking\'s status','Booking','2026-07-27 10:35:58','2026-07-27 10:35:58'),(6,'contract.create','Create a contract from an accepted proposal','Contract','2026-07-27 10:35:58','2026-07-27 10:35:58'),(7,'contract.terminate','Terminate a contract','Contract','2026-07-27 10:35:58','2026-07-27 10:35:58'),(8,'booking.generate-dispatch-document','Generate a booking line\'s ATW/CAN','Booking','2026-07-29 09:47:23','2026-07-29 09:47:23'),(9,'booking.assign-cv','Assign ConVan/Proforma BL/Waybill/Seal to a container unit','Booking','2026-07-29 09:47:23','2026-07-29 09:47:23'),(10,'booking.gate-scan','Scan a container in/out at the gate (Pier Check-In)','Booking','2026-07-29 10:16:00','2026-07-29 10:16:00'),(11,'booking.issue-eir','Issue a container\'s EIR Out/In','Booking','2026-07-29 10:36:55','2026-07-29 10:36:55'),(12,'booking.assign-voyage','Assign or shut out a container\'s vessel voyage','Booking','2026-07-29 11:56:27','2026-07-29 11:56:27'),(13,'booking.generate-loadlist','Generate a vessel voyage\'s loadlist','Booking','2026-07-29 12:20:36','2026-07-29 12:20:36');
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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `port_charges`
--

LOCK TABLES `port_charges` WRITE;
/*!40000 ALTER TABLE `port_charges` DISABLE KEYS */;
INSERT INTO `port_charges` VALUES (1,22,1,500.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(2,22,2,800.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(3,22,3,1200.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(4,4,1,500.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(5,4,2,800.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(6,4,3,1200.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(7,5,1,500.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(8,5,2,800.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(9,5,3,1200.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(10,6,1,500.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(11,6,2,800.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(12,6,3,1200.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(13,10,1,500.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(14,10,2,800.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(15,10,3,1200.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(16,1,1,500.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(17,1,2,800.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(18,1,3,1200.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44');
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
INSERT INTO `proposal_status` VALUES (1,'Pending',NULL,'2026-07-28 12:18:33'),(2,'Approved',NULL,'2026-07-28 12:18:33'),(3,'Disapproved',NULL,'2026-07-28 12:18:33'),(4,'Accepted',NULL,'2026-07-28 12:18:33'),(5,'Rejected',NULL,'2026-07-28 12:18:33'),(6,'On-Hold',NULL,'2026-07-28 12:18:33');
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
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permission`
--

LOCK TABLES `role_permission` WRITE;
/*!40000 ALTER TABLE `role_permission` DISABLE KEYS */;
INSERT INTO `role_permission` VALUES (7,1,1),(4,1,2),(3,1,3),(2,1,4),(1,1,5),(5,1,6),(6,1,7),(27,1,8),(26,1,9),(28,1,10),(29,1,11),(30,1,12),(31,1,13),(14,4,1),(11,4,2),(10,4,3),(9,4,4),(8,4,5),(12,4,6),(13,4,7),(18,5,2),(17,5,3),(16,5,4),(15,5,5),(19,5,6),(20,5,7),(24,6,2),(23,6,3),(22,6,4),(21,6,5),(25,6,6);
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
INSERT INTO `routes` VALUES (1,'BUTUAN','BUTUAN',NULL,'2026-07-28 12:18:33'),(2,'CEBU','CEBU',NULL,'2026-07-28 12:18:33'),(3,'CAGAYAN','CAGAYAN',NULL,'2026-07-28 12:18:33'),(4,'DAVAO','DAVAO',NULL,'2026-07-28 12:18:33'),(5,'DUMAGUETE','DUMAGUETE',NULL,'2026-07-28 12:18:33'),(6,'GEN SAN','GEN SAN',NULL,'2026-07-28 12:18:33'),(7,'ILIGAN','ILIGAN',NULL,'2026-07-28 12:18:33'),(8,'ILOILO','ILOILO',NULL,'2026-07-28 12:18:33'),(9,'OSAMIS','OSAMIS',NULL,'2026-07-28 12:18:33'),(10,'CORON','CORON',NULL,'2026-07-28 12:18:33'),(11,'ROXAS','ROXAS',NULL,'2026-07-28 12:18:33'),(12,'CATICLAN','CATICLAN',NULL,'2026-07-28 12:18:33'),(13,'ORMOC','ORMOC',NULL,'2026-07-28 12:18:33'),(14,'TAGBILARAN','TAGBILARAN',NULL,'2026-07-28 12:18:33'),(15,'TACLOBAN','TACLOBAN',NULL,'2026-07-28 12:18:33'),(16,'ZAMBOANGA','ZAMBOANGA',NULL,'2026-07-28 12:18:33'),(17,'PUERTO PRINCESSA','PUERTO PRINCESSA',NULL,'2026-07-28 12:18:33'),(18,'SURIGAO','SURIGAO',NULL,'2026-07-28 12:18:33'),(19,'COTABATO','COTABATO',NULL,'2026-07-28 12:18:33'),(20,'BATANGAS','BATANGAS',NULL,'2026-07-28 12:18:33'),(21,'MANILA','MANILA',NULL,'2026-07-28 12:18:33');
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
INSERT INTO `service_type` VALUES (1,'ORIGIN','DOOR',NULL,'2026-07-28 12:18:33'),(2,'ORIGIN','PIER-STUFFING',NULL,'2026-07-28 12:18:33'),(3,'ORIGIN','PIER-VANOUT',NULL,'2026-07-28 12:18:33'),(4,'DESTINATION','DOOR',NULL,'2026-07-28 12:18:33'),(5,'DESTINATION','PIER-STRIPPING',NULL,'2026-07-28 12:18:33'),(6,'DESTINATION','PIER-VAN OUT',NULL,'2026-07-28 12:18:33');
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
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `serviceable_areas`
--

LOCK TABLES `serviceable_areas` WRITE;
/*!40000 ALTER TABLE `serviceable_areas` DISABLE KEYS */;
INSERT INTO `serviceable_areas` VALUES (1,1,'Port Area, Manila',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(2,2,'Banago, Bacolod',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(3,3,'Nasipit',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(4,4,'Cebu City',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(5,5,'Macabalan, Cagayan de Oro',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(6,6,'Sasa, Davao City',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(7,7,'Dumaguete City',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(8,8,'Makar, General Santos',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(9,9,'Iligan City',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(10,10,'Iloilo City',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(11,11,'Ozamis City',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(12,12,'Coron, Palawan',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(13,13,'Roxas City',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(14,14,'Caticlan, Malay',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(15,15,'Ormoc City',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(16,16,'Tagbilaran City',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(17,17,'Tacloban City',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(18,18,'Zamboanga City',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(19,19,'Puerto Princesa City',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(20,20,'Surigao City',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(21,21,'Cotabato City',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(22,22,'Bauan',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(23,23,'PORT 23 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(24,24,'PORT 24 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(25,25,'PORT 25 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(26,26,'PORT 26 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(27,27,'PORT 27 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(28,28,'PORT 28 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(29,29,'PORT 29 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(30,30,'PORT 30 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(31,31,'PORT 31 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(32,32,'PORT 32 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(33,33,'PORT 33 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(34,34,'PORT 34 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(35,35,'PORT 35 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(36,36,'PORT 36 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(37,37,'PORT 37 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(38,38,'PORT 38 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(39,39,'PORT 39 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(40,40,'PORT 40 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(41,41,'PORT 41 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(42,42,'PORT 42 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(43,43,'PORT 43 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(44,44,'PORT 44 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(45,45,'PORT 45 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(46,46,'PORT 46 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(47,47,'PORT 47 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(48,48,'PORT 48 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(49,49,'PORT 49 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(50,50,'PORT 50 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(51,51,'PORT 51 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(52,52,'PORT 52 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(53,53,'PORT 53 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(54,54,'PORT 54 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(55,55,'PORT 55 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(56,56,'PORT 56 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(57,57,'PORT 57 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(58,58,'PORT 58 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(59,59,'PORT 59 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(60,60,'PORT 60 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(61,61,'PORT 61 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(62,62,'PORT 62 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(63,63,'PORT 63 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(64,64,'PORT 64 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(65,65,'PORT 65 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(66,66,'PORT 66 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(67,67,'PORT 67 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(68,68,'PORT 68 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(69,69,'PORT 69 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(70,70,'PORT 70 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(71,71,'PORT 71 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(72,72,'PORT 72 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(73,73,'PORT 73 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(74,74,'PORT 74 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(75,75,'PORT 75 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(76,76,'PORT 76 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(77,77,'PORT 77 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(78,78,'PORT 78 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(79,79,'PORT 79 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(80,80,'PORT 80 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(81,81,'PORT 81 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(82,82,'PORT 82 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(83,83,'PORT 83 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(84,84,'PORT 84 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(85,85,'PORT 85 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(86,86,'PORT 86 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(87,87,'PORT 87 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(88,88,'PORT 88 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(89,89,'PORT 89 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(90,90,'PORT 90 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(91,91,'PORT 91 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(92,92,'PORT 92 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(93,93,'PORT 93 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(94,94,'PORT 94 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(95,95,'PORT 95 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(96,96,'PORT 96 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(97,97,'PORT 97 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(98,98,'PORT 98 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(99,99,'PORT 99 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(100,100,'PORT 100 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(101,101,'PORT 101 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(102,102,'PORT 102 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(103,103,'PORT 103 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(104,104,'PORT 104 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(105,105,'PORT 105 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(106,106,'PORT 106 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(107,107,'PORT 107 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(108,108,'PORT 108 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(109,109,'PORT 109 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(110,110,'PORT 110 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(111,111,'PORT 111 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(112,112,'PORT 112 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(113,113,'PORT 113 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(114,114,'PORT 114 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(115,115,'PORT 115 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(116,116,'PORT 116 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(117,117,'PORT 117 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(118,118,'PORT 118 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(119,119,'PORT 119 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(120,120,'PORT 120 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(121,121,'PORT 121 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(122,122,'PORT 122 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(123,123,'PORT 123 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(124,124,'PORT 124 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(125,125,'PORT 125 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(126,126,'PORT 126 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(127,127,'PORT 127 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(128,128,'PORT 128 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(129,129,'PORT 129 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(130,130,'PORT 130 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(131,131,'PORT 131 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(132,132,'PORT 132 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(133,133,'PORT 133 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(134,134,'PORT 134 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(135,135,'PORT 135 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(136,136,'PORT 136 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(137,137,'PORT 137 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(138,138,'PORT 138 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(139,139,'PORT 139 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(140,140,'PORT 140 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(141,141,'PORT 141 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(142,142,'PORT 142 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(143,143,'PORT 143 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(144,144,'PORT 144 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(145,145,'PORT 145 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(146,146,'PORT 146 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(147,147,'PORT 147 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(148,148,'PORT 148 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(149,149,'PORT 149 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(150,150,'PORT 150 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(151,151,'PORT 151 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(152,152,'PORT 152 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(153,153,'PORT 153 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(154,154,'PORT 154 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(155,155,'PORT 155 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(156,156,'PORT 156 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(157,157,'PORT 157 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(158,158,'PORT 158 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(159,159,'PORT 159 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(160,160,'PORT 160 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(161,161,'PORT 161 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(162,162,'PORT 162 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(163,163,'PORT 163 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(164,164,'PORT 164 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(165,165,'PORT 165 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(166,166,'PORT 166 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(167,167,'PORT 167 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(168,168,'PORT 168 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(169,169,'PORT 169 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(170,170,'PORT 170 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(171,171,'PORT 171 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(172,172,'PORT 172 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(173,173,'PORT 173 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(174,174,'PORT 174 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(175,175,'PORT 175 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(176,176,'PORT 176 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(177,177,'PORT 177 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(178,178,'PORT 178 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(179,179,'PORT 179 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(180,180,'PORT 180 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(181,181,'PORT 181 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(182,182,'PORT 182 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(183,183,'PORT 183 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(184,184,'PORT 184 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(185,185,'PORT 185 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(186,186,'PORT 186 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(187,187,'PORT 187 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(188,188,'PORT 188 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(189,189,'PORT 189 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(190,190,'PORT 190 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(191,191,'PORT 191 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(192,192,'PORT 192 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(193,193,'PORT 193 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(194,194,'PORT 194 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(195,195,'PORT 195 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(196,196,'PORT 196 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(197,197,'PORT 197 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(198,198,'PORT 198 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(199,199,'PORT 199 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41'),(200,200,'PORT 200 Area',1,'2026-07-28 11:20:41','2026-07-28 11:20:41');
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
INSERT INTO `sessions` VALUES ('7apiUoo0xH1wWxenRCMzSOgOlC8O5B7cZYnNeU3S',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoicVJ2S0NhWGI5VmpoNzJkOUd1RUtFQ3ZqdFNhenJEdnRtRlZFd1hDUSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjYyOiJodHRwOi8va2FyZ2FtaW5lX3Byb3RvdHlwZS50ZXN0L2FwaS9ub3RpZmljYXRpb25zL3VucmVhZC1jb3VudCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1785953647),('cOPwJe9B2VQKxWeGpu7qrH9OUbLxykXhB9G055Os',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoia3VyYVh3ZXhOQlV6eWdkcXlHZHZuRXF0UFR0ekVjeGNHQVRNVFZ1bCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly9rYXJnYW1pbmVfcHJvdG90eXBlLnRlc3QvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1785817559),('JJjWSuj65l5WByhaW5MtbHisknWml7ypXDfqXiaZ',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaUxCOG5lVndXM3RVWnNqdE0ybWRTZUJUM2lJWjJxd2lHdjB2WTZLOSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly9rYXJnYW1pbmVfcHJvdG90eXBlLnRlc3QvP2hlcmQ9cHJldmlldyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785767277),('Ke17zKvT6hoxAJsePToQAovNGSTQfTg5MBeeaIN7',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWmkzV3FCMmRUQWEwUEdNQzlNcUFZQ3pSeUZDOXZSb1ZuQzhlc1RnYiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly9rYXJnYW1pbmVfcHJvdG90eXBlLnRlc3QvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1785905071),('kLFg12GaEb30H9RnDJ7QhSCyGXaSDh16idnqP0yT',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaEY5M2VFRnNFaUI4QkJ2VVFjc1Q1ekdYVzJycVVWcDBaUGdWTlIxMCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly9rYXJnYW1pbmVfcHJvdG90eXBlLnRlc3QvP2hlcmQ9cHJldmlldyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785767277),('KoqK0TD1UmvZ5OGpVCsFDIcQFjAUcTYAB5Vfylyj',NULL,'127.0.0.1','curl/8.12.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoibnpjdFdnMU1nQVkxME1JYlU4VEx5TzBJSTcycXhwbTFKa1ROTzRLeiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1MzoiaHR0cDovL2thcmdhbWluZV9wcm90b3R5cGUudGVzdC9wYWdlX2NsaWVudE1hc3RlckZvcm0iO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo1MzoiaHR0cDovL2thcmdhbWluZV9wcm90b3R5cGUudGVzdC9wYWdlX2NsaWVudE1hc3RlckZvcm0iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1785944991),('l4Ttdqe2HrjvK46rfq02MhzbexjEGGVRNMKCoSR1',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiV0taZTc3QTh3cVJKMkZXSlFNMmtERFJlbG45RmlkY0VYaUlBb0d6RCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly9rYXJnYW1pbmVfcHJvdG90eXBlLnRlc3QvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1785767280),('PQyF2SzPTYaLvJ6ndcz2GadKb3csaz2ddCZ8uyYJ',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTGhudGZMODBUSUVvTVRGeDd2cDhTWGFkcm5TU3BGYXNsdDE1V0xaNyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly9rYXJnYW1pbmVfcHJvdG90eXBlLnRlc3QvP2hlcmQ9cHJldmlldyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785905068),('wZB7bAnsggZFmJTq0xNoPN4kOlSfrjdenIcYfqmY',3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZHY3UzU0UDh3cjFSUmhTOXp5RXRxbWt4dElKM05KUDhlaHBSSnF0TiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NjI6Imh0dHA6Ly9rYXJnYW1pbmVfcHJvdG90eXBlLnRlc3QvYXBpL25vdGlmaWNhdGlvbnMvdW5yZWFkLWNvdW50Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9',1785953621),('yLrWDousM2grL3jT1TpF1DeO4olX7dHcYEKhNMaz',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoidXJjd3RXbTU0dFRlZDNqRjh4aGdlVXhadzJGU2VrN0RNeXpMNlV1dSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly9rYXJnYW1pbmVfcHJvdG90eXBlLnRlc3QvP2hlcmQ9cHJldmlldyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785817559),('z5u5qjEfbBRMe9Zxxl6lDPeIdgJVsWAvyUe1U43K',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.28.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoia1kyZ3NObW9aU1VjYU53SlhqY1p1VmRaUjQ3NGMzVDBHSVNGMnNrMyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly9rYXJnYW1pbmV0ZXN0LnRlc3QvP2hlcmQ9cHJldmlldyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785767277);
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
INSERT INTO `setting_role` VALUES (1,'superadmin',1),(2,'admin',1),(3,'user',1),(4,'developer',1),(5,'Credit Officer',0),(6,'Sales',0);
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
INSERT INTO `special_charges` VALUES (1,'Amendment Fee',0.00,1,'2026-08-05 06:41:55','2026-08-05 06:41:55'),(2,'Backhoe Rental',0.00,1,'2026-08-05 06:41:55','2026-08-05 06:41:55'),(3,'Bullet Seal',0.00,1,'2026-08-05 06:41:55','2026-08-05 06:51:04'),(4,'Container Van Rental',0.00,1,'2026-08-05 06:41:55','2026-08-05 06:41:55'),(5,'Crane Rental',0.00,1,'2026-08-05 06:41:55','2026-08-05 06:41:55'),(6,'Demurrage',0.00,1,'2026-08-05 06:41:55','2026-08-05 06:41:55'),(7,'Documentation Assistance Fee',0.00,1,'2026-08-05 06:41:55','2026-08-05 06:41:55'),(8,'Double Handling Fee',0.00,1,'2026-08-05 06:41:55','2026-08-05 06:41:55'),(9,'Driver Assistance Fee',0.00,1,'2026-08-05 06:41:55','2026-08-05 06:41:55'),(10,'Forklift Rental',0.00,1,'2026-08-05 06:41:55','2026-08-05 06:41:55'),(11,'Foul Trip',0.00,1,'2026-08-05 06:41:55','2026-08-05 06:41:55'),(12,'Genset Rental',0.00,1,'2026-08-05 06:41:55','2026-08-05 06:41:55'),(13,'Hustling',0.00,1,'2026-08-05 06:41:55','2026-08-05 06:41:55'),(14,'Inspection Fee',0.00,1,'2026-08-05 06:41:55','2026-08-05 06:41:55'),(15,'Lashing Fee',0.00,1,'2026-08-05 06:41:55','2026-08-05 06:41:55'),(16,'Lashing Materials',0.00,1,'2026-08-05 06:41:55','2026-08-05 06:41:55'),(17,'Lift On Lift Off',0.00,1,'2026-08-05 06:41:55','2026-08-05 06:41:55'),(18,'Loader Rental',0.00,1,'2026-08-05 06:41:55','2026-08-05 06:41:55'),(19,'Overweight Fee',0.00,1,'2026-08-05 06:41:55','2026-08-05 06:41:55'),(20,'Port Charges',0.00,1,'2026-08-05 06:41:55','2026-08-05 06:41:55'),(21,'Reimbursement',0.00,1,'2026-08-05 06:41:55','2026-08-05 06:41:55'),(22,'Storage',0.00,1,'2026-08-05 06:41:55','2026-08-05 06:41:55'),(23,'Stripping',0.00,1,'2026-08-05 06:41:55','2026-08-05 06:41:55'),(24,'Stuffing',0.00,1,'2026-08-05 06:41:55','2026-08-05 06:41:55'),(25,'Trucking',0.00,1,'2026-08-05 06:41:55','2026-08-05 06:41:55'),(26,'Valuation Fee',0.00,1,'2026-08-05 06:41:55','2026-08-05 06:41:55');
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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trucking_tariffs`
--

LOCK TABLES `trucking_tariffs` WRITE;
/*!40000 ALTER TABLE `trucking_tariffs` DISABLE KEYS */;
INSERT INTO `trucking_tariffs` VALUES (1,1,1,1500.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(2,1,2,1500.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(3,1,3,1500.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(4,4,1,1500.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(5,4,2,1500.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(6,4,3,1500.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(7,5,1,1500.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(8,5,2,1500.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(9,5,3,1500.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(10,6,1,1500.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(11,6,2,1500.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(12,6,3,1500.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(13,10,1,1500.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(14,10,2,1500.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(15,10,3,1500.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(16,22,1,1500.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(17,22,2,1500.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44'),(18,22,3,1500.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44');
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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Developer','superadmin@email.com',NULL,'$2y$12$2VtSQQZw5lVRrpTC1YNAWOuc1gOp8yTRLlYupSJWM5VXlaKTgRuUm',NULL,'1',0,NULL,NULL,'2026-07-27 10:35:58','2026-07-27 14:46:00',NULL,0),(2,'Synxcel Gabby','gabriel.david@email.com',NULL,'$2y$12$wQ6iRZy5ZFCr995XoUPPKuiHXnXYvvqRDmHiA/saDk.EXY/wv/1JS',NULL,'1',0,NULL,NULL,'2026-07-27 10:35:58','2026-07-27 10:35:58',NULL,0),(3,'Synxcel Minton','minton.diaz@email.com',NULL,'$2y$12$KMUkx7bKXe.TWOUuZv.9P.38js6ezwRT0zn2sd1pT.FRwXQfKBwz2',NULL,'1',0,NULL,NULL,'2026-07-27 10:35:58','2026-07-27 10:35:58',NULL,0),(4,'Kargamine User','user.kargamine@email.com',NULL,'$2y$12$VcsMpZsGSbSjGM/v6INAyOTCKhJtwZbqyDFDW11Vi4LuV.rPF3RO2',NULL,'6',0,NULL,NULL,'2026-07-27 10:35:58','2026-07-27 15:26:54',9,0),(13,'Fritzie Tangan','fritzie.tangan@kargamine.com.ph',NULL,'$2y$12$2bzgVcIm8hfGezWfQAQOIuYdXhvPKiDsYGfQeKfunchllKH4sZdL2',NULL,'5',0,NULL,NULL,'2026-07-27 14:44:43','2026-07-27 14:46:05',8,1),(14,'Eden Palma','eden.palma@karga-container.com',NULL,'$2y$12$OiGLwi5kK78jstQKRm0F7Oo//KlZNpR.tssAFcsVM4l5SqQ2r93P2',NULL,'6',0,NULL,NULL,'2026-07-27 14:45:17','2026-07-27 14:48:03',9,1),(22,'Credit Officer','creditofficer@email.com',NULL,'$2y$12$.Sw8d2kCS5Wuo.GSvup1EeJyT7nmPfH9KkgTC73Bb3iGcCEpWN13a',NULL,'5',0,NULL,NULL,'2026-08-03 15:16:49','2026-08-03 15:16:49',NULL,0);
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vat_rates`
--

LOCK TABLES `vat_rates` WRITE;
/*!40000 ALTER TABLE `vat_rates` DISABLE KEYS */;
INSERT INTO `vat_rates` VALUES (1,20.00,'2026-07-02',NULL,1,'2026-07-28 11:21:57','2026-07-28 11:21:57','General'),(2,12.00,'2026-07-27',NULL,1,'2026-07-28 11:39:44','2026-07-28 11:39:44','General'),(3,12.00,'2026-08-05',NULL,1,'2026-08-05 16:42:49','2026-08-05 16:42:49','General'),(4,12.00,'2026-08-06',NULL,1,'2026-08-05 16:42:49','2026-08-05 16:42:49','VAT Inclusive'),(5,0.00,'2026-08-06',NULL,1,'2026-08-05 16:42:49','2026-08-05 16:42:49','VAT Exempt'),(6,3.00,'2026-08-06',NULL,1,'2026-08-05 16:42:49','2026-08-05 16:42:49','Non-VAT');
/*!40000 ALTER TABLE `vat_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vessel_voyages`
--

DROP TABLE IF EXISTS `vessel_voyages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vessel_voyages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `vessel_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  KEY `vessel_voyages_vessel_name_index` (`vessel_name`),
  CONSTRAINT `vessel_voyages_destination_port_id_foreign` FOREIGN KEY (`destination_port_id`) REFERENCES `ports` (`port_id`),
  CONSTRAINT `vessel_voyages_origin_port_id_foreign` FOREIGN KEY (`origin_port_id`) REFERENCES `ports` (`port_id`)
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

-- Dump completed on 2026-08-06  2:14:14

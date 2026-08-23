-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Aug 23, 2026 at 05:01 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `staynest_db`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_audit_log` (IN `p_table_name` VARCHAR(100), IN `p_record_id` CHAR(36), IN `p_action` VARCHAR(10), IN `p_old_values` JSON, IN `p_new_values` JSON, IN `p_performed_by` CHAR(36), IN `p_ip_address` VARCHAR(45))   BEGIN
    INSERT INTO audit_logs (table_name, record_id, action, old_values, new_values, performed_by, ip_address)
    VALUES (p_table_name, p_record_id, p_action, p_old_values, p_new_values, p_performed_by, p_ip_address);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_primary_role` (IN `p_user_id` CHAR(36))   BEGIN
    SELECT r.* FROM user_roles ur
    JOIN roles r ON ur.role_id = r.id
    WHERE ur.user_id = p_user_id AND ur.is_primary = 1 AND ur.is_active = 1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_has_permission` (IN `p_user_id` CHAR(36), IN `p_permission_slug` VARCHAR(120), OUT `p_result` TINYINT(1))   BEGIN
    SELECT EXISTS(
        SELECT 1 FROM vw_user_permissions
        WHERE user_id = p_user_id AND permission_slug = p_permission_slug
    ) INTO p_result;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `amenities`
--

CREATE TABLE `amenities` (
  `id` char(36) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `amenities`
--

INSERT INTO `amenities` (`id`, `name`, `slug`, `icon`, `category`, `is_active`, `version`, `created_at`, `updated_at`, `deleted_at`) VALUES
('426895b3-ff08-4b74-99cb-813ad1b93143', 'Daily Housekeeping', 'housekeeping', 'broom', 'basic', 1, 1, '2026-08-22 04:07:10', '2026-08-22 04:07:10', NULL),
('4d50db16-a3d2-485f-99b9-310fb795b7e4', 'Laundry Facility', 'laundry', 'tshirt', 'basic', 1, 1, '2026-08-22 04:07:10', '2026-08-22 04:07:10', NULL),
('a25bf534-ac51-40f5-a9ab-b84fc3cb82b7', 'Power Backup', 'power-backup', 'bolt', 'basic', 1, 1, '2026-08-22 04:07:10', '2026-08-22 04:13:49', NULL),
('b3791fe5-2680-450b-839a-41031e9af2e7', 'CCTV & Security', 'cctv', 'shield-alt', 'safety', 1, 1, '2026-08-22 04:07:10', '2026-08-22 04:07:10', NULL),
('b704f699-fe2d-4084-9f1b-c237b2915d8c', 'RO Drinking Water', 'ro-water', 'tint', 'basic', 1, 1, '2026-08-22 04:07:10', '2026-08-22 04:07:10', NULL),
('ba3607da-8065-4b8b-8da9-6adf440ed6df', 'Attached Washroom', 'attached-washroom', 'bath', 'room', 1, 1, '2026-08-22 04:07:10', '2026-08-22 04:07:10', NULL),
('c9ab4c4c-8dab-11f1-a4cf-1062e5a5cd6c', 'High-Speed WiFi', 'wifi', 'wifi', 'basic', 1, 1, '2026-08-01 13:20:38', '2026-08-01 13:20:38', NULL),
('c9ab4e32-8dab-11f1-a4cf-1062e5a5cd6c', 'Air Conditioning', 'ac', 'snowflake', 'premium', 1, 1, '2026-08-01 13:20:38', '2026-08-22 04:13:49', NULL),
('c9ab4f37-8dab-11f1-a4cf-1062e5a5cd6c', 'Meals / Food', 'food', 'utensils', 'basic', 1, 1, '2026-08-01 13:20:38', '2026-08-22 04:13:49', NULL),
('eea2dfbf-711c-4ecf-ab42-4a249d4d16a6', 'Vehicle Parking', 'parking', 'parking', 'basic', 1, 1, '2026-08-22 03:35:11', '2026-08-22 03:35:11', NULL),
('f8c7471a-b54b-48c7-b4db-ddd10834a020', 'Refrigerator / Fridge', 'refrigerator', 'temperature-low', 'appliance', 1, 1, '2026-08-22 03:35:11', '2026-08-22 03:35:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `api_tokens`
--

CREATE TABLE `api_tokens` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `user_id` char(36) NOT NULL,
  `name` varchar(100) NOT NULL,
  `token_hash` varchar(255) NOT NULL,
  `token_prefix` varchar(20) NOT NULL COMMENT 'First 20 chars for identification',
  `scopes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Permission scopes' CHECK (json_valid(`scopes`)),
  `last_used_at` timestamp NULL DEFAULT NULL,
  `last_used_ip` varchar(45) DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `revoked_at` timestamp NULL DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `areas`
--

CREATE TABLE `areas` (
  `id` char(36) NOT NULL,
  `city_id` char(36) NOT NULL,
  `name` varchar(150) NOT NULL,
  `slug` varchar(170) NOT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `geohash` varchar(12) DEFAULT NULL,
  `is_popular` tinyint(1) DEFAULT 0,
  `pg_count` int(11) DEFAULT 0,
  `avg_rent` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `areas`
--

INSERT INTO `areas` (`id`, `city_id`, `name`, `slug`, `pincode`, `latitude`, `longitude`, `geohash`, `is_popular`, `pg_count`, `avg_rent`, `is_active`, `version`, `created_at`, `updated_at`, `deleted_at`) VALUES
('0152e447-1d6b-44c0-b748-e731120b43ef', '0744f27a-ed42-4417-9622-2b1ae7be547d', 'Sector 62', 'sector-62-c0d9', '110002', NULL, NULL, NULL, 0, 0, NULL, 1, 1, '2026-08-22 02:28:15', '2026-08-22 02:28:15', NULL),
('0971c7e3-c9f4-4216-9c88-55d8c05d1062', '88614695-bf11-4eae-b17e-93e87271e69c', 'Sector 62 Tech Hub', 'sector-62-tech-hub-Ez6a', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, 1, '2026-08-17 21:34:07', '2026-08-17 21:34:07', NULL),
('1e1451c5-cc99-4405-8755-80d1c7440a44', '474f78f3-e154-4f6e-b720-604f250fa0c4', 'qw', 'qw-X7Ns', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, 1, '2026-08-17 21:28:32', '2026-08-17 21:28:32', NULL),
('2af87933-ab7e-4996-9559-a62c3f1ad12c', '064a7ec5-0f8e-47f1-984f-6349af165a60', 'Noida', 'noida-7a2c', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, 1, '2026-08-22 06:46:53', '2026-08-22 06:46:53', NULL),
('37359372-23e4-4c6b-9ece-3c15cd9848fb', '88614695-bf11-4eae-b17e-93e87271e69c', 'Sector 62', 'sector-62-dc4b', '201301', NULL, NULL, NULL, 0, 0, NULL, 1, 1, '2026-08-16 09:07:06', '2026-08-16 09:07:06', NULL),
('5a391a1c-1fce-431f-9740-88caebae0d5a', '00723629-e6bc-46f2-9378-60e9f91aa1db', 'Vadodara', 'vadodara-33fa', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, 1, '2026-08-22 04:33:58', '2026-08-22 04:33:58', NULL),
('6fe3e645-8648-4727-ad7a-ff88d43729d8', '0744f27a-ed42-4417-9622-2b1ae7be547d', 'South Extension', 'south-extension-2wC4', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, 1, '2026-08-16 22:47:13', '2026-08-16 22:47:13', NULL),
('7768b62e-1d35-4ba3-bb7f-a6007e43750b', '01780c3b-0a91-4f49-9248-7472457ef24b', 'Bangalore', 'bangalore-e791', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, 1, '2026-08-22 06:46:53', '2026-08-22 06:46:53', NULL),
('8d7012df-e925-4df4-935f-38b6cb50e84c', '00723629-e6bc-46f2-9378-60e9f91aa1db', 'Sector 18', 'sector-18-zReV', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, 1, '2026-08-17 21:27:25', '2026-08-17 21:27:25', NULL),
('9d92671b-df73-4f5b-bf64-ffee9a0d8302', '88614695-bf11-4eae-b17e-93e87271e69c', 'Dadri', 'dadri-9WDv', '201309', NULL, NULL, NULL, 0, 0, NULL, 1, 1, '2026-08-18 10:37:24', '2026-08-18 10:37:24', NULL),
('b4cba635-508b-491c-9359-062184da0f31', '064a7ec5-0f8e-47f1-984f-6349af165a60', 'Sector 62', 'sector-62-t7CG', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, 1, '2026-08-16 22:47:13', '2026-08-16 22:47:13', NULL),
('bde1f3a1-2d01-4d43-8f43-cf6c41387374', '01780c3b-0a91-4f49-9248-7472457ef24b', 'Indiranagar', 'indiranagar-8CyV', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, 1, '2026-08-16 22:47:13', '2026-08-16 22:47:13', NULL),
('c3b4b1bc-1344-4daf-9bd3-f1de91498756', '0744f27a-ed42-4417-9622-2b1ae7be547d', 'लोधी एस्टेट', 'lthha-esatata-805a', '110003', NULL, NULL, NULL, 0, 0, NULL, 1, 1, '2026-08-16 21:17:43', '2026-08-16 21:17:43', NULL),
('c9afff0f-8dab-11f1-a4cf-1062e5a5cd6c', 'c9aef033-8dab-11f1-a4cf-1062e5a5cd6c', 'Indiranagar', 'indiranagar', '560038', NULL, NULL, NULL, 1, 0, NULL, 1, 1, '2026-08-01 13:20:38', '2026-08-01 13:20:38', NULL),
('c9b001cd-8dab-11f1-a4cf-1062e5a5cd6c', 'c9aef033-8dab-11f1-a4cf-1062e5a5cd6c', 'HSR Layout', 'hsr-layout', '560102', NULL, NULL, NULL, 1, 0, NULL, 1, 1, '2026-08-01 13:20:38', '2026-08-01 13:20:38', NULL),
('f9ac6e38-eeee-43d1-a5f1-2e5a5bee1533', '0744f27a-ed42-4417-9622-2b1ae7be547d', 'Delhi', 'delhi-e153', NULL, NULL, NULL, NULL, 0, 0, NULL, 1, 1, '2026-08-22 06:46:53', '2026-08-22 06:46:53', NULL),
('ffbf3945-8a9f-4b9b-bb4a-3e7fc9629f79', '0744f27a-ed42-4417-9622-2b1ae7be547d', 'Lodhi Estate', 'lodhi-estate-c8fd', '110003', NULL, NULL, NULL, 0, 0, NULL, 1, 1, '2026-08-16 09:54:59', '2026-08-16 09:54:59', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` char(36) NOT NULL,
  `table_name` varchar(100) NOT NULL,
  `record_id` char(36) DEFAULT NULL,
  `action` enum('insert','update','delete','restore') NOT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `performed_by` char(36) DEFAULT NULL,
  `performed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `beds`
--

CREATE TABLE `beds` (
  `id` char(36) NOT NULL,
  `room_id` char(36) NOT NULL,
  `bed_number` varchar(50) NOT NULL,
  `bed_type` varchar(50) DEFAULT 'single',
  `status` enum('available','occupied','maintenance') DEFAULT 'available',
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `beds`
--

INSERT INTO `beds` (`id`, `room_id`, `bed_number`, `bed_type`, `status`, `is_active`, `version`, `created_at`, `updated_at`, `deleted_at`) VALUES
('0047e35d-7489-4e4c-88b7-f0015fe66fe9', '74f9c09b-2bb6-4b8e-902c-4b133b79f983', '101-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('013cf786-94b2-4de4-bb81-455b3f3d27ea', 'abd4198c-4fdc-4943-acd4-9d4c4982d71a', '102-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('025526e9-814a-433e-b97b-195434965299', 'a46e2c0c-540d-4a1a-ba1e-111fc78bb61b', '103-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('04158546-7917-401d-9136-ae93cf427492', 'cfa051a8-bb39-4a2e-b213-7d83ea9af364', '103-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('0a863321-fafb-4898-9bf2-8b963b14fef3', 'cf26b6cb-273f-4b3b-baa9-5dce475d70ec', '103-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('0c706e33-7225-41f7-911a-e5e6246c188e', '1db581a8-a3af-4a29-ac67-f9681b04489f', '103-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('0d2c27f1-9a6a-41fd-a341-90e6f29fdb0b', 'a0fa2a8e-3937-44ff-ae09-12b4a248dced', '101-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('17ab6e5f-3b75-49d0-a34c-d1935ba54dfd', 'bd480841-f1e3-42f6-9d45-66b1d5622f74', '103-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('18c5a6f6-fef1-402a-8631-fd752141c3f7', '9ffd0853-7a69-47d7-8271-1cc1874109ec', '103-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('1aa34069-444d-4764-a40e-4294341dff9d', '42f4c112-2da1-4285-8330-6eb9bfcce441', '102-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('22218836-a749-40d6-8313-27bf74e42c5b', '6c73c5e0-2ceb-4286-a7e2-7dcbd5497a3c', '102-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('27f7da83-28e4-45a3-85f0-21f4f71eae2f', '1f9f6784-34b0-4070-bfde-34298ff8b04b', '103-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('2ad28e52-2bd1-46bb-b96f-a2e8cc3fc33b', '6d16d373-c365-4da2-8788-05754472d3b8', '102-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('3019078d-275d-4b7d-8365-9c065dcd88a8', 'db816900-9ce4-4699-abf7-5c1113d0438c', '103-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('317eabcf-fba7-4bc8-9f2a-41c1e9a02b3d', '6ebc4590-ce6d-4557-aa74-b1ef5603ed23', '101-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('3303256d-256f-4ccf-9ca9-3ce3b62a8530', '365c0245-2e31-410e-a2ba-037aae2fd328', '103-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('332fdd6e-ce66-4b08-bcd9-b52370ac545d', 'cf26b6cb-273f-4b3b-baa9-5dce475d70ec', '103-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('3667940c-a6c8-413d-84d7-76d07fe922d0', '1eafd830-319b-4c82-ad3b-a3ed49d2daf8', '101-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('449f0bc4-3e57-4565-9129-1a267b73f4df', '1667bb02-e58f-4a26-bacd-73760ca9ec2d', '102-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('451a5d23-1327-41da-84d0-bc15b7276dcb', 'a0fa2a8e-3937-44ff-ae09-12b4a248dced', '101-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('453745cc-83ee-47e2-af03-4ce04607d199', '4f5b5260-3c0c-481c-b613-170b26306abb', '102-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('4b92096b-23c3-4c75-aaa0-c035a469c853', 'fe6c84bc-a2b5-42dc-b469-03830c2e9592', '103-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('4c40ff46-d92f-4bfd-906f-a167cfe8f8b0', '500162b1-2c4b-44e2-a24c-0c87ddee973b', '101-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('4fcd4d90-c0fb-4017-8663-905fb824e198', 'db816900-9ce4-4699-abf7-5c1113d0438c', '103-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('50a58073-fc7e-466f-b796-a8f9028223de', '623d7c36-cf4c-49ec-989b-d08737c70c30', '103-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('51efb018-c21e-4b25-a48a-f911f14b5909', '1b44527b-6897-433f-9616-f83dea1d8706', '101-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('5241ed27-8cf7-44ed-8282-d7b8781beac8', 'fec9e281-edc7-468c-b949-2996385520da', '101-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('54a47cf7-5889-46c5-8359-834a314172b5', 'cfa051a8-bb39-4a2e-b213-7d83ea9af364', '103-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('56536227-17c3-4b9d-a54a-34a6c6f40c99', '975d0466-1787-42c8-9587-73d1c56de7e7', '102-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('5821033d-9603-4df3-aae0-fcf077f6bfd7', '037ac521-05b4-48ed-a59e-2104992766d5', '101-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('58ce3137-b745-4ce5-bba7-26a688634247', 'cb11b740-35ac-474f-8046-14de6a1c78ca', '103-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('5f3b6ec7-d7fc-4757-9118-8aaf31dcbbc1', '9ec6c483-4b51-4c5e-86d7-0ed9e25e0985', '102-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('66277e84-4b39-44f4-b9f4-91fa98d2a564', '94491367-3520-43fb-a303-d9218482fb9c', '101-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('69373451-b81e-4408-bf4c-34438780260a', '4f5b5260-3c0c-481c-b613-170b26306abb', '102-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('6b99dc63-be04-43c2-bc6e-a17dcfd86396', 'fc759d5d-fab7-4304-b9bd-81c4c166b61a', '101-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('6cad27d4-dbf8-41c4-b4e9-0fd8a20f2576', '9a15f0aa-6b09-4429-80ba-cecc421a7260', '101-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('6e974b80-33a6-4a17-b58c-793d3ed4d72a', '1f9f6784-34b0-4070-bfde-34298ff8b04b', '103-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('7161ed09-50cc-4ec5-8567-b7a04782a6c5', 'bcd56d71-f65c-4705-8f0c-9b465630c109', '102-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('7228f71e-12a1-48a8-b065-d5241b7081f6', '910a0ff3-87ca-4dca-b15f-468f910b2629', '102-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('725c9bb8-f298-424f-bd25-1ebcd8d4b086', 'cb11b740-35ac-474f-8046-14de6a1c78ca', '103-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('78ae1fdc-4a40-45f0-9c92-d77ab04d9b4b', '6d16d373-c365-4da2-8788-05754472d3b8', '102-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('7982f968-270e-43f7-8008-e2f49e50567e', 'fec9e281-edc7-468c-b949-2996385520da', '101-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('80172e36-4922-4828-9bd9-937e6d46be7d', '3c046a51-5abf-4372-98f2-59e6aff355b1', '101-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('8713971a-92e2-4b61-82b4-a0ab1bd56a2a', '6c73c5e0-2ceb-4286-a7e2-7dcbd5497a3c', '102-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('87504d8c-124d-44b0-a294-d4afe69687f3', '42f4c112-2da1-4285-8330-6eb9bfcce441', '102-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('88c20925-037d-4bd6-9af9-8126c65528b9', 'a46e2c0c-540d-4a1a-ba1e-111fc78bb61b', '103-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('89c817fd-61f7-42c7-b40d-4be48b998222', '54e1e183-7b94-49a0-a89c-e67e2036c3d3', '101-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('8a08ae19-e4cf-449d-b7ec-4b0a5c37f7c6', 'f8f1b957-d6bf-49ed-a6e5-e4502ee62a1d', '102-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('8a0bdcfe-a0db-4f29-ac5e-db44e065a904', '623d7c36-cf4c-49ec-989b-d08737c70c30', '103-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('8a9c390f-3b34-4beb-959b-34fcc4558736', '388afdad-06c3-4c13-8c2f-9ce3eb9814a3', '102-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('902ebaba-3315-4dbd-b674-73c6129bac6e', '16d9f70c-f149-47f6-ab36-2a1f90be8be7', '103-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('90da5071-3caf-4cd5-84f1-a8fb2838f3a5', '1b44527b-6897-433f-9616-f83dea1d8706', '101-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('910e2093-a85f-4360-8aac-8419c5e09081', '74f9c09b-2bb6-4b8e-902c-4b133b79f983', '101-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('92746902-aac4-4f31-851c-ad1c2c394a29', 'f8f1b957-d6bf-49ed-a6e5-e4502ee62a1d', '102-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('943d9e56-52fc-4245-b481-17fed50eeb23', '388afdad-06c3-4c13-8c2f-9ce3eb9814a3', '102-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('95040884-c831-411d-975c-7e1c75b170ce', 'abd4198c-4fdc-4943-acd4-9d4c4982d71a', '102-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('98ec9a48-654d-4d84-bcc3-8654cc0db7ea', 'fc759d5d-fab7-4304-b9bd-81c4c166b61a', '101-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('9afdffe1-37bf-486f-929f-a504e1f0211d', '1eafd830-319b-4c82-ad3b-a3ed49d2daf8', '101-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('a4c36677-60c5-4645-b781-b12d300faa81', '54e1e183-7b94-49a0-a89c-e67e2036c3d3', '101-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('a710f325-7a3e-46cf-856a-d53ea19c0fa6', '500162b1-2c4b-44e2-a24c-0c87ddee973b', '101-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('a9400774-119c-43fa-8df7-d68ac1b8ea9c', '1667bb02-e58f-4a26-bacd-73760ca9ec2d', '102-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('ab31b3fa-b5b2-459b-80d7-b425e96bd07a', 'ac8bfcff-57c9-4a04-becd-3eea924d9769', '102-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('af35f0f4-e374-4f1f-8987-a4409cb7b462', '1db581a8-a3af-4a29-ac67-f9681b04489f', '103-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('b0230ac0-03b9-4441-9720-6294e65e5069', '975d0466-1787-42c8-9587-73d1c56de7e7', '102-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('b0b320d6-0031-4d09-a71e-699129039ed3', '6ebc4590-ce6d-4557-aa74-b1ef5603ed23', '101-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('b2e1d4ad-24dc-44e2-859b-f77ded0c3744', '678659ac-99ac-4766-9d41-47460755df81', '101-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('bad176ef-5972-467b-8e39-b8db86336e6a', '94491367-3520-43fb-a303-d9218482fb9c', '101-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('bfe590b5-f810-48a5-8689-a083b30d9247', 'f9d9423f-1b69-4d6d-927a-113b8d0a95cf', '102-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('c103f17a-9458-40c1-a111-9d0b1fdce0b0', '60dc39a6-b3eb-4813-966e-c3d9382253cc', '103-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('c12a1a94-f837-41e8-886e-70676f0c5729', 'ac8bfcff-57c9-4a04-becd-3eea924d9769', '102-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('c187234c-bab5-4ddb-9125-061f7a5cd9ef', '678659ac-99ac-4766-9d41-47460755df81', '101-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('d3472836-0d2b-43bb-9e30-ab65ba34f3b9', '910a0ff3-87ca-4dca-b15f-468f910b2629', '102-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('e13582cf-1026-48cd-9615-55f1d2351295', '60dc39a6-b3eb-4813-966e-c3d9382253cc', '103-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('e446e487-85d8-4f6c-aedc-c6fdca946947', 'bcd56d71-f65c-4705-8f0c-9b465630c109', '102-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('e4570ec2-3ac9-4742-a0eb-1bb94f94b8fd', '037ac521-05b4-48ed-a59e-2104992766d5', '101-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('e56752ee-ab21-4e6e-877b-9dedcc922c49', '9ffd0853-7a69-47d7-8271-1cc1874109ec', '103-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('e64d9e76-bad9-492b-aabf-70626d486c9e', '9a15f0aa-6b09-4429-80ba-cecc421a7260', '101-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('eb2b12d1-254b-48f5-afe3-853dd9ff4d70', '365c0245-2e31-410e-a2ba-037aae2fd328', '103-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('eced2c5d-3a73-4756-9c36-bb70b11a6cb1', '16d9f70c-f149-47f6-ab36-2a1f90be8be7', '103-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('f342a7f8-2fbc-4156-b614-72c1ea0b228e', 'bd480841-f1e3-42f6-9d45-66b1d5622f74', '103-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('f6f31565-fa13-404d-b7a0-05dcdf49469e', '3c046a51-5abf-4372-98f2-59e6aff355b1', '101-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('fa4bd1d4-0e0e-4637-b9ab-e6ade893d0f2', 'f9d9423f-1b69-4d6d-927a-113b8d0a95cf', '102-B', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('fcdef0bd-662f-4fbc-b3b2-c684a0a0068f', 'fe6c84bc-a2b5-42dc-b469-03830c2e9592', '103-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('fda60f28-595c-4c37-ae41-f30d00c0e7ac', '9ec6c483-4b51-4c5e-86d7-0ed9e25e0985', '102-A', 'single', 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bed_types`
--

CREATE TABLE `bed_types` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blocks`
--

CREATE TABLE `blocks` (
  `id` char(36) NOT NULL,
  `property_id` char(36) NOT NULL,
  `name` varchar(100) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blocks`
--

INSERT INTO `blocks` (`id`, `property_id`, `name`, `is_active`, `version`, `created_at`, `updated_at`, `deleted_at`) VALUES
('0104e75f-d2ef-45f1-af32-88648c7b90fe', 'e8564421-0b10-4590-9a4a-8b5281b936e2', 'Main Block', 1, 1, '2026-08-22 06:49:58', '2026-08-22 06:49:58', NULL),
('0d994f14-9714-4c47-a773-719ad3eacf8d', 'c95791bd-c085-4e63-8075-5207d5fa7cbf', 'Main Block', 1, 1, '2026-08-16 22:47:51', '2026-08-16 22:47:51', NULL),
('1a383049-d9fc-4aec-a47d-6d4d0f7015da', '11a5732c-6a26-4a58-bc43-aeab0f95a8cc', 'Main Block A', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('1cdcd451-d68d-40ee-91a5-c772bfc1f6bb', '807319ca-3418-4c12-b110-b66e00e7ab92', 'Main Block A', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('215dec02-0c0f-423d-a251-bbfce9510584', 'e4614af2-142d-4c05-bc61-0c1f275d37c3', 'Main Block', 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL),
('26ad438a-8954-4e75-beb9-f29ad6649993', 'c95791bd-c085-4e63-8075-5207d5fa7cbf', 'Main Block', 1, 1, '2026-08-16 22:48:40', '2026-08-16 22:48:40', NULL),
('3269c2cc-d32e-4ef0-8682-25fb3faafb97', '3527dd94-3289-48ef-b7e1-a0c8c347ca05', 'Main Block', 1, 1, '2026-08-22 06:49:58', '2026-08-22 06:49:58', NULL),
('3bcd7912-53b4-4d84-a005-2ad25dbaf060', 'dc5b0828-7cdc-4cfc-8f13-3c75bd25b248', 'Main Block A', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('49d6df3c-8c92-48d6-813b-6c556498e3ba', '94a9038d-3e4f-4153-aa79-eb83d2542f6a', 'Main Block', 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL),
('4c3018e0-0a4e-45b1-a9fd-adec2a8aea23', 'd9f5e991-207a-4023-b6fe-9deb3aacfc33', 'Main Block A', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('4cf87eb9-5c94-4adf-bfcc-0247bfeb54b5', 'da080740-0c28-40e2-a691-332a70e0f27f', 'Main Block A', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('514aa38c-a007-40c5-816f-d2f702a268f3', '4ef670b3-de31-415f-b061-deb50a89877d', 'Main Block', 1, 1, '2026-08-22 06:46:53', '2026-08-22 06:46:53', NULL),
('56faed01-a454-482f-8a3c-deccd82f521f', '2ce06724-f6aa-4b6d-b12d-df72a3492eb9', 'Main Block A', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('5cef5170-d75b-43a1-87ed-dfac51b5a807', '93220e71-23ec-43df-8577-272c5c873711', 'Main Block A', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('5f307748-7d02-4860-b462-88f9c1e76fb8', '6ca9f328-86ac-40d1-9d98-4a9c3823986f', 'Main Block A', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('68e8e975-0bdd-4723-b472-588e771fef8e', 'a0050c80-7752-43b1-9ff2-8b86044fe7fd', 'Main Block', 1, 1, '2026-08-22 06:48:05', '2026-08-22 06:48:05', NULL),
('6cb88170-9562-4b23-8b51-9f69c8ed1e8a', '51c35024-b974-4e62-8a4f-06f7a3282321', 'Main Block', 1, 1, '2026-08-22 06:48:05', '2026-08-22 06:48:05', NULL),
('6e3a6262-80bc-4860-9a71-61864b0508d4', '5de5b687-9316-459a-b3ed-481bf1b8a03f', 'Main Block', 1, 1, '2026-08-22 06:46:53', '2026-08-22 06:46:53', NULL),
('703b9d20-52b8-441e-8293-cd025e524d4f', 'c9b8b7b3-8dab-11f1-a4cf-1062e5a5cd6c', 'Main Block A', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('71959ff1-5e21-4bd3-a263-012d8fbd41da', '9f495350-cc5c-4e44-a254-4fa36b179ac6', 'Main Block', 1, 1, '2026-08-22 06:48:05', '2026-08-22 06:48:05', NULL),
('73342864-19f5-4d81-a687-f693f4f94a90', 'c95791bd-c085-4e63-8075-5207d5fa7cbf', 'Main Block', 1, 1, '2026-08-16 22:48:08', '2026-08-16 22:48:08', NULL),
('7d1f0949-53d6-4712-876b-9c0baa7dd9b2', 'bea2204f-a5cc-4931-a06a-0559b191d791', 'Main Block', 1, 1, '2026-08-22 06:43:45', '2026-08-22 06:43:45', NULL),
('7d4de19d-4e48-45e4-bd4a-40e7aaa18ae3', 'bd1e05f8-4124-4df5-81cd-79c794ba6432', 'Main Block', 1, 1, '2026-08-22 06:55:03', '2026-08-22 06:55:03', NULL),
('8c43980c-27ea-4f46-994c-29c8747cb20b', 'ed514de7-d9e2-45fc-876c-c192a5d6fd01', 'Main Block A', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('9cc811c4-715a-470b-9d19-2241d6500d14', 'c95791bd-c085-4e63-8075-5207d5fa7cbf', 'Main Block', 1, 1, '2026-08-16 22:48:27', '2026-08-16 22:48:27', NULL),
('a1aa30f9-ea62-454d-a32c-6d0433c757cc', 'c95791bd-c085-4e63-8075-5207d5fa7cbf', 'Main Block', 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL),
('a8ab51c3-6058-449f-aba6-9754d85e367a', '4ab9cbde-a774-4cb1-929e-7c116716030a', 'Main Block A', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('a92d5cc2-c879-477b-b8b8-d4af9ad847b5', '2182587c-9095-41b4-a9d2-62eb7248df62', 'Main Block', 1, 1, '2026-08-22 08:52:06', '2026-08-22 08:52:06', NULL),
('ae808af5-a38c-406e-8718-73b1ab108f60', 'd6779d7e-b9a0-45ac-ba92-3ced3ead699c', 'Main Block', 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL),
('bc6c9fb2-d120-482d-9d66-43cdce02a4c6', '7d787a88-1b74-481f-9061-8867f1babf60', 'Main Block', 1, 1, '2026-08-22 06:46:53', '2026-08-22 06:46:53', NULL),
('c961d697-ab6a-43e6-a5a0-b0ad8320686e', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', 'Main Block', 1, 1, '2026-08-22 04:58:01', '2026-08-22 04:58:01', NULL),
('d14ac0b8-437a-471d-bd0a-74266b6df3d3', '35f0faf4-d7c6-4709-87fd-d1f2d43758bb', 'Main Block A', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('d1836c43-a1f6-4a6a-9833-9be930227f81', '175827b9-c7af-4c56-90fe-f0eb3898a6cf', 'Main Block A', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('db7c9cfe-7813-4dc8-92c1-ee9027861a4e', '7c77e036-c1c7-4c5a-b688-122ff0f93098', 'Main Block', 1, 1, '2026-08-22 06:49:58', '2026-08-22 06:49:58', NULL),
('e5fdf9d9-7515-41c2-9f00-d71a7973cea6', '8b77164a-57ed-4a7b-8443-fafd3daa06db', 'Main Block', 1, 1, '2026-08-22 06:45:03', '2026-08-22 06:45:03', NULL),
('f6dc0441-e467-44c3-be96-92d874562345', '69b8669e-a7c6-44be-98b5-491ac4657915', 'Main Block', 1, 1, '2026-08-22 05:00:28', '2026-08-22 05:00:28', NULL),
('f97198c5-cbc1-4a80-8e20-8d7fa130f202', '882021ce-cdef-4461-b250-39ff6b882c13', 'Main Block A', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` char(36) NOT NULL,
  `booking_id` varchar(20) NOT NULL,
  `user_id` char(36) NOT NULL,
  `tenant_name` varchar(150) DEFAULT NULL,
  `tenant_phone` varchar(30) DEFAULT NULL,
  `tenant_email` varchar(150) DEFAULT NULL,
  `property_id` char(36) NOT NULL,
  `room_id` char(36) DEFAULT NULL,
  `bed_id` char(36) DEFAULT NULL,
  `room_type_name` varchar(100) DEFAULT NULL,
  `broker_id` char(36) NOT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date DEFAULT NULL,
  `duration_months` int(11) DEFAULT 11,
  `base_rent` decimal(10,2) NOT NULL,
  `security_deposit` decimal(10,2) DEFAULT 0.00,
  `maintenance_charges` decimal(10,2) DEFAULT 0.00,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `coupon_code` varchar(50) DEFAULT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `paid_amount` decimal(12,2) DEFAULT 0.00,
  `payment_status` enum('pending','partial','paid','refunded') DEFAULT 'pending',
  `booking_status` enum('pending','confirmed','cancelled','completed','moved_out') DEFAULT 'pending',
  `broker_approval` enum('pending','approved','rejected') DEFAULT 'pending',
  `cancellation_reason` text DEFAULT NULL,
  `special_requests` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `updated_by` char(36) DEFAULT NULL,
  `deleted_by` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `booking_id`, `user_id`, `tenant_name`, `tenant_phone`, `tenant_email`, `property_id`, `room_id`, `bed_id`, `room_type_name`, `broker_id`, `check_in_date`, `check_out_date`, `duration_months`, `base_rent`, `security_deposit`, `maintenance_charges`, `discount_amount`, `coupon_code`, `total_amount`, `paid_amount`, `payment_status`, `booking_status`, `broker_approval`, `cancellation_reason`, `special_requests`, `is_active`, `version`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`) VALUES
('0f432925-54b2-43a4-bbc4-7cf8f32e50f8', 'BK-GHU1TBU', 'bd238724-7aee-4107-8833-384acfcfb0be', NULL, NULL, NULL, '11a5732c-6a26-4a58-bc43-aeab0f95a8cc', '037ac521-05b4-48ed-a59e-2104992766d5', '5821033d-9603-4df3-aae0-fcf077f6bfd7', NULL, 'be8170ed-18c1-47fd-8313-da286315bc89', '2026-08-28', '2027-07-17', 11, 7500.00, 15000.00, 500.00, 0.00, NULL, 22500.00, 22500.00, 'paid', 'confirmed', 'approved', NULL, NULL, 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('115fe2fe-7cee-4835-ad7d-bbec261370a2', 'BK-2WXFSNB7', 'e62e7b98-e262-4c3c-a366-2989f5b87bbe', NULL, NULL, NULL, 'd6779d7e-b9a0-45ac-ba92-3ced3ead699c', '037ac521-05b4-48ed-a59e-2104992766d5', '0047e35d-7489-4e4c-88b7-f0015fe66fe9', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-23', '2026-11-21', 3, 9500.00, 19000.00, 0.00, 0.00, NULL, 9500.00, 9500.00, 'paid', 'confirmed', 'approved', NULL, NULL, 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL, NULL, NULL, NULL),
('12742f8e-8f14-41a1-ae95-d361c5f2f660', 'BK-YAO3ZOAN', 'bd238724-7aee-4107-8833-384acfcfb0be', NULL, NULL, NULL, 'd6779d7e-b9a0-45ac-ba92-3ced3ead699c', '037ac521-05b4-48ed-a59e-2104992766d5', '0047e35d-7489-4e4c-88b7-f0015fe66fe9', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-17', '2026-11-15', 3, 9500.00, 19000.00, 0.00, 0.00, NULL, 9500.00, 0.00, 'paid', 'confirmed', 'approved', NULL, NULL, 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:51:37', NULL, NULL, NULL, NULL),
('14b78bc8-a96b-43db-9e5e-fd1cdf89e81c', 'BK-9TNLN9ZT', '2d28d9da-2144-487d-997e-68b5624706f1', NULL, NULL, NULL, '94a9038d-3e4f-4153-aa79-eb83d2542f6a', '037ac521-05b4-48ed-a59e-2104992766d5', '0047e35d-7489-4e4c-88b7-f0015fe66fe9', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-25', '2026-11-23', 3, 14500.00, 29000.00, 0.00, 0.00, NULL, 14500.00, 0.00, 'pending', 'pending', 'pending', NULL, NULL, 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL, NULL, NULL, NULL),
('14bdbd12-5ebe-4e01-bccd-5105c1a74def', 'BK-XBG9DWJ', 'b9585777-9009-44f4-a3f7-e316abbaf0d2', NULL, NULL, NULL, '882021ce-cdef-4461-b250-39ff6b882c13', '365c0245-2e31-410e-a2ba-037aae2fd328', '3303256d-256f-4ccf-9ca9-3ce3b62a8530', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-31', '2027-07-17', 11, 699.00, 15000.00, 500.00, 0.00, NULL, 15699.00, 15699.00, 'paid', 'confirmed', 'approved', NULL, NULL, 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('1cb997d0-f2be-4566-9f49-816f6dda8a39', 'BK-SW1FOKOQ', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'Rahul Admin', '9876543210', 'admin@staynest.com', 'c95791bd-c085-4e63-8075-5207d5fa7cbf', NULL, NULL, 'Standard Stay', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-23', '2027-07-23', 11, 8500.00, 17000.00, 0.00, 0.00, NULL, 25500.00, 0.00, 'pending', 'cancelled', 'pending', 'aDDW', NULL, 1, 1, '2026-08-22 09:11:35', '2026-08-22 09:43:44', NULL, NULL, NULL, NULL),
('1fc56ed9-b4e6-4160-a8d9-12c50df309a1', 'BK-QPH2ME0', 'e62e7b98-e262-4c3c-a366-2989f5b87bbe', NULL, NULL, NULL, '35f0faf4-d7c6-4709-87fd-d1f2d43758bb', '1b44527b-6897-433f-9616-f83dea1d8706', '90da5071-3caf-4cd5-84f1-a8fb2838f3a5', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-18', '2027-07-17', 11, 412294.00, 15000.00, 500.00, 0.00, NULL, 427294.00, 427294.00, 'paid', 'confirmed', 'approved', NULL, NULL, 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('232d7c63-a40f-4edd-8b9b-229e74301487', 'BK-TI9A2UVS', 'e62e7b98-e262-4c3c-a366-2989f5b87bbe', NULL, NULL, NULL, 'e4614af2-142d-4c05-bc61-0c1f275d37c3', '037ac521-05b4-48ed-a59e-2104992766d5', '0047e35d-7489-4e4c-88b7-f0015fe66fe9', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-23', '2026-11-21', 3, 11000.00, 22000.00, 0.00, 0.00, NULL, 11000.00, 11000.00, 'paid', 'confirmed', 'approved', NULL, NULL, 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL, NULL, NULL, NULL),
('258f1689-0c86-4ed6-95b9-000670fc0f2e', 'BK-ZLOCRDGK', '035e8b11-9be9-402a-a986-f95e4aea9fe9', 'test user', '9876543210', 'adminer@staynest.com', '2ce06724-f6aa-4b6d-b12d-df72a3492eb9', NULL, NULL, 'Twin Sharing', 'a0ea13d9-49a7-4e08-b364-7f0f00b97495', '2026-08-25', '2027-07-25', 11, 8500.00, 8500.00, 0.00, 0.00, NULL, 17000.00, 0.00, 'pending', 'confirmed', 'approved', NULL, 'Quiet room preferred on 2nd floor.', 1, 1, '2026-08-22 08:19:59', '2026-08-22 08:19:59', NULL, NULL, NULL, NULL),
('31172949-5d35-48e2-9b2f-3383febb447b', 'BK-ULZ1LLFQ', 'b9585777-9009-44f4-a3f7-e316abbaf0d2', NULL, NULL, NULL, '94a9038d-3e4f-4153-aa79-eb83d2542f6a', '037ac521-05b4-48ed-a59e-2104992766d5', '0047e35d-7489-4e4c-88b7-f0015fe66fe9', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-19', '2026-11-17', 3, 14500.00, 29000.00, 0.00, 0.00, NULL, 14500.00, 14500.00, 'paid', 'confirmed', 'approved', NULL, NULL, 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL, NULL, NULL, NULL),
('3a27a508-b243-4263-89cb-c30fc6e6eb68', 'BK-T2DVLW1C', '63576b41-af34-452b-b4a5-2b603bda3fe6', 'Rajesh Sharma', '9876500002', 'rajesh.sharma@staynest.com', '2ce06724-f6aa-4b6d-b12d-df72a3492eb9', NULL, NULL, 'Triple Sharing', 'a0ea13d9-49a7-4e08-b364-7f0f00b97495', '2026-08-24', '2027-07-24', 11, 8500.00, 8500.00, 0.00, 0.00, NULL, 17000.00, 0.00, 'pending', 'pending', 'pending', NULL, NULL, 1, 1, '2026-08-23 07:05:52', '2026-08-23 07:05:52', NULL, NULL, NULL, NULL),
('48fd24fb-f90f-442e-8ddc-b2ae87467de7', 'BK-J3HVT5Q7', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'Rahul Admin', '9876543210', 'admin@staynest.com', '2182587c-9095-41b4-a9d2-62eb7248df62', NULL, NULL, 'Standard Stay', '63576b41-af34-452b-b4a5-2b603bda3fe6', '2026-08-23', '2027-07-23', 11, 10000.00, 43.00, 1242.00, 0.00, NULL, 10043.00, 0.00, 'pending', 'completed', 'approved', NULL, 'scsa', 1, 1, '2026-08-22 08:52:43', '2026-08-22 08:55:55', NULL, NULL, NULL, NULL),
('5591eb08-157f-49ee-83bb-61061d8cb183', 'BK-JJQ40CN', 'c9098d68-9d79-421f-a1f5-83fc4ec8fbda', NULL, NULL, NULL, '11a5732c-6a26-4a58-bc43-aeab0f95a8cc', '60dc39a6-b3eb-4813-966e-c3d9382253cc', 'c103f17a-9458-40c1-a111-9d0b1fdce0b0', NULL, 'dc12b8a0-fcec-4a52-b96f-55f58e7b5fe9', '2026-08-19', '2027-07-17', 11, 7500.00, 15000.00, 500.00, 0.00, NULL, 22500.00, 0.00, 'pending', 'pending', 'pending', NULL, NULL, 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('575ce069-b0a4-4bac-ada8-d9a4a5ea4fac', 'BK-WI26JHSB', '2d28d9da-2144-487d-997e-68b5624706f1', NULL, NULL, NULL, 'e4614af2-142d-4c05-bc61-0c1f275d37c3', '037ac521-05b4-48ed-a59e-2104992766d5', '0047e35d-7489-4e4c-88b7-f0015fe66fe9', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-25', '2026-11-23', 3, 11000.00, 22000.00, 0.00, 0.00, NULL, 11000.00, 0.00, 'pending', 'pending', 'pending', NULL, NULL, 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL, NULL, NULL, NULL),
('5a26ba89-afc4-4386-b36d-7f3c308957e2', 'BK-WCMDGJX', '67d8bdcd-4ad7-4d0b-b85f-25a9afa82499', NULL, NULL, NULL, 'd9f5e991-207a-4023-b6fe-9deb3aacfc33', '42f4c112-2da1-4285-8330-6eb9bfcce441', '1aa34069-444d-4764-a40e-4294341dff9d', NULL, 'dc12b8a0-fcec-4a52-b96f-55f58e7b5fe9', '2026-08-27', '2027-07-17', 11, 8500.00, 15000.00, 500.00, 0.00, NULL, 23500.00, 23500.00, 'paid', 'completed', 'pending', NULL, NULL, 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('5b538c1f-a2cb-45de-aba2-1eb9af9abe6a', 'BK-80ZWFGWM', 'e62e7b98-e262-4c3c-a366-2989f5b87bbe', NULL, NULL, NULL, 'c95791bd-c085-4e63-8075-5207d5fa7cbf', '037ac521-05b4-48ed-a59e-2104992766d5', '0047e35d-7489-4e4c-88b7-f0015fe66fe9', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-23', '2026-11-21', 3, 8500.00, 17000.00, 0.00, 0.00, NULL, 8500.00, 8500.00, 'paid', 'confirmed', 'approved', NULL, NULL, 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL, NULL, NULL, NULL),
('5d46b024-a9d5-4ccc-b8f6-38eed7463718', 'BK-BJC3IGYX', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'Rahul Admin', '9876543210', 'admin@staynest.com', 'c95791bd-c085-4e63-8075-5207d5fa7cbf', NULL, NULL, 'Standard Stay', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-23', '2027-07-23', 11, 8500.00, 17000.00, 0.00, 0.00, NULL, 25500.00, 0.00, 'pending', 'pending', 'pending', NULL, 'vv love u', 1, 1, '2026-08-22 09:08:17', '2026-08-22 09:08:17', NULL, NULL, NULL, NULL),
('650e8847-0323-43c7-92fb-4ef16674f5af', 'BK-JMQ7Y8FM', 'bd238724-7aee-4107-8833-384acfcfb0be', NULL, NULL, NULL, 'c95791bd-c085-4e63-8075-5207d5fa7cbf', '037ac521-05b4-48ed-a59e-2104992766d5', '0047e35d-7489-4e4c-88b7-f0015fe66fe9', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-17', '2026-11-15', 3, 8500.00, 17000.00, 0.00, 0.00, NULL, 8500.00, 0.00, 'pending', 'pending', 'pending', NULL, NULL, 1, 1, '2026-08-16 22:48:40', '2026-08-16 22:48:40', NULL, NULL, NULL, NULL),
('690abc30-a8db-4e7c-b75b-fe0e3420a58b', 'BK-S7ZJJXO9', '035e8b11-9be9-402a-a986-f95e4aea9fe9', 'test user', '9876543210', 'adminer@staynest.com', '2ce06724-f6aa-4b6d-b12d-df72a3492eb9', NULL, NULL, 'Twin Sharing', 'a0ea13d9-49a7-4e08-b364-7f0f00b97495', '2026-08-25', '2027-07-25', 11, 8500.00, 8500.00, 0.00, 0.00, NULL, 17000.00, 0.00, 'pending', 'pending', 'pending', NULL, 'Quiet room preferred on 2nd floor.', 1, 1, '2026-08-22 08:18:20', '2026-08-22 08:18:20', NULL, NULL, NULL, NULL),
('6dfe0bf6-db66-4c60-9011-0586415cce36', 'BK-7TVYHTV', '67d8bdcd-4ad7-4d0b-b85f-25a9afa82499', NULL, NULL, NULL, '4ab9cbde-a774-4cb1-929e-7c116716030a', '1db581a8-a3af-4a29-ac67-f9681b04489f', 'af35f0f4-e374-4f1f-8987-a4409cb7b462', NULL, 'be8170ed-18c1-47fd-8313-da286315bc89', '2026-08-24', '2027-07-17', 11, 2421412.00, 15000.00, 500.00, 0.00, NULL, 2436412.00, 2436412.00, 'paid', 'completed', 'pending', NULL, NULL, 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('71239c0e-3c71-405d-80b5-fe8da276b95e', 'BK-IY2ZFTVG', '035e8b11-9be9-402a-a986-f95e4aea9fe9', 'test user', '9044032140', 'adminer@staynest.com', 'c95791bd-c085-4e63-8075-5207d5fa7cbf', NULL, NULL, 'Standard Stay', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-23', '2027-08-23', 12, 8500.00, 17000.00, 0.00, 0.00, NULL, 25500.00, 0.00, 'pending', 'pending', 'pending', NULL, 'OKOK', 1, 1, '2026-08-22 08:33:19', '2026-08-22 08:33:19', NULL, NULL, NULL, NULL),
('73016d11-1939-4035-b808-dac9b4f2d041', 'BK-YXHVRBP', 'e62e7b98-e262-4c3c-a366-2989f5b87bbe', NULL, NULL, NULL, 'c9b8b7b3-8dab-11f1-a4cf-1062e5a5cd6c', '3c046a51-5abf-4372-98f2-59e6aff355b1', '80172e36-4922-4828-9bd9-937e6d46be7d', NULL, '63576b41-af34-452b-b4a5-2b603bda3fe6', '2026-08-22', '2027-07-17', 11, 8500.00, 15000.00, 500.00, 0.00, NULL, 23500.00, 23500.00, 'paid', 'confirmed', 'approved', NULL, NULL, 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('7c916cf1-e0f3-452c-8035-383d0eb7c1cf', 'BK-KFMZPRNN', '035e8b11-9be9-402a-a986-f95e4aea9fe9', 'test user', '9876543210', 'adminer@staynest.com', '2ce06724-f6aa-4b6d-b12d-df72a3492eb9', NULL, NULL, 'Single Room', 'a0ea13d9-49a7-4e08-b364-7f0f00b97495', '2026-08-27', '2027-02-27', 6, 12000.00, 8500.00, 0.00, 0.00, NULL, 20500.00, 0.00, 'pending', 'cancelled', 'pending', 'Changed my move-in plan', NULL, 1, 1, '2026-08-22 08:20:26', '2026-08-22 08:20:26', NULL, NULL, NULL, NULL),
('7cfddb84-b435-4f13-b801-8a62ce95415e', 'BK-ZA5YDXP8', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'Rahul Admin', '9876543210', 'admin@staynest.com', 'c95791bd-c085-4e63-8075-5207d5fa7cbf', NULL, NULL, 'Standard Stay', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-23', '2027-02-23', 6, 8500.00, 17000.00, 0.00, 0.00, NULL, 25500.00, 0.00, 'pending', 'cancelled', 'pending', 'Cancelled by tenant', 'FWFVA', 1, 1, '2026-08-22 08:27:33', '2026-08-22 09:43:57', NULL, NULL, NULL, NULL),
('8963bd8b-c0c6-4b4d-a277-4f8f227e39f4', 'BK-U2JIUQ4D', 'c9098d68-9d79-421f-a1f5-83fc4ec8fbda', NULL, NULL, NULL, 'c95791bd-c085-4e63-8075-5207d5fa7cbf', '037ac521-05b4-48ed-a59e-2104992766d5', '0047e35d-7489-4e4c-88b7-f0015fe66fe9', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-21', '2026-11-19', 3, 8500.00, 17000.00, 0.00, 0.00, NULL, 8500.00, 0.00, 'pending', 'pending', 'pending', NULL, NULL, 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL, NULL, NULL, NULL),
('a78c8135-1e5e-4549-8981-eb2e5277f208', 'BK-IXDGOZ39', '2d28d9da-2144-487d-997e-68b5624706f1', NULL, NULL, NULL, 'd6779d7e-b9a0-45ac-ba92-3ced3ead699c', '037ac521-05b4-48ed-a59e-2104992766d5', '0047e35d-7489-4e4c-88b7-f0015fe66fe9', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-25', '2026-11-23', 3, 9500.00, 19000.00, 0.00, 0.00, NULL, 9500.00, 0.00, 'pending', 'pending', 'pending', NULL, NULL, 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL, NULL, NULL, NULL),
('accbd439-3d44-4815-800d-61388dc9ff6b', 'BK-BKKQZ101', '035e8b11-9be9-402a-a986-f95e4aea9fe9', 'test user', '9876543210', 'adminer@staynest.com', '2ce06724-f6aa-4b6d-b12d-df72a3492eb9', NULL, NULL, 'Twin Sharing', 'a0ea13d9-49a7-4e08-b364-7f0f00b97495', '2026-08-25', '2027-07-25', 11, 8500.00, 8500.00, 0.00, 0.00, NULL, 17000.00, 0.00, 'pending', 'confirmed', 'approved', NULL, 'Quiet room preferred on 2nd floor.', 1, 1, '2026-08-22 08:29:06', '2026-08-22 08:29:06', NULL, NULL, NULL, NULL),
('ae023d04-4848-4feb-8efa-9fd232ed12db', 'BK-R60YZD3', 'bd238724-7aee-4107-8833-384acfcfb0be', NULL, NULL, NULL, 'dc5b0828-7cdc-4cfc-8f13-3c75bd25b248', '500162b1-2c4b-44e2-a24c-0c87ddee973b', 'a710f325-7a3e-46cf-856a-d53ea19c0fa6', NULL, 'be8170ed-18c1-47fd-8313-da286315bc89', '2026-08-20', '2027-07-17', 11, 8500.00, 15000.00, 500.00, 0.00, NULL, 23500.00, 23500.00, 'paid', 'confirmed', 'approved', NULL, NULL, 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('aff89c7b-6b1e-4e42-b7ec-58f8fc2d188d', 'BK-LMMCN55J', 'b9585777-9009-44f4-a3f7-e316abbaf0d2', NULL, NULL, NULL, 'c95791bd-c085-4e63-8075-5207d5fa7cbf', '037ac521-05b4-48ed-a59e-2104992766d5', '0047e35d-7489-4e4c-88b7-f0015fe66fe9', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-19', '2026-11-17', 3, 8500.00, 17000.00, 0.00, 0.00, NULL, 8500.00, 8500.00, 'paid', 'confirmed', 'approved', NULL, NULL, 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL, NULL, NULL, NULL),
('b41a91c5-0711-47ce-ac7f-00e5cc11e2e2', 'BK-FODOVBKJ', '035e8b11-9be9-402a-a986-f95e4aea9fe9', 'test user', '9044032140', 'adminer@staynest.com', 'bd1e05f8-4124-4df5-81cd-79c794ba6432', NULL, NULL, 'Double Sharing', '5cb05e6c-56b8-4a6f-844b-c8437ecea822', '2026-08-23', '2027-08-23', 12, 8500.00, 35353.00, 6436.00, 0.00, NULL, 43853.00, 0.00, 'pending', 'confirmed', 'approved', NULL, 'ghjkll', 1, 1, '2026-08-22 08:42:09', '2026-08-22 08:49:01', NULL, NULL, NULL, NULL),
('b6ae5f4c-1c03-433d-b6ed-bfa115b27ec5', 'BK-HNEK3ARQ', 'b9585777-9009-44f4-a3f7-e316abbaf0d2', NULL, NULL, NULL, 'e4614af2-142d-4c05-bc61-0c1f275d37c3', '037ac521-05b4-48ed-a59e-2104992766d5', '0047e35d-7489-4e4c-88b7-f0015fe66fe9', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-19', '2026-11-17', 3, 11000.00, 22000.00, 0.00, 0.00, NULL, 11000.00, 11000.00, 'paid', 'confirmed', 'approved', NULL, NULL, 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL, NULL, NULL, NULL),
('be875e16-0720-4a91-b27c-0f256c92fce3', 'BK-RUUX3JX', 'c9098d68-9d79-421f-a1f5-83fc4ec8fbda', NULL, NULL, NULL, '93220e71-23ec-43df-8577-272c5c873711', '388afdad-06c3-4c13-8c2f-9ce3eb9814a3', '8a9c390f-3b34-4beb-959b-34fcc4558736', NULL, 'be8170ed-18c1-47fd-8313-da286315bc89', '2026-08-25', '2027-07-17', 11, 22412.00, 15000.00, 500.00, 0.00, NULL, 37412.00, 0.00, 'pending', 'pending', 'pending', NULL, NULL, 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('c7b04772-a61a-47bd-8bfd-a4607577f515', 'BK-PXI3HAFP', 'bd238724-7aee-4107-8833-384acfcfb0be', NULL, NULL, NULL, '94a9038d-3e4f-4153-aa79-eb83d2542f6a', '037ac521-05b4-48ed-a59e-2104992766d5', '0047e35d-7489-4e4c-88b7-f0015fe66fe9', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-17', '2026-11-15', 3, 14500.00, 29000.00, 0.00, 0.00, NULL, 14500.00, 0.00, 'pending', 'pending', 'pending', NULL, NULL, 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL, NULL, NULL, NULL),
('c970f3ba-ad95-4832-9a8b-9121ac296c4e', 'BK-A9WZK42A', 'bd238724-7aee-4107-8833-384acfcfb0be', NULL, NULL, NULL, 'e4614af2-142d-4c05-bc61-0c1f275d37c3', '037ac521-05b4-48ed-a59e-2104992766d5', '0047e35d-7489-4e4c-88b7-f0015fe66fe9', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-17', '2026-11-15', 3, 11000.00, 22000.00, 0.00, 0.00, NULL, 11000.00, 0.00, 'pending', 'pending', 'pending', NULL, NULL, 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL, NULL, NULL, NULL),
('ca574f89-3674-48c7-b4c0-1564171a0515', 'BK-Z03R0FP', 'c9098d68-9d79-421f-a1f5-83fc4ec8fbda', NULL, NULL, NULL, '2ce06724-f6aa-4b6d-b12d-df72a3492eb9', '16d9f70c-f149-47f6-ab36-2a1f90be8be7', 'eced2c5d-3a73-4756-9c36-bb70b11a6cb1', NULL, 'dc12b8a0-fcec-4a52-b96f-55f58e7b5fe9', '2026-08-22', '2027-07-17', 11, 8500.00, 15000.00, 500.00, 0.00, NULL, 23500.00, 0.00, 'pending', 'pending', 'pending', NULL, NULL, 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('d53a05cf-5ba0-4890-a668-334d310d3971', 'BK-8RRDUZ1Q', 'c9098d68-9d79-421f-a1f5-83fc4ec8fbda', NULL, NULL, NULL, '94a9038d-3e4f-4153-aa79-eb83d2542f6a', '037ac521-05b4-48ed-a59e-2104992766d5', '0047e35d-7489-4e4c-88b7-f0015fe66fe9', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-21', '2026-11-19', 3, 14500.00, 29000.00, 0.00, 0.00, NULL, 14500.00, 0.00, 'pending', 'pending', 'pending', NULL, NULL, 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL, NULL, NULL, NULL),
('dc3ccd88-b7c6-4477-b96b-b8b7f7312338', 'BK-PNQIWEZ6', 'c9098d68-9d79-421f-a1f5-83fc4ec8fbda', NULL, NULL, NULL, 'd6779d7e-b9a0-45ac-ba92-3ced3ead699c', '037ac521-05b4-48ed-a59e-2104992766d5', '0047e35d-7489-4e4c-88b7-f0015fe66fe9', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-21', '2026-11-19', 3, 9500.00, 19000.00, 0.00, 0.00, NULL, 9500.00, 0.00, 'pending', 'pending', 'pending', NULL, NULL, 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL, NULL, NULL, NULL),
('e0879b2e-5b5f-4060-a113-52152e412e62', 'BK-XUKYQENC', 'e62e7b98-e262-4c3c-a366-2989f5b87bbe', NULL, NULL, NULL, '94a9038d-3e4f-4153-aa79-eb83d2542f6a', '037ac521-05b4-48ed-a59e-2104992766d5', '0047e35d-7489-4e4c-88b7-f0015fe66fe9', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-23', '2026-11-21', 3, 14500.00, 29000.00, 0.00, 0.00, NULL, 14500.00, 14500.00, 'paid', 'confirmed', 'approved', NULL, NULL, 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL, NULL, NULL, NULL),
('e1b84973-0733-4024-a6e8-783b00030430', 'BK-9LIWI4PZ', 'c9098d68-9d79-421f-a1f5-83fc4ec8fbda', NULL, NULL, NULL, 'e4614af2-142d-4c05-bc61-0c1f275d37c3', '037ac521-05b4-48ed-a59e-2104992766d5', '0047e35d-7489-4e4c-88b7-f0015fe66fe9', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-21', '2026-11-19', 3, 11000.00, 22000.00, 0.00, 0.00, NULL, 11000.00, 0.00, 'pending', 'pending', 'pending', NULL, NULL, 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL, NULL, NULL, NULL),
('e31fc690-597b-4212-826b-b378e8319b06', 'BK-PBXCRZU', '923062d4-d28d-46a7-a8b3-0ec3424b1a6b', NULL, NULL, NULL, '6ca9f328-86ac-40d1-9d98-4a9c3823986f', '1eafd830-319b-4c82-ad3b-a3ed49d2daf8', '3667940c-a6c8-413d-84d7-76d07fe922d0', NULL, '63576b41-af34-452b-b4a5-2b603bda3fe6', '2026-08-29', '2027-07-17', 11, 8500.00, 15000.00, 500.00, 0.00, NULL, 23500.00, 5000.00, 'refunded', 'cancelled', 'pending', NULL, NULL, 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('e3c8a572-202e-4d9f-bfbb-507edd1283c9', 'BK-VHHCXSZS', '2d28d9da-2144-487d-997e-68b5624706f1', NULL, NULL, NULL, 'c95791bd-c085-4e63-8075-5207d5fa7cbf', '037ac521-05b4-48ed-a59e-2104992766d5', '0047e35d-7489-4e4c-88b7-f0015fe66fe9', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-25', '2026-11-23', 3, 8500.00, 17000.00, 0.00, 0.00, NULL, 8500.00, 0.00, 'pending', 'pending', 'pending', NULL, NULL, 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL, NULL, NULL, NULL),
('e7ef4c5c-c422-4a18-8c67-7a1d78404144', 'BK-QWVR9EYP', 'b9585777-9009-44f4-a3f7-e316abbaf0d2', NULL, NULL, NULL, 'd6779d7e-b9a0-45ac-ba92-3ced3ead699c', '037ac521-05b4-48ed-a59e-2104992766d5', '0047e35d-7489-4e4c-88b7-f0015fe66fe9', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-19', '2026-11-17', 3, 9500.00, 19000.00, 0.00, 0.00, NULL, 9500.00, 9500.00, 'paid', 'confirmed', 'approved', NULL, NULL, 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL, NULL, NULL, NULL),
('eb4642a8-8edf-4e95-80c4-958c40f6bf72', 'BK-FATRCBO', 'bd238724-7aee-4107-8833-384acfcfb0be', NULL, NULL, NULL, '807319ca-3418-4c12-b110-b66e00e7ab92', '1f9f6784-34b0-4070-bfde-34298ff8b04b', '6e974b80-33a6-4a17-b58c-793d3ed4d72a', NULL, 'dc12b8a0-fcec-4a52-b96f-55f58e7b5fe9', '2026-08-21', '2027-07-17', 11, 8500.00, 15000.00, 500.00, 0.00, NULL, 23500.00, 23500.00, 'paid', 'confirmed', 'approved', NULL, NULL, 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('ec7f5ccf-bbf5-4a23-9839-c5411dd89067', 'BK-YECAGZW8', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'Rahul Admin', '9876543210', 'admin@staynest.com', 'c95791bd-c085-4e63-8075-5207d5fa7cbf', NULL, NULL, 'Standard Stay', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-23', '2027-07-23', 11, 8500.00, 17000.00, 0.00, 0.00, NULL, 25500.00, 0.00, 'pending', 'pending', 'pending', NULL, 'vv love u', 1, 1, '2026-08-22 09:09:13', '2026-08-22 09:09:13', NULL, NULL, NULL, NULL),
('edd0b4e5-e6a9-46e7-abec-1a1c06819be5', 'BK-NDXLKEP', 'b9585777-9009-44f4-a3f7-e316abbaf0d2', NULL, NULL, NULL, 'ed514de7-d9e2-45fc-876c-c192a5d6fd01', '54e1e183-7b94-49a0-a89c-e67e2036c3d3', '89c817fd-61f7-42c7-b40d-4be48b998222', NULL, '63576b41-af34-452b-b4a5-2b603bda3fe6', '2026-08-29', '2027-07-17', 11, 232123.00, 15000.00, 500.00, 0.00, NULL, 247123.00, 247123.00, 'paid', 'confirmed', 'approved', NULL, NULL, 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('f69bf83e-6281-46ec-9c90-06bb152a3c78', 'BK-WKVXKGB', 'b9585777-9009-44f4-a3f7-e316abbaf0d2', NULL, NULL, NULL, '175827b9-c7af-4c56-90fe-f0eb3898a6cf', '1667bb02-e58f-4a26-bacd-73760ca9ec2d', '449f0bc4-3e57-4565-9129-1a267b73f4df', NULL, '63576b41-af34-452b-b4a5-2b603bda3fe6', '2026-08-29', '2027-07-17', 11, 8500.00, 15000.00, 500.00, 0.00, NULL, 23500.00, 23500.00, 'paid', 'confirmed', 'approved', NULL, NULL, 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('fcffa079-70b9-4129-9fd3-44b2451cf67a', 'BK-UXEXHCV', '923062d4-d28d-46a7-a8b3-0ec3424b1a6b', NULL, NULL, NULL, 'da080740-0c28-40e2-a691-332a70e0f27f', '4f5b5260-3c0c-481c-b613-170b26306abb', '69373451-b81e-4408-bf4c-34438780260a', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-18', '2027-07-17', 11, 5000.00, 15000.00, 500.00, 0.00, NULL, 20000.00, 5000.00, 'refunded', 'cancelled', 'pending', NULL, NULL, 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `booking_history`
--

CREATE TABLE `booking_history` (
  `id` char(36) NOT NULL,
  `booking_id` char(36) NOT NULL,
  `from_status` varchar(20) DEFAULT NULL,
  `to_status` varchar(20) NOT NULL,
  `changed_by` char(36) DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `broker_payouts`
--

CREATE TABLE `broker_payouts` (
  `id` char(36) NOT NULL,
  `payout_id` varchar(20) NOT NULL,
  `broker_id` char(36) NOT NULL,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `gross_amount` decimal(12,2) NOT NULL,
  `commission_amount` decimal(12,2) NOT NULL,
  `net_amount` decimal(12,2) NOT NULL,
  `status` enum('pending','processing','paid','failed') DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `transaction_ref` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('home_top_cities_v2', 'O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:6:{i:0;O:15:\"App\\Models\\City\":31:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"cities\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:6:\"string\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:17:{s:2:\"id\";s:36:\"064a7ec5-0f8e-47f1-984f-6349af165a60\";s:8:\"state_id\";s:36:\"26a49e1d-45b1-413f-9a07-9da80d02c11f\";s:4:\"name\";s:13:\"Greater Noida\";s:4:\"slug\";s:13:\"greater-noida\";s:8:\"latitude\";N;s:9:\"longitude\";N;s:7:\"geohash\";N;s:15:\"google_place_id\";N;s:8:\"district\";N;s:8:\"is_metro\";i:1;s:8:\"is_tier1\";i:0;s:9:\"is_active\";i:1;s:7:\"version\";i:1;s:10:\"created_at\";s:19:\"2026-08-17 03:59:51\";s:10:\"updated_at\";s:19:\"2026-08-17 03:59:51\";s:10:\"deleted_at\";N;s:16:\"properties_count\";i:5;}s:11:\"\0*\0original\";a:17:{s:2:\"id\";s:36:\"064a7ec5-0f8e-47f1-984f-6349af165a60\";s:8:\"state_id\";s:36:\"26a49e1d-45b1-413f-9a07-9da80d02c11f\";s:4:\"name\";s:13:\"Greater Noida\";s:4:\"slug\";s:13:\"greater-noida\";s:8:\"latitude\";N;s:9:\"longitude\";N;s:7:\"geohash\";N;s:15:\"google_place_id\";N;s:8:\"district\";N;s:8:\"is_metro\";i:1;s:8:\"is_tier1\";i:0;s:9:\"is_active\";i:1;s:7:\"version\";i:1;s:10:\"created_at\";s:19:\"2026-08-17 03:59:51\";s:10:\"updated_at\";s:19:\"2026-08-17 03:59:51\";s:10:\"deleted_at\";N;s:16:\"properties_count\";i:5;}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:8:\"is_metro\";s:7:\"boolean\";s:8:\"is_tier1\";s:7:\"boolean\";s:9:\"is_active\";s:7:\"boolean\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:11:{i:0;s:2:\"id\";i:1;s:8:\"state_id\";i:2;s:4:\"name\";i:3;s:4:\"slug\";i:4;s:8:\"latitude\";i:5;s:9:\"longitude\";i:6;s:8:\"district\";i:7;s:8:\"is_metro\";i:8;s:8:\"is_tier1\";i:9;s:9:\"is_active\";i:10;s:7:\"version\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}i:1;O:15:\"App\\Models\\City\":31:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"cities\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:6:\"string\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:17:{s:2:\"id\";s:36:\"01780c3b-0a91-4f49-9248-7472457ef24b\";s:8:\"state_id\";s:36:\"c9adb837-8dab-11f1-a4cf-1062e5a5cd6c\";s:4:\"name\";s:21:\"Bangalore (Bengaluru)\";s:4:\"slug\";s:19:\"bangalore-bengaluru\";s:8:\"latitude\";N;s:9:\"longitude\";N;s:7:\"geohash\";N;s:15:\"google_place_id\";N;s:8:\"district\";N;s:8:\"is_metro\";i:1;s:8:\"is_tier1\";i:1;s:9:\"is_active\";i:1;s:7:\"version\";i:1;s:10:\"created_at\";s:19:\"2026-08-17 03:59:51\";s:10:\"updated_at\";s:19:\"2026-08-17 03:59:51\";s:10:\"deleted_at\";N;s:16:\"properties_count\";i:2;}s:11:\"\0*\0original\";a:17:{s:2:\"id\";s:36:\"01780c3b-0a91-4f49-9248-7472457ef24b\";s:8:\"state_id\";s:36:\"c9adb837-8dab-11f1-a4cf-1062e5a5cd6c\";s:4:\"name\";s:21:\"Bangalore (Bengaluru)\";s:4:\"slug\";s:19:\"bangalore-bengaluru\";s:8:\"latitude\";N;s:9:\"longitude\";N;s:7:\"geohash\";N;s:15:\"google_place_id\";N;s:8:\"district\";N;s:8:\"is_metro\";i:1;s:8:\"is_tier1\";i:1;s:9:\"is_active\";i:1;s:7:\"version\";i:1;s:10:\"created_at\";s:19:\"2026-08-17 03:59:51\";s:10:\"updated_at\";s:19:\"2026-08-17 03:59:51\";s:10:\"deleted_at\";N;s:16:\"properties_count\";i:2;}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:8:\"is_metro\";s:7:\"boolean\";s:8:\"is_tier1\";s:7:\"boolean\";s:9:\"is_active\";s:7:\"boolean\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:11:{i:0;s:2:\"id\";i:1;s:8:\"state_id\";i:2;s:4:\"name\";i:3;s:4:\"slug\";i:4;s:8:\"latitude\";i:5;s:9:\"longitude\";i:6;s:8:\"district\";i:7;s:8:\"is_metro\";i:8;s:8:\"is_tier1\";i:9;s:9:\"is_active\";i:10;s:7:\"version\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}i:2;O:15:\"App\\Models\\City\":31:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"cities\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:6:\"string\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:17:{s:2:\"id\";s:36:\"0744f27a-ed42-4417-9622-2b1ae7be547d\";s:8:\"state_id\";s:36:\"c9adb837-8dab-11f1-a4cf-1062e5a5cd6c\";s:4:\"name\";s:9:\"New Delhi\";s:4:\"slug\";s:9:\"new-delhi\";s:8:\"latitude\";N;s:9:\"longitude\";N;s:7:\"geohash\";N;s:15:\"google_place_id\";N;s:8:\"district\";N;s:8:\"is_metro\";i:0;s:8:\"is_tier1\";i:0;s:9:\"is_active\";i:1;s:7:\"version\";i:1;s:10:\"created_at\";s:19:\"2026-08-16 15:24:59\";s:10:\"updated_at\";s:19:\"2026-08-16 15:24:59\";s:10:\"deleted_at\";N;s:16:\"properties_count\";i:2;}s:11:\"\0*\0original\";a:17:{s:2:\"id\";s:36:\"0744f27a-ed42-4417-9622-2b1ae7be547d\";s:8:\"state_id\";s:36:\"c9adb837-8dab-11f1-a4cf-1062e5a5cd6c\";s:4:\"name\";s:9:\"New Delhi\";s:4:\"slug\";s:9:\"new-delhi\";s:8:\"latitude\";N;s:9:\"longitude\";N;s:7:\"geohash\";N;s:15:\"google_place_id\";N;s:8:\"district\";N;s:8:\"is_metro\";i:0;s:8:\"is_tier1\";i:0;s:9:\"is_active\";i:1;s:7:\"version\";i:1;s:10:\"created_at\";s:19:\"2026-08-16 15:24:59\";s:10:\"updated_at\";s:19:\"2026-08-16 15:24:59\";s:10:\"deleted_at\";N;s:16:\"properties_count\";i:2;}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:8:\"is_metro\";s:7:\"boolean\";s:8:\"is_tier1\";s:7:\"boolean\";s:9:\"is_active\";s:7:\"boolean\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:11:{i:0;s:2:\"id\";i:1;s:8:\"state_id\";i:2;s:4:\"name\";i:3;s:4:\"slug\";i:4;s:8:\"latitude\";i:5;s:9:\"longitude\";i:6;s:8:\"district\";i:7;s:8:\"is_metro\";i:8;s:8:\"is_tier1\";i:9;s:9:\"is_active\";i:10;s:7:\"version\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}i:3;O:15:\"App\\Models\\City\":31:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"cities\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:6:\"string\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:17:{s:2:\"id\";s:36:\"88614695-bf11-4eae-b17e-93e87271e69c\";s:8:\"state_id\";s:36:\"c9adb837-8dab-11f1-a4cf-1062e5a5cd6c\";s:4:\"name\";s:5:\"Noida\";s:4:\"slug\";s:5:\"noida\";s:8:\"latitude\";N;s:9:\"longitude\";N;s:7:\"geohash\";N;s:15:\"google_place_id\";N;s:8:\"district\";N;s:8:\"is_metro\";i:0;s:8:\"is_tier1\";i:0;s:9:\"is_active\";i:1;s:7:\"version\";i:1;s:10:\"created_at\";s:19:\"2026-08-16 14:37:06\";s:10:\"updated_at\";s:19:\"2026-08-16 14:37:06\";s:10:\"deleted_at\";N;s:16:\"properties_count\";i:2;}s:11:\"\0*\0original\";a:17:{s:2:\"id\";s:36:\"88614695-bf11-4eae-b17e-93e87271e69c\";s:8:\"state_id\";s:36:\"c9adb837-8dab-11f1-a4cf-1062e5a5cd6c\";s:4:\"name\";s:5:\"Noida\";s:4:\"slug\";s:5:\"noida\";s:8:\"latitude\";N;s:9:\"longitude\";N;s:7:\"geohash\";N;s:15:\"google_place_id\";N;s:8:\"district\";N;s:8:\"is_metro\";i:0;s:8:\"is_tier1\";i:0;s:9:\"is_active\";i:1;s:7:\"version\";i:1;s:10:\"created_at\";s:19:\"2026-08-16 14:37:06\";s:10:\"updated_at\";s:19:\"2026-08-16 14:37:06\";s:10:\"deleted_at\";N;s:16:\"properties_count\";i:2;}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:8:\"is_metro\";s:7:\"boolean\";s:8:\"is_tier1\";s:7:\"boolean\";s:9:\"is_active\";s:7:\"boolean\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:11:{i:0;s:2:\"id\";i:1;s:8:\"state_id\";i:2;s:4:\"name\";i:3;s:4:\"slug\";i:4;s:8:\"latitude\";i:5;s:9:\"longitude\";i:6;s:8:\"district\";i:7;s:8:\"is_metro\";i:8;s:8:\"is_tier1\";i:9;s:9:\"is_active\";i:10;s:7:\"version\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}i:5;O:15:\"App\\Models\\City\":31:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"cities\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:6:\"string\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:17:{s:2:\"id\";s:36:\"0d0f038f-033f-4e4d-b1d9-ff99e772d6bc\";s:8:\"state_id\";s:36:\"5b0d73f1-9040-4e30-81d3-4db2aa7fce78\";s:4:\"name\";s:6:\"Mumbai\";s:4:\"slug\";s:6:\"mumbai\";s:8:\"latitude\";N;s:9:\"longitude\";N;s:7:\"geohash\";N;s:15:\"google_place_id\";N;s:8:\"district\";N;s:8:\"is_metro\";i:1;s:8:\"is_tier1\";i:1;s:9:\"is_active\";i:1;s:7:\"version\";i:1;s:10:\"created_at\";s:19:\"2026-08-17 03:59:51\";s:10:\"updated_at\";s:19:\"2026-08-17 03:59:51\";s:10:\"deleted_at\";N;s:16:\"properties_count\";i:0;}s:11:\"\0*\0original\";a:17:{s:2:\"id\";s:36:\"0d0f038f-033f-4e4d-b1d9-ff99e772d6bc\";s:8:\"state_id\";s:36:\"5b0d73f1-9040-4e30-81d3-4db2aa7fce78\";s:4:\"name\";s:6:\"Mumbai\";s:4:\"slug\";s:6:\"mumbai\";s:8:\"latitude\";N;s:9:\"longitude\";N;s:7:\"geohash\";N;s:15:\"google_place_id\";N;s:8:\"district\";N;s:8:\"is_metro\";i:1;s:8:\"is_tier1\";i:1;s:9:\"is_active\";i:1;s:7:\"version\";i:1;s:10:\"created_at\";s:19:\"2026-08-17 03:59:51\";s:10:\"updated_at\";s:19:\"2026-08-17 03:59:51\";s:10:\"deleted_at\";N;s:16:\"properties_count\";i:0;}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:8:\"is_metro\";s:7:\"boolean\";s:8:\"is_tier1\";s:7:\"boolean\";s:9:\"is_active\";s:7:\"boolean\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:11:{i:0;s:2:\"id\";i:1;s:8:\"state_id\";i:2;s:4:\"name\";i:3;s:4:\"slug\";i:4;s:8:\"latitude\";i:5;s:9:\"longitude\";i:6;s:8:\"district\";i:7;s:8:\"is_metro\";i:8;s:8:\"is_tier1\";i:9;s:9:\"is_active\";i:10;s:7:\"version\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}i:6;O:15:\"App\\Models\\City\":31:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"cities\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:6:\"string\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:17:{s:2:\"id\";s:36:\"2d207c63-ce32-4eb0-8fde-1802cf51fe26\";s:8:\"state_id\";s:36:\"5f8ce97d-9d5b-421b-ad4f-7c9e9e1ec53f\";s:4:\"name\";s:9:\"Ahmedabad\";s:4:\"slug\";s:9:\"ahmedabad\";s:8:\"latitude\";N;s:9:\"longitude\";N;s:7:\"geohash\";N;s:15:\"google_place_id\";N;s:8:\"district\";N;s:8:\"is_metro\";i:1;s:8:\"is_tier1\";i:1;s:9:\"is_active\";i:1;s:7:\"version\";i:1;s:10:\"created_at\";s:19:\"2026-08-17 03:59:51\";s:10:\"updated_at\";s:19:\"2026-08-17 03:59:51\";s:10:\"deleted_at\";N;s:16:\"properties_count\";i:0;}s:11:\"\0*\0original\";a:17:{s:2:\"id\";s:36:\"2d207c63-ce32-4eb0-8fde-1802cf51fe26\";s:8:\"state_id\";s:36:\"5f8ce97d-9d5b-421b-ad4f-7c9e9e1ec53f\";s:4:\"name\";s:9:\"Ahmedabad\";s:4:\"slug\";s:9:\"ahmedabad\";s:8:\"latitude\";N;s:9:\"longitude\";N;s:7:\"geohash\";N;s:15:\"google_place_id\";N;s:8:\"district\";N;s:8:\"is_metro\";i:1;s:8:\"is_tier1\";i:1;s:9:\"is_active\";i:1;s:7:\"version\";i:1;s:10:\"created_at\";s:19:\"2026-08-17 03:59:51\";s:10:\"updated_at\";s:19:\"2026-08-17 03:59:51\";s:10:\"deleted_at\";N;s:16:\"properties_count\";i:0;}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:8:\"is_metro\";s:7:\"boolean\";s:8:\"is_tier1\";s:7:\"boolean\";s:9:\"is_active\";s:7:\"boolean\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:11:{i:0;s:2:\"id\";i:1;s:8:\"state_id\";i:2;s:4:\"name\";i:3;s:4:\"slug\";i:4;s:8:\"latitude\";i:5;s:9:\"longitude\";i:6;s:8:\"district\";i:7;s:8:\"is_metro\";i:8;s:8:\"is_tier1\";i:9;s:9:\"is_active\";i:10;s:7:\"version\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1787497345);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` char(36) NOT NULL,
  `state_id` char(36) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `geohash` varchar(12) DEFAULT NULL,
  `google_place_id` varchar(100) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `is_metro` tinyint(1) DEFAULT 0,
  `is_tier1` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `state_id`, `name`, `slug`, `latitude`, `longitude`, `geohash`, `google_place_id`, `district`, `is_metro`, `is_tier1`, `is_active`, `version`, `created_at`, `updated_at`, `deleted_at`) VALUES
('00723629-e6bc-46f2-9378-60e9f91aa1db', '5f8ce97d-9d5b-421b-ad4f-7c9e9e1ec53f', 'Vadodara', 'vadodara', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('01780c3b-0a91-4f49-9248-7472457ef24b', 'c9adb837-8dab-11f1-a4cf-1062e5a5cd6c', 'Bangalore (Bengaluru)', 'bangalore-bengaluru', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('01dfeb34-5d74-4cc4-8e76-36b05501f957', '6a460256-4af0-49ae-b46b-1230a62091d8', 'Margao (South Goa)', 'margao-south-goa', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:52', '2026-08-16 22:29:52', NULL),
('04bd889c-5d9c-4866-b0d4-0818a9042969', 'e24c447e-b8aa-4bf6-9bb5-007f2adcf752', 'Madurai', 'madurai', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('064a7ec5-0f8e-47f1-984f-6349af165a60', '26a49e1d-45b1-413f-9a07-9da80d02c11f', 'Greater Noida', 'greater-noida', NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('06f48ae6-2c34-43b7-8d36-a6e584a9dc95', '5290d3e2-3a5a-4b6e-af53-23edf204e86b', 'Siliguri', 'siliguri', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('0744f27a-ed42-4417-9622-2b1ae7be547d', 'c9adb837-8dab-11f1-a4cf-1062e5a5cd6c', 'New Delhi', 'new-delhi', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 09:54:59', '2026-08-16 09:54:59', NULL),
('09005100-5c1b-45f8-9cb5-14488b80ed43', '6b4a5170-9aa2-48ec-ab6c-2528792c6bde', 'Jalandhar', 'jalandhar', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('0ace334b-2df0-405a-8195-d505cfa6480c', '5c36523c-8757-4a05-9468-a902f8c8f001', 'Dhanbad', 'dhanbad', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:52', '2026-08-16 22:29:52', NULL),
('0b958f2b-30d1-423d-bb1f-c672c4c2226a', '5b0d73f1-9040-4e30-81d3-4db2aa7fce78', 'Nagpur', 'nagpur', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('0d0f038f-033f-4e4d-b1d9-ff99e772d6bc', '5b0d73f1-9040-4e30-81d3-4db2aa7fce78', 'Mumbai', 'mumbai', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('11ae455e-e828-4fa3-b592-7214a64d98a4', 'f2e3460d-e200-42c8-a36f-603aa9d061f8', 'Jabalpur', 'jabalpur', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('1209a428-39c0-449f-873b-c88dafbbcc05', '5290d3e2-3a5a-4b6e-af53-23edf204e86b', 'Howrah', 'howrah', NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('13860925-81aa-4165-a9dc-0c3cc5936c9d', '2589d0a3-0fe5-45a4-b0cb-696acbf20d9d', 'Faridabad', 'faridabad', NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('15aeda7e-f270-4eac-946a-c112a12d216f', 'cd14a46c-d429-4de8-816e-6ca998c96c1b', 'Udaipur', 'udaipur', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('172704a3-2017-47a4-a030-0609cd22e6a5', '4bf5bab8-7fcc-4946-9f07-2f2b81120791', 'Bhubaneswar', 'bhubaneswar', NULL, NULL, NULL, NULL, NULL, 0, 1, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('1eac9f8c-a48c-4a1d-b73d-f4ea325c2ae3', 'f2e3460d-e200-42c8-a36f-603aa9d061f8', 'Indore', 'indore', NULL, NULL, NULL, NULL, NULL, 0, 1, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('2247f135-1541-4dd8-9e10-c3f419ba4fcf', '17e096cd-8ab6-4f2a-9fbc-7bfd54d5468f', 'Visakhapatnam', 'visakhapatnam', NULL, NULL, NULL, NULL, NULL, 0, 1, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('23f0feb4-343d-4cb7-94f7-4526a407ee7b', '26a49e1d-45b1-413f-9a07-9da80d02c11f', 'Kanpur', 'kanpur', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('2d207c63-ce32-4eb0-8fde-1802cf51fe26', '5f8ce97d-9d5b-421b-ad4f-7c9e9e1ec53f', 'Ahmedabad', 'ahmedabad', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('30b80b3d-9679-4788-93c3-2296216471ba', 'c9adb837-8dab-11f1-a4cf-1062e5a5cd6c', 'Mysore (Mysuru)', 'mysore-mysuru', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('32456f51-feec-4ae4-a118-01561075c8b5', '4ac23b71-f140-4bff-be23-5004d5278081', 'Gaya', 'gaya', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('3249348b-f5a4-4d79-b75f-666105bbf024', 'cd14a46c-d429-4de8-816e-6ca998c96c1b', 'Jodhpur', 'jodhpur', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('3522222f-ea92-476b-93e7-df1e2944cd01', '26a49e1d-45b1-413f-9a07-9da80d02c11f', 'Meerut', 'meerut', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('38aa061b-30e0-43e4-a59b-3431a1738271', '2d29f2fa-fcec-451f-ba64-efa21e0c232b', 'Rishikesh', 'rishikesh', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('39070ee1-5b13-4427-9417-07a3dee7ffd3', '2d29f2fa-fcec-451f-ba64-efa21e0c232b', 'Roorkee', 'roorkee', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('3b102e83-3359-49ec-8959-c3b6410b134c', '5f8ce97d-9d5b-421b-ad4f-7c9e9e1ec53f', 'Rajkot', 'rajkot', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('3ed04e3a-d56a-4408-836e-65e4893cdcd7', '26a49e1d-45b1-413f-9a07-9da80d02c11f', 'Varanasi', 'varanasi', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('474f78f3-e154-4f6e-b720-604f250fa0c4', '6b4a5170-9aa2-48ec-ab6c-2528792c6bde', 'Ludhiana', 'ludhiana', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('476613f1-49a8-4074-bd4b-5eb9099c78b1', '5f8ce97d-9d5b-421b-ad4f-7c9e9e1ec53f', 'Surat', 'surat', NULL, NULL, NULL, NULL, NULL, 0, 1, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('4b47ae03-fe03-43bd-9b0f-02221793d74b', '26a49e1d-45b1-413f-9a07-9da80d02c11f', 'Lucknow', 'lucknow', NULL, NULL, NULL, NULL, NULL, 0, 1, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('5614a8c0-d05e-4365-b9b3-1fcf27dccf04', '2a92cf3f-c8ff-42a8-b9f7-16b0b48b6d0f', 'Thiruvananthapuram', 'thiruvananthapuram', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('56a0fdd1-ef67-4a5b-b312-c79ec7247399', '7ab8f1a9-d78b-4ab2-8d5f-cacdb3eb9a55', 'Hyderabad', 'hyderabad', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('5854eec1-f572-4687-a6c9-a1d1fb7f0de6', '7ab8f1a9-d78b-4ab2-8d5f-cacdb3eb9a55', 'Secunderabad', 'secunderabad', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('5c2ecdd1-81d3-4965-9581-c292bc798f70', '8981acff-6a7f-4c26-84d1-aef0f7f43756', 'Bhilai', 'bhilai', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:52', '2026-08-16 22:29:52', NULL),
('5ecc06a3-364c-4b56-b085-be628ea7c184', 'c9adba43-8dab-11f1-a4cf-1062e5a5cd6c', 'Delhi', 'delhi', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('60394ea3-526a-4e60-905b-01b6b8c9232c', '26a49e1d-45b1-413f-9a07-9da80d02c11f', 'Ghaziabad', 'ghaziabad', NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('60cfc319-ddbb-4585-8f12-90d62cb8393f', '26a49e1d-45b1-413f-9a07-9da80d02c11f', 'Prayagraj', 'prayagraj', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('6417b269-058b-440e-b60e-31341453d018', '2d29f2fa-fcec-451f-ba64-efa21e0c232b', 'Dehradun', 'dehradun', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('6b9fc0ff-d83e-4263-83f7-3224baa942a2', '5b0d73f1-9040-4e30-81d3-4db2aa7fce78', 'Pune', 'pune', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('6bbeb49c-96b1-4693-9a37-4ed3e3fe49e3', '4ac23b71-f140-4bff-be23-5004d5278081', 'Muzaffarpur', 'muzaffarpur', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('6d090ee7-afa5-4e7f-81cb-28b980e0855a', 'e24c447e-b8aa-4bf6-9bb5-007f2adcf752', 'Coimbatore', 'coimbatore', NULL, NULL, NULL, NULL, NULL, 0, 1, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('6e699eaa-22bb-4a81-bce8-45dc6d588460', '2589d0a3-0fe5-45a4-b0cb-696acbf20d9d', 'Gurugram (Gurgaon)', 'gurugram-gurgaon', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('7210d0ae-2ea9-4f8b-a9b2-0793d817a3ce', 'c9adb837-8dab-11f1-a4cf-1062e5a5cd6c', 'Hubli-Dharwad', 'hubli-dharwad', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('7634c89c-b416-46df-ae27-4ec762b9c0f5', 'cd14a46c-d429-4de8-816e-6ca998c96c1b', 'Kota', 'kota', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('7b06b0fb-f5a6-4f7a-aa6f-2c1444031094', '6b4a5170-9aa2-48ec-ab6c-2528792c6bde', 'Chandigarh', 'chandigarh', NULL, NULL, NULL, NULL, NULL, 0, 1, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('82f7533c-67e6-4c2d-b0f8-8d03cc5a39fe', '17e096cd-8ab6-4f2a-9fbc-7bfd54d5468f', 'Guntur', 'guntur', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('8560b22f-2cd1-4de1-b14f-1b0ae1aefb4f', '4ac23b71-f140-4bff-be23-5004d5278081', 'Patna', 'patna', NULL, NULL, NULL, NULL, NULL, 0, 1, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('8786103d-ccd6-42a1-b166-84db0d2795f6', '5b0d73f1-9040-4e30-81d3-4db2aa7fce78', 'Thane', 'thane', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('87f63bad-a936-48fa-b6dd-74d0bf538f35', '6a460256-4af0-49ae-b46b-1230a62091d8', 'Panaji (North Goa)', 'panaji-north-goa', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('88614695-bf11-4eae-b17e-93e87271e69c', 'c9adb837-8dab-11f1-a4cf-1062e5a5cd6c', 'Noida', 'noida', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 09:07:06', '2026-08-16 09:07:06', NULL),
('8983a376-26a2-4f2c-9fae-150efb5eeda2', '5f8ce97d-9d5b-421b-ad4f-7c9e9e1ec53f', 'Gandhinagar', 'gandhinagar', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('8a33f276-0d19-4f7e-be4e-32983997d987', '5c36523c-8757-4a05-9468-a902f8c8f001', 'Jamshedpur', 'jamshedpur', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:52', '2026-08-16 22:29:52', NULL),
('8a5a3388-af57-4b2d-9fef-2229fb1f22ec', '6b4a5170-9aa2-48ec-ab6c-2528792c6bde', 'Mohali', 'mohali', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('8a74e8b8-f704-4e3c-b1de-6f9f33050344', '2d29f2fa-fcec-451f-ba64-efa21e0c232b', 'Haridwar', 'haridwar', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('8b299846-4fd8-4f84-98f9-d2ef3b6798f8', '17e096cd-8ab6-4f2a-9fbc-7bfd54d5468f', 'Tirupati', 'tirupati', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('a24792c2-6721-41f7-bbfa-c2eecf88956a', '5b0d73f1-9040-4e30-81d3-4db2aa7fce78', 'Aurangabad (Chhatrapati Sambhajinagar)', 'aurangabad-chhatrapati-sambhajinagar', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('a57da6df-760a-4133-83c9-b34368273cec', '17e096cd-8ab6-4f2a-9fbc-7bfd54d5468f', 'Vijayawada', 'vijayawada', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('a9aa9a7b-1ad9-4105-9468-418a9a82b90a', '5b0d73f1-9040-4e30-81d3-4db2aa7fce78', 'Nashik', 'nashik', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('add612c3-3ece-4c11-b891-2291a032b70b', '4bf5bab8-7fcc-4946-9f07-2f2b81120791', 'Rourkela', 'rourkela', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('b7940bff-cff2-4d45-8353-6e1e03776ac4', 'cd14a46c-d429-4de8-816e-6ca998c96c1b', 'Ajmer', 'ajmer', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('b7f51de2-e549-4e8a-9334-61e3922f247e', '5290d3e2-3a5a-4b6e-af53-23edf204e86b', 'Kolkata', 'kolkata', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('b82070c2-d7d5-4a1f-822d-29615d01bdc2', 'e24c447e-b8aa-4bf6-9bb5-007f2adcf752', 'Chennai', 'chennai', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('b94b834c-4613-4d00-bccf-776ebf3b4251', 'cd14a46c-d429-4de8-816e-6ca998c96c1b', 'Jaipur', 'jaipur', NULL, NULL, NULL, NULL, NULL, 0, 1, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('bd7d97ac-4ccc-42c0-8699-2695bf7b863e', 'e24c447e-b8aa-4bf6-9bb5-007f2adcf752', 'Tiruchirappalli', 'tiruchirappalli', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('be2ad044-b3e2-49db-b357-209bfd05c14a', '5b0d73f1-9040-4e30-81d3-4db2aa7fce78', 'Navi Mumbai', 'navi-mumbai', NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('c1056799-c1f5-43f4-aeb8-6adcac9ff857', 'f2e3460d-e200-42c8-a36f-603aa9d061f8', 'Bhopal', 'bhopal', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('c9aef033-8dab-11f1-a4cf-1062e5a5cd6c', 'c9adb837-8dab-11f1-a4cf-1062e5a5cd6c', 'Bangalore', 'bangalore', 12.9716000, 77.5946000, NULL, NULL, NULL, 1, 1, 1, 1, '2026-08-01 13:20:38', '2026-08-01 13:20:38', NULL),
('c9cbaf83-bc71-4afd-ba09-f6b6b396a799', '7ab8f1a9-d78b-4ab2-8d5f-cacdb3eb9a55', 'Warangal', 'warangal', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('ca4a5294-78d3-435f-bf67-c0ea6029baca', '4bf5bab8-7fcc-4946-9f07-2f2b81120791', 'Cuttack', 'cuttack', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('cc2454ea-fbf0-4508-92ca-3471af5a4228', '5c36523c-8757-4a05-9468-a902f8c8f001', 'Ranchi', 'ranchi', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:52', '2026-08-16 22:29:52', NULL),
('d50ddfbc-5ccf-426b-b12c-98515d6f138b', '5290d3e2-3a5a-4b6e-af53-23edf204e86b', 'Durgapur', 'durgapur', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('d8b712a6-92c7-46de-86e6-7c2fb851fa47', '26a49e1d-45b1-413f-9a07-9da80d02c11f', 'Agra', 'agra', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('dbfc5773-9da7-406b-88d5-ff01b1350934', '53d7471e-5ecb-4e2c-8fd1-434451fa7c72', 'Guwahati', 'guwahati', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('dc7d9f6f-f17f-47e7-99fa-1927e6271a2c', '6b4a5170-9aa2-48ec-ab6c-2528792c6bde', 'Amritsar', 'amritsar', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('de7d6bef-7185-4e08-8ae5-f17feaffbbcf', '2589d0a3-0fe5-45a4-b0cb-696acbf20d9d', 'Panchkula', 'panchkula', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('e062ee51-1da9-457b-9116-abc73cf08c73', 'f2e3460d-e200-42c8-a36f-603aa9d061f8', 'Gwalior', 'gwalior', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('e6d02d6f-9757-496a-82c2-7aa26624fd50', '2a92cf3f-c8ff-42a8-b9f7-16b0b48b6d0f', 'Kozhikode (Calicut)', 'kozhikode-calicut', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('e9c87d6c-7b41-40e3-8f71-22f67dcde1ac', 'c9adb837-8dab-11f1-a4cf-1062e5a5cd6c', 'Mangalore (Mangaluru)', 'mangalore-mangaluru', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('ecadf65d-05bd-4b77-9d11-3958b83293bb', '8981acff-6a7f-4c26-84d1-aef0f7f43756', 'Raipur', 'raipur', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:52', '2026-08-16 22:29:52', NULL),
('eecb6bf8-5572-41ea-b583-750ee09fc2a5', '2a92cf3f-c8ff-42a8-b9f7-16b0b48b6d0f', 'Kochi (Cochin)', 'kochi-cochin', NULL, NULL, NULL, NULL, NULL, 0, 1, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('f40c3550-b8dc-4af9-9a56-dfea91402bf8', 'e24c447e-b8aa-4bf6-9bb5-007f2adcf752', 'Salem', 'salem', NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` char(36) NOT NULL,
  `complaint_id` varchar(20) NOT NULL,
  `user_id` char(36) NOT NULL,
  `property_id` char(36) NOT NULL,
  `category` varchar(100) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `priority` enum('low','medium','high','urgent') DEFAULT 'medium',
  `status` enum('open','in_progress','resolved','closed') DEFAULT 'open',
  `assigned_to` char(36) DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complaint_images`
--

CREATE TABLE `complaint_images` (
  `id` char(36) NOT NULL,
  `complaint_id` char(36) NOT NULL,
  `image_url` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_inquiries`
--

CREATE TABLE `contact_inquiries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `user_type` varchar(50) NOT NULL DEFAULT 'tenant',
  `city` varchar(150) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('new','in_progress','resolved','archived') NOT NULL DEFAULT 'new',
  `admin_notes` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_inquiries`
--

INSERT INTO `contact_inquiries` (`id`, `name`, `email`, `phone`, `user_type`, `city`, `message`, `status`, `admin_notes`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 'Rahul Sharma', 'rahul.sharma@gmail.com', '+91 9876543210', 'tenant', 'Koramangala, Bangalore', 'Looking for a private single room PG with attached bathroom, power backup, and food included near Sony Signal.', 'in_progress', 'Assigned to Support Rep Rajesh for callback.', NULL, NULL, '2026-08-18 22:06:54', '2026-08-18 22:09:14'),
(2, 'Priya Patel', 'priya.patel@staynestpartners.com', '+91 9812345678', 'owner', 'Sector 62, Noida', 'We have a brand new 40-bed luxury PG building in Noida Sector 62. Looking to list and verify on StayNest.', 'in_progress', 'RM contacted and scheduled site inspection for Thursday.', NULL, NULL, '2026-08-18 22:06:54', '2026-08-18 22:06:54'),
(3, 'Vikram Aditya Singh', 'vikram.singh@techcorp.in', '+91 9988776655', 'partner', 'Hinjewadi Phase 1, Pune', 'Corporate partnership inquiry for booking 25 executive PG rooms for our relocating engineering team.', 'new', NULL, NULL, NULL, '2026-08-18 22:06:54', '2026-08-18 22:06:54'),
(4, 'Sneha Kulkarni', 'sneha.k@outlook.com', '+91 9765432109', 'support', 'Whitefield, Bangalore', 'Need an official rent payment receipt for HRA tax exemption for my StayNest PG booking.', 'resolved', 'Invoice sent to tenant via email on Aug 18.', NULL, NULL, '2026-08-18 22:06:54', '2026-08-18 22:06:54'),
(5, 'Ananya Verma', 'ananya.verma@college.edu', '+91 9845123670', 'tenant', 'North Campus, Delhi', 'Searching for girls PG near Delhi University North Campus with curfew flexibility and study room.', 'new', NULL, NULL, NULL, '2026-08-18 22:06:54', '2026-08-18 22:06:54'),
(6, 'rishikesh jadaun', 'imrishi@gmail.com', '+919044032145', 'tenant', 'delhi', 'ffefef', 'new', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-18 22:07:53', '2026-08-18 22:07:53'),
(7, 'test user', 'admin@gmail.com', '9044032145', 'tenant', 'Ludhiana', 'ffe  wefefweg', 'new', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-18 22:08:16', '2026-08-18 22:08:16'),
(8, 'Karan Malhotra', 'karan.m@gmail.com', '+91 9898989898', 'tenant', 'Mumbai, Andheri East', 'Inquiring about StayNest PG availability and monthly deposit terms.', 'new', NULL, '127.0.0.1', 'curl/8.21.0', '2026-08-18 22:08:37', '2026-08-18 22:08:37'),
(9, 'Aarav Sharma', 'aarav.sharma@example.com', '+91 9876543210', 'tenant', 'Koramangala, Bangalore', 'Looking for a single sharing PG room near Sony World Signal with high speed WiFi and power backup.', 'new', NULL, '127.0.0.1', 'Symfony', '2026-08-18 22:09:14', '2026-08-18 22:09:14'),
(10, 'Priya Patel', 'priya.patel@gmail.com', '+91 9812345678', 'owner', 'Sector 62, Noida', 'I have a 30-bed luxury PG property in Sector 62 Noida and want to partner with StayNest to list all rooms.', 'new', NULL, '127.0.0.1', 'Symfony', '2026-08-18 22:09:14', '2026-08-18 22:09:14'),
(11, 'Vikram Singh', 'vikram.singh@corporate-housing.in', '+91 9988776655', 'partner', 'Hinjewadi, Pune', 'We are an IT firm looking for 50 reserved beds for our new graduate trainee cohort starting next month.', 'new', NULL, '127.0.0.1', 'Symfony', '2026-08-18 22:09:14', '2026-08-18 22:09:14'),
(13, 'Broker Agency Partner Test', 'partner.test@agency.com', '+91 98765 43210', 'partner', 'Bangalore', 'We are a property management agency with 50+ PG beds in Koramangala looking to onboard with StayNest.', 'new', NULL, '127.0.0.1', 'Symfony', '2026-08-22 00:59:14', '2026-08-22 00:59:14');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` char(36) NOT NULL,
  `code` varchar(3) NOT NULL,
  `code2` varchar(2) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone_code` varchar(5) DEFAULT NULL,
  `currency_id` char(36) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `code`, `code2`, `name`, `phone_code`, `currency_id`, `is_active`, `version`, `created_at`, `updated_at`, `deleted_at`) VALUES
('c9ac8db1-8dab-11f1-a4cf-1062e5a5cd6c', 'IND', 'IN', 'India', '+91', 'c9a45d06-8dab-11f1-a4cf-1062e5a5cd6c', 1, 1, '2026-08-01 13:20:38', '2026-08-01 13:20:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` char(36) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(3) NOT NULL,
  `symbol` varchar(10) DEFAULT NULL,
  `decimal_places` tinyint(4) DEFAULT 2,
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `code`, `symbol`, `decimal_places`, `is_active`, `version`, `created_at`, `updated_at`, `deleted_at`) VALUES
('c9a45d06-8dab-11f1-a4cf-1062e5a5cd6c', 'Indian Rupee', 'INR', '₹', 2, 1, 1, '2026-08-01 13:20:38', '2026-08-01 13:20:38', NULL),
('c9a46153-8dab-11f1-a4cf-1062e5a5cd6c', 'US Dollar', 'USD', '$', 2, 1, 1, '2026-08-01 13:20:38', '2026-08-01 13:20:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `daily_property_stats`
--

CREATE TABLE `daily_property_stats` (
  `date` date NOT NULL,
  `property_id` char(36) NOT NULL,
  `views_count` int(11) DEFAULT 0,
  `visits_count` int(11) DEFAULT 0,
  `bookings_count` int(11) DEFAULT 0,
  `revenue` decimal(12,2) DEFAULT 0.00,
  `occupancy_rate` decimal(5,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_types`
--

CREATE TABLE `document_types` (
  `id` char(36) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `requires_expiry` tinyint(1) DEFAULT 0,
  `requires_front_back` tinyint(1) DEFAULT 1,
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_verifications`
--

CREATE TABLE `document_verifications` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `document_id` char(36) NOT NULL,
  `verifier_id` char(36) DEFAULT NULL,
  `status` enum('pending','in_review','verified','rejected') DEFAULT 'pending',
  `verified_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `verification_method` enum('manual','auto_ocr','third_party') DEFAULT 'manual',
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `floors`
--

CREATE TABLE `floors` (
  `id` char(36) NOT NULL,
  `block_id` char(36) NOT NULL,
  `floor_number` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `floors`
--

INSERT INTO `floors` (`id`, `block_id`, `floor_number`, `name`, `is_active`, `version`, `created_at`, `updated_at`, `deleted_at`) VALUES
('04b7356f-72da-47b1-9a35-60ace602f33d', '703b9d20-52b8-441e-8293-cd025e524d4f', 1, '1st Floor', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('08257402-1996-447b-b899-f75f4ca4f3b0', '6cb88170-9562-4b23-8b51-9f69c8ed1e8a', 1, '1st Floor', 1, 1, '2026-08-22 06:48:05', '2026-08-22 06:48:05', NULL),
('0ee4e2f2-cc24-42ed-a3f2-25f81ee46f16', '56faed01-a454-482f-8a3c-deccd82f521f', 1, '1st Floor', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('15648a89-ec85-4773-bff0-1e8ed5b5233e', '71959ff1-5e21-4bd3-a263-012d8fbd41da', 1, '1st Floor', 1, 1, '2026-08-22 06:48:05', '2026-08-22 06:48:05', NULL),
('20f611f4-03fb-4a74-9f42-0bda0afec8d8', '3269c2cc-d32e-4ef0-8682-25fb3faafb97', 1, '1st Floor', 1, 1, '2026-08-22 06:49:58', '2026-08-22 06:49:58', NULL),
('227f83c0-8f13-4a27-b72b-de5bce70b2cf', '68e8e975-0bdd-4723-b472-588e771fef8e', 1, '1st Floor', 1, 1, '2026-08-22 06:48:05', '2026-08-22 06:48:05', NULL),
('279e46c5-16bd-4beb-a67a-a73e4e701155', 'a92d5cc2-c879-477b-b8b8-d4af9ad847b5', 1, '1st Floor', 1, 1, '2026-08-22 08:52:06', '2026-08-22 08:52:06', NULL),
('29ef1fda-c870-4128-b82b-d80c707c2ae1', 'd14ac0b8-437a-471d-bd0a-74266b6df3d3', 1, '1st Floor', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('3120491f-a514-49c5-8e51-d09a112f7a3c', 'a8ab51c3-6058-449f-aba6-9754d85e367a', 1, '1st Floor', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('377cae88-ceef-43ff-a095-4ccd510220b5', '215dec02-0c0f-423d-a251-bbfce9510584', 1, 'First Floor', 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL),
('3c88ae19-deba-43ae-9d75-bf63037ce7f7', 'd1836c43-a1f6-4a6a-9833-9be930227f81', 1, '1st Floor', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('3d7f9e99-db4c-48de-b622-aa297e384863', '5cef5170-d75b-43a1-87ed-dfac51b5a807', 1, '1st Floor', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('3e1c5340-3c57-4344-ac72-d0da0687f11f', 'f97198c5-cbc1-4a80-8e20-8d7fa130f202', 1, '1st Floor', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('429ae38d-840a-49b8-ae7e-7a6ccf44c294', 'e5fdf9d9-7515-41c2-9f00-d71a7973cea6', 1, '1st Floor', 1, 1, '2026-08-22 06:45:03', '2026-08-22 06:45:03', NULL),
('4c479803-4eaf-4b1f-959d-6078506fda79', '3bcd7912-53b4-4d84-a005-2ad25dbaf060', 1, '1st Floor', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('4dd903c2-82d0-447e-926d-413098eff8ca', '4c3018e0-0a4e-45b1-a9fd-adec2a8aea23', 1, '1st Floor', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('50153f35-73c6-4d59-83e7-cd4cbb6e45e0', '73342864-19f5-4d81-a687-f693f4f94a90', 1, 'First Floor', 1, 1, '2026-08-16 22:48:08', '2026-08-16 22:48:08', NULL),
('65ed029f-52e0-4890-858d-67e63eac9a02', '49d6df3c-8c92-48d6-813b-6c556498e3ba', 1, 'First Floor', 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL),
('7865427a-11c3-4987-b558-06fc19761dbf', '26ad438a-8954-4e75-beb9-f29ad6649993', 1, 'First Floor', 1, 1, '2026-08-16 22:48:40', '2026-08-16 22:48:40', NULL),
('7ef12ce0-8149-4953-a3b9-3447f9d4fc73', '5f307748-7d02-4860-b462-88f9c1e76fb8', 1, '1st Floor', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('8a2a36a4-7ee7-483a-b6a4-d5951dbf7ab6', '9cc811c4-715a-470b-9d19-2241d6500d14', 1, 'First Floor', 1, 1, '2026-08-16 22:48:27', '2026-08-16 22:48:27', NULL),
('8c2c78fc-de0a-475a-91ce-042ed52ad5b7', '0d994f14-9714-4c47-a773-719ad3eacf8d', 1, 'First Floor', 1, 1, '2026-08-16 22:47:51', '2026-08-16 22:47:51', NULL),
('917d66a7-247b-48f9-912b-95a0c63a40eb', 'db7c9cfe-7813-4dc8-92c1-ee9027861a4e', 1, '1st Floor', 1, 1, '2026-08-22 06:49:58', '2026-08-22 06:49:58', NULL),
('99ff9406-aa33-4db3-99f8-57d1cf4403ee', '6e3a6262-80bc-4860-9a71-61864b0508d4', 1, '1st Floor', 1, 1, '2026-08-22 06:46:53', '2026-08-22 06:46:53', NULL),
('9c4c271c-9794-40a1-ad6c-264b5409ef21', 'bc6c9fb2-d120-482d-9d66-43cdce02a4c6', 1, '1st Floor', 1, 1, '2026-08-22 06:46:53', '2026-08-22 06:46:53', NULL),
('9e5021fc-b0ec-4feb-8b19-df3af9e8d2b3', '514aa38c-a007-40c5-816f-d2f702a268f3', 1, '1st Floor', 1, 1, '2026-08-22 06:46:53', '2026-08-22 06:46:53', NULL),
('a74505c1-753b-4316-94c5-070fcf289ab2', 'ae808af5-a38c-406e-8718-73b1ab108f60', 1, 'First Floor', 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL),
('ae55573a-6636-454e-984c-39fc850a8efd', 'a1aa30f9-ea62-454d-a32c-6d0433c757cc', 1, 'First Floor', 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL),
('c9ee5b6b-d9ff-47c6-8624-9726061de833', '1cdcd451-d68d-40ee-91a5-c772bfc1f6bb', 1, '1st Floor', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('cc20c792-b016-4878-b8e0-0e91145c7149', 'c961d697-ab6a-43e6-a5a0-b0ad8320686e', 1, '1st Floor', 1, 1, '2026-08-22 04:58:01', '2026-08-22 04:58:01', NULL),
('d400a779-f8dd-4487-9765-11688446b9d8', '1a383049-d9fc-4aec-a47d-6d4d0f7015da', 1, '1st Floor', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('d4369a7d-745f-4115-8e78-fc459e96b677', '0104e75f-d2ef-45f1-af32-88648c7b90fe', 1, '1st Floor', 1, 1, '2026-08-22 06:49:58', '2026-08-22 06:49:58', NULL),
('d7dfc936-cd94-41a5-848c-5306eb918847', '7d1f0949-53d6-4712-876b-9c0baa7dd9b2', 1, '1st Floor', 1, 1, '2026-08-22 06:43:45', '2026-08-22 06:43:45', NULL),
('e7a31e55-7d90-4226-81aa-be59b1d934c3', '7d4de19d-4e48-45e4-bd4a-40e7aaa18ae3', 1, '1st Floor', 1, 1, '2026-08-22 06:55:03', '2026-08-22 06:55:03', NULL),
('e94ed2b1-e1ee-43f8-96c6-5880bed023a1', '8c43980c-27ea-4f46-994c-29c8747cb20b', 1, '1st Floor', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL),
('ec3dee3d-beba-47c5-81b6-0316536a8feb', 'f6dc0441-e467-44c3-be96-92d874562345', 1, '1st Floor', 1, 1, '2026-08-22 05:00:28', '2026-08-22 05:00:28', NULL),
('fe5e8f7c-2a44-43dc-a322-80d9c51fdc9d', '4cf87eb9-5c94-4adf-bfcc-0247bfeb54b5', 1, '1st Floor', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `genders`
--

CREATE TABLE `genders` (
  `id` char(36) NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(60) NOT NULL,
  `code` varchar(10) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `genders`
--

INSERT INTO `genders` (`id`, `name`, `slug`, `code`, `is_active`, `version`, `created_at`, `updated_at`, `deleted_at`) VALUES
('c9a24879-8dab-11f1-a4cf-1062e5a5cd6c', 'Male', 'male', 'M', 1, 1, '2026-08-01 13:20:38', '2026-08-01 13:20:38', NULL),
('c9a24abd-8dab-11f1-a4cf-1062e5a5cd6c', 'Female', 'female', 'F', 1, 1, '2026-08-01 13:20:38', '2026-08-01 13:20:38', NULL),
('c9a24bbd-8dab-11f1-a4cf-1062e5a5cd6c', 'Other', 'other', 'O', 1, 1, '2026-08-01 13:20:38', '2026-08-01 13:20:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` char(36) NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `booking_id` char(36) NOT NULL,
  `user_id` char(36) NOT NULL,
  `type` enum('rent','deposit','maintenance','penalty','other') NOT NULL,
  `billing_period_start` date DEFAULT NULL,
  `billing_period_end` date DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `tax_amount` decimal(12,2) DEFAULT 0.00,
  `discount_amount` decimal(12,2) DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL,
  `due_date` date NOT NULL,
  `status` enum('draft','unpaid','partially_paid','paid','overdue','cancelled') DEFAULT 'draft',
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` char(36) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(10) NOT NULL,
  `native_name` varchar(100) DEFAULT NULL,
  `is_rtl` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leases`
--

CREATE TABLE `leases` (
  `id` char(36) NOT NULL,
  `booking_id` char(36) NOT NULL,
  `lease_number` varchar(50) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `monthly_rent` decimal(10,2) NOT NULL,
  `deposit_amount` decimal(10,2) NOT NULL,
  `terms_and_conditions` text DEFAULT NULL,
  `digital_signature_url` varchar(500) DEFAULT NULL,
  `status` enum('draft','active','expired','terminated') DEFAULT 'draft',
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_history`
--

CREATE TABLE `login_history` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `user_id` char(36) NOT NULL,
  `login_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `logout_at` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `device_id` char(36) DEFAULT NULL,
  `login_method` enum('password','otp','social_google','social_facebook','social_apple','magic_link','sso') DEFAULT 'password',
  `status` enum('success','failed','blocked','expired') DEFAULT 'success',
  `failure_reason` varchar(255) DEFAULT NULL,
  `location_city` varchar(100) DEFAULT NULL,
  `location_country` varchar(100) DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `login_history`
--

INSERT INTO `login_history` (`id`, `user_id`, `login_at`, `logout_at`, `ip_address`, `user_agent`, `device_id`, `login_method`, `status`, `failure_reason`, `location_city`, `location_country`, `metadata`) VALUES
('092ed59b-0dd0-4b01-a733-b0677f4700e3', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-17 20:50:40', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('0b1c9028-f88b-4c58-bfc9-657709b1254e', '77a10f6a-c120-405c-bca9-f95341a44a15', '2026-08-19 20:35:35', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('0eb93c7c-638f-416c-a96b-20ad2a0261a6', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 22:39:26', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('0ec44ed0-bb6b-4e73-8ce6-337640415fd6', 'b008ff33-1473-40b0-ba25-6e3c718f0680', '2026-08-16 07:59:31', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('11d9ad54-8645-4e13-aed8-216a2f06beb5', '63576b41-af34-452b-b4a5-2b603bda3fe6', '2026-08-18 12:44:41', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('11e5257e-5084-468b-90e6-ac08716e29f5', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 22:04:55', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('129c3c9f-3454-443d-be5f-6185902f4dd3', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-17 20:57:37', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('15f5a949-e59c-472b-9bf6-047e79d28796', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 22:18:48', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'failed', 'Invalid admin credentials', NULL, NULL, NULL),
('17aba1be-a880-4c51-ac5f-fadb493d121c', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-17 21:04:56', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', NULL, 'password', 'failed', 'Invalid admin credentials', NULL, NULL, NULL),
('1bde0d52-0b4c-4e72-938a-2a3e0f94bf81', '77a10f6a-c120-405c-bca9-f95341a44a15', '2026-08-19 20:35:26', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'failed', 'Invalid password', NULL, NULL, NULL),
('1cf300b7-80d9-4a6c-bbb4-21bb10f537b8', '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', '2026-08-16 07:55:26', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('1e67f76a-a209-4863-b067-a6e9384ec84d', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-17 21:16:24', NULL, '127.0.0.1', 'Symfony', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('238d21df-78df-4cf8-b7b8-5e63cd68869d', '63576b41-af34-452b-b4a5-2b603bda3fe6', '2026-08-17 21:20:03', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', NULL, 'password', 'failed', 'Inactive or non-active broker account (pending_verification)', NULL, NULL, NULL),
('23b2b5f1-66c2-4c11-b658-ca445b67ebcd', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-17 21:24:17', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'failed', 'Invalid broker password', NULL, NULL, NULL),
('23ec5d43-0f3a-4ae3-90ff-7ca77c259a63', 'bd238724-7aee-4107-8833-384acfcfb0be', '2026-08-17 21:16:06', NULL, '127.0.0.1', 'Symfony', NULL, 'password', 'failed', 'Invalid password', NULL, NULL, NULL),
('24973eab-ed12-45cc-af8e-36f7a1763e70', '2d28d9da-2144-487d-997e-68b5624706f1', '2026-08-19 22:00:29', NULL, '127.0.0.1', 'Symfony', NULL, 'otp', 'success', NULL, NULL, NULL, NULL),
('2a7920d8-fdd8-49f6-aea1-fe736ac6f316', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 02:29:47', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'failed', 'Invalid admin credentials', NULL, NULL, NULL),
('30c78cd3-8b1d-4764-8f36-a9904947a44f', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-17 21:20:53', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('324ef04d-5c71-4e1d-a99b-1efa09c9c5ef', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-19 21:51:52', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('327a86ca-ec7d-45cd-a3a1-4d6b557e3d4b', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-17 21:16:25', NULL, '127.0.0.1', 'Symfony', NULL, 'password', 'failed', 'Inactive or non-active broker account (pending_verification)', NULL, NULL, NULL),
('33755d4e-1b0d-48ce-b5b7-bb048bbbf9eb', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-19 21:52:28', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'failed', 'Invalid broker password', NULL, NULL, NULL),
('36fee3cb-d041-46e5-b5e7-9c87a1c3584a', '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', '2026-08-16 08:03:33', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('3847fd2f-6a8c-404a-a37f-f7f19eef5b7f', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 08:51:06', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'failed', 'Invalid broker password', NULL, NULL, NULL),
('38722c7a-074b-4f73-aa8a-51fb67b2b3eb', '77a10f6a-c120-405c-bca9-f95341a44a15', '2026-08-19 21:04:47', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('3bfc4428-8a00-4bfc-b731-79dd89203ae6', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 22:45:51', NULL, NULL, NULL, NULL, 'password', 'failed', 'Invalid broker password', NULL, NULL, NULL),
('40b82c1f-a162-4967-b185-1a8c2835e025', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-17 21:20:12', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', NULL, 'password', 'failed', 'Invalid broker password', NULL, NULL, NULL),
('438f8b37-617a-4e02-a09c-3ef92006547f', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-17 21:16:04', NULL, '127.0.0.1', 'Symfony', NULL, 'password', 'failed', 'Invalid broker password', NULL, NULL, NULL),
('498ad0ae-04d5-4b68-8887-d604d973f7e8', '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', '2026-08-19 20:35:01', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'failed', 'Invalid password', NULL, NULL, NULL),
('4caca871-c7f9-4cf9-957c-29a19c7eed7f', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 22:04:47', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'failed', 'Invalid admin credentials', NULL, NULL, NULL),
('56662913-7cac-44be-b7a4-da851929459a', '035e8b11-9be9-402a-a986-f95e4aea9fe9', '2026-08-22 08:32:41', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('57697bef-c9b6-4668-927e-16dfdd675507', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-17 21:16:04', NULL, '127.0.0.1', 'Symfony', NULL, 'password', 'failed', 'Invalid broker password', NULL, NULL, NULL),
('58b51d93-948c-4bf2-a195-8aaf2222dcbd', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-17 21:16:05', NULL, '127.0.0.1', 'Symfony', NULL, 'password', 'failed', 'Invalid broker password', NULL, NULL, NULL),
('599d8649-299c-4fd9-8138-8f56e085a5fb', '77a10f6a-c120-405c-bca9-f95341a44a15', '2026-08-18 12:47:44', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('5ab73eaa-a0dd-4462-a53e-ab24f55c9981', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 02:29:55', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'failed', 'Invalid admin credentials', NULL, NULL, NULL),
('5d1eeb8f-12c7-4aa8-9ebe-ec6ffec7d35f', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 22:45:37', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('5f0af148-ce75-4ce4-9397-9fcdffcf81cd', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 22:39:15', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'failed', 'Invalid admin credentials', NULL, NULL, NULL),
('626c2cd5-46fb-49b3-b692-c540f5924124', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-19 21:52:10', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('69cb5c2e-f0bd-4e34-ba1d-e30ef0cca191', '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', '2026-08-16 07:58:48', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('713ff510-1a45-48fe-b546-0d727280b79a', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-17 21:16:06', NULL, '127.0.0.1', 'Symfony', NULL, 'password', 'failed', 'Invalid broker password', NULL, NULL, NULL),
('760a6af8-f940-48f3-b18e-c2f642e31d40', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-17 21:19:56', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', NULL, 'password', 'failed', 'Invalid broker password', NULL, NULL, NULL),
('770d51fa-76ee-4dcc-9253-26120de949e2', '2d28d9da-2144-487d-997e-68b5624706f1', '2026-08-19 22:00:29', NULL, '127.0.0.1', 'Symfony', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('814d18cd-803f-4d68-93b4-7610218b9067', '63576b41-af34-452b-b4a5-2b603bda3fe6', '2026-08-22 01:07:08', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('8194e26f-e221-478a-a595-c65cdbf11a7a', '63576b41-af34-452b-b4a5-2b603bda3fe6', '2026-08-22 00:54:02', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('81a84737-f39e-46d0-84b8-2caa1629c16d', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 08:51:02', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'failed', 'Invalid broker password', NULL, NULL, NULL),
('831362e8-db4d-484f-a114-c86fdd98096c', '2d28d9da-2144-487d-997e-68b5624706f1', '2026-08-22 00:53:46', NULL, '127.0.0.1', 'Symfony', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('84deb079-c67a-43c8-a221-20cfc33e635d', '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', '2026-08-16 07:58:09', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'failed', 'Invalid password', NULL, NULL, NULL),
('851be759-64e0-468f-ad3d-cbad09d07c00', '63576b41-af34-452b-b4a5-2b603bda3fe6', '2026-08-22 08:51:09', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('87a65dde-8277-461d-9235-0ce62d0dc3bf', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 22:45:47', NULL, NULL, NULL, NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('8ab1697b-c5e7-4b59-8226-6c8fec0a119d', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-17 21:05:07', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('8caf8892-a304-43e1-80b1-857c2cbf412f', '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', '2026-08-16 07:56:29', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('8e9a54a5-c9f3-4e6d-8232-04cf543cd809', '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', '2026-08-16 08:03:44', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('9220b41d-f3a9-4b58-ae4f-1a39d4c859a5', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 02:30:03', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('97250741-bea4-40d8-84d8-bdc380f0c935', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 00:53:54', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'failed', 'Invalid broker password', NULL, NULL, NULL),
('97cdec88-59fb-45b5-8528-e1b0b988fbc9', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 02:29:30', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'failed', 'Invalid admin credentials', NULL, NULL, NULL),
('a3838ead-e32a-472d-9d75-e71d49e1a4a1', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-18 10:41:33', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('a5b4d33d-2cc6-4f24-accb-7de1248fda9e', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-18 11:10:36', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('a60941d7-bc6a-41d4-84db-2e9e36a1af85', '63576b41-af34-452b-b4a5-2b603bda3fe6', '2026-08-22 00:52:38', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('a66318bf-4540-4780-9101-b907155d0b7f', '2d28d9da-2144-487d-997e-68b5624706f1', '2026-08-22 00:49:27', NULL, '127.0.0.1', 'Symfony', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('a6e74f00-5269-4ec9-82a6-ec11dcb9d828', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 22:06:22', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'failed', 'Invalid admin credentials', NULL, NULL, NULL),
('a8668415-1ccc-486a-a6b8-1cbf6bd003c4', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-17 21:16:26', NULL, '127.0.0.1', 'Symfony', NULL, 'password', 'failed', 'Inactive or non-active broker account (active)', NULL, NULL, NULL),
('a9f3e088-ff68-405f-9203-7a7bff5c6e1d', 'bd238724-7aee-4107-8833-384acfcfb0be', '2026-08-17 21:16:27', NULL, '127.0.0.1', 'Symfony', NULL, 'password', 'failed', 'Inactive or non-active account (suspended)', NULL, NULL, NULL),
('af4b3565-8656-4eb9-9bea-e9bb6635c5e1', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 01:06:44', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'failed', 'Invalid broker password', NULL, NULL, NULL),
('be3b9fa0-3b7c-4fa3-8f62-64e69c2b5921', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 22:04:35', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('bfbc7d68-2ea1-455c-bfef-8b60ecb82846', '63576b41-af34-452b-b4a5-2b603bda3fe6', '2026-08-17 21:24:23', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('c80b911c-30ff-4c3d-8f0d-6812d1bd9dcf', '63576b41-af34-452b-b4a5-2b603bda3fe6', '2026-08-17 21:20:28', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', NULL, 'password', 'failed', 'Inactive or non-active broker account (pending_verification)', NULL, NULL, NULL),
('ce216fef-7724-437a-8745-ebabc88bab47', '63576b41-af34-452b-b4a5-2b603bda3fe6', '2026-08-22 00:53:39', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('ce463031-be5d-4b5b-9b49-a871d874ae55', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 00:52:31', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'failed', 'Invalid broker password', NULL, NULL, NULL),
('cee3a875-ba94-486e-82f5-cfc561d04f81', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 22:06:31', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('cfc25e28-1958-4f88-a4bb-706fa334f4f1', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-17 21:16:25', NULL, '127.0.0.1', 'Symfony', NULL, 'password', 'failed', 'Inactive or non-active broker account (suspended)', NULL, NULL, NULL),
('da985b10-506d-43ea-bb18-3fab77730704', 'bd238724-7aee-4107-8833-384acfcfb0be', '2026-08-17 21:16:07', NULL, '127.0.0.1', 'Symfony', NULL, 'password', 'failed', 'Invalid password', NULL, NULL, NULL),
('e8c7498b-9542-4119-a6da-da451760d4ea', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 22:45:24', NULL, NULL, NULL, NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('ea23a589-91f7-4a8b-901b-dbd6a19da76a', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 22:13:58', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('ecfcc233-accc-497a-9a02-e59cd40f2469', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 04:02:36', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('edb0701d-3400-479e-9914-b2287b5be8b6', 'bd238724-7aee-4107-8833-384acfcfb0be', '2026-08-17 21:16:26', NULL, '127.0.0.1', 'Symfony', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('edc759d4-f9f6-4126-bbec-0724aac0ed9d', '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', '2026-08-16 07:56:13', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('f238d09d-15e0-4a99-b296-dc350878c4f4', '035e8b11-9be9-402a-a986-f95e4aea9fe9', '2026-08-22 02:55:53', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL),
('f4897570-1801-4508-89af-f5332e5a6997', '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', '2026-08-16 08:26:37', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', NULL, 'password', 'success', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_08_05_034205_create_personal_access_tokens_table', 1),
(5, '2026_08_17_041009_create_platform_settings_table', 2),
(6, '2026_08_18_080000_create_relationship_managers_table', 3),
(7, '2026_08_18_100000_add_tag_to_properties_table', 4),
(8, '2026_08_18_172435_create_property_reports_table', 5),
(9, '2026_08_19_090500_create_contact_inquiries_table', 6),
(10, '2026_08_22_180500_add_is_recommended_to_properties_table', 7),
(11, '2026_08_22_191500_make_room_and_bed_nullable_in_bookings_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `user_id` char(36) NOT NULL,
  `user_type` enum('user','broker','admin') NOT NULL,
  `title` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(50) DEFAULT 'system',
  `is_read` tinyint(1) DEFAULT 0,
  `action_url` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `user_type`, `title`, `message`, `type`, `is_read`, `action_url`, `created_at`) VALUES
('00559003-4535-421f-9722-87e4ed385baf', 'a0ea13d9-49a7-4e08-b364-7f0f00b97495', 'broker', 'New Booking Request Received! 📋', 'test user has requested a stay at Royal Comfort PG starting Aug 25, 2026.', 'booking_created', 0, '/broker/bookings', '2026-08-22 08:29:06'),
('010b7fce-3229-43d1-962c-786129762fb9', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'Property Reported ⚠️', 'Listing \"Urban Nest Co-Living Spaces\" was reported for: Fake or Misleading Photos.', 'property_report', 0, NULL, '2026-08-18 11:58:03'),
('02aa3db4-36af-4736-874c-8cd25c600823', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"adult\" in New Delhi (PG / Hostel) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-16 10:13:20'),
('03df8b7d-5877-4c90-b05b-553ce75e62dd', 'a0ea13d9-49a7-4e08-b364-7f0f00b97495', 'broker', 'New Booking Request Received! 📋', 'test user has requested a stay at Royal Comfort PG starting Aug 25, 2026.', 'booking_created', 0, '/broker/bookings', '2026-08-22 08:19:59'),
('04e4466b-fc48-41c9-b5cd-934b1866a1c7', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"Elite GPS Residency PG\" in Noida (PG / Hostel) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-16 09:07:32'),
('0a4f77ce-30b3-4ca4-aad0-a09c5001a83c', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', 'broker', 'New Booking Request Received! 📋', 'Rahul Admin has requested a stay at Sunrise Premium Boys PG starting Aug 23, 2026.', 'booking_created', 0, '/broker/bookings', '2026-08-22 09:11:35'),
('0d96de60-bf86-456e-ba51-7b68cca9bc11', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"Starlight Co-living PG\" in Vadodara (PG / Hostel) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-22 05:06:54'),
('0fe46e85-77fc-47a8-9cf1-3beced1706e1', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"Shree Krishna Girls Executive Stay Test\" in New Delhi (PG / Hostel) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-22 06:49:58'),
('10ae5257-b241-4aa9-96b1-df0425bacda4', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Contact Inquiry Received', 'rishikesh jadaun (imrishi@gmail.com) submitted an inquiry regarding: ffefef', 'contact_inquiry', 0, 'http://127.0.0.1:8000/admin/contacts', '2026-08-18 22:07:53'),
('1250ea7f-2174-40f9-8a8e-27351cb6e6f5', '63576b41-af34-452b-b4a5-2b603bda3fe6', 'broker', 'Dedicated Relationship Manager Assigned 🤝', 'Karan Kapoor (VIP Pan-India Co-Living) has been assigned as your dedicated StayNest Relationship Manager.', 'rm_assigned', 0, '/broker/profile', '2026-08-17 21:11:18'),
('138c0119-6f08-4254-a259-4c99620a9a94', '63576b41-af34-452b-b4a5-2b603bda3fe6', 'user', 'Booking Request Sent! 🚀', 'Your request for Royal Comfort PG (#BK-T2DVLW1C) is sent to the owner for approval. Zero payment required now.', 'booking_pending', 0, '/bookings', '2026-08-23 07:05:52'),
('1a955c17-885f-44ea-80da-56acd0cd7d31', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Contact Inquiry Received', 'Aarav Sharma (aarav.sharma@example.com) submitted an inquiry regarding: Looking for a single sharing PG room near Sony World Signal with high speed WiFi...', 'contact_inquiry', 0, 'http://localhost/admin/contacts', '2026-08-18 22:09:14'),
('1cc2ef6c-9736-4314-a8f9-8eab462be54f', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', 'broker', 'Dedicated Relationship Manager Assigned 🤝', 'Rohan Mehta (South Zone (Bangalore & Hyderabad)) has been assigned as your dedicated StayNest Relationship Manager.', 'rm_assigned', 0, '/broker/profile', '2026-08-17 21:10:32'),
('1db8d988-d313-4399-bba1-59b2b4603c33', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'user', 'Booking Request Sent! 🚀', 'Your request for new listying (#BK-J3HVT5Q7) is sent to the owner for approval. Zero payment required now.', 'booking_pending', 0, '/bookings', '2026-08-22 08:52:43'),
('20a9faac-fb16-4c35-98ac-0049bee00c4c', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'Property Reported ⚠️', 'Listing \"Updated Sunrise Premium Residency (Updated)\" was reported for: Fake or Misleading Photos.', 'property_report', 0, NULL, '2026-08-18 12:14:59'),
('255bdd1e-8065-4b96-83ed-3f1bcb96087a', '035e8b11-9be9-402a-a986-f95e4aea9fe9', 'user', 'Booking Request Sent! 🚀', 'Your request for Royal Comfort PG (#BK-BKKQZ101) is sent to the owner for approval. Zero payment required now.', 'booking_pending', 0, '/bookings', '2026-08-22 08:29:06'),
('2c60977c-7666-44e9-a35d-3bf4a9c62c78', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Contact Inquiry Received', 'Vikram Singh (vikram.singh@corporate-housing.in) submitted an inquiry regarding: We are an IT firm looking for 50 reserved beds for our new graduate trainee coho...', 'contact_inquiry', 0, 'http://localhost/admin/contacts', '2026-08-18 22:06:26'),
('2cfca87a-2d23-4d0c-9857-5115656f81b3', '035e8b11-9be9-402a-a986-f95e4aea9fe9', 'user', 'Booking Request Sent! 🚀', 'Your request for Sunrise Premium Boys PG (#BK-IY2ZFTVG) is sent to the owner for approval. Zero payment required now.', 'booking_pending', 0, '/bookings', '2026-08-22 08:33:19'),
('2e3d4338-bdec-465c-975b-57496033e23d', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Contact Inquiry Received', 'Vikram Singh (vikram.singh@corporate-housing.in) submitted an inquiry regarding: We are an IT firm looking for 50 reserved beds for our new graduate trainee coho...', 'contact_inquiry', 0, 'http://localhost/admin/contacts', '2026-08-18 22:09:14'),
('2e8d3bc6-020b-42d4-88a6-dfa8e7e375e1', '035e8b11-9be9-402a-a986-f95e4aea9fe9', 'user', 'Booking Approved! 🎉', 'Great news! Your booking for Royal Comfort PG (#BK-BKKQZ101) has been ACCEPTED by the owner.', 'booking_approved', 0, '/bookings', '2026-08-22 08:29:06'),
('2f3b1060-3dd6-4e87-ba53-8ff08e88f67c', 'a0ea13d9-49a7-4e08-b364-7f0f00b97495', 'broker', 'Booking Cancelled by Tenant', 'Booking #BK-KFMZPRNN for Royal Comfort PG was cancelled by tenant.', 'booking_cancelled', 0, '/broker/bookings', '2026-08-22 08:20:26'),
('315e05c3-a07b-47a3-8db4-8bc03ff04334', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Contact Inquiry Received', 'Vikram Singh (vikram.singh@corporate-housing.in) submitted an inquiry regarding: We are an IT firm looking for 50 reserved beds for our new graduate trainee coho...', 'contact_inquiry', 0, 'http://localhost/admin/contacts', '2026-08-18 22:06:11'),
('31bd4702-aca3-4252-8a92-1761c4ad4504', '5cb05e6c-56b8-4a6f-844b-c8437ecea822', 'broker', 'Property Approved 🎉', 'Your listing \"mera bazr\" has been approved and published.', 'property_approved', 0, '/broker/pgs', '2026-08-22 06:55:15'),
('331fc81f-4ea4-4c2d-8b5a-57ae87753214', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"Shree Krishna Girls Executive Stay\" in New Delhi (PG / Hostel) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-22 06:48:05'),
('340d6d04-5d47-469e-9f62-c758cc4045ed', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Contact Inquiry Received', 'Aarav Sharma (aarav.sharma@example.com) submitted an inquiry regarding: Looking for a single sharing PG room near Sony World Signal with high speed WiFi...', 'contact_inquiry', 0, 'http://localhost/admin/contacts', '2026-08-18 22:06:11'),
('34f72134-98c1-4a06-81e4-c40fb399c279', '035e8b11-9be9-402a-a986-f95e4aea9fe9', 'user', 'Booking Approved! 🎉', 'Great news! Your booking for Royal Comfort PG (#BK-ZLOCRDGK) has been ACCEPTED by the owner.', 'booking_approved', 0, '/bookings', '2026-08-22 08:19:59'),
('3693b67f-92a5-470d-bcc3-ea540440e840', '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', 'broker', 'Property Approved 🎉', 'Your listing \"PROPERTY NEW IN DELHOI\" has been approved and published.', 'property_approved', 0, '/broker/pgs', '2026-08-22 02:31:08'),
('3750e450-2f9b-4a38-86b1-e7e3e6b19a3c', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'user', 'Booking Request Sent! 🚀', 'Your request for Sunrise Premium Boys PG (#BK-BJC3IGYX) is sent to the owner for approval. Zero payment required now.', 'booking_pending', 0, '/bookings', '2026-08-22 09:08:17'),
('39e82048-d461-48d7-aa85-19a5700ec98f', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"index.php\" in Greater Noida (PG / Hostel) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-18 21:56:04'),
('3c3d43a1-a0ad-4694-9382-714314182a5b', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"Automated Test Luxury PG - 837\" in Greater Noida (PG / Hostel) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-22 04:14:11'),
('3fd3db7e-edd5-472b-a0cd-2a0444bf2fbf', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"Shree Krishna Girls Executive Stay\" in New Delhi (PG / Hostel) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-22 06:46:53'),
('4215f335-fc58-474c-9e81-1208369b2983', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', 'broker', 'New Booking Request Received! 📋', 'Rahul Admin has requested a stay at Sunrise Premium Boys PG starting Aug 23, 2026.', 'booking_created', 0, '/broker/bookings', '2026-08-22 09:08:17'),
('422ec55e-5a57-4dda-9fe6-2687b0ca417e', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"falt one\" in Greater Noida (Flat / Apartment) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-22 06:43:45'),
('44006532-0603-45ae-9a60-d7588d219118', '035e8b11-9be9-402a-a986-f95e4aea9fe9', 'user', 'Booking Request Sent! 🚀', 'Your request for mera bazr (#BK-FODOVBKJ) is sent to the owner for approval. Zero payment required now.', 'booking_pending', 0, '/bookings', '2026-08-22 08:42:09'),
('4540abab-e0ac-4879-87d2-3fa261048dd2', '035e8b11-9be9-402a-a986-f95e4aea9fe9', 'user', 'Booking Request Sent! 🚀', 'Your request for Royal Comfort PG (#BK-KFMZPRNN) is sent to the owner for approval. Zero payment required now.', 'booking_pending', 0, '/bookings', '2026-08-22 08:20:26'),
('454ad1f4-599e-4016-b73a-695e368f6608', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"Cyber City Premium Office Space\" in Greater Noida (Commercial) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-22 06:46:53'),
('48790a64-62a6-4017-ac50-de4882b1fc89', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"Elite GPS Residency PG\" in Noida (PG / Hostel) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-16 09:07:06'),
('4e2c8a7a-7023-45aa-9b82-08942d439840', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"Cyber City Premium Office Space\" in Greater Noida (Commercial) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-22 06:48:05'),
('50884fda-ccbc-4a43-9cfd-32c79ccf5fd7', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'user', 'Stay Completed 🌟', 'Thank you for staying at new listying! Please consider leaving a review for other prospective tenants.', 'booking_completed', 0, '/bookings', '2026-08-22 08:55:55'),
('51854e7c-e0ed-496a-9451-1ff9a1686ec8', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"saefe 3r3\" in New Delhi (PG / Hostel) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-16 10:41:57'),
('52695f95-b81b-4456-b4c3-9a12e803fa6f', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"new listying\" in Greater Noida (PG / Hostel) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-22 08:52:06'),
('538d266c-953c-4e59-a5dc-66f7c27b96f7', '63576b41-af34-452b-b4a5-2b603bda3fe6', 'broker', 'Property Approved 🎉', 'Your listing \"new listying\" has been approved and published.', 'property_approved', 0, '/broker/pgs', '2026-08-22 08:52:21'),
('53d2a372-17fc-4bf2-b3ce-65fb3b94340b', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"Realistic Luxury PG 712\" in Vadodara (PG / Hostel) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-22 04:39:47'),
('5af270bf-6607-4448-89e9-33136289721a', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'user', 'Booking Request Sent! 🚀', 'Your request for Sunrise Premium Boys PG (#BK-ZA5YDXP8) is sent to the owner for approval. Zero payment required now.', 'booking_pending', 0, '/bookings', '2026-08-22 08:27:33'),
('5b837e29-5579-44ad-9fa5-306bcdac9c1e', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Review Pending Approval ⭐', 'User \"User\" submitted a 4.5★ review for \"Urban Nest Co-Living Spaces\".', 'property_review_pending', 0, NULL, '2026-08-18 12:56:50'),
('5ce6967f-57b2-4a4a-850f-dab642a76dcb', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Review Pending Approval ⭐', 'User \"rishikesh jadaun\" submitted a 5★ review for \"test user\".', 'property_review_pending', 0, NULL, '2026-08-18 13:06:43'),
('6028a500-ea34-4817-be08-dbe360f734cb', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"Green Valley 2BHK Luxury Apartment Test\" in Bangalore (Bengaluru) (Flat / Apartment) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-22 06:49:58'),
('61969126-3bba-4f8d-83ae-09bd5950071e', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"Automated Test Luxury PG - 737\" in Greater Noida (PG / Hostel) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-22 04:10:15'),
('65745680-e766-49da-b18f-914f5b4a4f13', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"test\" in New Delhi (PG / Hostel) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-16 09:54:59'),
('663cc5d8-7da4-4d63-821a-d505bfd7f12c', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Review Pending Approval ⭐', 'User \"User\" submitted a 5★ review for \"Urban Nest Co-Living Spaces\".', 'property_review_pending', 0, NULL, '2026-08-18 12:42:31'),
('666ea31c-b65f-4f3c-89fd-ef2caebb5d7b', 'a0ea13d9-49a7-4e08-b364-7f0f00b97495', 'broker', 'Dedicated Relationship Manager Assigned 🤝', 'Rohan Mehta (South Zone (Bangalore & Hyderabad)) has been assigned as your dedicated StayNest Relationship Manager.', 'rm_assigned', 0, '/broker/profile', '2026-08-17 21:06:29'),
('66972ea8-fc11-411a-8272-a9bf99c26864', '6a74294b-2c66-4f6b-b4de-bee2e365fa6b', 'broker', 'Dedicated Relationship Manager Assigned 🤝', 'Karan Kapoor (VIP Pan-India Co-Living) has been assigned as your dedicated StayNest Relationship Manager.', 'rm_assigned', 0, '/broker/profile', '2026-08-17 21:12:22'),
('694e1e02-ceff-4865-b228-b559de95beb4', 'be8170ed-18c1-47fd-8313-da286315bc89', 'broker', 'Dedicated Relationship Manager Assigned 🤝', 'Karan Kapoor (VIP Pan-India Co-Living) has been assigned as your dedicated StayNest Relationship Manager.', 'rm_assigned', 0, '/broker/profile', '2026-08-17 21:12:22'),
('6bfbf6c4-df16-4592-adf9-23966a5898ec', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Contact Inquiry Received', 'Priya Patel (priya.patel@gmail.com) submitted an inquiry regarding: I have a 30-bed luxury PG property in Sector 62 Noida and want to partner with S...', 'contact_inquiry', 0, 'http://localhost/admin/contacts', '2026-08-18 22:09:14'),
('6e42b272-622a-4807-a328-e1b54b14c9d3', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Review Pending Approval ⭐', 'User \"Rahul Admin\" submitted a 5★ review for \"Sunrise Premium Boys PG\".', 'property_review_pending', 0, NULL, '2026-08-22 09:13:01'),
('6eee0cc1-02a8-4bfc-b733-b507d65c61b3', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"mera bazr\" in Greater Noida (Flat / Apartment) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-22 06:55:03'),
('70101ff8-5dc1-461f-b87b-4ae4149af69f', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'user', 'Review Published! ⭐', 'Your review for \"PROPERTY NEW IN DELHOI\" was approved and is now public.', 'review_approved', 0, NULL, '2026-08-22 02:36:11'),
('736609a1-ced5-4f1b-9815-e025fd09b99d', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', 'broker', 'Dedicated Relationship Manager Assigned 🤝', 'Ananya Sengupta (North Zone (Noida & Delhi NCR)) has been assigned as your dedicated StayNest Relationship Manager.', 'rm_assigned', 0, '/broker/profile', '2026-08-17 21:04:11'),
('76170ab4-a952-4815-a2c0-40abd95fccce', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"shp one by one added\" in Greater Noida (Commercial) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-22 06:45:03'),
('7791dd7b-81e7-4d4c-b9cc-47b557e85251', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"Grand Stay Luxury PG with Master Bedroom\" in Noida (PG / Hostel) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-16 10:09:36'),
('781f7b04-a614-4983-8450-f2de7e7baf32', 'a0ea13d9-49a7-4e08-b364-7f0f00b97495', 'broker', 'New Booking Request Received! 📋', 'test user has requested a stay at Royal Comfort PG starting Aug 27, 2026.', 'booking_created', 0, '/broker/bookings', '2026-08-22 08:20:26'),
('782e4742-0124-47d6-be47-cd627708c26e', 'a0ea13d9-49a7-4e08-b364-7f0f00b97495', 'broker', 'Dedicated Relationship Manager Assigned 🤝', 'Karan Kapoor (VIP Pan-India Co-Living) has been assigned as your dedicated StayNest Relationship Manager.', 'rm_assigned', 0, '/broker/profile', '2026-08-17 21:12:22'),
('7983b9db-8a15-43f6-b548-97be90437bf5', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Review Pending Approval ⭐', 'User \"Rahul Admin\" submitted a 3★ review for \"PROPERTY NEW IN DELHOI\".', 'property_review_pending', 0, NULL, '2026-08-22 02:32:53'),
('79e3fef6-808e-4c53-9d3b-1bc6e2488b48', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', 'broker', 'New Booking Request Received! 📋', 'Rahul Admin has requested a stay at Sunrise Premium Boys PG starting Aug 23, 2026.', 'booking_created', 0, '/broker/bookings', '2026-08-22 09:09:13'),
('7d7cc759-da08-4060-8bbe-117340bad977', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'user', 'Booking Request Sent! 🚀', 'Your request for Sunrise Premium Boys PG (#BK-SW1FOKOQ) is sent to the owner for approval. Zero payment required now.', 'booking_pending', 0, '/bookings', '2026-08-22 09:11:35'),
('7e2c2466-6b5d-4381-a170-5f7f101e0d33', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"Base64 Photo PG Residency\" in Noida (PG / Hostel) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-16 09:59:14'),
('7ea8c2fc-1b3d-41ec-b6fd-9f597e2f5fbd', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', 'broker', 'Dedicated Relationship Manager Assigned 🤝', 'Rohan Mehta (South Zone (Bangalore & Hyderabad)) has been assigned as your dedicated StayNest Relationship Manager.', 'rm_assigned', 0, '/broker/profile', '2026-08-17 21:04:09'),
('7ed931c6-1463-4e12-a189-2fc0f1057d24', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Contact Inquiry Received', 'Sneha Kulkarni (sneha.k@outlook.com) submitted an inquiry regarding: Hi, I am currently staying at StayNest Green View PG. Needed help with my rent r...', 'contact_inquiry', 0, 'http://localhost/admin/contacts', '2026-08-18 22:06:26'),
('80a724a0-dd36-431b-aab7-c4e70fa28d3f', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Contact Inquiry Received', 'Priya Patel (priya.patel@gmail.com) submitted an inquiry regarding: I have a 30-bed luxury PG property in Sector 62 Noida and want to partner with S...', 'contact_inquiry', 0, 'http://localhost/admin/contacts', '2026-08-18 22:06:11'),
('80e05f57-6bd6-41a4-bd4b-13cf3ac4afa5', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', 'broker', 'Dedicated Relationship Manager Assigned 🤝', 'Ananya Sengupta (North Zone (Noida & Delhi NCR)) has been assigned as your dedicated StayNest Relationship Manager.', 'rm_assigned', 0, '/broker/profile', '2026-08-17 21:10:32'),
('8364df88-0662-4d2d-b296-d59d96e5958c', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Contact Inquiry Received', 'test user (admin@gmail.com) submitted an inquiry regarding: ffe  wefefweg', 'contact_inquiry', 0, 'http://127.0.0.1:8000/admin/contacts', '2026-08-18 22:08:16'),
('85024ed8-c278-47d8-b8cf-af55211a1e86', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', 'broker', 'New Booking Request Received! 📋', 'Rahul Admin has requested a stay at Sunrise Premium Boys PG starting Aug 23, 2026.', 'booking_created', 0, '/broker/bookings', '2026-08-22 08:27:33'),
('8b71a76c-00ea-4e6a-a142-0f9849062fa7', '035e8b11-9be9-402a-a986-f95e4aea9fe9', 'user', 'Booking Confirmed by Admin 🎉', 'Your booking for mera bazr (#BK-FODOVBKJ) has been confirmed by StayNest Admin.', 'booking_approved', 0, '/bookings', '2026-08-22 08:49:01'),
('8b7226ed-d58a-49cf-a2f7-6d072c619660', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"Green Valley 2BHK Luxury Apartment\" in Bangalore (Bengaluru) (Flat / Apartment) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-22 06:48:05'),
('8c04e646-8115-4c6e-97a0-ee1a43607158', 'a0ea13d9-49a7-4e08-b364-7f0f00b97495', 'broker', 'New Booking Request Received! 📋', 'test user has requested a stay at Royal Comfort PG starting Aug 25, 2026.', 'booking_created', 0, '/broker/bookings', '2026-08-22 08:18:20'),
('8cb237af-a66a-42be-95a0-9d75cdd3c1a8', '5cb05e6c-56b8-4a6f-844b-c8437ecea822', 'broker', 'Property Approved 🎉', 'Your listing \"Grand Stay Luxury PG with Master Bedroom\" has been approved and published.', 'property_approved', 0, '/broker/pgs', '2026-08-18 10:43:07'),
('8fcded5f-8b78-40ca-93e0-f27dd7dba3fe', '77a10f6a-c120-405c-bca9-f95341a44a15', 'user', 'Review Published! ⭐', 'Your review for \"test user\" was approved and is now public.', 'review_approved', 0, NULL, '2026-08-18 13:07:35'),
('941f10b9-808f-4649-9c6f-c0dd3095104f', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"Starlight Co-living PG\" in Vadodara (PG / Hostel) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-22 04:55:18'),
('94c425b3-5636-43ef-8d9a-a2d350c8ff12', '5cb05e6c-56b8-4a6f-844b-c8437ecea822', 'broker', 'Property Approved 🎉', 'Your listing \"Grand Stay Luxury PG with Master Bedroom\" has been approved and published.', 'property_approved', 0, '/broker/pgs', '2026-08-18 10:43:09'),
('9b3f36fc-3e53-4526-8197-1efeae65e58d', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"Grand Stay Luxury PG with Master Bedroom\" in Noida (PG / Hostel) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-16 10:10:08'),
('9ed68505-5b2e-45c9-9873-0b99fc3372eb', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"Starlight Co-living PG\" in Vadodara (PG / Hostel) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-22 04:56:31'),
('a407c67d-4a49-4107-be07-f36252850a54', '035e8b11-9be9-402a-a986-f95e4aea9fe9', 'broker', 'Property Approved 🎉', 'Your listing \"my property square\" has been approved and published.', 'property_approved', 0, '/broker/pgs', '2026-08-22 04:03:02'),
('a5b9df3f-c820-459f-8434-5b8fc5437b8f', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"Green Valley 2BHK Luxury Apartment\" in Bangalore (Bengaluru) (Flat / Apartment) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-22 06:46:53'),
('a660c834-9949-405e-9fd3-ded59270330b', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'Property Reported ⚠️', 'Listing \"Elite GPS Residency PG\" was reported for: Property Closed / Already Full.', 'property_report', 0, NULL, '2026-08-18 12:11:11'),
('a6d3abb4-b96e-4c1a-97b2-de2dbb807e31', '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', 'broker', 'Property Approved 🎉', 'Your listing \"saefe 3r3\" has been approved and published.', 'property_approved', 0, '/broker/pgs', '2026-08-18 10:48:25'),
('a6e77bb7-1d0c-44b2-92ed-e06c4f9347f2', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', 'broker', 'Dedicated Relationship Manager Assigned 🤝', 'Karan Kapoor (VIP Pan-India Co-Living) has been assigned as your dedicated StayNest Relationship Manager.', 'rm_assigned', 0, '/broker/profile', '2026-08-17 21:12:22'),
('a71e0b45-17d6-4341-abe5-a9f75c6e15bd', '5cb05e6c-56b8-4a6f-844b-c8437ecea822', 'broker', 'Dedicated Relationship Manager Assigned 🤝', 'Karan Kapoor (VIP Pan-India Co-Living) has been assigned as your dedicated StayNest Relationship Manager.', 'rm_assigned', 0, '/broker/profile', '2026-08-17 21:12:22'),
('a74f12fd-513d-40e9-9856-0cd4a0694e92', '63576b41-af34-452b-b4a5-2b603bda3fe6', 'broker', 'Broker Account Verified 🎉', 'Congratulations! Your partner broker account & KYC documents have been approved by StayNest administration.', 'kyc_approved', 0, '/broker/dashboard', '2026-08-17 21:21:10'),
('a7f719c0-13e7-4c2c-919a-424538941db7', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"shop\" in Greater Noida (Commercial) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-22 04:46:31'),
('abc30cc6-5b1f-404f-89e8-79571ccfab17', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"Royal Comfort PG\" in Bangalore (PG / Hostel) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-16 08:59:52'),
('acd2369d-4a65-4a61-8ed7-b93139d6c700', '63576b41-af34-452b-b4a5-2b603bda3fe6', 'broker', 'New Booking Request Received! 📋', 'Rahul Admin has requested a stay at new listying starting Aug 23, 2026.', 'booking_created', 0, '/broker/bookings', '2026-08-22 08:52:43'),
('ad64ef09-d1a6-45ef-afb0-24283a0daf74', '5cb05e6c-56b8-4a6f-844b-c8437ecea822', 'broker', 'New Booking Request Received! 📋', 'test user has requested a stay at mera bazr starting Aug 23, 2026.', 'booking_created', 0, '/broker/bookings', '2026-08-22 08:42:09'),
('aded2252-1c7b-4d4d-8ef5-87b9281f57ca', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"Automated Test Luxury PG - 913\" in Greater Noida (PG / Hostel) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-22 04:11:12'),
('b1864a59-5cf8-49d6-a365-7221dfaa915d', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"Cyber City Premium Office Space Test\" in Greater Noida (Commercial) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-22 06:49:58'),
('b2bc0619-5c77-4a78-97f9-10ce158fb47d', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Contact Inquiry Received', 'Broker Agency Partner Test (partner.test@agency.com) submitted an inquiry regarding: We are a property management agency with 50+ PG beds in Koramangala looking to o...', 'contact_inquiry', 0, 'http://localhost/admin/contacts', '2026-08-22 00:59:14'),
('b2bf9b88-a0fe-432b-acbf-1d925c48d1fe', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"Automated Test Luxury PG - 532\" in Greater Noida (PG / Hostel) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-22 04:22:30'),
('b5402ba3-c931-4dec-9ea6-e1ace9887f4d', '77a10f6a-c120-405c-bca9-f95341a44a15', 'user', 'Review Published! ⭐', 'Your review for \"Urban Nest Co-Living Spaces\" was approved and is now public.', 'review_approved', 0, NULL, '2026-08-18 13:03:10'),
('b5ec1e2e-8104-4ad3-a9dc-eea99c15768e', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Contact Inquiry Received', 'Sneha Kulkarni (sneha.k@outlook.com) submitted an inquiry regarding: Hi, I am currently staying at StayNest Green View PG. Needed help with my rent r...', 'contact_inquiry', 0, 'http://localhost/admin/contacts', '2026-08-18 22:06:11'),
('b98d0bc2-47ed-4a22-82e8-0744a9a8ddab', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Review Pending Approval ⭐', 'User \"User\" submitted a 5★ review for \"Urban Nest Co-Living Spaces\".', 'property_review_pending', 0, NULL, '2026-08-18 13:01:36'),
('ba6df540-771b-4235-a2b0-bea1caeef38c', '5cb05e6c-56b8-4a6f-844b-c8437ecea822', 'broker', 'Property Approved 🎉', 'Your listing \"shp one by one added\" has been approved and published.', 'property_approved', 0, '/broker/pgs', '2026-08-22 06:45:15'),
('bb69e879-4692-45bc-9534-d98aff834b50', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"No Photo PG Residency\" in Noida (PG / Hostel) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-16 09:59:13'),
('bc839b42-00f5-4c8d-b2eb-0f9ca336ee4d', '5cb05e6c-56b8-4a6f-844b-c8437ecea822', 'broker', 'Property Approved 🎉', 'Your listing \"Elite GPS Residency PG\" has been approved and published.', 'property_approved', 0, '/broker/pgs', '2026-08-17 21:11:06'),
('bcb2a293-f797-4a41-a4fd-3172cfb3747c', '035e8b11-9be9-402a-a986-f95e4aea9fe9', 'user', 'Booking Request Sent! 🚀', 'Your request for Royal Comfort PG (#BK-ZLOCRDGK) is sent to the owner for approval. Zero payment required now.', 'booking_pending', 0, '/bookings', '2026-08-22 08:19:59'),
('bcbb9a0b-fe80-47b0-b25a-2724f662d978', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', 'broker', 'New Booking Request Received! 📋', 'test user has requested a stay at Sunrise Premium Boys PG starting Aug 23, 2026.', 'booking_created', 0, '/broker/bookings', '2026-08-22 08:33:19'),
('bd0f8a4c-e7cf-4c24-96c4-c35c5bcdd25f', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', 'broker', 'Booking Cancelled by Tenant', 'Booking #BK-ZA5YDXP8 for Sunrise Premium Boys PG was cancelled by tenant.', 'booking_cancelled', 0, '/broker/bookings', '2026-08-22 09:43:57'),
('bde4707b-8d78-4164-b7f1-e15b44b21240', '5cb05e6c-56b8-4a6f-844b-c8437ecea822', 'broker', 'Property Approved 🎉', 'Your listing \"Elite GPS Residency PG\" has been approved and published.', 'property_approved', 0, '/broker/pgs', '2026-08-16 22:25:13'),
('be4bc9ba-4459-4b23-b5bd-8094f5e0f853', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"rv 3r3r4\" in New Delhi (PG / Hostel) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-16 21:23:15'),
('c174a7ac-6368-4c52-a269-c8875cbd979c', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', 'broker', 'Booking Cancelled by Tenant', 'Booking #BK-SW1FOKOQ for Sunrise Premium Boys PG was cancelled by tenant.', 'booking_cancelled', 0, '/broker/bookings', '2026-08-22 09:43:44'),
('c5d28719-aa3f-413c-bb83-42116a3648ac', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'Property Reported ⚠️', 'Listing \"Sunrise Premium Boys PG\" was reported for: Fake or Misleading Photos.', 'property_report', 0, NULL, '2026-08-19 20:06:43'),
('c9663d64-a1bd-4acc-8a43-f4c109980103', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"my property square\" in Greater Noida (PG / Hostel) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-22 04:01:43'),
('ca5ad283-98ea-4edf-b44a-bfae2114a281', 'a0ea13d9-49a7-4e08-b364-7f0f00b97495', 'broker', 'New Booking Request Received! 📋', 'Rajesh Sharma has requested a stay at Royal Comfort PG starting Aug 24, 2026.', 'booking_created', 0, '/broker/bookings', '2026-08-23 07:05:52'),
('caab34b8-b162-4f04-9399-4f6e84f97e6f', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Contact Inquiry Received', 'Karan Malhotra (karan.m@gmail.com) submitted an inquiry regarding: Inquiring about StayNest PG availability and monthly deposit terms.', 'contact_inquiry', 0, 'http://127.0.0.1:8000/admin/contacts', '2026-08-18 22:08:37'),
('cd02a1b5-81e5-4db5-84ca-263813fd83d7', '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', 'broker', 'Property Approved 🎉', 'Your listing \"falt one\" has been approved and published.', 'property_approved', 0, '/broker/pgs', '2026-08-22 06:43:59'),
('cd21358b-5787-433f-8094-47a116475e13', '5cb05e6c-56b8-4a6f-844b-c8437ecea822', 'broker', 'Property Approved 🎉', 'Your listing \"shop\" has been approved and published.', 'property_approved', 0, '/broker/pgs', '2026-08-22 04:46:44'),
('d7392eb7-5b9a-4d13-a606-0dd78274883b', '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', 'broker', 'Property Approved 🎉', 'Your listing \"index.php\" has been approved and published.', 'property_approved', 0, '/broker/pgs', '2026-08-18 22:01:51'),
('d7addb9e-4ac2-4391-b02c-622cbda48d98', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'Property Reported ⚠️', 'Listing \"Royal Heritage Executive PG\" was reported for: Other Issue.', 'property_report', 0, NULL, '2026-08-18 11:57:03'),
('d8011bcd-1cad-48ee-9aea-341e8c91a810', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Contact Inquiry Received', 'Aarav Sharma (aarav.sharma@example.com) submitted an inquiry regarding: Looking for a single sharing PG room near Sony World Signal with high speed WiFi...', 'contact_inquiry', 0, 'http://localhost/admin/contacts', '2026-08-18 22:06:26'),
('d8c4cd7e-6d76-4fd8-8598-5285262b570c', 'a0ea13d9-49a7-4e08-b364-7f0f00b97495', 'broker', 'Property Approved & Published 🎉', 'Your listing \"Royal Comfort PG\" has been approved and is now live on StayNest.', 'property_approved', 0, '/broker/pgs', '2026-08-16 08:59:52'),
('dea062fc-1ad2-4ddc-a29a-65ab41e524ed', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Contact Inquiry Received', 'Sneha Kulkarni (sneha.k@outlook.com) submitted an inquiry regarding: Hi, I am currently staying at StayNest Green View PG. Needed help with my rent r...', 'contact_inquiry', 0, 'http://localhost/admin/contacts', '2026-08-18 22:09:14'),
('e16c88c3-a3c5-4cf5-9ecd-b84886dd675a', '2d28d9da-2144-487d-997e-68b5624706f1', 'broker', 'Property Approved 🎉', 'Your listing \"Updated Sunrise Premium Residency (Updated)\" has been verified and is now live.', 'property_approved', 0, '/broker/pgs', '2026-08-22 04:51:39'),
('e3044027-ddee-4ec3-aafb-3848cdde8e5e', '63576b41-af34-452b-b4a5-2b603bda3fe6', 'broker', 'Dedicated Relationship Manager Assigned 🤝', 'Karan Kapoor (VIP Pan-India Co-Living) has been assigned as your dedicated StayNest Relationship Manager.', 'rm_assigned', 0, '/broker/profile', '2026-08-17 21:12:22'),
('eae48ca7-e4e7-4bdc-af0e-62160923a564', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', 'broker', 'Property Approved 🎉', 'Your listing \"Sunrise Premium PG\" has been approved and published.', 'property_approved', 0, '/broker/pgs', '2026-08-16 22:38:04'),
('ebb71df0-9614-4cbd-8db3-53afeb972cd4', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"qdd33r3\" in New Delhi (PG / Hostel) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-16 21:17:43'),
('ed3d0fd0-a8b4-485b-96a8-bf7cd3788815', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"qwd 3r23\" in Noida (PG / Hostel) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-16 10:43:55'),
('f3ae7113-115e-4d94-b079-ca3c3e1dd355', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"Realistic Luxury PG 963\" in Vadodara (PG / Hostel) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-22 04:33:58'),
('f52801a0-5343-41de-9279-ae58926b0595', '5cb05e6c-56b8-4a6f-844b-c8437ecea822', 'broker', 'Booking Confirmed by Admin', 'Booking #BK-FODOVBKJ for mera bazr was approved by Admin.', 'booking_approved', 0, '/broker/bookings', '2026-08-22 08:49:01'),
('f8389713-4f6e-46c8-8390-5c08765ccc9a', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"PROPERTY NEW IN DELHOI\" in New Delhi (PG / Hostel) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-22 02:28:15'),
('f9bd025d-12ec-4a7a-8df0-4348dba37463', '2d28d9da-2144-487d-997e-68b5624706f1', 'user', 'Review Published! ⭐', 'Your review for \"Urban Nest Co-Living Spaces\" was approved and is now public.', 'review_approved', 0, NULL, '2026-08-18 12:42:32'),
('faaa8f71-c21c-478f-9189-79299f3030c5', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'user', 'Booking Request Sent! 🚀', 'Your request for Sunrise Premium Boys PG (#BK-YECAGZW8) is sent to the owner for approval. Zero payment required now.', 'booking_pending', 0, '/bookings', '2026-08-22 09:09:13'),
('fb33e366-63fb-4dae-b16d-e3e0a8a8d33c', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Listing Submitted for Review', 'New property \"Automated Test Luxury PG - 546\" in Greater Noida (PG / Hostel) is awaiting admin approval.', 'property_submission', 0, '/admin/pgs', '2026-08-22 04:13:56'),
('fbff4f0b-e630-45bd-a684-150cc45519c2', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'user', 'Booking Approved! 🎉', 'Great news! Your booking for new listying (#BK-J3HVT5Q7) has been ACCEPTED by the owner.', 'booking_approved', 0, '/bookings', '2026-08-22 08:53:07'),
('fc7efae7-c232-46e4-8020-d2c786195e07', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'Property Reported ⚠️', 'Listing \"Elite GPS Residency PG\" was reported for: Host Unreachable / Rude / Abusive Behavior.', 'property_report', 0, NULL, '2026-08-18 12:10:53'),
('fcc2043e-b110-443a-b9d0-ceabf2c28faa', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin', 'New Contact Inquiry Received', 'Priya Patel (priya.patel@gmail.com) submitted an inquiry regarding: I have a 30-bed luxury PG property in Sector 62 Noida and want to partner with S...', 'contact_inquiry', 0, 'http://localhost/admin/contacts', '2026-08-18 22:06:26');

-- --------------------------------------------------------

--
-- Table structure for table `occupations`
--

CREATE TABLE `occupations` (
  `id` char(36) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id` char(36) NOT NULL,
  `name` varchar(200) NOT NULL,
  `slug` varchar(220) NOT NULL,
  `logo_url` varchar(500) DEFAULT NULL,
  `subscription_plan` varchar(50) DEFAULT 'free',
  `subscription_status` varchar(50) DEFAULT 'active',
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`settings`)),
  `limits` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`limits`)),
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organization_settings`
--

CREATE TABLE `organization_settings` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `organization_id` char(36) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('string','number','boolean','json','encrypted') DEFAULT 'string',
  `is_encrypted` tinyint(1) DEFAULT 0,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `updated_by` char(36) DEFAULT NULL,
  `deleted_by` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organization_users`
--

CREATE TABLE `organization_users` (
  `id` char(36) NOT NULL,
  `organization_id` char(36) NOT NULL,
  `user_id` char(36) NOT NULL,
  `role_id` char(36) DEFAULT NULL,
  `is_owner` tinyint(1) DEFAULT 0,
  `is_admin` tinyint(1) DEFAULT 0,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `otp_verifications`
--

CREATE TABLE `otp_verifications` (
  `id` char(36) NOT NULL,
  `user_id` char(36) DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `channel` varchar(20) NOT NULL,
  `recipient` varchar(255) NOT NULL,
  `otp_hash` varchar(255) NOT NULL,
  `attempts` int(11) DEFAULT 0,
  `max_attempts` int(11) DEFAULT 5,
  `resend_count` int(11) DEFAULT 0,
  `status` enum('pending','verified','expired','blocked') DEFAULT 'pending',
  `verified_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `blocked_until` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_history`
--

CREATE TABLE `password_history` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `user_id` char(36) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `changed_by` char(36) DEFAULT NULL,
  `reason` enum('user_change','admin_reset','expired','compromised') DEFAULT 'user_change'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('vikram@broker.com', '$2y$12$B/LLMamlcgsc5Lb5APeqMOaTWX6ugKNMFEuaBmQQmgSBO6Yg76hPa', '2026-08-22 01:07:20');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` char(36) NOT NULL,
  `payment_id` varchar(30) NOT NULL,
  `invoice_id` char(36) DEFAULT NULL,
  `booking_id` char(36) DEFAULT NULL,
  `user_id` char(36) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_method` enum('upi','card','netbanking','wallet','cash') NOT NULL,
  `gateway` enum('razorpay','stripe','paytm','manual') DEFAULT 'razorpay',
  `gateway_payment_id` varchar(100) DEFAULT NULL,
  `status` enum('pending','success','failed','refunded') DEFAULT 'pending',
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `receipt_url` varchar(500) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` char(36) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `module` varchar(50) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `is_active`, `version`, `created_at`, `updated_at`, `deleted_at`) VALUES
('c9b21d02-8dab-11f1-a4cf-1062e5a5cd6c', 'View Properties', 'property.view', 'property', 1, 1, '2026-08-01 13:20:38', '2026-08-01 13:20:38', NULL),
('c9b21e7c-8dab-11f1-a4cf-1062e5a5cd6c', 'Create Bookings', 'booking.create', 'booking', 1, 1, '2026-08-01 13:20:38', '2026-08-01 13:20:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` varchar(255) NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', 'staynest-api-token', 'fa393c8e6dde1e1553ed885685151972468ab8773f1c22d020633d7108fed775', '[\"*\"]', NULL, NULL, '2026-08-16 07:55:26', '2026-08-16 07:55:26'),
(2, 'App\\Models\\User', '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', 'staynest-api-token', 'fe0bb3eb67d1b278364446949504111f9aeee81332bd35687c59ad22b3dd44e0', '[\"*\"]', NULL, NULL, '2026-08-16 07:56:13', '2026-08-16 07:56:13'),
(3, 'App\\Models\\User', '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', 'staynest-api-token', 'b5e8ceee79dc414151b6f1c5cd13c480940014e09b766e9834e092a033cf4181', '[\"*\"]', NULL, NULL, '2026-08-16 07:56:29', '2026-08-16 07:56:29'),
(14, 'App\\Models\\User', 'bd238724-7aee-4107-8833-384acfcfb0be', 'staynest-api-token', '1744c59dd0984c5c569cfc5aacc91473d0df1a3927bcf51e5dde6dfe16828067', '[\"*\"]', NULL, NULL, '2026-08-17 21:16:26', '2026-08-17 21:16:26'),
(16, 'App\\Models\\User', '63576b41-af34-452b-b4a5-2b603bda3fe6', 'staynest-api-token', '60ffe1703d865be8dec8c2a2142316e691cc252735f359c15b5e73c97e4b6933', '[\"*\"]', NULL, NULL, '2026-08-18 12:44:41', '2026-08-18 12:44:41'),
(17, 'App\\Models\\User', '77a10f6a-c120-405c-bca9-f95341a44a15', 'staynest-api-token', '62b52006a38cd6b19a736d4e0dfc7bb6ef167300c23bf70014409afd7ce5dd93', '[\"*\"]', NULL, NULL, '2026-08-18 12:47:44', '2026-08-18 12:47:44'),
(18, 'App\\Models\\User', '77a10f6a-c120-405c-bca9-f95341a44a15', 'staynest-api-token', 'b2454e2d10eae80f74a40b02ad532d67998b8d08effae834c17db0368edcd446', '[\"*\"]', NULL, NULL, '2026-08-19 20:35:35', '2026-08-19 20:35:35'),
(20, 'App\\Models\\User', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'staynest-api-token', '7f2b18ade593f25cd61a530f8862d891cda2b862c26b98b3d0dd85ebf9e7fdd3', '[\"*\"]', NULL, NULL, '2026-08-19 21:51:52', '2026-08-19 21:51:52'),
(22, 'App\\Models\\User', '2d28d9da-2144-487d-997e-68b5624706f1', 'staynest-api-token', '4d4b07eb4710127792cd6f55e83d9e996a17edbf91331aa1d649f99b0aaf0d5a', '[\"*\"]', NULL, NULL, '2026-08-19 22:00:29', '2026-08-19 22:00:29'),
(23, 'App\\Models\\User', '2d28d9da-2144-487d-997e-68b5624706f1', 'staynest-api-token', 'f45102624569e664bfffe553995f32236d922a22f965bff43d6aaa0adab8397e', '[\"*\"]', NULL, NULL, '2026-08-22 00:49:28', '2026-08-22 00:49:28'),
(25, 'App\\Models\\User', '63576b41-af34-452b-b4a5-2b603bda3fe6', 'staynest-api-token', '0536a418c525786bdb6b1d43ec81574b17d4965e9593a2c4b3b49cef17adb6ab', '[\"*\"]', NULL, NULL, '2026-08-22 00:53:39', '2026-08-22 00:53:39'),
(26, 'App\\Models\\User', '2d28d9da-2144-487d-997e-68b5624706f1', 'staynest-api-token', '52f5a39d72f0ffcf003c448fc6f01b257d403024b7574152e83a1b614f5ca9ad', '[\"*\"]', NULL, NULL, '2026-08-22 00:53:46', '2026-08-22 00:53:46'),
(29, 'App\\Models\\User', '035e8b11-9be9-402a-a986-f95e4aea9fe9', 'staynest-api-token', 'ce425342d2e01b8f8df0c19e4a465af2ab4633e14b81114c357e1c9e87cee4c7', '[\"*\"]', NULL, NULL, '2026-08-22 02:55:53', '2026-08-22 02:55:53'),
(30, 'App\\Models\\User', '035e8b11-9be9-402a-a986-f95e4aea9fe9', 'staynest-api-token', '5dc9ec7fe61f6cf94557bda7ad80e240c34fd2125e2d7141b83ea9d4c49424ff', '[\"*\"]', NULL, NULL, '2026-08-22 08:32:41', '2026-08-22 08:32:41'),
(31, 'App\\Models\\User', '63576b41-af34-452b-b4a5-2b603bda3fe6', 'staynest-broker-token', '8d917154b83e1b4c57243246cbc7706d45a973a6931580b67ad2be8b82364cef', '[\"*\"]', NULL, NULL, '2026-08-22 08:51:09', '2026-08-22 08:51:09');

-- --------------------------------------------------------

--
-- Table structure for table `platform_settings`
--

CREATE TABLE `platform_settings` (
  `id` char(36) NOT NULL,
  `key` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `group` varchar(50) NOT NULL DEFAULT 'general',
  `type` varchar(20) NOT NULL DEFAULT 'string',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `platform_settings`
--

INSERT INTO `platform_settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES
('029bef05-2e2d-4a51-a86f-f3948d7ccdc2', 'two_factor_auth_required', '0', 'security', 'boolean', '2026-08-16 22:40:27', '2026-08-16 22:40:27'),
('3146bd41-2487-4ce1-b216-1ad39fb1995f', 'maintenance_mode', '0', 'security', 'boolean', '2026-08-16 22:40:27', '2026-08-16 22:40:27'),
('3fd6481e-6426-4fa1-b136-bd225b91fc76', 'platform_name', 'StayNest', 'general', 'string', '2026-08-16 22:40:27', '2026-08-16 22:40:27'),
('4147e419-e0d9-4281-b85c-b2a8575dc1e2', 'support_phone', '+91 98765 43210', 'general', 'string', '2026-08-16 22:40:27', '2026-08-16 22:40:27'),
('5990bfc2-4c5d-4b6e-84a7-ac1e7161a758', 'platform_tagline', 'Premium Verified Co-Living & PGs across India', 'general', 'string', '2026-08-16 22:40:27', '2026-08-16 22:40:27'),
('643e9417-cabf-4fd1-bef3-3dc74ea3833a', 'payment_mode', 'test', 'payment', 'string', '2026-08-16 22:40:27', '2026-08-16 22:40:27'),
('67c1a308-1a33-4e4e-ac75-e6509a8d0340', 'auto_sms_whatsapp_alerts', '1', 'booking', 'boolean', '2026-08-16 22:40:27', '2026-08-16 22:40:27'),
('72fff0c7-b559-44fd-999c-c7300aed6338', 'currency_symbol', '₹', 'general', 'string', '2026-08-16 22:40:27', '2026-08-16 22:40:27'),
('76a9b3b3-98e9-4563-9e2b-9df4a57e482a', 'enable_guest_inquiry', '1', 'booking', 'boolean', '2026-08-16 22:40:27', '2026-08-16 22:40:27'),
('99b9d304-d2d4-407c-bab7-2fdb54ef8b53', 'platform_description', 'Making PG and co-living simple, safe, and comfortable with zero brokerage and verified amenities across India.', 'general', 'string', '2026-08-16 22:40:27', '2026-08-16 22:40:27'),
('a79f1e90-06ed-4c74-b2f2-32d0e57bfd2c', 'broker_commission_percentage', '10', 'booking', 'number', '2026-08-16 22:40:27', '2026-08-16 22:40:27'),
('a94a5939-b5a0-4cff-b837-d2cf88b6c602', 'razorpay_key_id', 'rzp_live_9381kdf89241', 'payment', 'string', '2026-08-16 22:40:27', '2026-08-16 22:40:27'),
('b4e75cdf-0c19-410b-acb1-a6d1a43770d7', 'mandatory_broker_kyc', '1', 'booking', 'boolean', '2026-08-16 22:40:27', '2026-08-16 22:40:27'),
('c3c8f3d2-c2e2-4535-b132-e4d008531d3d', 'notice_period_days', '30', 'booking', 'number', '2026-08-16 22:40:27', '2026-08-16 22:40:27'),
('c69b8fd4-0078-4912-8a7f-7350d89d5ef4', 'primary_city', 'Delhi NCR', 'general', 'string', '2026-08-16 22:40:27', '2026-08-16 22:40:27'),
('d36197cf-997e-423a-8cec-9ae995f20b23', 'razorpay_key_secret', 'sec_live_k89214710928341', 'payment', 'string', '2026-08-16 22:40:27', '2026-08-16 22:40:27'),
('f62b9bb0-a821-43c8-8f46-73ca85cd857a', 'auto_approve_bookings', '1', 'booking', 'boolean', '2026-08-16 22:40:27', '2026-08-16 22:40:27'),
('fd4b3469-ed6d-4153-8c34-bf00924eb23d', 'support_email', 'support@staynest.com', 'general', 'string', '2026-08-16 22:40:27', '2026-08-16 22:40:27');

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `id` char(36) NOT NULL,
  `organization_id` char(36) DEFAULT NULL,
  `broker_id` char(36) NOT NULL,
  `city_id` char(36) NOT NULL,
  `area_id` char(36) NOT NULL,
  `property_type_id` char(36) NOT NULL,
  `name` varchar(200) NOT NULL,
  `slug` varchar(220) NOT NULL,
  `description` text DEFAULT NULL,
  `address` text NOT NULL,
  `landmark` varchar(200) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `geohash` varchar(12) DEFAULT NULL,
  `gender_preference` varchar(30) DEFAULT NULL,
  `total_beds` int(11) NOT NULL DEFAULT 0,
  `available_beds` int(11) NOT NULL DEFAULT 0,
  `monthly_rent` decimal(10,2) NOT NULL,
  `security_deposit` decimal(10,2) DEFAULT 0.00,
  `maintenance_charges` decimal(10,2) DEFAULT 0.00,
  `notice_period_days` int(11) DEFAULT 30,
  `rating` decimal(3,2) DEFAULT 0.00,
  `total_reviews` int(11) DEFAULT 0,
  `verification_status` enum('pending','verified','rejected') DEFAULT 'pending',
  `status` enum('active','inactive','draft') DEFAULT 'draft',
  `featured` tinyint(1) DEFAULT 0,
  `is_recommended` tinyint(1) NOT NULL DEFAULT 0,
  `tag` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `updated_by` char(36) DEFAULT NULL,
  `deleted_by` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`id`, `organization_id`, `broker_id`, `city_id`, `area_id`, `property_type_id`, `name`, `slug`, `description`, `address`, `landmark`, `latitude`, `longitude`, `geohash`, `gender_preference`, `total_beds`, `available_beds`, `monthly_rent`, `security_deposit`, `maintenance_charges`, `notice_period_days`, `rating`, `total_reviews`, `verification_status`, `status`, `featured`, `is_recommended`, `tag`, `is_active`, `version`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`) VALUES
('0c2f5188-2234-4212-9683-17f2f2431238', NULL, '5cb05e6c-56b8-4a6f-844b-c8437ecea822', '064a7ec5-0f8e-47f1-984f-6349af165a60', 'b4cba635-508b-491c-9359-062184da0f31', 'c9a76400-8dab-11f1-a4cf-1062e5a5cd6c', 'PG near me', 'pg-near-me', 'Provide a clear description of your property, amenities, nearby hotspots, and facilities.Provide a clear description of your property, amenities, nearby hotspots, and facilities.', 'Flat 402, B-Block, Tulip Heights, Sector 62, Near Electronic City Metro, Noida, 201309', 'Near Electronic City Metro', 28.6280000, 77.3649000, NULL, 'boys', 20, 6, 32222.00, 32222.00, 0.00, 30, 0.00, 0, 'verified', 'active', 0, 0, 'Popular', 1, 1, '2026-08-18 21:56:04', '2026-08-22 05:08:07', '2026-08-22 05:08:07', '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', NULL, NULL),
('11a5732c-6a26-4a58-bc43-aeab0f95a8cc', NULL, '2d28d9da-2144-487d-997e-68b5624706f1', '88614695-bf11-4eae-b17e-93e87271e69c', '37359372-23e4-4c6b-9ece-3c15cd9848fb', 'c9a76400-8dab-11f1-a4cf-1062e5a5cd6c', 'Updated Sunrise Premium Residency (Updated)', 'updated-sunrise-premium-residency-updated', 'Luxury PG stay with all modern amenities.', 'Plot 45, Sector 62', 'Near Metro Station', 28.6245000, 77.3635000, NULL, 'boys', 30, 12, 8500.00, 20000.00, 0.00, 30, 0.00, 0, 'verified', 'active', 0, 0, 'Popular', 1, 1, '2026-08-16 09:59:13', '2026-08-22 05:07:39', '2026-08-22 05:07:39', '5cb05e6c-56b8-4a6f-844b-c8437ecea822', NULL, NULL),
('175827b9-c7af-4c56-90fe-f0eb3898a6cf', NULL, '5cb05e6c-56b8-4a6f-844b-c8437ecea822', '88614695-bf11-4eae-b17e-93e87271e69c', '37359372-23e4-4c6b-9ece-3c15cd9848fb', 'c9a76400-8dab-11f1-a4cf-1062e5a5cd6c', 'Elite GPS Residency PG', 'elite-gps-residency-pg', NULL, 'Plot 12, Sector 62, Near Metro Station', NULL, 28.6280000, 77.3649000, NULL, 'co-ed', 25, 8, 8500.00, 8500.00, 0.00, 30, 0.00, 0, 'verified', 'active', 0, 0, 'Verified', 1, 1, '2026-08-16 09:07:32', '2026-08-22 05:07:46', '2026-08-22 05:07:46', '5cb05e6c-56b8-4a6f-844b-c8437ecea822', NULL, NULL),
('2182587c-9095-41b4-a9d2-62eb7248df62', NULL, '63576b41-af34-452b-b4a5-2b603bda3fe6', '064a7ec5-0f8e-47f1-984f-6349af165a60', 'b4cba635-508b-491c-9359-062184da0f31', 'c9a76400-8dab-11f1-a4cf-1062e5a5cd6c', 'new listying', 'new-listying-b5d385', 'Property Description *\nMin 20 characters\nProvide a clear description of your property, amenities, nearby hotspots, and facilities.', 'Flat 402, 4th Floor, Tulip Heights, Sector 62, Electronic City Hub, Near Metro Gate No. 2, 201309', 'Near Electronic City Metro', 28.6280000, 77.3649000, NULL, 'boys', 20, 6, 10000.00, 43.00, 1242.00, 30, 0.00, 0, 'verified', 'active', 0, 0, NULL, 1, 1, '2026-08-22 08:52:06', '2026-08-22 08:55:55', NULL, '63576b41-af34-452b-b4a5-2b603bda3fe6', NULL, NULL),
('2cc20606-2431-4780-9ad7-aa2303773efe', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '00723629-e6bc-46f2-9378-60e9f91aa1db', '8d7012df-e925-4df4-935f-38b6cb50e84c', '1585645b-023f-4377-9372-22ec1f4e903d', 'Test Dynamic PG Horizon', 'test-dynamic-pg-horizon-bcb5d6d4', 'Premium PG accommodation with modern amenities in prime location.', 'Plot 10, Sector 18, Near Center Stage Mall', NULL, NULL, NULL, NULL, NULL, 16, 4, 9000.00, 18000.00, 0.00, 30, 4.80, 0, 'verified', 'active', 0, 0, NULL, 1, 1, '2026-08-17 21:34:12', '2026-08-22 12:12:48', '2026-08-17 21:34:12', NULL, NULL, NULL),
('2ce06724-f6aa-4b6d-b12d-df72a3492eb9', NULL, 'a0ea13d9-49a7-4e08-b364-7f0f00b97495', 'c9aef033-8dab-11f1-a4cf-1062e5a5cd6c', 'c9afff0f-8dab-11f1-a4cf-1062e5a5cd6c', 'c9a76400-8dab-11f1-a4cf-1062e5a5cd6c', 'Royal Comfort PG', 'royal-comfort-pg', NULL, '108, 12th Main, Indiranagar', NULL, 12.9763000, 77.6373000, NULL, 'boys', 12, 3, 8500.00, 8500.00, 0.00, 30, 0.00, 0, 'verified', 'active', 1, 1, 'Guest Favourite', 1, 1, '2026-08-16 08:59:52', '2026-08-22 08:29:06', NULL, 'a0ea13d9-49a7-4e08-b364-7f0f00b97495', NULL, NULL),
('3527dd94-3289-48ef-b7e1-a0c8c347ca05', NULL, '5cb05e6c-56b8-4a6f-844b-c8437ecea822', '064a7ec5-0f8e-47f1-984f-6349af165a60', '2af87933-ab7e-4996-9559-a62c3f1ad12c', '1585645b-023f-4377-9372-22ec1f4e903d', 'Cyber City Premium Office Space Test', 'cyber-city-premium-office-space-test-17ddb5', 'Modern air-conditioned commercial office space with high speed internet and security.', 'Plot 4, Sector 62, Noida', NULL, NULL, NULL, NULL, NULL, 10, 10, 45000.00, 45000.00, 0.00, 30, 0.00, 0, 'pending', 'draft', 0, 0, NULL, 1, 1, '2026-08-22 06:49:58', '2026-08-22 06:49:58', '2026-08-22 06:49:58', '5cb05e6c-56b8-4a6f-844b-c8437ecea822', NULL, NULL),
('35f0faf4-d7c6-4709-87fd-d1f2d43758bb', NULL, '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', '0744f27a-ed42-4417-9622-2b1ae7be547d', 'c3b4b1bc-1344-4daf-9bd3-f1de91498756', 'c9a76400-8dab-11f1-a4cf-1062e5a5cd6c', 'qdd33r3', 'qdd33r3', 'Provide a clear description of your property, amenities, nearby hotspots, and facilities.Provide a clear description of your property, amenities, nearby hotspots, and facilities.', 'लोधी एस्टेट, New Delhi', NULL, 28.5912000, 77.2240000, NULL, 'boys', 20, 6, 412294.00, 412294.00, 0.00, 30, 0.00, 0, 'rejected', 'inactive', 0, 0, 'Guest Favourite', 0, 1, '2026-08-16 21:17:43', '2026-08-18 14:12:40', '2026-08-18 14:12:40', '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', NULL, NULL),
('360cc1c4-e477-436a-9c71-28986f38e820', NULL, '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', '0744f27a-ed42-4417-9622-2b1ae7be547d', '0152e447-1d6b-44c0-b748-e731120b43ef', 'c9a76400-8dab-11f1-a4cf-1062e5a5cd6c', 'PROPERTY NEW IN DELHOI', 'property-new-in-delhoi-af6954', 'Provide a clear description of your property, amenities, nearby hotspots, and facilities.', 'Delhi', 'Near Electronic City Metro', 28.6390000, 77.2360000, NULL, 'co-ed', 20, 6, 7890.00, 100.00, 0.00, 0, 3.00, 1, 'verified', 'active', 0, 0, NULL, 1, 1, '2026-08-22 02:28:15', '2026-08-22 07:47:09', NULL, '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', NULL, NULL),
('4ab9cbde-a774-4cb1-929e-7c116716030a', NULL, '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', '0744f27a-ed42-4417-9622-2b1ae7be547d', 'ffbf3945-8a9f-4b9b-bb4a-3e7fc9629f79', 'c9a76400-8dab-11f1-a4cf-1062e5a5cd6c', 'saefe 3r3', 'saefe-3r3', 'Provide a clear description of your property, amenities, nearby hotspots, and facilities.Provide a clear description of your property, amenities, nearby hotspots, and facilities.', 'Lodhi Estate, New Delhi', 'wef', 28.5912000, 77.2240000, NULL, 'co-ed', 20, 6, 2421412.00, 2421412.00, 0.00, 30, 0.00, 0, 'rejected', 'inactive', 0, 0, 'Top rated', 0, 1, '2026-08-16 10:41:57', '2026-08-18 14:12:40', '2026-08-18 14:12:40', '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', NULL, NULL),
('4ef670b3-de31-415f-b061-deb50a89877d', NULL, '5cb05e6c-56b8-4a6f-844b-c8437ecea822', '0744f27a-ed42-4417-9622-2b1ae7be547d', 'f9ac6e38-eeee-43d1-a5f1-2e5a5bee1533', 'c9a76400-8dab-11f1-a4cf-1062e5a5cd6c', 'Shree Krishna Girls Executive Stay', 'shree-krishna-girls-executive-stay-924919', 'Safe and hygienic girls PG near metro station with daily hygienic food and 24/7 security.', 'Laxmi Nagar, Delhi', NULL, NULL, NULL, NULL, 'girls', 10, 10, 7500.00, 7500.00, 0.00, 30, 0.00, 0, 'pending', 'draft', 0, 0, NULL, 1, 1, '2026-08-22 06:46:53', '2026-08-22 07:47:09', NULL, '5cb05e6c-56b8-4a6f-844b-c8437ecea822', NULL, NULL),
('51c35024-b974-4e62-8a4f-06f7a3282321', NULL, '5cb05e6c-56b8-4a6f-844b-c8437ecea822', '01780c3b-0a91-4f49-9248-7472457ef24b', '7768b62e-1d35-4ba3-bb7f-a6007e43750b', '8a7008fd-7265-48d3-9cfa-a422cc1cd61a', 'Green Valley 2BHK Luxury Apartment', 'green-valley-2bhk-luxury-apartment-d491b3', 'Spacious 2BHK flat with modular kitchen, open balcony, power backup and parking.', 'Koramangala 4th Block, Bangalore', NULL, NULL, NULL, NULL, 'all', 10, 10, 28000.00, 28000.00, 0.00, 30, 0.00, 0, 'pending', 'draft', 0, 0, NULL, 1, 1, '2026-08-22 06:48:05', '2026-08-22 06:48:05', '2026-08-22 06:48:05', '5cb05e6c-56b8-4a6f-844b-c8437ecea822', NULL, NULL),
('5c0e35ff-2063-4602-9cf6-ca0bbc036e8e', NULL, '63576b41-af34-452b-b4a5-2b603bda3fe6', '88614695-bf11-4eae-b17e-93e87271e69c', '9d92671b-df73-4f5b-bf64-ffee9a0d8302', '1585645b-023f-4377-9372-22ec1f4e903d', 'test user', 'test-user', 'Premium PG accommodation with modern amenities in prime location.', 'Dadri, Noida', 'awda', 28.6225797, 77.3778238, NULL, NULL, 44, 4, 5690.00, 424.00, 0.00, 30, 5.00, 1, 'verified', 'active', 0, 0, 'Popular', 1, 1, '2026-08-17 21:28:32', '2026-08-22 12:12:48', '2026-08-22 05:08:02', NULL, NULL, NULL),
('5de5b687-9316-459a-b3ed-481bf1b8a03f', NULL, '5cb05e6c-56b8-4a6f-844b-c8437ecea822', '064a7ec5-0f8e-47f1-984f-6349af165a60', '2af87933-ab7e-4996-9559-a62c3f1ad12c', '1585645b-023f-4377-9372-22ec1f4e903d', 'Cyber City Premium Office Space', 'cyber-city-premium-office-space-5910f2', 'Modern air-conditioned commercial office space with high speed internet and security.', 'Plot 4, Sector 62, Noida', NULL, NULL, NULL, NULL, NULL, 10, 10, 45000.00, 45000.00, 0.00, 30, 0.00, 0, 'pending', 'draft', 0, 0, NULL, 1, 1, '2026-08-22 06:46:53', '2026-08-22 07:47:09', NULL, '5cb05e6c-56b8-4a6f-844b-c8437ecea822', NULL, NULL),
('69b12b99-bcf5-4cca-bdf6-c13887397c7f', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '00723629-e6bc-46f2-9378-60e9f91aa1db', '8d7012df-e925-4df4-935f-38b6cb50e84c', '1585645b-023f-4377-9372-22ec1f4e903d', 'Test Dynamic PG Horizon', 'test-dynamic-pg-horizon-f97d17d5', 'Premium PG accommodation with modern amenities in prime location.', 'Plot 10, Sector 18, Near Center Stage Mall', NULL, NULL, NULL, NULL, NULL, 16, 4, 9000.00, 18000.00, 0.00, 30, 4.80, 0, 'verified', 'active', 0, 0, NULL, 1, 1, '2026-08-17 21:39:24', '2026-08-22 12:12:48', '2026-08-17 21:39:24', NULL, NULL, NULL),
('69b8669e-a7c6-44be-98b5-491ac4657915', NULL, '5cb05e6c-56b8-4a6f-844b-c8437ecea822', '064a7ec5-0f8e-47f1-984f-6349af165a60', 'b4cba635-508b-491c-9359-062184da0f31', '1585645b-023f-4377-9372-22ec1f4e903d', 'shop', 'shop-275371', 'shop  Room Sharing & Pricing', 'Flat 402, B-Block, Tulip Heights, Sector 62, Near Electronic City Metro, Noida, 201309', 'Near Electronic City Metro', 28.6280000, 77.3649000, NULL, NULL, 20, 6, 3535.00, 55.00, 55.00, 15, 0.00, 0, 'verified', 'active', 1, 1, NULL, 1, 1, '2026-08-22 04:46:31', '2026-08-22 07:47:09', NULL, 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', NULL, NULL),
('6ca9f328-86ac-40d1-9d98-4a9c3823986f', NULL, '5cb05e6c-56b8-4a6f-844b-c8437ecea822', '88614695-bf11-4eae-b17e-93e87271e69c', '37359372-23e4-4c6b-9ece-3c15cd9848fb', 'c9a76400-8dab-11f1-a4cf-1062e5a5cd6c', 'Grand Stay Luxury PG with Master Bedroom', 'grand-stay-luxury-pg-with-master-bedroom', 'Spacious couple friendly PG with master bedroom, attached washroom, high-speed WiFi, and hygienic meals.', 'Plot 25, Sector 62, Near Metro Station', NULL, 28.6245000, 77.3579000, NULL, 'co-ed', 10, 10, 8500.00, 8500.00, 0.00, 30, 0.00, 0, 'verified', 'active', 0, 0, 'New', 1, 1, '2026-08-16 10:09:36', '2026-08-22 07:47:09', NULL, '5cb05e6c-56b8-4a6f-844b-c8437ecea822', NULL, NULL),
('7c77e036-c1c7-4c5a-b688-122ff0f93098', NULL, '5cb05e6c-56b8-4a6f-844b-c8437ecea822', '01780c3b-0a91-4f49-9248-7472457ef24b', '7768b62e-1d35-4ba3-bb7f-a6007e43750b', '8a7008fd-7265-48d3-9cfa-a422cc1cd61a', 'Green Valley 2BHK Luxury Apartment Test', 'green-valley-2bhk-luxury-apartment-test-56e3ea', 'Spacious 2BHK flat with modular kitchen, open balcony, power backup and parking.', 'Koramangala 4th Block, Bangalore', NULL, NULL, NULL, NULL, 'all', 10, 10, 28000.00, 28000.00, 0.00, 30, 0.00, 0, 'pending', 'draft', 0, 0, NULL, 1, 1, '2026-08-22 06:49:58', '2026-08-22 06:49:58', '2026-08-22 06:49:58', '5cb05e6c-56b8-4a6f-844b-c8437ecea822', NULL, NULL),
('7d787a88-1b74-481f-9061-8867f1babf60', NULL, '5cb05e6c-56b8-4a6f-844b-c8437ecea822', '01780c3b-0a91-4f49-9248-7472457ef24b', '7768b62e-1d35-4ba3-bb7f-a6007e43750b', '8a7008fd-7265-48d3-9cfa-a422cc1cd61a', 'Green Valley 2BHK Luxury Apartment', 'green-valley-2bhk-luxury-apartment-ad94fa', 'Spacious 2BHK flat with modular kitchen, open balcony, power backup and parking.', 'Koramangala 4th Block, Bangalore', NULL, NULL, NULL, NULL, 'all', 10, 10, 28000.00, 28000.00, 0.00, 30, 0.00, 0, 'pending', 'draft', 0, 0, NULL, 1, 1, '2026-08-22 06:46:53', '2026-08-22 07:47:09', NULL, '5cb05e6c-56b8-4a6f-844b-c8437ecea822', NULL, NULL),
('807319ca-3418-4c12-b110-b66e00e7ab92', NULL, '5cb05e6c-56b8-4a6f-844b-c8437ecea822', '88614695-bf11-4eae-b17e-93e87271e69c', '37359372-23e4-4c6b-9ece-3c15cd9848fb', 'c9a76400-8dab-11f1-a4cf-1062e5a5cd6c', 'Elite GPS Residency PG', 'elite-gps-residency-pg-2', NULL, 'Plot 12, Sector 62, Near Metro Station', NULL, 28.6280000, 77.3649000, NULL, 'co-ed', 25, 8, 8500.00, 8500.00, 0.00, 30, 0.00, 0, 'verified', 'active', 0, 0, 'Popular', 1, 1, '2026-08-16 09:07:06', '2026-08-22 07:47:09', NULL, '5cb05e6c-56b8-4a6f-844b-c8437ecea822', NULL, NULL),
('882021ce-cdef-4461-b250-39ff6b882c13', NULL, '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', '0744f27a-ed42-4417-9622-2b1ae7be547d', 'ffbf3945-8a9f-4b9b-bb4a-3e7fc9629f79', 'c9a76400-8dab-11f1-a4cf-1062e5a5cd6c', 'adult', 'adult-91625e', 'adultfqwf dvdv adult fff sex dvevgrv rt4', 'Lodhi Estate, New Delhi', 'qwqw', 28.5912000, 77.2240000, NULL, 'boys', 20, 6, 699.00, 2.00, 0.00, 0, 0.00, 0, 'pending', 'draft', 0, 0, NULL, 1, 1, '2026-08-16 10:13:20', '2026-08-18 10:48:14', '2026-08-18 10:48:14', '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', NULL, NULL),
('8b77164a-57ed-4a7b-8443-fafd3daa06db', NULL, '5cb05e6c-56b8-4a6f-844b-c8437ecea822', '064a7ec5-0f8e-47f1-984f-6349af165a60', 'b4cba635-508b-491c-9359-062184da0f31', '1585645b-023f-4377-9372-22ec1f4e903d', 'shp one by one added', 'shp-one-by-one-added-432acd', 'Provide a clear description of your property, amenities, nearby hotspots, and facilities.Provide a clear description of your property, amenities, nearby hotspots, and facilities.', 'Flat 402, B-Block, Tulip Heights, Sector 62, Near Electronic City Metro, Noida, 201309', 'Near Electronic City Metro', 28.6280000, 77.3649000, NULL, NULL, 20, 6, 9000.00, 78.00, 555.00, 30, 0.00, 0, 'verified', 'active', 0, 0, NULL, 1, 1, '2026-08-22 06:45:03', '2026-08-22 07:47:09', NULL, 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', NULL, NULL),
('93220e71-23ec-43df-8577-272c5c873711', NULL, '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', '88614695-bf11-4eae-b17e-93e87271e69c', '37359372-23e4-4c6b-9ece-3c15cd9848fb', 'c9a76400-8dab-11f1-a4cf-1062e5a5cd6c', 'qwd 3r23', 'qwd-3r23', 'Provide a clear description of your property, amenities, nearby hotspots, and facilitie', 'Sector 62, Noida', NULL, 28.6280000, 77.3649000, NULL, 'co-ed', 20, 6, 22412.00, 22412.00, 0.00, 30, 0.00, 0, 'rejected', 'inactive', 0, 0, 'Verified', 0, 1, '2026-08-16 10:43:55', '2026-08-18 14:12:40', '2026-08-18 14:12:40', '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', NULL, NULL),
('94a9038d-3e4f-4153-aa79-eb83d2542f6a', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '01780c3b-0a91-4f49-9248-7472457ef24b', 'bde1f3a1-2d01-4d43-8f43-cf6c41387374', 'c9a76614-8dab-11f1-a4cf-1062e5a5cd6c', 'Urban Nest Co-Living Spaces', 'urban-nest-co-living-spaces', 'Modern amenities, high-speed WiFi, 3 meals daily, and 24/7 security.', '27th Main, Sector 1, HSR Layout, Bangalore', 'Near Agara Lake', 12.9840000, 77.6443000, NULL, 'co-ed', 40, 10, 14500.00, 29000.00, 0.00, 30, 4.90, 4, 'verified', 'active', 0, 0, 'Verified', 1, 1, '2026-08-16 22:48:54', '2026-08-22 07:47:09', NULL, NULL, NULL, NULL),
('9f495350-cc5c-4e44-a254-4fa36b179ac6', NULL, '5cb05e6c-56b8-4a6f-844b-c8437ecea822', '064a7ec5-0f8e-47f1-984f-6349af165a60', '2af87933-ab7e-4996-9559-a62c3f1ad12c', '1585645b-023f-4377-9372-22ec1f4e903d', 'Cyber City Premium Office Space', 'cyber-city-premium-office-space-253d37', 'Modern air-conditioned commercial office space with high speed internet and security.', 'Plot 4, Sector 62, Noida', NULL, NULL, NULL, NULL, NULL, 10, 10, 45000.00, 45000.00, 0.00, 30, 0.00, 0, 'pending', 'draft', 0, 0, NULL, 1, 1, '2026-08-22 06:48:05', '2026-08-22 06:48:05', '2026-08-22 06:48:05', '5cb05e6c-56b8-4a6f-844b-c8437ecea822', NULL, NULL),
('a0050c80-7752-43b1-9ff2-8b86044fe7fd', NULL, '5cb05e6c-56b8-4a6f-844b-c8437ecea822', '0744f27a-ed42-4417-9622-2b1ae7be547d', 'f9ac6e38-eeee-43d1-a5f1-2e5a5bee1533', 'c9a76400-8dab-11f1-a4cf-1062e5a5cd6c', 'Shree Krishna Girls Executive Stay', 'shree-krishna-girls-executive-stay-3aa2f5', 'Safe and hygienic girls PG near metro station with daily hygienic food and 24/7 security.', 'Laxmi Nagar, Delhi', NULL, NULL, NULL, NULL, 'girls', 10, 10, 7500.00, 7500.00, 0.00, 30, 0.00, 0, 'pending', 'draft', 0, 0, NULL, 1, 1, '2026-08-22 06:48:05', '2026-08-22 06:48:05', '2026-08-22 06:48:05', '5cb05e6c-56b8-4a6f-844b-c8437ecea822', NULL, NULL),
('bd1e05f8-4124-4df5-81cd-79c794ba6432', NULL, '5cb05e6c-56b8-4a6f-844b-c8437ecea822', '064a7ec5-0f8e-47f1-984f-6349af165a60', 'b4cba635-508b-491c-9359-062184da0f31', '8a7008fd-7265-48d3-9cfa-a422cc1cd61a', 'mera bazr', 'mera-bazr-172e14', 'Recommended for YouRecommended for YouRecommended for YouRecommended for You', 'Flat 402, B-Block, Tulip Heights, Sector 62, Near Electronic City Metro, Noida, 201309', 'Near Electronic City Metro', 28.6280000, 77.3649000, NULL, 'all', 20, 5, 999983.00, 35353.00, 6436.00, 30, 0.00, 0, 'verified', 'active', 1, 1, NULL, 1, 1, '2026-08-22 06:55:03', '2026-08-22 08:49:01', NULL, 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', NULL, NULL),
('bea2204f-a5cc-4931-a06a-0559b191d791', NULL, '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', '064a7ec5-0f8e-47f1-984f-6349af165a60', 'b4cba635-508b-491c-9359-062184da0f31', '8a7008fd-7265-48d3-9cfa-a422cc1cd61a', 'falt one', 'falt-one-53fd1f', 'flat a one quelity he bhai', 'Flat 402, B-Block, Tulip Heights, Sector 62, Near Electronic City Metro, Noida, 201309', 'Near Electronic City Metro', 28.6280000, 77.3649000, NULL, 'co-ed', 20, 6, 242424.00, 44.00, 4444.00, 30, 0.00, 0, 'verified', 'active', 0, 0, NULL, 1, 1, '2026-08-22 06:43:45', '2026-08-22 07:56:10', '2026-08-22 07:56:10', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', NULL, NULL),
('c95791bd-c085-4e63-8075-5207d5fa7cbf', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '064a7ec5-0f8e-47f1-984f-6349af165a60', 'b4cba635-508b-491c-9359-062184da0f31', 'c9a76400-8dab-11f1-a4cf-1062e5a5cd6c', 'Sunrise Premium Boys PG', 'sunrise-premium-boys-pg', 'Modern amenities, high-speed WiFi, 3 meals daily, and 24/7 security.', 'Plot 45, Sector 62, Electronic City, Noida', 'Near Metro Station', 28.6294000, 77.3698000, NULL, 'boys', 24, 6, 8500.00, 17000.00, 0.00, 30, 4.80, 2, 'verified', 'active', 0, 0, NULL, 1, 1, '2026-08-16 22:47:14', '2026-08-22 07:47:09', NULL, NULL, NULL, NULL),
('c9b8b7b3-8dab-11f1-a4cf-1062e5a5cd6c', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', 'c9aef033-8dab-11f1-a4cf-1062e5a5cd6c', 'c9afff0f-8dab-11f1-a4cf-1062e5a5cd6c', 'c9a76400-8dab-11f1-a4cf-1062e5a5cd6c', 'Sunrise Premium PG', 'sunrise-premium-pg', NULL, '123, 5th Cross, Indiranagar', NULL, 12.9763000, 77.6471000, NULL, 'boys', 20, 5, 8500.00, 0.00, 0.00, 30, 0.00, 0, 'verified', 'active', 0, 0, NULL, 1, 1, '2026-08-01 13:20:38', '2026-08-22 05:07:51', '2026-08-22 05:07:51', NULL, NULL, NULL),
('d6779d7e-b9a0-45ac-ba92-3ced3ead699c', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '0744f27a-ed42-4417-9622-2b1ae7be547d', '6fe3e645-8648-4727-ad7a-ff88d43729d8', 'c9a76400-8dab-11f1-a4cf-1062e5a5cd6c', 'Royal Heritage Executive PG', 'royal-heritage-executive-pg', 'Modern amenities, high-speed WiFi, 3 meals daily, and 24/7 security.', 'Block C, South Extension Part II, New Delhi', 'Near AIIMS Metro', 28.5742000, 77.2242000, NULL, 'boys', 20, 3, 9500.00, 19000.00, 0.00, 30, 4.80, 2, 'verified', 'active', 0, 0, 'Top rated', 1, 1, '2026-08-16 22:48:54', '2026-08-22 07:47:09', NULL, NULL, NULL, NULL),
('d9f5e991-207a-4023-b6fe-9deb3aacfc33', NULL, '5cb05e6c-56b8-4a6f-844b-c8437ecea822', '88614695-bf11-4eae-b17e-93e87271e69c', '37359372-23e4-4c6b-9ece-3c15cd9848fb', 'c9a76400-8dab-11f1-a4cf-1062e5a5cd6c', 'Grand Stay Luxury PG with Master Bedroom', 'grand-stay-luxury-pg-with-master-bedroom-2', 'Spacious couple friendly PG with master bedroom, attached washroom, high-speed WiFi, and hygienic meals.', 'Plot 25, Sector 62, Near Metro Station', NULL, NULL, NULL, NULL, 'co-ed', 10, 10, 8500.00, 8500.00, 0.00, 30, 0.00, 0, 'rejected', 'inactive', 0, 0, NULL, 0, 1, '2026-08-16 10:10:08', '2026-08-18 14:12:40', '2026-08-18 14:12:40', '5cb05e6c-56b8-4a6f-844b-c8437ecea822', NULL, NULL),
('da080740-0c28-40e2-a691-332a70e0f27f', NULL, '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', '0744f27a-ed42-4417-9622-2b1ae7be547d', 'ffbf3945-8a9f-4b9b-bb4a-3e7fc9629f79', 'c9a76400-8dab-11f1-a4cf-1062e5a5cd6c', 'test', 'test-c6faa3', 'test datat', 'Lodhi Estate, New Delhi', 'near bf', 28.5912000, 77.2240000, NULL, 'boys', 20, 6, 5000.00, 500.00, 0.00, 0, 0.00, 0, 'pending', 'draft', 0, 0, NULL, 1, 1, '2026-08-16 09:54:59', '2026-08-16 22:25:04', '2026-08-16 22:25:04', '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', NULL, NULL),
('dc5b0828-7cdc-4cfc-8f13-3c75bd25b248', NULL, '6a74294b-2c66-4f6b-b4de-bee2e365fa6b', '88614695-bf11-4eae-b17e-93e87271e69c', '37359372-23e4-4c6b-9ece-3c15cd9848fb', 'c9a76400-8dab-11f1-a4cf-1062e5a5cd6c', 'Base64 Photo PG Residency', 'base64-photo-pg-residency-8fcfa4', NULL, 'Plot 11, Sector 62, Electronic City', NULL, NULL, NULL, NULL, 'co-ed', 10, 10, 8500.00, 8500.00, 0.00, 30, 0.00, 0, 'pending', 'draft', 0, 0, NULL, 1, 1, '2026-08-16 09:59:14', '2026-08-16 22:26:21', '2026-08-16 22:26:21', '6a74294b-2c66-4f6b-b4de-bee2e365fa6b', NULL, NULL),
('e4614af2-142d-4c05-bc61-0c1f275d37c3', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '01780c3b-0a91-4f49-9248-7472457ef24b', 'bde1f3a1-2d01-4d43-8f43-cf6c41387374', 'c9a76400-8dab-11f1-a4cf-1062e5a5cd6c', 'Aura Luxury Women Stay', 'aura-luxury-women-stay', 'Modern amenities, high-speed WiFi, 3 meals daily, and 24/7 security.', '100ft Road, Indiranagar, Bangalore', 'Behind Toit Pub', 12.9833000, 77.6443000, NULL, 'girls', 30, 8, 11000.00, 22000.00, 0.00, 30, 4.80, 2, 'verified', 'active', 0, 0, 'Top rated', 1, 1, '2026-08-16 22:48:54', '2026-08-22 07:47:09', NULL, NULL, NULL, NULL),
('e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', NULL, '035e8b11-9be9-402a-a986-f95e4aea9fe9', '064a7ec5-0f8e-47f1-984f-6349af165a60', 'b4cba635-508b-491c-9359-062184da0f31', 'c9a76400-8dab-11f1-a4cf-1062e5a5cd6c', 'my property square', 'my-property-square-d9e7d8', 'Property Description *\nMin 20 characters\nProvide a clear description of your property, amenities, nearby hotspots, and facilities.', 'Flat 402, 4th Floor, Tulip Heights, Sector 62, Electronic City Hub, Near Metro Gate No. 2, 201309', 'Near Electronic City Metro', 28.6280000, 77.3649000, NULL, 'boys', 20, 6, 1000000.00, 2000000.00, 63463.00, 15, 0.00, 0, 'verified', 'active', 0, 0, 'Trending', 1, 1, '2026-08-22 04:01:43', '2026-08-22 07:57:14', '2026-08-22 07:57:14', '035e8b11-9be9-402a-a986-f95e4aea9fe9', NULL, NULL),
('e8564421-0b10-4590-9a4a-8b5281b936e2', NULL, '5cb05e6c-56b8-4a6f-844b-c8437ecea822', '0744f27a-ed42-4417-9622-2b1ae7be547d', 'f9ac6e38-eeee-43d1-a5f1-2e5a5bee1533', 'c9a76400-8dab-11f1-a4cf-1062e5a5cd6c', 'Shree Krishna Girls Executive Stay Test', 'shree-krishna-girls-executive-stay-test-d75cf1', 'Safe and hygienic girls PG near metro station with daily hygienic food and 24/7 security.', 'Laxmi Nagar, Delhi', NULL, NULL, NULL, NULL, 'girls', 10, 10, 7500.00, 7500.00, 0.00, 30, 0.00, 0, 'pending', 'draft', 0, 0, NULL, 1, 1, '2026-08-22 06:49:58', '2026-08-22 06:49:58', '2026-08-22 06:49:58', '5cb05e6c-56b8-4a6f-844b-c8437ecea822', NULL, NULL),
('eb578e4e-8c64-4cd5-865c-88501563d123', NULL, 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', '00723629-e6bc-46f2-9378-60e9f91aa1db', '8d7012df-e925-4df4-935f-38b6cb50e84c', '1585645b-023f-4377-9372-22ec1f4e903d', 'Test Dynamic PG Horizon', 'test-dynamic-pg-horizon-f48e3b3b', 'Premium PG accommodation with modern amenities in prime location.', 'Plot 10, Sector 18, Near Center Stage Mall', NULL, NULL, NULL, NULL, NULL, 16, 4, 9000.00, 18000.00, 0.00, 30, 4.80, 0, 'verified', 'active', 0, 0, NULL, 1, 1, '2026-08-17 21:27:25', '2026-08-22 12:12:48', '2026-08-17 21:27:25', NULL, NULL, NULL),
('ed514de7-d9e2-45fc-876c-c192a5d6fd01', NULL, '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', '0744f27a-ed42-4417-9622-2b1ae7be547d', 'c3b4b1bc-1344-4daf-9bd3-f1de91498756', 'c9a76400-8dab-11f1-a4cf-1062e5a5cd6c', 'rv 3r3r4', 'rv-3r3r4', 'Provide a clear description of your property, amenities, nearby hotspots, and facilities.Provide a clear description of your property, amenities, nearby hotspots, and facilities.Provide a clear description of your property, amenities, nearby hotspots, and facilities.', 'लोधी एस्टेट, New Delhi', 'we', 28.5912000, 77.2240000, NULL, 'boys', 20, 6, 232123.00, 232123.00, 0.00, 30, 0.00, 0, 'rejected', 'inactive', 0, 0, 'New', 0, 1, '2026-08-16 21:23:15', '2026-08-18 14:12:40', '2026-08-18 14:12:40', '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `property_amenities`
--

CREATE TABLE `property_amenities` (
  `id` char(36) NOT NULL,
  `property_id` char(36) NOT NULL,
  `amenity_id` char(36) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `property_amenities`
--

INSERT INTO `property_amenities` (`id`, `property_id`, `amenity_id`, `created_at`) VALUES
('00b92cbb-695e-4ece-b97e-f7eff78e0ebd', '8b77164a-57ed-4a7b-8443-fafd3daa06db', 'eea2dfbf-711c-4ecf-ab42-4a249d4d16a6', '2026-08-22 06:45:03'),
('01e68fa1-0ad3-4f26-b24d-eac8135bee19', '2182587c-9095-41b4-a9d2-62eb7248df62', 'b704f699-fe2d-4084-9f1b-c237b2915d8c', '2026-08-22 08:52:06'),
('0402b9bb-8a59-4699-8213-adeab388c829', '175827b9-c7af-4c56-90fe-f0eb3898a6cf', 'c9ab4e32-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 09:07:32'),
('0410a023-08ef-4353-b845-1c085de23843', 'bea2204f-a5cc-4931-a06a-0559b191d791', 'b3791fe5-2680-450b-839a-41031e9af2e7', '2026-08-22 06:43:45'),
('05efcf29-a103-4af1-845a-59ae4217d862', '69b8669e-a7c6-44be-98b5-491ac4657915', 'a25bf534-ac51-40f5-a9ab-b84fc3cb82b7', '2026-08-22 05:00:28'),
('0745244f-1895-4588-b9fc-a60956402c2f', '8b77164a-57ed-4a7b-8443-fafd3daa06db', 'a25bf534-ac51-40f5-a9ab-b84fc3cb82b7', '2026-08-22 06:45:03'),
('0cdf14da-02ef-4aca-a9a6-e79f5ff0ff44', '8b77164a-57ed-4a7b-8443-fafd3daa06db', 'f8c7471a-b54b-48c7-b4db-ddd10834a020', '2026-08-22 06:45:03'),
('0d378d08-a79d-4e75-a3af-2fa0b4f433d0', 'bea2204f-a5cc-4931-a06a-0559b191d791', 'c9ab4e32-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 06:43:45'),
('0e6e0373-60bf-4e4c-92d0-41b41e57a25c', 'bea2204f-a5cc-4931-a06a-0559b191d791', 'ba3607da-8065-4b8b-8da9-6adf440ed6df', '2026-08-22 06:43:45'),
('116b8e6b-5f0b-47d1-8c8e-eab82e903482', 'bd1e05f8-4124-4df5-81cd-79c794ba6432', 'eea2dfbf-711c-4ecf-ab42-4a249d4d16a6', '2026-08-22 06:55:03'),
('128008ae-69ef-4792-9d7d-e9906d1a9b5d', '807319ca-3418-4c12-b110-b66e00e7ab92', 'c9ab4f37-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 09:07:06'),
('15e1dd6b-6956-4d85-8da8-3fdaebc3a736', 'bea2204f-a5cc-4931-a06a-0559b191d791', '426895b3-ff08-4b74-99cb-813ad1b93143', '2026-08-22 06:43:45'),
('17f6c736-5409-46d7-a107-740d41dc6fe6', '69b8669e-a7c6-44be-98b5-491ac4657915', 'b3791fe5-2680-450b-839a-41031e9af2e7', '2026-08-22 05:00:28'),
('2016e925-fd24-42b7-825a-01fd5f57a647', '2ce06724-f6aa-4b6d-b12d-df72a3492eb9', 'c9ab4f37-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 08:59:52'),
('220cddcd-d991-4ab0-88b4-7999849c4be9', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', 'a25bf534-ac51-40f5-a9ab-b84fc3cb82b7', '2026-08-22 04:59:20'),
('22127671-94c9-4395-8376-af22fb6b2883', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', 'f8c7471a-b54b-48c7-b4db-ddd10834a020', '2026-08-22 04:59:20'),
('246c0910-2c5d-45f1-a5c9-24260f195b9c', '175827b9-c7af-4c56-90fe-f0eb3898a6cf', 'c9ab4f37-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 09:07:32'),
('2475d28d-5cd7-4278-9064-2b090687e97a', 'ed514de7-d9e2-45fc-876c-c192a5d6fd01', 'c9ab4c4c-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 21:23:15'),
('2da926bb-b1f3-42a5-ba21-664c27538bfa', '360cc1c4-e477-436a-9c71-28986f38e820', 'c9ab4c4c-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 02:28:15'),
('2e070173-f31b-481b-b8d5-64886e915ba2', 'bd1e05f8-4124-4df5-81cd-79c794ba6432', 'c9ab4c4c-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 06:55:03'),
('2f638d1d-c8d4-4440-b425-7d63fe11af1d', '8b77164a-57ed-4a7b-8443-fafd3daa06db', 'b3791fe5-2680-450b-839a-41031e9af2e7', '2026-08-22 06:45:03'),
('302a9217-8adb-47d6-ab75-c56a27cda41b', 'bea2204f-a5cc-4931-a06a-0559b191d791', 'c9ab4c4c-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 06:43:45'),
('309ed0e2-39e5-49c5-9734-f6f5d3561d11', '69b8669e-a7c6-44be-98b5-491ac4657915', '4d50db16-a3d2-485f-99b9-310fb795b7e4', '2026-08-22 05:00:28'),
('30eb273c-bebb-4657-ba01-938c6f318a70', 'bea2204f-a5cc-4931-a06a-0559b191d791', 'b704f699-fe2d-4084-9f1b-c237b2915d8c', '2026-08-22 06:43:45'),
('3389aac2-b237-402a-aa92-276ca361fbaa', '8b77164a-57ed-4a7b-8443-fafd3daa06db', 'ba3607da-8065-4b8b-8da9-6adf440ed6df', '2026-08-22 06:45:03'),
('34c1a9c4-35bb-462e-9b40-3b83b89fe299', '2182587c-9095-41b4-a9d2-62eb7248df62', 'c9ab4f37-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 08:52:06'),
('3b005a7a-59a7-47f1-aa64-768c30dc8eb9', 'bd1e05f8-4124-4df5-81cd-79c794ba6432', 'b704f699-fe2d-4084-9f1b-c237b2915d8c', '2026-08-22 06:55:03'),
('3c4a6630-ba9a-4c98-b24f-0301ad2cc9d2', '8b77164a-57ed-4a7b-8443-fafd3daa06db', 'c9ab4c4c-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 06:45:03'),
('3d33bad9-f8ac-4368-9b4a-818f9b753bf2', '0c2f5188-2234-4212-9683-17f2f2431238', 'c9ab4e32-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-18 22:02:43'),
('3e83095e-3da8-4819-b07a-679af2870e2d', '2182587c-9095-41b4-a9d2-62eb7248df62', 'c9ab4e32-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 08:52:06'),
('3f040065-95c7-4514-b261-e571ca56672a', '4ab9cbde-a774-4cb1-929e-7c116716030a', 'c9ab4e32-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 10:41:57'),
('401b5256-f19b-4bd9-8ba3-4fcb55fe73aa', '69b8669e-a7c6-44be-98b5-491ac4657915', 'ba3607da-8065-4b8b-8da9-6adf440ed6df', '2026-08-22 05:00:28'),
('41710968-da2a-4507-95ce-e9d0ca04e2e7', '69b8669e-a7c6-44be-98b5-491ac4657915', 'c9ab4f37-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 05:00:28'),
('42582091-0b1c-42eb-a669-5f9b907f4612', '360cc1c4-e477-436a-9c71-28986f38e820', 'c9ab4e32-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 02:28:15'),
('43cc2d40-1a6c-420f-9595-4b90e6e9bfb6', '2ce06724-f6aa-4b6d-b12d-df72a3492eb9', 'c9ab4c4c-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 08:59:52'),
('463e3ab7-460e-419e-b0a7-f9f3853ebd54', '69b8669e-a7c6-44be-98b5-491ac4657915', 'eea2dfbf-711c-4ecf-ab42-4a249d4d16a6', '2026-08-22 05:00:28'),
('46e3b3b0-7f8b-4f5b-96c1-afde7f591be5', 'bd1e05f8-4124-4df5-81cd-79c794ba6432', 'b3791fe5-2680-450b-839a-41031e9af2e7', '2026-08-22 06:55:03'),
('4a72acbf-2df2-4feb-a9e1-3c04a1d1df70', '4ab9cbde-a774-4cb1-929e-7c116716030a', 'c9ab4f37-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 10:41:57'),
('501b8982-f381-44bf-b3d7-e77557148620', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', 'c9ab4e32-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 04:59:20'),
('5368bc8d-f24f-436c-81ab-2bf1923204d4', '882021ce-cdef-4461-b250-39ff6b882c13', 'c9ab4f37-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 10:13:20'),
('57510a53-7837-4eb0-bd64-591d4b2e627a', '807319ca-3418-4c12-b110-b66e00e7ab92', 'c9ab4c4c-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 09:07:06'),
('58b74aac-f85e-4aa5-a01f-d941b23677ff', 'bd1e05f8-4124-4df5-81cd-79c794ba6432', '426895b3-ff08-4b74-99cb-813ad1b93143', '2026-08-22 06:55:03'),
('5b7f6d03-e132-4d2a-8f92-c236bea9f658', '2182587c-9095-41b4-a9d2-62eb7248df62', 'f8c7471a-b54b-48c7-b4db-ddd10834a020', '2026-08-22 08:52:06'),
('5d599455-05a3-4ef3-b9a6-419557c8c06f', '2182587c-9095-41b4-a9d2-62eb7248df62', 'c9ab4c4c-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 08:52:06'),
('5f97eee8-53f1-4678-8012-371e8202ea77', '35f0faf4-d7c6-4709-87fd-d1f2d43758bb', 'c9ab4e32-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 21:17:43'),
('65c9bd05-012d-4a3d-b297-239803fc0dd4', '5c0e35ff-2063-4602-9cf6-ca0bbc036e8e', 'c9ab4c4c-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-18 10:37:24'),
('66b98c08-2c38-49e4-9191-b060e5519b50', '8b77164a-57ed-4a7b-8443-fafd3daa06db', '426895b3-ff08-4b74-99cb-813ad1b93143', '2026-08-22 06:45:03'),
('68b80303-8ea9-4631-8f65-abf765ad1edc', '69b8669e-a7c6-44be-98b5-491ac4657915', '426895b3-ff08-4b74-99cb-813ad1b93143', '2026-08-22 05:00:28'),
('6bcdbf84-86bd-4d3a-ac50-856fdb6fadd1', '882021ce-cdef-4461-b250-39ff6b882c13', 'c9ab4c4c-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 10:13:20'),
('6bf0f740-935a-4836-82e1-b5a6afa55b61', 'bd1e05f8-4124-4df5-81cd-79c794ba6432', 'c9ab4e32-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 06:55:03'),
('72de194a-e84d-4e99-be41-5e334b9c2564', '2182587c-9095-41b4-a9d2-62eb7248df62', 'a25bf534-ac51-40f5-a9ab-b84fc3cb82b7', '2026-08-22 08:52:06'),
('7a91303b-1fa1-4cdd-987a-9dd82b07f804', '2182587c-9095-41b4-a9d2-62eb7248df62', 'eea2dfbf-711c-4ecf-ab42-4a249d4d16a6', '2026-08-22 08:52:06'),
('7e7e069e-e1d6-475e-8f09-dbc7602eea10', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', 'b3791fe5-2680-450b-839a-41031e9af2e7', '2026-08-22 04:59:20'),
('87185088-7b4d-4807-a5dc-500b04d7e895', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '4d50db16-a3d2-485f-99b9-310fb795b7e4', '2026-08-22 04:59:20'),
('89799848-7e46-4437-842b-127dd6caef20', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '426895b3-ff08-4b74-99cb-813ad1b93143', '2026-08-22 04:59:20'),
('8aa2d236-c491-41a4-b981-c45cef8ed45f', '11a5732c-6a26-4a58-bc43-aeab0f95a8cc', 'c9ab4f37-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-17 21:41:52'),
('8ff63c09-66d8-4480-b239-a8258ac88e60', '2ce06724-f6aa-4b6d-b12d-df72a3492eb9', 'c9ab4e32-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 08:59:52'),
('95f365e9-f096-44ef-908f-8c0887a094b0', '4ab9cbde-a774-4cb1-929e-7c116716030a', 'c9ab4c4c-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 10:41:57'),
('99d14f5f-caf1-4225-8297-96afa34af758', 'da080740-0c28-40e2-a691-332a70e0f27f', 'c9ab4c4c-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 09:54:59'),
('9accebc8-48ec-40e3-af1a-384145c8e9a3', '69b8669e-a7c6-44be-98b5-491ac4657915', 'c9ab4e32-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 05:00:28'),
('9be721fa-a9a3-4342-a8ac-45ab89e1a44a', 'bea2204f-a5cc-4931-a06a-0559b191d791', 'a25bf534-ac51-40f5-a9ab-b84fc3cb82b7', '2026-08-22 06:43:45'),
('9c6c0470-bdad-492d-afa4-cd7b5112c050', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', 'c9ab4f37-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 04:59:20'),
('a30269ca-41d5-4da3-8812-f10d349eb5ac', '93220e71-23ec-43df-8577-272c5c873711', 'c9ab4e32-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 10:43:55'),
('a3694d3d-f906-48c6-af33-ebc5c04495b3', '0c2f5188-2234-4212-9683-17f2f2431238', 'c9ab4f37-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-18 22:02:43'),
('a899621e-a6bb-4fde-a80e-ffb9dc9829ab', 'bd1e05f8-4124-4df5-81cd-79c794ba6432', 'ba3607da-8065-4b8b-8da9-6adf440ed6df', '2026-08-22 06:55:03'),
('ab1630ad-beb7-4e56-9020-5361001c080f', 'bea2204f-a5cc-4931-a06a-0559b191d791', 'c9ab4f37-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 06:43:45'),
('ab3e9364-5371-436b-8ddc-123e248c359f', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', 'ba3607da-8065-4b8b-8da9-6adf440ed6df', '2026-08-22 04:59:20'),
('aef138a9-9296-4e56-9c0d-7a86531012df', '2182587c-9095-41b4-a9d2-62eb7248df62', 'ba3607da-8065-4b8b-8da9-6adf440ed6df', '2026-08-22 08:52:06'),
('afd4eb24-2843-41b1-9226-5e4e8ea33147', 'bd1e05f8-4124-4df5-81cd-79c794ba6432', 'a25bf534-ac51-40f5-a9ab-b84fc3cb82b7', '2026-08-22 06:55:03'),
('b0772c6b-6fe4-4b5e-99a3-37be14f9e6aa', '807319ca-3418-4c12-b110-b66e00e7ab92', 'c9ab4e32-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 09:07:06'),
('b59143db-545f-49a8-99b5-20485d9f9ac1', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', 'eea2dfbf-711c-4ecf-ab42-4a249d4d16a6', '2026-08-22 04:59:20'),
('b6a12d04-5952-4dc4-9eda-78a5ce9240d2', 'bea2204f-a5cc-4931-a06a-0559b191d791', 'f8c7471a-b54b-48c7-b4db-ddd10834a020', '2026-08-22 06:43:45'),
('b753b1ba-7cfc-4d37-8075-ec6907060a40', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', 'b704f699-fe2d-4084-9f1b-c237b2915d8c', '2026-08-22 04:59:20'),
('bc82b9db-140a-4758-913e-8e7067cc9b4a', 'bd1e05f8-4124-4df5-81cd-79c794ba6432', 'f8c7471a-b54b-48c7-b4db-ddd10834a020', '2026-08-22 06:55:03'),
('cfca1651-0f45-4638-a934-a03c2f5f4f59', '93220e71-23ec-43df-8577-272c5c873711', 'c9ab4f37-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 10:43:55'),
('d0ca2981-b5a9-4726-8a33-d835c0c9f3b3', 'ed514de7-d9e2-45fc-876c-c192a5d6fd01', 'c9ab4e32-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 21:23:15'),
('d4535c38-e253-4499-9e62-46253cfa4c62', '8b77164a-57ed-4a7b-8443-fafd3daa06db', 'c9ab4f37-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 06:45:03'),
('d4cd7e56-fdfd-4ceb-99af-c9e964288e7c', '69b8669e-a7c6-44be-98b5-491ac4657915', 'c9ab4c4c-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 05:00:28'),
('d8c31a3d-2130-486b-9832-95948de01931', '2182587c-9095-41b4-a9d2-62eb7248df62', '426895b3-ff08-4b74-99cb-813ad1b93143', '2026-08-22 08:52:06'),
('da62bd97-4163-4a30-b2df-77d41bc8796e', '8b77164a-57ed-4a7b-8443-fafd3daa06db', '4d50db16-a3d2-485f-99b9-310fb795b7e4', '2026-08-22 06:45:03'),
('dae26ee5-efdc-4b1e-858a-d569a5b68d2d', '69b8669e-a7c6-44be-98b5-491ac4657915', 'f8c7471a-b54b-48c7-b4db-ddd10834a020', '2026-08-22 05:00:28'),
('db5773b2-32e4-4a4e-92f8-da50ea30a811', '2182587c-9095-41b4-a9d2-62eb7248df62', 'b3791fe5-2680-450b-839a-41031e9af2e7', '2026-08-22 08:52:06'),
('dbcb9ea1-7b12-4a5a-9134-ba975d2e5c7f', '8b77164a-57ed-4a7b-8443-fafd3daa06db', 'b704f699-fe2d-4084-9f1b-c237b2915d8c', '2026-08-22 06:45:03'),
('dc0780c3-7615-4d55-a800-f0533126e004', '11a5732c-6a26-4a58-bc43-aeab0f95a8cc', 'c9ab4c4c-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-17 21:41:52'),
('dc0e7185-cc65-47d1-ab5c-70ceae7d4704', '35f0faf4-d7c6-4709-87fd-d1f2d43758bb', 'c9ab4f37-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 21:17:43'),
('dc65c5b9-53b1-4a85-a31c-04c554410c55', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', 'c9ab4c4c-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 04:59:20'),
('dfd54a03-4d90-4cfe-b341-a6cad6e0097e', '882021ce-cdef-4461-b250-39ff6b882c13', 'c9ab4e32-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 10:13:20'),
('e493852c-b04c-4a07-9895-db74ded35df1', 'da080740-0c28-40e2-a691-332a70e0f27f', 'c9ab4e32-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 09:54:59'),
('e49e74a9-ce48-4b2f-bffd-a8aafea7e83f', '35f0faf4-d7c6-4709-87fd-d1f2d43758bb', 'c9ab4c4c-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 21:17:43'),
('e7857667-095d-4185-be47-3cf99907df7f', '11a5732c-6a26-4a58-bc43-aeab0f95a8cc', 'c9ab4e32-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-17 21:41:52'),
('eb06e7a0-98aa-4ab3-9a28-ca99fe420769', '175827b9-c7af-4c56-90fe-f0eb3898a6cf', 'c9ab4c4c-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 09:07:32'),
('ed31b10f-52c2-4d90-a54b-cb8146cf9c51', '8b77164a-57ed-4a7b-8443-fafd3daa06db', 'c9ab4e32-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 06:45:03'),
('f0a30e10-08f0-48da-8d50-a57f0b7c7317', '69b8669e-a7c6-44be-98b5-491ac4657915', 'b704f699-fe2d-4084-9f1b-c237b2915d8c', '2026-08-22 05:00:28'),
('f288c5a3-5d02-4896-8e5b-af1892d9ea15', 'bd1e05f8-4124-4df5-81cd-79c794ba6432', 'c9ab4f37-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 06:55:03'),
('f3e405e6-716f-4c8d-9b0a-6e32a9dc6ae8', '360cc1c4-e477-436a-9c71-28986f38e820', 'c9ab4f37-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-22 02:28:15'),
('f599f9ea-218b-4a3d-a2b0-9d604d5576bc', 'bea2204f-a5cc-4931-a06a-0559b191d791', 'eea2dfbf-711c-4ecf-ab42-4a249d4d16a6', '2026-08-22 06:43:45'),
('fb79ec5d-da9a-4ea2-93ee-03b6e0e18c4f', 'ed514de7-d9e2-45fc-876c-c192a5d6fd01', 'c9ab4f37-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 21:23:15'),
('fd60e296-4c0d-4e24-a325-56ec2234b28c', '0c2f5188-2234-4212-9683-17f2f2431238', 'c9ab4c4c-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-18 22:02:43'),
('fda09ef6-4521-4240-b85e-5b2c8eafef8c', '93220e71-23ec-43df-8577-272c5c873711', 'c9ab4c4c-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 10:43:55'),
('fe712e58-37c5-404e-959d-c61e9696b3ac', 'da080740-0c28-40e2-a691-332a70e0f27f', 'c9ab4f37-8dab-11f1-a4cf-1062e5a5cd6c', '2026-08-16 09:54:59');

-- --------------------------------------------------------

--
-- Table structure for table `property_images`
--

CREATE TABLE `property_images` (
  `id` char(36) NOT NULL,
  `property_id` char(36) NOT NULL,
  `image_url` text NOT NULL,
  `image_type` varchar(50) DEFAULT 'main',
  `sort_order` int(11) DEFAULT 0,
  `is_primary` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `property_images`
--

INSERT INTO `property_images` (`id`, `property_id`, `image_url`, `image_type`, `sort_order`, `is_primary`, `is_active`, `version`, `created_at`, `updated_at`, `deleted_at`) VALUES
('0723961a-2351-49a6-b48b-d17c9579b165', '4ef670b3-de31-415f-b061-deb50a89877d', 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'main', 0, 1, 1, 1, '2026-08-22 06:46:53', '2026-08-22 06:46:53', NULL),
('12b28f14-097f-4f4e-b130-3a99a51b8207', '0c2f5188-2234-4212-9683-17f2f2431238', '/uploads/properties/prop_0c2f5188_1787109964_2.jpeg', 'gallery', 2, 0, 1, 1, '2026-08-18 21:56:04', '2026-08-18 22:02:43', '2026-08-18 22:02:43'),
('1bf23f8d-9357-490a-b963-b8506763e506', '3527dd94-3289-48ef-b7e1-a0c8c347ca05', 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'main', 0, 1, 1, 1, '2026-08-22 06:49:58', '2026-08-22 06:49:58', NULL),
('20fb3566-ef42-458a-b4c3-9752a5589ccd', '0c2f5188-2234-4212-9683-17f2f2431238', '/uploads/properties/prop_0c2f5188_1787109964_2.jpeg', 'gallery', 2, 0, 1, 1, '2026-08-18 22:02:43', '2026-08-18 22:02:43', NULL),
('291b019e-7230-4dc3-9cc8-76acaf37ed7a', '11a5732c-6a26-4a58-bc43-aeab0f95a8cc', 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'gallery', 1, 0, 1, 1, '2026-08-17 21:41:52', '2026-08-17 21:41:52', NULL),
('2a4e92d2-6dc0-4730-82a8-8703d981ca67', '0c2f5188-2234-4212-9683-17f2f2431238', '/uploads/properties/prop_0c2f5188_1787109964_0.jpeg', 'main', 0, 1, 1, 1, '2026-08-18 21:56:04', '2026-08-18 22:02:43', '2026-08-18 22:02:43'),
('2b72293d-38ff-4889-ad01-554b210778c2', '807319ca-3418-4c12-b110-b66e00e7ab92', 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267', 'main', 0, 1, 1, 1, '2026-08-16 09:07:06', '2026-08-16 09:07:06', NULL),
('2f26ed96-bb22-4f7a-9f8f-a3c4549b45bc', '0c2f5188-2234-4212-9683-17f2f2431238', '/uploads/properties/prop_0c2f5188_1787109964_0.jpeg', 'main', 0, 1, 1, 1, '2026-08-18 22:02:43', '2026-08-18 22:02:43', NULL);
INSERT INTO `property_images` (`id`, `property_id`, `image_url`, `image_type`, `sort_order`, `is_primary`, `is_active`, `version`, `created_at`, `updated_at`, `deleted_at`) VALUES
('30d2a3ac-2843-4b41-97a1-5e0ed78d8a02', 'da080740-0c28-40e2-a691-332a70e0f27f', 'data:image/jpeg;base64,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', 'main', 0, 1, 1, 1, '2026-08-16 09:54:59', '2026-08-16 09:54:59', NULL);
INSERT INTO `property_images` (`id`, `property_id`, `image_url`, `image_type`, `sort_order`, `is_primary`, `is_active`, `version`, `created_at`, `updated_at`, `deleted_at`) VALUES
('325c3253-90ee-4169-a61d-6762bf1a3871', '9f495350-cc5c-4e44-a254-4fa36b179ac6', 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'main', 0, 1, 1, 1, '2026-08-22 06:48:05', '2026-08-22 06:48:05', NULL),
('3f800527-97d1-4aa3-b0ab-707a0ca63468', '7d787a88-1b74-481f-9061-8867f1babf60', 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'main', 0, 1, 1, 1, '2026-08-22 06:46:53', '2026-08-22 06:46:53', NULL),
('40a45521-f6c6-4960-88c7-065ab56b31bf', 'eb578e4e-8c64-4cd5-865c-88501563d123', 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'main', 1, 1, 1, 1, '2026-08-17 21:27:25', '2026-08-17 21:27:25', NULL),
('4168c3eb-73db-482c-b57f-2ec6c4f59e18', '11a5732c-6a26-4a58-bc43-aeab0f95a8cc', 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'gallery', 1, 0, 1, 1, '2026-08-17 21:39:15', '2026-08-17 21:41:52', '2026-08-17 21:41:52'),
('432e9751-99ba-4c50-babe-982c27c170d9', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '/uploads/properties/prop_e5f42792_1787391103_0.jpeg', 'main', 0, 1, 1, 1, '2026-08-22 04:01:43', '2026-08-22 04:37:40', '2026-08-22 04:37:40'),
('4cdd06b8-5499-49f4-bddd-0bda1c9d975c', '2cc20606-2431-4780-9ad7-aa2303773efe', 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'main', 1, 1, 1, 1, '2026-08-17 21:34:12', '2026-08-17 21:34:12', NULL),
('4d1dcd69-7228-4c1e-bfd2-7a94789ecf85', 'dc5b0828-7cdc-4cfc-8f13-3c75bd25b248', '/uploads/properties/prop_dc5b0828_1786894154_0.png', 'main', 0, 1, 1, 1, '2026-08-16 09:59:14', '2026-08-16 09:59:14', NULL),
('540cc681-fa3d-4dc0-911d-39422718c202', 'ed514de7-d9e2-45fc-876c-c192a5d6fd01', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSxPcup_X-Vawo7o1nQFlze4kcxHzniVkCin7Ft1fOW1Fy-afMxedmCsZo&s=10', 'main', 0, 1, 1, 1, '2026-08-16 21:23:15', '2026-08-16 21:23:15', NULL),
('5f1b71fe-22f6-4f26-b3a2-725d3af73fb4', '807319ca-3418-4c12-b110-b66e00e7ab92', 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c', 'gallery', 1, 0, 1, 1, '2026-08-16 09:07:06', '2026-08-16 09:07:06', NULL),
('5fb1d668-f2c5-4e23-80ae-23e75f2eaabd', '5de5b687-9316-459a-b3ed-481bf1b8a03f', 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'main', 0, 1, 1, 1, '2026-08-22 06:46:53', '2026-08-22 06:46:53', NULL),
('624a6c4b-106c-4f54-ac52-528035b3232e', '69b8669e-a7c6-44be-98b5-491ac4657915', '/uploads/properties/prop_69b8669e_1787393791_0.jpeg', 'main', 0, 1, 1, 1, '2026-08-22 05:00:28', '2026-08-22 05:00:28', NULL),
('63b7de91-93b4-4c22-9a53-bc712cffb903', '35f0faf4-d7c6-4709-87fd-d1f2d43758bb', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSxPcup_X-Vawo7o1nQFlze4kcxHzniVkCin7Ft1fOW1Fy-afMxedmCsZo&s=10', 'main', 0, 1, 1, 1, '2026-08-16 21:17:43', '2026-08-16 21:17:43', NULL),
('64506c4c-9201-45ba-bf45-2efb404a3239', 'e4614af2-142d-4c05-bc61-0c1f275d37c3', 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'main', 1, 1, 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL),
('649f0994-f43c-469c-86d2-c099781f6308', '175827b9-c7af-4c56-90fe-f0eb3898a6cf', 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c', 'gallery', 1, 0, 1, 1, '2026-08-16 09:07:32', '2026-08-16 09:07:32', NULL),
('658e0d57-53dd-4c53-b9a7-9c8d981cae25', '5c0e35ff-2063-4602-9cf6-ca0bbc036e8e', '/uploads/properties/prop_5c0e35ff_1787069244_0.jpeg', 'main', 0, 1, 1, 1, '2026-08-18 10:37:24', '2026-08-18 10:37:24', NULL),
('65ead94d-bf47-4ed1-8744-46ad4c9f718f', '51c35024-b974-4e62-8a4f-06f7a3282321', 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'main', 0, 1, 1, 1, '2026-08-22 06:48:05', '2026-08-22 06:48:05', NULL),
('6657ef54-209f-4361-be14-c4b36a9a6b3f', '4ab9cbde-a774-4cb1-929e-7c116716030a', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRfEbLw_4iPWKzJlmFLpz6ygPsSh4vJ5orNH17i9nXpjA&s', 'main', 0, 1, 1, 1, '2026-08-16 10:41:57', '2026-08-16 10:41:57', NULL),
('6abcc7a1-1b4b-4484-b3e0-36e0cffac0cf', '360cc1c4-e477-436a-9c71-28986f38e820', '/uploads/properties/prop_360cc1c4_1787385495_0.jpeg', 'main', 0, 1, 1, 1, '2026-08-22 02:28:15', '2026-08-22 02:28:15', NULL),
('6c58c0d2-2bbe-4b50-bed3-028eb5f610a0', '7c77e036-c1c7-4c5a-b688-122ff0f93098', 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'main', 0, 1, 1, 1, '2026-08-22 06:49:58', '2026-08-22 06:49:58', NULL),
('6d654aa3-fdb2-4293-828d-1d698ab2f5b3', '2182587c-9095-41b4-a9d2-62eb7248df62', '/uploads/properties/prop_2182587c_1787408526_0.jpeg', 'main', 0, 1, 1, 1, '2026-08-22 08:52:06', '2026-08-22 08:52:06', NULL),
('71266cf3-aa81-41f3-b30e-50205e750366', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '/uploads/properties/prop_e5f42792_1787391103_0.jpeg', 'main', 0, 1, 1, 1, '2026-08-22 04:58:01', '2026-08-22 04:59:20', '2026-08-22 04:59:20'),
('73221a21-8939-4898-8fd9-4a6b38e730ea', '0c2f5188-2234-4212-9683-17f2f2431238', '/uploads/properties/prop_0c2f5188_1787109964_1.jpeg', 'gallery', 1, 0, 1, 1, '2026-08-18 21:56:04', '2026-08-18 22:02:43', '2026-08-18 22:02:43'),
('7854de92-86c8-4d03-90c4-78ad31c9edfd', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '/uploads/properties/prop_e5f42792_1787391103_0.jpeg', 'main', 0, 1, 1, 1, '2026-08-22 04:40:35', '2026-08-22 04:42:50', '2026-08-22 04:42:50'),
('7947437f-d2b0-484e-84d7-b0424bad9879', '11a5732c-6a26-4a58-bc43-aeab0f95a8cc', '/uploads/properties/prop_11a5732c_1787022712_0.png', 'main', 0, 1, 1, 1, '2026-08-17 21:41:52', '2026-08-17 21:41:52', NULL),
('79c315ab-5696-464d-a99b-55f0dfae2439', '882021ce-cdef-4461-b250-39ff6b882c13', '/uploads/properties/prop_882021ce_1786895000_0.jpeg', 'main', 0, 1, 1, 1, '2026-08-16 10:13:20', '2026-08-16 10:13:20', NULL),
('8c042956-d3d9-487b-89df-0b62165edf20', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '/uploads/properties/prop_e5f42792_1787391103_0.jpeg', 'main', 0, 1, 1, 1, '2026-08-22 04:37:40', '2026-08-22 04:39:32', '2026-08-22 04:39:32'),
('8cc57bb1-1645-4c58-aaef-a7f002168eea', '2ce06724-f6aa-4b6d-b12d-df72a3492eb9', 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'main', 0, 1, 1, 1, '2026-08-16 08:59:52', '2026-08-16 08:59:52', NULL),
('9469cfe6-3bb1-4c37-ba0b-12ce8d6789a4', '6ca9f328-86ac-40d1-9d98-4a9c3823986f', 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'main', 0, 1, 1, 1, '2026-08-16 10:09:36', '2026-08-16 10:09:36', NULL),
('951d591d-2e1d-4243-8469-7ac80eb4d71b', '11a5732c-6a26-4a58-bc43-aeab0f95a8cc', '/uploads/properties/prop_11a5732c_1787022555_0.png', 'main', 0, 1, 1, 1, '2026-08-17 21:39:15', '2026-08-17 21:41:52', '2026-08-17 21:41:52'),
('9a870b05-4d3f-4189-bf70-68ae7488f1e3', '807319ca-3418-4c12-b110-b66e00e7ab92', 'https://images.unsplash.com/photo-1505691938895-1758d7feb511', 'gallery', 2, 0, 1, 1, '2026-08-16 09:07:06', '2026-08-16 09:07:06', NULL),
('9cfa3efd-3aa2-437f-8ac9-b3453c9c0019', '69b12b99-bcf5-4cca-bdf6-c13887397c7f', 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'main', 1, 1, 1, 1, '2026-08-17 21:39:24', '2026-08-17 21:39:24', NULL),
('9d87fcad-c09e-4a49-8fcc-51a5d64b757d', 'bea2204f-a5cc-4931-a06a-0559b191d791', '/uploads/properties/prop_bea2204f_1787400825_0.jpeg', 'main', 0, 1, 1, 1, '2026-08-22 06:43:45', '2026-08-22 06:43:45', NULL),
('9ec48531-3555-493c-bbea-193c15864979', '93220e71-23ec-43df-8577-272c5c873711', 'https://snadgy.com/wp-content/webp-express/webp-images/uploads/2022/01/Anal-sex-nude-girls-ass-fucking-baxkdoor-Adult-Time-videos-01.jpg.webp', 'main', 0, 1, 1, 1, '2026-08-16 10:43:55', '2026-08-16 10:43:55', NULL),
('b2f6a21e-e595-4133-abb2-74d00b25224a', '8b77164a-57ed-4a7b-8443-fafd3daa06db', '/uploads/properties/prop_8b77164a_1787400903_0.jpeg', 'main', 0, 1, 1, 1, '2026-08-22 06:45:03', '2026-08-22 06:45:03', NULL),
('bd2f0de5-4b4d-4eb2-ace4-5702c93185ec', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '/uploads/properties/prop_e5f42792_1787391103_0.jpeg', 'main', 0, 1, 1, 1, '2026-08-22 04:39:32', '2026-08-22 04:40:35', '2026-08-22 04:40:35'),
('bf10c0b9-f609-4878-8aa5-815762080453', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '/uploads/properties/prop_e5f42792_1787391103_0.jpeg', 'main', 0, 1, 1, 1, '2026-08-22 04:42:50', '2026-08-22 04:58:01', '2026-08-22 04:58:01'),
('c3fe435c-6491-44bb-a4f1-66a4ff25f67c', '69b8669e-a7c6-44be-98b5-491ac4657915', '/uploads/properties/prop_69b8669e_1787393791_0.jpeg', 'main', 0, 1, 1, 1, '2026-08-22 04:46:31', '2026-08-22 05:00:28', '2026-08-22 05:00:28'),
('c5751558-5764-41cb-98d9-d6211aa53bac', 'd9f5e991-207a-4023-b6fe-9deb3aacfc33', 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'main', 0, 1, 1, 1, '2026-08-16 10:10:08', '2026-08-16 10:10:08', NULL),
('c609a613-631f-4e2c-a77d-e725a1c04ab3', '11a5732c-6a26-4a58-bc43-aeab0f95a8cc', 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'main', 0, 1, 1, 1, '2026-08-16 09:59:13', '2026-08-17 21:39:15', '2026-08-17 21:39:15'),
('c662df01-9011-41ce-a223-1005d0079de4', '94a9038d-3e4f-4153-aa79-eb83d2542f6a', 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'main', 1, 1, 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL),
('c7e68079-aef0-47b7-b94f-b7c53eceef49', 'e8564421-0b10-4590-9a4a-8b5281b936e2', 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'main', 0, 1, 1, 1, '2026-08-22 06:49:58', '2026-08-22 06:49:58', NULL),
('cab2f058-5192-4879-9dc1-b550694f529e', 'd6779d7e-b9a0-45ac-ba92-3ced3ead699c', 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'main', 1, 1, 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL),
('cf3caa5f-a099-495d-8526-53abf3cc3775', '175827b9-c7af-4c56-90fe-f0eb3898a6cf', 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267', 'main', 0, 1, 1, 1, '2026-08-16 09:07:32', '2026-08-16 09:07:32', NULL),
('d0440f86-7810-4779-b7b4-d0f59a33ec2a', '0c2f5188-2234-4212-9683-17f2f2431238', '/uploads/properties/prop_0c2f5188_1787109964_1.jpeg', 'gallery', 1, 0, 1, 1, '2026-08-18 22:02:43', '2026-08-18 22:02:43', NULL),
('d1b93bfd-934c-4f88-be45-922f5b8b9f0d', 'c95791bd-c085-4e63-8075-5207d5fa7cbf', 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80', 'main', 1, 1, 1, 1, '2026-08-16 22:47:14', '2026-08-16 22:47:14', NULL),
('d467ff87-2bba-49a2-a5ff-7d03a36cdc22', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '/uploads/properties/prop_e5f42792_1787391103_0.jpeg', 'main', 0, 1, 1, 1, '2026-08-22 04:59:20', '2026-08-22 04:59:20', NULL),
('d4ad215b-47fa-4363-879b-c570bab3e381', 'a0050c80-7752-43b1-9ff2-8b86044fe7fd', 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'main', 0, 1, 1, 1, '2026-08-22 06:48:05', '2026-08-22 06:48:05', NULL),
('db4409d2-974e-4c82-8967-7b9891dd9524', '175827b9-c7af-4c56-90fe-f0eb3898a6cf', 'https://images.unsplash.com/photo-1505691938895-1758d7feb511', 'gallery', 2, 0, 1, 1, '2026-08-16 09:07:32', '2026-08-16 09:07:32', NULL),
('de909ee4-1fa2-454b-ac2c-dc55ddd2b2e8', 'bd1e05f8-4124-4df5-81cd-79c794ba6432', '/uploads/properties/prop_bd1e05f8_1787401503_0.jpeg', 'main', 0, 1, 1, 1, '2026-08-22 06:55:03', '2026-08-22 06:55:03', NULL),
('e38e0a4a-8a61-4eb0-a56e-acec9c668938', '5c0e35ff-2063-4602-9cf6-ca0bbc036e8e', 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'main', 1, 1, 1, 1, '2026-08-17 21:28:32', '2026-08-18 10:37:24', '2026-08-18 10:37:24');

-- --------------------------------------------------------

--
-- Table structure for table `property_reports`
--

CREATE TABLE `property_reports` (
  `id` char(36) NOT NULL,
  `property_id` char(36) NOT NULL,
  `user_id` char(36) DEFAULT NULL,
  `reporter_name` varchar(255) DEFAULT NULL,
  `reporter_email` varchar(255) DEFAULT NULL,
  `reporter_phone` varchar(255) DEFAULT NULL,
  `reason` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `property_reports`
--

INSERT INTO `property_reports` (`id`, `property_id`, `user_id`, `reporter_name`, `reporter_email`, `reporter_phone`, `reason`, `description`, `status`, `admin_notes`, `ip_address`, `created_at`, `updated_at`) VALUES
('1d46569d-8ce9-4f46-ac39-f3097dfb3c4d', '807319ca-3418-4c12-b110-b66e00e7ab92', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'Guest User', 'admin@staynest.com', '+919876543210', 'Property Closed / Already Full', NULL, 'pending', NULL, '127.0.0.1', '2026-08-18 12:11:11', '2026-08-18 12:11:11'),
('53c35773-c401-4b44-a505-3f042c2f96b9', '807319ca-3418-4c12-b110-b66e00e7ab92', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'Guest User', 'admin@staynest.com', '+919876543210', 'Host Unreachable / Rude / Abusive Behavior', NULL, 'pending', NULL, '127.0.0.1', '2026-08-18 12:10:53', '2026-08-18 12:10:53'),
('7f896bb0-0e60-4a11-a116-1c6e8f59e88a', '11a5732c-6a26-4a58-bc43-aeab0f95a8cc', NULL, 'Guest User', NULL, '9876543210', 'Fake or Misleading Photos', NULL, 'investigating', NULL, '127.0.0.1', '2026-08-18 12:14:59', '2026-08-19 20:17:49'),
('d92b7662-17f3-45d8-b2c1-ee47f9b35a3c', 'c95791bd-c085-4e63-8075-5207d5fa7cbf', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'Rahul Admin', 'admin@staynest.com', '+919876543210', 'Fake or Misleading Photos', 'not good pg', 'investigating', NULL, '127.0.0.1', '2026-08-19 20:06:43', '2026-08-19 20:14:56');

-- --------------------------------------------------------

--
-- Table structure for table `property_rules`
--

CREATE TABLE `property_rules` (
  `id` char(36) NOT NULL,
  `property_id` char(36) NOT NULL,
  `rule_text` text NOT NULL,
  `rule_type` enum('mandatory','optional') DEFAULT 'mandatory',
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `property_rules`
--

INSERT INTO `property_rules` (`id`, `property_id`, `rule_text`, `rule_type`, `is_active`, `version`, `created_at`, `updated_at`, `deleted_at`) VALUES
('061cf073-1f5a-4a4f-ad09-36e38eea250d', '360cc1c4-e477-436a-9c71-28986f38e820', '• Maintain cleanliness in shared washrooms and kitchen', 'mandatory', 1, 1, '2026-08-22 02:28:15', '2026-08-22 02:28:15', NULL),
('08ee5d88-902f-4c8f-b840-171e81043a21', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '• No loud music after 10:00 PM', 'mandatory', 1, 1, '2026-08-22 04:59:20', '2026-08-22 04:59:20', NULL),
('0a09c2c2-6cc9-418b-b6b9-0fef92ea9a2d', '360cc1c4-e477-436a-9c71-28986f38e820', '• Visitors allowed till 8:00 PM in common areas', 'mandatory', 1, 1, '2026-08-22 02:28:15', '2026-08-22 02:28:15', NULL),
('0e338ac3-61df-4c0c-8f08-6582c5d60c2c', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '• No loud music after 10:00 PM', 'mandatory', 1, 1, '2026-08-22 04:42:50', '2026-08-22 04:58:01', '2026-08-22 04:58:01'),
('0e78aac2-63d1-468f-8d40-fa3e0e3c4098', '0c2f5188-2234-4212-9683-17f2f2431238', '• Visitors allowed till 8:00 PM in common areas', 'mandatory', 1, 1, '2026-08-18 22:02:43', '2026-08-18 22:02:43', NULL),
('0f790499-55b5-43af-b625-2066d9d2c7df', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '• Smoking / drinking strictly prohibited inside rooms', 'mandatory', 1, 1, '2026-08-22 04:37:40', '2026-08-22 04:39:32', '2026-08-22 04:39:32'),
('12bb5cb2-8198-4a02-aae1-53e94765124f', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '• Gates close at 11:00 PM', 'mandatory', 1, 1, '2026-08-22 04:40:35', '2026-08-22 04:42:50', '2026-08-22 04:42:50'),
('13284051-b657-4292-a1b5-8c4d95e4ac12', '0c2f5188-2234-4212-9683-17f2f2431238', '• Visitors allowed till 8:00 PM in common areas', 'mandatory', 1, 1, '2026-08-18 21:56:04', '2026-08-18 22:02:43', '2026-08-18 22:02:43'),
('1568c83c-8dee-4495-b261-e91033b085af', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '• No loud music after 10:00 PM', 'mandatory', 1, 1, '2026-08-22 04:58:01', '2026-08-22 04:59:20', '2026-08-22 04:59:20'),
('16ae7748-7472-46f9-ac60-cfa9d451ee84', 'e8564421-0b10-4590-9a4a-8b5281b936e2', 'No smoking inside premises.', 'mandatory', 1, 1, '2026-08-22 06:49:58', '2026-08-22 06:49:58', NULL),
('182f8238-8865-468f-898d-7a2e9dd80417', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '• Smoking / drinking strictly prohibited inside rooms', 'mandatory', 1, 1, '2026-08-22 04:39:32', '2026-08-22 04:40:35', '2026-08-22 04:40:35'),
('1918167f-7755-42ae-8526-91c47e57b9a4', '0c2f5188-2234-4212-9683-17f2f2431238', '• Gates close at 11:00 PM', 'mandatory', 1, 1, '2026-08-18 21:56:04', '2026-08-18 22:02:43', '2026-08-18 22:02:43'),
('19fec705-36a2-47a0-9520-378f7f2a00e3', '882021ce-cdef-4461-b250-39ff6b882c13', '• Gates close at 11:00 PM', 'mandatory', 1, 1, '2026-08-16 10:13:20', '2026-08-16 10:13:20', NULL),
('1c861706-1371-4e65-b26b-13cc9876eab8', '69b8669e-a7c6-44be-98b5-491ac4657915', '• Opposite gender entry restricted to visiting hours in common areas', 'mandatory', 1, 1, '2026-08-22 04:46:31', '2026-08-22 05:00:28', '2026-08-22 05:00:28'),
('1f241ea9-7e2e-4426-822e-a6ebbc6fe1a2', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '• Gates close at 11:00 PM', 'mandatory', 1, 1, '2026-08-22 04:42:50', '2026-08-22 04:58:01', '2026-08-22 04:58:01'),
('22c7f453-c9ae-4859-8c3a-6907ef6daea9', '11a5732c-6a26-4a58-bc43-aeab0f95a8cc', '• Gate closes at 11 PM', 'mandatory', 1, 1, '2026-08-17 21:39:15', '2026-08-17 21:41:52', '2026-08-17 21:41:52'),
('28507cc7-ff5c-4c4b-b0ee-103f5298248b', '51c35024-b974-4e62-8a4f-06f7a3282321', 'Families and working professionals welcome.', 'mandatory', 1, 1, '2026-08-22 06:48:05', '2026-08-22 06:48:05', NULL),
('2c2a7bbe-2182-47b8-9895-d481a86c3dbe', '0c2f5188-2234-4212-9683-17f2f2431238', '• Smoking / drinking strictly prohibited inside rooms', 'mandatory', 1, 1, '2026-08-18 21:56:04', '2026-08-18 22:02:43', '2026-08-18 22:02:43'),
('30a30ad1-409e-40a6-a4ac-788645f9456a', '11a5732c-6a26-4a58-bc43-aeab0f95a8cc', '• Gate closes at 11 PM', 'mandatory', 1, 1, '2026-08-17 21:41:52', '2026-08-17 21:41:52', NULL),
('3181bbf7-f344-41a7-9215-c076facee50c', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '• Maintain cleanliness in shared washrooms and kitchen', 'mandatory', 1, 1, '2026-08-22 04:58:01', '2026-08-22 04:59:20', '2026-08-22 04:59:20'),
('370f75e1-2537-4c72-8d09-00ffec108a51', '882021ce-cdef-4461-b250-39ff6b882c13', '• No loud music after 10:00 PM', 'mandatory', 1, 1, '2026-08-16 10:13:20', '2026-08-16 10:13:20', NULL),
('371b2b5d-593a-4709-8eea-b2b4ab542386', '11a5732c-6a26-4a58-bc43-aeab0f95a8cc', '• No smoking inside rooms', 'mandatory', 1, 1, '2026-08-17 21:41:52', '2026-08-17 21:41:52', NULL),
('3978ce52-2a4e-49c3-a098-0e71097312ec', '0c2f5188-2234-4212-9683-17f2f2431238', '• Gates close at 11:00 PM', 'mandatory', 1, 1, '2026-08-18 22:02:43', '2026-08-18 22:02:43', NULL),
('3a7ffb79-fa7c-4004-bb55-8683e23e0804', 'bea2204f-a5cc-4931-a06a-0559b191d791', '• Opposite gender entry restricted to visiting hours in common areas', 'mandatory', 1, 1, '2026-08-22 06:43:45', '2026-08-22 06:43:45', NULL),
('3a9c3a20-c397-44c5-93bb-d58de3ddba9c', '3527dd94-3289-48ef-b7e1-a0c8c347ca05', 'Commercial use only.', 'mandatory', 1, 1, '2026-08-22 06:49:58', '2026-08-22 06:49:58', NULL),
('3d48d426-25ca-45eb-aa1c-e58044e8a9f9', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '• Maintain cleanliness in shared washrooms and kitchen', 'mandatory', 1, 1, '2026-08-22 04:37:40', '2026-08-22 04:39:32', '2026-08-22 04:39:32'),
('3d7101f3-0639-44f2-b669-1b1ab88c4e91', 'bd1e05f8-4124-4df5-81cd-79c794ba6432', 'Recommended for You', 'mandatory', 1, 1, '2026-08-22 06:55:03', '2026-08-22 06:55:03', NULL),
('3dabe6bd-f14d-4db1-a306-62e4ea8bf56d', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '• Smoking / drinking strictly prohibited inside rooms', 'mandatory', 1, 1, '2026-08-22 04:42:50', '2026-08-22 04:58:01', '2026-08-22 04:58:01'),
('3eb9c060-18a4-46c2-80a6-64258a7f4e77', '69b8669e-a7c6-44be-98b5-491ac4657915', '• Opposite gender entry restricted to visiting hours in common areas', 'mandatory', 1, 1, '2026-08-22 05:00:28', '2026-08-22 05:00:28', NULL),
('4a947ea3-c455-40fc-9ee3-01d8af2495de', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '• Visitors allowed till 8:00 PM in common areas', 'mandatory', 1, 1, '2026-08-22 04:58:01', '2026-08-22 04:59:20', '2026-08-22 04:59:20'),
('4fa4e056-a238-43a9-9cc5-6ef7d5ccabd8', 'd9f5e991-207a-4023-b6fe-9deb3aacfc33', 'No loud music after 10 PM. Gates close at 11 PM.', 'mandatory', 1, 1, '2026-08-16 10:10:08', '2026-08-16 10:10:08', NULL),
('566455d9-6125-4911-a8b0-df3cc34f146a', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '• Maintain cleanliness in shared washrooms and kitchen', 'mandatory', 1, 1, '2026-08-22 04:01:43', '2026-08-22 04:37:40', '2026-08-22 04:37:40'),
('567fe0d5-6bad-4744-b9bb-c8b4b8adeb85', 'ed514de7-d9e2-45fc-876c-c192a5d6fd01', '• Visitors allowed till 8:00 PM in common areas', 'mandatory', 1, 1, '2026-08-16 21:23:15', '2026-08-16 21:23:15', NULL),
('57b36f2c-c834-481c-9417-268eaef4883d', '0c2f5188-2234-4212-9683-17f2f2431238', '• No loud music after 10:00 PM', 'mandatory', 1, 1, '2026-08-18 22:02:43', '2026-08-18 22:02:43', NULL),
('604043f1-0398-44d5-b21a-8490a4e821f5', '360cc1c4-e477-436a-9c71-28986f38e820', '• Smoking / drinking strictly prohibited inside rooms', 'mandatory', 1, 1, '2026-08-22 02:28:15', '2026-08-22 02:28:15', NULL),
('61a0c232-7acc-4bf5-8fbf-f113298b39c4', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '• Gates close at 11:00 PM', 'mandatory', 1, 1, '2026-08-22 04:59:20', '2026-08-22 04:59:20', NULL),
('63900931-ed0e-488e-a012-4dc61f6d5890', '5de5b687-9316-459a-b3ed-481bf1b8a03f', 'Commercial use only.', 'mandatory', 1, 1, '2026-08-22 06:46:53', '2026-08-22 06:46:53', NULL),
('6b8375df-79f6-4b4b-bd49-e0f8965e4bef', '35f0faf4-d7c6-4709-87fd-d1f2d43758bb', '• Visitors allowed till 8:00 PM in common areas', 'mandatory', 1, 1, '2026-08-16 21:17:43', '2026-08-16 21:17:43', NULL),
('6de72822-65ea-410c-858c-8d73e9ee12dc', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '• Smoking / drinking strictly prohibited inside rooms', 'mandatory', 1, 1, '2026-08-22 04:58:01', '2026-08-22 04:59:20', '2026-08-22 04:59:20'),
('7586fcef-ecba-4628-8cda-008c12131076', '69b8669e-a7c6-44be-98b5-491ac4657915', '• Smoking / drinking strictly prohibited inside rooms', 'mandatory', 1, 1, '2026-08-22 05:00:28', '2026-08-22 05:00:28', NULL),
('797978d8-ecc3-4080-ae33-2ab62381f0f7', '7d787a88-1b74-481f-9061-8867f1babf60', 'Families and working professionals welcome.', 'mandatory', 1, 1, '2026-08-22 06:46:53', '2026-08-22 06:46:53', NULL),
('7a5c6952-7519-4896-942e-f6736a0290d7', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '• Smoking / drinking strictly prohibited inside rooms', 'mandatory', 1, 1, '2026-08-22 04:01:43', '2026-08-22 04:37:40', '2026-08-22 04:37:40'),
('83d9781a-61da-4ee7-b882-d0228f09da6a', 'a0050c80-7752-43b1-9ff2-8b86044fe7fd', 'No smoking inside premises.', 'mandatory', 1, 1, '2026-08-22 06:48:05', '2026-08-22 06:48:05', NULL),
('878765f2-7018-43eb-99bf-75263f9951a0', '882021ce-cdef-4461-b250-39ff6b882c13', '• Maintain cleanliness in shared washrooms and kitchen', 'mandatory', 1, 1, '2026-08-16 10:13:20', '2026-08-16 10:13:20', NULL),
('8880d5dc-8769-41f9-bda3-6e86921531fe', '2182587c-9095-41b4-a9d2-62eb7248df62', '• Smoking / drinking strictly prohibited inside rooms', 'mandatory', 1, 1, '2026-08-22 08:52:06', '2026-08-22 08:52:06', NULL),
('8c3c1a69-bb2f-49d0-8e2a-d49389d940b3', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '• Visitors allowed till 8:00 PM in common areas', 'mandatory', 1, 1, '2026-08-22 04:59:20', '2026-08-22 04:59:20', NULL),
('92a02681-949a-4910-811e-efed17420b9c', '8b77164a-57ed-4a7b-8443-fafd3daa06db', '• No loud music after 10:00 PM', 'mandatory', 1, 1, '2026-08-22 06:45:03', '2026-08-22 06:45:03', NULL),
('9717530a-a1a2-459c-b957-5b29a0ed1d1f', '4ef670b3-de31-415f-b061-deb50a89877d', 'No smoking inside premises.', 'mandatory', 1, 1, '2026-08-22 06:46:53', '2026-08-22 06:46:53', NULL),
('97784666-1496-40bd-8e59-c51c26cc30ba', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '• Maintain cleanliness in shared washrooms and kitchen', 'mandatory', 1, 1, '2026-08-22 04:40:35', '2026-08-22 04:42:50', '2026-08-22 04:42:50'),
('9a53aaf4-8f46-4ac9-80fb-5848919754c1', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '• Visitors allowed till 8:00 PM in common areas', 'mandatory', 1, 1, '2026-08-22 04:42:50', '2026-08-22 04:58:01', '2026-08-22 04:58:01'),
('9b0effd1-1787-4aaf-9a38-5a00e5938b88', '7c77e036-c1c7-4c5a-b688-122ff0f93098', 'Families and working professionals welcome.', 'mandatory', 1, 1, '2026-08-22 06:49:58', '2026-08-22 06:49:58', NULL),
('a1409fba-fbdc-4efb-9e39-d6dc9f5bdc44', 'bd1e05f8-4124-4df5-81cd-79c794ba6432', 'Recommended for You', 'mandatory', 1, 1, '2026-08-22 06:55:03', '2026-08-22 06:55:03', NULL),
('a1907c86-6202-4559-b863-5967622fba90', '35f0faf4-d7c6-4709-87fd-d1f2d43758bb', '• Gates close at 11:00 PM', 'mandatory', 1, 1, '2026-08-16 21:17:43', '2026-08-16 21:17:43', NULL),
('a5357c72-cdbf-4602-9981-98c4030e58fe', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '• Maintain cleanliness in shared washrooms and kitchen', 'mandatory', 1, 1, '2026-08-22 04:42:50', '2026-08-22 04:58:01', '2026-08-22 04:58:01'),
('a53b032e-c8cb-499c-8f83-0f04afd4a02e', '6ca9f328-86ac-40d1-9d98-4a9c3823986f', 'No loud music after 10 PM. Gates close at 11 PM.', 'mandatory', 1, 1, '2026-08-16 10:09:36', '2026-08-16 10:09:36', NULL),
('a601e694-9922-42a7-9b6c-cce2c1bb95a5', '0c2f5188-2234-4212-9683-17f2f2431238', '• No loud music after 10:00 PM', 'mandatory', 1, 1, '2026-08-18 21:56:04', '2026-08-18 22:02:43', '2026-08-18 22:02:43'),
('aae31ac6-0b09-427a-95d6-3e23c94c4110', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '• Maintain cleanliness in shared washrooms and kitchen', 'mandatory', 1, 1, '2026-08-22 04:59:20', '2026-08-22 04:59:20', NULL),
('ab0cba1e-5296-4f99-a03e-f36bbfed774c', '69b8669e-a7c6-44be-98b5-491ac4657915', '• Smoking / drinking strictly prohibited inside rooms', 'mandatory', 1, 1, '2026-08-22 04:46:31', '2026-08-22 05:00:28', '2026-08-22 05:00:28'),
('ac2de2a5-a022-4b89-9910-7a3351c74ce3', '882021ce-cdef-4461-b250-39ff6b882c13', '• Visitors allowed till 8:00 PM in common areas', 'mandatory', 1, 1, '2026-08-16 10:13:20', '2026-08-16 10:13:20', NULL),
('b037f9e3-a221-4d4f-b229-ee6c3b27f6a4', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '• Smoking / drinking strictly prohibited inside rooms', 'mandatory', 1, 1, '2026-08-22 04:59:20', '2026-08-22 04:59:20', NULL),
('b2cb9dbc-529c-4a01-aadb-1a808193092e', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '• Smoking / drinking strictly prohibited inside rooms', 'mandatory', 1, 1, '2026-08-22 04:40:35', '2026-08-22 04:42:50', '2026-08-22 04:42:50'),
('b3063594-e945-400f-9400-b32a856123d4', '11a5732c-6a26-4a58-bc43-aeab0f95a8cc', '• No smoking inside rooms', 'mandatory', 1, 1, '2026-08-17 21:34:07', '2026-08-17 21:39:15', '2026-08-17 21:39:15'),
('b736ade6-d10b-4795-88a1-112abea87ca1', '0c2f5188-2234-4212-9683-17f2f2431238', '• Maintain cleanliness in shared washrooms and kitchen', 'mandatory', 1, 1, '2026-08-18 21:56:04', '2026-08-18 22:02:43', '2026-08-18 22:02:43'),
('ba273444-d883-4954-9e11-8d497d3f03bf', '11a5732c-6a26-4a58-bc43-aeab0f95a8cc', '• No smoking inside rooms', 'mandatory', 1, 1, '2026-08-17 21:39:15', '2026-08-17 21:41:52', '2026-08-17 21:41:52'),
('bccb1557-c90a-4df6-8fe0-35d68397c793', '93220e71-23ec-43df-8577-272c5c873711', 'Provide a clear description of your property, amenities, nearby hotspots, and facilitie', 'mandatory', 1, 1, '2026-08-16 10:43:55', '2026-08-16 10:43:55', NULL),
('c4632ff1-2892-4241-a4c2-85efd1dea6e4', 'ed514de7-d9e2-45fc-876c-c192a5d6fd01', '• Gates close at 11:00 PM', 'mandatory', 1, 1, '2026-08-16 21:23:15', '2026-08-16 21:23:15', NULL),
('c9f29fb1-a523-461a-bd5a-47dc017445ef', '882021ce-cdef-4461-b250-39ff6b882c13', '• Smoking / drinking strictly prohibited inside rooms', 'mandatory', 1, 1, '2026-08-16 10:13:20', '2026-08-16 10:13:20', NULL),
('caba3a8b-9188-430e-82b5-22e7934ca276', '0c2f5188-2234-4212-9683-17f2f2431238', '• Smoking / drinking strictly prohibited inside rooms', 'mandatory', 1, 1, '2026-08-18 22:02:43', '2026-08-18 22:02:43', NULL),
('cb818d7e-29be-470a-bfda-5cb0a08cb803', '5c0e35ff-2063-4602-9cf6-ca0bbc036e8e', '• Gates close at 11:00 PM', 'mandatory', 1, 1, '2026-08-18 10:37:24', '2026-08-18 10:37:24', NULL),
('cd46390f-0d95-4fe3-9eb4-b277b684c457', '0c2f5188-2234-4212-9683-17f2f2431238', '• Maintain cleanliness in shared washrooms and kitchen', 'mandatory', 1, 1, '2026-08-18 22:02:43', '2026-08-18 22:02:43', NULL),
('d06f113c-283c-4107-a40f-77bfa36ff3e4', '9f495350-cc5c-4e44-a254-4fa36b179ac6', 'Commercial use only.', 'mandatory', 1, 1, '2026-08-22 06:48:05', '2026-08-22 06:48:05', NULL),
('d2e4afce-a436-4a82-a032-ac6e439f73e3', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '• Opposite gender entry restricted to visiting hours in common areas', 'mandatory', 1, 1, '2026-08-22 04:42:50', '2026-08-22 04:58:01', '2026-08-22 04:58:01'),
('d5697f28-c45c-4c82-87ad-0ab7975c9adc', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '• Opposite gender entry restricted to visiting hours in common areas', 'mandatory', 1, 1, '2026-08-22 04:59:20', '2026-08-22 04:59:20', NULL),
('d8d7b660-48dd-4f39-a41a-bb303cd9e7c8', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '• Gates close at 11:00 PM', 'mandatory', 1, 1, '2026-08-22 04:58:01', '2026-08-22 04:59:20', '2026-08-22 04:59:20'),
('d9682fbf-90ed-45bf-8620-c09cd0db9792', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '• Maintain cleanliness in shared washrooms and kitchen', 'mandatory', 1, 1, '2026-08-22 04:39:32', '2026-08-22 04:40:35', '2026-08-22 04:40:35'),
('dd096960-eed7-4d51-a527-af4711847d95', '4ab9cbde-a774-4cb1-929e-7c116716030a', 'Provide a clear description of your property, amenities, nearby hotspots, and facilities.', 'mandatory', 1, 1, '2026-08-16 10:41:57', '2026-08-16 10:41:57', NULL),
('e660c2be-6e57-4dde-bfa9-c9d21839a0bc', 'e5f42792-abfb-43d7-9ee2-ddcc5b9cf72b', '• Opposite gender entry restricted to visiting hours in common areas', 'mandatory', 1, 1, '2026-08-22 04:58:01', '2026-08-22 04:59:20', '2026-08-22 04:59:20'),
('f8808e7c-fed4-4dbb-895a-39f1e430947d', '11a5732c-6a26-4a58-bc43-aeab0f95a8cc', '• Gate closes at 11 PM', 'mandatory', 1, 1, '2026-08-17 21:34:07', '2026-08-17 21:39:15', '2026-08-17 21:39:15'),
('fde60d6b-39c1-47de-9187-fb9d69cd8c58', '2182587c-9095-41b4-a9d2-62eb7248df62', '• No loud music after 10:00 PM', 'mandatory', 1, 1, '2026-08-22 08:52:06', '2026-08-22 08:52:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `property_types`
--

CREATE TABLE `property_types` (
  `id` char(36) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `property_types`
--

INSERT INTO `property_types` (`id`, `name`, `slug`, `is_active`, `version`, `created_at`, `updated_at`, `deleted_at`) VALUES
('1585645b-023f-4377-9372-22ec1f4e903d', 'Commercial', 'commercial', 1, 1, '2026-08-16 22:22:52', '2026-08-16 22:22:52', NULL),
('8a7008fd-7265-48d3-9cfa-a422cc1cd61a', 'Flat / Apartment', 'flat', 1, 1, '2026-08-16 22:22:52', '2026-08-16 22:22:52', NULL),
('c9a76400-8dab-11f1-a4cf-1062e5a5cd6c', 'PG / Hostel', 'pg-hostel', 1, 1, '2026-08-01 13:20:38', '2026-08-01 13:20:38', NULL),
('c9a76614-8dab-11f1-a4cf-1062e5a5cd6c', 'Co-living', 'co-living', 0, 1, '2026-08-01 13:20:38', '2026-08-22 11:17:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `property_visits`
--

CREATE TABLE `property_visits` (
  `id` char(36) NOT NULL,
  `user_id` char(36) NOT NULL,
  `property_id` char(36) NOT NULL,
  `scheduled_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('scheduled','completed','cancelled','no_show') DEFAULT 'scheduled',
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `refresh_tokens`
--

CREATE TABLE `refresh_tokens` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `user_id` char(36) NOT NULL,
  `session_id` char(36) DEFAULT NULL,
  `token_hash` varchar(255) NOT NULL,
  `device_id` char(36) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `revoked_at` timestamp NULL DEFAULT NULL,
  `refresh_count` int(11) DEFAULT 0,
  `last_refresh_at` timestamp NULL DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `refunds`
--

CREATE TABLE `refunds` (
  `id` char(36) NOT NULL,
  `payment_id` char(36) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('pending','processed','failed') DEFAULT 'pending',
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `relationship_managers`
--

CREATE TABLE `relationship_managers` (
  `id` char(36) NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `whatsapp_number` varchar(30) DEFAULT NULL,
  `designation` varchar(120) NOT NULL DEFAULT 'Partner Relationship Manager',
  `zone` varchar(100) NOT NULL DEFAULT 'North Zone (Delhi NCR)',
  `city_coverage` varchar(255) DEFAULT NULL,
  `working_hours` varchar(100) NOT NULL DEFAULT 'Mon - Sat: 9:00 AM - 7:00 PM',
  `avatar_url` varchar(500) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `version` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `relationship_managers`
--

INSERT INTO `relationship_managers` (`id`, `name`, `email`, `phone`, `whatsapp_number`, `designation`, `zone`, `city_coverage`, `working_hours`, `avatar_url`, `is_active`, `is_default`, `version`, `created_at`, `updated_at`, `deleted_at`) VALUES
('0af44a9c-c0c6-453b-b0b0-d33e931188d8', 'Pooja Sharma', 'pooja.sharma@staynest.com', '+91 98765 43213', '919876543213', 'Partner Success Manager', 'West Zone (Mumbai & Pune)', 'Mumbai, Pune, Ahmedabad, Surat', 'Mon - Sat: 9:00 AM - 6:30 PM', 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=300&q=80', 1, 0, 1, '2026-08-17 21:01:43', '2026-08-17 21:01:43', NULL),
('4e0dfbb0-1368-4bc0-8f23-a8257f70c5b8', 'Rohan Mehta', 'rohan.mehta@staynest.com', '+91 98765 43212', '919876543212', 'Regional Growth Specialist', 'South Zone (Bangalore & Hyderabad)', 'Bangalore, Hyderabad, Chennai, Kochi', 'Mon - Sat: 9:30 AM - 7:00 PM', 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=300&q=80', 1, 0, 1, '2026-08-17 21:01:43', '2026-08-17 21:01:43', NULL),
('55d42e3e-bcf5-4ad7-bd7d-0e1a4f86ee42', 'Ananya Sengupta', 'ananya.sengupta@staynest.com', '+91 98765 43210', '919876543210', 'Senior Key Account Lead', 'North Zone (Noida & Delhi NCR)', 'Noida, Delhi, Gurgaon, Faridabad', 'Mon - Sat: 9:00 AM - 7:30 PM', '/uploads/rm_avatars/rm_55d42e3e-bcf5-4ad7-bd7d-0e1a4f86ee42_1787020912.jpg', 1, 1, 1, '2026-08-17 21:01:43', '2026-08-17 21:11:52', NULL),
('cb79301d-747b-439d-a95f-70237e252ac3', 'Karan Kapoor', 'karan.kapoor@staynest.com', '+91 98765 43214', '919876543214', 'Priority Partner Concierge Lead', 'VIP Pan-India Co-Living', 'All Tier-1 & Metro Clusters', 'Mon - Sun: 8:30 AM - 8:30 PM', 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=300&q=80', 1, 0, 1, '2026-08-17 21:01:43', '2026-08-17 21:01:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` char(36) NOT NULL,
  `user_id` char(36) NOT NULL,
  `property_id` char(36) NOT NULL,
  `booking_id` char(36) DEFAULT NULL,
  `rating` decimal(3,2) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `comment` text NOT NULL,
  `broker_reply` text DEFAULT NULL,
  `broker_reply_at` timestamp NULL DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `helpful_count` int(11) DEFAULT 0,
  `status` enum('pending','approved','rejected') DEFAULT 'approved',
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `property_id`, `booking_id`, `rating`, `title`, `comment`, `broker_reply`, `broker_reply_at`, `is_verified`, `helpful_count`, `status`, `is_active`, `version`, `created_at`, `updated_at`, `deleted_at`) VALUES
('1897e172-0951-41d2-b099-c382469320b2', 'b9585777-9009-44f4-a3f7-e316abbaf0d2', '94a9038d-3e4f-4153-aa79-eb83d2542f6a', NULL, 4.80, 'Exceptional Living Experience', 'The property is pristine, WiFi is blazing fast, and the staff is very accommodating.', NULL, NULL, 1, 0, 'approved', 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL),
('29c05acb-9550-474f-a935-c53e4e39927b', '77a10f6a-c120-405c-bca9-f95341a44a15', '94a9038d-3e4f-4153-aa79-eb83d2542f6a', NULL, 5.00, 'hty', 'tddcty yuyu', NULL, NULL, 1, 0, 'approved', 1, 1, '2026-08-18 13:01:36', '2026-08-18 13:03:09', NULL),
('3d36186a-0b3c-4361-ad00-8b38575b0dff', '2d28d9da-2144-487d-997e-68b5624706f1', '94a9038d-3e4f-4153-aa79-eb83d2542f6a', NULL, 4.50, 'Comfortable and clean', 'Very satisfied with the amenities, food and location.', NULL, NULL, 1, 0, 'pending', 1, 1, '2026-08-18 12:56:50', '2026-08-18 12:56:50', NULL),
('49a74859-c0a2-4081-a94f-ea715f23442a', 'e62e7b98-e262-4c3c-a366-2989f5b87bbe', 'e4614af2-142d-4c05-bc61-0c1f275d37c3', NULL, 4.80, 'Exceptional Living Experience', 'The property is pristine, WiFi is blazing fast, and the staff is very accommodating.', NULL, NULL, 1, 0, 'approved', 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL),
('5d6c5edf-b8e0-4659-90c3-d8686a84db08', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', '360cc1c4-e477-436a-9c71-28986f38e820', NULL, 3.00, 'ok ok', 'ok he', NULL, NULL, 1, 0, 'approved', 1, 1, '2026-08-22 02:32:53', '2026-08-22 02:36:11', NULL),
('9564e77b-687c-442b-bd33-9d65ff8fe618', 'e62e7b98-e262-4c3c-a366-2989f5b87bbe', 'd6779d7e-b9a0-45ac-ba92-3ced3ead699c', NULL, 4.80, 'Exceptional Living Experience', 'The property is pristine, WiFi is blazing fast, and the staff is very accommodating.', NULL, NULL, 1, 0, 'approved', 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL),
('99a8d684-293e-41aa-8531-c18a65dba4a9', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'c95791bd-c085-4e63-8075-5207d5fa7cbf', NULL, 5.00, 'goof je', 'ok ok', NULL, NULL, 1, 0, 'pending', 1, 1, '2026-08-22 09:13:01', '2026-08-22 09:13:01', NULL),
('9be180cd-a66a-460e-8a68-b0ec7b9eef00', 'e62e7b98-e262-4c3c-a366-2989f5b87bbe', 'c95791bd-c085-4e63-8075-5207d5fa7cbf', NULL, 4.80, 'Exceptional Living Experience', 'The property is pristine, WiFi is blazing fast, and the staff is very accommodating.', NULL, NULL, 1, 0, 'approved', 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL),
('a36fc205-2cc8-40df-af68-9a75a57fb2d5', 'e62e7b98-e262-4c3c-a366-2989f5b87bbe', '94a9038d-3e4f-4153-aa79-eb83d2542f6a', NULL, 4.80, 'Exceptional Living Experience', 'The property is pristine, WiFi is blazing fast, and the staff is very accommodating.', NULL, NULL, 1, 0, 'approved', 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL),
('bd98fd83-dca2-42a6-b21a-7f5cd36dddc6', 'b9585777-9009-44f4-a3f7-e316abbaf0d2', 'c95791bd-c085-4e63-8075-5207d5fa7cbf', NULL, 4.80, 'Exceptional Living Experience', 'The property is pristine, WiFi is blazing fast, and the staff is very accommodating.', NULL, NULL, 1, 0, 'approved', 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL),
('cb45efb3-790a-4845-ad9f-b95109a18cd9', 'b9585777-9009-44f4-a3f7-e316abbaf0d2', 'd6779d7e-b9a0-45ac-ba92-3ced3ead699c', NULL, 4.80, 'Exceptional Living Experience', 'The property is pristine, WiFi is blazing fast, and the staff is very accommodating.', NULL, NULL, 1, 0, 'approved', 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL),
('d927412b-ea62-4ff8-8bc8-46612d064d2d', '2d28d9da-2144-487d-997e-68b5624706f1', '94a9038d-3e4f-4153-aa79-eb83d2542f6a', NULL, 5.00, 'Spectacular Co-living experience!', 'Spacious rooms, super clean bathrooms, high-speed WiFi and very supportive management.', NULL, NULL, 1, 0, 'approved', 1, 1, '2026-08-18 12:42:31', '2026-08-18 12:42:32', NULL),
('d9f12074-8b83-425b-bd5f-6a7c65af11b2', 'b9585777-9009-44f4-a3f7-e316abbaf0d2', 'e4614af2-142d-4c05-bc61-0c1f275d37c3', NULL, 4.80, 'Exceptional Living Experience', 'The property is pristine, WiFi is blazing fast, and the staff is very accommodating.', NULL, NULL, 1, 0, 'approved', 1, 1, '2026-08-16 22:48:54', '2026-08-16 22:48:54', NULL),
('e8599d0e-c4a6-49fc-b3f9-983f02e7abdb', '77a10f6a-c120-405c-bca9-f95341a44a15', '5c0e35ff-2063-4602-9cf6-ca0bbc036e8e', NULL, 5.00, 'ef 4ty', 'efe 43 36 6 6 6 6', NULL, NULL, 1, 0, 'approved', 1, 1, '2026-08-18 13:06:43', '2026-08-18 13:07:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` char(36) NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(60) NOT NULL,
  `level` int(11) DEFAULT 0,
  `is_system` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `level`, `is_system`, `is_active`, `version`, `created_at`, `updated_at`, `deleted_at`) VALUES
('c9b0fd91-8dab-11f1-a4cf-1062e5a5cd6c', 'Super Admin', 'super_admin', 100, 1, 1, 1, '2026-08-01 13:20:38', '2026-08-01 13:20:38', NULL),
('c9b10005-8dab-11f1-a4cf-1062e5a5cd6c', 'Broker', 'broker', 70, 1, 1, 1, '2026-08-01 13:20:38', '2026-08-01 13:20:38', NULL),
('c9b100f2-8dab-11f1-a4cf-1062e5a5cd6c', 'Tenant', 'tenant', 10, 1, 1, 1, '2026-08-01 13:20:38', '2026-08-01 13:20:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` char(36) NOT NULL,
  `role_id` char(36) NOT NULL,
  `permission_id` char(36) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` char(36) NOT NULL,
  `floor_id` char(36) NOT NULL,
  `room_type_id` char(36) NOT NULL,
  `room_number` varchar(50) NOT NULL,
  `total_beds` int(11) NOT NULL DEFAULT 1,
  `available_beds` int(11) NOT NULL DEFAULT 1,
  `monthly_rent` decimal(10,2) NOT NULL,
  `security_deposit` decimal(10,2) DEFAULT 0.00,
  `attached_bathroom` tinyint(1) DEFAULT 0,
  `ac_available` tinyint(1) DEFAULT 0,
  `balcony` tinyint(1) DEFAULT 0,
  `status` enum('available','occupied','maintenance','reserved') DEFAULT 'available',
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `updated_by` char(36) DEFAULT NULL,
  `deleted_by` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `floor_id`, `room_type_id`, `room_number`, `total_beds`, `available_beds`, `monthly_rent`, `security_deposit`, `attached_bathroom`, `ac_available`, `balcony`, `status`, `is_active`, `version`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`) VALUES
('037ac521-05b4-48ed-a59e-2104992766d5', 'fe5e8f7c-2a44-43dc-a322-80d9c51fdc9d', 'c9a9f0ee-8dab-11f1-a4cf-1062e5a5cd6c', '101', 2, 1, 5000.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('09fccc13-f28d-4b38-a101-a8760b167a78', 'e7a31e55-7d90-4226-81aa-be59b1d934c3', 'c9a9eeb7-8dab-11f1-a4cf-1062e5a5cd6c', 'S-101', 1, 0, 12000.00, 35353.00, 1, 1, 1, 'available', 1, 1, '2026-08-22 06:55:03', '2026-08-22 06:55:03', NULL, NULL, NULL, NULL),
('1399ec09-3b76-4156-b389-cbbbf7aa9731', '429ae38d-840a-49b8-ae7e-7a6ccf44c294', 'c9a9eeb7-8dab-11f1-a4cf-1062e5a5cd6c', 'S-101', 1, 0, 12000.00, 78.00, 1, 1, 1, 'available', 1, 1, '2026-08-22 06:45:03', '2026-08-22 06:45:03', NULL, NULL, NULL, NULL),
('1667bb02-e58f-4a26-bacd-73760ca9ec2d', '3120491f-a514-49c5-8e51-d09a112f7a3c', 'c9a9f1f4-8dab-11f1-a4cf-1062e5a5cd6c', '102', 2, 1, 2421412.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('16d9f70c-f149-47f6-ab36-2a1f90be8be7', '3e1c5340-3c57-4344-ac72-d0da0687f11f', 'c9a9eeb7-8dab-11f1-a4cf-1062e5a5cd6c', '103', 2, 1, 699.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('1b44527b-6897-433f-9616-f83dea1d8706', '3120491f-a514-49c5-8e51-d09a112f7a3c', 'c9a9f0ee-8dab-11f1-a4cf-1062e5a5cd6c', '101', 2, 1, 2421412.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('1db581a8-a3af-4a29-ac67-f9681b04489f', 'c9ee5b6b-d9ff-47c6-8624-9726061de833', 'c9a9eeb7-8dab-11f1-a4cf-1062e5a5cd6c', '103', 2, 1, 8500.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('1eafd830-319b-4c82-ad3b-a3ed49d2daf8', 'c9ee5b6b-d9ff-47c6-8624-9726061de833', 'c9a9f0ee-8dab-11f1-a4cf-1062e5a5cd6c', '101', 2, 1, 8500.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('1f9f6784-34b0-4070-bfde-34298ff8b04b', 'd400a779-f8dd-4487-9765-11688446b9d8', 'c9a9eeb7-8dab-11f1-a4cf-1062e5a5cd6c', '103', 2, 1, 7500.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('365c0245-2e31-410e-a2ba-037aae2fd328', '7ef12ce0-8149-4953-a3b9-3447f9d4fc73', 'c9a9eeb7-8dab-11f1-a4cf-1062e5a5cd6c', '103', 2, 1, 8500.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('388afdad-06c3-4c13-8c2f-9ce3eb9814a3', '0ee4e2f2-cc24-42ed-a3f2-25f81ee46f16', 'c9a9f1f4-8dab-11f1-a4cf-1062e5a5cd6c', '102', 2, 1, 8500.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('3c046a51-5abf-4372-98f2-59e6aff355b1', '0ee4e2f2-cc24-42ed-a3f2-25f81ee46f16', 'c9a9f0ee-8dab-11f1-a4cf-1062e5a5cd6c', '101', 2, 1, 8500.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('42f4c112-2da1-4285-8330-6eb9bfcce441', '3e1c5340-3c57-4344-ac72-d0da0687f11f', 'c9a9f1f4-8dab-11f1-a4cf-1062e5a5cd6c', '102', 2, 1, 699.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('47c9c988-01c7-4a68-aaff-2fa8b3469330', 'cc20c792-b016-4878-b8e0-0e91145c7149', 'c9a9f1f4-8dab-11f1-a4cf-1062e5a5cd6c', 'T-103', 3, 2, 6500.00, 2000000.00, 1, 1, 1, 'available', 1, 1, '2026-08-22 04:59:20', '2026-08-22 04:59:20', NULL, NULL, NULL, NULL),
('4f5b5260-3c0c-481c-b613-170b26306abb', 'c9ee5b6b-d9ff-47c6-8624-9726061de833', 'c9a9f1f4-8dab-11f1-a4cf-1062e5a5cd6c', '102', 2, 1, 8500.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('500162b1-2c4b-44e2-a24c-0c87ddee973b', '3d7f9e99-db4c-48de-b622-aa297e384863', 'c9a9f0ee-8dab-11f1-a4cf-1062e5a5cd6c', '101', 2, 1, 22412.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('54e1e183-7b94-49a0-a89c-e67e2036c3d3', 'd400a779-f8dd-4487-9765-11688446b9d8', 'c9a9f0ee-8dab-11f1-a4cf-1062e5a5cd6c', '101', 2, 1, 7500.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('60dc39a6-b3eb-4813-966e-c3d9382253cc', '3c88ae19-deba-43ae-9d75-bf63037ce7f7', 'c9a9eeb7-8dab-11f1-a4cf-1062e5a5cd6c', '103', 2, 1, 8500.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('623d7c36-cf4c-49ec-989b-d08737c70c30', '04b7356f-72da-47b1-9a35-60ace602f33d', 'c9a9eeb7-8dab-11f1-a4cf-1062e5a5cd6c', '103', 2, 1, 8500.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('678659ac-99ac-4766-9d41-47460755df81', '04b7356f-72da-47b1-9a35-60ace602f33d', 'c9a9f0ee-8dab-11f1-a4cf-1062e5a5cd6c', '101', 2, 1, 8500.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('6c73c5e0-2ceb-4286-a7e2-7dcbd5497a3c', '4c479803-4eaf-4b1f-959d-6078506fda79', 'c9a9f1f4-8dab-11f1-a4cf-1062e5a5cd6c', '102', 2, 1, 8500.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('6d16d373-c365-4da2-8788-05754472d3b8', '7ef12ce0-8149-4953-a3b9-3447f9d4fc73', 'c9a9f1f4-8dab-11f1-a4cf-1062e5a5cd6c', '102', 2, 1, 8500.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('6ebc4590-ce6d-4557-aa74-b1ef5603ed23', '4c479803-4eaf-4b1f-959d-6078506fda79', 'c9a9f0ee-8dab-11f1-a4cf-1062e5a5cd6c', '101', 2, 1, 8500.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('74f9c09b-2bb6-4b8e-902c-4b133b79f983', '7ef12ce0-8149-4953-a3b9-3447f9d4fc73', 'c9a9f0ee-8dab-11f1-a4cf-1062e5a5cd6c', '101', 2, 1, 8500.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('7892a641-891b-445b-9f22-1df83bd453d7', '429ae38d-840a-49b8-ae7e-7a6ccf44c294', 'c9a9f0ee-8dab-11f1-a4cf-1062e5a5cd6c', 'D-102', 2, 1, 8500.00, 78.00, 1, 1, 1, 'available', 1, 1, '2026-08-22 06:45:03', '2026-08-22 06:45:03', NULL, NULL, NULL, NULL),
('910a0ff3-87ca-4dca-b15f-468f910b2629', '4dd903c2-82d0-447e-926d-413098eff8ca', 'c9a9f1f4-8dab-11f1-a4cf-1062e5a5cd6c', '102', 2, 1, 8500.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('94491367-3520-43fb-a303-d9218482fb9c', '29ef1fda-c870-4128-b82b-d80c707c2ae1', 'c9a9f0ee-8dab-11f1-a4cf-1062e5a5cd6c', '101', 2, 1, 412294.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('96a17755-e654-47a5-9aaf-eacd7121e573', 'cc20c792-b016-4878-b8e0-0e91145c7149', 'c9a9f0ee-8dab-11f1-a4cf-1062e5a5cd6c', 'D-102', 2, 1, 848.00, 2000000.00, 1, 1, 1, 'available', 1, 1, '2026-08-22 04:59:20', '2026-08-22 04:59:20', NULL, NULL, NULL, NULL),
('975d0466-1787-42c8-9587-73d1c56de7e7', '29ef1fda-c870-4128-b82b-d80c707c2ae1', 'c9a9f1f4-8dab-11f1-a4cf-1062e5a5cd6c', '102', 2, 1, 412294.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('9a15f0aa-6b09-4429-80ba-cecc421a7260', 'e94ed2b1-e1ee-43f8-96c6-5880bed023a1', 'c9a9f0ee-8dab-11f1-a4cf-1062e5a5cd6c', '101', 2, 1, 232123.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('9ec6c483-4b51-4c5e-86d7-0ed9e25e0985', '04b7356f-72da-47b1-9a35-60ace602f33d', 'c9a9f1f4-8dab-11f1-a4cf-1062e5a5cd6c', '102', 2, 1, 8500.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('9ffd0853-7a69-47d7-8271-1cc1874109ec', '4dd903c2-82d0-447e-926d-413098eff8ca', 'c9a9eeb7-8dab-11f1-a4cf-1062e5a5cd6c', '103', 2, 1, 8500.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('a0fa2a8e-3937-44ff-ae09-12b4a248dced', '4dd903c2-82d0-447e-926d-413098eff8ca', 'c9a9f0ee-8dab-11f1-a4cf-1062e5a5cd6c', '101', 2, 1, 8500.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('a46e2c0c-540d-4a1a-ba1e-111fc78bb61b', '29ef1fda-c870-4128-b82b-d80c707c2ae1', 'c9a9eeb7-8dab-11f1-a4cf-1062e5a5cd6c', '103', 2, 1, 412294.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('abd4198c-4fdc-4943-acd4-9d4c4982d71a', 'e94ed2b1-e1ee-43f8-96c6-5880bed023a1', 'c9a9f1f4-8dab-11f1-a4cf-1062e5a5cd6c', '102', 2, 1, 232123.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('ac8bfcff-57c9-4a04-becd-3eea924d9769', '3d7f9e99-db4c-48de-b622-aa297e384863', 'c9a9f1f4-8dab-11f1-a4cf-1062e5a5cd6c', '102', 2, 1, 22412.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('bcd56d71-f65c-4705-8f0c-9b465630c109', 'd400a779-f8dd-4487-9765-11688446b9d8', 'c9a9f1f4-8dab-11f1-a4cf-1062e5a5cd6c', '102', 2, 1, 7500.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('bd480841-f1e3-42f6-9d45-66b1d5622f74', '4c479803-4eaf-4b1f-959d-6078506fda79', 'c9a9eeb7-8dab-11f1-a4cf-1062e5a5cd6c', '103', 2, 1, 8500.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('cb11b740-35ac-474f-8046-14de6a1c78ca', '0ee4e2f2-cc24-42ed-a3f2-25f81ee46f16', 'c9a9eeb7-8dab-11f1-a4cf-1062e5a5cd6c', '103', 2, 1, 8500.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('cf26b6cb-273f-4b3b-baa9-5dce475d70ec', '3120491f-a514-49c5-8e51-d09a112f7a3c', 'c9a9eeb7-8dab-11f1-a4cf-1062e5a5cd6c', '103', 2, 1, 2421412.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('cfa051a8-bb39-4a2e-b213-7d83ea9af364', 'e94ed2b1-e1ee-43f8-96c6-5880bed023a1', 'c9a9eeb7-8dab-11f1-a4cf-1062e5a5cd6c', '103', 2, 1, 232123.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('d81d2f88-8fd7-49cb-8fda-b0dd18c98714', 'cc20c792-b016-4878-b8e0-0e91145c7149', 'c9a9eeb7-8dab-11f1-a4cf-1062e5a5cd6c', 'S-101', 1, 0, 1200.00, 2000000.00, 1, 1, 1, 'available', 1, 1, '2026-08-22 04:59:20', '2026-08-22 04:59:20', NULL, NULL, NULL, NULL),
('db816900-9ce4-4699-abf7-5c1113d0438c', '3d7f9e99-db4c-48de-b622-aa297e384863', 'c9a9eeb7-8dab-11f1-a4cf-1062e5a5cd6c', '103', 2, 1, 22412.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('ddfde531-ef45-425f-a0d0-ed83fc5e1ea3', 'e7a31e55-7d90-4226-81aa-be59b1d934c3', 'c9a9f0ee-8dab-11f1-a4cf-1062e5a5cd6c', 'D-102', 2, 1, 8500.00, 35353.00, 1, 1, 1, 'available', 1, 1, '2026-08-22 06:55:03', '2026-08-22 06:55:03', NULL, NULL, NULL, NULL),
('eb32d909-84af-4acf-ae20-099cb7892af2', 'd7dfc936-cd94-41a5-848c-5306eb918847', 'c9a9eeb7-8dab-11f1-a4cf-1062e5a5cd6c', 'S-101', 1, 0, 12000.00, 44.00, 1, 1, 1, 'available', 1, 1, '2026-08-22 06:43:45', '2026-08-22 06:43:45', NULL, NULL, NULL, NULL),
('f224855a-0094-4c7a-8472-11fdd1ace548', 'd7dfc936-cd94-41a5-848c-5306eb918847', 'c9a9f0ee-8dab-11f1-a4cf-1062e5a5cd6c', 'D-102', 2, 1, 8500.00, 44.00, 1, 1, 1, 'available', 1, 1, '2026-08-22 06:43:45', '2026-08-22 06:43:45', NULL, NULL, NULL, NULL),
('f2d7c9a7-9514-4a36-ab08-7684bc919044', '279e46c5-16bd-4beb-a67a-a73e4e701155', 'c9a9eeb7-8dab-11f1-a4cf-1062e5a5cd6c', 'S-101', 1, 0, 12000.00, 43.00, 1, 1, 1, 'available', 1, 1, '2026-08-22 08:52:06', '2026-08-22 08:52:06', NULL, NULL, NULL, NULL),
('f711c11b-5ebd-4a2a-8992-ecd8d5032f7a', '279e46c5-16bd-4beb-a67a-a73e4e701155', 'c9a9f0ee-8dab-11f1-a4cf-1062e5a5cd6c', 'D-102', 2, 1, 8500.00, 43.00, 1, 1, 1, 'available', 1, 1, '2026-08-22 08:52:06', '2026-08-22 08:52:06', NULL, NULL, NULL, NULL),
('f8f1b957-d6bf-49ed-a6e5-e4502ee62a1d', '3c88ae19-deba-43ae-9d75-bf63037ce7f7', 'c9a9f1f4-8dab-11f1-a4cf-1062e5a5cd6c', '102', 2, 1, 8500.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('f9d9423f-1b69-4d6d-927a-113b8d0a95cf', 'fe5e8f7c-2a44-43dc-a322-80d9c51fdc9d', 'c9a9f1f4-8dab-11f1-a4cf-1062e5a5cd6c', '102', 2, 1, 5000.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('fc759d5d-fab7-4304-b9bd-81c4c166b61a', '3c88ae19-deba-43ae-9d75-bf63037ce7f7', 'c9a9f0ee-8dab-11f1-a4cf-1062e5a5cd6c', '101', 2, 1, 8500.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('fcaeafdb-9ba5-4485-b75f-a07ed267e95d', 'cc20c792-b016-4878-b8e0-0e91145c7149', 'b3e3f040-9871-4bce-ba6a-bca2e02b8ae2', 'F-104', 4, 3, 5000.00, 2000000.00, 1, 1, 1, 'available', 1, 1, '2026-08-22 04:59:20', '2026-08-22 04:59:20', NULL, NULL, NULL, NULL),
('fe6c84bc-a2b5-42dc-b469-03830c2e9592', 'fe5e8f7c-2a44-43dc-a322-80d9c51fdc9d', 'c9a9eeb7-8dab-11f1-a4cf-1062e5a5cd6c', '103', 2, 1, 5000.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('fec9e281-edc7-468c-b949-2996385520da', '3e1c5340-3c57-4344-ac72-d0da0687f11f', 'c9a9f0ee-8dab-11f1-a4cf-1062e5a5cd6c', '101', 2, 1, 699.00, 15000.00, 1, 1, 1, 'available', 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `room_types`
--

CREATE TABLE `room_types` (
  `id` char(36) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `max_occupancy` int(11) DEFAULT 1,
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_types`
--

INSERT INTO `room_types` (`id`, `name`, `slug`, `max_occupancy`, `is_active`, `version`, `created_at`, `updated_at`, `deleted_at`) VALUES
('b3e3f040-9871-4bce-ba6a-bca2e02b8ae2', 'Four Sharing', 'four', 4, 1, 1, '2026-08-22 04:55:18', '2026-08-22 04:55:18', NULL),
('c9a9eeb7-8dab-11f1-a4cf-1062e5a5cd6c', 'Single', 'single', 1, 1, 1, '2026-08-01 13:20:38', '2026-08-01 13:20:38', NULL),
('c9a9f0ee-8dab-11f1-a4cf-1062e5a5cd6c', 'Double Sharing', 'double', 2, 1, 1, '2026-08-01 13:20:38', '2026-08-01 13:20:38', NULL),
('c9a9f1f4-8dab-11f1-a4cf-1062e5a5cd6c', 'Triple Sharing', 'triple', 3, 1, 1, '2026-08-01 13:20:38', '2026-08-01 13:20:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` varchar(36) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('42JZaVoo5RfB7J5ufPtmoNZEcvjuOihIEB0kaFfp', '63576b41-af34-452b-b4a5-2b603bda3fe6', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiM3VCWjdSMVNRSlc2V1VoNjVsMG5JY1c3NEhkTFBld2pTUmFabENyYyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO3M6MzY6IjYzNTc2YjQxLWFmMzQtNDUyYi1iNGE1LTJiNjAzYmRhM2ZlNiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1787497045),
('jAWP7TPpdQxVLujQ8166cHevsZGX8vM9D23HyZwc', '63576b41-af34-452b-b4a5-2b603bda3fe6', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNzNlVlhmVnBubmpoaE0yakhCaGw3Q2VTSm9YVWlQMU1CdXV4dmdvWSI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO3M6MzY6IjYzNTc2YjQxLWFmMzQtNDUyYi1iNGE1LTJiNjAzYmRhM2ZlNiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC8ud2VsbC1rbm93bi9hcHBzcGVjaWZpYy9jb20uY2hyb21lLmRldnRvb2xzLmpzb24iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1787488636);

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` char(36) NOT NULL,
  `country_id` char(36) NOT NULL,
  `code` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `country_id`, `code`, `name`, `type`, `is_active`, `version`, `created_at`, `updated_at`, `deleted_at`) VALUES
('17e096cd-8ab6-4f2a-9fbc-7bfd54d5468f', 'c9ac8db1-8dab-11f1-a4cf-1062e5a5cd6c', 'AP', 'Andhra Pradesh', NULL, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('2589d0a3-0fe5-45a4-b0cb-696acbf20d9d', 'c9ac8db1-8dab-11f1-a4cf-1062e5a5cd6c', 'HR', 'Haryana', NULL, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('26a49e1d-45b1-413f-9a07-9da80d02c11f', 'c9ac8db1-8dab-11f1-a4cf-1062e5a5cd6c', 'UP', 'Uttar Pradesh', NULL, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('2a92cf3f-c8ff-42a8-b9f7-16b0b48b6d0f', 'c9ac8db1-8dab-11f1-a4cf-1062e5a5cd6c', 'KL', 'Kerala', NULL, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('2d29f2fa-fcec-451f-ba64-efa21e0c232b', 'c9ac8db1-8dab-11f1-a4cf-1062e5a5cd6c', 'UK', 'Uttarakhand', NULL, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('4ac23b71-f140-4bff-be23-5004d5278081', 'c9ac8db1-8dab-11f1-a4cf-1062e5a5cd6c', 'BR', 'Bihar', NULL, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('4bf5bab8-7fcc-4946-9f07-2f2b81120791', 'c9ac8db1-8dab-11f1-a4cf-1062e5a5cd6c', 'OD', 'Odisha', NULL, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('5290d3e2-3a5a-4b6e-af53-23edf204e86b', 'c9ac8db1-8dab-11f1-a4cf-1062e5a5cd6c', 'WB', 'West Bengal', NULL, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('53d7471e-5ecb-4e2c-8fd1-434451fa7c72', 'c9ac8db1-8dab-11f1-a4cf-1062e5a5cd6c', 'AS', 'Assam', NULL, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('5b0d73f1-9040-4e30-81d3-4db2aa7fce78', 'c9ac8db1-8dab-11f1-a4cf-1062e5a5cd6c', 'MH', 'Maharashtra', NULL, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('5c36523c-8757-4a05-9468-a902f8c8f001', 'c9ac8db1-8dab-11f1-a4cf-1062e5a5cd6c', 'JH', 'Jharkhand', NULL, 1, 1, '2026-08-16 22:29:52', '2026-08-16 22:29:52', NULL),
('5f8ce97d-9d5b-421b-ad4f-7c9e9e1ec53f', 'c9ac8db1-8dab-11f1-a4cf-1062e5a5cd6c', 'GJ', 'Gujarat', NULL, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('6a460256-4af0-49ae-b46b-1230a62091d8', 'c9ac8db1-8dab-11f1-a4cf-1062e5a5cd6c', 'GA', 'Goa', NULL, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('6b4a5170-9aa2-48ec-ab6c-2528792c6bde', 'c9ac8db1-8dab-11f1-a4cf-1062e5a5cd6c', 'PB', 'Punjab', NULL, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('7ab8f1a9-d78b-4ab2-8d5f-cacdb3eb9a55', 'c9ac8db1-8dab-11f1-a4cf-1062e5a5cd6c', 'TS', 'Telangana', NULL, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('8981acff-6a7f-4c26-84d1-aef0f7f43756', 'c9ac8db1-8dab-11f1-a4cf-1062e5a5cd6c', 'CG', 'Chhattisgarh', NULL, 1, 1, '2026-08-16 22:29:52', '2026-08-16 22:29:52', NULL),
('c9adb837-8dab-11f1-a4cf-1062e5a5cd6c', 'c9ac8db1-8dab-11f1-a4cf-1062e5a5cd6c', 'KA', 'Karnataka', NULL, 1, 1, '2026-08-01 13:20:38', '2026-08-01 13:20:38', NULL),
('c9adba43-8dab-11f1-a4cf-1062e5a5cd6c', 'c9ac8db1-8dab-11f1-a4cf-1062e5a5cd6c', 'DL', 'Delhi', NULL, 1, 1, '2026-08-01 13:20:38', '2026-08-01 13:20:38', NULL),
('cd14a46c-d429-4de8-816e-6ca998c96c1b', 'c9ac8db1-8dab-11f1-a4cf-1062e5a5cd6c', 'RJ', 'Rajasthan', NULL, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('e24c447e-b8aa-4bf6-9bb5-007f2adcf752', 'c9ac8db1-8dab-11f1-a4cf-1062e5a5cd6c', 'TN', 'Tamil Nadu', NULL, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL),
('f2e3460d-e200-42c8-a36f-603aa9d061f8', 'c9ac8db1-8dab-11f1-a4cf-1062e5a5cd6c', 'MP', 'Madhya Pradesh', NULL, 1, 1, '2026-08-16 22:29:51', '2026-08-16 22:29:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `timezones`
--

CREATE TABLE `timezones` (
  `id` char(36) NOT NULL,
  `name` varchar(100) NOT NULL,
  `offset_val` varchar(10) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` char(36) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `phone_verified_at` timestamp NULL DEFAULT NULL,
  `kyc_verified_at` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive','suspended','pending_verification','deleted') DEFAULT 'pending_verification',
  `relationship_manager_id` char(36) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `updated_by` char(36) DEFAULT NULL,
  `deleted_by` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `phone`, `password_hash`, `remember_token`, `email_verified_at`, `phone_verified_at`, `kyc_verified_at`, `status`, `relationship_manager_id`, `is_active`, `version`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`) VALUES
('035e8b11-9be9-402a-a986-f95e4aea9fe9', 'adminer@staynest.com', '9044032140', '$2y$12$1nYV9ogIa4r7MWSiwP1oPuwafJf8WHOMjqiNm/lTbPqoagE4F2uLC', NULL, NULL, NULL, NULL, 'active', NULL, 1, 1, '2026-08-22 02:55:53', '2026-08-22 04:37:40', NULL, NULL, NULL, NULL),
('2d28d9da-2144-487d-997e-68b5624706f1', 'ankit.tenant@gmail.com', '+919876510005', '$2y$12$3KOvbtJh67y.2AQbDgJKwer8ebtGGFkoXYan0mcw8WqM9v9GXKkRK', NULL, NULL, NULL, NULL, 'active', NULL, 1, 1, '2026-08-16 22:47:03', '2026-08-19 22:00:29', NULL, NULL, NULL, NULL),
('5cb05e6c-56b8-4a6f-844b-c8437ecea822', 'vikram.pg@staynest.com', '9876543210', '$2y$12$HPCFqrPk0DxHqpKzogouGeB0mR5wrHEUlQWgjc2qhtmhoIPj35PfO', NULL, NULL, NULL, NULL, 'active', 'cb79301d-747b-439d-a95f-70237e252ac3', 1, 1, '2026-08-16 09:07:06', '2026-08-17 21:12:22', NULL, NULL, NULL, NULL),
('63576b41-af34-452b-b4a5-2b603bda3fe6', 'rajesh.sharma@staynest.com', '9876500002', '$2y$12$sMLdzVERnzo1Ke/5okj5FOHILzUyJ1tGM.Cq56uBBdOglh/Dw7g/.', 'r7NeyhuMeyH9RsRRiJuFovubwbDhwfvt5TvBxyr1IfNt73YAMZQLzZbef0Nl', NULL, NULL, '2026-08-17 21:21:10', 'active', 'cb79301d-747b-439d-a95f-70237e252ac3', 1, 1, '2026-08-16 22:17:41', '2026-08-22 06:37:13', NULL, NULL, NULL, NULL),
('67d8bdcd-4ad7-4d0b-b85f-25a9afa82499', 'rohan.tenant@gmail.com', '+919855667788', '$2y$12$k0lJvonIS7so6ZI.h0A3aOEgFTPtKaI739Ao0lQYlhxJU/eVbLcQm', NULL, NULL, NULL, NULL, 'active', NULL, 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('69efac93-4196-4e7e-b97b-2bf8b4e9af85', 'landlord_1787393037@staynest.com', '9811122233', '$2y$12$rQfN0ZEIpyiDWKiGYn4sjelSZbN.SRgczH2T2Tkzt5koXFmUVLngG', NULL, NULL, NULL, NULL, 'active', NULL, 1, 1, '2026-08-22 04:33:58', '2026-08-22 04:50:29', '2026-08-22 04:50:29', NULL, NULL, NULL),
('6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', 'imrishi6@gmail.com', '9044032145', '$2y$12$HnEJLd2Ks4V6lq1cZ6YF3.nl4ItBy6VEBwBmBev5mZWfKfvgqBBLK', NULL, NULL, NULL, NULL, 'active', NULL, 1, 1, '2026-08-16 07:55:26', '2026-08-16 08:21:51', NULL, NULL, NULL, NULL),
('6a74294b-2c66-4f6b-b4de-bee2e365fa6b', 'owner_1786894154@staynest.com', '9876543211', '$2y$12$ATaH1N1K6ihtilKKZlklLu/iTe9lvN64XzUP7FRWY.mf8EDDHl/mW', NULL, NULL, NULL, NULL, 'active', 'cb79301d-747b-439d-a95f-70237e252ac3', 1, 1, '2026-08-16 09:59:14', '2026-08-17 21:12:22', NULL, NULL, NULL, NULL),
('77a10f6a-c120-405c-bca9-f95341a44a15', 'imrishi@gmail.com', '+919711424515', '$2y$12$rTJLh9s9ltUySL3h6kV2M.UH9Lq0Tp8WjO1frFw7UFT4F3q4Vdy3m', NULL, NULL, NULL, NULL, 'active', NULL, 1, 1, '2026-08-18 12:47:44', '2026-08-19 20:35:32', NULL, NULL, NULL, NULL),
('923062d4-d28d-46a7-a8b3-0ec3424b1a6b', 'ananya.tenant@gmail.com', '+919866778899', '$2y$12$FzjY5kn3EhlfvQKVJC76E.PLkbrBLVXwLRzJYvxHHAbe7RO8Osnzu', NULL, NULL, NULL, NULL, 'active', NULL, 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('a0ea13d9-49a7-4e08-b364-7f0f00b97495', 'ramesh.gupta@example.com', '+919876543299', '$2y$12$34DoXlHZxOO7GgDxdCqR8.GIGoS9Qpm5Kolm9Hh66nZhZH7I1ZKoe', NULL, NULL, NULL, NULL, 'active', 'cb79301d-747b-439d-a95f-70237e252ac3', 1, 1, '2026-08-16 08:59:52', '2026-08-17 21:12:22', NULL, NULL, NULL, NULL),
('b008ff33-1473-40b0-ba25-6e3c718f0680', 'imrishi10@gmail.com', '+919044032145', '$2y$12$BrLUsOrfjmM954tzL06f1.6jLHGGx32Lx7UtUeRYwfUgLItL7zVKO', NULL, NULL, NULL, NULL, 'active', NULL, 1, 1, '2026-08-16 07:59:31', '2026-08-16 07:59:31', NULL, NULL, NULL, NULL),
('b9585777-9009-44f4-a3f7-e316abbaf0d2', 'priya.tenant@gmail.com', '+919822334455', '$2y$12$JLWdiOwFV6iqkHZhfHdBYun.I/yza0r.ZfKRngq5V9eAHaapBOCXO', NULL, NULL, NULL, NULL, 'active', NULL, 1, 1, '2026-08-16 22:17:42', '2026-08-16 22:17:42', NULL, NULL, NULL, NULL),
('bd238724-7aee-4107-8833-384acfcfb0be', 'rahul.tenant@gmail.com', '+919811223344', '$2y$12$K07iY7rQs78Z99mQZ5DsBeavrCPOgtVQl/U1cNzTKd29u93xOJoT6', NULL, NULL, NULL, NULL, 'active', NULL, 1, 1, '2026-08-16 22:17:42', '2026-08-17 21:16:27', NULL, NULL, NULL, NULL),
('be8170ed-18c1-47fd-8313-da286315bc89', 'neha.patel@staynest.com', '+919876500001', '$2y$12$EWZjRKEDyvhYSr33YVtRYOwzBndJLQhpq/pYZLDuLgpE7NHh111cm', NULL, NULL, NULL, NULL, 'active', 'cb79301d-747b-439d-a95f-70237e252ac3', 1, 1, '2026-08-16 22:17:41', '2026-08-22 04:50:57', '2026-08-22 04:50:57', NULL, NULL, NULL),
('c9098d68-9d79-421f-a1f5-83fc4ec8fbda', 'amit.tenant@gmail.com', '+919833445566', '$2y$12$jcJTGhI3nMyd0jcXVqtY0ep83PJOx82EekxH0Q46cMZMdOPbVtua2', NULL, NULL, NULL, NULL, 'active', NULL, 1, 1, '2026-08-16 22:17:42', '2026-08-16 22:17:42', NULL, NULL, NULL, NULL),
('c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'admin@staynest.com', '+919876543210', '$2y$12$IF97WQt9Fp5/ozHE7YFZgeov.wZ8u5KBmE/aQSon2fhWVL8b1Shl.', 'opPSA7j9RsSrM7gbENzOaPZJ1UPZZZEf9JKQy2250paVrptnw9FT3vlH2uSN', '2026-08-01 13:20:38', NULL, NULL, 'active', NULL, 1, 1, '2026-08-01 13:20:38', '2026-08-22 08:24:55', NULL, NULL, NULL, NULL),
('c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', 'vikram@broker.com', '+919876543211', '$2y$12$MT9Cx3R7hczGYxIpD575sOvbgTJP6ZIIOdBwQZJ1KNKqkEFl3sNiK', 'szTT3ockR0NA8kLnStMFwlpSQ8PYhB3x01V8t6goLAMujcXirrlmmZIJkDwO', '2026-08-01 13:20:38', NULL, '2026-08-16 22:44:56', 'active', 'cb79301d-747b-439d-a95f-70237e252ac3', 1, 1, '2026-08-01 13:20:38', '2026-08-18 02:54:11', NULL, NULL, NULL, NULL),
('dc12b8a0-fcec-4a52-b96f-55f58e7b5fe9', 'anil.kumar@staynest.com', '+919876500003', '$2y$12$TBd9n/BuViLpqZpPMU/O.OXnPpK2UTcer1.Ena8dPvM2EUkXedGxq', NULL, NULL, NULL, '2026-08-16 22:17:41', 'active', '55d42e3e-bcf5-4ad7-bd7d-0e1a4f86ee42', 1, 1, '2026-08-16 22:17:41', '2026-08-18 02:31:43', '2026-08-16 22:37:34', NULL, NULL, NULL),
('e62e7b98-e262-4c3c-a366-2989f5b87bbe', 'sneha.tenant@gmail.com', '+919844556677', '$2y$12$r3WfMLZ08YJtbCI05OCVi.fkofPPZYGug7G8mblKPxpcQqnZfxC8.', NULL, NULL, NULL, NULL, 'active', NULL, 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_activity_logs`
--

CREATE TABLE `user_activity_logs` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `user_id` char(36) NOT NULL,
  `action` varchar(100) NOT NULL,
  `entity_type` varchar(50) DEFAULT NULL,
  `entity_id` char(36) DEFAULT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `user_id` char(36) NOT NULL,
  `type` enum('current','permanent','office') NOT NULL,
  `label` varchar(50) DEFAULT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `landmark` varchar(255) DEFAULT NULL,
  `country_id` char(36) DEFAULT NULL,
  `state_id` char(36) DEFAULT NULL,
  `city_id` char(36) DEFAULT NULL,
  `area_id` char(36) DEFAULT NULL,
  `pincode` varchar(10) NOT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `is_verified` tinyint(1) DEFAULT 0,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `updated_by` char(36) DEFAULT NULL,
  `deleted_by` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_devices`
--

CREATE TABLE `user_devices` (
  `id` char(36) NOT NULL,
  `user_id` char(36) NOT NULL,
  `device_id` varchar(255) NOT NULL,
  `device_type` varchar(50) DEFAULT NULL,
  `os` varchar(50) DEFAULT NULL,
  `browser` varchar(50) DEFAULT NULL,
  `fcm_token` varchar(500) DEFAULT NULL,
  `is_trusted` tinyint(1) DEFAULT 0,
  `last_seen_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_documents`
--

CREATE TABLE `user_documents` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `user_id` char(36) NOT NULL,
  `document_type_id` char(36) NOT NULL,
  `document_number` varchar(100) NOT NULL,
  `document_url` varchar(500) NOT NULL,
  `document_back_url` varchar(500) DEFAULT NULL,
  `thumbnail_url` varchar(500) DEFAULT NULL,
  `issued_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `issuing_authority` varchar(200) DEFAULT NULL,
  `verification_status` enum('pending','in_review','verified','rejected','expired') DEFAULT 'pending',
  `is_primary` tinyint(1) DEFAULT 0,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `updated_by` char(36) DEFAULT NULL,
  `deleted_by` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `user_id` char(36) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `full_name` varchar(200) GENERATED ALWAYS AS (trim(concat(`first_name`,' ',coalesce(`last_name`,'')))) STORED,
  `avatar_url` varchar(500) DEFAULT NULL,
  `gender_id` char(36) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `occupation_id` char(36) DEFAULT NULL,
  `company_name` varchar(200) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `preferences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`preferences`)),
  `notification_settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`notification_settings`)),
  `language_id` char(36) DEFAULT NULL,
  `timezone_id` char(36) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `updated_by` char(36) DEFAULT NULL,
  `deleted_by` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`user_id`, `first_name`, `last_name`, `avatar_url`, `gender_id`, `date_of_birth`, `occupation_id`, `company_name`, `bio`, `preferences`, `notification_settings`, `language_id`, `timezone_id`, `is_active`, `version`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`) VALUES
('035e8b11-9be9-402a-a986-f95e4aea9fe9', 'test', 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-08-22 02:55:53', '2026-08-22 02:55:53', NULL, NULL, NULL, NULL),
('2d28d9da-2144-487d-997e-68b5624706f1', 'Updated', 'Owner Name', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-08-16 22:47:03', '2026-08-18 11:08:38', NULL, NULL, NULL, NULL),
('5cb05e6c-56b8-4a6f-844b-c8437ecea822', 'Rahul', 'Admin', NULL, NULL, NULL, NULL, NULL, NULL, '{\"relationship_manager_id\":\"cb79301d-747b-439d-a95f-70237e252ac3\",\"relationship_manager_name\":\"Karan Kapoor\"}', NULL, NULL, NULL, 1, 1, '2026-08-16 09:07:06', '2026-08-18 22:02:43', NULL, NULL, NULL, NULL),
('63576b41-af34-452b-b4a5-2b603bda3fe6', 'Rajesh', 'Sharma', NULL, NULL, NULL, NULL, NULL, NULL, '{\"relationship_manager_id\":\"cb79301d-747b-439d-a95f-70237e252ac3\",\"relationship_manager_name\":\"Karan Kapoor\"}', NULL, NULL, NULL, 1, 1, '2026-08-16 22:17:41', '2026-08-17 21:11:18', NULL, NULL, NULL, NULL),
('67d8bdcd-4ad7-4d0b-b85f-25a9afa82499', 'Rohan', 'Mehta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('69efac93-4196-4e7e-b97b-2bf8b4e9af85', 'Landlord', 'Test', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-08-22 04:33:58', '2026-08-22 04:33:58', NULL, NULL, NULL, NULL),
('6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', 'test', 'user', NULL, NULL, NULL, NULL, NULL, 'Looking for clean, quiet verified stays near tech parks with 3 meals & high-speed WiFi.', NULL, NULL, NULL, NULL, 1, 1, '2026-08-16 07:55:26', '2026-08-16 08:11:45', NULL, NULL, NULL, NULL),
('6a74294b-2c66-4f6b-b4de-bee2e365fa6b', 'Amit', 'Kumar', NULL, NULL, NULL, NULL, NULL, NULL, '{\"relationship_manager_id\":\"cb79301d-747b-439d-a95f-70237e252ac3\",\"relationship_manager_name\":\"Karan Kapoor\"}', NULL, NULL, NULL, 1, 1, '2026-08-16 09:59:14', '2026-08-17 21:12:22', NULL, NULL, NULL, NULL),
('77a10f6a-c120-405c-bca9-f95341a44a15', 'rishikesh', 'jadaun', NULL, NULL, NULL, NULL, NULL, NULL, '{\"saved_properties\":[{\"id\":\"2ce06724-f6aa-4b6d-b12d-df72a3492eb9\",\"slug\":\"royal-comfort-pg\",\"title\":\"Royal Comfort PG\",\"price\":\"\\u20b98,500\",\"image\":\"https:\\/\\/images.unsplash.com\\/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80\",\"location\":\"108, 12th Main, Indiranagar, Bangalore\",\"type\":\"BOYS\"},{\"id\":\"5c0e35ff-2063-4602-9cf6-ca0bbc036e8e\",\"slug\":\"test-user\",\"title\":\"test user\",\"price\":\"\\u20b95,690\",\"image\":\"\\/uploads\\/properties\\/prop_5c0e35ff_1787069244_0.jpeg\",\"location\":\"Dadri, Noida, Noida\",\"type\":\"BOYS\"}]}', NULL, NULL, NULL, 1, 1, '2026-08-18 12:47:44', '2026-08-19 21:05:37', NULL, NULL, NULL, NULL),
('923062d4-d28d-46a7-a8b3-0ec3424b1a6b', 'Ananya', 'Joshi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL),
('a0ea13d9-49a7-4e08-b364-7f0f00b97495', 'Ramesh', 'Gupta', NULL, NULL, NULL, NULL, NULL, NULL, '{\"relationship_manager_id\":\"cb79301d-747b-439d-a95f-70237e252ac3\",\"relationship_manager_name\":\"Karan Kapoor\"}', NULL, NULL, NULL, 1, 1, '2026-08-16 08:59:52', '2026-08-17 21:12:22', NULL, NULL, NULL, NULL),
('b008ff33-1473-40b0-ba25-6e3c718f0680', 'rishikesh', 'jadaun', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-08-16 07:59:31', '2026-08-16 07:59:31', NULL, NULL, NULL, NULL),
('b9585777-9009-44f4-a3f7-e316abbaf0d2', 'Priya', 'Patel', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-08-16 22:17:42', '2026-08-16 22:17:42', NULL, NULL, NULL, NULL),
('bd238724-7aee-4107-8833-384acfcfb0be', 'Rahul', 'Sharma', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-08-16 22:17:42', '2026-08-16 22:17:42', NULL, NULL, NULL, NULL),
('be8170ed-18c1-47fd-8313-da286315bc89', 'Neha', 'Patel', NULL, NULL, NULL, NULL, NULL, NULL, '{\"relationship_manager_id\":\"cb79301d-747b-439d-a95f-70237e252ac3\",\"relationship_manager_name\":\"Karan Kapoor\"}', NULL, NULL, NULL, 1, 1, '2026-08-16 22:17:41', '2026-08-17 21:12:22', NULL, NULL, NULL, NULL),
('c9098d68-9d79-421f-a1f5-83fc4ec8fbda', 'Amit', 'Verma', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-08-16 22:17:42', '2026-08-16 22:17:42', NULL, NULL, NULL, NULL),
('c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'Rahul', 'Admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-08-01 13:20:38', '2026-08-01 13:20:38', NULL, NULL, NULL, NULL),
('c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', 'Vikrama', 'Singh', NULL, NULL, NULL, NULL, 'Singh Real Estate & PG Management', 'Experienced PG & Co-living space partner managing premium student and executive stays in Noida and Bangalore.', '{\"office_address\":\"Tower B, 4th Floor, Sector 62, Noida, UP 201309\",\"operating_city\":\"Noida\",\"operating_area\":\"Sector 62, Electronic City\",\"gstin\":\"09AAAAA0000A1Z5\",\"rera_number\":\"UPRERAAGT12490\",\"bank_details\":{\"account_holder_name\":\"Vikram Singh\",\"bank_name\":\"HDFC Bank\",\"account_number\":\"50100234567890\",\"ifsc_code\":\"HDFC0001234\",\"account_type\":\"savings\",\"upi_id\":\"vikram@hdfcbank\",\"updated_at\":\"2026-08-18T02:31:00+00:00\"},\"documents\":{\"id_proof\":{\"name\":\"Security_Guard_App_Proposal.pdf\",\"file_path\":\"\\/uploads\\/broker_docs\\/id_proof_c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c_1787020284.pdf\",\"doc_number\":\"rwertetty\",\"status\":\"pending_review\",\"uploaded_at\":\"2026-08-18 02:31:24\"}},\"relationship_manager_id\":\"cb79301d-747b-439d-a95f-70237e252ac3\",\"relationship_manager_name\":\"Karan Kapoor\"}', '{\"whatsapp_alerts\":true,\"sms_alerts\":true,\"email_statements\":true,\"inquiry_alerts\":true,\"payment_alerts\":true,\"marketing_updates\":false}', NULL, NULL, 1, 1, '2026-08-01 13:20:38', '2026-08-17 21:12:22', NULL, NULL, NULL, NULL),
('dc12b8a0-fcec-4a52-b96f-55f58e7b5fe9', 'Anil', 'Kumar', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-08-16 22:17:41', '2026-08-16 22:17:41', NULL, NULL, NULL, NULL),
('e62e7b98-e262-4c3c-a366-2989f5b87bbe', 'Sneha', 'Reddy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-08-16 22:17:43', '2026-08-16 22:17:43', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` char(36) NOT NULL,
  `user_id` char(36) NOT NULL,
  `role_id` char(36) NOT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `expires_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `user_id`, `role_id`, `is_primary`, `expires_at`, `is_active`, `created_at`, `created_by`) VALUES
('0b74e0c5-a2e2-47ae-a554-8da3ecf972e6', '69efac93-4196-4e7e-b97b-2bf8b4e9af85', 'c9b10005-8dab-11f1-a4cf-1062e5a5cd6c', 1, NULL, 1, '2026-08-22 04:33:58', NULL),
('1c2099b4-2e79-45be-8ab9-5e9882ad9638', '63576b41-af34-452b-b4a5-2b603bda3fe6', 'c9b10005-8dab-11f1-a4cf-1062e5a5cd6c', 1, NULL, 1, '2026-08-16 22:17:41', NULL),
('1d705cc2-12cf-4f24-b4ac-eebc8be0ab81', '923062d4-d28d-46a7-a8b3-0ec3424b1a6b', 'c9b100f2-8dab-11f1-a4cf-1062e5a5cd6c', 1, NULL, 1, '2026-08-16 22:17:43', NULL),
('3755903d-72a3-4df9-9d6b-1318913dca47', '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', 'c9b100f2-8dab-11f1-a4cf-1062e5a5cd6c', 1, NULL, 1, '2026-08-16 07:55:26', NULL),
('79813fb2-ea64-48c6-990b-a09722bebe25', 'e62e7b98-e262-4c3c-a366-2989f5b87bbe', 'c9b100f2-8dab-11f1-a4cf-1062e5a5cd6c', 1, NULL, 1, '2026-08-16 22:17:43', NULL),
('867657d8-a6ad-441c-967e-dcd18ff8aae4', '77a10f6a-c120-405c-bca9-f95341a44a15', 'c9b100f2-8dab-11f1-a4cf-1062e5a5cd6c', 1, NULL, 1, '2026-08-18 12:47:44', NULL),
('92396deb-1a8f-4485-a0a8-f421c0964579', 'b008ff33-1473-40b0-ba25-6e3c718f0680', 'c9b100f2-8dab-11f1-a4cf-1062e5a5cd6c', 1, NULL, 1, '2026-08-16 07:59:31', NULL),
('9e8079ee-9898-455f-8c83-8ef37a81d138', '6a74294b-2c66-4f6b-b4de-bee2e365fa6b', 'c9b10005-8dab-11f1-a4cf-1062e5a5cd6c', 1, NULL, 1, '2026-08-16 09:59:14', NULL),
('b5393dd6-2b6f-492f-b37a-51751410cf00', '5cb05e6c-56b8-4a6f-844b-c8437ecea822', 'c9b10005-8dab-11f1-a4cf-1062e5a5cd6c', 1, NULL, 1, '2026-08-16 09:07:06', NULL),
('b9a73b79-a2cc-4b08-9c45-f428325e3ed2', 'b9585777-9009-44f4-a3f7-e316abbaf0d2', 'c9b100f2-8dab-11f1-a4cf-1062e5a5cd6c', 1, NULL, 1, '2026-08-16 22:17:42', NULL),
('baae6057-f2db-48a5-b687-7222fd5bbb22', 'bd238724-7aee-4107-8833-384acfcfb0be', 'c9b100f2-8dab-11f1-a4cf-1062e5a5cd6c', 1, NULL, 1, '2026-08-16 22:17:42', NULL),
('bc09d882-a653-445e-a1ef-521d92bbbee7', '035e8b11-9be9-402a-a986-f95e4aea9fe9', 'c9b100f2-8dab-11f1-a4cf-1062e5a5cd6c', 1, NULL, 1, '2026-08-22 02:55:53', NULL),
('bf39f673-9c58-431e-b761-66b01fe9892d', '67d8bdcd-4ad7-4d0b-b85f-25a9afa82499', 'c9b100f2-8dab-11f1-a4cf-1062e5a5cd6c', 1, NULL, 1, '2026-08-16 22:17:43', NULL),
('c11fc553-1163-49a5-a5c9-614e324db30e', 'a0ea13d9-49a7-4e08-b364-7f0f00b97495', 'c9b10005-8dab-11f1-a4cf-1062e5a5cd6c', 1, NULL, 1, '2026-08-16 08:59:52', NULL),
('c9b4cdfc-8dab-11f1-a4cf-1062e5a5cd6c', 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', 'c9b0fd91-8dab-11f1-a4cf-1062e5a5cd6c', 1, NULL, 1, '2026-08-01 13:20:38', NULL),
('c9b755a2-8dab-11f1-a4cf-1062e5a5cd6c', 'c9b54fc4-8dab-11f1-a4cf-1062e5a5cd6c', 'c9b10005-8dab-11f1-a4cf-1062e5a5cd6c', 1, NULL, 1, '2026-08-01 13:20:38', NULL),
('e35321f3-e9c7-4b48-9f0a-2d3ed8440b13', 'dc12b8a0-fcec-4a52-b96f-55f58e7b5fe9', 'c9b10005-8dab-11f1-a4cf-1062e5a5cd6c', 1, NULL, 1, '2026-08-16 22:17:41', NULL),
('e8f1c3da-300d-4bf0-9acf-1d568abdfa1e', 'be8170ed-18c1-47fd-8313-da286315bc89', 'c9b10005-8dab-11f1-a4cf-1062e5a5cd6c', 1, NULL, 1, '2026-08-16 22:17:41', NULL),
('f09b2d2b-e192-4e98-bc1f-159a16472f91', 'c9098d68-9d79-421f-a1f5-83fc4ec8fbda', 'c9b100f2-8dab-11f1-a4cf-1062e5a5cd6c', 1, NULL, 1, '2026-08-16 22:17:42', NULL),
('f98ae59c-93d8-4ab6-98d1-1ac37e329b5c', '2d28d9da-2144-487d-997e-68b5624706f1', 'c9b100f2-8dab-11f1-a4cf-1062e5a5cd6c', 1, NULL, 1, '2026-08-16 22:47:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--

CREATE TABLE `user_sessions` (
  `id` char(36) NOT NULL,
  `user_id` char(36) NOT NULL,
  `session_token` varchar(255) NOT NULL,
  `device_id` char(36) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `login_method` varchar(50) DEFAULT 'password',
  `fcm_token` varchar(500) DEFAULT NULL,
  `refresh_count` int(11) DEFAULT 0,
  `last_refresh_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_revoked` tinyint(1) DEFAULT 0,
  `login_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `logout_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_activity_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `verification_history`
--

CREATE TABLE `verification_history` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `document_id` char(36) NOT NULL,
  `from_status` varchar(20) DEFAULT NULL,
  `to_status` varchar(20) NOT NULL,
  `changed_by` char(36) DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `notes` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_active_properties`
-- (See below for the actual view)
--
CREATE TABLE `vw_active_properties` (
`id` char(36)
,`name` varchar(200)
,`slug` varchar(220)
,`monthly_rent` decimal(10,2)
,`rating` decimal(3,2)
,`status` enum('active','inactive','draft')
,`city_name` varchar(100)
,`area_name` varchar(150)
,`broker_name` varchar(200)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_location_hierarchy`
-- (See below for the actual view)
--
CREATE TABLE `vw_location_hierarchy` (
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_user_full_profile`
-- (See below for the actual view)
--
CREATE TABLE `vw_user_full_profile` (
`id` char(36)
,`email` varchar(150)
,`phone` varchar(20)
,`status` enum('active','inactive','suspended','pending_verification','deleted')
,`first_name` varchar(100)
,`last_name` varchar(100)
,`full_name` varchar(200)
,`avatar_url` varchar(500)
,`gender` varchar(50)
,`occupation` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_user_permissions`
-- (See below for the actual view)
--
CREATE TABLE `vw_user_permissions` (
`user_id` char(36)
,`email` varchar(150)
,`role_slug` varchar(60)
,`role_level` int(11)
,`permission_slug` varchar(120)
,`module` varchar(50)
,`is_primary` tinyint(1)
);

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` char(36) NOT NULL,
  `user_id` char(36) NOT NULL,
  `balance` decimal(12,2) DEFAULT 0.00,
  `currency_code` varchar(3) DEFAULT 'INR',
  `is_active` tinyint(1) DEFAULT 1,
  `version` int(10) UNSIGNED DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wallets`
--

INSERT INTO `wallets` (`id`, `user_id`, `balance`, `currency_code`, `is_active`, `version`, `created_at`, `updated_at`) VALUES
('41c01513-e806-444f-a7c0-1604dae4a010', '6a2d896b-b9fb-4ba6-88ee-4a82f88ce249', 0.00, 'INR', 1, 1, '2026-08-16 07:55:26', '2026-08-16 07:55:26'),
('5cbf5048-ad33-4d63-8fdb-9447b53e4782', 'b008ff33-1473-40b0-ba25-6e3c718f0680', 0.00, 'INR', 1, 1, '2026-08-16 07:59:31', '2026-08-16 07:59:31'),
('6d56330c-a0a7-4b2e-93a6-31845856bf8b', '035e8b11-9be9-402a-a986-f95e4aea9fe9', 0.00, 'INR', 1, 1, '2026-08-22 02:55:53', '2026-08-22 02:55:53'),
('aa02c7c3-8e77-4056-a3d6-24616fcf3df2', '77a10f6a-c120-405c-bca9-f95341a44a15', 0.00, 'INR', 1, 1, '2026-08-18 12:47:44', '2026-08-18 12:47:44');

-- --------------------------------------------------------

--
-- Table structure for table `wallet_transactions`
--

CREATE TABLE `wallet_transactions` (
  `id` char(36) NOT NULL,
  `wallet_id` char(36) NOT NULL,
  `type` enum('credit','debit') NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `reference_type` varchar(50) DEFAULT NULL,
  `reference_id` char(36) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `balance_after` decimal(12,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure for view `vw_active_properties`
--
DROP TABLE IF EXISTS `vw_active_properties`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_active_properties`  AS SELECT `p`.`id` AS `id`, `p`.`name` AS `name`, `p`.`slug` AS `slug`, `p`.`monthly_rent` AS `monthly_rent`, `p`.`rating` AS `rating`, `p`.`status` AS `status`, `c`.`name` AS `city_name`, `a`.`name` AS `area_name`, `u`.`full_name` AS `broker_name` FROM (((`properties` `p` join `cities` `c` on(`p`.`city_id` = `c`.`id`)) join `areas` `a` on(`p`.`area_id` = `a`.`id`)) join `user_profiles` `u` on(`p`.`broker_id` = `u`.`user_id`)) WHERE `p`.`deleted_at` is null AND `p`.`status` = 'active' ;

-- --------------------------------------------------------

--
-- Structure for view `vw_location_hierarchy`
--
DROP TABLE IF EXISTS `vw_location_hierarchy`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_location_hierarchy`  AS SELECT `co`.`id` AS `country_id`, `co`.`name` AS `country_name`, `co`.`code` AS `country_code`, `s`.`id` AS `state_id`, `s`.`name` AS `state_name`, `s`.`code` AS `state_code`, `ci`.`id` AS `city_id`, `ci`.`name` AS `city_name`, `ci`.`slug` AS `city_slug`, `ci`.`is_metro` AS `is_metro`, `ci`.`is_tier1` AS `is_tier1`, `ci`.`district` AS `district`, `ci`.`zone` AS `zone`, `ci`.`google_place_id` AS `google_place_id`, `ci`.`osm_id` AS `osm_id`, `ci`.`geohash` AS `geohash`, `ar`.`id` AS `area_id`, `ar`.`name` AS `area_name`, `ar`.`slug` AS `area_slug`, `ar`.`pincode` AS `pincode`, `ar`.`taluka` AS `taluka`, `ar`.`ward` AS `ward`, `ar`.`zone` AS `area_zone`, `ar`.`google_place_id` AS `area_google_place_id` FROM (((`countries` `co` join `states` `s` on(`s`.`country_id` = `co`.`id` and `s`.`deleted_at` is null)) join `cities` `ci` on(`ci`.`state_id` = `s`.`id` and `ci`.`deleted_at` is null)) left join `areas` `ar` on(`ar`.`city_id` = `ci`.`id` and `ar`.`deleted_at` is null)) WHERE `co`.`deleted_at` is null AND `co`.`is_active` = 1 ;

-- --------------------------------------------------------

--
-- Structure for view `vw_user_full_profile`
--
DROP TABLE IF EXISTS `vw_user_full_profile`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_user_full_profile`  AS SELECT `u`.`id` AS `id`, `u`.`email` AS `email`, `u`.`phone` AS `phone`, `u`.`status` AS `status`, `p`.`first_name` AS `first_name`, `p`.`last_name` AS `last_name`, `p`.`full_name` AS `full_name`, `p`.`avatar_url` AS `avatar_url`, `g`.`name` AS `gender`, `o`.`name` AS `occupation` FROM (((`users` `u` left join `user_profiles` `p` on(`u`.`id` = `p`.`user_id` and `p`.`deleted_at` is null)) left join `genders` `g` on(`p`.`gender_id` = `g`.`id`)) left join `occupations` `o` on(`p`.`occupation_id` = `o`.`id`)) WHERE `u`.`deleted_at` is null AND `u`.`is_active` = 1 ;

-- --------------------------------------------------------

--
-- Structure for view `vw_user_permissions`
--
DROP TABLE IF EXISTS `vw_user_permissions`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_user_permissions`  AS SELECT `ur`.`user_id` AS `user_id`, `u`.`email` AS `email`, `r`.`slug` AS `role_slug`, `r`.`level` AS `role_level`, `p`.`slug` AS `permission_slug`, `p`.`module` AS `module`, `ur`.`is_primary` AS `is_primary` FROM ((((`user_roles` `ur` join `users` `u` on(`ur`.`user_id` = `u`.`id` and `u`.`deleted_at` is null)) join `roles` `r` on(`ur`.`role_id` = `r`.`id` and `r`.`deleted_at` is null)) join `role_permissions` `rp` on(`r`.`id` = `rp`.`role_id`)) join `permissions` `p` on(`rp`.`permission_id` = `p`.`id` and `p`.`deleted_at` is null)) WHERE `ur`.`is_active` = 1 AND (`ur`.`expires_at` is null OR `ur`.`expires_at` > current_timestamp()) AND `r`.`is_active` = 1 AND `p`.`is_active` = 1 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `amenities`
--
ALTER TABLE `amenities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `api_tokens`
--
ALTER TABLE `api_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token_hash` (`token_hash`),
  ADD KEY `idx_tokens_user` (`user_id`),
  ADD KEY `idx_tokens_hash` (`token_hash`),
  ADD KEY `idx_tokens_prefix` (`token_prefix`),
  ADD KEY `idx_tokens_expires` (`expires_at`,`is_active`);

--
-- Indexes for table `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ar` (`city_id`,`slug`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `beds`
--
ALTER TABLE `beds`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_bed_num` (`room_id`,`bed_number`),
  ADD KEY `idx_bed_status` (`status`,`is_active`);

--
-- Indexes for table `bed_types`
--
ALTER TABLE `bed_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `blocks`
--
ALTER TABLE `blocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_id` (`booking_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `bed_id` (`bed_id`),
  ADD KEY `broker_id` (`broker_id`),
  ADD KEY `idx_bk_user` (`user_id`),
  ADD KEY `idx_bk_prop` (`property_id`),
  ADD KEY `idx_bk_status` (`booking_status`);

--
-- Indexes for table `booking_history`
--
ALTER TABLE `booking_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_bh_bk` (`booking_id`,`changed_at`);

--
-- Indexes for table `broker_payouts`
--
ALTER TABLE `broker_payouts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payout_id` (`payout_id`),
  ADD KEY `idx_payout_broker` (`broker_id`,`status`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ct` (`state_id`,`slug`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `complaint_id` (`complaint_id`),
  ADD KEY `idx_comp_prop` (`property_id`,`status`),
  ADD KEY `idx_comp_user` (`user_id`);

--
-- Indexes for table `complaint_images`
--
ALTER TABLE `complaint_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `complaint_id` (`complaint_id`);

--
-- Indexes for table `contact_inquiries`
--
ALTER TABLE `contact_inquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_inquiries_email_index` (`email`),
  ADD KEY `contact_inquiries_phone_index` (`phone`),
  ADD KEY `contact_inquiries_user_type_index` (`user_type`),
  ADD KEY `contact_inquiries_city_index` (`city`),
  ADD KEY `contact_inquiries_status_index` (`status`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD UNIQUE KEY `code2` (`code2`),
  ADD KEY `currency_id` (`currency_id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `daily_property_stats`
--
ALTER TABLE `daily_property_stats`
  ADD PRIMARY KEY (`date`,`property_id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `document_types`
--
ALTER TABLE `document_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `document_verifications`
--
ALTER TABLE `document_verifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_verifications_document` (`document_id`),
  ADD KEY `idx_verifications_verifier` (`verifier_id`),
  ADD KEY `idx_verifications_status` (`status`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `floors`
--
ALTER TABLE `floors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `block_id` (`block_id`);

--
-- Indexes for table `genders`
--
ALTER TABLE `genders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `idx_inv_user` (`user_id`,`status`),
  ADD KEY `idx_inv_due` (`due_date`,`status`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `leases`
--
ALTER TABLE `leases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_id` (`booking_id`),
  ADD UNIQUE KEY `lease_number` (`lease_number`),
  ADD KEY `idx_lease_status` (`status`);

--
-- Indexes for table `login_history`
--
ALTER TABLE `login_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_login_user` (`user_id`,`login_at`),
  ADD KEY `idx_login_status` (`status`),
  ADD KEY `idx_login_ip` (`ip_address`),
  ADD KEY `idx_login_method` (`login_method`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notif_user` (`user_id`,`is_read`,`created_at`);

--
-- Indexes for table `occupations`
--
ALTER TABLE `occupations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `organization_settings`
--
ALTER TABLE `organization_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_org_settings` (`organization_id`,`setting_key`),
  ADD KEY `idx_org_settings_org` (`organization_id`);

--
-- Indexes for table `organization_users`
--
ALTER TABLE `organization_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ou` (`organization_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `otp_verifications`
--
ALTER TABLE `otp_verifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_otp_rec` (`recipient`,`status`);

--
-- Indexes for table `password_history`
--
ALTER TABLE `password_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_password_user` (`user_id`,`changed_at`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_id` (`payment_id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `idx_pay_user` (`user_id`,`status`),
  ADD KEY `idx_pay_gateway` (`gateway_payment_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `platform_settings`
--
ALTER TABLE `platform_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `platform_settings_key_unique` (`key`),
  ADD KEY `platform_settings_group_index` (`group`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `organization_id` (`organization_id`),
  ADD KEY `area_id` (`area_id`),
  ADD KEY `property_type_id` (`property_type_id`),
  ADD KEY `idx_prop_city` (`city_id`,`status`),
  ADD KEY `idx_prop_broker` (`broker_id`),
  ADD KEY `properties_is_recommended_index` (`is_recommended`);

--
-- Indexes for table `property_amenities`
--
ALTER TABLE `property_amenities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_pa` (`property_id`,`amenity_id`),
  ADD KEY `amenity_id` (`amenity_id`);

--
-- Indexes for table `property_images`
--
ALTER TABLE `property_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pi_prop` (`property_id`,`is_primary`);

--
-- Indexes for table `property_reports`
--
ALTER TABLE `property_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_reports_property_id_index` (`property_id`),
  ADD KEY `property_reports_user_id_index` (`user_id`);

--
-- Indexes for table `property_rules`
--
ALTER TABLE `property_rules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `property_types`
--
ALTER TABLE `property_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `property_visits`
--
ALTER TABLE `property_visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_visit_prop` (`property_id`,`scheduled_at`);

--
-- Indexes for table `refresh_tokens`
--
ALTER TABLE `refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token_hash` (`token_hash`),
  ADD KEY `session_id` (`session_id`),
  ADD KEY `idx_refresh_user` (`user_id`),
  ADD KEY `idx_refresh_hash` (`token_hash`),
  ADD KEY `idx_refresh_expires` (`expires_at`,`is_active`);

--
-- Indexes for table `refunds`
--
ALTER TABLE `refunds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_id` (`payment_id`);

--
-- Indexes for table `relationship_managers`
--
ALTER TABLE `relationship_managers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `relationship_managers_email_unique` (`email`),
  ADD KEY `relationship_managers_is_active_index` (`is_active`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `idx_rev_prop` (`property_id`,`status`),
  ADD KEY `idx_rev_rating` (`rating`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_rp` (`role_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_room_num` (`floor_id`,`room_number`),
  ADD KEY `room_type_id` (`room_type_id`),
  ADD KEY `idx_room_status` (`status`,`is_active`);

--
-- Indexes for table `room_types`
--
ALTER TABLE `room_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_st` (`country_id`,`code`);

--
-- Indexes for table `timezones`
--
ALTER TABLE `timezones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `idx_users_email` (`email`,`deleted_at`),
  ADD KEY `idx_users_phone` (`phone`,`deleted_at`),
  ADD KEY `users_relationship_manager_id_index` (`relationship_manager_id`);

--
-- Indexes for table `user_activity_logs`
--
ALTER TABLE `user_activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_activity_user` (`user_id`,`created_at`),
  ADD KEY `idx_activity_action` (`action`),
  ADD KEY `idx_activity_entity` (`entity_type`,`entity_id`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `state_id` (`state_id`),
  ADD KEY `city_id` (`city_id`),
  ADD KEY `area_id` (`area_id`),
  ADD KEY `idx_addresses_user` (`user_id`,`deleted_at`),
  ADD KEY `idx_addresses_type` (`type`),
  ADD KEY `idx_addresses_default` (`user_id`,`is_default`);

--
-- Indexes for table `user_devices`
--
ALTER TABLE `user_devices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ud` (`user_id`,`device_id`);

--
-- Indexes for table `user_documents`
--
ALTER TABLE `user_documents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_documents` (`user_id`,`document_type_id`,`document_number`),
  ADD KEY `idx_documents_user` (`user_id`),
  ADD KEY `idx_documents_type` (`document_type_id`),
  ADD KEY `idx_documents_status` (`verification_status`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ur` (`user_id`,`role_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_token` (`session_token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `verification_history`
--
ALTER TABLE `verification_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `changed_by` (`changed_by`),
  ADD KEY `idx_verification_history_doc` (`document_id`,`changed_at`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_wt_wallet` (`wallet_id`,`created_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact_inquiries`
--
ALTER TABLE `contact_inquiries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `api_tokens`
--
ALTER TABLE `api_tokens`
  ADD CONSTRAINT `api_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `areas`
--
ALTER TABLE `areas`
  ADD CONSTRAINT `areas_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`);

--
-- Constraints for table `beds`
--
ALTER TABLE `beds`
  ADD CONSTRAINT `beds_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blocks`
--
ALTER TABLE `blocks`
  ADD CONSTRAINT `blocks_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`),
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`),
  ADD CONSTRAINT `bookings_ibfk_4` FOREIGN KEY (`bed_id`) REFERENCES `beds` (`id`),
  ADD CONSTRAINT `bookings_ibfk_5` FOREIGN KEY (`broker_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `booking_history`
--
ALTER TABLE `booking_history`
  ADD CONSTRAINT `booking_history_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `broker_payouts`
--
ALTER TABLE `broker_payouts`
  ADD CONSTRAINT `broker_payouts_ibfk_1` FOREIGN KEY (`broker_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `cities_ibfk_1` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`);

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `complaints_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `complaint_images`
--
ALTER TABLE `complaint_images`
  ADD CONSTRAINT `complaint_images_ibfk_1` FOREIGN KEY (`complaint_id`) REFERENCES `complaints` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `countries`
--
ALTER TABLE `countries`
  ADD CONSTRAINT `countries_ibfk_1` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `daily_property_stats`
--
ALTER TABLE `daily_property_stats`
  ADD CONSTRAINT `daily_property_stats_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `document_verifications`
--
ALTER TABLE `document_verifications`
  ADD CONSTRAINT `document_verifications_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `user_documents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `document_verifications_ibfk_2` FOREIGN KEY (`verifier_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `floors`
--
ALTER TABLE `floors`
  ADD CONSTRAINT `floors_ibfk_1` FOREIGN KEY (`block_id`) REFERENCES `blocks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoices_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `leases`
--
ALTER TABLE `leases`
  ADD CONSTRAINT `leases_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `login_history`
--
ALTER TABLE `login_history`
  ADD CONSTRAINT `login_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `organization_settings`
--
ALTER TABLE `organization_settings`
  ADD CONSTRAINT `organization_settings_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `organization_users`
--
ALTER TABLE `organization_users`
  ADD CONSTRAINT `organization_users_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `organization_users_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `otp_verifications`
--
ALTER TABLE `otp_verifications`
  ADD CONSTRAINT `otp_verifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `password_history`
--
ALTER TABLE `password_history`
  ADD CONSTRAINT `password_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `properties`
--
ALTER TABLE `properties`
  ADD CONSTRAINT `properties_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `properties_ibfk_2` FOREIGN KEY (`broker_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `properties_ibfk_3` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`),
  ADD CONSTRAINT `properties_ibfk_4` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`),
  ADD CONSTRAINT `properties_ibfk_5` FOREIGN KEY (`property_type_id`) REFERENCES `property_types` (`id`);

--
-- Constraints for table `property_amenities`
--
ALTER TABLE `property_amenities`
  ADD CONSTRAINT `property_amenities_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `property_amenities_ibfk_2` FOREIGN KEY (`amenity_id`) REFERENCES `amenities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `property_images`
--
ALTER TABLE `property_images`
  ADD CONSTRAINT `property_images_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `property_rules`
--
ALTER TABLE `property_rules`
  ADD CONSTRAINT `property_rules_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `property_visits`
--
ALTER TABLE `property_visits`
  ADD CONSTRAINT `property_visits_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `property_visits_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `refresh_tokens`
--
ALTER TABLE `refresh_tokens`
  ADD CONSTRAINT `refresh_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `refresh_tokens_ibfk_2` FOREIGN KEY (`session_id`) REFERENCES `user_sessions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `refunds`
--
ALTER TABLE `refunds`
  ADD CONSTRAINT `refunds_ibfk_1` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`floor_id`) REFERENCES `floors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rooms_ibfk_2` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`);

--
-- Constraints for table `states`
--
ALTER TABLE `states`
  ADD CONSTRAINT `states_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`);

--
-- Constraints for table `user_activity_logs`
--
ALTER TABLE `user_activity_logs`
  ADD CONSTRAINT `user_activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD CONSTRAINT `user_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_addresses_ibfk_2` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `user_addresses_ibfk_3` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `user_addresses_ibfk_4` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `user_addresses_ibfk_5` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_devices`
--
ALTER TABLE `user_devices`
  ADD CONSTRAINT `user_devices_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_documents`
--
ALTER TABLE `user_documents`
  ADD CONSTRAINT `user_documents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_documents_ibfk_2` FOREIGN KEY (`document_type_id`) REFERENCES `document_types` (`id`);

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `user_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD CONSTRAINT `user_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `verification_history`
--
ALTER TABLE `verification_history`
  ADD CONSTRAINT `verification_history_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `user_documents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `verification_history_ibfk_2` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `wallets`
--
ALTER TABLE `wallets`
  ADD CONSTRAINT `wallets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD CONSTRAINT `wallet_transactions_ibfk_1` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

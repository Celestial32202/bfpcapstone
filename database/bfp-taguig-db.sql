-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 25, 2025 at 11:46 AM
-- Server version: 10.11.11-MariaDB-0ubuntu0.24.04.2
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bfp-taguig-db`
--

-- --------------------------------------------------------

--
-- Table structure for table `accepted_fire_rescues`
--

CREATE TABLE `accepted_fire_rescues` (
  `id` int(11) NOT NULL,
  `rescue_details_id` int(11) NOT NULL,
  `incident_id` varchar(255) NOT NULL,
  `fire_officer` varchar(255) NOT NULL,
  `branch` varchar(255) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `time_accepted` datetime DEFAULT current_timestamp(),
  `status` enum('accepted','ongoing','arrived','returning','completed') NOT NULL DEFAULT 'accepted',
  `time_arrived` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accepted_fire_rescues`
--

INSERT INTO `accepted_fire_rescues` (`id`, `rescue_details_id`, `incident_id`, `fire_officer`, `branch`, `latitude`, `longitude`, `time_accepted`, `status`, `time_arrived`) VALUES
(14, 63, 'FIR-20250425-000128', 'TEST', 'Fire Station 1 - Arca South Station', 14.60770000, 121.04630000, '2025-04-25 11:34:35', 'ongoing', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_creds`
--

CREATE TABLE `admin_creds` (
  `id` int(11) NOT NULL,
  `admin_id` varchar(255) NOT NULL,
  `branch` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `admin_position` varchar(255) NOT NULL,
  `admin_permissions` longtext NOT NULL,
  `verified` tinyint(4) NOT NULL DEFAULT 0,
  `session_id` varchar(255) DEFAULT NULL,
  `failed_attempts` int(11) DEFAULT 0,
  `last_failed_login` datetime DEFAULT NULL,
  `is_locked` tinyint(1) DEFAULT 0,
  `is_deleted` tinyint(11) DEFAULT 0,
  `expiredToken` tinyint(1) DEFAULT 0,
  `jwt_token` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_creds`
--

INSERT INTO `admin_creds` (`id`, `admin_id`, `branch`, `first_name`, `middle_name`, `last_name`, `contact_number`, `username`, `email`, `password`, `admin_position`, `admin_permissions`, `verified`, `session_id`, `failed_attempts`, `last_failed_login`, `is_locked`, `is_deleted`, `expiredToken`, `jwt_token`) VALUES
(30, 'BFPT-77559240', 'Command Officer Head', 'Mar Steven', 'N/A', 'Celestial', '09994657669', 'coh_admin', 'celestial32202@gmail.com', '$2y$10$aXakhVtywRCsfzn3c1MH1uCsvz/C0VEkGJH8DJ3msbu1SZDrk2ftG', 'Command Officer Head', '{\"main_dashboard\":1,\"manage_accounts\":1,\"edit_accounts\":1,\"manage_reports\":1,\"monitor_rescue\":1}', 1, '510bio1gf5n9eq37ium212p44r', 0, NULL, 0, 0, 0, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImNlbGVzdGlhbDMyMjAyQGdtYWlsLmNvbSIsImV4cCI6MTc0NDgyODA0Mn0.zOS3JeRft_XyOvD2SKNDee_WDhNYwEWh4iv0ZZTuuM4'),
(31, 'BFPT-18426575', 'Fire Station 1 - Arca South Station', 'TESTADMIN', 'TEST', 'TEST', '09123456789', 'testadmin.test632', 'celestialmarsteven22@gmail.com', '$2y$10$onJu3F2YUNfi/1Mz8cg6f.zYlgt0lB41sk5H6oKl9W3X4L3stV3oO', 'Fire Officer', '{\"main_dashboard\":1,\"recieve_rescue_reports\":1}', 1, 'hke6ravt651l36uardcibp1r5q', 0, NULL, 0, 0, 0, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImlhbXBvZ2kyMTNAZ21haWwuY29tIiwiZXhwIjoxNzQ0ODI4ODQwfQ.FkEmEgJgRFkp0QeQSlhogVuDzX5bQ7z4W98ujLEkZy8');

-- --------------------------------------------------------

--
-- Table structure for table `incident_report`
--

CREATE TABLE `incident_report` (
  `id` int(11) NOT NULL,
  `incident_id` varchar(255) NOT NULL,
  `connection_id` varchar(255) NOT NULL,
  `reporter_name` varchar(255) NOT NULL,
  `contact_no` varchar(255) NOT NULL,
  `incident_location` varchar(255) NOT NULL,
  `info_message` varchar(255) NOT NULL,
  `gps_location` varchar(255) DEFAULT NULL,
  `report_status` varchar(255) NOT NULL,
  `token` text DEFAULT NULL,
  `connection_status` varchar(255) NOT NULL,
  `submitted_at` datetime DEFAULT NULL,
  `verified_by` varchar(255) DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incident_report`
--

INSERT INTO `incident_report` (`id`, `incident_id`, `connection_id`, `reporter_name`, `contact_no`, `incident_location`, `info_message`, `gps_location`, `report_status`, `token`, `connection_status`, `submitted_at`, `verified_by`, `verified_at`) VALUES
(113, 'FIR-20250401-000001', 'ffdsb24888g234ao', 'TEST SYS', 'TEST SYS', 'TEST SYS', 'TEST SYS', '14.6112512, 121.0679296', 'Approved', NULL, 'Disconnected', '2025-04-02 01:58:58', 'Celestial', '2025-04-02 01:59:15'),
(114, 'FIR-20250401-000002', 'ffdsb24888g234ao', 'TEST SYS', 'TEST SYS', 'TEST SYS', 'TEST SYS', '14.6112512, 121.0679296', 'Approved', NULL, 'Disconnected', '2025-04-02 02:03:30', 'Celestial', '2025-04-02 02:03:35'),
(115, 'FIR-20250401-000003', 'ffdsb24888g234ao', 'TEST SYS', 'TEST SYS', 'TEST SYS', 'TEST SYS', '14.6112512, 121.0679296', 'Approved', NULL, 'Disconnected', '2025-04-02 02:06:27', 'Celestial', '2025-04-02 02:08:51'),
(116, 'FIR-20250401-000004', 'ffdsb24888g234ao', 'TEST SYS', 'TEST SYS', 'TEST SYS', 'TEST SYS', '14.6112512, 121.0679296', 'Approved', NULL, 'Disconnected', '2025-04-02 02:09:26', 'Celestial', '2025-04-02 02:09:29'),
(117, 'FIR-20250401-000005', 'ffdsb24888g234ao', 'TEST SYS', 'TEST SYS', 'TEST SYS', 'TEST SYS', '14.6112512, 121.0679296', 'Approved', NULL, 'Disconnected', '2025-04-02 02:12:53', 'Celestial', '2025-04-02 02:12:55'),
(118, 'FIR-20250401-000006', 'ffdsb24888g234ao', 'TEST SYS', 'TEST SYS', 'TEST SYS', 'TEST SYS', '14.6112512, 121.0679296', 'Approved', NULL, 'Disconnected', '2025-04-02 02:27:45', 'Celestial', '2025-04-02 02:27:47'),
(119, 'FIR-20250401-000007', 'ffdsb24888g234ao', 'TEST SYS', 'TEST SYS', 'TEST SYS', 'TEST SYS', '14.6112512, 121.0679296', 'Approved', NULL, 'Disconnected', '2025-04-02 02:50:26', 'Celestial', '2025-04-02 02:50:29'),
(120, 'FIR-20250401-000008', 'ffdsb24888g234ao', 'TEST SYS', 'TEST SYS', 'TEST SYS', 'TEST SYS', '14.6112512, 121.0679296', 'Approved', NULL, 'Disconnected', '2025-04-02 02:57:23', 'Celestial', '2025-04-02 03:00:09'),
(121, 'FIR-20250402-000009', 'ffdsb24888g234ao', 'TEST SYS', 'TEST SYS', 'TEST SYS', 'TEST SYS', 'Location request timed out', 'Approved', NULL, 'Disconnected', '2025-04-02 11:16:06', 'Celestial', '2025-04-02 11:16:11'),
(122, 'FIR-20250402-000010', 'ffdsb24888g234ao', 'TEST SYS', 'TEST SYS', 'TEST SYS', 'TEST SYS', '14.6079744, 121.0679296', 'Declined', NULL, 'Disconnected', '2025-04-02 22:16:09', 'majestral', '2025-04-03 05:06:53'),
(123, 'FIR-20250402-000011', 'ffdsb24888g234ao', 'TEST SYS', 'TEST SYS', 'TEST SYS', 'TEST SYS', '14.6079744, 121.0679296', 'Declined', NULL, 'Disconnected', '2025-04-03 05:08:12', 'Celestial', '2025-04-03 05:15:59'),
(124, 'FIR-20250402-000012', 'ffdsb24888g234ao', 'TEST SYS', 'TEST SYS', 'TEST SYS', 'TEST SYS', '14.6079744, 121.0679296', 'Declined', NULL, 'Disconnected', '2025-04-03 05:16:07', 'majestral', '2025-04-03 05:24:50'),
(125, 'FIR-20250402-000013', 'uqokmbiafej56k76', 'TEST GPS', 'TEST GPS', 'TEST GPS', 'TEST GPS', '14.6077, 121.0463', 'Declined', NULL, 'Disconnected', '2025-04-03 05:26:49', 'majestral', '2025-04-03 05:27:04'),
(126, 'FIR-20250405-000014', 'srxq0xxkjmmlcnfy', 'TEST HOSTED', 'TEST HOSTED', 'TEST HOSTED', 'sdfasd', '14.5888, 121.0641', 'Declined', NULL, 'connected', '2025-04-05 18:01:53', 'Celestial', '2025-04-06 04:04:24'),
(127, 'FIR-20250405-000015', 'terwzs7pe7bwjxkw', 'TEST ', 'TEST', 'test', 'test', 'User denied location access', 'Approved', NULL, 'Disconnected', '2025-04-05 18:08:42', 'Celestial', '2025-04-05 18:09:15'),
(128, 'FIR-20250406-000019', 'wb4qkmqkwwzzyca1', 'dudjd', 'shdhhs', 'shhdhs', 'dhdudu', 'User denied location access', 'Declined', NULL, 'Disconnected', '2025-04-06 04:28:44', 'Celestial', '2025-04-06 04:46:15'),
(129, 'FIR-20250406-000019', 'wb4qkmqkwwzzyca1', 'dudjd', 'shdhhs', 'shhdhs', 'dhdudu', 'User denied location access', 'Declined', NULL, 'Disconnected', '2025-04-06 04:28:44', 'Celestial', '2025-04-06 04:46:15'),
(130, 'FIR-20250406-000019', 'wb4qkmqkwwzzyca1', 'dudjd', 'shdhhs', 'shhdhs', 'dhdudu', 'User denied location access', 'Declined', NULL, 'Disconnected', '2025-04-06 04:28:44', 'Celestial', '2025-04-06 04:46:15'),
(131, 'FIR-20250406-000064', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-06 23:59:38', 'Celestial', '2025-04-07 00:24:09'),
(132, 'FIR-20250406-000064', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-06 23:59:38', 'Celestial', '2025-04-07 00:24:09'),
(133, 'FIR-20250406-000064', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-06 23:59:38', 'Celestial', '2025-04-07 00:24:09'),
(134, 'FIR-20250406-000023', 'terwzs7pe7bwjxkw', 'TEST ', 'TEST', 'test', 'test', 'User denied location access', 'Declined', NULL, 'Disconnected', '2025-04-06 04:49:25', 'Celestial', '2025-04-06 04:54:38'),
(135, 'FIR-20250406-000064', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-06 23:59:38', 'Celestial', '2025-04-07 00:24:09'),
(136, 'FIR-20250406-000025', 'terwzs7pe7bwjxkw', 'TEST', 'test', 'test', 'test', 'User denied location access', 'Declined', NULL, 'Disconnected', '2025-04-06 05:04:03', 'Celestial', '2025-04-06 05:04:24'),
(137, 'FIR-20250406-000026', 'terwzs7pe7bwjxkw', 'TEST', 'test', 'test', 'test', 'User denied location access', 'Declined', NULL, 'Disconnected', '2025-04-06 05:07:28', 'Celestial', '2025-04-06 05:11:39'),
(138, 'FIR-20250406-000027', 'wb4qkmqkwwzzyca1', 'Mar steven', 'dhjejejd', 'uwiemeo', 'iekendur', 'User denied location access', 'Declined', NULL, 'Disconnected', '2025-04-06 05:12:03', 'Celestial', '2025-04-06 05:18:23'),
(139, 'FIR-20250406-000028', 'evz4rcgrca5tsz3f', 'Jan Gabriel Tobias', '09182437361', 'Pipit st. Taguig City', 'Fire here', '14.52317336624097, 121.06188301845698', 'Declined', NULL, 'Disconnected', '2025-04-06 05:13:40', 'Celestial', '2025-04-06 05:18:20'),
(140, 'FIR-20250406-000029', 'wb4qkmqkwwzzyca1', 'jejekeke', 'duhdud', 'dhhdudud', 'hdhdushs', 'User denied location access', 'Declined', NULL, 'Disconnected', '2025-04-06 05:21:45', 'Celestial', '2025-04-06 05:22:55'),
(141, 'FIR-20250406-000030', 'evz4rcgrca5tsz3f', 'Jan Gabriel Tobias', '09182437361', 'Las marias st. Pembo taguig', 'Fire incident', '14.523014979490688, 121.06361456730522', 'Declined', NULL, 'Disconnected', '2025-04-06 05:22:07', 'Celestial', '2025-04-06 06:37:31'),
(142, 'FIR-20250406-000031', 'wb4qkmqkwwzzyca1', 'marsteveb', 'hwhehehs', 'shhsjdjdjd', 'hdhdhdhs', 'User denied location access', 'Declined', NULL, 'Disconnected', '2025-04-06 05:23:36', 'Celestial', '2025-04-06 05:29:16'),
(143, 'FIR-20250406-000032', 'wb4qkmqkwwzzyca1', 'Test', 'test', 'test', 'test', 'User denied location access', 'Declined', NULL, 'Disconnected', '2025-04-06 05:32:54', 'Celestial', '2025-04-06 06:37:05'),
(144, 'FIR-20250406-000064', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-06 23:59:38', 'Celestial', '2025-04-07 00:24:09'),
(145, 'FIR-20250406-000034', 'wb4qkmqkwwzzyca1', 'mar', 'mar', 'mar', 'mar', 'User denied location access', 'Declined', NULL, 'Disconnected', '2025-04-06 06:42:06', 'Celestial', '2025-04-06 08:04:08'),
(146, 'FIR-20250406-000064', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-06 23:59:38', 'Celestial', '2025-04-07 00:24:09'),
(147, 'FIR-20250406-000036', 'wb4qkmqkwwzzyca1', 'heisjsj', 'sndndjdj', 'ndjdidid', 'dndjdjdj', 'User denied location access', 'Declined', NULL, 'Disconnected', '2025-04-06 06:52:48', 'Celestial', '2025-04-06 08:04:04'),
(148, 'FIR-20250406-000064', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-06 23:59:38', 'Celestial', '2025-04-07 00:24:09'),
(149, 'FIR-20250406-000038', 'wb4qkmqkwwzzyca1', 'hhis', 'kbs', 'sbiskbs', 'sivsbisibs', 'User denied location access', 'Declined', NULL, 'Disconnected', '2025-04-06 08:11:02', 'Celestial', '2025-04-06 09:07:57'),
(150, 'FIR-20250406-000039', 'terwzs7pe7bwjxkw', 'ycuguhin', 'uvibniin', 'y inniin', 'j. j nkn', 'User denied location access', 'Declined', NULL, 'Disconnected', '2025-04-06 08:14:24', 'Celestial', '2025-04-06 09:07:56'),
(151, 'FIR-20250406-000064', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-06 23:59:38', 'Celestial', '2025-04-07 00:24:09'),
(152, 'FIR-20250406-000064', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-06 23:59:38', 'Celestial', '2025-04-07 00:24:09'),
(153, 'FIR-20250406-000064', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-06 23:59:38', 'Celestial', '2025-04-07 00:24:09'),
(154, 'FIR-20250406-000043', 'wb4qkmqkwwzzyca1', 'buuh ui ni', 'sen jijin', 'unun', 'nuin', 'User denied location access', 'Declined', NULL, 'Disconnected', '2025-04-06 09:09:06', 'Celestial', '2025-04-06 10:06:02'),
(155, 'FIR-20250406-000064', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-06 23:59:38', 'Celestial', '2025-04-07 00:24:09'),
(156, 'FIR-20250406-000045', 'terwzs7pe7bwjxkw', 'ueieie', 'ududid', 'duudud', 'dujfudd', 'User denied location access', 'Declined', NULL, 'Disconnected', '2025-04-06 09:49:12', 'Celestial', '2025-04-06 10:05:58'),
(157, 'FIR-20250406-000046', 'terwzs7pe7bwjxkw', 'gtamam hyy', 'ynynu', 'g yhu', 'y y', 'User denied location access', 'Declined', NULL, 'Disconnected', '2025-04-06 09:50:46', 'Celestial', '2025-04-06 10:05:03'),
(158, 'FIR-20250406-000047', 'wb4qkmqkwwzzyca1', 'hdufuf', 'ncjf', 'gdgdhx', 'jftiuf', 'User denied location access', 'Declined', NULL, 'Disconnected', '2025-04-06 10:08:33', 'Celestial', '2025-04-06 11:16:00'),
(159, 'FIR-20250406-000048', 'od32440a99by0za1', 'Mar Steven Celestial', '+639994657669', 'Pasig City', 'None', 'User denied location access', 'Declined', NULL, 'Disconnected', '2025-04-06 11:18:40', 'Celestial', '2025-04-07 00:24:11'),
(160, 'FIR-20250406-000064', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-06 23:59:38', 'Celestial', '2025-04-07 00:24:09'),
(161, 'FIR-20250406-000064', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-06 23:59:38', 'Celestial', '2025-04-07 00:24:09'),
(162, 'FIR-20250406-000064', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-06 23:59:38', 'Celestial', '2025-04-07 00:24:09'),
(163, 'FIR-20250406-000064', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-06 23:59:38', 'Celestial', '2025-04-07 00:24:09'),
(164, 'FIR-20250406-000064', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-06 23:59:38', 'Celestial', '2025-04-07 00:24:09'),
(165, 'FIR-20250406-000064', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-06 23:59:38', 'Celestial', '2025-04-07 00:24:09'),
(166, 'FIR-20250406-000064', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-06 23:59:38', 'Celestial', '2025-04-07 00:24:09'),
(167, 'FIR-20250406-000064', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-06 23:59:38', 'Celestial', '2025-04-07 00:24:09'),
(168, 'FIR-20250406-000064', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-06 23:59:38', 'Celestial', '2025-04-07 00:24:09'),
(169, 'FIR-20250406-000064', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-06 23:59:38', 'Celestial', '2025-04-07 00:24:09'),
(170, 'FIR-20250406-000064', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-06 23:59:38', 'Celestial', '2025-04-07 00:24:09'),
(171, 'FIR-20250406-000064', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-06 23:59:38', 'Celestial', '2025-04-07 00:24:09'),
(172, 'FIR-20250406-000064', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-06 23:59:38', 'Celestial', '2025-04-07 00:24:09'),
(173, 'FIR-20250406-000064', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-06 23:59:38', 'Celestial', '2025-04-07 00:24:09'),
(174, 'FIR-20250406-000064', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-06 23:59:38', 'Celestial', '2025-04-07 00:24:09'),
(175, 'FIR-20250407-000065', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asd', 'asdas', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-07 00:26:18', 'Celestial', '2025-04-07 00:28:34'),
(176, 'FIR-20250407-000066', 'wb4qkmqkwwzzyca1', 'djdjdid', 'udididid', 'usudisis', 'ususidis', 'User denied location access', 'Declined', NULL, 'Disconnected', '2025-04-07 00:26:49', 'Celestial', '2025-04-07 00:28:32'),
(177, 'FIR-20250407-000087', 'rbt77syxjfewjz25', 'Yowshie', '09182437361', 'Las marias st. Pembo, taguig', 'Run boi', 'Location request timed out', 'Declined', NULL, 'Disconnected', '2025-04-07 01:17:29', 'Celestial', '2025-04-07 01:17:38'),
(178, 'FIR-20250407-000068', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-07 00:31:42', 'Celestial', '2025-04-07 00:33:03'),
(179, 'FIR-20250407-000069', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asd', 'dasdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-07 00:33:09', 'Celestial', '2025-04-07 00:36:26'),
(180, 'FIR-20250407-000070', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-07 00:36:31', 'Celestial', '2025-04-07 00:41:28'),
(181, 'FIR-20250407-000071', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-07 00:41:32', 'Celestial', '2025-04-07 00:44:43'),
(182, 'FIR-20250407-000072', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-07 00:44:53', 'Celestial', '2025-04-07 00:45:39'),
(183, 'FIR-20250407-000087', 'rbt77syxjfewjz25', 'Yowshie', '09182437361', 'Las marias st. Pembo, taguig', 'Run boi', 'Location request timed out', 'Declined', NULL, 'Disconnected', '2025-04-07 01:17:29', 'Celestial', '2025-04-07 01:17:38'),
(184, 'FIR-20250407-000074', 'wb4qkmqkwwzzyca1', 'i. igcgi  hiç c', 'ucgugu', 'ycucvu', 'ychvuv', 'User denied location access', 'Declined', NULL, 'Disconnected', '2025-04-07 00:49:07', 'Celestial', '2025-04-07 00:53:11'),
(185, 'FIR-20250407-000075', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-07 00:50:06', 'Celestial', '2025-04-07 00:53:06'),
(186, 'FIR-20250407-000076', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-07 00:53:14', 'Celestial', '2025-04-07 01:01:05'),
(187, 'FIR-20250407-000077', 'wb4qkmqkwwzzyca1', 'hj s dv', 'scs sc', 'scs s', 's s s', 'User denied location access', 'Declined', NULL, 'Disconnected', '2025-04-07 00:53:37', 'Celestial', '2025-04-07 00:55:29'),
(188, 'FIR-20250407-000078', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-07 00:55:37', 'Celestial', '2025-04-07 01:01:03'),
(189, 'FIR-20250407-000079', 'wb4qkmqkwwzzyca1', 'jsjdjd', 'djdjdjd', 'dududi', 'sjdjdjs', 'User denied location access', 'Declined', NULL, 'Disconnected', '2025-04-07 00:56:02', 'Celestial', '2025-04-07 01:01:01'),
(190, 'FIR-20250407-000080', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'df', 'dsf', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-07 01:01:14', 'Celestial', '2025-04-07 01:05:33'),
(191, 'FIR-20250407-000081', 'wb4qkmqkwwzzyca1', 'f. fbt', 'ben rgrv', 'rvf ', 'vrrvr', 'User denied location access', 'Declined', NULL, 'Disconnected', '2025-04-07 01:02:22', 'Celestial', '2025-04-07 01:04:39'),
(192, 'FIR-20250407-000082', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-07 01:05:40', 'Celestial', '2025-04-07 01:11:58'),
(193, 'FIR-20250407-000083', 'wb4qkmqkwwzzyca1', 'snsnksks', 'ssjjsjss', 'sjsjsjsj', 'sjsjsjs', 'User denied location access', 'Declined', NULL, 'Disconnected', '2025-04-07 01:06:05', 'Celestial', '2025-04-07 01:11:05'),
(194, 'FIR-20250407-000084', 'terwzs7pe7bwjxkw', 'gtamam aubsins', 'subsu s', 'subjzbuz', 'z hzuz', 'User denied location access', 'Declined', NULL, 'Disconnected', '2025-04-07 01:06:49', 'Celestial', '2025-04-07 01:11:02'),
(195, 'FIR-20250407-000085', 'evz4rcgrca5tsz3f', 'Bossswa', '09182437361', 'Las marias st. Pembo, taguig city', 'Sunog boss', '14.546393940831488, 121.06708631424058', 'Declined', NULL, 'Disconnected', '2025-04-07 01:09:06', 'Celestial', '2025-04-07 01:11:56'),
(196, 'FIR-20250407-000087', 'rbt77syxjfewjz25', 'Yowshie', '09182437361', 'Las marias st. Pembo, taguig', 'Run boi', 'Location request timed out', 'Declined', NULL, 'Disconnected', '2025-04-07 01:17:29', 'Celestial', '2025-04-07 01:17:38'),
(197, 'FIR-20250407-000088', 'i9sbwrnrqdzqvs2c', 'TESTADMIN TESTADMIN TESTADMIN', '09994657669', 'asdsa', 'asdasd', '14.5888, 121.0641', 'Approved', NULL, 'Disconnected', '2025-04-07 01:18:58', 'Celestial', '2025-04-07 01:19:02'),
(198, 'FIR-20250407-000089', 'wb4qkmqkwwzzyca1', 'jejsjss', 'hdjdjs', 'hsjsjs', 'hshshs', 'User denied location access', 'Declined', NULL, 'Disconnected', '2025-04-07 01:44:41', 'Celestial', '2025-04-07 06:12:59'),
(199, 'FIR-20250407-000090', 'vtq8xq97ihezajok', 'TEST', 'TEST', 'TEST', 'TEST\r\n', '14.5627864, 121.0559697', 'Declined', NULL, 'Disconnected', '2025-04-07 03:17:09', 'Celestial', '2025-04-07 03:18:50'),
(200, 'FIR-20250407-000091', 'wb4qkmqkwwzzyca1', 'TEST', 'test', 'test', 'test', 'User denied location access', 'Declined', NULL, 'Disconnected', '2025-04-07 03:18:12', 'Celestial', '2025-04-07 03:18:48'),
(201, 'FIR-20250407-000092', 'terwzs7pe7bwjxkw', 'test', 'test', 'test', 'test', 'User denied location access', 'Declined', NULL, 'Disconnected', '2025-04-07 03:19:09', 'Celestial', '2025-04-07 06:12:58'),
(202, 'FIR-20250407-000093', 'i9sbwrnrqdzqvs2c', 'TEST', 'TEST', 'TEST', 'TEST', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-07 06:13:27', 'Celestial', '2025-04-07 06:14:18'),
(203, 'FIR-20250407-000094', 'i9sbwrnrqdzqvs2c', 'TEST', 'TEST', 'TEST', 'test', '14.5888, 121.0641', 'Declined', NULL, 'Disconnected', '2025-04-07 06:14:31', 'Celestial', '2025-04-07 06:16:30'),
(204, 'FIR-20250407-000095', 'wb4qkmqkwwzzyca1', 'TEST MOBILE', 'TEST MOBILE', 'TEST MOBILE', 'TEST MOBILE', 'User denied location access', 'Declined', NULL, 'Disconnected', '2025-04-07 06:18:59', 'Celestial', '2025-04-10 10:03:34'),
(205, 'FIR-20250407-000096', 'rbt77syxjfewjz25', 'Bogs', '09182437361', 'Las marias st. Pembo, taguig', 'Sunog', 'Location request timed out', 'Declined', NULL, 'Disconnected', '2025-04-07 06:24:07', 'Celestial', '2025-04-10 10:03:32'),
(206, 'FIR-20250407-000097', 'vtq8xq97ihezajok', 'Bopss', '09182437322', 'Las Marias St. Pembo, Taguig City', 'Sunog', '14.5462245, 121.0678958', 'Declined', NULL, 'Disconnected', '2025-04-07 06:43:50', 'Celestial', '2025-04-10 10:03:36'),
(207, 'FIR-20250408-000098', 'vtq8xq97ihezajok', 'Gab', '09123456789', 'Pateros', 'sunog\r\n', '14.5454183, 121.0674434', 'Declined', NULL, 'Disconnected', '2025-04-08 04:58:54', 'Celestial', '2025-04-10 10:03:30'),
(208, 'FIR-20250410-000102', 'tdmh74kjfd41g3s7', 'auscii', '09166860971', 'PH', 'qwerty', 'Location information unavailable', 'Declined', NULL, 'connected', '2025-04-10 05:32:50', 'Celestial', '2025-04-10 10:03:42'),
(209, 'FIR-20250410-000103', 'i9sbwrnrqdzqvs2c', 'TEST', 'TEST', 'TEST', 'SADASD', '14.6077, 121.0463', 'Declined', NULL, 'Disconnected', '2025-04-10 10:03:54', 'Celestial', '2025-04-10 10:12:06'),
(210, 'FIR-20250410-000104', 'i9sbwrnrqdzqvs2c', 'TEST', 'TEST', 'TEST', 'TEST', '14.6077, 121.0463', 'Declined', NULL, 'Disconnected', '2025-04-10 10:14:03', 'Celestial', '2025-04-10 19:20:20'),
(211, 'FIR-20250410-000105', 'i9sbwrnrqdzqvs2c', 'TEST', 'TEST', 'TEST', 'TEST', '14.6077, 121.0463', 'Declined', NULL, 'Disconnected', '2025-04-10 19:20:28', 'Celestial', '2025-04-10 19:24:25'),
(212, 'FIR-20250410-000106', 'i9sbwrnrqdzqvs2c', 'TEST', 'TEST', 'TEST', 'test', '14.6077, 121.0463', 'Declined', NULL, 'Disconnected', '2025-04-10 19:24:36', 'Celestial', '2025-04-10 19:25:19'),
(213, 'FIR-20250410-000107', 'i9sbwrnrqdzqvs2c', 'TEST', 'TEST', 'TEST', 'TREST', '14.6077, 121.0463', 'Declined', NULL, 'Disconnected', '2025-04-10 19:25:28', 'Celestial', '2025-04-10 19:26:56'),
(214, 'FIR-20250410-000108', 'i9sbwrnrqdzqvs2c', 'TEST', 'TEST', 'TEST', 'TEST', '14.6077, 121.0463', 'Declined', NULL, 'Disconnected', '2025-04-10 19:26:04', 'Celestial', '2025-04-10 19:26:54'),
(215, 'FIR-20250410-000109', 'i9sbwrnrqdzqvs2c', 'TEST', 'TEST', 'TEST', 'test', '14.6077, 121.0463', 'Declined', NULL, 'Disconnected', '2025-04-10 19:27:04', 'Celestial', '2025-04-10 19:27:36'),
(216, 'FIR-20250410-000110', 'i9sbwrnrqdzqvs2c', 'TEST', 'TEST', 'TEST', 'test', '14.6077, 121.0463', 'Declined', NULL, 'Disconnected', '2025-04-10 19:27:42', 'Celestial', '2025-04-10 19:29:12'),
(217, 'FIR-20250410-000111', 'i9sbwrnrqdzqvs2c', 'TEST', 'TEST', 'TEST', 'TEST', '14.6077, 121.0463', 'Declined', NULL, 'Disconnected', '2025-04-10 19:29:20', 'Celestial', '2025-04-10 19:30:00'),
(218, 'FIR-20250410-000112', 'i9sbwrnrqdzqvs2c', 'TEST', 'TEST', 'TEST', 'test', '14.6077, 121.0463', 'Declined', NULL, 'Disconnected', '2025-04-10 19:30:07', 'Celestial', '2025-04-10 19:32:28'),
(219, 'FIR-20250410-000113', 'i9sbwrnrqdzqvs2c', 'TEST', 'TEST', 'TEST', 'TEST', '14.6077, 121.0463', 'Declined', NULL, 'Disconnected', '2025-04-10 19:32:41', 'Celestial', '2025-04-10 19:40:44'),
(220, 'FIR-20250410-000114', 'i9sbwrnrqdzqvs2c', 'TEST', 'TEST', 'TEST', 'EST', '14.6077, 121.0463', 'Declined', NULL, 'Disconnected', '2025-04-10 19:40:56', 'Celestial', '2025-04-10 19:42:47'),
(221, 'FIR-20250410-000115', 'i9sbwrnrqdzqvs2c', 'TEST', 'TEST', 'TEST', 'TEST', '14.6077, 121.0463', 'Declined', NULL, 'Disconnected', '2025-04-10 19:42:52', 'Celestial', '2025-04-10 22:23:00'),
(222, 'FIR-20250410-000116', 'i9sbwrnrqdzqvs2c', 'sample', 'sample', 'sample', 'sample', '14.6077, 121.0463', 'Declined', NULL, 'Disconnected', '2025-04-10 22:22:51', 'Celestial', '2025-04-10 22:24:24'),
(223, 'FIR-20250410-000117', 'i9sbwrnrqdzqvs2c', 'sample', 'sample', 'sample', 'sample', '14.6077, 121.0463', 'Approved', NULL, 'Disconnected', '2025-04-10 22:24:34', 'Celestial', '2025-04-10 22:28:08'),
(224, 'FIR-20250410-000118', 'i9sbwrnrqdzqvs2c', 'sample', 'sample', 'sample', 'sample', '14.6077, 121.0463', 'Approved', NULL, 'Disconnected', '2025-04-10 22:48:16', 'Celestial', '2025-04-10 22:48:19'),
(225, 'FIR-20250410-000119', 'i9sbwrnrqdzqvs2c', 'sample', 'sample', 'sample', 'sample', '14.6077, 121.0463', 'Approved', NULL, 'Disconnected', '2025-04-10 22:53:15', 'Celestial', '2025-04-10 22:53:16'),
(226, 'FIR-20250411-000120', 'vtq8xq97ihezajok', 'SAMPLE', 'SAMPLE', 'SAMPLE', 'SAMPLE\r\n', '14.5358848, 121.0155008', 'Approved', NULL, 'Disconnected', '2025-04-11 03:01:30', 'Celestial', '2025-04-11 03:01:41'),
(227, 'FIR-20250411-000121', 'vtq8xq97ihezajok', 'sample', 'sample', 'sample', 'sample', '14.6079744, 121.0679296', 'Approved', NULL, 'Disconnected', '2025-04-11 04:36:13', 'Celestial', '2025-04-11 04:44:10'),
(228, 'FIR-20250411-000122', 'rbt77syxjfewjz25', 'Bogues', '09182437361', 'Las marias st. Pembo, taguig', 'Halimaw', 'Location request timed out', 'Declined', NULL, 'Disconnected', '2025-04-11 08:55:47', 'Celestial', '2025-04-11 09:01:13'),
(229, 'FIR-20250411-000123', 'rbt77syxjfewjz25', 'Jan Gabriel ', '09182437361', 'Las marias st. Pembo, taguig', 'Urgent sunog nearby', 'Location request timed out', 'Declined', NULL, 'Disconnected', '2025-04-11 10:03:03', 'Celestial', '2025-04-11 10:08:03'),
(230, 'FIR-20250411-000124', 'rbt77syxjfewjz25', 'Bogsssue', '09182437361', 'Las marias st. Pembo, taguig', 'Sunog urgent', 'Location request timed out', 'Declined', NULL, 'Disconnected', '2025-04-11 10:08:27', 'Celestial', '2025-04-11 10:10:41'),
(231, 'FIR-20250411-000125', 'vtq8xq97ihezajok', 'Mar Steven', '09994657669', 'Taguig', 'fire nearby', '14.6079744, 121.0679296', 'Approved', NULL, 'Disconnected', '2025-04-11 10:17:23', 'Celestial', '2025-04-11 10:25:32'),
(232, 'FIR-20250425-000126', 'i9sbwrnrqdzqvs2c', 'TEST', 'TEST', 'TEST', 'TEST', '14.6077, 121.0463', 'Approved', NULL, 'Disconnected', '2025-04-25 09:42:32', 'Celestial', '2025-04-25 09:42:36'),
(233, 'FIR-20250425-000127', 'i9sbwrnrqdzqvs2c', 'TEST', 'TEST', 'TEST', 'TEST', '14.6077, 121.0463', 'Approved', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpbmNpZGVudF9pZCI6IkZJUi0yMDI1MDQyNS0wMDAxMjciLCJleHAiOjE3NDU1NzgzOTl9.EgKx6hITwE6iAS-GMax6fYMABa3-bmpijtppJSmrkm0', 'Disconnected', '2025-04-25 09:53:16', 'Celestial', '2025-04-25 09:53:19'),
(234, 'FIR-20250425-000128', 'i9sbwrnrqdzqvs2c', 'TEST', 'TEST', 'TEST', 'TEST', 'Location request timed out', 'Approved', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpbmNpZGVudF9pZCI6IkZJUi0yMDI1MDQyNS0wMDAxMjgiLCJleHAiOjE3NDU1Nzk0NzZ9.CzncnOCP4fu8Wy6Ii3D3KWNaf_vm9uGRxwJmA5TLiSk', 'connected', '2025-04-25 10:11:09', 'Celestial', '2025-04-25 10:11:16');

-- --------------------------------------------------------

--
-- Table structure for table `locations_markers`
--

CREATE TABLE `locations_markers` (
  `id` int(11) NOT NULL,
  `latitude` decimal(10,7) NOT NULL,
  `longitude` decimal(10,7) NOT NULL,
  `location_name` varchar(255) NOT NULL,
  `type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations_markers`
--

INSERT INTO `locations_markers` (`id`, `latitude`, `longitude`, `location_name`, `type_id`) VALUES
(1, 14.5446760, 121.0602920, 'Mirasol cor Sampaguita', 3),
(2, 14.5440390, 121.0600210, 'Pitimihi cor Sampaguita', 3),
(3, 14.5429930, 121.0596880, 'Azucena cor Sampaguita', 3),
(4, 14.5437800, 121.0580490, 'Maricel cor Sampaguita', 3),
(5, 14.5462420, 121.0573700, 'Paraiso cor Kampupot', 3),
(6, 14.5463380, 121.0583840, 'Dana de Noche cor Ilang Ilang', 3),
(7, 14.5483900, 121.0595440, 'Amapola and Cadena de Amor Corner', 3),
(8, 14.5482070, 121.0595790, 'Bougainvilla and Cadena de Amore Corner', 3),
(9, 14.5027290, 121.0429890, 'Fire Station 1 - Arca South Station', 2),
(10, 14.5093410, 121.0546150, 'Fire Station 2 - Central Signal Station', 2),
(11, 14.5404680, 121.0884560, 'Fire Station 3 - Ibayo-Tipas Station', 2),
(12, 14.4738100, 121.0591410, 'Fire Station 4 - Bagumbayan', 2),
(13, 14.5138400, 121.0589100, 'Fire Station 5 - North Signal', 2),
(14, 14.5380870, 121.0803660, 'Fire Station 6 - Palingon-Tipas', 2),
(15, 14.5205820, 121.0808350, 'Fire Station 7 - Wawa', 2),
(16, 14.5189140, 121.0513290, 'Fire Station 8 - Cuasay', 2),
(17, 14.5489830, 121.0604530, 'Fire Station 9 - West Rembo', 2),
(18, 14.5456590, 121.0651990, 'Fire Station 10 - Comembo', 2),
(19, 14.5468570, 121.0597270, 'Pembo Elementary School', 1),
(20, 14.5459510, 121.0578840, 'Pembo Covered Court', 1),
(21, 14.5437410, 121.0578950, 'Pembo Brgy. Hall', 1),
(22, 14.5426710, 121.0606950, 'Umbel Covered Court', 1),
(23, 14.5410680, 121.0608960, 'Mansanas Covered Court', 1),
(24, 14.5394600, 121.0596410, 'Cattleya Covered Court', 1),
(25, 14.5367640, 121.0603500, 'Rizal Elementary School', 1),
(26, 14.5362890, 121.0638150, 'Rizal Community Complex', 1),
(27, 14.5385470, 121.0617100, 'Rizal Sports Complex', 1),
(28, 14.5456590, 121.0640880, 'Comembo Elementary School', 1),
(29, 14.5472480, 121.0649160, 'Comembo Sports Complex', 1),
(30, 14.5496210, 121.0650180, 'Benigno \"Ninoy\" S. Aquino High School', 1),
(31, 14.5538720, 121.0642050, 'East Rembo Elementary School', 1),
(32, 14.5571310, 121.0642850, 'Tibagan High School', 1),
(33, 14.5594160, 121.0633520, 'West Rembo Sports Complex', 1),
(34, 14.5631710, 121.0582990, 'Fort Bonifacio Elementary School', 1),
(35, 14.5384740, 121.0864850, 'Ibayo-Tipas Covered Court', 1),
(36, 14.5388610, 121.0789480, 'Tipas Elementary School', 1),
(37, 14.5351660, 121.0772500, 'BPTHAI Covered Court', 1),
(38, 14.5320000, 121.0796230, 'Tipas Elementary School Annex', 1),
(71, 14.5516840, 121.0510540, 'BGC', 3),
(72, 14.5497353, 121.0503752, 'Wall-mounted dry stand pipe', 3);

-- --------------------------------------------------------

--
-- Table structure for table `location_type_markers`
--

CREATE TABLE `location_type_markers` (
  `id` int(11) NOT NULL,
  `type_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `location_type_markers`
--

INSERT INTO `location_type_markers` (`id`, `type_name`) VALUES
(1, 'evacuation_center'),
(2, 'fire_station'),
(3, 'hydrant');

-- --------------------------------------------------------

--
-- Table structure for table `rescue_details`
--

CREATE TABLE `rescue_details` (
  `id` int(11) NOT NULL,
  `incident_id` varchar(255) NOT NULL,
  `incident_location` varchar(255) NOT NULL,
  `info_message` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `sent_by` varchar(255) NOT NULL,
  `submitted_at` datetime DEFAULT NULL,
  `rescue_return` datetime DEFAULT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `auth_token` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rescue_details`
--

INSERT INTO `rescue_details` (`id`, `incident_id`, `incident_location`, `info_message`, `status`, `sent_by`, `submitted_at`, `rescue_return`, `longitude`, `latitude`, `auth_token`) VALUES
(59, 'FIR-20250410-000119', 'sample', 'sample', 'ongoing', 'i9sbwrnrqdzqvs2c', '2025-04-10 22:53:23', NULL, 121.06036205, 14.53889954, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpbmNpZGVudF9kYXRhIjp7ImluY2lkZW50X2lkIjoiRklSLTIwMjUwNDEwLTAwMDExOSIsImxhdGl0dWRlIjoxNC41Mzg4OTk1NDQ3ODM2NzUsImxvbmdpdHVkZSI6MTIxLjA2MDM2MjA1NDEwNTA0fSwiaWF0IjoxNzQ0MzI1NjAzLCJleHAiOjE3NzU4NjE2MDN9.MzbGl2nRmZtg1y1CqinwWgWkhwEINYppUFvfSbaOzG0'),
(60, 'FIR-20250411-000120', 'SAMPLE', 'SAMPLE\r\n', 'ongoing', 'vtq8xq97ihezajok', '2025-04-11 03:02:20', NULL, 121.06101822, 14.53976250, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpbmNpZGVudF9kYXRhIjp7ImluY2lkZW50X2lkIjoiRklSLTIwMjUwNDExLTAwMDEyMCIsImxhdGl0dWRlIjoxNC41Mzk3NjI1MDAwMDk5LCJsb25naXR1ZGUiOjEyMS4wNjEwMTgyMTY2MzIwMn0sImlhdCI6MTc0NDM0MDU0MCwiZXhwIjoxNzc1ODc2NTQwfQ.vCo5ERAbVGBS-pp3zTuRtWt-owI6T8fhMHiLMjdPG6g'),
(61, 'FIR-20250411-000121', 'sample', 'sample', 'ongoing', 'vtq8xq97ihezajok', '2025-04-11 04:45:50', NULL, 121.06081727, 14.54214338, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpbmNpZGVudF9kYXRhIjp7ImluY2lkZW50X2lkIjoiRklSLTIwMjUwNDExLTAwMDEyMSIsImxhdGl0dWRlIjoxNC41NDIxNDMzODQwODQ0ODMsImxvbmdpdHVkZSI6MTIxLjA2MDgxNzI3MTA1ODIzfSwiaWF0IjoxNzQ0MzQ2NzUwLCJleHAiOjE3NzU4ODI3NTB9.qRhj6V-IL7P8YrVruCxED3c5ZZCWw2iEyUPl7zOVVac'),
(62, 'FIR-20250411-000125', 'Taguig', 'fire nearby', 'ongoing', 'vtq8xq97ihezajok', '2025-04-11 10:26:17', NULL, 121.06006742, 14.54350798, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpbmNpZGVudF9kYXRhIjp7ImluY2lkZW50X2lkIjoiRklSLTIwMjUwNDExLTAwMDEyNSIsImxhdGl0dWRlIjoxNC41NDM1MDc5ODA5MjcwNzUsImxvbmdpdHVkZSI6MTIxLjA2MDA2NzQyMzkxMjc2fSwiaWF0IjoxNzQ0MzY3MTc3LCJleHAiOjE3NzU5MDMxNzd9.HhT3-FAz_6HB1gxWRMTnNTkgTlupXAWKABYF2ttwrYs'),
(63, 'FIR-20250425-000128', 'TEST', 'TEST', 'ongoing', 'i9sbwrnrqdzqvs2c', '2025-04-25 10:20:08', NULL, 121.06156492, 14.53962214, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpbmNpZGVudF9kYXRhIjp7ImluY2lkZW50X2lkIjoiRklSLTIwMjUwNDI1LTAwMDEyOCIsImxhdGl0dWRlIjoxNC41Mzk2MjIxMzY4NTg2NjgsImxvbmdpdHVkZSI6MTIxLjA2MTU2NDkyMTEzNDA1fSwiaWF0IjoxNzQ1NTc2NDA4LCJleHAiOjE3NzcxMTI0MDh9.xDqDsv1EMcMZonoac4PtiE61N-bkgndxrovUXJ17QqE');

-- --------------------------------------------------------

--
-- Table structure for table `rescue_selected_stations`
--

CREATE TABLE `rescue_selected_stations` (
  `id` int(11) NOT NULL,
  `rescue_details_id` int(11) NOT NULL,
  `fire_station_name` varchar(255) NOT NULL,
  `rescue_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rescue_selected_stations`
--

INSERT INTO `rescue_selected_stations` (`id`, `rescue_details_id`, `fire_station_name`, `rescue_status`) VALUES
(135, 59, 'Fire Station 10 - Comembo', 'sent'),
(136, 59, 'Fire Station 9 - West Rembo', 'sent'),
(137, 59, 'Fire Station 6 - Palingon-Tipas', 'sent'),
(138, 59, 'Fire Station 8 - Cuasay', 'sent'),
(139, 59, 'Fire Station 5 - North Signal', 'sent'),
(140, 59, 'Fire Station 7 - Wawa', 'sent'),
(141, 59, 'Fire Station 3 - Ibayo-Tipas Station', 'sent'),
(142, 59, 'Fire Station 2 - Central Signal Station', 'sent'),
(143, 59, 'Fire Station 1 - Arca South Station', 'sent'),
(144, 59, 'Fire Station 4 - Bagumbayan', 'sent'),
(145, 60, 'Fire Station 10 - Comembo', 'sent'),
(146, 60, 'Fire Station 9 - West Rembo', 'sent'),
(147, 60, 'Fire Station 6 - Palingon-Tipas', 'sent'),
(148, 60, 'Fire Station 8 - Cuasay', 'sent'),
(149, 60, 'Fire Station 5 - North Signal', 'sent'),
(150, 61, 'Fire Station 10 - Comembo', 'sent'),
(151, 61, 'Fire Station 9 - West Rembo', 'sent'),
(152, 61, 'Fire Station 6 - Palingon-Tipas', 'sent'),
(153, 61, 'Fire Station 8 - Cuasay', 'sent'),
(154, 61, 'Fire Station 3 - Ibayo-Tipas Station', 'sent'),
(155, 62, 'Fire Station 10 - Comembo', 'sent'),
(156, 62, 'Fire Station 9 - West Rembo', 'sent'),
(157, 62, 'Fire Station 6 - Palingon-Tipas', 'sent'),
(158, 63, 'Fire Station 10 - Comembo', 'sent'),
(159, 63, 'Fire Station 9 - West Rembo', 'sent'),
(160, 63, 'Fire Station 6 - Palingon-Tipas', 'sent'),
(161, 63, 'Fire Station 8 - Cuasay', 'sent'),
(162, 63, 'Fire Station 5 - North Signal', 'sent'),
(163, 63, 'Fire Station 3 - Ibayo-Tipas Station', 'sent'),
(164, 63, 'Fire Station 7 - Wawa', 'sent'),
(165, 63, 'Fire Station 2 - Central Signal Station', 'sent'),
(166, 63, 'Fire Station 1 - Arca South Station', 'sent'),
(167, 63, 'Fire Station 4 - Bagumbayan', 'sent');

-- --------------------------------------------------------

--
-- Table structure for table `token_blacklist`
--

CREATE TABLE `token_blacklist` (
  `id` int(11) NOT NULL,
  `token` varchar(512) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `token_blacklist`
--

INSERT INTO `token_blacklist` (`id`, `token`, `created_at`) VALUES
(8, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImNlbGVzdGlhbDMyMjAyQGdtYWlsLmNvbSIsImV4cCI6MTc0NDgyODA0Mn0.zOS3JeRft_XyOvD2SKNDee_WDhNYwEWh4iv0ZZTuuM4', '2025-04-16 17:27:53'),
(9, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImlhbXBvZ2kyMTNAZ21haWwuY29tIiwiZXhwIjoxNzQ0ODI4ODQwfQ.FkEmEgJgRFkp0QeQSlhogVuDzX5bQ7z4W98ujLEkZy8', '2025-04-16 17:52:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accepted_fire_rescues`
--
ALTER TABLE `accepted_fire_rescues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rescue_details_id` (`rescue_details_id`);

--
-- Indexes for table `admin_creds`
--
ALTER TABLE `admin_creds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incident_report`
--
ALTER TABLE `incident_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations_markers`
--
ALTER TABLE `locations_markers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_id` (`type_id`);

--
-- Indexes for table `location_type_markers`
--
ALTER TABLE `location_type_markers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type_name` (`type_name`);

--
-- Indexes for table `rescue_details`
--
ALTER TABLE `rescue_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rescue_selected_stations`
--
ALTER TABLE `rescue_selected_stations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rescue_details_id` (`rescue_details_id`);

--
-- Indexes for table `token_blacklist`
--
ALTER TABLE `token_blacklist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accepted_fire_rescues`
--
ALTER TABLE `accepted_fire_rescues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `admin_creds`
--
ALTER TABLE `admin_creds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `incident_report`
--
ALTER TABLE `incident_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=235;

--
-- AUTO_INCREMENT for table `locations_markers`
--
ALTER TABLE `locations_markers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `location_type_markers`
--
ALTER TABLE `location_type_markers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rescue_details`
--
ALTER TABLE `rescue_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `rescue_selected_stations`
--
ALTER TABLE `rescue_selected_stations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- AUTO_INCREMENT for table `token_blacklist`
--
ALTER TABLE `token_blacklist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accepted_fire_rescues`
--
ALTER TABLE `accepted_fire_rescues`
  ADD CONSTRAINT `accepted_fire_rescues_ibfk_1` FOREIGN KEY (`rescue_details_id`) REFERENCES `rescue_details` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `locations_markers`
--
ALTER TABLE `locations_markers`
  ADD CONSTRAINT `locations_markers_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `location_type_markers` (`id`);

--
-- Constraints for table `rescue_selected_stations`
--
ALTER TABLE `rescue_selected_stations`
  ADD CONSTRAINT `rescue_selected_stations_ibfk_1` FOREIGN KEY (`rescue_details_id`) REFERENCES `rescue_details` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

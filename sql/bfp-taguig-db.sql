-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2025 at 08:27 AM
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
(9, 54, 'FIR-20250401-000008', 'celestial', 'Fire Station 4 - Bagumbayan', 14.59486720, 121.07776000, '2025-04-04 13:49:02', '', NULL);

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
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `admin_position` varchar(255) NOT NULL,
  `admin_permissions` longtext NOT NULL,
  `verified` tinyint(4) NOT NULL DEFAULT 0,
  `session_id` varchar(255) DEFAULT NULL
) ;

--
-- Dumping data for table `admin_creds`
--

INSERT INTO `admin_creds` (`id`, `admin_id`, `branch`, `first_name`, `middle_name`, `last_name`, `contact_number`, `email`, `password`, `admin_position`, `admin_permissions`, `verified`, `session_id`) VALUES
(20, 'BFPT-17755063', '', 'Mar Steven', 'n/a', 'Celestial', '09994657669', 'celestial32202@gmail.com', '$2y$10$qX2f4Z426ngrQMXr0V2N1OfRTaGJX5yfEObZP1veZqylSV9luBVvO', 'Command Officer Head', '{\"main_dashboard\":1,\"manage_accounts\":1,\"edit_accounts\":1,\"manage_reports\":1,\"monitor_rescue\":1}', 1, NULL),
(22, 'BFPT-50593094', 'Fire Station 4 - Bagumbayan', 'mar steven', 'test', 'celestial', '09994657669', 'iampogi213@gmail.com', '$2y$10$3Mx14G8O7ubH0V.J.uWcHONLWiheYfcZi1R7yy7384TdMlZe5Xzby', 'Fire Officer', '{\"main_dashboard\":1,\"recieve_rescue_reports\":1}', 1, NULL),
(23, 'BFPT-04063828', 'Command Officer Head', 'genesis', 'a', 'majestral', '09994657669', 'celestialmarsteven22@gmail.com', '$2y$10$VXHLNZQ5iDmMJ9UQ.1f23eoAUdLwf57Cbs8jNGFGFr9SlaQPvDK4a', 'Command Officer Head', '{\"main_dashboard\":1,\"manage_accounts\":1,\"edit_accounts\":1,\"manage_reports\":1,\"monitor_rescue\":1}', 1, NULL),
(24, 'BFPT-09340960', 'Fire Station 8 - Cuasay', 'Jan Gabriel', 'na', 'tobias', '09994657669', 'formapro32202@gmail.com', '$2y$10$bA5klEHRcxv1geJWQ1L9zeZDqip5tYUYBK3TR0nWCeW8WrGPFqvA.', 'Fire Officer', '{\"main_dashboard\":1,\"recieve_rescue_reports\":1}', 1, NULL);

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
  `connection_status` varchar(255) NOT NULL,
  `submitted_at` datetime DEFAULT NULL,
  `verified_by` varchar(255) NOT NULL,
  `verified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incident_report`
--

INSERT INTO `incident_report` (`id`, `incident_id`, `connection_id`, `reporter_name`, `contact_no`, `incident_location`, `info_message`, `gps_location`, `report_status`, `connection_status`, `submitted_at`, `verified_by`, `verified_at`) VALUES
(113, 'FIR-20250401-000001', 'ffdsb24888g234ao', 'TEST SYS', 'TEST SYS', 'TEST SYS', 'TEST SYS', '14.6112512, 121.0679296', 'Approved', 'Disconnected', '2025-04-02 01:58:58', 'Celestial', '2025-04-02 01:59:15'),
(114, 'FIR-20250401-000002', 'ffdsb24888g234ao', 'TEST SYS', 'TEST SYS', 'TEST SYS', 'TEST SYS', '14.6112512, 121.0679296', 'Approved', 'Disconnected', '2025-04-02 02:03:30', 'Celestial', '2025-04-02 02:03:35'),
(115, 'FIR-20250401-000003', 'ffdsb24888g234ao', 'TEST SYS', 'TEST SYS', 'TEST SYS', 'TEST SYS', '14.6112512, 121.0679296', 'Approved', 'Disconnected', '2025-04-02 02:06:27', 'Celestial', '2025-04-02 02:08:51'),
(116, 'FIR-20250401-000004', 'ffdsb24888g234ao', 'TEST SYS', 'TEST SYS', 'TEST SYS', 'TEST SYS', '14.6112512, 121.0679296', 'Approved', 'Disconnected', '2025-04-02 02:09:26', 'Celestial', '2025-04-02 02:09:29'),
(117, 'FIR-20250401-000005', 'ffdsb24888g234ao', 'TEST SYS', 'TEST SYS', 'TEST SYS', 'TEST SYS', '14.6112512, 121.0679296', 'Approved', 'Disconnected', '2025-04-02 02:12:53', 'Celestial', '2025-04-02 02:12:55'),
(118, 'FIR-20250401-000006', 'ffdsb24888g234ao', 'TEST SYS', 'TEST SYS', 'TEST SYS', 'TEST SYS', '14.6112512, 121.0679296', 'Approved', 'Disconnected', '2025-04-02 02:27:45', 'Celestial', '2025-04-02 02:27:47'),
(119, 'FIR-20250401-000007', 'ffdsb24888g234ao', 'TEST SYS', 'TEST SYS', 'TEST SYS', 'TEST SYS', '14.6112512, 121.0679296', 'Approved', 'Disconnected', '2025-04-02 02:50:26', 'Celestial', '2025-04-02 02:50:29'),
(120, 'FIR-20250401-000008', 'ffdsb24888g234ao', 'TEST SYS', 'TEST SYS', 'TEST SYS', 'TEST SYS', '14.6112512, 121.0679296', 'Approved', 'Disconnected', '2025-04-02 02:57:23', 'Celestial', '2025-04-02 03:00:09'),
(121, 'FIR-20250402-000009', 'ffdsb24888g234ao', 'TEST SYS', 'TEST SYS', 'TEST SYS', 'TEST SYS', 'Location request timed out', 'Approved', 'Disconnected', '2025-04-02 11:16:06', 'Celestial', '2025-04-02 11:16:11'),
(122, 'FIR-20250402-000010', 'ffdsb24888g234ao', 'TEST SYS', 'TEST SYS', 'TEST SYS', 'TEST SYS', '14.6079744, 121.0679296', 'Declined', 'Disconnected', '2025-04-02 22:16:09', 'majestral', '2025-04-03 05:06:53'),
(123, 'FIR-20250402-000011', 'ffdsb24888g234ao', 'TEST SYS', 'TEST SYS', 'TEST SYS', 'TEST SYS', '14.6079744, 121.0679296', 'Declined', 'Disconnected', '2025-04-03 05:08:12', 'Celestial', '2025-04-03 05:15:59'),
(124, 'FIR-20250402-000012', 'ffdsb24888g234ao', 'TEST SYS', 'TEST SYS', 'TEST SYS', 'TEST SYS', '14.6079744, 121.0679296', 'Declined', 'Disconnected', '2025-04-03 05:16:07', 'majestral', '2025-04-03 05:24:50'),
(125, 'FIR-20250402-000013', 'uqokmbiafej56k76', 'TEST GPS', 'TEST GPS', 'TEST GPS', 'TEST GPS', '14.6077, 121.0463', 'Declined', 'Disconnected', '2025-04-03 05:26:49', 'majestral', '2025-04-03 05:27:04');

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
(54, 'FIR-20250401-000008', 'TEST SYS', 'TEST SYS', 'ongoing', 'uqokmbiafej56k76', '2025-04-02 03:00:13', NULL, 121.06302739, 14.53719813, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpbmNpZGVudF9kYXRhIjp7ImluY2lkZW50X2lkIjoiRklSLTIwMjUwNDAxLTAwMDAwOCIsImxhdGl0dWRlIjoxNC41MzcxOTgxMjg2MjM5NywibG9uZ2l0dWRlIjoxMjEuMDYzMDI3Mzg1NTEyMzh9LCJpYXQiOjE3NDM1MzQwMTMsImV4cCI6MTc3NTA3MDAxM30.IW4OTfqdbrs5oQQFTvtuE0KMhq4w3tTTJA7OtM7lUNE'),
(55, 'FIR-20250402-000009', 'TEST SYS', 'TEST SYS', 'ongoing', 'uqokmbiafej56k76', '2025-04-02 11:16:20', NULL, 121.06036372, 14.54278629, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpbmNpZGVudF9kYXRhIjp7ImluY2lkZW50X2lkIjoiRklSLTIwMjUwNDAyLTAwMDAwOSIsImxhdGl0dWRlIjoxNC41NDI3ODYyOTQ1OTM2OTcsImxvbmdpdHVkZSI6MTIxLjA2MDM2MzcxNTcxNjk2fSwiaWF0IjoxNzQzNTYzNzgwLCJleHAiOjE3NzUwOTk3ODB9.V_TjBYbUHruY4ZLtYhXCKuxpE1QFYM27vrHm2Phk4mo');

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
(94, 54, 'Fire Station 4 - Bagumbayan', 'sent'),
(95, 54, 'Fire Station 9 - West Rembo', 'sent'),
(96, 54, 'Fire Station 6 - Palingon-Tipas', 'sent'),
(97, 54, 'Fire Station 8 - Cuasay', 'sent'),
(98, 54, 'Fire Station 5 - North Signal', 'sent'),
(99, 54, 'Fire Station 7 - Wawa', 'sent'),
(100, 54, 'Fire Station 3 - Ibayo-Tipas Station', 'sent'),
(101, 54, 'Fire Station 2 - Central Signal Station', 'sent'),
(102, 54, 'Fire Station 1 - Arca South Station', 'sent'),
(103, 54, 'Fire Station 4 - Bagumbayan', 'sent'),
(104, 55, 'Fire Station 10 - Comembo', 'sent'),
(105, 55, 'Fire Station 9 - West Rembo', 'sent'),
(106, 55, 'Fire Station 6 - Palingon-Tipas', 'sent'),
(107, 55, 'Fire Station 8 - Cuasay', 'sent'),
(108, 55, 'Fire Station 3 - Ibayo-Tipas Station', 'sent');

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
(6, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImZvcm1hcHJvMzIyMDJAZ21haWwuY29tIiwiZXhwIjoxNzQzNjg4OTQ4fQ.6AxPKdt6zZnDPZaB0YsJ-aTFrpX0pMnx_0G4otBc3vc', '2025-04-03 13:04:46');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `admin_creds`
--
ALTER TABLE `admin_creds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incident_report`
--
ALTER TABLE `incident_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `rescue_selected_stations`
--
ALTER TABLE `rescue_selected_stations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `token_blacklist`
--
ALTER TABLE `token_blacklist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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

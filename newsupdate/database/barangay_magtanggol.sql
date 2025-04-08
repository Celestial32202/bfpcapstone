-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2025 at 07:49 AM
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
-- Database: `barangay_magtanggol`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_acc`
--

CREATE TABLE `admin_acc` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_acc`
--

INSERT INTO `admin_acc` (`id`, `username`, `email`, `password`) VALUES
(1, 'admin', 'skmagtanggol123@gmail.com', '$2y$10$A8cK9ucRJ8BcLrL8Hf/UH.FaFbS29qJcpEKoGLKsGZpizwN5fVlhO');

-- --------------------------------------------------------

--
-- Table structure for table `contact_msg`
--

CREATE TABLE `contact_msg` (
  `contact_msg_id` int(11) NOT NULL,
  `contact_name` varchar(255) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `contact_subject` varchar(255) NOT NULL,
  `contact_message` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news_updates`
--

CREATE TABLE `news_updates` (
  `updates_id` int(11) NOT NULL,
  `update_num` int(255) NOT NULL,
  `update_title` varchar(255) NOT NULL,
  `update_desc` longtext NOT NULL,
  `update_img` varchar(255) NOT NULL,
  `update_date` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `update-url` mediumtext NOT NULL,
  `update_active_stat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news_updates`
--

INSERT INTO `news_updates` (`updates_id`, `update_num`, `update_title`, `update_desc`, `update_img`, `update_date`, `update-url`, `update_active_stat`) VALUES
(108, 84840522, '𝐓𝐈𝐆𝐍𝐀𝐍 | 𝐁𝐚𝐫𝐚𝐧𝐠𝐚𝐲 𝐌𝐚𝐠𝐭𝐚𝐧𝐠𝐠𝐨𝐥 𝐁𝐚𝐬𝐤𝐞𝐭𝐛𝐚𝐥𝐥 𝐓𝐫𝐲𝐨𝐮𝐭𝐬 𝟐𝟎𝟐𝟒', '<p>Inaanyayahan ng ating Sangguniang Kabataan ng Magtanggol ang bawat lalaking kabataan na may 𝗲𝗱𝗮𝗱 𝗻𝗮 𝟮𝟰 𝗽𝗮𝗯𝗮𝗯𝗮 para sa ating 𝗕𝗮𝗿𝗮𝗻𝗴𝗮𝘆 𝗠𝗮𝗴𝘁𝗮𝗻𝗴𝗴𝗼𝗹 𝗕𝗮𝘀𝗸𝗲𝘁𝗯𝗮𝗹𝗹 𝗧𝗿𝘆𝗼𝘂𝘁𝘀 𝟮𝟬𝟮𝟰 na gaganapin ngayong darating na Linggo, April 7, 2024, sa oras na 8PM.\r\n</p><p>Para sa mga katanungan, maaari lamang mag-iwan ng mensahe sa ating mga SK Officials o dito mismo sa ating Facebook Page. \r\n</p><p>Pubmat by SK Member Ralph Tuazon</p>', '[\"..\\/update-img\\/665e7c321aca6.png\"]', '2024-06-04 02:30:10', '', 1),
(109, 31236804, '𝐓𝐈𝐍𝐆𝐍𝐀𝐍 | 𝐊𝐚𝐭𝐢𝐩𝐮𝐧𝐚𝐧 𝐧𝐠 𝐊𝐚𝐛𝐚𝐭𝐚𝐚𝐧: 𝐁𝐚𝐭𝐚𝐧𝐠 𝐌𝐚𝐠𝐭𝐚𝐧𝐠𝐠𝐨𝐥 𝐘𝐨𝐮𝐭𝐡 𝐎𝐫𝐠𝐚𝐧𝐢𝐳𝐚𝐭𝐢𝐨𝐧 𝐅𝐢𝐫𝐬𝐭 𝐆𝐞𝐧𝐞𝐫𝐚𝐥 𝐀𝐬𝐬𝐞𝐦𝐛𝐥𝐲', '<p>Ngayong araw, April 7, 2024, ay isinagawa ng ating mga butihing SK Officials ang kanilang kauna-unahang 𝙆𝙖𝙩𝙞𝙥𝙪𝙣𝙖𝙣 𝙣𝙜 𝙆𝙖𝙗𝙖𝙩𝙖𝙖𝙣: 𝘽𝙖𝙩𝙖𝙣𝙜 𝙈𝙖𝙜𝙩𝙖𝙣𝙜𝙜𝙤𝙡 𝙔𝙤𝙪𝙩𝙝 𝙊𝙧𝙜𝙖𝙣𝙞𝙯𝙖𝙩𝙞𝙤𝙣 𝙂𝙚𝙣𝙚𝙧𝙖𝙡 𝘼𝙨𝙨𝙚𝙢𝙗𝙡𝙮. Nagpapasalamat kami sa mga kabataang nagpa-unlak ng oras para dumalo at makihalubilo sa nasabing pagtitipon na ito. Hangad naming makasama pa kayo sa aming mga susunod na proyekto!\r\n</p><p>𝙈𝙖𝙠𝙞-𝙞𝙨𝙖. 𝙈𝙖𝙠𝙞𝙡𝙖𝙝𝙤𝙠. 𝙈𝙖𝙠𝙞𝙝𝙖𝙡𝙪𝙗𝙞𝙡𝙤. 𝘿𝙖𝙝𝙞𝙡 𝙩𝙖𝙮𝙤 𝙖𝙣𝙜 Batang Magtanggol.</p><p>#SKMagtanggol</p>', '[\"..\\/update-img\\/665e7c4c4b3b7.png\"]', '2024-06-04 02:30:36', '', 1),
(110, 57875102, '𝐓𝐈𝐍𝐆𝐍𝐀𝐍 | 𝐔𝐧𝐚𝐧𝐠 𝐆𝐚𝐛𝐢 𝐧𝐠 𝐁𝐚𝐬𝐤𝐞𝐭𝐛𝐚𝐥𝐥 𝐓𝐫𝐲𝐨𝐮𝐭𝐬', '<p>Opisyal na ngang sinimulan ang Basketball Tryouts para sa ating barangay. Nais naming pasalamatan ang ating mga coaches na sina 𝘾𝙤𝙖𝙘𝙝 𝘾𝙝𝙧𝙞𝙨𝙩𝙞𝙖𝙣 𝙊𝙧𝙩𝙞𝙯, 𝘾𝙤𝙖𝙘𝙝 𝙉𝙞𝙘𝙝𝙤𝙡𝙚𝙞 𝙋𝙖𝙧𝙖𝙨, 𝙖𝙩 𝘾𝙤𝙖𝙘𝙝 𝙀𝙅 𝙃𝙤𝙣𝙩𝙖𝙣𝙖𝙧 para sa kanilang dedikasyon sa nasabing trayouts na ito.\r\n</p><p>Para sa iba pang detalye patungkol sa iba pang mga tryouts ng sports, maaari lamang patnubayan at abangan ang mga susunod na post dito sa ating Facebook page. Maraming salamat po.\r\n</p><p>#SKMagtanggol</p>', '[\"..\\/update-img\\/665e7c7e5fb0f.png\"]', '2024-06-04 02:31:26', '', 1),
(111, 72312480, '𝐓𝐈𝐍𝐆𝐍𝐀𝐍 | 𝐊𝐚𝐭𝐢𝐩𝐮𝐧𝐚𝐧 𝐧𝐠 𝐊𝐚𝐛𝐚𝐭𝐚𝐚𝐧: 𝐁𝐚𝐭𝐚𝐧𝐠 𝐌𝐚𝐠𝐭𝐚𝐧𝐠𝐠𝐨𝐥 𝐘𝐨𝐮𝐭𝐡 𝐎𝐫𝐠𝐚𝐧𝐢𝐳𝐚𝐭𝐢𝐨𝐧 𝐅𝐢𝐫𝐬𝐭 𝐆𝐞𝐧𝐞𝐫𝐚𝐥 𝐀𝐬𝐬𝐞𝐦𝐛𝐥𝐲 𝐎𝐟𝐟𝐢𝐜𝐞𝐫𝐬 𝐚𝐧𝐝 𝐒𝐩𝐞𝐜𝐢𝐚𝐥 𝐌𝐞𝐧𝐭𝐢𝐨𝐧𝐬', '<p>Isang pagpupugay at mainit na pasasalamat ang nais iparating ng ating SK Council para sa ating mga indibidwal na walang sawang nagpapakita ng suporta at pagmamahal para sa ating mga kabataan lalong-lalo na sa isinagawang 𝙆𝙖𝙩𝙞𝙥𝙪𝙣𝙖𝙣 𝙣𝙜 𝙆𝙖𝙗𝙖𝙩𝙖𝙖𝙣: 𝘽𝙖𝙩𝙖𝙣𝙜 𝙈𝙖𝙜𝙩𝙖𝙣𝙜𝙜𝙤𝙡 𝙔𝙤𝙪𝙩𝙝 𝙊𝙧𝙜𝙖𝙣𝙞𝙯𝙖𝙩𝙞𝙤𝙣 𝙁𝙞𝙧𝙨𝙩 𝙂𝙚𝙣𝙚𝙧𝙖𝙡 𝘼𝙨𝙨𝙚𝙢𝙗𝙡𝙮 na isinagawa kahapon, April 7.\r\n</p><p>Nais rin pasalamatan ng ating konseho ang mga indibidwal na nagpakita ng lakas ng loob na maihalal sa puwesto at tanggapin ang hamon bilang mga 𝙆𝙖𝙩𝙞𝙥𝙪𝙣𝙖𝙣 𝙣𝙜 𝙆𝙖𝙗𝙖𝙩𝙖𝙖𝙣 𝙊𝙛𝙛𝙞𝙘𝙚𝙧𝙨. Hangad namin ang inyong dedikasyon at tapat na serbisyo para sa ating mga kabataan. \r\n</p><p>𝙈𝙖𝙠𝙞-𝙞𝙨𝙖. 𝙈𝙖𝙠𝙞𝙡𝙖𝙝𝙤𝙠. 𝙈𝙖𝙠𝙞𝙝𝙖𝙡𝙪𝙗𝙞𝙡𝙤. 𝘿𝙖𝙝𝙞𝙡 𝙩𝙖𝙮𝙤 𝙖𝙣𝙜 Batang Magtanggol.\r\n</p><p>#SKMagtanggol</p>', '[\"..\\/update-img\\/665e7c9ba5a6b.png\"]', '2024-06-04 02:31:55', '', 1),
(112, 52828859, '𝐓𝐈𝐍𝐆𝐍𝐀𝐍 | 𝐅𝐥𝐚𝐠 𝐑𝐚𝐢𝐬𝐢𝐧𝐠 𝐂𝐞𝐫𝐞𝐦𝐨𝐧𝐲 𝐬𝐚 𝐩𝐚𝐧𝐠𝐮𝐧𝐠𝐮𝐧𝐚 𝐧𝐠 𝐁𝐚𝐫𝐚𝐧𝐠𝐚𝐲 𝐌𝐚𝐠𝐭𝐚𝐧𝐠𝐠𝐨𝐥', '<p>Ngayong araw, April 8, 2024, sa ganap na ala-siyete ng umaga ay dumalo ang ating SK Council sa ating 𝙁𝙡𝙖𝙜 𝙍𝙖𝙞𝙨𝙞𝙣𝙜 𝘾𝙚𝙧𝙚𝙢𝙤𝙣𝙮 na ginanap sa harap ng Municipal Hall ng Pateros. Kasama ang 𝘽𝙖𝙧𝙖𝙣𝙜𝙖𝙮 𝙈𝙖𝙜𝙩𝙖𝙣𝙜𝙜𝙤𝙡 𝘾𝙤𝙪𝙣𝙘𝙞𝙡 at ilang opisyal ng ating bayan sa pangunguna ng ating butihing Mayor na si 𝙈𝙖𝙮𝙤𝙧 𝙄𝙠𝙚 𝙋𝙤𝙣𝙘𝙚 𝙄𝙄𝙄, ay sabay-sabay nating inangat at nagbigay respeto sa nasabing seremonya na ito.\r\n</p><p>Matapos ang nasabing seremonya, dumaan at nagpaunlak ng bisita ang ating mga SK Officials sa opisina ng ating 𝘿𝙄𝙇𝙂 𝙋𝙖𝙩𝙚𝙧𝙤𝙨 𝙈𝙪𝙣𝙞𝙘𝙞𝙥𝙖𝙡 𝙇𝙤𝙘𝙖𝙡 𝙂𝙤𝙫𝙚𝙧𝙣𝙢𝙚𝙣𝙩 𝙊𝙥𝙚𝙧𝙖𝙩𝙞𝙤𝙣 𝙊𝙛𝙛𝙞𝙘𝙚𝙧 na si 𝙎𝙞𝙧 𝙅𝙤𝙣𝙖𝙨 𝙅𝙖𝙢𝙚𝙨 𝘼𝙜𝙤𝙩.\r\n</p><p>Nais rin pasalamatan ng SK Council si 𝙈𝙞𝙨𝙨 𝙍𝙚𝙜𝙞𝙣𝙚 𝙄𝙢𝙨𝙤𝙣 na nag-alay ng awitin para sa seremonya na ito.\r\n</p><p>#SKMagtanggol</p>', '[\"..\\/update-img\\/665e7cbe3140a.png\"]', '2024-06-04 02:32:30', '', 1),
(113, 96898151, '𝐓𝐈𝐆𝐍𝐀𝐍 | 𝐁𝐚𝐫𝐚𝐧𝐠𝐚𝐲 𝐌𝐚𝐠𝐭𝐚𝐧𝐠𝐠𝐨𝐥 𝐕𝐨𝐥𝐥𝐞𝐲𝐛𝐚𝐥𝐥 𝐓𝐫𝐲𝐨𝐮𝐭𝐬 𝟐𝟎𝟐𝟒', '<p>Inaanyayahan ng ating Sangguniang Kabataan ng Magtanggol ang bawat kabataan na may 𝗲𝗱𝗮𝗱 𝗻𝗮 𝟮𝟰 𝗽𝗮𝗯𝗮𝗯𝗮 para sa ating 𝗕𝗮𝗿𝗮𝗻𝗴𝗮𝘆 𝗠𝗮𝗴𝘁𝗮𝗻𝗴𝗴𝗼𝗹 𝗩𝗼𝗹𝗹𝗲𝘆𝗯𝗮𝗹𝗹 𝗧𝗿𝘆𝗼𝘂𝘁𝘀 𝟮𝟬𝟮𝟰 na gaganapin ngayong darating na Linggo, April 14, 2024, sa oras na 8AM.\r\n</p><p>Para sa mga katanungan, maaari lamang mag-iwan ng mensahe sa ating mga SK Officials o dito mismo sa ating Facebook Page. \r\n</p><p>Pubmat by SK Member Ralph Tuazon\r\n</p><p>#SKMagtanggol</p>', '[\"..\\/update-img\\/665e7cfe94f81.png\"]', '2024-06-04 02:33:34', '', 1),
(114, 44080597, '𝐓𝐈𝐍𝐆𝐍𝐀𝐍 | 𝐔𝐧𝐚𝐧𝐠 𝐀𝐫𝐚𝐰 𝐧𝐠 𝐕𝐨𝐥𝐥𝐞𝐲𝐛𝐚𝐥𝐥 𝐓𝐫𝐲𝐨𝐮𝐭𝐬', '<p>Kaninang umaga, April 14 ng 8AM, ay opisyal na ngang sinimulan ang Voleyball Tryouts para sa ating barangay. Nais naming pasalamatan ang ating coach na si 𝘾𝙤𝙖𝙘𝙝 𝙅𝘼 𝙍𝙤𝙡𝙙𝙖𝙣 para sa kaniyang dedikasyon sa nasabing trayouts na ito.\r\n</p><p>Para sa iba pang detalye patungkol sa iba pang mga tryouts ng sports, maaari lamang patnubayan at abangan ang mga susunod na posts dito sa ating Facebook page. Maraming salamat po.\r\n</p><p>#SKMagtanggol</p>', '[\"..\\/update-img\\/665e7d1f3184b.png\"]', '2024-06-04 02:34:07', '', 1),
(115, 31159993, '𝐓𝐈𝐆𝐍𝐀𝐍 | 𝐈𝐤𝐚𝐭𝐥𝐨𝐧𝐠 𝐒𝐩𝐞𝐜𝐢𝐚𝐥 𝐒𝐞𝐬𝐬𝐢𝐨𝐧 𝐧𝐠 𝐒𝐚𝐧𝐠𝐠𝐮𝐧𝐢𝐚𝐧𝐠 𝐊𝐚𝐛𝐚𝐭𝐚𝐚𝐧 𝐧𝐠 𝐁𝐚𝐫𝐚𝐧𝐠𝐚𝐲 𝐌𝐚𝐠𝐭𝐚𝐧𝐠𝐠𝐨𝐥', '<p>Naisagawa ng ating mga butihing lingkod ng SK Council ang kanilang Ikatlong Special Session ngayong gabi, ika-16 ng Abril taong 2024.\r\n</p><p>Caption by SK Member Nichole Menguito\r\n</p><p>Pubmat by SK Members Hannah Daquina &amp; Ralph Tuazon\r\n</p><p>#SKMagtanggol</p>', '[\"..\\/update-img\\/665e7d3023856.png\"]', '2024-06-04 02:34:24', '', 1),
(116, 82299855, '𝐓𝐈𝐆𝐍𝐀𝐍 | 𝐈𝐤𝐚-𝐚𝐧𝐢𝐦 𝐧𝐚 𝐑𝐞𝐠𝐮𝐥𝐚𝐫 𝐒𝐞𝐬𝐬𝐢𝐨𝐧 𝐧𝐠 𝐒𝐚𝐧𝐠𝐠𝐮𝐧𝐢𝐚𝐧𝐠 𝐊𝐚𝐛𝐚𝐭𝐚𝐚𝐧 𝐧𝐠 𝐁𝐚𝐫𝐚𝐧𝐠𝐚𝐲 𝐌𝐚𝐠𝐭𝐚𝐧𝐠𝐠𝐨𝐥', '<p>Naisagawa ng ating mga butihing lingkod ng SK Council ang kanilang Ika-anim na Regular Session ngayong gabi, ika-22 ng Abril taong 2024.\r\n</p><p>Caption by SK Member Nichole Menguito\r\n</p><p>Pubmat by SK Members Hannah Daquina &amp; Ralph Tuazon</p>', '[\"..\\/update-img\\/665e7d3fcdf39.png\"]', '2024-06-04 02:34:39', '', 1),
(117, 47421211, '𝐓𝐈𝐆𝐍𝐀𝐍 | 𝐏𝐚𝐥𝐚𝐫𝐨 𝐬𝐚 𝐃𝐚𝐚𝐧 𝐩𝐚𝐫𝐚 𝐬𝐚 𝐏𝐢𝐬𝐭𝐚 𝐧𝐠 𝐊𝐫𝐮𝐬', '<p>Nagtipon-tipon ngayong araw, May 4, ang bawat kabataan ng Barangay Magtanggol para sa ating 𝙋𝙖𝙡𝙖𝙧𝙤 𝙨𝙖 𝘿𝙖𝙖𝙣 para sa ating Pista ng Krus. Kitang-kita sa bawat mukha ng mga dumalo ang saya at tuwa sa kanilang pakikisalo at pakikihalubilo sa ganap na ito. \r\n</p><p>Nais din naming pasalamatan ang mga taong taos pusong tumulong at nagbigay ng suporta sa pista na ito mula sa Hermanos and Hermanas, Santos Family, at ang Pamayanan ng Magtanggol St John the Baptist PPU.\r\n</p><p>Hanggang sa muli, mga Batang Magtanggol! \r\n</p><p>𝙈𝙖𝙠𝙞-𝙞𝙨𝙖. 𝙈𝙖𝙠𝙞𝙡𝙖𝙝𝙤𝙠. 𝙈𝙖𝙠𝙞𝙝𝙖𝙡𝙪𝙗𝙞𝙡𝙤. 𝘿𝙖𝙝𝙞𝙡 𝙩𝙖𝙮𝙤 𝙖𝙣𝙜 𝘽𝙖𝙩𝙖𝙣𝙜 𝙈𝙖𝙜𝙩𝙖𝙣𝙜𝙜𝙤𝙡.\r\n</p><p>#BMYO \r\n</p><p>#SKMagtanggol</p>', '[\"..\\/update-img\\/665e7d7344744.png\"]', '2024-06-04 02:35:31', '', 1),
(118, 57827235, '𝐓𝐈𝐆𝐍𝐀𝐍 | 𝐈𝐤𝐚-𝐩𝐢𝐭𝐨 𝐧𝐚 𝐑𝐞𝐠𝐮𝐥𝐚𝐫 𝐒𝐞𝐬𝐬𝐢𝐨𝐧 𝐧𝐠 𝐒𝐚𝐧𝐠𝐠𝐮𝐧𝐢𝐚𝐧𝐠 𝐊𝐚𝐛𝐚𝐭𝐚𝐚𝐧 𝐧𝐠 𝐁𝐚𝐫𝐚𝐧𝐠𝐚𝐲 𝐌𝐚𝐠𝐭𝐚𝐧𝐠𝐠𝐨𝐥', '<p>Naisagawa ng ating mga butihing lingkod ng SK Council ang kanilang Ika-pito na Regular Session kagabi, ika-27 ng Mayo taong 2024.\r\n</p><p>Caption by SK Member Nichole Menguito\r\n</p><p>Pubmat by SK Members Hannah Daquina &amp; Ralph Tuazon\r\n</p><p>#SKMagtanggol</p>', '[\"..\\/update-img\\/665e7d86a9b9c.png\"]', '2024-06-04 02:35:50', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notify_all`
--

CREATE TABLE `notify_all` (
  `notif_counter` int(11) NOT NULL,
  `notif_id` int(255) NOT NULL,
  `notif_title` varchar(255) NOT NULL,
  `content_title` varchar(255) NOT NULL,
  `notif_img` varchar(255) NOT NULL,
  `notif_time` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `notif_active_stat` int(11) NOT NULL,
  `update_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notify_all`
--

INSERT INTO `notify_all` (`notif_counter`, `notif_id`, `notif_title`, `content_title`, `notif_img`, `notif_time`, `notif_active_stat`, `update_id`) VALUES
(9, 19123237, 'Sangguniang Kabataan has posted a new update!', 'TEST TEST TEST YES', '[\"..\\/update-img\\/665bd6eef1918.PNG\"]', '2024-06-02 02:20:31', 0, 88025321),
(10, 50737523, 'Sangguniang Kabataan has posted a new update!', 'testing new', '[\"..\\/update-img\\/665bd8748cdeb.PNG\"]', '2024-06-02 02:27:00', 0, 26142320),
(11, 25666805, 'Sangguniang Kabataan has posted a new update!', 'asdasdasdasdasdas', '[\"..\\/update-img\\/665bf893ed3e7.PNG\",\"..\\/update-img\\/665bf893efe7c.PNG\",\"..\\/update-img\\/665bf893f05da.PNG\"]', '2024-06-02 04:44:03', 0, 87014140),
(12, 89568121, 'Sangguniang Kabataan has posted a new update!', 'new post', '[\"..\\/update-img\\/665c0cfd56dde.jpg\",\"..\\/update-img\\/665c0cfd59fac.PNG\",\"..\\/update-img\\/665c0cfd5a967.PNG\"]', '2024-06-02 06:11:09', 0, 48964224),
(13, 53294442, 'Sangguniang Kabataan has posted a new update!', 'test', '[\"..\\/update-img\\/665c19d863118.jpg\",\"..\\/update-img\\/665c19d86491d.jpg\",\"..\\/update-img\\/665c19d8655c1.jpg\"]', '2024-06-02 07:06:00', 1, 22763349),
(14, 40452270, 'Sangguniang Kabataan has posted a new update!', '𝐓𝐈𝐆𝐍𝐀𝐍 | 𝐁𝐚𝐫𝐚𝐧𝐠𝐚𝐲 𝐌𝐚𝐠𝐭𝐚𝐧𝐠𝐠𝐨𝐥 𝐁𝐚𝐬𝐤𝐞𝐭𝐛𝐚𝐥𝐥 𝐓𝐫𝐲𝐨𝐮𝐭𝐬 𝟐𝟎𝟐𝟒', '[\"..\\/update-img\\/665e7c321aca6.png\"]', '2024-06-04 02:30:10', 1, 84840522),
(15, 51286923, 'Sangguniang Kabataan has posted a new update!', '𝐓𝐈𝐍𝐆𝐍𝐀𝐍 | 𝐊𝐚𝐭𝐢𝐩𝐮𝐧𝐚𝐧 𝐧𝐠 𝐊𝐚𝐛𝐚𝐭𝐚𝐚𝐧: 𝐁𝐚𝐭𝐚𝐧𝐠 𝐌𝐚𝐠𝐭𝐚𝐧𝐠𝐠𝐨𝐥 𝐘𝐨𝐮𝐭𝐡 𝐎𝐫𝐠𝐚𝐧𝐢𝐳𝐚𝐭𝐢𝐨𝐧 𝐅𝐢𝐫𝐬𝐭 𝐆𝐞𝐧𝐞𝐫𝐚𝐥 𝐀𝐬𝐬𝐞𝐦𝐛𝐥𝐲', '[\"..\\/update-img\\/665e7c4c4b3b7.png\"]', '2024-06-04 02:30:36', 1, 31236804),
(16, 34156609, 'Sangguniang Kabataan has posted a new update!', '𝐓𝐈𝐍𝐆𝐍𝐀𝐍 | 𝐔𝐧𝐚𝐧𝐠 𝐆𝐚𝐛𝐢 𝐧𝐠 𝐁𝐚𝐬𝐤𝐞𝐭𝐛𝐚𝐥𝐥 𝐓𝐫𝐲𝐨𝐮𝐭𝐬', '[\"..\\/update-img\\/665e7c7e5fb0f.png\"]', '2024-06-04 02:31:26', 1, 57875102),
(17, 44649829, 'Sangguniang Kabataan has posted a new update!', '𝐓𝐈𝐍𝐆𝐍𝐀𝐍 | 𝐊𝐚𝐭𝐢𝐩𝐮𝐧𝐚𝐧 𝐧𝐠 𝐊𝐚𝐛𝐚𝐭𝐚𝐚𝐧: 𝐁𝐚𝐭𝐚𝐧𝐠 𝐌𝐚𝐠𝐭𝐚𝐧𝐠𝐠𝐨𝐥 𝐘𝐨𝐮𝐭𝐡 𝐎𝐫𝐠𝐚𝐧𝐢𝐳𝐚𝐭𝐢𝐨𝐧 𝐅𝐢𝐫𝐬𝐭 𝐆𝐞𝐧𝐞𝐫𝐚𝐥 𝐀𝐬𝐬𝐞𝐦𝐛𝐥𝐲 𝐎𝐟𝐟𝐢𝐜𝐞𝐫𝐬 𝐚𝐧𝐝 𝐒𝐩𝐞𝐜𝐢𝐚𝐥 𝐌𝐞𝐧𝐭𝐢𝐨𝐧𝐬', '[\"..\\/update-img\\/665e7c9ba5a6b.png\"]', '2024-06-04 02:31:55', 1, 72312480),
(18, 23901940, 'Sangguniang Kabataan has posted a new update!', '𝐓𝐈𝐍𝐆𝐍𝐀𝐍 | 𝐅𝐥𝐚𝐠 𝐑𝐚𝐢𝐬𝐢𝐧𝐠 𝐂𝐞𝐫𝐞𝐦𝐨𝐧𝐲 𝐬𝐚 𝐩𝐚𝐧𝐠𝐮𝐧𝐠𝐮𝐧𝐚 𝐧𝐠 𝐁𝐚𝐫𝐚𝐧𝐠𝐚𝐲 𝐌𝐚𝐠𝐭𝐚𝐧𝐠𝐠𝐨𝐥', '[\"..\\/update-img\\/665e7cbe3140a.png\"]', '2024-06-04 02:32:30', 1, 52828859),
(19, 80122003, 'Sangguniang Kabataan has posted a new update!', '𝐓𝐈𝐆𝐍𝐀𝐍 | 𝐁𝐚𝐫𝐚𝐧𝐠𝐚𝐲 𝐌𝐚𝐠𝐭𝐚𝐧𝐠𝐠𝐨𝐥 𝐕𝐨𝐥𝐥𝐞𝐲𝐛𝐚𝐥𝐥 𝐓𝐫𝐲𝐨𝐮𝐭𝐬 𝟐𝟎𝟐𝟒', '[\"..\\/update-img\\/665e7cfe94f81.png\"]', '2024-06-04 02:33:34', 1, 96898151),
(20, 75409397, 'Sangguniang Kabataan has posted a new update!', '𝐓𝐈𝐍𝐆𝐍𝐀𝐍 | 𝐔𝐧𝐚𝐧𝐠 𝐀𝐫𝐚𝐰 𝐧𝐠 𝐕𝐨𝐥𝐥𝐞𝐲𝐛𝐚𝐥𝐥 𝐓𝐫𝐲𝐨𝐮𝐭𝐬', '[\"..\\/update-img\\/665e7d1f3184b.png\"]', '2024-06-04 02:34:07', 1, 44080597),
(21, 46110931, 'Sangguniang Kabataan has posted a new update!', '𝐓𝐈𝐆𝐍𝐀𝐍 | 𝐈𝐤𝐚𝐭𝐥𝐨𝐧𝐠 𝐒𝐩𝐞𝐜𝐢𝐚𝐥 𝐒𝐞𝐬𝐬𝐢𝐨𝐧 𝐧𝐠 𝐒𝐚𝐧𝐠𝐠𝐮𝐧𝐢𝐚𝐧𝐠 𝐊𝐚𝐛𝐚𝐭𝐚𝐚𝐧 𝐧𝐠 𝐁𝐚𝐫𝐚𝐧𝐠𝐚𝐲 𝐌𝐚𝐠𝐭𝐚𝐧𝐠𝐠𝐨𝐥', '[\"..\\/update-img\\/665e7d3023856.png\"]', '2024-06-04 02:34:24', 1, 31159993),
(22, 57705591, 'Sangguniang Kabataan has posted a new update!', '𝐓𝐈𝐆𝐍𝐀𝐍 | 𝐈𝐤𝐚-𝐚𝐧𝐢𝐦 𝐧𝐚 𝐑𝐞𝐠𝐮𝐥𝐚𝐫 𝐒𝐞𝐬𝐬𝐢𝐨𝐧 𝐧𝐠 𝐒𝐚𝐧𝐠𝐠𝐮𝐧𝐢𝐚𝐧𝐠 𝐊𝐚𝐛𝐚𝐭𝐚𝐚𝐧 𝐧𝐠 𝐁𝐚𝐫𝐚𝐧𝐠𝐚𝐲 𝐌𝐚𝐠𝐭𝐚𝐧𝐠𝐠𝐨𝐥', '[\"..\\/update-img\\/665e7d3fcdf39.png\"]', '2024-06-04 02:34:39', 1, 82299855),
(23, 56893955, 'Sangguniang Kabataan has posted a new update!', '𝐓𝐈𝐆𝐍𝐀𝐍 | 𝐏𝐚𝐥𝐚𝐫𝐨 𝐬𝐚 𝐃𝐚𝐚𝐧 𝐩𝐚𝐫𝐚 𝐬𝐚 𝐏𝐢𝐬𝐭𝐚 𝐧𝐠 𝐊𝐫𝐮𝐬', '[\"..\\/update-img\\/665e7d7344744.png\"]', '2024-06-04 02:35:31', 1, 47421211),
(24, 35315211, 'Sangguniang Kabataan has posted a new update!', '𝐓𝐈𝐆𝐍𝐀𝐍 | 𝐈𝐤𝐚-𝐩𝐢𝐭𝐨 𝐧𝐚 𝐑𝐞𝐠𝐮𝐥𝐚𝐫 𝐒𝐞𝐬𝐬𝐢𝐨𝐧 𝐧𝐠 𝐒𝐚𝐧𝐠𝐠𝐮𝐧𝐢𝐚𝐧𝐠 𝐊𝐚𝐛𝐚𝐭𝐚𝐚𝐧 𝐧𝐠 𝐁𝐚𝐫𝐚𝐧𝐠𝐚𝐲 𝐌𝐚𝐠𝐭𝐚𝐧𝐠𝐠𝐨𝐥', '[\"..\\/update-img\\/665e7d86a9b9c.png\"]', '2024-06-04 02:35:50', 1, 57827235);

-- --------------------------------------------------------

--
-- Table structure for table `survey_information`
--

CREATE TABLE `survey_information` (
  `survey_id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `full_address` varchar(255) NOT NULL,
  `civil_status` varchar(255) NOT NULL,
  `age_group` varchar(255) NOT NULL,
  `youth_class` varchar(255) NOT NULL,
  `youth_class_needs` varchar(255) NOT NULL,
  `work_status` varchar(255) NOT NULL,
  `educ_background` varchar(255) NOT NULL,
  `sk_voter` varchar(255) NOT NULL,
  `voted` varchar(255) NOT NULL,
  `date_sub` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_creds`
--

CREATE TABLE `users_creds` (
  `id_counter` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `hashed_password` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `birth_date` date NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `verif_code` varchar(255) NOT NULL,
  `verif_token` varchar(255) NOT NULL,
  `acc_status` varchar(255) NOT NULL,
  `survey_confirm` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_creds`
--

INSERT INTO `users_creds` (`id_counter`, `email`, `hashed_password`, `first_name`, `middle_name`, `last_name`, `birth_date`, `phone_number`, `verif_code`, `verif_token`, `acc_status`, `survey_confirm`) VALUES
(36, 'celestial32202@gmail.com', '$2y$10$k./sd.pdQAjYEpXl0JfcSO8xt8.NXKpyJRGzFB.i2HlcBDPeJy0X6', 'Mar Steven', 'NA', 'Celestial', '2002-03-22', '09994657669', '0', '', 'verified', ''),
(37, 'celestialmarsteven22@gmail.com', '$2y$10$6D7hVt.qyEBTSwrTNo19YO/M7iyQeugxhta9dudK0B3Ur1tJDBzai', 'Mar Steven', 'NA', 'CELESTIAL', '2024-05-28', '09994657669', '0', '', 'verified', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_acc`
--
ALTER TABLE `admin_acc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_msg`
--
ALTER TABLE `contact_msg`
  ADD PRIMARY KEY (`contact_msg_id`);

--
-- Indexes for table `news_updates`
--
ALTER TABLE `news_updates`
  ADD PRIMARY KEY (`updates_id`);

--
-- Indexes for table `notify_all`
--
ALTER TABLE `notify_all`
  ADD PRIMARY KEY (`notif_counter`);

--
-- Indexes for table `survey_information`
--
ALTER TABLE `survey_information`
  ADD PRIMARY KEY (`survey_id`);

--
-- Indexes for table `users_creds`
--
ALTER TABLE `users_creds`
  ADD PRIMARY KEY (`id_counter`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_acc`
--
ALTER TABLE `admin_acc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_msg`
--
ALTER TABLE `contact_msg`
  MODIFY `contact_msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `news_updates`
--
ALTER TABLE `news_updates`
  MODIFY `updates_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `notify_all`
--
ALTER TABLE `notify_all`
  MODIFY `notif_counter` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `survey_information`
--
ALTER TABLE `survey_information`
  MODIFY `survey_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users_creds`
--
ALTER TABLE `users_creds`
  MODIFY `id_counter` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

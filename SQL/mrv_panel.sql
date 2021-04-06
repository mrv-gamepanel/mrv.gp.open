-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 04, 2021 at 11:55 PM
-- Server version: 10.3.25-MariaDB-0ubuntu0.20.04.1
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mrv-gp`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `Username` text NOT NULL,
  `Password` text NOT NULL,
  `Email` text NOT NULL,
  `Name` text NOT NULL,
  `Lastname` text NOT NULL,
  `Image` text DEFAULT NULL,
  `Token` text DEFAULT NULL,
  `Rank` text DEFAULT NULL,
  `Status` text DEFAULT NULL,
  `AdminPerm` text NOT NULL,
  `AdminSupp` text NOT NULL,
  `createdBy` text DEFAULT NULL,
  `lastactivity` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `Username`, `Password`, `Email`, `Name`, `Lastname`, `Image`, `Token`, `Rank`, `Status`, `AdminPerm`, `AdminSupp`, `createdBy`, `lastactivity`) VALUES
(8, 'breaK.', 'fe01ce2a7fbac8fafaed7c982a04e229', 'break@mrv-hosting.com', 'breaK.', '', 'assets/img/i/logo/logo.png', '1312', '5', '1', 'a:4:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";}', 'a:5:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";}', '8', '1591067036');

-- --------------------------------------------------------

--
-- Table structure for table `box_list`
--

CREATE TABLE `box_list` (
  `id` int(11) NOT NULL,
  `Name` text DEFAULT NULL,
  `Host` text DEFAULT NULL,
  `Username` text DEFAULT NULL,
  `Password` text DEFAULT NULL,
  `sshPort` text DEFAULT NULL,
  `ftpPort` text DEFAULT NULL,
  `Status` text DEFAULT NULL,
  `Online` text DEFAULT NULL,
  `isStart` text DEFAULT NULL,
  `Note` text DEFAULT NULL,
  `boxLocation` text DEFAULT NULL,
  `gameID` text DEFAULT NULL,
  `autoRestart` text DEFAULT NULL,
  `createdBy` text DEFAULT NULL,
  `createdDate` text DEFAULT NULL,
  `lastactivity` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `box_list`
--

INSERT INTO `box_list` (`id`, `Name`, `Host`, `Username`, `Password`, `sshPort`, `ftpPort`, `Status`, `Online`, `isStart`, `Note`, `boxLocation`, `gameID`, `autoRestart`, `createdBy`, `createdDate`, `lastactivity`) VALUES
(1, 'TEst for yt', '157.90.244.110', 'root', 'demo123', '22', '21', '1', '1', '1', 'test', 'Germany', '2', '', '8', '04/04/2021, 23:31pm', '1617571880');

-- --------------------------------------------------------

--
-- Table structure for table `fdl_servers`
--

CREATE TABLE `fdl_servers` (
  `id` int(11) NOT NULL,
  `boxID` text DEFAULT NULL,
  `Username` text DEFAULT NULL,
  `Password` text DEFAULT NULL,
  `userID` text DEFAULT NULL,
  `Install` text DEFAULT NULL,
  `isFree` text DEFAULT NULL,
  `expiresFor` text DEFAULT NULL,
  `createdBy` text DEFAULT NULL,
  `createdDate` text DEFAULT NULL,
  `lastactivity` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fdl_servers`
--

INSERT INTO `fdl_servers` (`id`, `boxID`, `Username`, `Password`, `userID`, `Install`, `isFree`, `expiresFor`, `createdBy`, `createdDate`, `lastactivity`) VALUES
(3, '3', 'srv_1_1_ufqae', 'FRFO3DbF', '1', 'default', '0', NULL, '8', '28/08/2020, 15:50pm', '1598622654'),
(4, '3', 'srv_70_2_7b8jx', 'Zb3mVA5l', '70', NULL, '0', NULL, '12', '14/09/2020, 00:01am', '1600034471'),
(5, '3', 'srv_57_2_n216p', '27HDCT9G', '57', NULL, '0', NULL, '8', '14/09/2020, 21:41pm', '1600112466'),
(6, '3', 'srv_84_3_4x3gk', 'lxSaH8wa', '84', NULL, '0', NULL, '12', '17/09/2020, 18:46pm', '1600361208'),
(7, '3', 'srv_100_3_6lvjg', 'jP3OpenJ', '100', NULL, '0', NULL, '11', '20/09/2020, 21:27pm', '1600630077'),
(8, '3', 'srv_84_4_8gj6x', '3FQy8mbh', '84', NULL, '0', NULL, '11', '25/09/2020, 17:54pm', '1601049250'),
(9, '3', 'srv_114_2_ju3ct', '7ElaYArE', '114', NULL, '0', NULL, '11', '26/09/2020, 19:23pm', '1601141027'),
(10, '3', 'srv_79_5_moyfu', 'wGobC2om', '79', NULL, '0', NULL, '11', '28/09/2020, 22:54pm', '1601326467');

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `Name` text DEFAULT NULL,
  `smName` text DEFAULT NULL,
  `Icon` text DEFAULT NULL,
  `bg_img` text DEFAULT NULL,
  `Status` int(11) DEFAULT 1,
  `createdBy` text DEFAULT NULL,
  `createdDate` text DEFAULT NULL,
  `lastactivity` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `Name`, `smName`, `Icon`, `bg_img`, `Status`, `createdBy`, `createdDate`, `lastactivity`) VALUES
(1, 'Counter-Strike 1.6', 'cs16', 'https://i.imgur.com/e7O9Uul.png', 'https://i.imgur.com/FCqn3Lq.png', 1, '1', '1592038851', '1592038851'),
(2, 'San Andreas Multiplayer', 'samp', 'https://i.imgur.com/0Zf8JBo.png', 'https://i.imgur.com/LZh0xSQ.png', 1, '1', '1592038851', '1592038851'),
(3, 'FiveM', 'fivem', 'https://i.imgur.com/DGZS11m.png', 'https://i.imgur.com/En2jzFu.png', 1, '1', '1592038851', '1592038851'),
(4, 'Counter-Strike:Global Offensive', 'csgo', 'https://i.imgur.com/TGCUrI8.png', 'https://i.imgur.com/T1fAkh1.png', 1, '1', '1592038851', '1592038851'),
(5, 'Fast Download', 'fdl', 'https://i.imgur.com/x6ndhmr.png', 'https://i.imgur.com/RKg4dlJ.png', 1, '1', '1592038851', '1592038851'),
(6, 'MySQL', 'mysql', 'https://i.imgur.com/x6ndhmr.png', 'https://i.imgur.com/RKg4dlJ.png', 0, '1', '1592038851', '1592038851'),
(7, 'Minecraft', 'mc', 'https://i.imgur.com/Xe6GMV9.png', 'https://i.imgur.com/jYBAeCd.jpg', 1, '1', '1592038851', '1592038851');

-- --------------------------------------------------------

--
-- Table structure for table `mods`
--

CREATE TABLE `mods` (
  `id` int(11) NOT NULL,
  `Name` text DEFAULT NULL,
  `modDir` text DEFAULT NULL,
  `Map` text DEFAULT NULL,
  `commandLine` text DEFAULT NULL,
  `Note` text DEFAULT NULL,
  `gameID` text DEFAULT NULL,
  `Status` text DEFAULT NULL,
  `createdBy` text DEFAULT NULL,
  `createdDate` text DEFAULT NULL,
  `lastactivity` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mods`
--

INSERT INTO `mods` (`id`, `Name`, `modDir`, `Map`, `commandLine`, `Note`, `gameID`, `Status`, `createdBy`, `createdDate`, `lastactivity`) VALUES
(1, 'Public', '/home/games/cs/PublicMRV', 'de_dust2', 'test', '	Normalna igra CS1.6, tim Terorista(T) protiv tima Counter-Terorista(CT). Misija CT tima je da sacuva ciljeve T tima, te da ih sve eliminira ili do kraja runde zadrzi plant-ove sigurnima. T tim mora da se probije do plantova koje cuva CT tim, da ih eliminira ili da postavi C4 te ga cuva do njegovog unistenja.', '1', '1', '1', '1', '1'),
(3, 'SA-MP 0.3.7-R2', '/home/games/samp/samp03', '', './samp03svr', 'Testiram.', '2', '1', '1', '1', '1'),
(4, 'FiveM', '/home/games/fivem', '', 'cd server-data;../run.sh +exec server.cfg', 'FiveM', '3', '1', '1', '1', '1'),
(6, 'CS GO', '/home/games/csgo', 'de_dust2', 'test', 'Testiram.', '4', '1', '1', '1', '1'),
(7, 'Deathmatch', '/home/games/cs/dmMRV', 'de_dust2', 'test', 'Ovo je mod koji obozava vecina igraca, na njemu ucite da se snalazite u prostoru, dobijate na brzini i preciznosti, jer svaki metak Vam znaci. Igra se tako sto se stvarate randomirano po mapi, brzo odabirete zeljeno oruzije i ubijate koga stignete, od svojih, pa do tudjih takmicara, tu se ne igra timski, vec pojedinacno, onaj koji ima najvise ubistava na kraju runde, on je pobednik.', '1', '1', '1', '1', '1'),
(8, 'COD:MW4', '/home/games/cs/cod4MRV', 'de_dust2', 'test', 'COD Mod je standardan CS ali sa zvukovima i oruzjem iz igre Call of Duty', '1', '1', '1', '1', '1'),
(9, 'GunGame', '/home/games/cs/ggMRV', 'de_dust2', 'test', 'Svakim ubistvom sakupljate poene, kada nakupite dovoljan broj za sledeci level, automatski dobijate jace oruzije.\nCim umrete ozivljavate se kao na deathmatch modu.', '1', '1', '1', '1', '1'),
(10, 'AWP Mod', '/home/games/cs/awpMRV', 'de_dust2', 'test', 'AWP Maps Mod ima specijalne male mape i u ovom modu se koristi samo AWP sniper', '1', '1', '1', '1', '1'),
(11, 'ClanWar (CW)', '/home/games/cs/cwMRV', 'de_dust2', 'test', 'ClanWar mod', '1', '1', '1', '1', '1'),
(12, 'PaintBall', '/home/games/cs/pbMRV', 'de_dust2', 'test', 'Paintball Mod ima specijalne mape i specijalna oruzja koja umesto metaka ispaljuje obojene loptice.', '1', '1', '1', '1', '1'),
(13, 'AutoMix', '/home/games/cs/amixMRV', 'de_dust2', 'test', 'AutoMix mod', '1', '1', '1', '1', '1'),
(14, 'Public HLDS', '/home/games/cs/PublicHLDS', 'de_dust2', 'test', 'Normalna igra CS1.6, tim Terorista(T) protiv tima Counter-Terorista(CT). Misija CT tima je da sacuva ciljeve T tima, te da ih sve eliminira ili do kraja runde zadrzi plant-ove sigurnima. T tim mora da se probije do plantova koje cuva CT tim, da ih eliminira ili da postavi C4 te ga cuva do njegovog unistenja.', '1', '1', '1', '1', '1'),
(15, 'Public ReHLDS', '/home/games/cs/PublicRHLDS', 'de_dust2', 'test', 'Normalna igra CS1.6, tim Terorista(T) protiv tima Counter-Terorista(CT). Misija CT tima je da sacuva ciljeve T tima, te da ih sve eliminira ili do kraja runde zadrzi plant-ove sigurnima. T tim mora da se probije do plantova koje cuva CT tim, da ih eliminira ili da postavi C4 te ga cuva do njegovog unistenja.', '1', '1', '1', '1', '1'),
(16, 'Minecraft 1.16.3', '/home/games/mc/1.16.3', 'world', 'java -Xms{minRAM} -Xms{maxRAM} -jar server.jar nogui', 'Pripremite se za avanturu neograničenih mogućnosti dok gradite, kopate, borite se protiv rulje i istražujete stalno mijenjajući Minecraft krajolik.', '7', '1', '1', '1', '1');

-- --------------------------------------------------------

--
-- Table structure for table `mysql_servers`
--

CREATE TABLE `mysql_servers` (
  `id` int(11) NOT NULL,
  `boxID` text DEFAULT NULL,
  `serverID` text DEFAULT NULL,
  `userID` text DEFAULT NULL,
  `mysqlUser` text DEFAULT NULL,
  `mysqlPass` text DEFAULT NULL,
  `createdDate` text DEFAULT NULL,
  `lastactivity` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mysql_servers`
--

INSERT INTO `mysql_servers` (`id`, `boxID`, `serverID`, `userID`, `mysqlUser`, `mysqlPass`, `createdDate`, `lastactivity`) VALUES
(1, '4', '30', '1', 'mrv_1_30_lda4v', '653gsz1gzp', '24/09/2020, 01:44am', '1600904659'),
(2, '4', '52', '81', 'mrv_81_52_koef5', 'zea7sg5pfn', '24/09/2020, 13:31pm', '1600947064'),
(3, '4', '59', '57', 'mrv_57_59_ya61c', '0qxwtm4qth', '25/09/2020, 18:06pm', '1601049988'),
(4, '4', '63', '115', 'mrv_115_63_0facr', 'lojyv7x4b5', '25/09/2020, 23:15pm', '1601068524'),
(5, '4', '61', '113', 'mrv_113_61_vg31h', 'ehuimle10d', '26/09/2020, 11:02am', '1601110961'),
(6, '4', '56', '100', 'mrv_100_56_ujufj', 'h5xropt9nw', '27/09/2020, 22:15pm', '1601237711'),
(7, '4', '56', '100', 'mrv_100_56_hro2l', 'ol4rs8kdyw', '28/09/2020, 21:54pm', '1601322872');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `Title` text DEFAULT NULL,
  `Text` text DEFAULT NULL,
  `Location` text DEFAULT NULL,
  `Tags` text DEFAULT NULL,
  `Image` text DEFAULT NULL,
  `userID` text DEFAULT NULL,
  `Date` text DEFAULT NULL,
  `Status` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `Title`, `Text`, `Location`, `Tags`, `Image`, `userID`, `Date`, `Status`) VALUES
(2, 'EBL #\r\nSupport Ticket', 'Support ticket je pusten u rad! \nUbuduce kad vam treba pomoc oko servera/gamepanela da iskljucivo otvorite tiket u sekciji support!\n\nPozdrav,\nMRV-Hosting.com\n#SupportTeam', 'World', 'No-share', 'n/i/d2iztojgc2rp2bxst6nwqy7hgrbepe.jpg', '8', '18/05/2020, 05:43am', '1'),
(3, 'EBL # Facebook Page', 'Obavjestavamo vas da smo napravili i nasu oficijalu Facebook stranicu \"MRV Hosting\" te da ako mozete da odvojite malo vaseg vremena i lajkujete istu jer spremamo dosta toga za VAS!. -Uskoro!\n\nhttps://www.facebook.com/mrvhosting\n\nPozdrav,\nMRV Hosting Team <3', 'World', 'facebook, mrv, mrvhosting, mrv-hosting', 'n/i/d2iztojgc2rp2bxst6nwqy7hgrbepe.jpg', '8', '02/07/2020, 08:00am', '1');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `gameID` text NOT NULL,
  `userID` text NOT NULL,
  `Location` text NOT NULL,
  `Slots` text NOT NULL,
  `Months` text NOT NULL,
  `modID` text NOT NULL,
  `serverName` text NOT NULL,
  `Price` text DEFAULT NULL,
  `gb` text DEFAULT NULL,
  `orderDate` text NOT NULL,
  `lastactivity` text NOT NULL,
  `orderStatus` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `plugins`
--

CREATE TABLE `plugins` (
  `id` int(11) NOT NULL,
  `Name` text DEFAULT NULL,
  `pluginDir` text DEFAULT NULL,
  `Note` text DEFAULT NULL,
  `gameID` text DEFAULT NULL,
  `Status` text DEFAULT NULL,
  `createdBy` text DEFAULT NULL,
  `createdDate` text DEFAULT NULL,
  `lastactivity` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `plugins`
--

INSERT INTO `plugins` (`id`, `Name`, `pluginDir`, `Note`, `gameID`, `Status`, `createdBy`, `createdDate`, `lastactivity`) VALUES
(1, 'Bad camper', '/home/plugins/cs16/bad_camper/bad_camper.amxx', 'Kaznjava igrace koji se stekaju po mapama.\r\nPlugin podesavate:\r\ncstrike/badcamper.cfg', '1', '1', '1', '1592038851', '1592038851'),
(2, 'Smoke Color', '/home/plugins/cs16/color_smoke/ColoredSmoke.amxx', 'Boja bombe \"Smoke\" je drugacija i razlicitih boja', '1', '1', '1', '1592038851', '1592038851'),
(3, 'Admin Spectator ESP', '/home/plugins/cs16/admin_spec_esp/admin_spec_esp.amxx', 'Admin kada je \"dead\" pojavljuju mu se pravougaonici na mestima gde su igraci sa druge strane zidova i tako moze da prati da li igrac pogadja druge tacno kroz zid', '1', '1', '1', '1592038851', '1592038851'),
(4, 'Frag Counter', '/home/plugins/cs16/frag_counter/fragcounter.amxx', 'Brojac fragova na levoj strani ekrana', '1', '1', '1', '1592038851', '1592038851'),
(5, 'Grenade Trail', '/home/plugins/cs16/grenade_trail/grenade_trail.amxx', 'Granade Trail', '1', '1', '1', '1592038851', '1592038851'),
(6, 'Mrv Bots', '/home/plugins/cs16/mrv_bots/mrv_bots.amxx', 'Server kreira dva bota i oni ce svo vreme biti u spec.\r\nPlugin podesavate: cstrike/addons/amxmodx/configs/mrvbots.cfg', '1', '1', '1', '1592038851', '1592038851'),
(7, 'Public Rules', '/home/plugins/cs16/public_rules/public_rules.amxx', 'Ispisuje pravila posle odabira tima.\r\nPlugin podesavate:\r\ncstrike/rules.txt', '1', '1', '1', '1592038851', '1592038851'),
(8, 'Steam Bonus', '/home/plugins/cs16/steam_bonus/steam_bonus.amxx', 'Steam igraci dobijaju XP, Money i Sve bombe na pocetku svake runde', '1', '1', '1', '1592038851', '1592038851');

-- --------------------------------------------------------

--
-- Table structure for table `servers`
--

CREATE TABLE `servers` (
  `id` int(11) NOT NULL,
  `userID` text DEFAULT NULL,
  `boxID` text DEFAULT NULL,
  `gameID` text DEFAULT NULL,
  `modID` text DEFAULT NULL,
  `Name` text DEFAULT NULL,
  `Port` text DEFAULT NULL,
  `Map` text DEFAULT NULL,
  `Slot` text DEFAULT NULL,
  `fps` text DEFAULT NULL,
  `expiresFor` text DEFAULT NULL,
  `Username` text DEFAULT NULL,
  `Password` text DEFAULT NULL,
  `Status` text DEFAULT NULL,
  `Online` text DEFAULT NULL,
  `isStart` text DEFAULT NULL,
  `commandLine` text DEFAULT NULL,
  `Note` text DEFAULT NULL,
  `isFree` text DEFAULT NULL,
  `autoRestart` text DEFAULT NULL,
  `serverOption` text DEFAULT NULL,
  `ftpBlock` text DEFAULT NULL,
  `createdBy` text DEFAULT NULL,
  `createdDate` text DEFAULT NULL,
  `lastactivity` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `servers`
--

INSERT INTO `servers` (`id`, `userID`, `boxID`, `gameID`, `modID`, `Name`, `Port`, `Map`, `Slot`, `fps`, `expiresFor`, `Username`, `Password`, `Status`, `Online`, `isStart`, `commandLine`, `Note`, `isFree`, `autoRestart`, `serverOption`, `ftpBlock`, `createdBy`, `createdDate`, `lastactivity`) VALUES
(1, '2', '1', '2', '3', 'test', '7779', '', '50', '1000', '2021-04-04', 'srv_1_1_152y5', '03uZuaFt', '1', '0', '1', '', '', '0', '', '1', '0', '8', '04/04/2021, 23:33pm', '1617572024'),
(2, '2', '1', '2', '3', 'test', '7778', '', '50', '1000', '2021-04-04', 'srv_2_1_vs8bx', '1o9AuxEi', '1', '0', '0', '', '', '0', '', '1', '0', '8', '04/04/2021, 23:46pm', '1617572797');

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `site_name` text NOT NULL,
  `site_link` text NOT NULL,
  `site_online` text NOT NULL,
  `site_update` text NOT NULL,
  `site_version` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `site_name`, `site_link`, `site_online`, `site_update`, `site_version`) VALUES
(1, 'MRV Hosting', 'https://www.mrv-Hosting.com', '1', '1', '1.0.0');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `serverID` text DEFAULT NULL,
  `Title` text DEFAULT NULL,
  `Message` text DEFAULT NULL,
  `Priority` text DEFAULT NULL,
  `Status` text DEFAULT NULL,
  `Date` text DEFAULT NULL,
  `userID` text DEFAULT NULL,
  `lastactivity` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_answ`
--

CREATE TABLE `ticket_answ` (
  `id` int(11) NOT NULL,
  `tID` text DEFAULT NULL,
  `userID` text DEFAULT NULL,
  `supportID` text DEFAULT NULL,
  `Message` text DEFAULT NULL,
  `Date` text DEFAULT NULL,
  `lastactivity` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `Username` text NOT NULL,
  `Password` text NOT NULL,
  `Email` text NOT NULL,
  `Name` text NOT NULL,
  `Lastname` text NOT NULL,
  `Image` text DEFAULT NULL,
  `pC` text DEFAULT NULL,
  `Token` text DEFAULT NULL,
  `Status` text DEFAULT NULL,
  `mrvCash` varchar(100) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `Username`, `Password`, `Email`, `Name`, `Lastname`, `Image`, `pC`, `Token`, `Status`, `mrvCash`) VALUES
(1, 'demo', 'fe01ce2a7fbac8fafaed7c982a04e229', 'break@mrv-hosting.com', 'demo', 'demo', 'asd', '1000', 'fe01ce2a7fbac8fafaed7c982a04e229_ihadha8d', '1', '0'),
(2, 'mskoko', 'fe01ce2a7fbac8fafaed7c982a04e229', 'mskoko.me@gmail.com', 'Muhamed', 'Skoko', '', '29785', 'WBWezRMcCdCzWzS4tuqcCtXnoXOI7qT6GjSyZaGTpN9rlbMqrwFEiveKfGp9', '1', '0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `box_list`
--
ALTER TABLE `box_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fdl_servers`
--
ALTER TABLE `fdl_servers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mods`
--
ALTER TABLE `mods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mysql_servers`
--
ALTER TABLE `mysql_servers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plugins`
--
ALTER TABLE `plugins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `servers`
--
ALTER TABLE `servers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_answ`
--
ALTER TABLE `ticket_answ`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `box_list`
--
ALTER TABLE `box_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `servers`
--
ALTER TABLE `servers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

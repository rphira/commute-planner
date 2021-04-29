-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2021 at 09:20 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wp_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `persons`
--

CREATE TABLE `persons` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) DEFAULT NULL,
  `ticket` varchar(30) NOT NULL DEFAULT 'none'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `stations`
--

CREATE TABLE `stations` (
  `StationID` int(20) NOT NULL,
  `Name` varchar(20) NOT NULL,
  `Central` varchar(20) NOT NULL,
  `Western` varchar(20) NOT NULL,
  `Harbour` varchar(20) NOT NULL,
  `Speed` varchar(20) NOT NULL,
  `Distance` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stations`
--

INSERT INTO `stations` (`StationID`, `Name`, `Central`, `Western`, `Harbour`, `Speed`, `Distance`) VALUES
(16, 'Andheri', '0', '1', '1', 'Fast', 21),
(12, 'Bandra', '0', '1', '1', 'Fast', 14),
(69, 'Belapur CBD', '0', '0', '1', 'Slow', 44),
(45, 'Bhandup', '1', '0', '0', 'Slow', 23),
(25, 'Bhayandar', '0', '1', '0', 'Fast', 39),
(22, 'Borivali', '0', '1', '0', 'Fast', 31),
(33, 'Byculla', '1', '0', '0', 'Fast', 4),
(3, 'Charni Road', '0', '1', '0', 'Fast', 2),
(61, 'Chembur', '0', '0', '1', 'Slow', 17),
(34, 'Chinchpokli', '1', '0', '0', 'Slow', 5),
(58, 'Chunna Bhatti', '0', '0', '1', 'Slow', 13),
(1, 'Churchgate', '0', '1', '0', 'Fast', 0),
(54, 'Cotton Green', '0', '0', '1', 'Slow', 6),
(30, 'CSMT C', '1', '0', '0', 'Fast', 0),
(49, 'CSMT H', '0', '0', '1', 'Slow', 0),
(35, 'Currey Road', '1', '0', '0', 'Slow', 6),
(37, 'Dadar C', '1', '0', '0', 'Fast', 9),
(9, 'Dadar W', '0', '1', '0', 'Fast', 9),
(23, 'Dahisar', '0', '1', '0', 'Slow', 33),
(52, 'Dockyard Road', '0', '0', '1', 'Slow', 4),
(42, 'Ghatkopar', '1', '0', '0', 'Fast', 15),
(19, 'Goregaon', '0', '1', '1', 'Slow', 25),
(62, 'Govandi', '0', '0', '1', 'Slow', 18),
(4, 'Grant Road', '0', '1', '0', 'Fast', 3),
(57, 'GTB Nagar', '0', '0', '1', 'Slow', 11),
(17, 'Jogeshwari', '0', '1', '1', 'Slow', 23),
(66, 'Juinagar', '0', '0', '1', 'Slow', 34),
(21, 'Kandivali', '0', '1', '0', 'Slow', 28),
(44, 'Kanjur Marg', '1', '0', '0', 'Slow', 21),
(72, 'Khandeshwar', '0', '0', '1', 'Slow', 54),
(13, 'Khar Road', '0', '1', '1', 'Slow', 16),
(70, 'Kharghar', '0', '0', '1', 'Slow', 48),
(0, 'Kings Circle', '0', '0', '0', 'Slow', 10),
(40, 'Kurla C', '1', '0', '0', 'Fast', 12),
(59, 'Kurla H', '0', '0', '1', 'Slow', 15),
(7, 'Lower Parel', '0', '1', '0', 'Slow', 7),
(6, 'Mahalaxmi', '0', '1', '0', 'Slow', 5),
(11, 'Mahim', '0', '1', '1', 'Slow', 12),
(20, 'Malad', '0', '1', '0', 'Slow', 27),
(71, 'Manasarovar', '0', '0', '1', 'Slow', 51),
(63, 'Mankhurd', '0', '0', '1', 'Slow', 20),
(2, 'Marine Lines', '0', '1', '0', 'Fast', 1),
(31, 'Masjid C', '1', '0', '0', 'Slow', 1),
(50, 'Masjid H', '0', '0', '1', 'Slow', 1),
(38, 'Matunga', '1', '0', '0', 'Slow', 10),
(10, 'Matunga Road', '0', '1', '0', 'Slow', 11),
(24, 'Mira Road', '0', '1', '0', 'Slow', 36),
(47, 'Mulund', '1', '0', '0', 'Slow', 26),
(5, 'Mumbai Central', '0', '1', '0', 'Fast', 4),
(46, 'Nahur', '1', '0', '0', 'Slow', 24),
(26, 'Naigaon', '0', '1', '0', 'Slow', 44),
(28, 'Nallasopara', '0', '1', '0', 'Slow', 52),
(67, 'Nerul', '0', '0', '1', 'Slow', 37),
(73, 'Panvel', '0', '0', '1', 'Slow', 57),
(36, 'Parel', '1', '0', '0', 'Slow', 8),
(8, 'Prabhadevi', '0', '1', '0', 'Slow', 8),
(18, 'Ram Mandir', '0', '1', '1', 'Slow', 24),
(53, 'Reay Road', '0', '0', '1', 'Slow', 5),
(32, 'Sandhurst Road C', '1', '0', '0', 'Slow', 2),
(51, 'Sandhurst Road H', '0', '0', '1', 'Slow', 2),
(65, 'Sanpada', '0', '0', '1', 'Slow', 31),
(14, 'Santacruz', '0', '1', '1', 'Slow', 17),
(68, 'Seawood Darave', '0', '0', '1', 'Slow', 40),
(55, 'Sewri', '0', '0', '1', 'Slow', 7),
(39, 'Sion', '1', '0', '0', 'Slow', 11),
(48, 'Thane', '1', '0', '0', 'Fast', 28),
(60, 'Tilaknagar', '0', '0', '1', 'Slow', 16),
(27, 'Vasai Road', '0', '1', '0', 'Fast', 48),
(64, 'Vashi', '0', '0', '1', 'Slow', 28),
(41, 'Vidyavihar', '1', '0', '0', 'Slow', 13),
(43, 'Vikhroli', '1', '0', '0', 'Slow', 19),
(15, 'Vile Parle', '0', '1', '1', 'Slow', 19),
(29, 'Virar', '0', '1', '0', 'Fast', 56),
(56, 'Wadala', '0', '0', '1', 'Slow', 9);

-- --------------------------------------------------------

--
-- Table structure for table `trains`
--

CREATE TABLE `trains` (
  `TrainID` int(20) NOT NULL,
  `Start` varchar(20) NOT NULL,
  `Dest` varchar(20) NOT NULL,
  `StartID` int(20) NOT NULL,
  `DestID` int(20) NOT NULL,
  `Speed` varchar(20) NOT NULL,
  `Route` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `trains`
--

INSERT INTO `trains` (`TrainID`, `Start`, `Dest`, `StartID`, `DestID`, `Speed`, `Route`) VALUES
(1, 'Churchgate', 'Virar', 1, 29, 'Slow', 'Western'),
(2, 'Churchgate', 'Borivali', 1, 22, 'Slow', 'Western'),
(3, 'Churchgate', 'Virar', 1, 29, 'Fast', 'Western'),
(4, 'Churchgate', 'Borivali', 1, 22, 'Fast', 'Western'),
(5, 'Virar', 'Churchgate', 29, 1, 'Slow', 'Western'),
(6, 'Borivali', 'Churchgate', 22, 1, 'Slow', 'Western'),
(7, 'Virar', 'Churchgate', 29, 1, 'Fast', 'Western'),
(8, 'Borivali', 'Churchgate', 22, 1, 'Fast', 'Western'),
(9, 'CSMT C', 'Thane', 30, 48, 'Slow', 'Central'),
(10, 'CSMT C', 'Thane', 30, 48, 'Fast', 'Central'),
(11, 'Thane', 'CSMT C', 48, 30, 'Slow', 'Central'),
(12, 'Thane', 'CSMT C', 48, 30, 'Fast', 'Central'),
(13, 'CSMT H', 'Panvel', 49, 73, 'Slow', 'Harbour'),
(14, 'CSMT H', 'Goregaon', 49, 19, 'Slow', 'Harbour'),
(15, 'Panvel', 'CSMT H', 73, 49, 'Slow', 'Harbour'),
(16, 'Goregaon', 'CSMT H', 19, 49, 'Slow', 'Harbour');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `persons`
--
ALTER TABLE `persons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stations`
--
ALTER TABLE `stations`
  ADD PRIMARY KEY (`Name`);

--
-- Indexes for table `trains`
--
ALTER TABLE `trains`
  ADD PRIMARY KEY (`TrainID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `persons`
--
ALTER TABLE `persons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

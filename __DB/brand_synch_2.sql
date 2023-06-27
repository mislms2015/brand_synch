-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2023 at 02:03 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `brand_synch_2`
--

-- --------------------------------------------------------

--
-- Table structure for table `bca_file`
--

CREATE TABLE `bca_file` (
  `id` varchar(25) NOT NULL,
  `file_id` varchar(50) NOT NULL,
  `number_series` varchar(50) NOT NULL,
  `brand_id` varchar(150) NOT NULL,
  `brand_name` varchar(150) NOT NULL,
  `brand_description` varchar(150) NOT NULL,
  `is_active` varchar(10) NOT NULL,
  `is_supported` varchar(10) NOT NULL,
  `is_match` varchar(5) DEFAULT NULL,
  `brand` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `brand_series`
--

CREATE TABLE `brand_series` (
  `id` varchar(25) NOT NULL,
  `file_id` varchar(50) NOT NULL,
  `brand_description` varchar(50) NOT NULL,
  `brand_id` varchar(50) NOT NULL,
  `brand_name` varchar(50) NOT NULL,
  `creation_timestamp` varchar(50) NOT NULL,
  `is_active` varchar(50) NOT NULL,
  `last_update_timestamp` varchar(50) NOT NULL,
  `series` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `file_uploaded`
--

CREATE TABLE `file_uploaded` (
  `id` int(11) NOT NULL,
  `file_type` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `banner` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `min_metadata`
--

CREATE TABLE `min_metadata` (
  `id` varchar(25) NOT NULL,
  `file_id` varchar(50) NOT NULL,
  `brand_description` varchar(50) NOT NULL,
  `brand_id` varchar(50) NOT NULL,
  `brand_name` varchar(50) NOT NULL,
  `creation_timestamp` varchar(50) NOT NULL,
  `is_active` varchar(50) NOT NULL,
  `last_update_timestamp` varchar(50) NOT NULL,
  `min` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bca_file`
--
ALTER TABLE `bca_file`
  ADD KEY `number_series_idx` (`number_series`),
  ADD KEY `brand_id_idx` (`brand_id`);

--
-- Indexes for table `brand_series`
--
ALTER TABLE `brand_series`
  ADD KEY `series_idx` (`series`),
  ADD KEY `brand_id_brand_series_idx` (`brand_id`);

--
-- Indexes for table `file_uploaded`
--
ALTER TABLE `file_uploaded`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `min_metadata`
--
ALTER TABLE `min_metadata`
  ADD KEY `min_metadata_idx` (`min`),
  ADD KEY `brand_id_min_metadata_idx` (`brand_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `file_uploaded`
--
ALTER TABLE `file_uploaded`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

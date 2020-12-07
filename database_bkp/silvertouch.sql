-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2020 at 11:11 AM
-- Server version: 5.7.14
-- PHP Version: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `silvertouch`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(10) DEFAULT '0',
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  `category_name` varchar(2555) DEFAULT NULL,
  `category_image` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `lft`, `rght`, `category_name`, `category_image`, `created`, `modified`) VALUES
(1, NULL, 1, 20, 'My Categories', 'q-21607329647150375fcde76f30ef4.png', '2020-12-07 12:42:53', '2020-12-07 08:27:27'),
(2, 0, 2, 15, 'Fun', 'q-21607329647150375fcde76f30ef4.png', '2020-12-07 12:42:53', '2020-12-07 12:42:53'),
(3, 2, 3, 8, 'Sport', 'q-21607329647150375fcde76f30ef4.png', '2020-12-07 12:42:53', '2020-12-07 12:42:53'),
(4, 3, 4, 5, 'Surfing', 'q-21607329647150375fcde76f30ef4.png', '2020-12-07 12:42:53', '2020-12-07 12:42:53'),
(5, 3, 6, 7, 'Extreme knitting', 'q-21607329647150375fcde76f30ef4.png', '2020-12-07 12:42:53', '2020-12-07 12:42:53'),
(6, 2, 9, 14, 'Friends', 'q-21607329647150375fcde76f30ef4.png', '2020-12-07 12:42:53', '2020-12-07 12:42:53'),
(7, 6, 10, 11, 'Gerald', 'q-21607329647150375fcde76f30ef4.png', '2020-12-07 12:42:53', '2020-12-07 12:42:53'),
(8, 6, 12, 13, 'Gwendolyn', 'q-21607329647150375fcde76f30ef4.png', '2020-12-07 12:42:53', '2020-12-07 12:42:53'),
(9, 1, 16, 19, 'Work', 'q-21607329647150375fcde76f30ef4.png', '2020-12-07 12:42:53', '2020-12-07 12:42:53'),
(10, 9, 17, 18, 'Reports', 'q-21607329647150375fcde76f30ef4.png', '2020-12-07 12:42:53', '2020-12-07 12:42:53');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_description` varchar(255) NOT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `categories_id` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

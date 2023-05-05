-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 05, 2023 at 06:48 AM
-- Server version: 5.7.36
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mseries2`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `id` varchar(20) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `product_id` varchar(20) NOT NULL,
  `price` varchar(10) NOT NULL,
  `qty` varchar(2) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` varchar(20) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `number` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` varchar(200) NOT NULL,
  `address_type` varchar(10) NOT NULL,
  `method` varchar(50) NOT NULL,
  `product_id` varchar(20) NOT NULL,
  `price` varchar(10) NOT NULL,
  `qty` varchar(2) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'in progress',
  `date` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `address`, `address_type`, `method`, `product_id`, `price`, `qty`, `status`, `date`) VALUES
('5ShF4hKTv5a0vY1onFe1', 'm4NC0EvWJatuF9SddpHQ', 'jaya', '91244746', 'sample@mail.com', '22, fin street, goa, india - 12345', 'home', 'cash on delivery', 'nu10kcxS35NghhIIeBiz', '12', '1', 'in progress', '0000-00-00 00:00:00'),
('A0klKxqddN9WsUbKyNSM', 'm4NC0EvWJatuF9SddpHQ', 'John', '5555666', 'sample@mail.com', '77, fin street, goa, india - 54558', 'office', 'cash on Delivery', 'QXZ6iWTDFFjHvlGheU7X', '16', '2', 'in progress', '0000-00-00 00:00:00'),
('r7NDjtBPy0OtlpcNuN1I', 'm4NC0EvWJatuF9SddpHQ', 'John', '5555666', 'sample@mail.com', '77, fin street, goa, india - 54558', 'office', 'cash on Delivery', 'eizVvmwzsmt0Hey6jFCl', '14', '3', 'canceled', '0000-00-00 00:00:00'),
('apn5oaGJ7ZpH5W3EFatU', 'm4NC0EvWJatuF9SddpHQ', 'John', '5555666', 'sample@mail.com', '77, fin street, goa, india - 54558', 'office', 'cash on Delivery', 'nu10kcxS35NghhIIeBiz', '12', '1', 'in progress', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` varchar(10) NOT NULL,
  `image` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`) VALUES
('nu10kcxS35NghhIIeBiz', 'Face Wash', '12', 'TZ6sFitsxnNoGrwG5pGk.webp'),
('eizVvmwzsmt0Hey6jFCl', 'Face Moisturizer', '14', 'CVmePnkviouQTBsWp6Ec.webp'),
('QXZ6iWTDFFjHvlGheU7X', 'Face Sunscreen', '16', 'Z0jtE0Nt73QqBpc3qpe6.webp');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

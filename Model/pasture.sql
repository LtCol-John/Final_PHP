
-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 25, 2016 at 05:18 PM
-- Server version: 10.1.9-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `stickman1db`
--

CREATE DATABASE IF NOT EXISTS `pasturedb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `pasturedb`;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `bidders`;
CREATE TABLE `bidders` (
  `firstName` varChar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `alias` varChar(50) NOT NULL,
  `password` varChar(256) NOT NULL,
  `email` varchar(100) NOT NULL,
  CONSTRAINT pk_bidders primary key(`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- Table structure for table "bids"

DROP TABLE IF EXISTS `bids`;
CREATE TABLE `bids` (
  `bidID` int not null auto_increment,
  `alias` varChar(50) NOT NULL,
  `bid` text NOT NULL,
  `bidDate` datetime not null,
  CONSTRAINT pk_bids primary key(`bidID`),
  CONSTRAINT fk_bidalias foreign key(`alias`) references bidders(`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
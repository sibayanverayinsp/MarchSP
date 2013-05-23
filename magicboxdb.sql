-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 10, 2013 at 05:04 AM
-- Server version: 5.5.20
-- PHP Version: 5.3.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `magicboxdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
  `acct_id` int(16) NOT NULL AUTO_INCREMENT,
  `acct_name` varchar(32) NOT NULL,
  `acct_pass` varchar(32) NOT NULL,
  `acct_type` varchar(16) NOT NULL,
  PRIMARY KEY (`acct_id`),
  UNIQUE KEY `acct_name` (`acct_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `repositories`
--

CREATE TABLE IF NOT EXISTS `repositories` (
  `repo_id` int(16) NOT NULL AUTO_INCREMENT,
  `repo_name` varchar(32) NOT NULL,
  `repo_creator` int(16) NOT NULL,
  PRIMARY KEY (`repo_id`),
  UNIQUE KEY `repo_name` (`repo_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `versions`
--

CREATE TABLE IF NOT EXISTS `versions` (
  `vers_id` int(16) NOT NULL AUTO_INCREMENT,
  `vers_acct_id` int(16) NOT NULL,
  `vers_repo_id` int(16) NOT NULL,
  `vers_repo_vers` int(16) NOT NULL,
  `vers_comp_id` int(16) NOT NULL,
  `vers_file_id` int(16) NOT NULL,
  `vers_lock_acct_id` int(16) NOT NULL,
  `vers_parent` int(16) NOT NULL,
  `vers_name` varchar(256) DEFAULT NULL,
  `vers_type` varchar(10) DEFAULT NULL,
  `vers_date` datetime DEFAULT NULL,
  `vers_message` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`vers_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

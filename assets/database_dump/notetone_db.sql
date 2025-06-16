-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2025 at 05:28 PM
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
-- Database: `notetone_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `audio`
--

CREATE TABLE `audio` (
  `audioid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `data` longblob NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `genreid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`genreid`, `name`, `description`) VALUES
(1, 'Rock', 'Rock music and its subgenres'),
(2, 'Pop', 'Popular music'),
(3, 'Jazz', 'Jazz music and its variations'),
(4, 'Classical', 'Classical music'),
(5, 'Electronic', 'Electronic and dance music'),
(6, 'Hip Hop', 'Hip hop and rap music'),
(7, 'Metal', 'Heavy metal and its subgenres'),
(8, 'Folk', 'Folk music'),
(9, 'Blues', 'Blues music'),
(10, 'Country', 'Country music'),
(11, 'Rap', 'A rhythmic and rhyming style of vocal delivery that expresses thoughts, stories, or emotions over a beat.');

-- --------------------------------------------------------

--
-- Table structure for table `instruments`
--

CREATE TABLE `instruments` (
  `instrumentid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instruments`
--

INSERT INTO `instruments` (`instrumentid`, `name`) VALUES
(1, 'Acoustic Guitar'),
(2, 'Electric Guitar'),
(3, 'Classic Guitar');

-- --------------------------------------------------------

--
-- Table structure for table `notations`
--

CREATE TABLE `notations` (
  `notationid` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `dateadded` date NOT NULL,
  `content` text NOT NULL,
  `songid` int(11) DEFAULT NULL,
  `instrumentid` int(11) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notations`
--

INSERT INTO `notations` (`notationid`, `title`, `dateadded`, `content`, `songid`, `instrumentid`, `userid`) VALUES
(87, 'spokoynaya noch solo', '2025-06-05', '[{\"positions\":[{\"str\":2,\"fret\":8}]},{\"positions\":[{\"str\":3,\"fret\":7}]},{\"positions\":[{\"str\":5,\"fret\":8}]},{\"tact\":true},{\"positions\":[{\"str\":4,\"fret\":10}]},{\"positions\":[{\"str\":4,\"fret\":9}]},{\"positions\":[{\"str\":3,\"fret\":9}]},{\"tact\":true},{\"positions\":[{\"str\":3,\"fret\":9}]},{\"positions\":[{\"str\":3,\"fret\":10}]},{\"positions\":[{\"str\":3,\"fret\":12}]},{\"tact\":true},{\"positions\":[{\"str\":3,\"fret\":9}]},{\"positions\":[{\"str\":3,\"fret\":10}]},{\"positions\":[{\"str\":4,\"fret\":12}]},{\"tact\":true},{\"positions\":[{\"str\":2,\"fret\":8}]},{\"positions\":[{\"str\":3,\"fret\":7}]},{\"positions\":[{\"str\":5,\"fret\":8}]},{\"tact\":true},{\"positions\":[{\"str\":3,\"fret\":10}]},{\"positions\":[{\"str\":3,\"fret\":12}]},{\"positions\":[{\"str\":3,\"fret\":9}]},{\"tact\":true},{\"positions\":[{\"str\":3,\"fret\":9}]},{\"positions\":[{\"str\":3,\"fret\":10}]},{\"positions\":[{\"str\":3,\"fret\":12}]},{\"positions\":[{\"str\":3,\"fret\":12}]},{\"positions\":[{\"str\":3,\"fret\":10}]},{\"positions\":[{\"str\":3,\"fret\":9}]},{\"tact\":true},{\"positions\":[{\"str\":3,\"fret\":9}]},{\"positions\":[{\"str\":3,\"fret\":10}]},{\"positions\":[{\"str\":3,\"fret\":12}]},{\"positions\":[{\"str\":4,\"fret\":12}]},{\"positions\":[{\"str\":4,\"fret\":10}]},{\"positions\":[{\"str\":4,\"fret\":9}]},{\"tact\":true}]', 27, 2, 1),
(88, 'Offspring parody', '2025-06-05', '[{\"positions\":[{\"str\":2,\"fret\":1}]},{\"positions\":[{\"str\":2,\"fret\":1}]},{\"positions\":[{\"str\":1,\"fret\":1}]},{\"positions\":[{\"str\":2,\"fret\":1}]},{\"positions\":[{\"str\":1,\"fret\":1}]},{\"positions\":[{\"str\":2,\"fret\":1}]},{\"positions\":[{\"str\":3,\"fret\":6}]},{\"positions\":[{\"str\":5,\"fret\":6},{\"str\":4,\"fret\":6}]},{\"tact\":true},{\"positions\":[{\"str\":3,\"fret\":3},{\"str\":2,\"fret\":2}]},{\"positions\":[{\"str\":4,\"fret\":5},{\"str\":2,\"fret\":4}]},{\"positions\":[{\"str\":1,\"fret\":3},{\"str\":2,\"fret\":3}]},{\"positions\":[{\"str\":2,\"fret\":2},{\"str\":3,\"fret\":2},{\"str\":1,\"fret\":0},{\"str\":4,\"fret\":0},{\"str\":5,\"fret\":0},{\"str\":6,\"fret\":0}]},{\"tact\":true}]', 28, 3, 1),
(95, 'Main Riff', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":0},{\"str\":2,\"fret\":0},{\"str\":3,\"fret\":0}]},{\"positions\":[{\"str\":1,\"fret\":2},{\"str\":2,\"fret\":2},{\"str\":3,\"fret\":2}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":3},{\"str\":2,\"fret\":3},{\"str\":3,\"fret\":3}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":10},{\"str\":2,\"fret\":10},{\"str\":6,\"fret\":10},{\"str\":5,\"fret\":10},{\"str\":4,\"fret\":10},{\"str\":3,\"fret\":10}]}]', 30, 1, 1),
(96, 'Chorus', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":5},{\"str\":2,\"fret\":5},{\"str\":3,\"fret\":5}]},{\"positions\":[{\"str\":1,\"fret\":7},{\"str\":2,\"fret\":7},{\"str\":3,\"fret\":7}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":8},{\"str\":2,\"fret\":8},{\"str\":3,\"fret\":8}]},{\"tact\":true}]', 30, 1, 2),
(97, 'Verse', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":3},{\"str\":2,\"fret\":3},{\"str\":3,\"fret\":3}]},{\"positions\":[{\"str\":1,\"fret\":5},{\"str\":2,\"fret\":5},{\"str\":3,\"fret\":5}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":7},{\"str\":2,\"fret\":7},{\"str\":3,\"fret\":7}]},{\"tact\":true}]', 31, 1, 3),
(98, 'Solo', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":12},{\"str\":2,\"fret\":12},{\"str\":3,\"fret\":12}]},{\"positions\":[{\"str\":1,\"fret\":14},{\"str\":2,\"fret\":14},{\"str\":3,\"fret\":14}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":15},{\"str\":2,\"fret\":15},{\"str\":3,\"fret\":15}]},{\"tact\":true}]', 31, 1, 4),
(99, 'Intro', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":0},{\"str\":2,\"fret\":0},{\"str\":3,\"fret\":0}]},{\"positions\":[{\"str\":1,\"fret\":2},{\"str\":2,\"fret\":2},{\"str\":3,\"fret\":2}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":3},{\"str\":2,\"fret\":3},{\"str\":3,\"fret\":3}]},{\"tact\":true}]', 32, 1, 5),
(100, 'Bridge', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":5},{\"str\":2,\"fret\":5},{\"str\":3,\"fret\":5}]},{\"positions\":[{\"str\":1,\"fret\":7},{\"str\":2,\"fret\":7},{\"str\":3,\"fret\":7}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":8},{\"str\":2,\"fret\":8},{\"str\":3,\"fret\":8}]},{\"tact\":true}]', 32, 1, 6),
(101, 'Main Riff', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":0},{\"str\":2,\"fret\":0},{\"str\":3,\"fret\":0}]},{\"positions\":[{\"str\":1,\"fret\":2},{\"str\":2,\"fret\":2},{\"str\":3,\"fret\":2}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":3},{\"str\":2,\"fret\":3},{\"str\":3,\"fret\":3}]},{\"tact\":true}]', 33, 1, 7),
(102, 'Chorus', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":5},{\"str\":2,\"fret\":5},{\"str\":3,\"fret\":5}]},{\"positions\":[{\"str\":1,\"fret\":7},{\"str\":2,\"fret\":7},{\"str\":3,\"fret\":7}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":8},{\"str\":2,\"fret\":8},{\"str\":3,\"fret\":8}]},{\"tact\":true}]', 33, 1, 8),
(103, 'Verse', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":3},{\"str\":2,\"fret\":3},{\"str\":3,\"fret\":3}]},{\"positions\":[{\"str\":1,\"fret\":5},{\"str\":2,\"fret\":5},{\"str\":3,\"fret\":5}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":7},{\"str\":2,\"fret\":7},{\"str\":3,\"fret\":7}]},{\"tact\":true}]', 34, 1, 9),
(104, 'Solo', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":12},{\"str\":2,\"fret\":12},{\"str\":3,\"fret\":12}]},{\"positions\":[{\"str\":1,\"fret\":14},{\"str\":2,\"fret\":14},{\"str\":3,\"fret\":14}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":15},{\"str\":2,\"fret\":15},{\"str\":3,\"fret\":15}]},{\"tact\":true}]', 34, 1, 10),
(105, 'Intro', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":0},{\"str\":2,\"fret\":0},{\"str\":3,\"fret\":0}]},{\"positions\":[{\"str\":1,\"fret\":2},{\"str\":2,\"fret\":2},{\"str\":3,\"fret\":2}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":3},{\"str\":2,\"fret\":3},{\"str\":3,\"fret\":3}]},{\"tact\":true}]', 35, 1, 11),
(106, 'Bridge', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":5},{\"str\":2,\"fret\":5},{\"str\":3,\"fret\":5}]},{\"positions\":[{\"str\":1,\"fret\":7},{\"str\":2,\"fret\":7},{\"str\":3,\"fret\":7}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":8},{\"str\":2,\"fret\":8},{\"str\":3,\"fret\":8}]},{\"tact\":true}]', 35, 1, 12),
(107, 'Main Riff', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":0},{\"str\":2,\"fret\":0},{\"str\":3,\"fret\":0}]},{\"positions\":[{\"str\":1,\"fret\":2},{\"str\":2,\"fret\":2},{\"str\":3,\"fret\":2}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":3},{\"str\":2,\"fret\":3},{\"str\":3,\"fret\":3}]},{\"tact\":true}]', 36, 1, 13),
(108, 'Chorus', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":5},{\"str\":2,\"fret\":5},{\"str\":3,\"fret\":5}]},{\"positions\":[{\"str\":1,\"fret\":7},{\"str\":2,\"fret\":7},{\"str\":3,\"fret\":7}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":8},{\"str\":2,\"fret\":8},{\"str\":3,\"fret\":8}]},{\"tact\":true}]', 36, 1, 14),
(109, 'Verse', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":3},{\"str\":2,\"fret\":3},{\"str\":3,\"fret\":3}]},{\"positions\":[{\"str\":1,\"fret\":5},{\"str\":2,\"fret\":5},{\"str\":3,\"fret\":5}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":7},{\"str\":2,\"fret\":7},{\"str\":3,\"fret\":7}]},{\"tact\":true}]', 37, 1, 15),
(110, 'Solo', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":12},{\"str\":2,\"fret\":12},{\"str\":3,\"fret\":12}]},{\"positions\":[{\"str\":1,\"fret\":14},{\"str\":2,\"fret\":14},{\"str\":3,\"fret\":14}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":15},{\"str\":2,\"fret\":15},{\"str\":3,\"fret\":15}]},{\"tact\":true}]', 37, 1, 16),
(111, 'Intro', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":0},{\"str\":2,\"fret\":0},{\"str\":3,\"fret\":0}]},{\"positions\":[{\"str\":1,\"fret\":2},{\"str\":2,\"fret\":2},{\"str\":3,\"fret\":2}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":3},{\"str\":2,\"fret\":3},{\"str\":3,\"fret\":3}]},{\"tact\":true}]', 38, 1, 17),
(112, 'Bridge', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":5},{\"str\":2,\"fret\":5},{\"str\":3,\"fret\":5}]},{\"positions\":[{\"str\":1,\"fret\":7},{\"str\":2,\"fret\":7},{\"str\":3,\"fret\":7}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":8},{\"str\":2,\"fret\":8},{\"str\":3,\"fret\":8}]},{\"tact\":true}]', 38, 1, 18),
(113, 'Main Riff', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":0},{\"str\":2,\"fret\":0},{\"str\":3,\"fret\":0}]},{\"positions\":[{\"str\":1,\"fret\":2},{\"str\":2,\"fret\":2},{\"str\":3,\"fret\":2}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":3},{\"str\":2,\"fret\":3},{\"str\":3,\"fret\":3}]},{\"tact\":true}]', 39, 1, 19),
(114, 'Chorus', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":5},{\"str\":2,\"fret\":5},{\"str\":3,\"fret\":5}]},{\"positions\":[{\"str\":1,\"fret\":7},{\"str\":2,\"fret\":7},{\"str\":3,\"fret\":7}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":8},{\"str\":2,\"fret\":8},{\"str\":3,\"fret\":8}]},{\"tact\":true}]', 39, 1, 20),
(115, 'Verse', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":3},{\"str\":2,\"fret\":3},{\"str\":3,\"fret\":3}]},{\"positions\":[{\"str\":1,\"fret\":5},{\"str\":2,\"fret\":5},{\"str\":3,\"fret\":5}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":7},{\"str\":2,\"fret\":7},{\"str\":3,\"fret\":7}]},{\"tact\":true}]', 40, 1, 21),
(116, 'Solo', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":12},{\"str\":2,\"fret\":12},{\"str\":3,\"fret\":12}]},{\"positions\":[{\"str\":1,\"fret\":14},{\"str\":2,\"fret\":14},{\"str\":3,\"fret\":14}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":15},{\"str\":2,\"fret\":15},{\"str\":3,\"fret\":15}]},{\"tact\":true}]', 40, 1, 22),
(117, 'Intro', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":0},{\"str\":2,\"fret\":0},{\"str\":3,\"fret\":0}]},{\"positions\":[{\"str\":1,\"fret\":2},{\"str\":2,\"fret\":2},{\"str\":3,\"fret\":2}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":3},{\"str\":2,\"fret\":3},{\"str\":3,\"fret\":3}]},{\"tact\":true}]', 41, 1, 23),
(118, 'Bridge', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":5},{\"str\":2,\"fret\":5},{\"str\":3,\"fret\":5}]},{\"positions\":[{\"str\":1,\"fret\":7},{\"str\":2,\"fret\":7},{\"str\":3,\"fret\":7}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":8},{\"str\":2,\"fret\":8},{\"str\":3,\"fret\":8}]},{\"tact\":true}]', 41, 1, 24),
(119, 'Main Riff', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":0},{\"str\":2,\"fret\":0},{\"str\":3,\"fret\":0}]},{\"positions\":[{\"str\":1,\"fret\":2},{\"str\":2,\"fret\":2},{\"str\":3,\"fret\":2}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":3},{\"str\":2,\"fret\":3},{\"str\":3,\"fret\":3}]},{\"tact\":true}]', 42, 1, 25),
(120, 'Chorus', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":5},{\"str\":2,\"fret\":5},{\"str\":3,\"fret\":5}]},{\"positions\":[{\"str\":1,\"fret\":7},{\"str\":2,\"fret\":7},{\"str\":3,\"fret\":7}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":8},{\"str\":2,\"fret\":8},{\"str\":3,\"fret\":8}]},{\"tact\":true}]', 42, 1, 26),
(121, 'Verse', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":3},{\"str\":2,\"fret\":3},{\"str\":3,\"fret\":3}]},{\"positions\":[{\"str\":1,\"fret\":5},{\"str\":2,\"fret\":5},{\"str\":3,\"fret\":5}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":7},{\"str\":2,\"fret\":7},{\"str\":3,\"fret\":7}]},{\"tact\":true}]', 43, 1, 27),
(122, 'Solo', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":12},{\"str\":2,\"fret\":12},{\"str\":3,\"fret\":12}]},{\"positions\":[{\"str\":1,\"fret\":14},{\"str\":2,\"fret\":14},{\"str\":3,\"fret\":14}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":15},{\"str\":2,\"fret\":15},{\"str\":3,\"fret\":15}]},{\"tact\":true}]', 43, 1, 28),
(123, 'Intro', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":0},{\"str\":2,\"fret\":0},{\"str\":3,\"fret\":0}]},{\"positions\":[{\"str\":1,\"fret\":2},{\"str\":2,\"fret\":2},{\"str\":3,\"fret\":2}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":3},{\"str\":2,\"fret\":3},{\"str\":3,\"fret\":3}]},{\"tact\":true}]', 44, 1, 29),
(124, 'Bridge', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":5},{\"str\":2,\"fret\":5},{\"str\":3,\"fret\":5}]},{\"positions\":[{\"str\":1,\"fret\":7},{\"str\":2,\"fret\":7},{\"str\":3,\"fret\":7}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":8},{\"str\":2,\"fret\":8},{\"str\":3,\"fret\":8}]},{\"tact\":true}]', 44, 1, 30),
(125, 'Main Riff', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":0},{\"str\":2,\"fret\":0},{\"str\":3,\"fret\":0}]},{\"positions\":[{\"str\":1,\"fret\":2},{\"str\":2,\"fret\":2},{\"str\":3,\"fret\":2}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":3},{\"str\":2,\"fret\":3},{\"str\":3,\"fret\":3}]},{\"tact\":true}]', 45, 1, 31),
(126, 'Chorus', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":5},{\"str\":2,\"fret\":5},{\"str\":3,\"fret\":5}]},{\"positions\":[{\"str\":1,\"fret\":7},{\"str\":2,\"fret\":7},{\"str\":3,\"fret\":7}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":8},{\"str\":2,\"fret\":8},{\"str\":3,\"fret\":8}]},{\"tact\":true}]', 45, 1, 32),
(127, 'Verse', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":3},{\"str\":2,\"fret\":3},{\"str\":3,\"fret\":3}]},{\"positions\":[{\"str\":1,\"fret\":5},{\"str\":2,\"fret\":5},{\"str\":3,\"fret\":5}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":7},{\"str\":2,\"fret\":7},{\"str\":3,\"fret\":7}]},{\"tact\":true}]', 46, 1, 33),
(128, 'Solo', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":12},{\"str\":2,\"fret\":12},{\"str\":3,\"fret\":12}]},{\"positions\":[{\"str\":1,\"fret\":14},{\"str\":2,\"fret\":14},{\"str\":3,\"fret\":14}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":15},{\"str\":2,\"fret\":15},{\"str\":3,\"fret\":15}]},{\"tact\":true}]', 46, 1, 34),
(129, 'Intro', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":0},{\"str\":2,\"fret\":0},{\"str\":3,\"fret\":0}]},{\"positions\":[{\"str\":1,\"fret\":2},{\"str\":2,\"fret\":2},{\"str\":3,\"fret\":2}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":3},{\"str\":2,\"fret\":3},{\"str\":3,\"fret\":3}]},{\"tact\":true}]', 47, 1, 35),
(130, 'Bridge', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":5},{\"str\":2,\"fret\":5},{\"str\":3,\"fret\":5}]},{\"positions\":[{\"str\":1,\"fret\":7},{\"str\":2,\"fret\":7},{\"str\":3,\"fret\":7}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":8},{\"str\":2,\"fret\":8},{\"str\":3,\"fret\":8}]},{\"tact\":true}]', 47, 1, 36),
(131, 'Main Riff', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":0},{\"str\":2,\"fret\":0},{\"str\":3,\"fret\":0}]},{\"positions\":[{\"str\":1,\"fret\":2},{\"str\":2,\"fret\":2},{\"str\":3,\"fret\":2}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":3},{\"str\":2,\"fret\":3},{\"str\":3,\"fret\":3}]},{\"tact\":true}]', 48, 1, 37),
(132, 'Chorus', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":5},{\"str\":2,\"fret\":5},{\"str\":3,\"fret\":5}]},{\"positions\":[{\"str\":1,\"fret\":7},{\"str\":2,\"fret\":7},{\"str\":3,\"fret\":7}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":8},{\"str\":2,\"fret\":8},{\"str\":3,\"fret\":8}]},{\"tact\":true}]', 48, 1, 38),
(133, 'Verse', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":3},{\"str\":2,\"fret\":3},{\"str\":3,\"fret\":3}]},{\"positions\":[{\"str\":1,\"fret\":5},{\"str\":2,\"fret\":5},{\"str\":3,\"fret\":5}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":7},{\"str\":2,\"fret\":7},{\"str\":3,\"fret\":7}]},{\"tact\":true}]', 49, 1, 39),
(134, 'Solo', '2025-06-16', '[{\"positions\":[{\"str\":1,\"fret\":12},{\"str\":2,\"fret\":12},{\"str\":3,\"fret\":12}]},{\"positions\":[{\"str\":1,\"fret\":14},{\"str\":2,\"fret\":14},{\"str\":3,\"fret\":14}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":15},{\"str\":2,\"fret\":15},{\"str\":3,\"fret\":15}]},{\"tact\":true}]', 49, 1, 40),
(138, 'Thunderstorm solo', '2025-06-16', '[{\"positions\":[{\"str\":3,\"fret\":6},{\"str\":2,\"fret\":6}]},{\"positions\":[{\"str\":6,\"fret\":0},{\"str\":5,\"fret\":0},{\"str\":4,\"fret\":1},{\"str\":3,\"fret\":1},{\"str\":2,\"fret\":0},{\"str\":1,\"fret\":0}]},{\"positions\":[{\"str\":2,\"fret\":6}]},{\"tact\":true},{\"positions\":[{\"str\":5,\"fret\":4}]}]', 73, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `songs`
--

CREATE TABLE `songs` (
  `songid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `performer` varchar(100) NOT NULL,
  `noteentrycount` int(11) DEFAULT 0,
  `userid` int(11) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `songs`
--

INSERT INTO `songs` (`songid`, `title`, `performer`, `noteentrycount`, `userid`, `status`) VALUES
(20, 'Everlong', 'Foo Fighters', 0, 16, 'approved'),
(21, 'The Pretender', 'Foo Fighters', 0, 16, 'approved'),
(26, 'Wesside', 'Linkin Park', 0, 22, 'approved'),
(27, 'Spokoynaya noch', 'Kino', 0, 1, 'approved'),
(28, 'The Kids Aren\'t Alright', 'The Offspring', 0, 1, 'approved'),
(29, 'BAD', 'Micheal Jacksons', 0, 39, 'approved'),
(30, 'Sweet Child O\' Mine', 'Guns N\' Roses', 0, 1, 'approved'),
(31, 'Stairway to Heaven', 'Led Zeppelin', 0, 2, 'approved'),
(32, 'Hotel California', 'Eagles', 0, 3, 'approved'),
(33, 'Smells Like Teen Spirit', 'Nirvana', 0, 4, 'approved'),
(34, 'Sweet Home Alabama', 'Lynyrd Skynyrd', 0, 5, 'approved'),
(35, 'Wonderwall', 'Oasis', 0, 6, 'approved'),
(36, 'Purple Haze', 'Jimi Hendrix', 0, 7, 'approved'),
(37, 'Back in Black', 'AC/DC', 0, 8, 'approved'),
(38, 'Sweet Caroline', 'Neil Diamond', 0, 9, 'approved'),
(39, 'Smoke on the Water', 'Deep Purple', 0, 10, 'approved'),
(40, 'Sweet Dreams', 'Eurythmics', 0, 11, 'approved'),
(41, 'Sweet Emotion', 'Aerosmith', 0, 12, 'approved'),
(42, 'Sweet Jane', 'The Velvet Underground', 0, 13, 'approved'),
(43, 'Sweet Leaf', 'Black Sabbath', 0, 14, 'approved'),
(44, 'Sweet Melissa', 'The Allman Brothers Band', 0, 15, 'approved'),
(45, 'Sweet Virginia', 'The Rolling Stones', 0, 16, 'approved'),
(46, 'Sweet Talkin\' Woman', 'Electric Light Orchestra', 0, 17, 'approved'),
(47, 'Sweet Surrender', 'Bread', 0, 18, 'approved'),
(48, 'Sweet Thing', 'Van Morrison', 0, 19, 'approved'),
(49, 'Sweet Young Thing', 'The Monkees', 0, 20, 'approved'),
(50, 'Sweet Little Angel', 'B.B. King', 0, 21, 'approved'),
(51, 'Sweet Home Chicago', 'Robert Johnson', 0, 22, 'approved'),
(52, 'Sweet Georgia Brown', 'Brother Bones', 0, 23, 'approved'),
(53, 'Sweet Adeline', 'The Mills Brothers', 0, 24, 'approved'),
(54, 'Sweet Lorraine', 'Nat King Cole', 0, 25, 'approved'),
(55, 'Sweet Sue', 'Benny Goodman', 0, 26, 'approved'),
(56, 'Sweet and Lovely', 'Guy Lombardo', 0, 27, 'approved'),
(57, 'Sweet Leilani', 'Bing Crosby', 0, 28, 'approved'),
(58, 'Sweet and Low', 'Artie Shaw', 0, 29, 'approved'),
(59, 'Sweet Someone', 'Glenn Miller', 0, 30, 'approved'),
(60, 'Sweet Eloise', 'The Ink Spots', 0, 31, 'approved'),
(61, 'Sweet Dreams', 'Patsy Cline', 0, 32, 'approved'),
(62, 'Sweet Nothin\'s', 'Brenda Lee', 0, 33, 'approved'),
(63, 'Sweet Talk', 'The Drifters', 0, 34, 'approved'),
(64, 'Sweet Little Sixteen', 'Chuck Berry', 0, 35, 'approved'),
(65, 'Sweet Nothings', 'Connie Francis', 0, 36, 'approved'),
(66, 'Sweet Talk', 'The Platters', 0, 37, 'approved'),
(67, 'Sweet Talk', 'The Shirelles', 0, 38, 'approved'),
(68, 'Sweet Talk', 'The Ronettes', 0, 39, 'approved'),
(69, 'Sweet Talk', 'The Crystals', 0, 40, 'approved'),
(70, 'I\'m the Man', '50 Cent', 0, NULL, 'approved'),
(71, 'reqeus', '50 Cent', 0, 81, 'pending'),
(72, 'k', 'k', 0, 81, 'pending'),
(73, 'Thunderstorm', 'AC/DC', 0, 1, 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `song_genres`
--

CREATE TABLE `song_genres` (
  `songid` int(11) NOT NULL,
  `genreid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `song_genres`
--

INSERT INTO `song_genres` (`songid`, `genreid`) VALUES
(27, 1),
(28, 1),
(29, 2),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 2),
(39, 1),
(40, 2),
(41, 1),
(42, 1),
(43, 7),
(44, 1),
(45, 1),
(46, 2),
(47, 2),
(48, 2),
(49, 2),
(50, 9),
(51, 9),
(52, 3),
(53, 3),
(54, 3),
(55, 3),
(56, 3),
(57, 3),
(58, 3),
(59, 3),
(60, 3),
(61, 2),
(62, 2),
(63, 2),
(64, 1),
(65, 2),
(66, 2),
(67, 2),
(68, 2),
(69, 2),
(70, 11),
(72, 9),
(73, 1);

-- --------------------------------------------------------

--
-- Table structure for table `threadcomments`
--

CREATE TABLE `threadcomments` (
  `commentid` int(11) NOT NULL,
  `content` text NOT NULL,
  `replytocommentid` int(11) DEFAULT NULL,
  `likecount` int(11) DEFAULT 0,
  `threadid` int(11) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `threadcomments`
--

INSERT INTO `threadcomments` (`commentid`, `content`, `replytocommentid`, `likecount`, `threadid`, `userid`) VALUES
(65, 'Gan jau, viņš ir viscītīgākais.', 0, 0, 19, 1),
(67, 'Jimmy Hendrix!!!', 0, 0, 32, 39),
(68, 'NOTEIKTI NOKARTOS', 0, 0, 19, 39),
(69, 'aliexpress.com', 0, 0, 31, 39),
(70, 'She is alright', 0, 0, 30, 39),
(71, 'no, sorry, ss.lv', 69, 0, 31, 39),
(75, 'Agreed! The way he uses feedback is incredible', 1, 3, 74, 2),
(76, 'Don\'t forget Eddie Van Halen\'s Eruption!', 0, 4, 74, 3),
(77, 'That tapping technique changed everything', 3, 2, 74, 4),
(78, 'What about David Gilmour\'s Comfortably Numb solo?', 0, 6, 74, 5),
(79, 'The emotion in that solo is unmatched', 5, 3, 74, 6),
(80, 'Slash\'s November Rain solo is a masterpiece', 0, 4, 74, 7),
(81, 'The way he builds up to the climax is perfect', 7, 2, 74, 8),
(82, 'Don\'t forget Randy Rhoads!', 0, 3, 74, 9),
(83, 'His technical ability was incredible', 9, 2, 74, 10),
(84, 'Start with basic chords!', 0, 4, 75, 11),
(85, 'Which chords should I learn first?', 11, 2, 75, 12),
(86, 'G, C, D, and E minor are great to start with', 12, 3, 75, 13),
(87, 'Practice switching between them slowly', 13, 2, 75, 14),
(88, 'Don\'t forget to use a metronome!', 0, 5, 75, 15),
(89, 'What tempo should I start with?', 15, 2, 75, 16),
(90, 'Try 60 BPM and gradually increase', 16, 3, 75, 17),
(91, 'Also, focus on proper finger placement', 0, 4, 75, 18),
(92, 'Any tips for sore fingers?', 18, 2, 75, 19),
(93, 'It gets better with time, keep practicing!', 19, 3, 75, 20),
(94, 'Fender Stratocaster is my favorite', 0, 5, 76, 21),
(95, 'Why do you prefer it?', 21, 2, 76, 22),
(96, 'The versatility and tone are unmatched', 22, 3, 76, 23),
(97, 'I love my Gibson Les Paul', 0, 4, 76, 24),
(98, 'The sustain is incredible', 24, 2, 76, 25),
(99, 'What about PRS?', 0, 3, 76, 26),
(100, 'Great quality and playability', 26, 2, 76, 27),
(101, 'Don\'t forget about Ibanez!', 0, 4, 76, 28),
(102, 'Perfect for metal and rock', 28, 2, 76, 29),
(103, 'What about acoustic brands?', 0, 3, 76, 30),
(104, 'YouTube has great tutorials', 0, 5, 77, 31),
(105, 'Any specific channels you recommend?', 31, 2, 77, 32),
(106, 'Justin Guitar is amazing for beginners', 32, 3, 77, 33),
(107, 'What about paid courses?', 0, 4, 77, 34),
(108, 'Fender Play is worth it', 34, 2, 77, 35),
(109, 'Any good books?', 0, 3, 77, 36),
(110, 'The Guitar Handbook is a classic', 36, 2, 77, 37),
(111, 'What about apps?', 0, 4, 77, 38),
(112, 'Yousician is great for practice', 38, 2, 77, 39),
(113, 'Any other recommendations?', 0, 3, 77, 40),
(114, 'Change strings regularly!', 0, 5, 78, 1),
(115, 'How often should I change them?', 1, 2, 78, 2),
(116, 'Every 3-4 months with regular playing', 2, 3, 78, 3),
(117, 'What about cleaning?', 0, 4, 78, 4),
(118, 'Use a microfiber cloth after playing', 4, 2, 78, 5),
(119, 'How to prevent humidity damage?', 0, 3, 78, 6),
(120, 'Use a humidifier in dry climates', 6, 2, 78, 7),
(121, 'What about storage?', 0, 4, 78, 8),
(122, 'Keep it in a case when not playing', 8, 2, 78, 9),
(123, 'Any other maintenance tips?', 0, 3, 78, 10),
(124, 'Jimi Hendrix changed everything', 0, 5, 79, 11),
(125, 'His innovation was incredible', 11, 2, 79, 12),
(126, 'What about Eddie Van Halen?', 0, 4, 79, 13),
(127, 'His technique was revolutionary', 13, 2, 79, 14),
(128, 'Don\'t forget David Gilmour', 0, 3, 79, 15),
(129, 'His phrasing is perfect', 15, 2, 79, 16),
(130, 'What about modern players?', 0, 4, 79, 17),
(131, 'John Mayer is amazing', 17, 2, 79, 18),
(132, 'Any other favorites?', 0, 3, 79, 19),
(133, 'I love my Tube Screamer', 0, 5, 80, 20),
(134, 'What settings do you use?', 20, 2, 80, 21),
(135, 'Drive at 9 o\'clock, tone at 12', 21, 3, 80, 22),
(136, 'What about delay?', 0, 4, 80, 23),
(137, 'Boss DD-7 is my go-to', 23, 2, 80, 24),
(138, 'Any good reverb pedals?', 0, 3, 80, 25),
(139, 'Strymon BigSky is amazing', 25, 2, 80, 26),
(140, 'What about multi-effects?', 0, 4, 80, 27),
(141, 'Line 6 Helix is great', 27, 2, 80, 28),
(142, 'Any other recommendations?', 0, 3, 80, 29),
(143, 'Use a good microphone', 0, 5, 81, 30),
(144, 'Which mic do you recommend?', 30, 2, 81, 31),
(145, 'SM57 is a classic', 31, 3, 81, 32),
(146, 'What about DI recording?', 0, 4, 81, 33),
(147, 'Axe-Fx is great for direct recording', 33, 2, 81, 34),
(148, 'How to get good tone?', 0, 3, 81, 35),
(149, 'Start with a clean sound', 35, 2, 81, 36),
(150, 'What about mixing?', 0, 4, 81, 37),
(151, 'EQ is crucial', 37, 2, 81, 38),
(152, 'Any other tips?', 0, 3, 81, 39),
(153, 'Learn the major scale first', 0, 5, 82, 40),
(154, 'Why is it important?', 40, 2, 82, 1),
(155, 'It\'s the foundation of most music', 41, 3, 82, 2),
(156, 'What about modes?', 0, 4, 82, 3),
(157, 'Start with Ionian and Aeolian', 43, 2, 82, 4),
(158, 'How to learn chord construction?', 0, 3, 82, 5),
(159, 'Start with triads', 45, 2, 82, 6),
(160, 'What about progressions?', 0, 4, 82, 7),
(161, 'I-IV-V is a good start', 47, 2, 82, 8),
(162, 'Any other theory basics?', 0, 3, 82, 9),
(163, 'Hammer-ons and pull-offs are essential', 0, 5, 83, 10),
(164, 'How to practice them?', 50, 2, 83, 11),
(165, 'Start slow and build speed', 51, 3, 83, 12),
(166, 'What about bending?', 0, 4, 83, 13),
(167, 'Practice with a tuner', 53, 2, 83, 14),
(168, 'How to improve vibrato?', 0, 3, 83, 15),
(169, 'Start wide and get tighter', 55, 2, 83, 16),
(170, 'What about tapping?', 0, 4, 83, 17),
(171, 'Start with simple patterns', 57, 2, 83, 18),
(172, 'Any other techniques?', 0, 3, 83, 19),
(173, 'I use a Fender amp', 0, 5, 84, 20),
(174, 'Which model?', 60, 2, 84, 21),
(175, 'Twin Reverb is my favorite', 61, 3, 84, 22),
(176, 'What about pedals?', 0, 4, 84, 23),
(177, 'I use a small board', 63, 2, 84, 24),
(178, 'How to choose a guitar?', 0, 3, 84, 25),
(179, 'Try different ones in store', 65, 2, 84, 26),
(180, 'What about strings?', 0, 4, 84, 27),
(181, 'I prefer Ernie Ball', 67, 2, 84, 28),
(182, 'Any other gear?', 0, 3, 84, 29),
(183, 'Private lessons helped me most', 0, 5, 85, 30),
(184, 'How often did you go?', 70, 2, 85, 31),
(185, 'Once a week for a year', 71, 3, 85, 32),
(186, 'What about online lessons?', 0, 4, 85, 33),
(187, 'They can be good too', 73, 2, 85, 34),
(188, 'How to find a good teacher?', 0, 3, 85, 35),
(189, 'Ask for recommendations', 75, 2, 85, 36),
(190, 'What about group lessons?', 0, 4, 85, 37),
(191, 'Great for motivation', 77, 2, 85, 38),
(192, 'Any other advice?', 0, 3, 85, 39),
(193, 'Set specific goals', 0, 5, 86, 40),
(194, 'Like what?', 80, 2, 86, 1),
(195, 'Learn one song per week', 81, 3, 86, 2),
(196, 'How long to practice?', 0, 4, 86, 3),
(197, 'Start with 30 minutes daily', 83, 2, 86, 4),
(198, 'What to practice?', 0, 3, 86, 5),
(199, 'Mix of scales and songs', 85, 2, 86, 6),
(200, 'How to stay motivated?', 0, 4, 86, 7),
(201, 'Record your progress', 87, 2, 86, 8),
(202, 'Any other tips?', 0, 3, 86, 9),
(203, 'Currently learning Stairway', 0, 5, 87, 10),
(204, 'How\'s it going?', 90, 2, 87, 11),
(205, 'The solo is challenging', 91, 3, 87, 12),
(206, 'What about other songs?', 0, 4, 87, 13),
(207, 'Working on Hotel California', 93, 2, 87, 14),
(208, 'How to choose songs?', 0, 3, 87, 15),
(209, 'Start with your favorites', 95, 2, 87, 16),
(210, 'What about difficulty?', 0, 4, 87, 17),
(211, 'Gradually increase challenge', 97, 2, 87, 18),
(212, 'Any recommendations?', 0, 3, 87, 19),
(213, 'Guitar started in Spain', 0, 5, 88, 20),
(214, 'Really? Tell me more', 100, 2, 88, 21),
(215, 'It evolved from the lute', 101, 3, 88, 22),
(216, 'What about electric guitar?', 0, 4, 88, 23),
(217, 'Les Paul was a pioneer', 103, 2, 88, 24),
(218, 'How did it evolve?', 0, 3, 88, 25),
(219, 'Many innovations over time', 105, 2, 88, 26),
(220, 'What about modern guitars?', 0, 4, 88, 27),
(221, 'Digital technology changed everything', 107, 2, 88, 28),
(222, 'Any other history?', 0, 3, 88, 29),
(223, 'I play blues mostly', 0, 5, 89, 30),
(224, 'What got you into blues?', 110, 2, 89, 31),
(225, 'BB King inspired me', 111, 3, 89, 32),
(226, 'What about rock?', 0, 4, 89, 33),
(227, 'Classic rock is my favorite', 113, 2, 89, 34),
(228, 'How about jazz?', 0, 3, 89, 35),
(229, 'Still learning jazz chords', 115, 2, 89, 36),
(230, 'What about metal?', 0, 4, 89, 37),
(231, 'Love playing metal riffs', 117, 2, 89, 38),
(232, 'Any other styles?', 0, 3, 89, 39),
(233, 'Start with a good amp', 0, 5, 90, 40),
(234, 'Which one?', 120, 2, 90, 1),
(235, 'Fender Mustang is great', 121, 3, 90, 2),
(236, 'What about pedals?', 0, 4, 90, 3),
(237, 'Start with basics', 123, 2, 90, 4),
(238, 'How to choose a guitar?', 0, 3, 90, 5),
(239, 'Try different ones', 125, 2, 90, 6),
(240, 'What about accessories?', 0, 4, 90, 7),
(241, 'Get good cables', 127, 2, 90, 8),
(242, 'Any other essentials?', 0, 3, 90, 9),
(243, 'First gig was nerve-wracking', 0, 5, 91, 10),
(244, 'How did you handle it?', 130, 2, 91, 11),
(245, 'Practice helped a lot', 131, 3, 91, 12),
(246, 'What about mistakes?', 0, 4, 91, 13),
(247, 'Keep playing through them', 133, 2, 91, 14),
(248, 'How to prepare?', 0, 3, 91, 15),
(249, 'Practice with a band', 135, 2, 91, 16),
(250, 'What about stage presence?', 0, 4, 91, 17),
(251, 'Be confident and have fun', 137, 2, 91, 18),
(252, 'Any other tips?', 0, 3, 91, 19),
(253, 'Start with a melody', 0, 5, 92, 20),
(254, 'How to develop it?', 140, 2, 92, 21),
(255, 'Add chords underneath', 141, 3, 92, 22),
(256, 'What about structure?', 0, 4, 92, 23),
(257, 'Verse-chorus-verse is classic', 143, 2, 92, 24),
(258, 'How to add solos?', 0, 3, 92, 25),
(259, 'Build on the melody', 145, 2, 92, 26),
(260, 'What about lyrics?', 0, 4, 92, 27),
(261, 'Match the mood of the music', 147, 2, 92, 28),
(262, 'Any other advice?', 0, 3, 92, 29),
(263, 'Share your knowledge', 0, 5, 93, 30),
(264, 'How to help beginners?', 150, 2, 93, 31),
(265, 'Be patient and encouraging', 151, 3, 93, 32),
(266, 'What about online?', 0, 4, 93, 33),
(267, 'Create helpful content', 153, 2, 93, 34),
(268, 'How to connect?', 0, 3, 93, 35),
(269, 'Join local groups', 155, 2, 93, 36),
(270, 'What about events?', 0, 4, 93, 37),
(271, 'Organize jam sessions', 157, 2, 93, 38),
(272, 'Any other ideas?', 0, 3, 93, 39),
(273, 'I dont write lyrics', 0, 0, 54, 1),
(274, 'shes mid', 0, 0, 30, 1),
(277, 'asda', 0, 0, 94, 1),
(278, 'n', 277, 0, 94, 1);

-- --------------------------------------------------------

--
-- Table structure for table `threads`
--

CREATE TABLE `threads` (
  `threadid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `createdby` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `threads`
--

INSERT INTO `threads` (`threadid`, `title`, `content`, `createdby`) VALUES
(19, 'Zhenja nodos eksamenu', 'gribu zinat jusu domas?\r\n', 22),
(29, 'Any tips for begginners?', 'I just started playing guitar, wanted to see if any of you guys had any tips', 1),
(30, 'New Linking Park lead singer?', 'What do you guys think about her?', 1),
(31, 'Cheap capo\'s?', 'Does anyone know where i can order capos for cheap?', 34),
(32, 'Who is best guitarist of all time????', ':D', 34),
(33, 'What is a must learn?', 'What is a must learn for complete begginners when starting out playing guitar?', 39),
(34, 'Favorite Guitar Riffs?', 'Share your favorite guitar riffs here!', 1),
(35, 'Best Practice Routines', 'What routines help you improve fastest?', 2),
(36, 'Songwriting Tips', 'How do you start writing a new song?', 3),
(37, 'Drum Patterns', 'Post your go-to drum patterns.', 4),
(38, 'Bass Grooves', 'Let’s talk about funky bass lines!', 5),
(39, 'Piano Progressions', 'Share your favorite chord progressions.', 6),
(40, 'Vocal Warmups', 'How do you warm up your voice?', 7),
(41, 'Recording Gear', 'What gear do you use for home recording?', 8),
(42, 'Mixing Advice', 'Tips for mixing tracks?', 9),
(43, 'Live Performance Stories', 'Share your best/worst gig stories.', 10),
(44, 'Favorite Genres', 'What genres inspire you most?', 11),
(45, 'Music Theory Questions', 'Ask your theory questions here.', 12),
(46, 'Instrument Maintenance', 'How do you care for your instruments?', 13),
(47, 'Best Music Apps', 'What apps do you use for music?', 14),
(48, 'Collaborations', 'Looking for collaborators!', 15),
(49, 'Cover Songs', 'What covers are you working on?', 16),
(50, 'Improvisation', 'How do you practice improv?', 17),
(51, 'Music Education', 'Share your learning resources.', 18),
(52, 'Stage Fright', 'How do you deal with nerves?', 19),
(53, 'Favorite Albums', 'What albums changed your life?', 20),
(54, 'Lyric Writing', 'Share your lyric writing process.', 1),
(55, 'Favorite Producers', 'Who are your favorite producers?', 2),
(56, 'Music Challenges', 'Set a challenge for the community!', 3),
(57, 'Gear Reviews', 'Review your latest gear purchase.', 4),
(58, 'Favorite Venues', 'Best places you’ve played?', 5),
(59, 'Music Memes', 'Share your favorite music memes.', 6),
(60, 'Practice Logs', 'Log your daily practice here.', 7),
(61, 'Music News', 'Share the latest music news.', 8),
(62, 'Favorite Scales', 'What scales do you use most?', 9),
(63, 'Song Analysis', 'Analyze a song together.', 10),
(64, 'Music Books', 'Recommend your favorite books.', 11),
(65, 'Ear Training', 'How do you train your ear?', 12),
(66, 'Favorite Licks', 'Share your favorite licks.', 13),
(67, 'Music Festivals', 'Who’s going to which festivals?', 14),
(68, 'Studio Setups', 'Show off your studio!', 15),
(69, 'Favorite Strings', 'What strings do you use?', 16),
(70, 'Music Videos', 'Share your music videos.', 17),
(71, 'Favorite Drummers', 'Who are your favorite drummers?', 18),
(72, 'Music Podcasts', 'Recommend a podcast.', 19),
(73, 'Favorite Songwriters', 'Who inspires your writing?', 20),
(74, 'Best Guitar Solos of All Time', 'What are your favorite guitar solos? Share your thoughts!', 1),
(75, 'Tips for Beginners', 'New to guitar? Share your questions and tips here!', 2),
(76, 'Favorite Guitar Brands', 'Which guitar brands do you prefer and why?', 3),
(77, 'Learning Resources', 'Share your favorite resources for learning guitar', 4),
(78, 'Guitar Maintenance', 'How do you take care of your guitar?', 5),
(79, 'Favorite Guitarists', 'Who are your favorite guitarists and why?', 6),
(80, 'Guitar Effects', 'What effects pedals do you use?', 7),
(81, 'Recording Tips', 'Share your tips for recording guitar', 8),
(82, 'Guitar Theory', 'Discuss music theory for guitarists', 9),
(83, 'Guitar Techniques', 'Share your favorite techniques', 10),
(84, 'Guitar Gear', 'What gear do you use?', 11),
(85, 'Guitar Lessons', 'Share your experience with lessons', 12),
(86, 'Guitar Practice', 'How do you practice?', 13),
(87, 'Guitar Songs', 'What songs are you learning?', 14),
(88, 'Guitar History', 'Discuss the history of guitar', 15),
(89, 'Guitar Styles', 'What styles do you play?', 16),
(90, 'Guitar Equipment', 'What equipment do you need?', 17),
(91, 'Guitar Performance', 'Share your performance experiences', 18),
(92, 'Guitar Composition', 'How do you write guitar parts?', 19),
(93, 'Guitar Community', 'Let\'s build a better guitar community!', 20),
(94, 'My first thread', 'dont judge me, tell me what can i do in NoteTone?', 80);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `moderatorstatus` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `username`, `password`, `moderatorstatus`) VALUES
(1, 'god', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 1),
(22, 'testuser1', '$2y$10$Mmo/OGP5VOoyN0b2tOSVduxSq255ZqVF56jimiv9R8xGwDgRRHele', 0),
(23, 'testuser2', '$2y$10$DEL38UFkEM8Nw8eE9bKgPOey8GQSXsJz7JSWOLvIDoJ1uk8YoYVl2', 0),
(24, 'mrkrister', '$2y$10$wCCcTGx2WH8Nvy39QzsM4OWHS6fe7K23v699jeXtk/AMq0AOpLXa2', 0),
(34, 'dog', '$2y$10$THv9qJQixHQ2tymikfHghel0QkYjvWmDaufPO3RQ09Yw6pPokqQ9S', 0),
(37, 'DAWG', '$2y$10$uGUOgZFggySsmqdOPQvrk.8xZDdhBIesxh7pDWD.Sov1leatKebhK', 0),
(38, 'user3', '$2y$10$eNe5Wl.5uv37erfuAYox1.oEGOZVmj7f2AFEvqYFyFwPwKxBNdvna', 0),
(39, 'user4', '$2y$10$kF9bI.HQaCZddg1tg1A36O2AoBmVlu2nBwOqyvIPLR6xRLplhu4mu', 0),
(40, 'melody_maker', 'password', 0),
(41, 'tabmaster', 'password', 0),
(42, 'groove_guru', 'password', 0),
(43, 'note_ninja', 'password', 0),
(44, 'chord_chief', 'password', 0),
(45, 'rhythm_queen', 'password', 0),
(46, 'fretboarder', 'password', 0),
(47, 'harmonizer', 'password', 0),
(48, 'bassboss', 'password', 0),
(49, 'drum_dreamer', 'password', 0),
(50, 'piano_pal', 'password', 0),
(51, 'sax_sensation', 'password', 0),
(52, 'vocal_vibe', 'password', 0),
(53, 'lyricist_lad', 'password', 0),
(54, 'composer_cat', 'password', 0),
(55, 'string_slinger', 'password', 0),
(56, 'beat_builder', 'password', 0),
(57, 'tune_tiger', 'password', 0),
(58, 'jam_jester', 'password', 0),
(59, 'solo_star', 'password', 0),
(60, 'guitar_master', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
(61, 'rock_star', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
(62, 'jazz_cat', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
(63, 'metal_head', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
(64, 'blues_king', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
(65, 'folk_singer', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
(66, 'classical_maestro', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
(67, 'pop_princess', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
(68, 'hip_hop_hero', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
(69, 'country_crooner', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
(70, 'electronic_beat', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
(71, 'indie_artist', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
(72, 'punk_rocker', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
(73, 'soul_singer', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
(74, 'reggae_rhythm', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
(75, 'funk_master', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
(76, 'gospel_voice', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
(77, 'latin_beat', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
(78, 'world_music', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
(79, 'jazz_fusion', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
(80, 'testuser100', '$2y$10$RK2JXDDqnR2nf8DXJikfTeTG.UtF/EY/y5edQ8gNSgIJDNe23STru', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audio`
--
ALTER TABLE `audio`
  ADD PRIMARY KEY (`audioid`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`genreid`);

--
-- Indexes for table `instruments`
--
ALTER TABLE `instruments`
  ADD PRIMARY KEY (`instrumentid`);

--
-- Indexes for table `notations`
--
ALTER TABLE `notations`
  ADD PRIMARY KEY (`notationid`);

--
-- Indexes for table `songs`
--
ALTER TABLE `songs`
  ADD PRIMARY KEY (`songid`);

--
-- Indexes for table `song_genres`
--
ALTER TABLE `song_genres`
  ADD PRIMARY KEY (`songid`,`genreid`),
  ADD KEY `genreid` (`genreid`);

--
-- Indexes for table `threadcomments`
--
ALTER TABLE `threadcomments`
  ADD PRIMARY KEY (`commentid`);

--
-- Indexes for table `threads`
--
ALTER TABLE `threads`
  ADD PRIMARY KEY (`threadid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audio`
--
ALTER TABLE `audio`
  MODIFY `audioid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `genreid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `instruments`
--
ALTER TABLE `instruments`
  MODIFY `instrumentid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `notations`
--
ALTER TABLE `notations`
  MODIFY `notationid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `songs`
--
ALTER TABLE `songs`
  MODIFY `songid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `threadcomments`
--
ALTER TABLE `threadcomments`
  MODIFY `commentid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=279;

--
-- AUTO_INCREMENT for table `threads`
--
ALTER TABLE `threads`
  MODIFY `threadid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `song_genres`
--
ALTER TABLE `song_genres`
  ADD CONSTRAINT `song_genres_ibfk_1` FOREIGN KEY (`songid`) REFERENCES `songs` (`songid`) ON DELETE CASCADE,
  ADD CONSTRAINT `song_genres_ibfk_2` FOREIGN KEY (`genreid`) REFERENCES `genres` (`genreid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

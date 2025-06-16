-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2025 at 08:27 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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
(10, 'Country', 'Country music');

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
(72, 'newar', '2025-06-04', '[{\"positions\":[{\"str\":3,\"fret\":3},{\"str\":1,\"fret\":2}]},{\"tact\":true},{\"positions\":[{\"str\":2,\"fret\":2},{\"str\":1,\"fret\":1}]},{\"positions\":[{\"str\":3,\"fret\":5},{\"str\":2,\"fret\":6}]},{\"tact\":true},{\"positions\":[{\"str\":3,\"fret\":4},{\"str\":1,\"fret\":2}]},{\"tact\":true},{\"positions\":[{\"str\":3,\"fret\":6}]},{\"tact\":true},{\"positions\":[{\"str\":3,\"fret\":4},{\"str\":2,\"fret\":3}]},{\"positions\":[{\"str\":3,\"fret\":4},{\"str\":2,\"fret\":4}]},{\"tact\":true},{\"positions\":[{\"str\":4,\"fret\":4},{\"str\":2,\"fret\":3}]},{\"tact\":true},{\"positions\":[{\"str\":3,\"fret\":3},{\"str\":2,\"fret\":3}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":2}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":2}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":2}]},{\"tact\":true},{\"positions\":[{\"str\":3,\"fret\":3},{\"str\":2,\"fret\":2}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":3}]},{\"positions\":[{\"str\":3,\"fret\":3}]},{\"positions\":[{\"str\":1,\"fret\":3}]},{\"positions\":[{\"str\":1,\"fret\":2}]},{\"positions\":[{\"str\":2,\"fret\":10}]},{\"positions\":[{\"str\":5,\"fret\":11}]},{\"positions\":[{\"str\":3,\"fret\":4}]},{\"tact\":true},{\"positions\":[{\"str\":2,\"fret\":4}]},{\"positions\":[{\"str\":1,\"fret\":6}]},{\"positions\":[{\"str\":1,\"fret\":4}]},{\"positions\":[{\"str\":1,\"fret\":3}]},{\"positions\":[{\"str\":1,\"fret\":2}]},{\"positions\":[{\"str\":5,\"fret\":6}]},{\"positions\":[{\"str\":4,\"fret\":3}]},{\"positions\":[{\"str\":4,\"fret\":2}]},{\"positions\":[{\"str\":3,\"fret\":3}]},{\"positions\":[{\"str\":3,\"fret\":4}]},{\"positions\":[{\"str\":2,\"fret\":2}]},{\"tact\":true},{\"positions\":[{\"str\":3,\"fret\":5}]},{\"positions\":[{\"str\":2,\"fret\":3}]},{\"positions\":[{\"str\":3,\"fret\":3}]},{\"tact\":true},{\"tact\":true}]', 20, 1, 22),
(73, 'krsiter', '2025-06-04', '[{\"positions\":[{\"str\":6,\"fret\":0},{\"str\":5,\"fret\":0},{\"str\":1,\"fret\":0}]},{\"positions\":[{\"str\":1,\"fret\":1}]},{\"positions\":[{\"str\":3,\"fret\":3},{\"str\":2,\"fret\":3},{\"str\":1,\"fret\":3}]},{\"positions\":[{\"str\":2,\"fret\":0},{\"str\":1,\"fret\":0}]},{\"tact\":true},{\"positions\":[{\"str\":5,\"fret\":2},{\"str\":4,\"fret\":2},{\"str\":2,\"fret\":0},{\"str\":1,\"fret\":0}]}]', 21, 1, 24),
(85, '1', '2025-06-05', 'e--------------------------------------------------------------------------------------------------------------------------------------------------------------------1\\nB---------------------------------------------------------------------------------------------------------------------------------------------------------------------\\nG---------1-----------------------------------------------------------------------------------------------------------------------------------------------------------\\nD------------------------------2-------------------------------------------------------------------------------------------------------------------------------------1\\nA---------------------------------------------------------------------------------------------------------------------------------------------------------------------\\nE---------------------------------------------------------------------------------------------------------------------------------------------------------------------', 20, 1, 1),
(86, 'asda', '2025-06-05', 'e---------------------------------------------------------------------------------------------------------------------------------------------------------------------\\nB---------------------------------------------------------------------------------------------------------------------------------------------------------------------\\nG---------------------------------------------------------------------------------------------------------------------------------------------------------------------\\nD---------------------------------------------------------------------------------------------------------------------------------------------------------------------\\nA---------------------------------------------------------------------------------------------------------------------------------------------------------------------\\nE---------------------------------------------------------------------------------------------------------------------------------------------------------------------', 20, 1, 1),
(87, 'spokoynaya noch solo', '2025-06-05', '[{\"positions\":[{\"str\":2,\"fret\":8}]},{\"positions\":[{\"str\":3,\"fret\":7}]},{\"positions\":[{\"str\":5,\"fret\":8}]},{\"tact\":true},{\"positions\":[{\"str\":4,\"fret\":10}]},{\"positions\":[{\"str\":4,\"fret\":9}]},{\"positions\":[{\"str\":3,\"fret\":9}]},{\"tact\":true},{\"positions\":[{\"str\":3,\"fret\":9}]},{\"positions\":[{\"str\":3,\"fret\":10}]},{\"positions\":[{\"str\":3,\"fret\":12}]},{\"tact\":true},{\"positions\":[{\"str\":3,\"fret\":9}]},{\"positions\":[{\"str\":3,\"fret\":10}]},{\"positions\":[{\"str\":4,\"fret\":12}]},{\"tact\":true},{\"positions\":[{\"str\":2,\"fret\":8}]},{\"positions\":[{\"str\":3,\"fret\":7}]},{\"positions\":[{\"str\":5,\"fret\":8}]},{\"tact\":true},{\"positions\":[{\"str\":3,\"fret\":10}]},{\"positions\":[{\"str\":3,\"fret\":12}]},{\"positions\":[{\"str\":3,\"fret\":9}]},{\"tact\":true},{\"positions\":[{\"str\":3,\"fret\":9}]},{\"positions\":[{\"str\":3,\"fret\":10}]},{\"positions\":[{\"str\":3,\"fret\":12}]},{\"positions\":[{\"str\":3,\"fret\":12}]},{\"positions\":[{\"str\":3,\"fret\":10}]},{\"positions\":[{\"str\":3,\"fret\":9}]},{\"tact\":true},{\"positions\":[{\"str\":3,\"fret\":9}]},{\"positions\":[{\"str\":3,\"fret\":10}]},{\"positions\":[{\"str\":3,\"fret\":12}]},{\"positions\":[{\"str\":4,\"fret\":12}]},{\"positions\":[{\"str\":4,\"fret\":10}]},{\"positions\":[{\"str\":4,\"fret\":9}]},{\"tact\":true}]', 27, 2, 1),
(88, 'Offspring parody', '2025-06-05', '[{\"positions\":[{\"str\":2,\"fret\":1}]},{\"positions\":[{\"str\":2,\"fret\":1}]},{\"positions\":[{\"str\":1,\"fret\":1}]},{\"positions\":[{\"str\":2,\"fret\":1}]},{\"positions\":[{\"str\":1,\"fret\":1}]},{\"positions\":[{\"str\":2,\"fret\":1}]},{\"positions\":[{\"str\":3,\"fret\":6}]},{\"positions\":[{\"str\":5,\"fret\":6},{\"str\":4,\"fret\":6}]},{\"tact\":true},{\"positions\":[{\"str\":3,\"fret\":3},{\"str\":2,\"fret\":2}]},{\"positions\":[{\"str\":4,\"fret\":5},{\"str\":2,\"fret\":4}]},{\"positions\":[{\"str\":1,\"fret\":3},{\"str\":2,\"fret\":3}]},{\"positions\":[{\"str\":2,\"fret\":2},{\"str\":3,\"fret\":2},{\"str\":1,\"fret\":0},{\"str\":4,\"fret\":0},{\"str\":5,\"fret\":0},{\"str\":6,\"fret\":0}]},{\"tact\":true}]', 28, 3, 1),
(89, 'New random notes', '2025-06-05', '[{\"positions\":[{\"str\":4,\"fret\":3},{\"str\":3,\"fret\":3}]},{\"positions\":[{\"str\":4,\"fret\":6},{\"str\":2,\"fret\":6}]},{\"positions\":[{\"str\":5,\"fret\":4},{\"str\":3,\"fret\":4}]},{\"tact\":true},{\"positions\":[{\"str\":5,\"fret\":5},{\"str\":3,\"fret\":5}]},{\"positions\":[{\"str\":5,\"fret\":9},{\"str\":3,\"fret\":9}]},{\"tact\":true},{\"positions\":[{\"str\":5,\"fret\":4},{\"str\":3,\"fret\":3}]},{\"tact\":true},{\"positions\":[{\"str\":3,\"fret\":5},{\"str\":2,\"fret\":5}]},{\"tact\":true},{\"positions\":[{\"str\":4,\"fret\":4}]},{\"tact\":true}]', 26, 3, 1),
(90, 'MY FIRST NOTATION', '2025-06-05', 'e---------------------------------------------------------------------------------------------------------------------------------------------------------------------\\nB--------------------------------1--------------------------------3---------------------------------------------------------------------------------------------------\\nG---------0-----------------------------------------------------------------------------------------------------------------------------------------------------------\\nD---------------------------------------------------------------------------------------------------------------------------------------------------------------------\\nA-----------------------2---------------------------------------------------------------------------------------------------------------------------------------------\\nE---------------------------------------------------------------------------------------------------------------------------------------------------------------------\\n\\ne---------------------------------------------------------------------------------------------------------------------------------------------------------------------\\nB--------------------------------1--------------------------------3---------------------------------------------------------------------------------------------------\\nG---------0-----------------------------------------------------------------------------------------------------------------------------------------------------------\\nD---------------------------------------------------------------------------------------------------------------------------------------------------------------------\\nA-----------------------2---------------------------------------------------------------------------------------------------------------------------------------------\\nE---------------------------------------------------------------------------------------------------------------------------------------------------------------------\\n\\n\\n\\n\\n\\n\\n\\n\\n\\n', 20, 1, 39),
(91, '2', '2025-06-05', '[{\"positions\":[{\"str\":1,\"fret\":0}]},{\"positions\":[{\"str\":5,\"fret\":1},{\"str\":4,\"fret\":1},{\"str\":1,\"fret\":2},{\"str\":2,\"fret\":2},{\"str\":3,\"fret\":2},{\"str\":6,\"fret\":2}]},{\"positions\":[{\"str\":4,\"fret\":3},{\"str\":2,\"fret\":3}]},{\"positions\":[{\"str\":3,\"fret\":4},{\"str\":2,\"fret\":3}]},{\"positions\":[{\"str\":2,\"fret\":4},{\"str\":3,\"fret\":4}]},{\"tact\":true},{\"positions\":[{\"str\":1,\"fret\":4},{\"str\":2,\"fret\":4},{\"str\":3,\"fret\":5},{\"str\":4,\"fret\":5},{\"str\":5,\"fret\":3},{\"str\":6,\"fret\":3}]},{\"positions\":[{\"str\":3,\"fret\":5},{\"str\":2,\"fret\":5},{\"str\":1,\"fret\":3},{\"str\":4,\"fret\":3},{\"str\":5,\"fret\":3},{\"str\":6,\"fret\":3}]}]', 26, 3, 39);

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
(29, 'BAD', 'Micheal Jacksons', 0, 39, 'approved');

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
(29, 2);

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
(48, 'asd', 0, 0, 21, 1),
(49, 'xd', 0, 0, 21, 1),
(50, 'asdas', 0, 0, 22, 1),
(65, 'Gan jau, viņš ir viscītīgākais.', 0, 0, 19, 1),
(66, 'posti', 0, 0, 21, 1),
(67, 'Jimmy Hendrix!!!', 0, 0, 32, 39),
(68, 'NOTEIKTI NOKARTOS', 0, 0, 19, 39),
(69, 'aliexpress.com', 0, 0, 31, 39),
(70, 'She is alright', 0, 0, 30, 39),
(71, 'no, sorry, ss.lv', 69, 0, 31, 39);

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
(33, 'What is a must learn?', 'What is a must learn for complete begginners when starting out playing guitar?', 39);

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
(39, 'user4', '$2y$10$kF9bI.HQaCZddg1tg1A36O2AoBmVlu2nBwOqyvIPLR6xRLplhu4mu', 0);

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
  MODIFY `genreid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `instruments`
--
ALTER TABLE `instruments`
  MODIFY `instrumentid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `notations`
--
ALTER TABLE `notations`
  MODIFY `notationid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `songs`
--
ALTER TABLE `songs`
  MODIFY `songid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `threadcomments`
--
ALTER TABLE `threadcomments`
  MODIFY `commentid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `threads`
--
ALTER TABLE `threads`
  MODIFY `threadid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

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

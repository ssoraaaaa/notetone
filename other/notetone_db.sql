-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2024 at 05:12 AM
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
-- Table structure for table `instruments`
--

CREATE TABLE `instruments` (
  `instrumentid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instruments`
--

INSERT INTO `instruments` (`instrumentid`, `name`, `type`) VALUES
(1, 'Electric guitar', 'String'),
(4, 'Piano', 'Classical'),
(5, 'Drums', 'Hittable'),
(6, 'Synth', 'Electrical'),
(7, 'Xylophone', 'Hittable');

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
(11, 'The pretender', '2024-06-24', 'laallaal', 21, 1, 16),
(13, 'w', '2024-06-25', 'w', 20, 4, 16),
(15, 'asas', '2024-06-25', 'asdad', 20, 4, 16),
(16, '12312', '2024-06-25', '123123', 21, 4, 16),
(17, 'asdasdads', '2024-06-25', 'asdasdaad', 21, 4, 16),
(18, '1d1wd1wd', '2024-06-25', '1wd1d', 21, 1, 16),
(19, 'My notation', '2024-06-25', 'ad1w1g1eg1', 21, 4, 18),
(20, 'asdas', '2024-06-25', 'asdasd', 24, 4, 18),
(21, 'asa', '2024-06-25', 'asdasd', 20, 4, 18),
(23, 'asdasdadsad', '2024-06-25', 'asda', 24, 1, 18),
(24, 'asdas', '2024-06-25', 'asdsad', 20, 1, 18),
(25, 'asdasd', '2024-06-25', 'asdasda', 21, 1, 18),
(27, 'asdasdd', '2024-06-25', 'asdasda', 20, 4, 18),
(28, 'asdads', '2024-06-25', 'asdasdasd', 20, 1, 18),
(29, 'Bazooka by haroldtheguy', '2024-06-25', '------d------------', 24, 5, 21);

-- --------------------------------------------------------

--
-- Table structure for table `songs`
--

CREATE TABLE `songs` (
  `songid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `performer` varchar(100) NOT NULL,
  `noteentrycount` int(11) DEFAULT 0,
  `userid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `songs`
--

INSERT INTO `songs` (`songid`, `title`, `performer`, `noteentrycount`, `userid`) VALUES
(20, 'Everlong', 'Foo Fighters', 0, 16),
(21, 'The Pretender', 'Foo Fighters', 0, 16),
(24, 'Wrath', 'Freddie Dredd', 0, 16);

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
(11, 'how to use threads?', 'idk ask your mom', 18),
(13, 'Marka threads', '123123123123123123', 20),
(14, 'Do you like Bazooka?', 'I want to know your opinion', 21);

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
(16, 'anceooo', '$2y$10$rod.O/5TzOMes2adPRFYwuAyDkHuGpiEovhgINiALx9nK6kXFd9NG', 0),
(18, 'tomass', '$2y$10$SRjC9ScH73NU79CH4GgPsep3qLk4deRdzTquIcl.hxSOVS.OkJMwG', 0);

-- --------------------------------------------------------

--
-- Table structure for table `video`
--

CREATE TABLE `video` (
  `video_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `song_id` int(11) NOT NULL,
  `instrument_id` int(11) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `like_count` int(11) DEFAULT 0,
  `comment_count` int(11) DEFAULT 0,
  `length` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `video_comment`
--

CREATE TABLE `video_comment` (
  `video_comment_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `like_count` int(11) DEFAULT 0,
  `contains` text NOT NULL,
  `replies_to` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `genreid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `song_genres`
--

CREATE TABLE `song_genres` (
  `songid` int(11) NOT NULL,
  `genreid` int(11) NOT NULL,
  PRIMARY KEY (`songid`, `genreid`),
  KEY `genreid` (`genreid`),
  CONSTRAINT `song_genres_ibfk_1` FOREIGN KEY (`songid`) REFERENCES `songs` (`songid`) ON DELETE CASCADE,
  CONSTRAINT `song_genres_ibfk_2` FOREIGN KEY (`genreid`) REFERENCES `genres` (`genreid`) ON DELETE CASCADE
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

--
-- Indexes for dumped tables
--

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
-- Indexes for table `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`video_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `song_id` (`song_id`),
  ADD KEY `instrument_id` (`instrument_id`);

--
-- Indexes for table `video_comment`
--
ALTER TABLE `video_comment`
  ADD PRIMARY KEY (`video_comment_id`),
  ADD KEY `video_id` (`video_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `replies_to` (`replies_to`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`genreid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `instruments`
--
ALTER TABLE `instruments`
  MODIFY `instrumentid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `notations`
--
ALTER TABLE `notations`
  MODIFY `notationid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `songs`
--
ALTER TABLE `songs`
  MODIFY `songid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `threadcomments`
--
ALTER TABLE `threadcomments`
  MODIFY `commentid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `threads`
--
ALTER TABLE `threads`
  MODIFY `threadid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `video`
--
ALTER TABLE `video`
  MODIFY `video_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `video_comment`
--
ALTER TABLE `video_comment`
  MODIFY `video_comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `genreid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `video`
--
ALTER TABLE `video`
  ADD CONSTRAINT `video_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`userid`),
  ADD CONSTRAINT `video_ibfk_2` FOREIGN KEY (`song_id`) REFERENCES `songs` (`songid`),
  ADD CONSTRAINT `video_ibfk_3` FOREIGN KEY (`instrument_id`) REFERENCES `instruments` (`instrumentid`);

--
-- Constraints for table `video_comment`
--
ALTER TABLE `video_comment`
  ADD CONSTRAINT `video_comment_ibfk_1` FOREIGN KEY (`video_id`) REFERENCES `video` (`video_id`),
  ADD CONSTRAINT `video_comment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`userid`),
  ADD CONSTRAINT `video_comment_ibfk_3` FOREIGN KEY (`replies_to`) REFERENCES `video_comment` (`video_comment_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

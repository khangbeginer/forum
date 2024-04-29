-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2024 at 06:06 PM
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
-- Database: `coursework`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `messageid` int(11) NOT NULL,
  `userid` int(255) NOT NULL,
  `messagesubject` varchar(255) NOT NULL,
  `message` varchar(500) NOT NULL,
  `response` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`messageid`, `userid`, `messagesubject`, `message`, `response`) VALUES
(7, 8, 'about User', 'User\'s Problem', '');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `moduleid` int(255) NOT NULL,
  `modulename` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`moduleid`, `modulename`, `description`) VALUES
(6, 'python 1', 'python is easy lang '),
(7, 'javascript', 'a computer language'),
(8, 'com ga ngon khon', 'com ga kha ngon');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `questionid` int(255) NOT NULL,
  `userid` int(255) DEFAULT NULL,
  `moduleid` int(255) NOT NULL,
  `title` varchar(2000) NOT NULL,
  `content` varchar(2000) NOT NULL,
  `picture_path` varchar(5000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`questionid`, `userid`, `moduleid`, `title`, `content`, `picture_path`) VALUES
(57, 1, 6, 'java (update question)', 'tutorial (update question)', 'question_picture/66268f4392792.jpg'),
(58, 8, 7, 'java, where to start?', 'want to learn', 'question_picture/662744600d966.png'),
(59, 1, 8, 'anh danh gia la kha ngon', 'ngon ', 'question_picture/6629fd618c910.png');

-- --------------------------------------------------------

--
-- Table structure for table `replies`
--

CREATE TABLE `replies` (
  `replyid` int(255) NOT NULL,
  `userid` int(255) NOT NULL,
  `questionid` int(255) NOT NULL,
  `replycontent` varchar(2000) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `replies`
--

INSERT INTO `replies` (`replyid`, `userid`, `questionid`, `replycontent`, `timestamp`) VALUES
(32, 1, 57, 'ngon', '2024-04-22 16:28:35'),
(33, 1, 57, 'pic hoạt động rồi kìa, não mình to :D\r\n', '2024-04-22 16:29:02'),
(34, 8, 58, 'help please\r\n', '2024-04-23 05:17:36'),
(35, 8, 58, 'need help\r\n', '2024-04-23 05:17:44'),
(36, 1, 57, 'aa\r\na\r\na', '2024-04-23 19:28:04'),
(37, 1, 57, 'a\r\na\r\na\r\na\r\na\r\n\r\n', '2024-04-23 19:28:11'),
(38, 1, 59, 'ngon', '2024-04-25 06:51:20');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userid` int(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `role` int(1) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `about` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userid`, `username`, `password`, `profile_picture`, `role`, `email`, `about`) VALUES
(1, 'admin', '$2y$10$A0nqcFFQ6SCwtzCM6zHA0.nU8MLa5bRoZI8zG0R1R2ign9.LsRFLG', NULL, 1, '', ''),
(8, '1', '$2y$10$iscsEwAf.QRIi7/0Vj.SqOR24cWXmqYdaig/3WuVt28pKjpPziJR.', 'img/662691bd7ecef.jpg', NULL, '', ''),
(9, '2', '$2y$10$lWP2EjAsDbuAQdb9r2nkauXgs2UU.gT7v/co93E7UT4/wZ5SPyTNm', NULL, NULL, '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`messageid`),
  ADD KEY `messages_ibfk_1` (`userid`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`moduleid`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`questionid`),
  ADD KEY `questions_ibfk_2` (`moduleid`),
  ADD KEY `questions_ibfk_3` (`userid`);

--
-- Indexes for table `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`replyid`),
  ADD UNIQUE KEY `replyid` (`replyid`),
  ADD KEY `replies_ibfk_1` (`userid`),
  ADD KEY `replies_ibfk_2` (`questionid`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `messageid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `moduleid` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `questionid` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `replies`
--
ALTER TABLE `replies`
  MODIFY `replyid` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userid` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `fk_userid` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `questions_ibfk_2` FOREIGN KEY (`moduleid`) REFERENCES `modules` (`moduleid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `questions_ibfk_3` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `replies`
--
ALTER TABLE `replies`
  ADD CONSTRAINT `replies_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `replies_ibfk_2` FOREIGN KEY (`questionid`) REFERENCES `questions` (`questionid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

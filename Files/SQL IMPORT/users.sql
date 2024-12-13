-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2024 at 09:02 PM
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
-- Database: `users`
--

-- --------------------------------------------------------

--
-- Table structure for table `forums`
--

CREATE TABLE `forums` (
  `forum_id` int(11) NOT NULL,
  `forum_name` varchar(100) NOT NULL,
  `forum_description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forums`
--

INSERT INTO `forums` (`forum_id`, `forum_name`, `forum_description`, `created_at`) VALUES
(1, 'General Discussion', 'A forum for general topics and discussions.', '2024-12-09 23:29:21'),
(2, 'Tech Talk', 'Discuss technology, gadgets, and coding.', '2024-12-09 23:29:21'),
(3, 'Gaming', 'Talk about your favorite games and gaming news.', '2024-12-09 23:29:21'),
(4, 'Test Forum', 'This is a test forum.', '2024-12-09 23:32:07');

-- --------------------------------------------------------

--
-- Table structure for table `forum_messages`
--

CREATE TABLE `forum_messages` (
  `message_id` int(11) NOT NULL,
  `forum_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message_text` text NOT NULL,
  `posted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forum_messages`
--

INSERT INTO `forum_messages` (`message_id`, `forum_id`, `user_id`, `message_text`, `posted_at`) VALUES
(1, 1, 1, 'This is a test message for the Test Forum.', '2024-12-09 23:32:13');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message_text` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `sender_id`, `receiver_id`, `message_text`, `sent_at`) VALUES
(3, 1, 3, 'helo', '2024-12-09 23:26:37'),
(4, 1, 3, 'hello0\r\n', '2024-12-09 23:26:48');

-- --------------------------------------------------------

--
-- Table structure for table `news_links`
--

CREATE TABLE `news_links` (
  `link_id` int(11) NOT NULL,
  `link_title` varchar(255) NOT NULL,
  `link_url` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news_links`
--

INSERT INTO `news_links` (`link_id`, `link_title`, `link_url`, `created_at`) VALUES
(1, 'Example', 'https://www.theguardian.com/technology/2024/dec/09/openai-ai-video-generator-sora-publicly-available', '2024-12-10 00:07:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_nickname` varchar(50) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `created_at`, `user_nickname`, `is_admin`) VALUES
(1, 'chris', 'johndoe@example.com', '$2y$10$e09mARLfGkZEsLAk11Uz0O55WsVHfmWyULA8qfPM7nIPoH.tD63Zy', '2024-12-09 22:59:03', '', 1),
(3, 'csupko', 'chrissupko@gmail.com', '$2y$10$Mn5WyL/9nbrm4MCfemhZ/eFrxLzAYcQYCrOktBH7I4JFG/PuRuZTu', '2024-12-09 23:22:37', '', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `forums`
--
ALTER TABLE `forums`
  ADD PRIMARY KEY (`forum_id`);

--
-- Indexes for table `forum_messages`
--
ALTER TABLE `forum_messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `forum_id` (`forum_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `news_links`
--
ALTER TABLE `news_links`
  ADD PRIMARY KEY (`link_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `forums`
--
ALTER TABLE `forums`
  MODIFY `forum_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `forum_messages`
--
ALTER TABLE `forum_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `news_links`
--
ALTER TABLE `news_links`
  MODIFY `link_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `forum_messages`
--
ALTER TABLE `forum_messages`
  ADD CONSTRAINT `forum_messages_ibfk_1` FOREIGN KEY (`forum_id`) REFERENCES `forums` (`forum_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `forum_messages_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

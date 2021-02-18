-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2019 at 07:53 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jetdb`
--
CREATE DATABASE IF NOT EXISTS `jetdb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `jetdb`;

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `comment_id` int(11) NOT NULL,
  `commenter_id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`comment_id`, `commenter_id`, `stock_id`, `comment`, `created_on`) VALUES
(6, 6, 8, 'Wow this is #amazing', '2019-12-06 18:49:55'),
(7, 9, 8, 'I do not know how I feel about this... #bad', '2019-12-06 18:50:16'),
(8, 8, 8, 'Actually I think it is very #amazing but also #expensive', '2019-12-06 18:51:15'),
(10, 5, 8, 'I will spend my whole saving into this stock! #justdoit', '2019-12-06 18:54:59');

-- --------------------------------------------------------

--
-- Table structure for table `comment_like`
--

CREATE TABLE `comment_like` (
  `like_id` int(11) NOT NULL,
  `liker_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comment_like`
--

INSERT INTO `comment_like` (`like_id`, `liker_id`, `comment_id`) VALUES
(2, 6, 6),
(3, 9, 6),
(4, 8, 8),
(5, 5, 6);

-- --------------------------------------------------------

--
-- Table structure for table `hashtag`
--

CREATE TABLE `hashtag` (
  `stock_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `hashtag_id` int(11) NOT NULL,
  `hashtag_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hashtag`
--

INSERT INTO `hashtag` (`stock_id`, `comment_id`, `hashtag_id`, `hashtag_name`) VALUES
(8, 6, 7, '#amazing'),
(8, 7, 8, '#bad'),
(8, 8, 9, '#amazing'),
(8, 8, 10, '#expensive'),
(8, 10, 12, '#justdoit');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `message_id` int(11) NOT NULL,
  `parent_message_id` int(11) DEFAULT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `timestamp` datetime NOT NULL,
  `seen` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`message_id`, `parent_message_id`, `sender_id`, `receiver_id`, `message`, `timestamp`, `seen`) VALUES
(1, NULL, 8, 6, 'hello a', '2019-11-20 14:52:00', 1),
(2, 1, 6, 8, 'oh hi there', '2019-11-20 15:01:02', 1),
(3, NULL, 8, 6, 'i hate you man', '2019-12-06 18:39:13', 0),
(4, NULL, 8, 6, 'ffhgfhgfhgf', '2019-12-06 19:52:28', 0);

-- --------------------------------------------------------

--
-- Table structure for table `stock_held`
--

CREATE TABLE `stock_held` (
  `stock_held_id` int(11) NOT NULL,
  `stock_id` int(11) DEFAULT NULL,
  `stock_symbol` varchar(15) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `bought_price` decimal(8,2) DEFAULT NULL,
  `sold_price` decimal(8,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stock_held`
--

INSERT INTO `stock_held` (`stock_held_id`, `stock_id`, `stock_symbol`, `user_id`, `bought_price`, `sold_price`, `quantity`) VALUES
(24, 8, 'FB', 1, '194.98', NULL, 12),
(27, 8, 'FB', 6, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stock_info`
--

CREATE TABLE `stock_info` (
  `stock_id` int(11) NOT NULL,
  `stock_symbol` varchar(15) NOT NULL,
  `current_price` decimal(8,2) NOT NULL,
  `day_high` decimal(10,2) DEFAULT NULL,
  `day_low` decimal(10,2) DEFAULT NULL,
  `week_high` decimal(10,2) DEFAULT NULL,
  `week_low` decimal(10,2) DEFAULT NULL,
  `market_cap` bigint(20) DEFAULT NULL,
  `volume` bigint(20) DEFAULT NULL,
  `volume_avg` bigint(20) DEFAULT NULL,
  `shares` bigint(20) DEFAULT NULL,
  `last_trade_time` datetime DEFAULT NULL,
  `pe` varchar(10) DEFAULT NULL,
  `raw_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stock_info`
--

INSERT INTO `stock_info` (`stock_id`, `stock_symbol`, `current_price`, `day_high`, `day_low`, `week_high`, `week_low`, `market_cap`, `volume`, `volume_avg`, `shares`, `last_trade_time`, `pe`, `raw_data`) VALUES
(8, 'FB', '200.93', '201.57', '200.07', '208.66', '123.02', 573002088448, 8193701, 10668716, 2406469888, '2019-12-06 13:48:05', '32.12', '{\"symbols_requested\":1,\"symbols_returned\":1,\"data\":[{\"symbol\":\"FB\",\"name\":\"Facebook, Inc.\",\"currency\":\"USD\",\"price\":\"200.93\",\"price_open\":\"200.50\",\"day_high\":\"201.57\",\"day_low\":\"200.07\",\"52_week_high\":\"208.66\",\"52_week_low\":\"123.02\",\"day_change\":\"1.57\",\"change_pct\":\"0.79\",\"close_yesterday\":\"199.36\",\"market_cap\":\"573002088448\",\"volume\":\"8193701\",\"volume_avg\":\"10668716\",\"shares\":\"2406469888\",\"stock_exchange_long\":\"NASDAQ Stock Exchange\",\"stock_exchange_short\":\"NASDAQ\",\"timezone\":\"EST\",\"timezone_name\":\"America/New_York\",\"gmt_offset\":\"-18000\",\"last_trade_time\":\"2019-12-06 13:48:05\",\"pe\":\"32.12\",\"eps\":\"6.26\"}]}'),
(9, 'aapl', '261.70', '266.08', '260.40', '268.00', '142.00', 1182674583552, 16097467, 22293000, 4519199744, '2019-11-20 14:03:07', '22.01', '{\"symbols_requested\":1,\"symbols_returned\":1,\"data\":[{\"symbol\":\"AAPL\",\"name\":\"Apple Inc.\",\"currency\":\"USD\",\"price\":\"261.70\",\"price_open\":\"265.54\",\"day_high\":\"266.08\",\"day_low\":\"260.40\",\"52_week_high\":\"268.00\",\"52_week_low\":\"142.00\",\"day_change\":\"-4.59\",\"change_pct\":\"-1.72\",\"close_yesterday\":\"266.29\",\"market_cap\":\"1182674583552\",\"volume\":\"16097467\",\"volume_avg\":\"22293000\",\"shares\":\"4519199744\",\"stock_exchange_long\":\"NASDAQ Stock Exchange\",\"stock_exchange_short\":\"NASDAQ\",\"timezone\":\"EST\",\"timezone_name\":\"America/New_York\",\"gmt_offset\":\"-18000\",\"last_trade_time\":\"2019-11-20 14:03:07\",\"pe\":\"22.01\",\"eps\":\"11.89\"}]}'),
(10, 'snap', '14.99', '15.08', '14.81', '18.36', '4.82', 20985550848, 8588399, 17930716, 1138349952, '2019-12-04 14:37:08', 'N/A', '{\"symbols_requested\":1,\"symbols_returned\":1,\"data\":[{\"symbol\":\"SNAP\",\"name\":\"Snap Inc.\",\"currency\":\"USD\",\"price\":\"14.99\",\"price_open\":\"14.99\",\"day_high\":\"15.08\",\"day_low\":\"14.81\",\"52_week_high\":\"18.36\",\"52_week_low\":\"4.82\",\"day_change\":\"0.07\",\"change_pct\":\"0.47\",\"close_yesterday\":\"14.92\",\"market_cap\":\"20985550848\",\"volume\":\"8588399\",\"volume_avg\":\"17930716\",\"shares\":\"1138349952\",\"stock_exchange_long\":\"New York Stock Exchange\",\"stock_exchange_short\":\"NYSE\",\"timezone\":\"EST\",\"timezone_name\":\"America/New_York\",\"gmt_offset\":\"-18000\",\"last_trade_time\":\"2019-12-04 14:37:08\",\"pe\":\"N/A\",\"eps\":\"-0.72\"}]}'),
(11, 'amzn', '1749.00', '1754.37', '1741.05', '2035.80', '1307.00', 867148955648, 1596351, 3017800, 495796992, '2019-12-06 12:33:55', '77.50', '{\"symbols_requested\":1,\"symbols_returned\":1,\"data\":[{\"symbol\":\"AMZN\",\"name\":\"Amazon.com, Inc.\",\"currency\":\"USD\",\"price\":\"1749.00\",\"price_open\":\"1751.20\",\"day_high\":\"1754.37\",\"day_low\":\"1741.05\",\"52_week_high\":\"2035.80\",\"52_week_low\":\"1307.00\",\"day_change\":\"8.52\",\"change_pct\":\"0.49\",\"close_yesterday\":\"1740.48\",\"market_cap\":\"867148955648\",\"volume\":\"1596351\",\"volume_avg\":\"3017800\",\"shares\":\"495796992\",\"stock_exchange_long\":\"NASDAQ Stock Exchange\",\"stock_exchange_short\":\"NASDAQ\",\"timezone\":\"EST\",\"timezone_name\":\"America/New_York\",\"gmt_offset\":\"-18000\",\"last_trade_time\":\"2019-12-06 12:33:55\",\"pe\":\"77.50\",\"eps\":\"22.57\"}]}'),
(12, 'e', '30.36', '30.55', '30.29', '36.34', '28.54', 55389089792, 235363, 284666, 1817090048, '2019-12-06 13:19:06', '8.18', '{\"symbols_requested\":1,\"symbols_returned\":1,\"data\":[{\"symbol\":\"E\",\"name\":\"Eni S.p.A.\",\"currency\":\"USD\",\"price\":\"30.36\",\"price_open\":\"30.27\",\"day_high\":\"30.55\",\"day_low\":\"30.29\",\"52_week_high\":\"36.34\",\"52_week_low\":\"28.54\",\"day_change\":\"0.24\",\"change_pct\":\"0.80\",\"close_yesterday\":\"30.12\",\"market_cap\":\"55389089792\",\"volume\":\"235363\",\"volume_avg\":\"284666\",\"shares\":\"1817090048\",\"stock_exchange_long\":\"New York Stock Exchange\",\"stock_exchange_short\":\"NYSE\",\"timezone\":\"EST\",\"timezone_name\":\"America/New_York\",\"gmt_offset\":\"-18000\",\"last_trade_time\":\"2019-12-06 13:19:06\",\"pe\":\"8.18\",\"eps\":\"3.71\"}]}');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(30) DEFAULT NULL,
  `password_hash` varchar(100) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `login_token` int(11) DEFAULT NULL,
  `biography` text,
  `privacy_flag` tinyint(1) DEFAULT NULL,
  `money` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_name`, `password_hash`, `email`, `login_token`, `biography`, `privacy_flag`, `money`) VALUES
(1, 'test', '', '', 0, '', 0, '0.00'),
(2, 'mrgreen', '$2y$10$FjX4Uqm.n6ggZ/7wV7ASoeJ.JBJG9Gy0ZWxnhFDwpN796.g2NhfVO', 'mrgreen@mail.com', 0, '', 0, '0.00'),
(3, 'mrred', '$2y$10$ziTdH0wY1Fz1Glde.v994.BLdUHSzksAq9Z/z7.opNwtkF2p1lSI6', 'mrred@mail.com', 0, '', 0, '0.00'),
(4, 'mrblue', '$2y$10$d4PSlQ5391St8qCWy6oHhu64s9jhohxJsuRK5QuveRwvQQjtW/pnW', 'mrblue@mail.com', 0, '', 0, '0.00'),
(5, 'private', '$2y$10$DIsVfej35Z2yOgUAwxefcuiRpQIVxUcBfmB7nZFLtxVm4bo4gT1R2', 'private@mail.com', 0, '', 1, '0.00'),
(6, 'a', '$2y$10$bDV2IraSvHBj/u3iV77D5eU1.sk0Tfkz81FHcJShA/ewouKlpML86', 'a@a.com', 0, '', 0, '0.00'),
(7, 'b', '$2y$10$HeaaKn24BhmfGPb6rdFzAO7PY46XH.lGV4dhnFh6GIzEnvfH3emee', 'b@b.com', 0, '', 0, '0.00'),
(8, 'emmanuel', '$2y$10$Vhg6gsZrUzv7mU61mhSq5ugaeF0bXYG6tOmlHIzjRd//JVKMh9Dzm', 'e@e.com', 0, 'hello worldgfhgf', 0, '0.00'),
(9, 'tim', '$2y$10$RLiL1wMLEOYew5lqMdxbbOGqbsckHWF7MWqkbJYXPakRqxSzW2wxu', 'tim@gmail.com', 0, '', 0, '5.00'),
(10, 'timp', '$2y$10$SkhYIEvgOC/xrwW/.wwmAOL7v/AwgyItyrqSClltvRl6DeQM8QvQa', 'timp@gmail.com', 0, '', 1, '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `user_relationship`
--

CREATE TABLE `user_relationship` (
  `user_relationship_id` int(11) NOT NULL,
  `follower_id` int(11) DEFAULT NULL,
  `following_id` int(11) DEFAULT NULL,
  `approved` tinyint(1) DEFAULT NULL,
  `blocked` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_relationship`
--

INSERT INTO `user_relationship` (`user_relationship_id`, `follower_id`, `following_id`, `approved`, `blocked`) VALUES
(61, 1, 5, 0, 0),
(66, 1, 3, 1, 0),
(69, 1, 6, 1, 0),
(70, 1, 6, 1, 0),
(71, 1, 1, 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `comment_stock_info_stock_id_fk` (`stock_id`),
  ADD KEY `comment_user_commenter_id_fk` (`commenter_id`) USING BTREE;

--
-- Indexes for table `comment_like`
--
ALTER TABLE `comment_like`
  ADD PRIMARY KEY (`like_id`),
  ADD KEY `like_user_liker_id_fk` (`liker_id`),
  ADD KEY `like_comment_comment_id_fk` (`comment_id`);

--
-- Indexes for table `hashtag`
--
ALTER TABLE `hashtag`
  ADD PRIMARY KEY (`hashtag_id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `message_user_sender_id_fk` (`sender_id`),
  ADD KEY `message_user_receiver_id_fk` (`receiver_id`),
  ADD KEY `message_parent_message_id` (`parent_message_id`);

--
-- Indexes for table `stock_held`
--
ALTER TABLE `stock_held`
  ADD PRIMARY KEY (`stock_held_id`),
  ADD KEY `stock_held_user_user_id_fk` (`user_id`),
  ADD KEY `stock_held_stock_info_stock_id_fk` (`stock_id`);

--
-- Indexes for table `stock_info`
--
ALTER TABLE `stock_info`
  ADD PRIMARY KEY (`stock_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_relationship`
--
ALTER TABLE `user_relationship`
  ADD PRIMARY KEY (`user_relationship_id`),
  ADD KEY `user_rel_user_follower_id` (`follower_id`),
  ADD KEY `user_rel_user_following_id` (`following_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `comment_like`
--
ALTER TABLE `comment_like`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hashtag`
--
ALTER TABLE `hashtag`
  MODIFY `hashtag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stock_held`
--
ALTER TABLE `stock_held`
  MODIFY `stock_held_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `stock_info`
--
ALTER TABLE `stock_info`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_relationship`
--
ALTER TABLE `user_relationship`
  MODIFY `user_relationship_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_stock_info_stock_id_fk` FOREIGN KEY (`stock_id`) REFERENCES `stock_info` (`stock_id`),
  ADD CONSTRAINT `comment_user_user_id_fk` FOREIGN KEY (`commenter_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `comment_like`
--
ALTER TABLE `comment_like`
  ADD CONSTRAINT `like_comment_comment_id_fk` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`comment_id`),
  ADD CONSTRAINT `like_user_liker_id_fk` FOREIGN KEY (`liker_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_parent_message_id` FOREIGN KEY (`parent_message_id`) REFERENCES `message` (`message_id`),
  ADD CONSTRAINT `message_user_receiver_id_fk` FOREIGN KEY (`receiver_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `message_user_sender_id_fk` FOREIGN KEY (`sender_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `stock_held`
--
ALTER TABLE `stock_held`
  ADD CONSTRAINT `stock_held_stock_info_stock_id_fk` FOREIGN KEY (`stock_id`) REFERENCES `stock_info` (`stock_id`),
  ADD CONSTRAINT `stock_held_user_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `user_relationship`
--
ALTER TABLE `user_relationship`
  ADD CONSTRAINT `user_rel_user_follower_id` FOREIGN KEY (`follower_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `user_rel_user_following_id` FOREIGN KEY (`following_id`) REFERENCES `user` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

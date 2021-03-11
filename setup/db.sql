
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `u947544758_day2day`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` blob NOT NULL,
  `status` enum('new','active','disabled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `created` int(10) NOT NULL COMMENT 'unix epoch time'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `goals` (
  `goal_id` bigint(20) NOT NULL,
  `username` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_added` date NOT NULL DEFAULT current_timestamp(),
  `status` enum('exists','current','deleted') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'exists'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



--
-- Indexes for tables
--

-- Table Users

ALTER TABLE `users`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `email` (`email`);


-- Table Goals

ALTER TABLE `goals`
  ADD PRIMARY KEY (`goal_id`),
  ADD KEY `username` (`username`);

ALTER TABLE `goals`
  MODIFY `goal_id` bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `goals`
  ADD CONSTRAINT `username` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;


--
-- Content of tables
--





COMMIT;
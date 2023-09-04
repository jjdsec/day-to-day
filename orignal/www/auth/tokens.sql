-- requires the users table to be already created

CREATE TABLE `tokens` (
  `token` varchar(256) NOT NULL PRIMARY KEY,
  `username` varchar(32) NOT NULL REFERENCES users(username) ON UPDATE CASCADE ON DELETE CASCADE,
  `access` varchar(64),
  `expiration` int(10) NOT NULL COMMENT 'unix epoch time'
) ENGINE=InnoDB;
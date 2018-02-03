-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:                5.5.59-0+deb8u1 - (Debian)
-- Server OS:                    debian-linux-gnu
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


CREATE DATABASE IF NOT EXISTS `faucet` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `faucet`;

CREATE TABLE IF NOT EXISTS `users` (
  `address` varchar(35) NOT NULL,
  `time` int(11) unsigned NOT NULL,
  `ip` varchar(17) NOT NULL,
  `amount` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

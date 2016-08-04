# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.6.21)
# Database: tango
# Generation Time: 2015-01-11 11:41:36 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table events
# ------------------------------------------------------------

DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events` (
`id` int(11) NOT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `extid` varchar(150) DEFAULT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `slug` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) DEFAULT NULL,
  `place` varchar(255) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `tags` varchar(255) DEFAULT NULL,
  `createdat` datetime DEFAULT NULL,
  `updatedat` datetime DEFAULT NULL,
  `content` text NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `layout` text,
  `showonlist` tinyint(1) NOT NULL DEFAULT '1',
  `file` varchar(255) DEFAULT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `recurrence` varchar(255) DEFAULT NULL,
  `socialnetwork_id` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=297 DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
 ADD PRIMARY KEY (`id`), ADD KEY `category_id` (`category_id`), ADD KEY `parent_id` (`parent_id`), ADD KEY `language_id` (`language_id`), ADD KEY `user_id` (`user_id`), ADD KEY `socialnetwork_id` (`socialnetwork_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `events`
--
ALTER TABLE `events`
ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `events_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `events_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `events` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
ADD CONSTRAINT `events_ibfk_3` FOREIGN KEY (`language_id`) REFERENCES `base_languages` (`id`) ON DELETE SET NULL,
ADD CONSTRAINT `events_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;


# Dump of table events_category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `events_category`;

CREATE TABLE `events_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL,
  `cssclass` varchar(50) NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `events_category` WRITE;
/*!40000 ALTER TABLE `events_category` DISABLE KEYS */;

INSERT INTO `events_category` (`id`, `category`, `visible`)
VALUES
    (1,'Milonga',1),
    (2,'Festival',1),
    (3,'Stage',1),
    (4,'Practice',1),
    (5,'Lesson',1),
    (6,'Show',1);

/*!40000 ALTER TABLE `events_category` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table events_socialnetwork
# ------------------------------------------------------------

DROP TABLE IF EXISTS `events_socialnetwork`;

CREATE TABLE `events_socialnetwork` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `socialnetwork` varchar(50) NOT NULL,
  `start` varchar(50) NOT NULL,
  `end` varchar(50) DEFAULT NULL,
  `code` varchar(150) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `summary` text NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `description` text,
  `location` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `recurrence` varchar(150) DEFAULT NULL,
  `created` varchar(50) NOT NULL,
  `updated` varchar(50) NOT NULL,
  `note` text,
  `photo` varchar(255) DEFAULT NULL,
  `etag` varchar(50) DEFAULT NULL,
  `icaluid` varchar(100) DEFAULT NULL,
  `referencelink` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `events_socialnetwork_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

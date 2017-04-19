# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.13-0ubuntu0.16.04.2)
# Database: prosteer
# Generation Time: 2017-04-19 11:23:19 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table items
# ------------------------------------------------------------

CREATE TABLE `items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) unsigned NOT NULL,
  `item_nr` int(11) unsigned NOT NULL,
  `site_id` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `price` float(10,2) DEFAULT NULL,
  `url` varchar(255) NOT NULL DEFAULT '',
  `code` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_id` (`item_nr`,`site_id`),
  KEY `site` (`site_id`),
  KEY `produ` (`product_id`),
  CONSTRAINT `site` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table products
# ------------------------------------------------------------

CREATE TABLE `products` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `brand` varchar(100) NOT NULL DEFAULT '',
  `model` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `price` float(10,2) NOT NULL,
  `price2` float(10,2) NOT NULL,
  `code` varchar(100) NOT NULL DEFAULT '',
  `code2` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `brand` (`brand`),
  KEY `model` (`model`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table sites
# ------------------------------------------------------------

CREATE TABLE `sites` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(30) NOT NULL DEFAULT '',
  `url` varchar(200) NOT NULL DEFAULT '',
  `sitemap` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table view_compare
# ------------------------------------------------------------

CREATE TABLE `view_compare` (
   `id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
   `title` VARCHAR(255) NOT NULL DEFAULT '',
   `price` FLOAT(10) NOT NULL,
   `item_nr2` INT(11) UNSIGNED NULL DEFAULT NULL,
   `name2` VARCHAR(255) NULL DEFAULT '',
   `price2` FLOAT(10) NULL DEFAULT NULL,
   `item_nr3` INT(11) UNSIGNED NULL DEFAULT NULL,
   `name3` VARCHAR(255) NULL DEFAULT '',
   `price3` FLOAT(10) NULL DEFAULT NULL
) ENGINE=MyISAM;





# Replace placeholder table for view_compare with correct view syntax
# ------------------------------------------------------------

DROP TABLE `view_compare`;

CREATE ALGORITHM=UNDEFINED DEFINER=`prosteer`@`%` SQL SECURITY DEFINER VIEW `view_compare`
AS SELECT
   `t1`.`id` AS `id`,
   `t1`.`title` AS `title`,
   `t1`.`price` AS `price`,
   `t2`.`item_nr` AS `item_nr2`,
   `t2`.`name` AS `name2`,
   `t2`.`price` AS `price2`,
   `t3`.`item_nr` AS `item_nr3`,
   `t3`.`name` AS `name3`,
   `t3`.`price` AS `price3`
FROM ((`products` `t1` left join `items` `t2` on(((`t2`.`site_id` = 2) and (`t1`.`id` = `t2`.`product_id`)))) left join `items` `t3` on(((`t3`.`site_id` = 3) and (`t1`.`id` = `t3`.`product_id`)))) where 1 having (length(`t2`.`item_nr`) or length(`t3`.`item_nr`));

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

/*
SQLyog Ultimate v8.55 
MySQL - 5.6.12-log : Database - cellarea
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`cellarea` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `cellarea`;

/*Table structure for table `cellarea` */

DROP TABLE IF EXISTS `cellarea`;

CREATE TABLE `cellarea` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(15) DEFAULT NULL,
  `cell_id` varchar(50) NOT NULL,
  `town` varchar(15) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `lat` double NOT NULL,
  `long` double NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `cellarea` */

LOCK TABLES `cellarea` WRITE;

UNLOCK TABLES;

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `user` */

LOCK TABLES `user` WRITE;

insert  into `user`(`id`,`name`,`email`,`password`,`active`,`creation_date`) values (3,'Nirav Patel 1','nirav.patel@kraffsoft.com','5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8',1,'2015-11-18 18:14:53');

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*
SQLyog Ultimate v11.24 (32 bit)
MySQL - 5.5.24-log : Database - bms
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`bms` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `bms`;

/*Table structure for table `bms_blog` */

DROP TABLE IF EXISTS `bms_blog`;

CREATE TABLE `bms_blog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `createDate` datetime NOT NULL,
  `modifyDate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `bms_blog` */

insert  into `bms_blog`(`id`,`userId`,`title`,`content`,`createDate`,`modifyDate`) values (1,1,'title1','content1','0000-00-00 00:00:00','0000-00-00 00:00:00'),(2,1,'title2','content2\r\n','0000-00-00 00:00:00','0000-00-00 00:00:00'),(3,1,'title3','content3\r\n','0000-00-00 00:00:00','0000-00-00 00:00:00'),(4,1,'title4','content4\r\n','0000-00-00 00:00:00','0000-00-00 00:00:00'),(5,1,'title5','content5','0000-00-00 00:00:00','0000-00-00 00:00:00'),(6,1,'title6','content6','0000-00-00 00:00:00','0000-00-00 00:00:00'),(7,1,'title7','content7','0000-00-00 00:00:00','0000-00-00 00:00:00'),(8,0,'title8','content8content8content8content8content8content8content8content8content8content8content8content8content8content8content8content8content8content8content8content8content8content8content8content8content8content8content8content8content8content8content8content8content8content8content8content8','0000-00-00 00:00:00','0000-00-00 00:00:00');

/*Table structure for table `bms_comment` */

DROP TABLE IF EXISTS `bms_comment`;

CREATE TABLE `bms_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) DEFAULT NULL,
  `blogId` int(11) DEFAULT NULL,
  `content` text,
  `createDate` datetime DEFAULT NULL,
  `modifyDate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `bms_comment` */

/*Table structure for table `bms_user` */

DROP TABLE IF EXISTS `bms_user`;

CREATE TABLE `bms_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `birthday` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `createDate` datetime DEFAULT NULL,
  `modifyDate` datetime DEFAULT NULL,
  `accessToken` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

/*Data for the table `bms_user` */

insert  into `bms_user`(`id`,`name`,`password`,`token`,`salt`,`avatar`,`gender`,`birthday`,`email`,`phone`,`createDate`,`modifyDate`,`accessToken`) values (1,'admin','f6fdffe48c908deb0f4c3bd36c032e72',NULL,NULL,NULL,'male','2014/07/29','admin@test.com',NULL,NULL,NULL,NULL),(27,'test1','488da249a5f8338d63949188853ca71a','a043151c851f2fa9f4d5c06e7b70abe4',NULL,'','male','2014/07/22','test1@test.com',NULL,NULL,NULL,'e10adc3949ba59abbe56e057f20f883e');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

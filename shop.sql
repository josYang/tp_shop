/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 5.7.11 : Database - shop
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`shop` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `shop`;

/*Table structure for table `shop_access` */

DROP TABLE IF EXISTS `shop_access`;

CREATE TABLE `shop_access` (
  `role_id` smallint(6) unsigned NOT NULL,
  `node_id` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `shop_access` */

/*Table structure for table `shop_admin_user` */

DROP TABLE IF EXISTS `shop_admin_user`;

CREATE TABLE `shop_admin_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理员编号',
  `username` varchar(60) NOT NULL COMMENT '管理员名称',
  `password` varchar(32) NOT NULL COMMENT '管理员密码',
  `add_time` datetime NOT NULL COMMENT '注册时间',
  `last_time` datetime NOT NULL COMMENT '上次登录时间',
  `last_ip` varchar(15) NOT NULL COMMENT '上次登录IP',
  `status` tinyint(1) NOT NULL DEFAULT '2' COMMENT '管理员状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `shop_admin_user` */

insert  into `shop_admin_user`(`id`,`username`,`password`,`add_time`,`last_time`,`last_ip`,`status`) values (1,'admin','0192023a7bbd73250516f069df18b500','2016-03-19 22:02:57','2016-03-22 19:23:28','192.168.1.124',2);

/*Table structure for table `shop_node` */

DROP TABLE IF EXISTS `shop_node`;

CREATE TABLE `shop_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `sort` smallint(6) unsigned DEFAULT NULL,
  `pid` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `shop_node` */

/*Table structure for table `shop_role` */

DROP TABLE IF EXISTS `shop_role`;

CREATE TABLE `shop_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `shop_role` */

/*Table structure for table `shop_role_user` */

DROP TABLE IF EXISTS `shop_role_user`;

CREATE TABLE `shop_role_user` (
  `role_id` mediumint(9) unsigned DEFAULT NULL,
  `user_id` char(32) DEFAULT NULL,
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `shop_role_user` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

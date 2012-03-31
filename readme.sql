/*
后台没做浏览器兼容，我自己用的Chrome
debug开关在index.php中，16行，上线前改为false
文件上传路径在app/config/upload.php配置，上传图片的url是 /admin/up
数据库参数在app/config/database.php配置
控制器下的admin的构造方法中限制后台登陆，默认的是ip和浏览器类型
model下的sync文件顶部定义腾讯接口相关的参数，如果不需要同步到微博就把app/admin.php84行那个if区域删掉
by xiaojing ^_^
*/

/*
SQLyog Enterprise - MySQL GUI v6.16
MySQL - 5.5.8-log : Database - test
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `my_cat` */

DROP TABLE IF EXISTS `my_cat`;

CREATE TABLE `my_cat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `p_count` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `my_cfg` */

DROP TABLE IF EXISTS `my_cfg`;

CREATE TABLE `my_cfg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `k` varchar(100) NOT NULL DEFAULT '',
  `v` text NOT NULL,
  `ctime` int(10) unsigned NOT NULL DEFAULT '0',
  `expire` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `my_cmt` */

DROP TABLE IF EXISTS `my_cmt`;

CREATE TABLE `my_cmt` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(10) unsigned NOT NULL DEFAULT '0',
  `author_name` varchar(100) NOT NULL DEFAULT '',
  `author_email` varchar(100) NOT NULL DEFAULT '',
  `author_url` varchar(100) NOT NULL DEFAULT '',
  `author_ip` varchar(16) NOT NULL DEFAULT '',
  `ctime` int(10) unsigned NOT NULL,
  `content` text NOT NULL,
  `user_agent` varchar(255) NOT NULL DEFAULT '',
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `seat` varchar(20) NOT NULL DEFAULT '00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

/*Table structure for table `my_post` */

DROP TABLE IF EXISTS `my_post`;

CREATE TABLE `my_post` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(60) NOT NULL,
  `excerpt` text NOT NULL,
  `content` text NOT NULL,
  `ctime` int(10) unsigned NOT NULL,
  `mtime` int(10) unsigned NOT NULL DEFAULT '0',
  `passwd` char(20) NOT NULL DEFAULT '',
  `cat_id` int(10) unsigned NOT NULL,
  `cmt_count` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `view_count` int(10) unsigned NOT NULL DEFAULT '0',
  `top_pic` char(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

/*Table structure for table `my_tag` */

DROP TABLE IF EXISTS `my_tag`;

CREATE TABLE `my_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `p_count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM 4 DEFAULT CHARSET=utf8;

/*Table structure for table `my_tag_post` */

DROP TABLE IF EXISTS `my_tag_post`;

CREATE TABLE `my_tag_post` (
  `tid` int(10) unsigned DEFAULT NULL,
  `pid` int(10) unsigned DEFAULT NULL,
  UNIQUE KEY `tid` (`tid`,`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `my_tblog` */

DROP TABLE IF EXISTS `my_tblog`;

CREATE TABLE `my_tblog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cnt` text NOT NULL,
  `cid` int(10) unsigned NOT NULL DEFAULT '0',
  `ctime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;

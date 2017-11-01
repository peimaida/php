/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50631
Source Host           : localhost:3306
Source Database       : backend

Target Server Type    : MYSQL
Target Server Version : 50631
File Encoding         : 65001

Date: 2016-08-09 21:19:39
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `backend_administrator`
-- ----------------------------
DROP TABLE IF EXISTS `backend_administrator`;
CREATE TABLE `backend_administrator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL COMMENT '用户名',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `salt` int(3) DEFAULT NULL COMMENT '盐值(用于加强MD5加密强度)',
  `tel` varchar(11) DEFAULT NULL COMMENT '分机号',
  `status` tinyint(3) DEFAULT NULL COMMENT '状态',
  `last_login_ip` varchar(100) DEFAULT NULL COMMENT '最后登录IP',
  `last_login_time` int(11) DEFAULT 0 COMMENT '最后登录时间',
  `expire_time` int(11) DEFAULT 0 COMMENT '过期时间',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `permission` tinyint(2) NOT NULL COMMENT '管理员权限(0:普通,1:超级)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of backend_administrator
-- ----------------------------
INSERT INTO `backend_administrator` VALUES ('1', 'admin', 'ad283a1894553f047eef9c458edec1de', '677', '13000000000', '1', '0.0.0.0', '1488009415', '1488614215', '1463362516', '1487158557', '1');

-- ----------------------------
-- Table structure for `backend_posts`
-- ----------------------------
DROP TABLE IF EXISTS `backend_posts`;
CREATE TABLE `backend_posts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '发布作者ID',
  `post_admin` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '发布管理员ID',
  `update_admin` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '更新管理员ID',
  `post_content` longtext,
  `post_title` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `tag_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '标签ID',
  `origin_image_name` varchar(255) NOT NULL COMMENT '新闻详情页图片原名',
  `origin_title_image_name` varchar(255) NOT NULL COMMENT '首页新闻图片原名',
  `source_image` varchar(255) NOT NULL COMMENT '新闻详情页图片原图',
  `source_title_image` varchar(255) NOT NULL COMMENT '首页新闻图片原图',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `start_time` int(11) DEFAULT NULL COMMENT '新闻开始时间',
  `end_time` int(11) DEFAULT NULL COMMENT '新闻结束时间',
  `show_flag` tinyint(2) DEFAULT 0 COMMENT '公开标识',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `backend_events`
-- ----------------------------
DROP TABLE IF EXISTS `backend_events`;
CREATE TABLE `backend_events` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `event_author` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '发布作者ID',
  `event_admin` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '发布管理员ID',
  `update_admin` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '更新管理员ID',
  `event_content` longtext,
  `event_title` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `origin_image_name` varchar(255) NOT NULL COMMENT '活动详情页图片原名',
  `origin_title_image_name` varchar(255) NOT NULL COMMENT '首页活动图片原名',
  `source_image` varchar(255) NOT NULL COMMENT '活动详情页图片原图',
  `source_title_image` varchar(255) NOT NULL COMMENT '首页活动图片原图',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `start_time` int(11) DEFAULT NULL COMMENT '活动开始时间',
  `end_time` int(11) DEFAULT NULL COMMENT '活动结束时间',
  `show_flag` tinyint(2) DEFAULT 0 COMMENT '公开标识',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `backend_banners`
-- ----------------------------
DROP TABLE IF EXISTS `backend_banners`;
CREATE TABLE `backend_banners` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `banner_title` text NOT NULL,
  `banner_admin` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '发布管理员ID',
  `update_admin` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '更新管理员ID',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `origin_image_name` varchar(255) DEFAULT NULL COMMENT 'banner图片原名',
  `source_image` varchar(255) NOT NULL COMMENT 'banner原图',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `start_time` int(11) DEFAULT NULL COMMENT 'banner开始时间',
  `end_time` int(11) DEFAULT NULL COMMENT 'banner结束时间',
  `show_flag` tinyint(2) DEFAULT 0 COMMENT '公开标识',
  `url` varchar(255) DEFAULT 0 COMMENT 'banner图片的URL链接',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `backend_author`
-- ----------------------------
DROP TABLE IF EXISTS `backend_author`;
CREATE TABLE `backend_author` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author` varchar(100) NOT NULL COMMENT '作者名',
  `status` tinyint(3) DEFAULT NULL COMMENT '状态',
  `createdby` int(11)NOT NULL COMMENT '创建者',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of backend_author
-- ----------------------------
INSERT INTO `backend_author` VALUES ('1', '米思米中国', '1', '1', '1486665554', '1486665554');

-- ----------------------------
-- Table structure for `backend_tags`
-- ----------------------------
DROP TABLE IF EXISTS `backend_tags`;
CREATE TABLE `backend_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(100) NOT NULL COMMENT '标签名',
  `status` tinyint(3) NOT NULL COMMENT '状态',
  `createdby` int(11) NOT NULL COMMENT '创建者',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of backend_tags
-- ----------------------------
INSERT INTO `backend_tags` VALUES ('1', '通知', '1', '1', '1486665554', '1486665554');
INSERT INTO `backend_tags` VALUES ('2', '公告', '1', '1', '1486665554', '1486665554');

-- ----------------------------
-- Table structure for `backend_maintenance`
-- ----------------------------
DROP TABLE IF EXISTS `backend_maintenance`;
CREATE TABLE `backend_maintenance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_admin_id` int(11) NOT NULL COMMENT '创建管理员ID',
  `update_admin_id` int(11) NOT NULL COMMENT '更新管理员ID',
  `status` tinyint(2) NOT NULL COMMENT '状态',
  `useless` tinyint(2) DEFAULT 0 COMMENT '废弃标识',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `start_time` int(11) DEFAULT 0 COMMENT '维护开始时间',
  `end_time` int(11) DEFAULT 0 COMMENT '维护结束时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `backend_online`
-- ----------------------------
DROP TABLE IF EXISTS `backend_online`;
CREATE TABLE `backend_online` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL COMMENT '管理员ID',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
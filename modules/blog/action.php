<?php

/**
 * @Project NUKEVIET BLOG 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die('Stop!!!');

$sql_drop_module = array();
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_categories`";

$sql_create_module = $sql_drop_module;

// Chuyên mục bài viết
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_categories` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT 'Từ khóa cho máy chủ tìm kiếm',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT 'Mô tả cho máy chủ tìm kiếm',
  `numSubs` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Số danh mục con',
  `numPosts` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Số bài viết',
  `weight` smallint(4) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0: Vô hiệu, 1: Hiệu lực',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`)  
)ENGINE=MyISAM";

// Đăng ký nhận bản tin
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_newsletters` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT 'Email đăng ký',
  `regIP` varchar(20) NOT NULL DEFAULT '' COMMENT 'IP đã đăng ký',
  `regTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian đăng ký',
  `confirmTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian xác nhận',
  `lastSendTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Lần gửi cuối',
  `tokenKey` varchar(255) NOT NULL DEFAULT '' COMMENT 'Khóa xác nhận',
  `numEmail` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Số email đã gửi',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '-1: Chưa xác nhận, 0: Vô hiệu, 1: Hiệu lực',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)  
)ENGINE=MyISAM";

// Cấu hình module
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config` (
  `config_name` varchar(30) NOT NULL,
  `config_value` mediumtext NOT NULL,
  UNIQUE KEY `config_name` (`config_name`)
)ENGINE=MyISAM";

$sql_create_module[] = "INSERT INTO `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config` VALUES
('indexViewType', 'type_blog')";

?>
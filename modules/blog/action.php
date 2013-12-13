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

// Chuyen muc
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_categories` (
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

?>
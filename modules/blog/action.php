<?php

/**
 * @Project NUKEVIET BLOG 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die('Stop!!!');

$sql_drop_module = array();

// Xoa cac bang du lieu
$result = $db->sql_query( "SHOW TABLE STATUS LIKE '" . $db_config['prefix'] . "\_" . $lang . "\_" . $module_data . "\_%'" );
$num_table = intval( $db->sql_numrows( $result ) );
if( $num_table > 0 )
{
	// Xoa cac bang HTML
	list( $maxid ) = $db->sql_fetchrow( $db->sql_query( "SELECT MAX(`id`) FROM `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows`" ) );
	$i1 = 1;
	while( $i1 <= $maxid )
	{
		$tb = ceil( $i1 / 4000 );
		$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_data_" . $tb . "`";	
		$i1 = $i1 + 4000;
	}
}

$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_categories`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_newsletters`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_tags`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config`";

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

// Tags
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_tags` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID tags',
  `title` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT 'Từ khóa cho máy chủ tìm kiếm',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT 'Mô tả cho máy chủ tìm kiếm',
  `numPosts` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Số bài viết',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`)
)ENGINE=MyISAM";

// Bài viết
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID bài viết',
  `postid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Người đăng',
  `siteTitle` varchar(255) NOT NULL DEFAULT '' COMMENT 'Tiêu đề của trang, mặc định là tiêu đề bài viết',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT 'Tên bài viết',
  `alias` varchar(255) NOT NULL DEFAULT '' COMMENT 'Liên kết tĩnh',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT 'Từ khóa cho máy chủ tìm kiếm',
  `images` varchar(255) NOT NULL DEFAULT '' COMMENT 'Hình ảnh bài viết',
  `mediaType` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '0: Dùng ảnh đại diện, 1: Dùng hình ảnh tùy chọn, 2: File âm thanh, 3: File video, 4: Iframe',
  `mediaHeight` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Chiều cao media',
  `mediaValue` mediumtext NOT NULL COMMENT 'Nội dung media',
  `hometext` mediumtext NOT NULL COMMENT 'Mô tả ngắn gọn',
  `bodytext` mediumtext NOT NULL COMMENT 'Nội dung bài viết dạng text',
  `postType` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '0: Bình thường, 1: Ảnh, 2: Video, 3: Audio, 4: Ghi chú, 5: Liên kết, 6: Thư viện',
  `catids` varchar(255) NOT NULL DEFAULT '' COMMENT 'Chuyên mục',
  `tagids` varchar(255) NOT NULL DEFAULT '' COMMENT 'Tags',
  `numWords` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Số từ',
  `numViews` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Lượt xem',
  `numComments` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Số bình luận',
  `numVotes` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt đánh giá',
  `voteTotal` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Tổng số điểm',
  `voteDetail` varchar(255) NOT NULL DEFAULT '' COMMENT 'Chi tiết đánh giá',
  `postTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian viết',
  `updateTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian sửa gần nhất',
  `pubTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian xuất bản',
  `expTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian hết hạn',
  `expMode` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Kiểu xử lý tự động khi hết hạn: 0: Ngưng hoạt động, 1: Cho thành hết hạn, 2: Xóa',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '-2: Nháp, -1: Chờ đăng, 0: Tạm ngưng, 1: Hoạt động, 2: Hết hạn',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`),
  KEY `postid` (`postid`)
)ENGINE=MyISAM";

// Dữ liệu html
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_data_1` (
	`id` mediumint(8) unsigned NOT NULL COMMENT 'ID bài viết', 
	`bodyhtml` longtext NOT NULL COMMENT 'Nội dung HTML của bài viết',
	PRIMARY KEY (`id`)
)ENGINE=MyISAM";

// Cấu hình module
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config` (
  `config_name` varchar(30) NOT NULL,
  `config_value` mediumtext NOT NULL,
  UNIQUE KEY `config_name` (`config_name`)
)ENGINE=MyISAM";

$sql_create_module[] = "INSERT INTO `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config` VALUES
('indexViewType', 'type_blog'),

('initPostExp', '0'),
('initPostType', '0'),
('initMediaType', '4'),
('initMediaHeight', '250'),

('folderStructure', 'Ym')
";

?>
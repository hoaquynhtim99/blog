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
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_send`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_tags`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config`";

// Xoa cron cua module
$result = $db->sql_query( "SELECT `id`, `params` FROM `" . NV_CRONJOBS_GLOBALTABLE . "` ORDER BY `id` DESC" );
$is_auto = 0;
while( list( $id, $params ) = $db->sql_fetchrow( $result ) )
{
    $params = ( ! empty( $params ) ) ? array_map( "trim", explode( ",", $params ) ) : array( "", 0 );
    if( $params[0] == $lang . "_" . $module_data )
    {
        $is_auto = $id;
        break;
    }
}

if( $is_auto )
{
    $db->sql_query( "DELETE FROM `" . NV_CRONJOBS_GLOBALTABLE . "` WHERE `id`=" . $is_auto );
}

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

// Dữ liệu gửi email newsletters
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_send` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'ID bài viết',
  `startTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian bắt đầu gửi',
  `endTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian kết thúc gửi',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: Chờ gửi, 1: Đang gửi, 2: Đã gửi',
  `round` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Gửi lần mấy 1, 2, 3, 4...',
  `lastID` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'ID email đăng ký nhận tin lần cuối cùng gửi',
  `resendData` mediumtext NOT NULL COMMENT 'Danh sách ID đăng ký nhận tin chờ gửi lại',
  `errorData` mediumtext NOT NULL COMMENT 'Danh sách ID đăng ký nhận tin gửi bị lỗi cho đến thời điểm hiện tại',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)  
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
  `postGoogleID` varchar(30) NOT NULL DEFAULT '' COMMENT 'Google Author',
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
  `fullPage` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Nếu là 1, đối với kiểu hiển thị dạng blog, sẽ show toàn bộ nội dung bài viết',
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
('catViewType', 'type_blog'),
('tagsViewType', 'type_blog'),
('numPostPerPage', '10'),
('nextExecuteTime', '0'),
('numberResendNewsletter', '1'),
('strCutHomeText', '150'),
('numSearchResult', '20'),

('iconClass0', 'icon-pencil'),
('iconClass1', 'icon-picture'),
('iconClass2', 'icon-film'),
('iconClass3', 'icon-music'),
('iconClass4', 'icon-quote-left'),
('iconClass5', 'icon-link'),
('iconClass6', 'icon-camera'),

('blockTagsShowType', 'random'),
('blockTagsNums', '10'),
('blockTagsCacheIfRandom', '1'),
('blockTagsCacheLive', '5'),

('initPostExp', '0'),
('initPostType', '0'),
('initMediaType', '4'),
('initMediaHeight', '250'),
('initNewsletters', '1'),

('sysDismissAdminCache', '0'),
('sysHighlightTheme', 'default'),
('sysGoogleAuthor', ''),
('sysFbAppID', ''),
('sysRedirect2Home', '0'),

('folderStructure', 'Ym')
";

// Them cron cua module
$result = $db->sql_query( "SHOW COLUMNS FROM `" . NV_CRONJOBS_GLOBALTABLE . "`" );

$list_field = array();
$list_value = array();

while( $row = $db->sql_fetch_assoc( $result ) )
{
	if( preg_match( "/^([a-zA-Z0-9]{2})\_cron_name$/", $row['Field'] ) )
	{
		$list_field[] = $row['Field'];
		$list_value[] = "'Cron Blog Newsletters'";
	}
}

$list_field = ", `" . implode( "`, `", $list_field ) . "`";
$list_value = ", " . implode( ", ", $list_value );

$sql_create_module[] = "INSERT INTO `" . NV_CRONJOBS_GLOBALTABLE . "` (
	`id`, 
	`start_time`, 
	`interval`, 
	`run_file`, 
	`run_func`, 
	`params`, 
	`del`, 
	`is_sys`, 
	`act`, 
	`last_time`, 
	`last_result` " . $list_field . "
) VALUES (
	NULL, 
	" . NV_CURRENTTIME . ", 
	5, 
	'module_blog_newsletters.php', 
	'cron_blog_newsletters', 
	'" . $lang . "_" . $module_data . ", 5', 
	0, 
	0, 
	1, 
	" . NV_CURRENTTIME . ", 
	1 " . $list_value . "
)";

?>
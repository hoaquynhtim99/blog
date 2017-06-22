<?php

/**
 * @Project NUKEVIET BLOG 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if (!defined('NV_IS_FILE_MODULES'))
    die('Stop!!!');

$sql_drop_module = array();

// Xoa cac bang du lieu
$result = $db->query("SHOW TABLE STATUS LIKE '" . $db_config['prefix'] . "\_" . $lang . "\_" . $module_data . "\_categories'");
$num_table = sizeof($result->fetchAll());
if ($num_table > 0) {
    // Xoa cac bang HTML
    $maxid = $db->query("SELECT MAX(id) FROM " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows")->fetchColumn();

    if ($maxid == 0) {
        $maxid = 1;
    }

    $i1 = 1;
    while ($i1 <= $maxid) {
        $tb = ceil($i1 / 4000);
        $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_data_" . $tb;
        $i1 = $i1 + 4000;
    }
}

$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_categories";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_newsletters";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_send";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_tags";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config";

// Xoa cron cua module
$result = $db->query("SELECT id, params FROM " . NV_CRONJOBS_GLOBALTABLE . " ORDER BY id DESC");
$is_auto = 0;
while (list($id, $params) = $result->fetch(3)) {
    $params = (!empty($params)) ? array_map("trim", explode(",", $params)) : array("", 0);
    if ($params[0] == $lang . "_" . $module_data) {
        $is_auto = $id;
        break;
    }
}

if ($is_auto) {
    $db->query("DELETE FROM " . NV_CRONJOBS_GLOBALTABLE . " WHERE id=" . $is_auto);
}

$sql_create_module = $sql_drop_module;

// Chuyên mục bài viết
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_categories (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  parentid mediumint(8) unsigned NOT NULL DEFAULT '0',
  title varchar(250) NOT NULL DEFAULT '',
  alias varchar(250) NOT NULL DEFAULT '',
  keywords varchar(255) NOT NULL DEFAULT '' COMMENT 'Từ khóa cho máy chủ tìm kiếm',
  description varchar(255) NOT NULL DEFAULT '' COMMENT 'Mô tả cho máy chủ tìm kiếm',
  numsubs smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Số danh mục con',
  numposts smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Số bài viết',
  weight smallint(4) unsigned NOT NULL DEFAULT '0',
  status tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0: Vô hiệu, 1: Hiệu lực',
  PRIMARY KEY (id),
  UNIQUE KEY alias (alias)  
)ENGINE=MyISAM";

// Đăng ký nhận bản tin
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_newsletters (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  email varchar(250) NOT NULL DEFAULT '' COMMENT 'Email đăng ký',
  regip varchar(20) NOT NULL DEFAULT '' COMMENT 'IP đã đăng ký',
  regtime int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian đăng ký',
  confirmtime int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian xác nhận',
  lastsendtime int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Lần gửi cuối',
  tokenkey varchar(255) NOT NULL DEFAULT '' COMMENT 'Khóa xác nhận',
  numemail mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Số email đã gửi',
  status tinyint(1) NOT NULL DEFAULT '0' COMMENT '-1: Chưa xác nhận, 0: Vô hiệu, 1: Hiệu lực',
  PRIMARY KEY (id),
  UNIQUE KEY email (email)  
)ENGINE=MyISAM";

// Dữ liệu gửi email newsletters
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_send (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  pid mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'ID bài viết',
  starttime int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian bắt đầu gửi',
  endtime int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian kết thúc gửi',
  status tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: Chờ gửi, 1: Đang gửi, 2: Đã gửi',
  round smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Gửi lần mấy 1, 2, 3, 4...',
  lastid mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'ID email đăng ký nhận tin lần cuối cùng gửi',
  resenddata mediumtext NOT NULL COMMENT 'Danh sách ID đăng ký nhận tin chờ gửi lại',
  errordata mediumtext NOT NULL COMMENT 'Danh sách ID đăng ký nhận tin gửi bị lỗi cho đến thời điểm hiện tại',
  PRIMARY KEY (id),
  KEY pid (pid)  
)ENGINE=MyISAM";

// Tags
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_tags (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID tags',
  title varchar(250) NOT NULL DEFAULT '',
  alias varchar(250) NOT NULL DEFAULT '',
  keywords varchar(255) NOT NULL DEFAULT '' COMMENT 'Từ khóa cho máy chủ tìm kiếm',
  description varchar(255) NOT NULL DEFAULT '' COMMENT 'Mô tả cho máy chủ tìm kiếm',
  numposts smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Số bài viết',
  PRIMARY KEY (id),
  UNIQUE KEY alias (alias)
)ENGINE=MyISAM";

// Bài viết
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID bài viết',
  postid mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Người đăng',
  postgoogleid varchar(30) NOT NULL DEFAULT '' COMMENT 'Google Author',
  sitetitle varchar(255) NOT NULL DEFAULT '' COMMENT 'Tiêu đề của trang, mặc định là tiêu đề bài viết',
  title varchar(250) NOT NULL DEFAULT '' COMMENT 'Tên bài viết',
  alias varchar(250) NOT NULL DEFAULT '' COMMENT 'Liên kết tĩnh',
  keywords varchar(255) NOT NULL DEFAULT '' COMMENT 'Từ khóa cho máy chủ tìm kiếm',
  images varchar(255) NOT NULL DEFAULT '' COMMENT 'Hình ảnh bài viết',
  mediatype smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '0: Dùng ảnh đại diện, 1: Dùng hình ảnh tùy chọn, 2: File âm thanh, 3: File video, 4: Iframe',
  mediaheight smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Chiều cao media',
  mediawidth smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Chiều rộng media',
  mediaresponsive tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Responsive hay không',
  mediavalue mediumtext NOT NULL COMMENT 'Nội dung media',
  hometext mediumtext NOT NULL COMMENT 'Mô tả ngắn gọn',
  bodytext mediumtext NOT NULL COMMENT 'Nội dung bài viết dạng text',
  posttype smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '0: Bình thường, 1: Ảnh, 2: Video, 3: Audio, 4: Ghi chú, 5: Liên kết, 6: Thư viện',
  fullpage tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Nếu là 1, đối với kiểu hiển thị dạng blog, sẽ show toàn bộ nội dung bài viết',
  inhome tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Hiển thị bài viết trên trang chủ',
  catids varchar(255) NOT NULL DEFAULT '' COMMENT 'Chuyên mục',
  tagids varchar(255) NOT NULL DEFAULT '' COMMENT 'Tags',
  numwords mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Số từ',
  numviews mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Lượt xem',
  numcomments mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Số bình luận',
  numvotes mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt đánh giá',
  votetotal mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Tổng số điểm',
  votedetail varchar(255) NOT NULL DEFAULT '' COMMENT 'Chi tiết đánh giá',
  posttime int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian viết',
  updatetime int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian sửa gần nhất',
  pubtime int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian xuất bản',
  exptime int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian hết hạn',
  expmode smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Kiểu xử lý tự động khi hết hạn: 0: Ngưng hoạt động, 1: Cho thành hết hạn, 2: Xóa',
  status tinyint(1) NOT NULL DEFAULT '0' COMMENT '-2: Nháp, -1: Chờ đăng, 0: Tạm ngưng, 1: Hoạt động, 2: Hết hạn',
  PRIMARY KEY (id),
  UNIQUE KEY alias (alias),
  KEY postid (postid)
)ENGINE=MyISAM";

// Dữ liệu html
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_data_1 (
	id mediumint(8) unsigned NOT NULL COMMENT 'ID bài viết', 
	bodyhtml longtext NOT NULL COMMENT 'Nội dung HTML của bài viết',
	PRIMARY KEY (id)
)ENGINE=MyISAM";

// Cấu hình module
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config (
  config_name varchar(30) NOT NULL,
  config_value mediumtext NOT NULL,
  UNIQUE KEY config_name (config_name)
)ENGINE=MyISAM";

$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config VALUES
('indexViewType', 'type_blog'),
('catViewType', 'type_blog'),
('tagsViewType', 'type_blog'),
('numPostPerPage', '10'),
('nextExecuteTime', '0'),
('numberResendNewsletter', '1'),
('strCutHomeText', '150'),
('numSearchResult', '20'),

('iconClass0', 'fa fa-pencil'),
('iconClass1', 'fa fa-picture-o'),
('iconClass2', 'fa fa-film'),
('iconClass3', 'fa fa-music'),
('iconClass4', 'fa fa-quote-left'),
('iconClass5', 'fa fa-link'),
('iconClass6', 'fa fa-camera'),

('blockTagsShowType', 'random'),
('blockTagsNums', '10'),
('blockTagsCacheIfRandom', '1'),
('blockTagsCacheLive', '5'),

('commentType', 'sys'),
('commentPerPage', '8'),
('commentDisqusShortname', ''),
('commentFacebookColorscheme', 'light'),

('initPostExp', '0'),
('initPostType', '0'),
('initMediaType', '4'),
('initMediaHeight', '250'),
('initMediaWidth', '960'),
('initMediaResponsive', '1'),
('initNewsletters', '1'),
('initAutoKeywords', '1'),

('sysDismissAdminCache', '0'),
('sysHighlightTheme', 'default'),
('sysGoogleAuthor', ''),
('sysFbAppID', ''),
('sysFbAdminID', ''),
('sysRedirect2Home', '0'),
('sysLocale', 'vi_VN'),
('sysDefaultImage', ''),

('folderStructure', 'Ym')
";

// Them cron cua module
$result = $db->query("SHOW COLUMNS FROM " . NV_CRONJOBS_GLOBALTABLE);

$list_field = array();
$list_value = array();

while ($row = $result->fetch()) {
    if (preg_match("/^([a-zA-Z0-9]{2})\_cron_name$/", $row['field'])) {
        $list_field[] = $row['field'];
        $list_value[] = "'Cron Blog Newsletters'";
    }
}

$list_field = ", " . implode(", ", $list_field);
$list_value = ", " . implode(", ", $list_value);

$sql_create_module[] = "INSERT INTO " . NV_CRONJOBS_GLOBALTABLE . " (
	id, 
	start_time, 
	inter_val, 
	run_file, 
	run_func, 
	params, 
	del, 
	is_sys, 
	act, 
	last_time, 
	last_result " . $list_field . "
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

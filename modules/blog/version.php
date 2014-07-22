<?php

/**
 * @Project NUKEVIET BLOG 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$module_version = array(
	"name" => "NukeViet Blog",
	"modfuncs" => "main, tags, viewcat, search, detail, newsletters",
	"submenu" => "main, tags, viewcat, search, detail, newsletters",
	"is_sysmod" => 0,
	"virtual" => 1,
	"version" => "3.4.01",
	"date" => "Wed, 11 Dec 2013 00:00:00 GMT",
	"author" => "PHAN TAN DUNG (phantandung92@gmail.com)",
	"note" => "Module Blog For Personal Blog Website",
	"uploads_dir" => array(
		$module_name,
		$module_name . "/images",
		$module_name . "/thumb"
	)
);

?>
<?php

/**
 * @Project NUKEVIET BLOG 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! defined( 'NV_IS_CRON' ) ) die( 'Stop!!!' );

function cron_blog_newsletters( $table_data, $num_onesend )
{
	global $db, $global_config, $db_config;
	
	$is_complete = true;
	
	return $is_complete;
}

?>
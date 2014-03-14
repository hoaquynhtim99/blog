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
	
	$num_onesend = empty( $num_onesend ) ? 5 : intval( $num_onesend );
	
	if( ! empty( $table_data ) )
	{
		$table_data = explode( "_", $table_data );
		$table_data = array_map( "trim", $table_data );
		
		$lang = $table_data[0];
		unset( $table_data[0] );
		$module_data = implode( "_", $table_data );
		
		// Lay module file, module name
		$sql = "SELECT `title`, `module_file` FROM `" . NV_MODULES_TABLE . "` WHERE `module_data`=" . $db->dbescape( $module_data );
		$result = $db->sql_query( $sql );
		list( $module_name, $module_file ) = $db->sql_fetchrow( $result );
		
		$table_data = $db_config['prefix'] . "_" . $lang ."_" . $module_data;
		
		// Lay cau hinh gui mail
		$sql = "SELECT `config_value` FROM `" . $table_data . "_config` WHERE `config_name`=" . $db->dbescape('numberResendNewsletter');
		$result = $db->sql_query( $sql );
		list( $numberResendNewsletter ) = $db->sql_fetchrow( $result );
		
		// Lay tin can gui (uu tien cac tin round 1 hon)
		$sql = "SELECT a.*, b.title, b.alias, b.hometext FROM `" . $table_data . "_send` AS a INNER JOIN `" . $table_data . "_rows` AS b ON a.pid=b.id WHERE a.status!=2 AND b.status=1 ORDER BY a.status DESC, a.id ASC LIMIT 0,1";
		$result = $db->sql_query( $sql );
		
		if( $db->sql_numrows( $result ) )
		{
			// Du lieu gui
			$data_send = $db->sql_fetch_assoc( $result );
			
			$data_send['lastID'] = intval( $data_send['lastID'] );
			$data_send['link'] = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . $lang . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $data_send['alias'];
			$data_send['resendData'] = explode( ",", $data_send['resendData'] );
			$data_send['errorData'] = explode( ",", $data_send['errorData'] );
			
			// Danh dau la dang gui
			if( empty( $data_send['status'] ) )
			{
				$db->sql_query( "UPDATE `" . $table_data . "_send` SET `status`=1, `startTime`=" . NV_CURRENTTIME . ", `round`=1 WHERE `id`=" . $data_send['id'] );
			}
		
			// Goi ngon ngu
			include( NV_ROOTDIR . "/modules/" . $module_file . "/language/" . $lang . ".php" );
			
			// Lay nguoi gui lan gui 1
			if( $data_send['round'] <= 1 )
			{
				$sql = "SELECT `id`, `email` FROM `" . $table_data . "_newsletters` WHERE `status`=1 AND `id`>" . $data_send['lastID'] . " ORDER BY `id` ASC LIMIT 0," . $num_onesend;
			}
			// Nguoi gui o cac lan gui tiep theo
			else
			{
				$sql = "SELECT `id`, `email` FROM `" . $table_data . "_newsletters` WHERE `status`=1 AND `id`>" . $data_send['lastID'] . " AND `id` IN(" . implode( ",", $data_send['resendData'] ) . ") ORDER BY `id` ASC LIMIT 0," . $num_onesend;
			}
			$result = $db->sql_query( $sql );
			$num = $db->sql_numrows( $result );
			
			while( $row = $db->sql_fetchrow( $result ) )
			{
				if( nv_sendmail( array( $global_config['site_name'], $global_config['site_email'] ), $row['email'], nv_unhtmlspecialchars( $data_send['title'] ), $data_send['hometext'] . "<br /><br />" . $lang_module['newsletterMessage'] . ": <a href=\"" . $data_send['link'] . "\">" . $data_send['link'] . "</a>" ) )
				{
					// Cap nhat lan gui cuoi va so email da gui
					$db->sql_query( "UPDATE `" . $table_data . "_newsletters` SET `numEmail`=`numEmail`+1, `lastSendTime`=" . NV_CURRENTTIME . " WHERE `id`=" . $row['id'] );
				}
				else
				{
					// Danh dau cac email bi loi trong lan gui nay
					$data_send['errorData'][$row['id']] = $row['id'];
				}
				
				$data_send['lastID'] = $row['id'];
			}
			
			// Cap nhat lai thong tin
			$sql = array();
			
			$sql[] = "`lastID`=" . $data_send['lastID'];
			
			if( $num < $num_onesend )
			{
				// Tang so lan gui len 1
				$data_send['round'] = $data_send['round'] + 1;
				
				// Danh dau da gui xong neu qua so lan gui hoac khong co email loi
				if( $data_send['round'] > $numberResendNewsletter or empty( $data_send['errorData'] ) )
				{
					$sql[] = "`status`=2";
					$sql[] = "`endTime`=" . NV_CURRENTTIME;
				}
				// Gui chua xong thi moi tang va ghi vào CSDL
				else
				{
					$sql[] = "`round`=" . $data_send['round'];
					
					// Du lieu gui lan tiep theo la du lieu loi cua lan nay
					$sql[] = "`resendData`=" . $db->dbescape( implode( ",", $data_send['errorData'] ) );
					$sql[] = "`errorData`=''";
				}
			}
			else
			{
				// Ghi lai du lieu loi
				$sql[] = "`errorData`=" . $db->dbescape( $data_send['errorData'] ? implode( ",", $data_send['errorData'] ) : '' );
			}
			
			$db->sql_query( "UPDATE `" . $table_data . "_send` SET " . implode( ", ", $sql ) . " WHERE `id`=" . $data_send['id'] );
		}
	}
	
	return true;
}

?>
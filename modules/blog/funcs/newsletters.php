<?php

/**
 * @Project NUKEVIET BLOG 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if ( ! defined( 'NV_IS_MOD_BLOG' ) ) die( 'Stop!!!' );

// Dang ky nhan ban tin
if( $nv_Request->isset_request( 'newsletters', 'post' ) )
{
	$array['email'] = filter_text_input( 'newsletters', 'post', '', 1, 255 );
	$array['checksess'] = filter_text_input( 'checksess', 'post', '', 1, 255 );
	
	if( empty( $array['email'] ) or empty( $array['checksess'] ) or $array['checksess'] != md5( $global_config['sitekey'] . $client_info['session_id'] ) )
	{
		die('Error Access!!!');
	}
	
	// Kiem tra email hop le
	$checkEmail = nv_check_valid_email( $array['email'] );
	if( $checkEmail != '' )
	{
		die( $checkEmail );
	}
	
	// Kiem tra email da dang ky
	$sql = "SELECT * FROM `" . $BL->table_prefix . "_newsletters` WHERE `email`=" . $db->dbescape( $array['email'] );
	$result = $db->sql_query( $sql );
	
	if( $db->sql_numrows( $result ) )
	{
		$row = $db->sql_fetch_assoc( $result );
		
		if( $row['status'] == 0 )
		{
			die( sprintf( $BL->lang('newsletterIsBan'), $array['email'] ) );
		}
		elseif( $row['status'] == 1 )
		{
			die( sprintf( $BL->lang('newsletterIsActive'), $array['email'] ) );
		}
		else
		{
			if( ! $db->sql_query( "DELETE FROM `" . $BL->table_prefix . "_newsletters` WHERE `email`=" . $db->dbescape( $array['email'] ) ) )
			{
				die('Unknow Error!!!');
			}
		}
	}
	
	// Khoa unique
	$tokenKey = md5( $array['checksess'] . $array['email'] );
	
	$linkConfirm = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;confirm=" . $tokenKey;
	$linkCancel = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;cancel=" . $tokenKey;
	
	$mailContent = sprintf( $BL->lang('newsletterMailConfirm'), $linkConfirm, $linkConfirm, $linkCancel, $linkCancel, $global_config['site_name'] );
	
	if( nv_sendmail( array( $global_config['site_name'], $global_config['site_email'] ), $array['email'], $BL->lang('newsletterMailSubject'), $mailContent ) )
	{
		if( $db->sql_query( "INSERT INTO `" . $BL->table_prefix . "_newsletters` (`id`, `email`, `regIP`, `regTime`, `tokenKey`, `status`) VALUES(NULL, " . $db->dbescape( $array['email'] ) . ", " . $db->dbescape( $client_info['ip'] ) . ", " . NV_CURRENTTIME . ", " . $db->dbescape( $tokenKey ) . ", -1)" ) )
		{
			die( sprintf( $BL->lang('newsletterMailOk'), $array['email'] ) );
		}
		
		die("Unknow Error!!!");
	}
	else
	{
		die( $BL->lang('newsletterMailError') );
	}
}

$page_title = $mod_title = $BL->lang('newsletter');

// Khoi tao
$array = array(
	'status' => 0, // 0: ok, 1: error
	'message' => "",
);

// Xac thuc hoac huy dang ky nhan tin
if( $nv_Request->isset_request( "confirm", "get" ) or $nv_Request->isset_request( "cancel", "get" ) )
{
	$confirm = filter_text_input( 'confirm', 'get', '', 1, 255 );
	$cancel = filter_text_input( 'cancel', 'get', '', 1, 255 );
	$tokenKey = $confirm ? $confirm : $cancel;
	
	if( empty( $tokenKey ) or ! preg_match( "/^[a-z0-9]{32}$/", $tokenKey ) )
	{
		$array['status'] = 1;
		$array['message'] = $BL->lang('newsletterConfirmErrorVar');
	}
	else
	{
		$sql = "SELECT * FROM `" . $BL->table_prefix . "_newsletters` WHERE `tokenKey`=" . $db->dbescape( $tokenKey );
		$result = $db->sql_query( $sql );
		
		if( $db->sql_numrows( $result ) )
		{
			$row = $db->sql_fetch_assoc( $result );
			
			// Huy dang ky nhan tin
			if( ! empty( $cancel ) )
			{
				$db->sql_query( "DELETE FROM `" . $BL->table_prefix . "_newsletters` WHERE `id`=" . $row['id'] );
				
				$array['status'] = 0;
				$array['message'] = sprintf( $BL->lang('newsletterCancelComplete'), $row['email'] );
			}
			// Xac nhan dang ky
			else
			{
				// Dang bi cam
				if( $row['status'] == 0 )
				{
					$array['status'] = 1;
					$array['message'] = sprintf( $BL->lang('newsletterIsBan'), $row['email'] );
				}
				else
				{
					// Xoa tokenKey
					$db->sql_query( "UPDATE `" . $BL->table_prefix . "_newsletters` SET `tokenKey`='' WHERE `id`=" . $row['id'] );
					
					// Kich hoat
					if( $row['status'] == -1 )
					{
						$db->sql_query( "UPDATE `" . $BL->table_prefix . "_newsletters` SET `confirmTime`=" . NV_CURRENTTIME . ", `status`=1 WHERE `id`=" . $row['id'] );
					}
					
					$array['status'] = 0;
					$array['message'] = sprintf( $BL->lang('newsletterConfirmComplete'), $row['email'] );
				}
			}
		}
		else
		{
			$array['status'] = 1;
			$array['message'] = $BL->lang('newsletterConfirmErrorVar');
		}
	}
}
else
{
	Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
	exit();
}

$contents = nv_newsletters_theme( $array );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>
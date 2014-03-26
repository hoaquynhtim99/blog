<?php

/**
 * @Project NUKEVIET BLOG 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if ( ! defined( 'NV_IS_MOD_BLOG' ) ) die( 'Stop!!!' );

$page_title = $mod_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$description = $module_info['description'];

// Xac dinh thong tin phan trang
$per_page = intval( $BL->setting['numPostPerPage'] );

// SQL co ban
$sql = "FROM `" . $BL->table_prefix . "_rows` WHERE `status`=1";
$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;

// Lay so row
$sql1 = "SELECT COUNT(*) " . $sql;
$result1 = $db->sql_query( $sql1 );
list( $all_page ) = $db->sql_fetchrow( $result1 );

// Lay du lieu
$sql = "SELECT * " . $sql . " ORDER BY `pubTime` DESC LIMIT " . ( ( $page - 1 ) * $per_page ) . ", " . $per_page;
$result = $db->sql_query( $sql );

$array = $array_userids = array();

while( $row = $db->sql_fetch_assoc( $result ) )
{
	$row['mediaType'] = intval( $row['mediaType'] );
	$row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'];
	$row['postName'] = '';
	
	// Xac dinh media
	if( $row['mediaType'] == 0 )
	{
		$row['mediaValue'] = $row['images'];
	}
	
	if( ! empty( $row['mediaValue'] ) )
	{
		if( is_file( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $row['mediaValue'] ) )
		{
			$row['mediaValue'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $row['mediaValue'];
		}
		elseif( ! nv_is_url( $row['mediaValue'] ) )
		{
			$row['mediaValue'] = '';
		}
	}

	// Xac dinh images
	if( ! empty( $row['images'] ) )
	{
		if( is_file( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $row['images'] ) )
		{
			$row['images'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $row['images'];
		}
		elseif( ! nv_is_url( $row['images'] ) )
		{
			$row['images'] = '';
		}
	}
	
	$array[$row['id']] = $row;
	$array_userids[$row['postid']] = $row['postid'];
}

// Khong cho dat $page tuy y
if( $page > 1 and empty( $array ) )
{
	header( 'Location:' . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
	die();
}

// Lay thanh vien dang bai
if( ! empty( $array_userids ) )
{
	$sql = "SELECT `userid`, `username` FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid` IN(" . implode( ",", $array_userids ) . ")";
	$result = $db->sql_query( $sql );
	
	$array_userids = array();
	while( $row = $db->sql_fetchrow( $result ) )
	{
		$array_userids[$row['userid']] = $row['username'];
	}
	
	foreach( $array as $row )
	{
		if( isset( $array_userids[$row['postid']] ) )
		{
			$array[$row['id']]['postName'] = $array_userids[$row['postid']];
		}
	}
}

// Du lieu phan trang
$generate_page = $BL->pagination( $page_title, $base_url, $all_page, $per_page, $page );
$total_pages = ceil( $all_page / $per_page );

// Them vao tieu de neu phan trang
if( $page > 1 )
{
	$page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $BL->glang('page') . ' ' . $page;
	$key_words .= ', ' . $BL->glang('page') . ' ' . $page;
	$description .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $BL->glang('page') . ' ' . $page;
}

$contents = nv_main_theme( $array, $generate_page, $BL->setting, $page, $total_pages );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>
<?php

/**
 * @Project NUKEVIET BLOG 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if ( ! defined( 'NV_IS_MOD_BLOG' ) ) die( 'Stop!!!' );

$page_title = $mod_title = $global_array_cat[$catid]['title'];
$key_words = $global_array_cat[$catid]['keywords'];
$description = $global_array_cat[$catid]['description'];

// Xac dinh thong tin phan trang
$per_page = intval( $BL->setting['numPostPerPage'] );

// SQL co ban
$sql = "FROM `" . $BL->table_prefix . "_rows` WHERE `status`=1 AND " . $BL->build_query_search_id( $catid, 'catids', 'AND' );
$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;

// Lay so row
$sql1 = "SELECT COUNT(*) " . $sql;
$result1 = $db->sql_query( $sql1 );
list( $all_page ) = $db->sql_fetchrow( $result1 );

// Lay du lieu
$sql = "SELECT * " . $sql . " ORDER BY `pubTime` DESC LIMIT " . ( ( $page - 1 ) * $per_page ) . ", " . $per_page;
$result = $db->sql_query( $sql );

$array = $array_userids = array();

// Danh sách các bảng data của bài viết sẽ cần duyệt qua để lấy nội dung hmtl
$array_table_pass = array();

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
		if( is_file( NV_UPLOADS_REAL_DIR . '/' . $module_name . $row['mediaValue'] ) )
		{
			$row['mediaValue'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . $row['mediaValue'];
		}
		elseif( ! nv_is_url( $row['mediaValue'] ) )
		{
			$row['mediaValue'] = '';
		}
	}

	// Xac dinh images
	if( ! empty( $row['images'] ) )
	{
		if( is_file( NV_UPLOADS_REAL_DIR . '/' . $module_name . $row['images'] ) )
		{
			$row['images'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . $row['images'];
		}
		elseif( ! nv_is_url( $row['images'] ) )
		{
			$row['images'] = '';
		}
	}
	
	// Mặc định nội dung html trống
	$row['bodyhtml'] = '';
	
	// Đánh dấu fullpage
	if( ! empty( $row['fullPage'] ) )
	{
		$table = ceil( $row['id'] / 4000 );
		$array_table_pass[$table][$row['id']] = $row['id'];
		unset( $table );
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

// Lấy nội dung html nếu có
if( ! empty( $array_table_pass ) )
{
	foreach( $array_table_pass as $table => $postids )
	{
		$sql = "SELECT `id`, `bodyhtml` FROM `" . $BL->table_prefix . "_data_" . $table . "` WHERE `id` IN( " . implode( ",", $postids ) . " )";
		$result = $db->sql_query( $sql );
		
		while( $row = $db->sql_fetch_assoc( $result ) )
		{
			$array[$row['id']]['bodyhtml'] = $row['bodyhtml'];
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

$contents = nv_viewcat_theme( $array, $generate_page, $BL->setting, $page, $total_pages, $BL );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>
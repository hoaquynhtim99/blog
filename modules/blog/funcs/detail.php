<?php

/**
 * @Project NUKEVIET BLOG 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if ( ! defined( 'NV_IS_MOD_BLOG' ) ) die( 'Stop!!!' );

if( empty( $blog_data ) )
{
	header( 'Location:' . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
	die();
}

$page_title = $mod_title = $blog_data['siteTitle'];
$key_words = $blog_data['keywords'];
$description = $blog_data['hometext'];

// Lấy nội dung html của bài viết
$sql = "SELECT `bodyhtml` FROM `" . $BL->table_prefix . "_data_" . ceil( $blog_data['id'] / 4000 ) . "` WHERE `id`=" . $blog_data['id'];
$result = $db->sql_query( $sql );

if( $db->sql_numrows( $result ) )
{
	list( $blog_data['bodyhtml'] ) = $db->sql_fetchrow( $result );
}

// Lấy tags bài viết
$blog_data['tags'] = $BL->getTagsByID( $blog_data['tagids'], true );

// Lấy bài viết tiếp theo
$blog_data['nextPost'] = array();
$sql = "SELECT `title`, `alias` FROM `" . $BL->table_prefix . "_rows` WHERE `status`=1 AND ( " . $BL->build_query_search_id( $catid, 'catids' ) . " ) AND `pubTime`>" . $blog_data['pubTime'] . " ORDER BY `pubTime` ASC LIMIT 1";
$result = $db->sql_query( $sql );

if( $db->sql_numrows( $result ) )
{
	$blog_data['nextPost'] = $db->sql_fetch_assoc( $result );
	$blog_data['nextPost']['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $blog_data['nextPost']['alias'];
}

// Lấy bài viết trước đó
$blog_data['prevPost'] = array();
$sql = "SELECT `title`, `alias` FROM `" . $BL->table_prefix . "_rows` WHERE `status`=1 AND ( " . $BL->build_query_search_id( $catid, 'catids' ) . " ) AND `pubTime`<" . $blog_data['pubTime'] . " ORDER BY `pubTime` DESC LIMIT 1";
$result = $db->sql_query( $sql );

if( $db->sql_numrows( $result ) )
{
	$blog_data['prevPost'] = $db->sql_fetch_assoc( $result );
	$blog_data['prevPost']['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $blog_data['prevPost']['alias'];
}

// Url chính xác của bài đăng
$blog_data['href'] = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $blog_data['alias'], true );

// Open Graph
$my_head .= "<meta property=\"og:title\" content=\"" . $page_title . ' ' . NV_TITLEBAR_DEFIS . ' ' . $global_config['site_name'] . "\" />\n";
$my_head .= "<meta property=\"og:type\" content=\"article\" />\n";
$my_head .= "<meta property=\"og:url\" content=\"" . $blog_data['href'] . "\" />\n";
$my_head .= "<meta property=\"og:description\" content=\"" . $description . "\" />\n";
$my_head .= "<meta property=\"article:published_time\" content=\"" . date( "Y-m-d", $blog_data['pubTime'] ) . "\" />\n";
$my_head .= "<meta property=\"article:section\" content=\"" . $global_array_cat[$catid]['title'] . "\" />\n";

// Khai báo thời gian cập nhật nếu bài đăng được cập nhật
if( $blog_data['pubTime'] != $blog_data['updateTime'] )
{
	$my_head .= "<meta property=\"article:modified_time\" content=\"" . date( "Y-m-d", $blog_data['updateTime'] ) . "\" />\n";	
}

// Khai báo thời gian hết hạn nếu bài đăng hết hạn
if( ! empty( $blog_data['expTime'] ) )
{
	$my_head .= "<meta property=\"article:expiration_time\" content=\"" . date( "Y-m-d", $blog_data['expTime'] ) . "\" />\n";
}

// Từ khóa bài đăng
if( ! empty( $blog_data['keywords'] ) )
{
	$keywords = array_map( 'trim', array_map( 'nv_strtolower', array_unique( array_filter( explode( ',', $blog_data['keywords'] ) ) ) ) );
	
	if( ! empty( $keywords ) )
	{
		arsort( $keywords );
		
		foreach( $keywords as $keyword )
		{
			$my_head .= "<meta property=\"article:tag\" content=\"" . $keyword . "\" />\n";
		}
	}
}

if( ! empty( $blog_data['mediaValue'] ) )
{
	// Âm thanh
	if( $blog_data['mediaType'] == 2 )
	{
		$my_head .= "<meta property=\"og:audio\" content=\"" . ( preg_match( "/^\//", $blog_data['mediaValue'] ) ? NV_MY_DOMAIN . $blog_data['mediaValue'] : $blog_data['mediaValue'] ) . "\" />\n";
	}
	// Video
	elseif( $blog_data['mediaType'] == 3 )
	{
		$my_head .= "<meta property=\"og:video\" content=\"" . ( preg_match( "/^\//", $blog_data['mediaValue'] ) ? NV_MY_DOMAIN . $blog_data['mediaValue'] : $blog_data['mediaValue'] ) . "\" />\n";
	}
}

// Hình ảnh bài đăng
if( ! empty( $blog_data['mediaValue'] ) and $blog_data['mediaType'] == 1 )
{
	if( preg_match( "/^\//", $blog_data['mediaValue'] ) )
	{
		$my_head .= "<meta property=\"og:image\" content=\"" . NV_MY_DOMAIN . $blog_data['mediaValue'] . "\" />\n";
	}
	else
	{
		$my_head .= "<meta property=\"og:image\" content=\"" . $blog_data['mediaValue'] . "\" />\n";
	}
}
elseif( ! empty( $blog_data['images'] ) )
{
	if( preg_match( "/^\//", $blog_data['images'] ) )
	{
		$my_head .= "<meta property=\"og:image\" content=\"" . NV_MY_DOMAIN . $blog_data['images'] . "\" />\n";
	}
	else
	{
		$my_head .= "<meta property=\"og:image\" content=\"" . $blog_data['images'] . "\" />\n";
	}
}
elseif( ! empty( $BL->setting['sysDefaultImage'] ) )
{
	if( preg_match( "/^\//", $BL->setting['sysDefaultImage'] ) )
	{
		$my_head .= "<meta property=\"og:image\" content=\"" . NV_MY_DOMAIN . $BL->setting['sysDefaultImage'] . "\" />\n";
	}
	else
	{
		$my_head .= "<meta property=\"og:image\" content=\"" . $BL->setting['sysDefaultImage'] . "\" />\n";
	}
}
else
{
	$my_head .= "<meta property=\"og:image\" content=\"" . NV_MY_DOMAIN . NV_BASE_SITEURL . $global_config['site_logo'] . "\" />\n";
}

$contents = nv_detail_theme( $blog_data, $BL );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>
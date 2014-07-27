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

$contents = nv_detail_theme( $blog_data, $BL );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>
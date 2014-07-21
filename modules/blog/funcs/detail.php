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

//print_r( $blog_data );
//die();

$contents = nv_detail_theme( $blog_data );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>
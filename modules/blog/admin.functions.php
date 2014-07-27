<?php

/**
 * @Project NUKEVIET BLOG 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

// Class cua module
require_once( NV_ROOTDIR . "/modules/" . $module_file . "/blog.class.php" );
$BL = new nv_mod_blog();

$submenu['blog-list'] = $BL->lang('blogList');
$submenu['blog-content'] = $BL->lang('blogAdd');
$submenu['categories'] = $BL->lang('categoriesManager');
$submenu['tags'] = $BL->lang('tagsMg');
$submenu['newsletter-manager'] = $BL->lang('nltList');
$submenu['config-master'] = $BL->lang('cfgMaster');

$allow_func = array( 'main', 'categories', 'newsletter-manager', 'config-master', 'tags', 'blog-list', 'blog-content', 'config-block-tags', 'config-sys', 'config-structured-data' );

define( 'NV_BLOG_ADMIN', true );

// Tao lien ket tinh tu dong
if( $nv_Request->isset_request( "get_alias", "post" ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
	
	include ( NV_ROOTDIR . "/includes/header.php" );
	echo $BL->creatAlias( filter_text_input( 'get_alias', 'post', '', 1, 255 ), filter_text_input( 'mode', 'post', 'cat', 1, 255 ) );
	include ( NV_ROOTDIR . "/includes/footer.php" );
	die();
}

?>
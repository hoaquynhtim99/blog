<?php

/**
 * @Project NUKEVIET BLOG 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

// Class cua module
require_once( NV_ROOTDIR . "/modules/" . $module_file . "/blog.class.php" );
$BL = new nv_mod_blog();

define( 'NV_BLOG_ADMIN', true );

// Tao lien ket tinh tu dong
if( $nv_Request->isset_request( "get_alias", "post" ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
	
	include NV_ROOTDIR . '/includes/header.php';
	echo $BL->creatAlias( nv_substr( $nv_Request->get_title( 'get_alias', 'post', '', 1 ), 0, 255 ), nv_substr( $nv_Request->get_title( 'mode', 'post', 'cat', 1 ), 0, 255 ) );
	include NV_ROOTDIR . '/includes/footer.php';
	die();
}
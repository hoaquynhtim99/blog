<?php

/**
 * @Project NUKEVIET BLOG 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if ( ! defined( 'NV_IS_MOD_BLOG' ) ) die( 'Stop!!!' );

// Cập nhật số bình luận khi đăng bình luận ở các cổng bình luận của mạng xã hội
if( $nv_Request->isset_request( 'addCommentOnly', 'post' ) )
{
	$id = $nv_Request->get_int( 'id', 'post', 0 );
	
	if( $id )
	{
		$sql = "UPDATE " . $BL->table_prefix . "_rows SET numcomments=numcomments+1 WHERE id=" . $id;
		$db->query( $sql );
		
		nv_del_moduleCache( $module_name );
	}
	
	die( 'OK' );
}

if( $nv_Request->isset_request( 'delCommentOnly', 'post' ) )
{
	$id = $nv_Request->get_int( 'id', 'post', 0 );
	
	if( $id )
	{
		$sql = "UPDATE " . $BL->table_prefix . "_rows SET numcomments=numcomments-1 WHERE id=" . $id;
		$db->query( $sql );
		
		nv_del_moduleCache( $module_name );
	}
	
	die('OK');
}

die('Error Access!!!');
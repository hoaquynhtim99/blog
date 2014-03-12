<?php

/**
 * @Project NUKEVIET BLOG 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if( ! defined( 'NV_BLOG_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $BL->lang('mainTitle');

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$array_notice = array();

// Tags chua co mo ta hoac keywords
$sql = "SELECT COUNT(*) FROM `" . $BL->table_prefix . "_tags` WHERE `keywords`='' OR `description`=''";
list( $num ) = $db->sql_fetchrow( $db->sql_query( $sql ) );

if( $num > 0 )
{
	$array_notice[] = array(
		"link" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name  . "&amp;" . NV_OP_VARIABLE . "=tags&amp;noComplete=1",
		"title" => sprintf( $BL->lang('mainTagsWarning'), $num ),
	);
}

// Bai viet het han, cho dang, nhap
$sql = "SELECT COUNT(*) AS `number`, `status` FROM `" . $BL->table_prefix . "_rows` GROUP BY `status`";
$result = $db->sql_query( $sql );

while( $row = $db->sql_fetchrow( $result ) )
{
	if( $row['number'] > 0 )
	{
		if( $row['status'] == 2 )
		{
			$array_notice[] = array(
				"link" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name  . "&amp;" . NV_OP_VARIABLE . "=tags&amp;noComplete=1",
				"title" => sprintf( $BL->lang('mainPostExpriedWarning'), $row['number'] ),
			);
		}
		elseif( $row['status'] == -2 )
		{
			$array_notice[] = array(
				"link" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name  . "&amp;" . NV_OP_VARIABLE . "=tags&amp;noComplete=1",
				"title" => sprintf( $BL->lang('mainPostDraftWarning'), $row['number'] ),
			);
		}
		elseif( $row['status'] == -1 )
		{
			$array_notice[] = array(
				"link" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name  . "&amp;" . NV_OP_VARIABLE . "=tags&amp;noComplete=1",
				"title" => sprintf( $BL->lang('mainPostWaitWarning'), $row['number'] ),
			);
		}
	}
}

// Xuat cac canh bao
if( ! empty( $array_notice ) )
{
	foreach( $array_notice as $notice )
	{
		$xtpl->assign( 'NOTICE', $notice );
		$xtpl->parse( 'main.notice.loop' );
	}
	
	$xtpl->parse( 'main.notice' );
}
else
{
	$xtpl->parse( 'main.NoNotice' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>
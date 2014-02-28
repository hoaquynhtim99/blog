<?php

/**
 * @Project NUKEVIET BLOG 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if ( ! defined( 'NV_BLOG_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $BL->lang('cfgMaster');

$array = array();

// Lay thong tin submit
if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$array['indexViewType'] = filter_text_input( 'indexViewType', 'post', 'type_blog', 1, 255 );
	
	// Kiem tra xac nhan
	if( ! in_array( $array['indexViewType'], $BL->indexViewType ) )
	{
		$array['indexViewType'] = $BL->indexViewType[0];
	}
	
	foreach( $array as $config_name => $config_value )
	{
		$sql = "REPLACE INTO `" . $BL->table_prefix . "_config` VALUES (" . $db->dbescape( $config_name ) . "," . $db->dbescape( $config_value ) . ")";
		$db->sql_query( $sql );
	}

	nv_del_moduleCache( $module_name );

	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
	die();
}

$xtpl = new XTemplate( "config-master.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op );
$xtpl->assign( 'DATA', $BL->setting );

// Xuat cac kieu hien thi
foreach( $BL->indexViewType as $type )
{
	$xtpl->assign( 'INDEXVIEWTYPE', array( "key" => $type, "title" => $BL->lang('cfgindexViewType_' . $type), "selected" => $type == $BL->setting['indexViewType'] ? " selected=\"selected\"" : "" ) );
	$xtpl->parse( 'main.indexViewType' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>
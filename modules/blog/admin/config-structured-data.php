<?php

/**
 * @Project NUKEVIET BLOG 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if( ! defined( 'NV_BLOG_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $BL->lang('cfgStructureData');

$array = array();

$_array_locales = nv_object2array( simplexml_load_file( NV_ROOTDIR . '/modules/' . $module_file . '/locales/locales.xml' )->xpath('locale') );
$array_locales = array();

foreach( $_array_locales as $locale )
{
	$array_locales[$locale['codes']['code']['standard']['representation']] = $locale['englishName'];
}

unset( $_array_locales, $locale );

// Lay thong tin submit
if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$array['sysGoogleAuthor'] = filter_text_input( 'sysGoogleAuthor', 'post', '', 0, 30 );
	$array['sysFbAppID'] = filter_text_input( 'sysFbAppID', 'post', '', 0, 30 );
	$array['sysLocale'] = filter_text_input( 'sysLocale', 'post', '', 0, 255 );
	
	if( ! preg_match( "/^([0-9]+)$/", $array['sysGoogleAuthor'] ) )
	{
		$array['sysGoogleAuthor'] = '';
	}
	if( ! preg_match( "/^([0-9]+)$/", $array['sysFbAppID'] ) )
	{
		$array['sysFbAppID'] = '';
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

$xtpl = new XTemplate( "config-structured-data.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op );
$xtpl->assign( 'DATA', $BL->setting );

$xtpl->assign( 'INITNEWSLETTERS', $BL->setting['initNewsletters'] ? " checked=\"checked\"" : "" );

// Xu?t ngôn ng? và qu?c gia
foreach( $array_locales as $k => $v )
{
	$xtpl->assign( 'SYSLOCALE', array(
		'key' => $k,
		'title' => $v,
		'selected' => $k == $BL->setting['sysLocale'] ? " selected=\"selected\"" : "",
	) );
	$xtpl->parse( 'main.sysLocale' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>
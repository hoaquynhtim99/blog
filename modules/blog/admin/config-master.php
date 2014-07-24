<?php

/**
 * @Project NUKEVIET BLOG 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if( ! defined( 'NV_BLOG_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $BL->lang('cfgMaster');

$array = array();

// Thu muc uploads
$array_structure_image = array();
$array_structure_image[''] = NV_UPLOADS_DIR . '/' . $module_name;
$array_structure_image['Y'] = NV_UPLOADS_DIR . '/' . $module_name . '/' . date( 'Y' );
$array_structure_image['Ym'] = NV_UPLOADS_DIR . '/' . $module_name . '/' . date( 'Y_m' );
$array_structure_image['Y_m'] = NV_UPLOADS_DIR . '/' . $module_name . '/' . date( 'Y/m' );
$array_structure_image['Ym_d'] = NV_UPLOADS_DIR . '/' . $module_name . '/' . date( 'Y_m/d' );
$array_structure_image['Y_m_d'] = NV_UPLOADS_DIR . '/' . $module_name . '/' . date( 'Y/m/d' );
$array_structure_image['username'] = NV_UPLOADS_DIR . '/' . $module_name . '/username';

$array_structure_image['username_Y'] = NV_UPLOADS_DIR . '/' . $module_name . '/username/' . date( 'Y' );
$array_structure_image['username_Ym'] = NV_UPLOADS_DIR . '/' . $module_name . '/username/' . date( 'Y_m' );
$array_structure_image['username_Y_m'] = NV_UPLOADS_DIR . '/' . $module_name . '/username/' . date( 'Y/m' );
$array_structure_image['username_Ym_d'] = NV_UPLOADS_DIR . '/' . $module_name . '/username/' . date( 'Y_m/d' );
$array_structure_image['username_Y_m_d'] = NV_UPLOADS_DIR . '/' . $module_name . '/username/' . date( 'Y/m/d' );

// Giao diện của highlight js
$array_highlight_themes = nv_scandir( NV_ROOTDIR . '/modules/' . $module_file . '/frameworks/highlight/styles', "/^(.*?)\.css$/i" );

// Fix du lieu
$numberResendNewsletterMax = 5;

// Lay thong tin submit
if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$array['indexViewType'] = filter_text_input( 'indexViewType', 'post', 'type_blog', 1, 255 );
	$array['catViewType'] = filter_text_input( 'catViewType', 'post', 'type_blog', 1, 255 );
	$array['numPostPerPage'] = $nv_Request->get_int( 'numPostPerPage', 'post', 0 );
	$array['initPostExp'] = $nv_Request->get_int( 'initPostExp', 'post', 0 );
	$array['initPostType'] = $nv_Request->get_int( 'initPostType', 'post', 0 );
	$array['initMediaType'] = $nv_Request->get_int( 'initMediaType', 'post', 0 );
	$array['initMediaHeight'] = $nv_Request->get_int( 'initMediaHeight', 'post', 250 );
	$array['initNewsletters'] = $nv_Request->get_int( 'initNewsletters', 'post', 0 );
	$array['folderStructure'] = filter_text_input( 'folderStructure', 'post', '', 0, 255 );
	$array['numberResendNewsletter'] = $nv_Request->get_int( 'numberResendNewsletter', 'post', 0 );
	$array['strCutHomeText'] = $nv_Request->get_int( 'strCutHomeText', 'post', 0 );
	$array['sysHighlightTheme'] = filter_text_input( 'sysHighlightTheme', 'post', '', 0, 255 );
	
	// Lấy cấu hình lớp của icon loại bài viết
	foreach( $BL->blogpostType as $type )
	{
		$array['iconClass' . $type] = filter_text_input( 'iconClass' . $type, 'post', 'icon-pencil', 1, 255 );
	}
	
	// Kiem tra xac nhan
	if( ! in_array( $array['indexViewType'], $BL->indexViewType ) )
	{
		$array['indexViewType'] = $BL->indexViewType[0];
	}
	if( ! in_array( $array['catViewType'], $BL->catViewType ) )
	{
		$array['catViewType'] = $BL->catViewType[0];
	}
	if( ! in_array( $array['initPostExp'], $BL->blogExpMode ) )
	{
		$array['initPostExp'] = $BL->blogExpMode[0];
	}
	if( ! in_array( $array['initPostType'], $BL->blogpostType ) )
	{
		$array['initPostType'] = $BL->blogpostType[0];
	}
	if( ! in_array( $array['initMediaType'], $BL->blogMediaType ) )
	{
		$array['initMediaType'] = $BL->blogMediaType[0];
	}
	if( $array['initMediaHeight'] <= 0 )
	{
		$array['initMediaHeight'] = 250;
	}
	if( ! isset( $array_structure_image[$array['folderStructure']] ) )
	{
		$array['folderStructure'] = "Ym";
	}
	
	$array['initNewsletters'] = $array['initNewsletters'] ? 1 : 0;
	
	if( $array['numberResendNewsletter'] > $numberResendNewsletterMax or $array['numberResendNewsletter'] < 0 )
	{
		$array['numberResendNewsletter'] = 0;
	}
	if( $array['numPostPerPage'] > 100 or $array['numPostPerPage'] < 1 )
	{
		$array['numPostPerPage'] = 10;
	}
	if( $array['strCutHomeText'] < 0 )
	{
		$array['strCutHomeText'] = 0;
	}
	
	// Kiểm tra giao diện highlight tồn tại
	if( ! in_array( $array['sysHighlightTheme'] . '.css', $array_highlight_themes ) )
	{
		$array['sysHighlightTheme'] = 'default';
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

$xtpl->assign( 'INITNEWSLETTERS', $BL->setting['initNewsletters'] ? " checked=\"checked\"" : "" );

// Xuat cac kieu hien thi
foreach( $BL->indexViewType as $type )
{
	$xtpl->assign( 'INDEXVIEWTYPE', array( "key" => $type, "title" => $BL->lang('cfgindexViewType_' . $type), "selected" => $type == $BL->setting['indexViewType'] ? " selected=\"selected\"" : "" ) );
	$xtpl->parse( 'main.indexViewType' );
}

foreach( $BL->catViewType as $type )
{
	$xtpl->assign( 'CATVIEWTYPE', array( "key" => $type, "title" => $BL->lang('cfgcatViewType_' . $type), "selected" => $type == $BL->setting['catViewType'] ? " selected=\"selected\"" : "" ) );
	$xtpl->parse( 'main.catViewType' );
}

// Xuat cac kieu xu ly khi het han dang bai
foreach( $BL->blogExpMode as $initPostExp )
{
	$initPostExp = array(
		"key" => $initPostExp,
		"title" => $BL->lang('blogExpMode_' . $initPostExp),
		"selected" => $initPostExp == $BL->setting['initPostExp'] ? " selected=\"selected\"" : "",
	);

	$xtpl->assign( 'INITPOSTEXP', $initPostExp );
	$xtpl->parse( 'main.initPostExp' );
}

// Loai bai viet mac dinh
foreach( $BL->blogpostType as $initPostType )
{
	$initPostType = array(
		"key" => $initPostType,
		"title" => $BL->lang('blogpostType' . $initPostType),
		"selected" => $initPostType == $BL->setting['initPostType'] ? " selected=\"selected\"" : "",
	);

	$xtpl->assign( 'INITPOSTTYPE', $initPostType );
	$xtpl->parse( 'main.initPostType' );
}

// Loai Media mac dinh
foreach( $BL->blogMediaType as $initMediaType )
{
	$initMediaType = array(
		"key" => $initMediaType,
		"title" => $BL->lang('blogmediaType' . $initMediaType),
		"selected" => $initMediaType == $BL->setting['initMediaType'] ? " selected=\"selected\"" : "",
	);

	$xtpl->assign( 'INITMEDIATYPE', $initMediaType );
	$xtpl->parse( 'main.initMediaType' );
}

// Xuat thu muc uploads
foreach( $array_structure_image as $folderStructure => $dir )
{
	$folderStructure = array(
		"key" => $folderStructure,
		"title" => $dir,
		"selected" => $folderStructure == $BL->setting['folderStructure'] ? " selected=\"selected\"" : "",
	);
	
	$xtpl->assign( 'FOLDERSTRUCTURE', $folderStructure );
	$xtpl->parse( 'main.folderStructure' );
}

// Xuat so lan gui lai mail
for( $i = 0; $i <= $numberResendNewsletterMax; $i ++ )
{
	$numberResendNewsletter = array(
		"key" => $i,
		"title" => $i,
		"selected" => $i == $BL->setting['numberResendNewsletter'] ? " selected=\"selected\"" : "",
	);
	
	$xtpl->assign( 'NUMBERRESENDNEWSLETTER', $numberResendNewsletter );
	$xtpl->parse( 'main.numberResendNewsletter' );
}

// Xuất các loại bài đăng để chọn icon
$i = 0;
foreach( $BL->blogpostType as $type )
{
	$iconClass = array(
		'class' => $i ++ % 2 ? " class=\"second\"" : "",
		'key' => $type,
		'title' => sprintf( $BL->lang('cfgIconPost'), $BL->lang('blogpostType' . $type) ),
		'value' => $BL->setting['iconClass' . $type],
	);
	
	$xtpl->assign( 'ICONCLASS', $iconClass );
	$xtpl->parse( 'main.iconClass' );
}

// Xuất giao diện highlight
foreach( $array_highlight_themes as $_highlightTheme )
{
	$_highlightTheme = substr( $_highlightTheme, 0, -4 );
	
	$highlightTheme = array(
		'key' => $_highlightTheme,
		'title' => ucfirst( str_replace( array( '-', '_', '.' ), ' ', $_highlightTheme ) ),
		'selected' => $_highlightTheme == $BL->setting['sysHighlightTheme'] ? " selected=\"selected\"" : "",
	);
	
	$xtpl->assign( 'HIGHLIGHTTHEME', $highlightTheme );
	$xtpl->parse( 'main.highlightTheme' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>
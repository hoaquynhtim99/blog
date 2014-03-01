<?php

/**
 * @Project NUKEVIET BLOG 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if ( ! defined( 'NV_BLOG_ADMIN' ) ) die( 'Stop!!!' );

// Goi js
$BL->callJqueryPlugin( 'jquery.ui.sortable', 'jquery.tipsy', 'jquery.autosize', 'jquery.ui.autocomplete' );

$page_title = $BL->lang('blogManager');

// Lay va khoi tao cac bien
$error = "";
$id = $nv_Request->get_int( 'id', 'get', 0 );

// Xu ly
if( $id )
{
	$sql = "SELECT * FROM `" . $BL->table_prefix . "_rows` WHERE `id`=" . $id;
	$result = $db->sql_query( $sql );
	
	if( $db->sql_numrows( $result ) != 1 )
	{
		nv_info_die( $BL->glang('error_404_title'), $BL->glang('error_404_title'), $BL->glang('error_404_content') );
	}
	
	$row = $db->sql_fetchrow( $result );
	
	$array_old = $array = array(
		"postid" => ( int ) $row['postid'],
		"siteTitle" => $row['siteTitle'],
		"title" => $row['title'],
		"alias" => $row['alias'],
		"keywords" => $row['keywords'],
		"images" => $row['images'],
		"mediaType" => ( int ) $row['mediaType'],
		"mediaHeight" => ( int ) $row['mediaHeight'],
		"mediaValue" => $row['mediaValue'],
		"hometext" => nv_br2nl( $row['hometext'] ),
		"bodytext" => $row['bodytext'],
		"bodyhtml" => '',
		"postType" => ( int ) $row['postType'],
		"catids" => $BL->string2array( $row['catids'] ),
		"tagids" => $BL->string2array( $row['tagids'] ),
		"numWords" => ( int ) $row['numWords'],
		"pubTime" => ( int ) $row['pubTime'],
		"pubTime_h" => date( "G", $array['pubTime'] ),
		"pubTime_m" => ( int ) date( "i", $array['pubTime'] ),
		"expTime" => ( int ) $row['expTime'],
		"expTime_h" => $array['expTime'] ? date( "G", $array['expTime'] ) : 0,
		"expTime_m" => $array['expTime'] ? ( int ) date( "i", $array['expTime'] ) : 0,
		"expMode" => ( int ) $row['expMode'],
	);
	
	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;id=" . $id;
	$table_caption = $BL->lang('blogEdit');
}
else
{
	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
	$table_caption = $BL->lang('blogAdd');
	
	$array = array(
		"postid" => $admin_info['userid'],
		"siteTitle" => '',
		"title" => '',
		"alias" => '',
		"keywords" => '',
		"images" => '',
		"mediaType" => 0,
		"mediaHeight" => 0,
		"mediaValue" => '',
		"hometext" => '',
		"bodytext" => '',
		"bodyhtml" => '',
		"postType" => 0,
		"catids" => array(),
		"tagids" => array(),
		"numWords" => 0,
		"pubTime" => NV_CURRENTTIME,
		"pubTime_h" => date( "G", NV_CURRENTTIME ),
		"pubTime_m" => ( int ) date( "i", NV_CURRENTTIME ),
		"expTime" => 0,
		"expTime_h" => 0,
		"expTime_m" => 0,
		"expMode" => 0,
	);
}

// Sua lai noi dung
if( ! empty( $array['hometext'] ) ) $array['hometext'] = nv_htmlspecialchars( $array['hometext'] );
if( ! empty( $array['bodyhtml'] ) ) $array['bodyhtml'] = nv_htmlspecialchars( $array['bodyhtml'] );

// Trinh soan thao
if( defined( 'NV_EDITOR' ) )
{
	require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
}

if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
{
	$array['bodyhtml'] = nv_aleditor( 'bodyhtml', '100%', '800px', $array['bodyhtml'] );
}
else
{
	$array['bodyhtml'] = "<textarea style=\"width:100%; height:800px\" name=\"bodyhtml\" id=\"bodyhtml\">" . $array['bodyhtml'] . "</textarea>";
}

// Sua lai gio tu so thanh text
$array['pubTime'] = $array['pubTime'] ? date( "d/m/Y", $array['pubTime'] ) : "";
$array['expTime'] = $array['expTime'] ? date( "d/m/Y", $array['expTime'] ) : "";

$xtpl = new XTemplate( "blog-content.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$xtpl->assign( 'DATA', $array );
$xtpl->assign( 'TAGIDS', implode( ",", $array['tagids'] ) );
$xtpl->assign( 'TABLE_CAPTION', $table_caption );
$xtpl->assign( 'FORM_ACTION', $form_action );

// Xuat danh muc
$list_cats = $BL->listCat( 0, 0 );

foreach( $list_cats as $cat )
{
	$cat['checked'] = in_array( $cat['id'], $array['catids'] ) ? " checked=\"checked\"" : "";

	$xtpl->assign( 'CAT', $cat );
	$xtpl->parse( 'main.cat' );
}

// Xuat loai bai viet
foreach( $BL->blogpostType as $postType )
{
	$postType = array(
		"key" => $postType,
		"title" => $BL->lang('blogpostType' . $postType),
		"checked" => $postType == $array['postType'] ? " checked=\"checked\"" : "",
	);

	$xtpl->assign( 'POSTTYPE', $postType );
	$xtpl->parse( 'main.postType' );
}

// Xuat gio
for( $i = 0; $i <= 23; $i ++ )
{
	$hour = array(
		"key" => $i,
		"title" => $i,
		"pub" => $i == $array['pubTime_h'] ? " selected=\"selected\"" : "",
		"exp" => $i == $array['expTime_h'] ? " selected=\"selected\"" : "",
	);

	$xtpl->assign( 'HOUR', $hour );
	
	$xtpl->parse( 'main.pubTime_h' );
	$xtpl->parse( 'main.expTime_h' );
}

// Xuat phut
for( $i = 0; $i <= 59; $i ++ )
{
	$min = array(
		"key" => $i,
		"title" => $i,
		"pub" => $i == $array['pubTime_m'] ? " selected=\"selected\"" : "",
		"exp" => $i == $array['expTime_m'] ? " selected=\"selected\"" : "",
	);

	$xtpl->assign( 'MIN', $min );
	
	$xtpl->parse( 'main.pubTime_m' );
	$xtpl->parse( 'main.expTime_m' );
}

// Xuat thao tac sau khi het han
foreach( $BL->blogExpMode as $expMode )
{
	$expMode = array(
		"key" => $expMode,
		"title" => $BL->lang('blogExpMode_' . $expMode),
		"checked" => $expMode == $array['expMode'] ? " checked=\"checked\"" : "",
	);

	$xtpl->assign( 'EXPMODE', $expMode );
	$xtpl->parse( 'main.expMode' );
}

// Lay mot so tag co so bai viet nhieu
$sql = "SELECT * FROM `" . $BL->table_prefix . "_tags` ORDER BY `numPosts` DESC LIMIT 0, 10";
$result = $db->sql_query( $sql );

if( $db->sql_numrows( $result ) )
{
	while( $row = $db->sql_fetch_assoc( $result ) )
	{
		$xtpl->assign( 'MOSTTAGS', $row );
		$xtpl->parse( 'main.mostTags.loop' );
	}
	
	$xtpl->parse( 'main.mostTags' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>
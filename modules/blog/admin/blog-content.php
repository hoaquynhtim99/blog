<?php

/**
 * @Project NUKEVIET BLOG 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if ( ! defined( 'NV_BLOG_ADMIN' ) ) die( 'Stop!!!' );

// Goi js
$BL->callJqueryPlugin( 'jquery.ui.sortable', 'jquery.tipsy', 'jquery.autosize', 'jquery.ui.autocomplete', 'jquery.ui.datepicker' );

$page_title = $BL->lang('blogManager');

// Lay va khoi tao cac bien
$error = "";
$complete = false;
$id = $nv_Request->get_int( 'id', 'get, post', 0 );

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
		"status" => ( int ) $row['status'],
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
		"status" => -2,
	);
}

// Thao tac xu ly
$prosessMode = "none";
if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$prosessMode = "public";
}
elseif( $nv_Request->isset_request( 'draft', 'post' ) )
{
	$prosessMode = "draft";
}

// Xu ly khi submit
if( $prosessMode != 'none' )
{
	$array['siteTitle'] = filter_text_input( 'siteTitle', 'post', '', 1, 255 );
	$array['title'] = filter_text_input( 'title', 'post', '', 1, 255 );
	$array['alias'] = filter_text_input( 'alias', 'post', '', 1, 255 );
	$array['keywords'] = filter_text_input( 'keywords', 'post', '', 1, 255 );
	$array['images'] = $nv_Request->get_string( 'images', 'post', '' );
	$array['mediaType'] = $nv_Request->get_int( 'mediaType', 'post', 0 );
	$array['mediaHeight'] = $nv_Request->get_int( 'mediaHeight', 'post', 0 );
	$array['mediaValue'] = filter_text_textarea( 'mediaValue', '', NV_ALLOWED_HTML_TAGS );
	$array['hometext'] = filter_text_textarea( 'hometext', '', NV_ALLOWED_HTML_TAGS );
	$array['bodyhtml'] = nv_editor_filter_textarea( 'bodyhtml', '', NV_ALLOWED_HTML_TAGS );
	$array['postType'] = $nv_Request->get_int( 'postType', 'post', 0 );
	$array['catids'] = $nv_Request->get_typed_array( 'catids', 'post', 'int' );
	$array['tagids'] = filter_text_input( 'tagids', 'post', '', 1, 255 );
	$array['pubTime'] = $nv_Request->get_string( 'pubTime', 'post', '' );
	$array['pubTime_h'] = $nv_Request->get_int( 'pubTime_h', 'post', 0 );
	$array['pubTime_m'] = $nv_Request->get_int( 'pubTime_m', 'post', 0 );
	$array['expTime'] = $nv_Request->get_string( 'expTime', 'post', '' );
	$array['expTime_h'] = $nv_Request->get_int( 'expTime_h', 'post', 0 );
	$array['expTime_m'] = $nv_Request->get_int( 'expTime_m', 'post', 0 );
	$array['expMode'] = $nv_Request->get_int( 'expMode', 'post', 0 );
	
	// Chuan hoa tu khoa
	$array['keywords'] = $array['keywords'] ? implode( ", ", array_filter( array_unique( array_map( "trim", explode( ",", $array['keywords'] ) ) ) ) ) : "";
	
	// Chinh duong dan anh
	if( ! empty( $array['images'] ) )
	{
		if( preg_match( "/^\//i", $array['images'] ) )
		{
			$array['images'] = substr( $array['images'], strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/images/" ) );
		}
	}
	
	// Chuan hoa, khoi tao lien ket tinh
	if( ! empty( $array['alias'] ) )
	{
		$array['alias'] = strtolower( change_alias( $array['alias'] ) );
	}
	else
	{
		if( ! empty( $array['title'] ) )
		{
			$array['alias'] = strtolower( change_alias( $array['title'] ) );
		}
		elseif( $prosessMode == "draft" )
		{
			$array['alias'] = "draft";
		}
	}
	
	// Tao bodytext
	$array['bodytext'] = trim( nv_nl2br( strip_tags( $array['bodyhtml'] ), " " ) );
	
	// Chuan hoa loai bai viet
	if( ! in_array( $array['postType'], $BL->blogpostType ) )
	{
		$array['postType'] = 0;
	}
	
	// Thay doi danh muc, tags string => array
	$array['tagids'] = $BL->string2array( $array['tagids'] );
	
	// Chuan hoa va xu ly thoi gian
	if( $array['pubTime_h'] > 23 or $array['pubTime_h'] < 0 )
	{
		$array['pubTime_h'] = 0;
	}
	if( $array['pubTime_m'] > 59 or $array['pubTime_m'] < 0 )
	{
		$array['pubTime_m'] = 0;
	}
	if( $array['expTime_h'] > 23 or $array['expTime_h'] < 0 )
	{
		$array['expTime_h'] = 0;
	}
	if( $array['expTime_m'] > 59 or $array['expTime_m'] < 0 )
	{
		$array['expTime_m'] = 0;
	}
	
	if( preg_match( "/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/", $array['pubTime'], $m ) )
	{
		$array['pubTime'] = mktime( $array['pubTime_h'], $array['pubTime_m'], 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$array['pubTime'] = NV_CURRENTTIME;
	}
	if( preg_match( "/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/", $array['expTime'], $m ) )
	{
		$array['expTime'] = mktime( $array['expTime_h'], $array['expTime_m'], 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$array['expTime'] = 0;
	}
	
	// Chuan hoa kieu xu ly khi het han
	if( ! in_array( $array['expMode'], $BL->blogExpMode ) )
	{
		$array['expMode'] = 0;
	}
	
	// Kiem tra loi, khong kiem tra neu luu ban nhap
	if( $prosessMode == "public" )
	{
		if( empty( $array['title'] ) )
		{
			$error = $BL->lang('blogErrorTitle');
		}
		elseif( empty( $array['keywords'] ) )
		{
			$error = $BL->lang('errorKeywords');
		}
		elseif( empty( $array['hometext'] ) )
		{
			$error = $BL->lang('blogErrorHometext');
		}
		elseif( empty( $array['bodyhtml'] ) )
		{
			$error = $BL->lang('blogErrorBodyhtml');
		}
		elseif( empty( $array['catids'] ) )
		{
			$error = $BL->lang('blogErrorCategories');
		}
		elseif( ! empty( $array['expTime'] ) and $array['expTime'] <= $array['pubTime'] )
		{
			$error = $BL->lang('blogErrorExpThanPub');
		}
	}
	
	// Kiem tra lien ket tinh ton tai va tao lien ket tinh khac neu la luu ban nhap
	$sql = "SELECT * FROM `" . $BL->table_prefix . "_rows` WHERE `alias`=" . $db->dbescape( $array['alias'] ) . ( ! empty( $id ) ? " AND `id`!=" . $id : "" );
	$result = $db->sql_query( $sql );
	
	if( $db->sql_numrows( $result ) )
	{
		// Neu la xuat ban thi bao loi ton tai
		if( $prosessMode == "public" )
		{
			$error = $BL->lang('errorAliasExists');
		}
		// Tao lien ket tinh khac
		else
		{
			$array['alias'] = $BL->creatAlias( $array['alias'], 'post' );
		}
	}
	
	// Xac dinh status
	if( empty( $id ) and $prosessMode == "draft" )
	{
		$array['status'] = -2;
	}
	
	if( empty( $error ) )
	{
		$array['hometext'] = nv_nl2br( $array['hometext'] );
		$array['bodyhtml'] = nv_editor_nl2br( $array['bodyhtml'] );
		
		if( empty( $id ) )
		{
			$sql = "INSERT INTO `" . $BL->table_prefix . "_rows` VALUES(
				NULL,
				" . $array['postid'] . ", 
				" . $db->dbescape( $array['siteTitle'] ) . ",
				" . $db->dbescape( $array['title'] ) . ",
				" . $db->dbescape( $array['alias'] ) . ",
				" . $db->dbescape( $array['keywords'] ) . ",
				" . $db->dbescape( $array['images'] ) . ",
				" . $array['mediaType'] . ", 
				" . $array['mediaHeight'] . ", 
				" . $db->dbescape( $array['mediaValue'] ) . ",
				" . $db->dbescape( $array['hometext'] ) . ",
				" . $db->dbescape( $array['bodytext'] ) . ",
				" . $array['postType'] . ", 
				" . $db->dbescape( $array['catids'] ? "0," . implode( ",", $array['catids'] ) . ",0" : "" ) . ",
				" . $db->dbescape( $array['tagids'] ? "0," . implode( ",", $array['tagids'] ) . ",0" : "" ) . ",
				" . $array['numWords'] . ", 
				0, 0, 0, 0, '', 
				" . NV_CURRENTTIME . ", 
				" . NV_CURRENTTIME . ", 
				" . $array['pubTime'] . ", 
				" . $array['expTime'] . ", 
				" . $array['expMode'] . ", 
				" . $array['status'] . "
			)";
			
			$id = $db->sql_query_insert_id( $sql );
			
			if( $id )
			{
				if( $prosessMode != "draft" )
				{
					// Xoa cache 
				}
				
				$complete = true;
			}
			else
			{
				$error = $BL->lang('errorSaveUnknow');
			}
		}
		else
		{
			$sql = "UPDATE `" . $BL->table_prefix ."_rows` SET 
				`postid`=" . $array['postid'] . ", 
				`siteTitle`=" . $db->dbescape( $array['siteTitle'] ) . ",
				`title`=" . $db->dbescape( $array['title'] ) . ",
				`alias`=" . $db->dbescape( $array['alias'] ) . ",
				`keywords`=" . $db->dbescape( $array['keywords'] ) . ",
				`images`=" . $db->dbescape( $array['images'] ) . ",
				`mediaType`=" . $array['mediaType'] . ", 
				`mediaHeight`=" . $array['mediaHeight'] . ", 
				`mediaValue`=" . $db->dbescape( $array['mediaValue'] ) . ",
				`hometext`=" . $db->dbescape( $array['hometext'] ) . ",
				`bodytext`=" . $db->dbescape( $array['bodytext'] ) . ",
				`postType`=" . $array['postType'] . ", 
				`catids`=" . $db->dbescape( $array['catids'] ? "0," . implode( ",", $array['catids'] ) . ",0" : "" ) . ",
				`tagids`=" . $db->dbescape( $array['tagids'] ? "0," . implode( ",", $array['tagids'] ) . ",0" : "" ) . ",
				`numWords`=" . $array['numWords'] . ", 
				`updateTime`=" . NV_CURRENTTIME . ", 
				`pubTime`=" . $array['pubTime'] . ", 
				`expTime`=" . $array['expTime'] . ", 
				`expMode`=" . $array['expMode'] . ", 
				`status`=" . $array['status'] . "
			WHERE `id`=" . $id;
			
			if( $db->sql_query( $sql ) )
			{
				$complete = true;
			}
			else
			{
				$error = $BL->lang('errorUpdateUnknow');
			}
		}
	}
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
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

$xtpl->assign( 'ID', $id );
$xtpl->assign( 'DATA', $array );
$xtpl->assign( 'TAGIDS', implode( ",", $array['tagids'] ) );
$xtpl->assign( 'TABLE_CAPTION', $table_caption );
$xtpl->assign( 'FORM_ACTION', $form_action );
$xtpl->assign( 'EDITOR', ( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) ) ? "true" : "false" );

// Tra ve JSON neu luu ban nhap
if( $prosessMode == "draft" )
{
	$contents = array(
		"error" => empty( $error ) ? 0 : 1,
		"message" => $complete ? $BL->lang('blogSaveDraftOk') : $error,
		"id" => $id,
	);
	
	include ( NV_ROOTDIR . "/includes/header.php" );
	echo json_encode( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
	die();
}

// Neu la xuat ban thanh cong
if( $complete )
{
	$my_head = "<meta http-equiv=\"refresh\" content=\"3;url=" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name  . "&" . NV_OP_VARIABLE . "=blog-list\" />";
	
	$xtpl->assign( 'MESSAGE', $BL->lang('blogSaveOk') );

	$xtpl->parse( 'complete' );
	$contents = $xtpl->text( 'complete' );

	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_admin_theme( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
	die();
}

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
		"title" => str_pad( $i, 2, "0", STR_PAD_LEFT ),
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
		"title" => str_pad( $i, 2, "0", STR_PAD_LEFT ),
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
		"selected" => $expMode == $array['expMode'] ? " selected=\"selected\"" : "",
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

// Xuat tags da chon
$tags = $BL->getTagsByID( $array['tagids'], true );
if( ! empty( $tags ) )
{
	foreach( $tags as $tag )
	{
		$xtpl->assign( 'TAG', $tag );
		$xtpl->parse( 'main.tag' );
	}
}

// Xuat loi
if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>
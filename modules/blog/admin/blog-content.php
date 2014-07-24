<?php

/**
 * @Project NUKEVIET BLOG 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if( ! defined( 'NV_BLOG_ADMIN' ) ) die( 'Stop!!!' );

// Xu ly thu muc uploads
$username_alias = change_alias( $admin_info['username'] );
$array_structure_image = array();
$array_structure_image[''] = $module_name;
$array_structure_image['Y'] = $module_name . '/' . date( 'Y' );
$array_structure_image['Ym'] = $module_name . '/' . date( 'Y_m' );
$array_structure_image['Y_m'] = $module_name . '/' . date( 'Y/m' );
$array_structure_image['Ym_d'] = $module_name . '/' . date( 'Y_m/d' );
$array_structure_image['Y_m_d'] = $module_name . '/' . date( 'Y/m/d' );
$array_structure_image['username'] = $module_name . '/' . $username_alias;

$array_structure_image['username_Y'] = $module_name . '/' . $username_alias . '/' . date( 'Y' );
$array_structure_image['username_Ym'] = $module_name . '/' . $username_alias . '/' . date( 'Y_m' );
$array_structure_image['username_Y_m'] = $module_name . '/' . $username_alias . '/' . date( 'Y/m' );
$array_structure_image['username_Ym_d'] = $module_name . '/' . $username_alias . '/' . date( 'Y_m/d' );
$array_structure_image['username_Y_m_d'] = $module_name . '/' . $username_alias . '/' . date( 'Y/m/d' );

$currentpath = isset( $array_structure_image[$BL->setting['folderStructure']] ) ? $array_structure_image[$BL->setting['folderStructure']] : '';

if( file_exists( NV_UPLOADS_REAL_DIR . '/' . $currentpath ) )
{
	$upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $currentpath;
}
else
{
	$upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $module_name;
	$e = explode( "/", $currentpath );
	if( ! empty( $e ) )
	{
		$cp = "";
		foreach( $e as $p )
		{
			if( ! empty( $p ) and ! is_dir( NV_UPLOADS_REAL_DIR . '/' . $cp . $p ) )
			{
				$mk = nv_mkdir( NV_UPLOADS_REAL_DIR . '/' . $cp, $p );
				nv_loadUploadDirList( false );
				if( $mk[0] > 0 )
				{
					$upload_real_dir_page = $mk[2];
				}
			}
			elseif( ! empty( $p ) )
			{
				$upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $cp . $p;
			}
			$cp .= $p . '/';
		}
	}
	$upload_real_dir_page = str_replace( "\\", "/", $upload_real_dir_page );
}

$currentpath = str_replace( NV_ROOTDIR . "/", "", $upload_real_dir_page );

// Goi js
$BL->callFrameWorks( 'ui.sortable', 'tipsy', 'autosize', 'ui.autocomplete', 'ui.datepicker', 'shadowbox' );

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
		"fullPage" => ( int ) $row['fullPage'],
		"catids" => $BL->string2array( $row['catids'] ),
		"tagids" => $BL->string2array( $row['tagids'] ),
		"numWords" => ( int ) $row['numWords'],
		"pubTime" => ( int ) $row['pubTime'],
		"pubTime_h" => date( "G", $row['pubTime'] ),
		"pubTime_m" => ( int ) date( "i", $row['pubTime'] ),
		"expTime" => ( int ) $row['expTime'],
		"expTime_h" => $row['expTime'] ? date( "G", $row['expTime'] ) : 0,
		"expTime_m" => $row['expTime'] ? ( int ) date( "i", $row['expTime'] ) : 0,
		"expMode" => ( int ) $row['expMode'],
		"status" => ( int ) $row['status'],
	);
	
	$sql = "SELECT * FROM `" . $BL->table_prefix . "_data_" . ceil( $id / 4000 ) . "` WHERE `id`=" . $id;
	$result = $db->sql_query( $sql );
	
	if( $db->sql_numrows( $result ) )
	{
		$row = $db->sql_fetchrow( $result );
		$array_old['bodyhtml'] = $array['bodyhtml'] = $row['bodyhtml'];
	}
	
	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;id=" . $id;
	$table_caption = $BL->lang('blogEdit');
	
	// Gui email den cac email dang ky nhan tin
	$newsletters = 0;
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
		"mediaType" => $BL->setting['initMediaType'],
		"mediaHeight" => $BL->setting['initMediaHeight'],
		"mediaValue" => '',
		"hometext" => '',
		"bodytext" => '',
		"bodyhtml" => '',
		"postType" => $BL->setting['initPostType'],
		"fullPage" => 0,
		"catids" => array(),
		"tagids" => array(),
		"numWords" => 0,
		"pubTime" => NV_CURRENTTIME,
		"pubTime_h" => date( "G", NV_CURRENTTIME ),
		"pubTime_m" => ( int ) date( "i", NV_CURRENTTIME ),
		"expTime" => 0,
		"expTime_h" => 0,
		"expTime_m" => 0,
		"expMode" => $BL->setting['initPostExp'],
		"status" => -2,
	);
	
	// Gui email den cac email dang ky nhan tin
	$newsletters = $BL->setting['initNewsletters'];
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
	$array['mediaValue'] = $nv_Request->get_string( 'mediaValue', 'post', '' );
	$array['hometext'] = filter_text_textarea( 'hometext', '', NV_ALLOWED_HTML_TAGS );
	$array['bodyhtml'] = nv_editor_filter_textarea( 'bodyhtml', '', NV_ALLOWED_HTML_TAGS );
	$array['postType'] = $nv_Request->get_int( 'postType', 'post', 0 );
	$array['fullPage'] = $nv_Request->get_int( 'fullPage', 'post', 0 );
	$array['catids'] = $nv_Request->get_typed_array( 'catids', 'post', 'int' );
	$array['tagids'] = filter_text_input( 'tagids', 'post', '', 1, 255 );
	$array['pubTime'] = $nv_Request->get_string( 'pubTime', 'post', '' );
	$array['pubTime_h'] = $nv_Request->get_int( 'pubTime_h', 'post', 0 );
	$array['pubTime_m'] = $nv_Request->get_int( 'pubTime_m', 'post', 0 );
	$array['expTime'] = $nv_Request->get_string( 'expTime', 'post', '' );
	$array['expTime_h'] = $nv_Request->get_int( 'expTime_h', 'post', 0 );
	$array['expTime_m'] = $nv_Request->get_int( 'expTime_m', 'post', 0 );
	$array['expMode'] = $nv_Request->get_int( 'expMode', 'post', 0 );
	
	$newsletters = $nv_Request->get_int( 'newsletters', 'post', 0 );
	
	// Chuan hoa tu khoa
	$array['keywords'] = $array['keywords'] ? implode( ", ", array_filter( array_unique( array_map( "trim", explode( ",", $array['keywords'] ) ) ) ) ) : "";
	
	// Chinh duong dan anh
	if( ! empty( $array['images'] ) )
	{
		if( preg_match( "/^\//i", $array['images'] ) )
		{
			$array['images'] = substr( $array['images'], strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name ) );
		}
	}
	
	// Chinh duong dan media
	if( ! empty( $array['mediaValue'] ) )
	{
		if( preg_match( "/^\//i", $array['mediaValue'] ) )
		{
			$array['mediaValue'] = substr( $array['mediaValue'], strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name ) );
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
	
	// Chuan hoa loai media
	if( ! in_array( $array['mediaType'], $BL->blogMediaType ) )
	{
		$array['mediaType'] = 0;
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
	
	// Chuẩn hóa giá trị 0, 1
	$array['fullPage'] = $array['fullPage'] ? 1 : 0;
	
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
		elseif( empty( $array['bodytext'] ) )
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
		elseif( ! empty( $array['expTime'] ) and $array['expTime'] <= NV_CURRENTTIME and $array['expMode'] == 2 )
		{
			$error = $BL->lang('blogErrorExp');
		}
		elseif( ! empty( $array['mediaType'] ) and empty( $array['mediaValue'] ) )
		{
			$error = $BL->lang('blogErrorMediaValue');
		}
		elseif( $array['mediaType'] > 1 and empty( $array['mediaHeight'] ) )
		{
			$error = $BL->lang('blogErrorMediaHeight');
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
	if( $prosessMode == "draft" )
	{
		$array['status'] = -2;
	}
	// Bai viet het han
	elseif( $array['expTime'] <= NV_CURRENTTIME and ! empty( $array['expTime'] ) )
	{
		$array['status'] = $array['expMode'] == 0 ? 0 : 2;
	}
	// Bai viet cho dang
	elseif( $array['pubTime'] > NV_CURRENTTIME )
	{
		// Tao bai viet thi -1
		if( empty( $id ) )
		{
			$array['status'] = -1;
		}
		// Sua bai viet
		else
		{
			// Neu khong bi dung thi cho dang
			if( ! in_array( $array_old['status'], array( 0 ) ) )
			{
				$array['status'] = -1;
			}
			// Bi dung thi tiep tuc dung, nhap thi tiep tuc nhap
			else
			{
				$array['status'] = $array_old['status'];
			}
		}
	}
	else
	{
		// Tao bai viet thi 1
		if( empty( $id ) )
		{
			$array['status'] = 1;
		}
		// Sua bai viet
		else
		{
			// Neu khong bi dung thi cho dang
			if( ! in_array( $array_old['status'], array( 0 ) ) )
			{
				$array['status'] = 1;
			}
			// Bi dung thi tiep tuc dung, nhap thi tiep tuc nhap
			else
			{
				$array['status'] = $array_old['status'];
			}
		}
	}
	
	if( empty( $error ) )
	{
		$array['hometext'] = nv_nl2br( $array['hometext'] );
		
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
				" . $array['fullPage'] . ", 
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
				// Tao bang HTML
				$html_table = $BL->table_prefix . "_data_" . ceil( $id / 4000 );
				
				$sql = "CREATE TABLE IF NOT EXISTS `" . $html_table . "` (
					`id` mediumint(8) unsigned NOT NULL, 
					`bodyhtml` longtext NOT NULL,
					PRIMARY KEY  (`id`) 
				) ENGINE=MyISAM";
				
				if( ! $db->sql_query( $sql ) and $prosessMode != "draft" )
				{
					$error = $BL->lang('blogErrorCreatTable');
				}
				
				// Luu noi dung bodyhtml vao
				$sql = "INSERT INTO `" . $html_table . "` VALUES( " . $id . ", " . $db->dbescape( $array['bodyhtml'] ) . " )";
				if( ! $db->sql_query( $sql ) and $prosessMode != "draft" )
				{
					$error = $BL->lang('blogErrorSaveHtml');
				}
				
				if( empty( $error ) )
				{
					$complete = true;
					
					if( $prosessMode != "draft" )
					{
						// Cap nhat danh muc
						$BL->fixCat( $array['catids'] );
						
						// Cap nhat tags
						$BL->fixTags( $array['tagids'] );
						
						// Gui newsletters
						if( ! empty( $newsletters ) )
						{
							$sql = "INSERT INTO `" . $BL->table_prefix . "_send` VALUES( NULL, " . $id . ", 0, 0, 0, 1, 0, '', '' )";
							$db->sql_query( $sql );
						}
						
						// Xoa cache
						nv_del_moduleCache( $module_name );
					}
					
					// Xu ly tin
					$BL->executeData();
				}
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
				`fullPage`=" . $array['fullPage'] . ", 
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
				$html_table = $BL->table_prefix . "_data_" . ceil( $id / 4000 );
				
				// Luu noi dung bodyhtml vao
				$sql = "UPDATE `" . $html_table . "` SET `bodyhtml`=" . $db->dbescape( $array['bodyhtml'] ) . " WHERE `id`=" . $id;
				
				if( ! $db->sql_query( $sql ) and $prosessMode != "draft" )
				{
					$error = $BL->lang('blogErrorUpdateHtml');
				}
				
				if( empty( $error ) )
				{
					$complete = true;
					
					if( $prosessMode != "draft" )
					{
						// Cap nhat danh muc
						$BL->fixCat( array_unique( array_filter( array_merge_recursive( $array_old['catids'], $array['catids'] ) ) ) );
						
						// Cap nhat tags
						$BL->fixTags( array_unique( array_filter( array_merge_recursive( $array_old['tagids'], $array['tagids'] ) ) ) );
						
						// Xoa cache
						nv_del_moduleCache( $module_name );
					}
					
					// Xu ly tin
					$BL->executeData();
				}
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

// Trinh soan thao
if( defined( 'NV_EDITOR' ) )
{
	require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
}

if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
{
	$array['bodyhtml'] = nv_aleditor( 'bodyhtml', '100%', '500px', $array['bodyhtml'] );
}
else
{
	$array['bodyhtml'] = "<textarea style=\"width:100%; height:500px\" name=\"bodyhtml\" id=\"bodyhtml\">" . $array['bodyhtml'] . "</textarea>";
}

// Sua lai gio tu so thanh text
$array['pubTime'] = $array['pubTime'] ? date( "d/m/Y", $array['pubTime'] ) : "";
$array['expTime'] = $array['expTime'] ? date( "d/m/Y", $array['expTime'] ) : "";

// Chuyen so thanh chuoi
if( empty( $array['mediaHeight'] ) )
{
	$array['mediaHeight'] = "";
}

// Chinh duong dan anh
if( ! empty( $array['images'] ) and preg_match( "/^\//i", $array['images'] ) )
{
	$array['images'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . $array['images'];
}

// Chinh duong dan media
if( ! empty( $array['mediaValue'] ) and preg_match( "/^\//i", $array['mediaValue'] ) )
{
	$array['mediaValue'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . $array['mediaValue'];
}

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

// Xuat kieu media
foreach( $BL->blogMediaType as $mediaType )
{
	$mediaType = array(
		"key" => $mediaType,
		"title" => $BL->lang('blogmediaType' . $mediaType),
		"selected" => $mediaType == $array['mediaType'] ? " selected=\"selected\"" : "",
	);

	$xtpl->assign( 'MEDIATYPE', $mediaType );
	$xtpl->parse( 'main.mediaType' );
}

// Bien chon media
$xtpl->assign( 'UPLOADS_PATH', NV_UPLOADS_DIR . '/' . $module_name );
$xtpl->assign( 'CURRENT_PATH', $currentpath );

// Cac checkbox
$xtpl->assign( 'NEWSLETTERS', $newsletters ? " checked=\"checked\"" : "" );
$xtpl->assign( 'FULLPAGE', $array['fullPage'] ? " checked=\"checked\"" : "" );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>
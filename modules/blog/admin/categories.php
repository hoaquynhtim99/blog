<?php

/**
 * @Project NUKEVIET BLOG 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if( ! defined( 'NV_BLOG_ADMIN' ) ) die( 'Stop!!!' );

// Ham xoa danh muc
function nv_del_cat( $catid, $db, $module_data, $BL )
{
	$sql = "SELECT `parentid`, `title` FROM `" . $BL->table_prefix . "_categories` WHERE `id`=" . $catid;
	list( $parentid, $catTitle ) = $db->sql_fetchrow( $db->sql_query( $sql ) );
	
	$sql = "SELECT `id` FROM `" . $BL->table_prefix . "_categories` WHERE `parentid`=" . $catid;
	$result = $db->sql_query( $sql );
	
	while( list( $id ) = $db->sql_fetchrow( $result ) )
	{
		nv_del_cat( $id, $db, $module_data, $BL );
	}
	
	// Xoa bang danh muc
	$sql = "DELETE FROM `" . $BL->table_prefix . "_categories` WHERE `id`=" . $catid;
	$db->sql_query( $sql );
	
	// Cap nhat thong ke danh muc
	$BL->fixCat( $parentid );
	
	// Ghi nhat ky
	nv_insert_logs( NV_LANG_DATA, $module_data, $BL->lang('categoriesDelete'), $catTitle, $admin_info['userid'] );
}

// Thay doi thu tu chuyen muc
if( $nv_Request->isset_request( 'changeweight', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
	
	$catid = $nv_Request->get_int( 'id', 'post', 0 );
	$new = $nv_Request->get_int( 'new', 'post', 0 );
	
	if( empty( $catid ) ) die( "NO" );
	
	$sql = "SELECT `parentid` FROM `" . $BL->table_prefix . "_categories` WHERE `id`=" . $catid;
	$result = $db->sql_query( $sql );
	$numrows = $db->sql_numrows( $result );
	if( $numrows != 1 ) die( 'NO' );
	list( $parentid ) = $db->sql_fetchrow( $result );
	
	$sql = "SELECT `id` FROM `" . $BL->table_prefix . "_categories` WHERE `id`!=" . $catid . " AND `parentid`=" . $parentid . " ORDER BY `weight` ASC";
	$result = $db->sql_query( $sql );
	
	$weight = 0;
	while( $row = $db->sql_fetchrow( $result ) )
	{
		$weight ++;
		if( $weight == $new ) $weight ++;
		$sql = "UPDATE `" . $BL->table_prefix . "_categories` SET `weight`=" . $weight . " WHERE `id`=" . $row['id'];
		$db->sql_query( $sql );
	}
	$sql = "UPDATE `" . $BL->table_prefix . "_categories` SET `weight`=" . $new . " WHERE `id`=" . $catid;
	$db->sql_query( $sql );
	
	nv_del_moduleCache( $module_name );
	
	die( "OK" );
}

// Cho hoat dong, dung hoat dong
if( $nv_Request->isset_request( 'changestatus', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
	
	$catid = $nv_Request->get_int( 'id', 'post', 0 );
	
	if( empty( $catid ) ) die( "NO" );
	
	$query = "SELECT `status` FROM `" . $BL->table_prefix . "_categories` WHERE `id`=" . $catid;
	$result = $db->sql_query( $query );
	$numrows = $db->sql_numrows( $result );
	if( $numrows != 1 ) die( 'NO' );
	
	list( $status ) = $db->sql_fetchrow( $result );
	$status = $status ? 0 : 1;
	
	$sql = "UPDATE `" . $BL->table_prefix . "_categories` SET `status`=" . $status . " WHERE `id`=" . $catid;
	$db->sql_query( $sql );
	
	nv_del_moduleCache( $module_name );
	
	die( "OK" );
}

// Xoa chuyen muc
if( $nv_Request->isset_request( 'del', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
	
	$catid = $nv_Request->get_int( 'id', 'post', 0 );
	
	if( empty( $catid ) )
	{
		die( "NO" );
	}
	
	$sql = "SELECT COUNT(*) AS count, `parentid` FROM `" . $BL->table_prefix . "_categories` WHERE `id`=" . $catid;
	$result = $db->sql_query( $sql );
	list( $count, $parentid ) = $db->sql_fetchrow( $result );
	
	if( $count != 1 )
	{
		die( "NO" );
	}
	
	nv_del_cat( $catid, $db, $module_data, $BL );
	$BL->fixWeightCat( $parentid );
	
	nv_del_moduleCache( $module_name );
	
	die( "OK" );
}

$page_title = $BL->lang('categoriesManager');

$xtpl = new XTemplate( "categories.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

// Them va sua chuyen muc
$id = $nv_Request->get_int( "id", "post,get", 0 );
$error = "";

if( $id )
{
	$sql = "SELECT `id`, `parentid`, `title`, `alias`, `keywords`, `description`, `weight` FROM `" . $BL->table_prefix . "_categories` WHERE `id`=" . $id;
	$result = $db->sql_query( $sql );
	if( $db->sql_numrows( $result ) != 1 ) nv_info_die( $BL->glang('error_404_title'), $BL->glang('error_404_title'), $BL->glang('error_404_content') );
	
	$row = $db->sql_fetch_assoc( $result );
	$data = $row;
}
else
{
	$data = array(
		"parentid" => $nv_Request->get_int( "parentid", "post,get", 0 ),
		"title" => "",
		"alias" => "",
		"keywords" => "",
		"description" => "",
	);
}

if( $nv_Request->isset_request( "submit", "post" ) )
{
	$data['parentid'] = $nv_Request->get_int( "parentid", "post", 0 );
	$data['title'] = filter_text_input( 'title', 'post', '', 1, 255 );
	$data['alias'] = filter_text_input( 'alias', 'post', '', 1, 255 );
	$data['keywords'] = filter_text_input( 'keywords', 'post', '', 1, 255 );
	$data['description'] = filter_text_input( 'description', 'post', '', 1, 255 );
	
	$data['alias'] = $data['alias'] ? strtolower( change_alias( $data['alias'] ) ) : strtolower( change_alias( $data['title'] ) );
	$data['keywords'] = $data['keywords'] ? implode( ", ", array_filter( array_unique( array_map( "trim", explode( ",", $data['keywords'] ) ) ) ) ) : "";
	
	if( empty( $data['title'] ) )
	{
		$error = $BL->lang('categoriesErrorTitle');
	}
	elseif( empty( $data['keywords'] ) )
	{
		$error = $BL->lang('errorKeywords');
	}
	elseif( empty( $data['description'] ) )
	{
		$error = $BL->lang('errorSescription');
	}
	elseif( $BL->checkExistsAlias( $data['alias'], "cat", $id ) )
	{
		$error = $BL->lang('errorAliasExists');
	}
	else
	{
		// Xac dinh thu tu moi
		$new_weight = 1;
		if( ! $id or ( $id and $data['parentid'] != $row['parentid'] ) )
		{
			$sql = "SELECT MAX(weight) AS new_weight FROM `" . $BL->table_prefix . "_categories` WHERE `parentid`=" . $data['parentid'];
			$result = $db->sql_query( $sql );
			list( $new_weight ) = $db->sql_fetchrow( $result );
			$new_weight = ( int )$new_weight;
			$new_weight ++;
		}
		else
		{
			$new_weight = $row['weight'];
		}
		
		if( $id )
		{
			$sql = "UPDATE `" . $BL->table_prefix . "_categories` SET 
				`parentid`=" . $data['parentid'] . ", 
				`title`=" . $db->dbescape( $data['title'] ) . ", 
				`alias`=" . $db->dbescape( $data['alias'] ) . ", 
				`keywords`=" . $db->dbescape( $data['keywords'] ) . ", 
				`description`=" . $db->dbescape( $data['description'] ) . ", 
				`weight`=" . $new_weight . "
			WHERE `id`=" . $id;
			
			if( $db->sql_query( $sql ) )
			{
				$BL->fixCat( $id );
				
				if( $data['parentid'] != $row['parentid'] )
				{
					$BL->fixWeightCat( $row['parentid'] );
					$BL->fixCat( $row['parentid'] );
				}
				
				nv_insert_logs( NV_LANG_DATA, $module_name, $BL->lang('categoriesEditLog'), $row['title'], $admin_info['userid'] );
				nv_del_moduleCache( $module_name );
				
				Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=categories&parentid=" . $data['parentid'] );
				exit();
			}
			else
			{
				$error = $BL->lang('errorUpdateUnknow');
			}
		}
		else
		{
			$sql = "INSERT INTO `" . $BL->table_prefix . "_categories` VALUES (
				NULL, 
				" . $data['parentid'] . ", 
				" . $db->dbescape( $data['title'] ) . ", 
				" . $db->dbescape( $data['alias'] ) . ", 
				" . $db->dbescape( $data['keywords'] ) . ", 
				" . $db->dbescape( $data['description'] ) . ", 
				0, 0, " . $new_weight . ", 1
			)";
			
			$newid = $db->sql_query_insert_id( $sql );
			
			if( $newid )
			{
				$BL->fixCat( $newid );
				nv_insert_logs( NV_LANG_DATA, $module_name, $BL->lang('categoriesAdd'), $data['title'], $admin_info['userid'] );
				nv_del_moduleCache( $module_name );
				Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=categories&parentid=" . $data['parentid'] );
				exit();
			}
			else
			{
				$error = $BL->lang('errorSaveUnknow');
			}
		}
	}
}

// Xuat thong tin them, sua
$xtpl->assign( 'TABLE_CONTENT_CAPTION', ! $id ? $BL->lang('categoriesAdd') : sprintf( $BL->lang('categoriesEdit'), $row['title'] ) );
$xtpl->assign( 'DATA', $data );

$listcats = array( array( 'id' => 0, 'name' => $BL->lang('categoriesMainCat'), 'selected' => "" ) );
$listcats = $listcats + $BL->listCat( $data['parentid'], $id );
foreach( $listcats as $cat )
{
	$xtpl->assign( 'CAT', $cat );
	$xtpl->parse( 'main.cat' );
}

// Cap danh muc can lay
$array_search = array();
$array_search['parentid'] = $nv_Request->get_int( "parentid", "post,get", 0 );

// Breadcrumbs
if( $array_search['parentid'] == 0 )
{
	$breadcrumbs = array( $BL->lang('categoriesListMainCat') );
}
else
{
	$parentid = $array_search['parentid'];
	$breadcrumbs = array();
	
	while( $parentid > 0 )
	{
		if( isset( $listcats[$parentid] ) )
		{
			$breadcrumbs[] = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=categories&amp;parentid=" . $parentid . "\">" . $listcats[$parentid]['title'] . "</a>";
			$parentid = $listcats[$parentid]['parentid'];
		}
		else
		{
			$parentid = 0;
		}
	}
	$breadcrumbs[] = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=categories\">" . $BL->lang('categoriesListMainCat') . "</a>";
	sort( $breadcrumbs, SORT_NUMERIC );
}

$xtpl->assign( 'BREADCRUMBS', implode( " &gt; ", $breadcrumbs ) );

// Danh sach cac danh muc
$sql = "SELECT * FROM `" . $BL->table_prefix . "_categories` WHERE `parentid`=" . $array_search['parentid'] . " ORDER BY `weight` ASC";
$result = $db->sql_query( $sql );
$numCat = $db->sql_numrows( $result );

if( empty( $numCat ) )
{
	$xtpl->parse( 'main.empty' );
}
else
{
	$array = array();
	$i = 0;

	while( $_row = $db->sql_fetch_assoc( $result ) )
	{
		if( ! empty( $_row['numSubs'] ) )
		{
			$_row['title'] .= " (<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=categories&amp;parentid=" . $_row['id'] . "\">" . sprintf( $BL->lang('categoriesHasSub'), $_row['numSubs'] ) . "</a>)";
		}
		
		$_row['class'] = $i ++ % 2 == 0 ? " class=\"second\"" : "";
		$_row['status'] = $_row['status'] ? " checked=\"checked\"" : "";
		$_row['urlEdit'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=categories&amp;id=" . $_row['id'] . "#edit";
		
		$xtpl->assign( 'ROW', $_row );
		
		for( $j = 1; $j <= $numCat; $j ++ )
		{
			$weight = array();
			$weight['title'] = $j;
			$weight['pos'] = $j;
			$weight['selected'] = ( $j == $_row['weight'] ) ? " selected=\"selected\"" : "";
			
			$xtpl->assign( 'WEIGHT', $weight );
			$xtpl->parse( 'main.data.loop.weight' );
		}
		
		$xtpl->parse( 'main.data.loop' );
	}
	
	$xtpl->parse( 'main.data' );
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
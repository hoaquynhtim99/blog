<?php

/**
 * @Project NUKEVIET BLOG 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if ( ! defined( 'NV_BLOG_ADMIN' ) ) die( 'Stop!!!' );

// Xoa tags
if ( $nv_Request->isset_request( 'del', 'post' ) )
{
	if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
	
	$id = $nv_Request->get_int( 'id', 'post', 0 );
	$list_levelid = filter_text_input( 'listid', 'post', '' );
	
	if ( empty( $id ) and empty ( $list_levelid ) ) die( "NO" );
	
	$listid = array();
	if ( $id )
	{
		$listid[] = $id;
		$num = 1;
	}
	else
	{
		$list_levelid = explode ( ",", $list_levelid );
		$list_levelid = array_map ( "trim", $list_levelid );
		$list_levelid = array_filter ( $list_levelid );

		$listid = $list_levelid;
		$num = sizeof( $list_levelid );
	}
	
	foreach( $listid as $id )
	{
		$sql = "DELETE FROM `" . $BL->table_prefix . "_tags` WHERE `id`=" . $id;
		$db->sql_query( $sql );
	}	
	
	nv_del_moduleCache( $module_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, $BL->lang('tagsDelete'), implode( ", ", $listid ), $admin_info['userid'] );
	
	die( "OK" );
}

$page_title = $BL->lang('tagsMg');

$xtpl = new XTemplate( "tags.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

// Them va sua tags
$id = $nv_Request->get_int( "id", "post,get", 0 );
$error = "";

if( $id )
{
	$sql = "SELECT `title`, `alias`, `keywords`, `description` FROM `" . $BL->table_prefix . "_tags` WHERE `id`=" . $id;
	$result = $db->sql_query( $sql );
	if( $db->sql_numrows( $result ) != 1 ) nv_info_die( $BL->glang('error_404_title'), $BL->glang('error_404_title'), $BL->glang('error_404_content') );
	
	$row = $db->sql_fetch_assoc( $result );
	$data = $row;
}
else
{
	$data = array(
		"title" => "",
		"alias" => "",
		"keywords" => "",
		"description" => "",
	);
}

if( $nv_Request->isset_request( "submit", "post" ) )
{
	$data['title'] = filter_text_input( 'title', 'post', '', 1, 255 );
	$data['alias'] = filter_text_input( 'alias', 'post', '', 1, 255 );
	$data['keywords'] = filter_text_input( 'keywords', 'post', '', 1, 255 );
	$data['description'] = filter_text_input( 'description', 'post', '', 1, 255 );
	
	$data['alias'] = $data['alias'] ? strtolower( change_alias( $data['alias'] ) ) : strtolower( change_alias( $data['title'] ) );
	$data['keywords'] = $data['keywords'] ? implode( ", ", array_filter( array_unique( array_map( "trim", explode( ",", $data['keywords'] ) ) ) ) ) : "";
	
	if( empty( $data['title'] ) )
	{
		$error = $BL->lang('tagsErrorTitle');
	}
	elseif( empty( $data['keywords'] ) )
	{
		$error = $BL->lang('errorKeywords');
	}
	elseif( empty( $data['description'] ) )
	{
		$error = $BL->lang('errorSescription');
	}
	elseif( $BL->checkExistsAlias( $data['alias'], "tags", $id ) )
	{
		$error = $BL->lang('errorAliasExists');
	}
	else
	{
		if( $id )
		{
			$sql = "UPDATE `" . $BL->table_prefix . "_tags` SET 
				`title`=" . $db->dbescape( $data['title'] ) . ", 
				`alias`=" . $db->dbescape( $data['alias'] ) . ", 
				`keywords`=" . $db->dbescape( $data['keywords'] ) . ", 
				`description`=" . $db->dbescape( $data['description'] ) . "
			WHERE `id`=" . $id;
			
			if( $db->sql_query( $sql ) )
			{
				$BL->fixTags( $id );
				
				nv_insert_logs( NV_LANG_DATA, $module_name, $BL->lang('tagsEditLog'), $row['title'], $admin_info['userid'] );
				nv_del_moduleCache( $module_name );
				
				Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=tags" );
				exit();
			}
			else
			{
				$error = $BL->lang('errorUpdateUnknow');
			}
		}
		else
		{
			$sql = "INSERT INTO `" . $BL->table_prefix . "_tags` VALUES (
				NULL, 
				" . $db->dbescape( $data['title'] ) . ", 
				" . $db->dbescape( $data['alias'] ) . ", 
				" . $db->dbescape( $data['keywords'] ) . ", 
				" . $db->dbescape( $data['description'] ) . ", 
				0
			)";
			
			$newid = $db->sql_query_insert_id( $sql );
			
			if( $newid )
			{
				nv_insert_logs( NV_LANG_DATA, $module_name, $BL->lang('tagsAdd'), $data['title'], $admin_info['userid'] );
				nv_del_moduleCache( $module_name );
				Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=tags" );
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
$xtpl->assign( 'TABLE_CONTENT_CAPTION', ! $id ? $BL->lang('tagsAdd') : sprintf( $BL->lang('tagsEdit'), $row['title'] ) );
$xtpl->assign( 'DATA', $data );

// Phan trang
$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 60;

// Base data
$sql = "FROM `" . $BL->table_prefix . "_tags` WHERE `id`!=0";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;

// Du lieu tim kiem
$data_search = array(
	"q" => filter_text_input( 'q', 'get', '', 1, 100 ),
	"disabled" => " disabled=\"disabled\""
);

// Cam an nut huy tim kiem
if( ! empty ( $data_search['q'] ) )
{
	$data_search['disabled'] = "";
}

// Query tim kiem
if( ! empty ( $data_search['q'] ) )
{
	$base_url .= "&amp;q=" . urlencode( $data_search['q'] );
	$sql .= " AND `title` LIKE '%" . $db->dblikeescape( $data_search['q'] ) . "%'";
}

// Du lieu sap xep
$order = array();
$check_order = array( "ASC", "DESC", "NO" );
$opposite_order = array(
	"NO" => "ASC",
	"DESC" => "ASC",
	"ASC" => "DESC"
);
$lang_order_1 = array(
	"NO" => $BL->lang('filter_lang_asc'),
	"DESC" => $BL->lang('filter_lang_asc'),
	"ASC" => $BL->lang('filter_lang_desc')
);
$lang_order_2 = array(
	"title" => $BL->lang('title'),
	"numPosts" => $BL->lang('numPosts'),
);

$order['title']['order'] = filter_text_input( 'order_title', 'get', 'NO' );
$order['numPosts']['order'] = filter_text_input( 'order_numPosts', 'get', 'NO' );

foreach ( $order as $key => $check )
{
	$order[$key]['data'] = array(
		"class" => "order" . strtolower ( $order[$key]['order'] ),
		"url" => $base_url . "&amp;order_" . $key . "=" . $opposite_order[$order[$key]['order']],
		"title" => sprintf ( $lang_module['filter_order_by'], "&quot;" . $lang_order_2[$key] . "&quot;" ) . " " . $lang_order_1[$order[$key]['order']]
	);
	
	if ( ! in_array ( $check['order'], $check_order ) )
	{
		$order[$key]['order'] = "NO";
	}
	else
	{
		$base_url .= "&amp;order_" . $key . "=" . $order[$key]['order'];
	}
}

if( $order['title']['order'] != "NO" )
{
	$sql .= " ORDER BY `title` " . $order['title']['order'];
}
elseif( $order['numPosts']['order'] != "NO" )
{
	$sql .= " ORDER BY `numPosts` " . $order['numsong']['order'];
}
else
{
	$sql .= " ORDER BY `id` DESC";
}

// Lay so row
$sql1 = "SELECT COUNT(*) " . $sql;
$result1 = $db->sql_query( $sql1 );
list( $all_page ) = $db->sql_fetchrow( $result1 );

// Xay dung du lieu
$i = 1;
$sql = "SELECT * " . $sql . " LIMIT " . $page . ", " . $per_page;
$result = $db->sql_query( $sql );

// Xuat tags
while( $row = $db->sql_fetch_assoc( $result ) )
{
	$row['urlEdit'] = $base_url . "&amp;page=" . $page . "&amp;id=" . $row['id'] . "#edit";
	$row['class'] = ( $i ++ % 2 == 0 ) ? " class=\"second\"" : "";
	
	$xtpl->assign( 'ROW', $row );
	$xtpl->parse( 'main.row' );
}

// Cac thao tac
$list_action = array(
	0 => array(
		"key" => 1,
		"class" => "delete",
		"title" => $BL->glang('delete')
	),
);

foreach( $list_action as $action )
{
	$xtpl->assign( 'ACTION', $action );
	$xtpl->parse( 'main.action' );
}

// Xuat du lieu phuc vu tim kiem
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'DATA_SEARCH', $data_search );
$xtpl->assign( 'DATA_ORDER', $order );
$xtpl->assign( 'URL_CANCEL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name  . "&" . NV_OP_VARIABLE . "=" . $op );

// Phan trang
$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

if( ! empty( $generate_page ) )
{
	$xtpl->assign( 'GENERATE_PAGE', $generate_page );
	$xtpl->parse( 'main.generate_page' );
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
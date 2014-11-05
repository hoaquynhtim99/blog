<?php

/**
 * @Project NUKEVIET BLOG 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if( ! defined( 'NV_BLOG_ADMIN' ) ) die( 'Stop!!!' );

// Xoa bai viet
if( $nv_Request->isset_request( 'del', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
	
	$id = $nv_Request->get_int( 'id', 'post', 0 );
	$list_levelid = $nv_Request->get_title( 'listid', 'post', '' );
	
	if( empty( $id ) and empty( $list_levelid ) ) die( 'NO' );
	
	$listid = array();
	if( $id )
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
	
	// Goi chuc nang xoa
	$BL->delPost( $listid );
	
	// Ghi log
	nv_insert_logs( NV_LANG_DATA, $module_name, $BL->lang('blogDelete'), implode( ", ", $listid ), $admin_info['userid'] );
	
	// Xoa cache
	nv_del_moduleCache( $module_name );
	
	die( 'OK' );
}

// Thay doi hoat dong bai viet
if( $nv_Request->isset_request( 'changestatus', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
	
	$id = $nv_Request->get_int( 'id', 'post', 0 );
	$controlstatus = $nv_Request->get_int( 'status', 'post', 0 );
	$array_id = $nv_Request->get_title( 'listid', 'post', '' );
	
	if( ( empty( $id ) and empty( $array_id ) ) or empty( $controlstatus ) ) die( 'NO' );
	
	$listid = array();
	if( $id )
	{
		$listid[] = $id;
		$num = 1;
	}
	else
	{
		$array_id = explode ( ",", $array_id );
		$array_id = array_map ( "trim", $array_id );
		$array_id = array_filter ( $array_id );

		$listid = $array_id;
		$num = sizeof( $array_id );
	}
	
	// Lay cac bai viet
	$posts = $BL->getPostByID( $listid );
	
	// Kiem tra du lieu
	if( sizeof( $posts ) != $num ) die( 'NO' );
	
	$array_status = array();
	foreach( $posts as $row )
	{
		if( $controlstatus == 2 )
		{
			$array_status[$row['id']] = 0;
		}
		else
		{
			if( ! empty( $row['title'] ) and ! empty( $row['alias'] ) and ! empty( $row['keywords'] ) and ! empty( $row['hometext'] ) and ! empty( $row['bodytext'] ) and ! empty( $row['catids'] ) and ( empty( $row['exptime'] ) or $row['exptime'] > NV_CURRENTTIME ) and $row['pubtime'] > 0 and $row['pubtime'] <= NV_CURRENTTIME )
			{
				$array_status[$row['id']] = 1;
			}
			else
			{
				$array_status[$row['id']] = $row['status'];
			}
		}
	}
	
	foreach( $array_status as $id => $status )
	{
		$sql = "UPDATE " . $BL->table_prefix . "_rows SET status=" . $status . " WHERE id=" . $id;
		$db->query( $sql );	
	}
	
	// Xoa cache
	nv_del_moduleCache( $module_name );
	
	die( 'OK' );
}

$page_title = $BL->lang('blogList');

// Goi js
$BL->callFrameWorks( 'tipsy', 'ui.datepicker' );

// Khoi tao bien, phan trang
$array = array();
$per_page = 30;
$page = $nv_Request->get_int( 'page', 'get', 1 );

// SQL co ban
$sql = "FROM " . $BL->table_prefix . "_rows WHERE id!=0";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;

// Bien tim kiem
$data_search = array(
	"q" => nv_substr( $nv_Request->get_title( 'q', 'get', '', 1 ), 0, 100 ),
	"from" => nv_substr( $nv_Request->get_title( 'from', 'get', '', 1 ), 0, 100 ),
	"to" => nv_substr( $nv_Request->get_title( 'to', 'get', '', 1 ), 0, 100 ),
	"catid" => $nv_Request->get_int( 'catid', 'get', 0 ),
	"status" => $nv_Request->get_int( 'status', 'get', 10 ),
	"disabled" => " disabled=\"disabled\""
);

// Cam an nut huy tim kiem
if( ! empty( $data_search['q'] ) or ! empty( $data_search['from'] ) or ! empty( $data_search['to'] ) or ! empty( $data_search['catid'] ) or in_array( $data_search['status'], $BL->blogStatus ) )
{
	$data_search['disabled'] = "";
}

// Query tim kiem
if( ! empty( $data_search['q'] ) )
{
	$base_url .= "&amp;q=" . urlencode( $data_search['q'] );
	$sql .= " AND ( title LIKE '%" . $db->dblikeescape( $data_search['q'] ) . "%' OR hometext LIKE '%" . $db->dblikeescape( $data_search['q'] ) . "%' OR bodytext LIKE '%" . $db->dblikeescape( $data_search['q'] ) . "%' )";
}
if( ! empty( $data_search['from'] ) )
{
	unset( $match );
	if( preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $data_search['from'], $match ) )
	{
		$from = mktime( 0, 0, 0, $match[2], $match[1], $match[3] );
		$sql .= " AND posttime >= " . $from;
		$base_url .= "&amp;from=" . $data_search['from'];
	}
}
if( ! empty( $data_search['to'] ) )
{
	unset( $match );
	if( preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $data_search['to'], $match ) )
	{
		$to = mktime( 0, 0, 0, $match[2], $match[1], $match[3] );
		$sql .= " AND posttime <= " . $to;
		$base_url .= "&amp;to=" . $data_search['to'];
	}
}
if( ! empty( $data_search['catid'] ) )
{
	$base_url .= "&amp;catid=" . $data_search['catid'];
	$sql .= " AND " . $BL->build_query_search_id( $data_search['catid'], 'catids' );
}
if( in_array( $data_search['status'], $BL->blogStatus ) )
{
	$base_url .= "&amp;status=" . $data_search['status'];
	$sql .= " AND status=" . $data_search['status'];
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
	"posttime" => $BL->lang('blogposttime'),
	"updatetime" => $BL->lang('blogupdatetime'),
);

$order['title']['order'] = $nv_Request->get_title( 'order_title', 'get', 'NO' );
$order['posttime']['order'] = $nv_Request->get_title( 'order_posttime', 'get', 'NO' );
$order['updatetime']['order'] = $nv_Request->get_title( 'order_updatetime', 'get', 'NO' );

foreach ( $order as $key => $check )
{
	$order[$key]['data'] = array(
		"class" => "order" . strtolower ( $order[$key]['order'] ),
		"url" => $base_url . "&amp;order_" . $key . "=" . $opposite_order[$order[$key]['order']],
		"title" => sprintf ( $lang_module['filter_order_by'], "&quot;" . $lang_order_2[$key] . "&quot;" ) . " " . $lang_order_1[$order[$key]['order']]
	);
	
	if( ! in_array ( $check['order'], $check_order ) )
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
	$sql .= " ORDER BY title " . $order['title']['order'];
}
elseif( $order['posttime']['order'] != "NO" )
{
	$sql .= " ORDER BY posttime " . $order['posttime']['order'];
}
elseif( $order['updatetime']['order'] != "NO" )
{
	$sql .= " ORDER BY updatetime " . $order['updatetime']['order'];
}
else
{
	$sql .= " ORDER BY id DESC";
}

// Lay so row
$sql1 = "SELECT COUNT(*) " . $sql;
$result1 = $db->query( $sql1 );
$all_page = $result1->fetchColumn();

// Xay dung du lieu
$i = 1;
$sql = "SELECT * " . $sql . " LIMIT " . ( ( $page - 1 ) * $per_page ) . ", " . $per_page;
$result = $db->query( $sql );

// Goi xtemplate
$xtpl = new XTemplate( "blog-list.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

// Xuat bai viet
while( $row = $result->fetch() )
{
	$row['urlEdit'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name  . "&" . NV_OP_VARIABLE . "=blog-content&amp;id=" . $row['id'];
	$row['updatetime'] = nv_date( "H:i d/m/Y", $row['updatetime'] );
	$row['posttime'] = nv_date( "H:i d/m/Y", $row['posttime'] );
	$row['class'] = ( $i ++ % 2 == 0 ) ? " class=\"second\"" : "";
	$row['statusText'] = $BL->lang('blogStatus' . $row['status']);
	$row['link'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'], true );
	
	$xtpl->assign( 'ROW', $row );
	
	if( $row['status'] == 1 )
	{
		$xtpl->parse( 'main.row.titleLink' );
	}
	else
	{
		$xtpl->parse( 'main.row.titleText' );
	}
	
	$xtpl->parse( 'main.row' );
}

// Cac thao tac
$list_action = array(
	0 => array(
		"key" => 1,
		"class" => "delete",
		"title" => $BL->glang('delete')
	),
	1 => array(
		"key" => 2,
		"class" => "status-ok",
		"title" => $BL->lang('action_status_public')
	),
	2 => array(
		"key" => 3,
		"class" => "status-no",
		"title" => $BL->lang('action_status_no')
	)
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
$xtpl->assign( 'URL_CANCEL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name  . "&" . NV_OP_VARIABLE . "=" . $op );

// Phan trang
$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

if( ! empty( $generate_page ) )
{
	$xtpl->assign( 'GENERATE_PAGE', $generate_page );
	$xtpl->parse( 'main.generate_page' );
}

// Xuat danh muc
$list_cats = $BL->listCat( $data_search['catid'] );

foreach( $list_cats as $cat )
{
	$xtpl->assign( 'CAT', $cat );
	$xtpl->parse( 'main.cat' );
}

// Xuat trng thai
foreach( $BL->blogStatus as $status )
{
	$status = array(
		"key" => $status,
		"title" => $BL->lang('blogStatus' . $status),
		"selected" => $status == $data_search['status'] ? " selected=\"selected\"" : "",
	);

	$xtpl->assign( 'STATUS', $status );
	$xtpl->parse( 'main.status' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
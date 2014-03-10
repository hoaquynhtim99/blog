<?php

/**
 * @Project NUKEVIET BLOG 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if ( ! defined( 'NV_BLOG_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $BL->lang('blogList');

// Goi js
$BL->callJqueryPlugin( 'jquery.tipsy', 'jquery.ui.datepicker' );

// Khoi tao bien, phan trang
$array = array();
$per_page = 30;
$page = $nv_Request->get_int( 'page', 'get', 0 );

// SQL co ban
$sql = "FROM `" . $BL->table_prefix . "_rows` WHERE `id`!=0";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;

// Bien tim kiem
$data_search = array(
	"q" => filter_text_input( 'q', 'get', '', 1, 100 ),
	"from" => filter_text_input( 'from', 'get', '', 1, 100 ),
	"to" => filter_text_input( 'to', 'get', '', 1, 100 ),
	"catid" => $nv_Request->get_int( 'catid', 'get', 0 ),
	"disabled" => " disabled=\"disabled\""
);

// Cam an nut huy tim kiem
if( ! empty( $data_search['q'] ) or ! empty( $data_search['from'] ) or ! empty( $data_search['to'] ) or ! empty( $data_search['catid'] ) )
{
	$data_search['disabled'] = "";
}

// Query tim kiem
if( ! empty( $data_search['q'] ) )
{
	$base_url .= "&amp;q=" . urlencode( $data_search['q'] );
	$sql .= " AND ( `title` LIKE '%" . $db->dblikeescape( $data_search['q'] ) . "%' OR `hometext` LIKE '%" . $db->dblikeescape( $data_search['q'] ) . "%' OR `bodytext` LIKE '%" . $db->dblikeescape( $data_search['q'] ) . "%' )";
}
if( ! empty( $data_search['from'] ) )
{
	unset( $match );
	if( preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $data_search['from'], $match ) )
	{
		$from = mktime( 0, 0, 0, $match[2], $match[1], $match[3] );
		$sql .= " AND `postTime` >= " . $from;
		$base_url .= "&amp;from=" . $data_search['from'];
	}
}
if( ! empty( $data_search['to'] ) )
{
	unset( $match );
	if( preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $data_search['to'], $match ) )
	{
		$to = mktime( 0, 0, 0, $match[2], $match[1], $match[3] );
		$sql .= " AND `postTime` <= " . $to;
		$base_url .= "&amp;to=" . $data_search['to'];
	}
}
if( ! empty( $data_search['catid'] ) )
{
	$base_url .= "&amp;catid=" . $data_search['catid'];
	$sql .= " AND " . $BL->build_query_search_id( $data_search['catid'], 'catids' );
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
	"postTime" => $BL->lang('blogpostTime'),
	"updateTime" => $BL->lang('blogupdateTime'),
);

$order['title']['order'] = filter_text_input( 'order_title', 'get', 'NO' );
$order['postTime']['order'] = filter_text_input( 'order_postTime', 'get', 'NO' );
$order['updateTime']['order'] = filter_text_input( 'order_updateTime', 'get', 'NO' );

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
	$sql .= " ORDER BY `title` " . $order['title']['order'];
}
elseif( $order['postTime']['order'] != "NO" )
{
	$sql .= " ORDER BY `postTime` " . $order['postTime']['order'];
}
elseif( $order['updateTime']['order'] != "NO" )
{
	$sql .= " ORDER BY `updateTime` " . $order['updateTime']['order'];
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

// Goi xtemplate
$xtpl = new XTemplate( "blog-list.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

// Xuat bai viet
while( $row = $db->sql_fetch_assoc( $result ) )
{
	$row['urlEdit'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name  . "&" . NV_OP_VARIABLE . "=blog-content&amp;id=" . $row['id'];
	$row['updateTime'] = nv_date( "H:i d/m/Y", $row['updateTime'] );
	$row['postTime'] = nv_date( "H:i d/m/Y", $row['postTime'] );
	$row['class'] = ( $i ++ % 2 == 0 ) ? " class=\"second\"" : "";
	$row['statusText'] = $BL->lang('blogStatus' . $row['status']);
	
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

// Xuat danh muc
$list_cats = $BL->listCat( $data_search['catid'] );

foreach( $list_cats as $cat )
{
	$xtpl->assign( 'CAT', $cat );
	$xtpl->parse( 'main.cat' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>
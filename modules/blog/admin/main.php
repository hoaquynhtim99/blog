<?php

/**
 * @Project NUKEVIET BLOG 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if( ! defined( 'NV_BLOG_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $BL->lang('mainTitle');

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$array_notice = array();

// Tags chua co mo ta hoac keywords
$sql = "SELECT COUNT(*) FROM " . $BL->table_prefix . "_tags WHERE keywords='' OR description=''";
$num = $db->query( $sql )->fetchColumn();

if( $num > 0 )
{
	$array_notice[] = array(
		"link" => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name  . "&amp;" . NV_OP_VARIABLE . "=tags&amp;noComplete=1",
		"title" => sprintf( $BL->lang('mainTagsWarning'), $num ),
	);
}

// Bai viet het han, cho dang, nhap
$sql = "SELECT COUNT(*) AS number, status FROM " . $BL->table_prefix . "_rows GROUP BY status";
$result = $db->query( $sql );

while( $row = $result->fetch() )
{
	if( $row['number'] > 0 )
	{
		if( $row['status'] == 2 )
		{
			$array_notice[] = array(
				"link" => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name  . "&amp;" . NV_OP_VARIABLE . "=blog-list&amp;status=2",
				"title" => sprintf( $BL->lang('mainPostExpriedWarning'), $row['number'] ),
			);
		}
		elseif( $row['status'] == -2 )
		{
			$array_notice[] = array(
				"link" => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name  . "&amp;" . NV_OP_VARIABLE . "=blog-list&amp;status=-2",
				"title" => sprintf( $BL->lang('mainPostDraftWarning'), $row['number'] ),
			);
		}
		elseif( $row['status'] == -1 )
		{
			$array_notice[] = array(
				"link" => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name  . "&amp;" . NV_OP_VARIABLE . "=blog-list&amp;status=-1",
				"title" => sprintf( $BL->lang('mainPostWaitWarning'), $row['number'] ),
			);
		}
	}
}

// Cảnh báo Google Author chưa được đặt.
if( empty( $BL->setting['sysGoogleAuthor'] ) )
{
	$array_notice[] = array(
		"link" => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name  . "&amp;" . NV_OP_VARIABLE . "=config-structured-data",
		"title" => $BL->lang('mainCfgGoogleAuthorWarning'),
	);
}

// Cảnh báo Facebook App ID chưa được đặt.
if( empty( $BL->setting['sysFbAppID'] ) )
{
	$array_notice[] = array(
		"link" => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name  . "&amp;" . NV_OP_VARIABLE . "=config-structured-data",
		"title" => $BL->lang('mainCfgFbAppIDWarning'),
	);
}

$array_statistics = array();

// Thong ke so bai viet
$sql = "SELECT COUNT(*) FROM " . $BL->table_prefix . "_rows";
$num = $db->query( $sql )->fetchColumn();

$array_statistics[] = array(
	"link" => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name  . "&amp;" . NV_OP_VARIABLE . "=blog-list",
	"title" => sprintf( $BL->lang('mainStatPostTotal'), $num ),
);

// Thong ke so tags
$sql = "SELECT COUNT(*) FROM " . $BL->table_prefix . "_tags";
$num = $db->query( $sql )->fetchColumn();

$array_statistics[] = array(
	"link" => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name  . "&amp;" . NV_OP_VARIABLE . "=tags",
	"title" => sprintf( $BL->lang('mainStatTagsTotal'), $num ),
);

// Thong ke so email dang ky nhan tin
$sql = "SELECT COUNT(*) FROM " . $BL->table_prefix . "_newsletters";
$num = $db->query( $sql )->fetchColumn();

$array_statistics[] = array(
	"link" => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name  . "&amp;" . NV_OP_VARIABLE . "=newsletter-manager",
	"title" => sprintf( $BL->lang('mainStatNewsletters'), $num ),
);

// Xuat cac canh bao
if( ! empty( $array_notice ) )
{
	foreach( $array_notice as $notice )
	{
		$xtpl->assign( 'NOTICE', $notice );
		$xtpl->parse( 'main.notice.loop' );
	}
	
	$xtpl->parse( 'main.notice' );
}
else
{
	$xtpl->parse( 'main.NoNotice' );
}

// Xuat cac thong tin thong ke
foreach( $array_statistics as $statistics )
{
	$xtpl->assign( 'STATISTICS', $statistics );
	$xtpl->parse( 'main.statistics' );
}

// Xuat thong tin module
include( NV_ROOTDIR . "/modules/" . $module_file . "/version.php" );

$module_version['date'] = intval( strtotime( $module_version['date'] ) );
$module_version['date'] = $module_version['date'] ? nv_date( "D, j M Y H:i:s", $module_version['date'] ) . " GMT" : "N/A";

$xtpl->assign( 'MODULE_INFO', $module_version );
$xtpl->assign( 'AUTHOR_CONTACT', nv_EncodeEmail( $BL->author_email ) );

$xtpl->assign( 'DONATE_EMAIL', $BL->author_email );
$xtpl->assign( 'DONATE_ORDERID', NV_CURRENTTIME );
$xtpl->assign( 'DONATE_AMOUNT', 200000 );
$xtpl->assign( 'DONATE_RETURN', NV_MY_DOMAIN . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name );

$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'TEMPLATE', $global_config['module_theme'] );
$xtpl->assign( 'MODULE_FILE', $module_file );

// Xuất một số link cần thiết
$xtpl->assign( 'BASE_ADMIN_LINK', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . '&amp;' . NV_OP_VARIABLE . '=' );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
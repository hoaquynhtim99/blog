<?php

/**
 * @Project NUKEVIET BLOG 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if( ! defined( 'NV_IS_MOD_BLOG' ) ) die( 'Stop!!!' );

$channel = array();
$items = array();

$channel['title'] = $module_info['custom_title'] . ' ' . NV_TITLEBAR_DEFIS . ' ' . $global_config['site_name'] . ' RSS';
$channel['link'] = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;
$channel['atomlink'] = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=rss";
$channel['description'] = $module_info['description'] ? $module_info['description'] : $global_config['site_description'];

if( ! empty( $global_array_cat ) )
{
	$catalias = isset( $array_op[1] ) ? $array_op[1] : "";
	$catid = 0;
	
	if( ! empty( $catalias ) )
	{
		foreach ( $global_array_cat as $c )
		{
			if( $c['alias'] == $catalias )
			{
				$catid = $c['id'];
				break;
			}
		}
	}
	
	if( $catid > 0 )
	{
		$channel['title'] = $global_array_cat[$catid]['title'] . ' ' . NV_TITLEBAR_DEFIS . ' ' . $module_info['custom_title'] . ' ' . NV_TITLEBAR_DEFIS . ' ' . $global_config['site_name'] . ' RSS';
		$channel['link'] = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$catid]['alias'];
		$channel['description'] = $global_array_cat[$catid]['description'];
		
		$sql = "SELECT `id`, `title`, `alias`, `images`, `pubTime`, `hometext` FROM `" . $BL->table_prefix . "_rows` WHERE ( " . $BL->build_query_search_id( $catid, 'catids' ) . " ) AND `status`=1 ORDER BY `pubTime` DESC LIMIT 30";
	}
	else
	{
		$sql = "SELECT `id`, `title`, `alias`, `images`, `pubTime`, `hometext` FROM `" . $BL->table_prefix . "_rows` WHERE `status`=1 ORDER BY `pubTime` DESC LIMIT 30";
	}
	
	if( $module_info['rss'] )
	{
		if( ( $result = $db->sql_query( $sql ) ) !== false )
		{
			while( $row = $db->sql_fetch_assoc( $result ) )
			{
				// Xac dinh images
				if( ! empty( $row['images'] ) )
				{
					if( is_file( NV_UPLOADS_REAL_DIR . '/' . $module_name . $row['images'] ) )
					{
						$row['images'] = '<img src="' . NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . $row['images'] . '" alt="' . $row['title'] . '" width="100" style="float:left;margin-right:10px"/>';
					}
					elseif( nv_is_url( $row['images'] ) )
					{
						$row['images'] = '<img src="' . $row['images'] . '" alt="' . $row['title'] . '" width="100" style="float:left;margin-right:10px"/>';
					}
					else
					{
						$row['images'] = '';
					}
				}
			
				$items[] = array(
					'title' => $row['title'],
					'link' => NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $row['alias'],
					'guid' => $module_name . '_' . $row['id'],
					'description' => $row['images'] . $row['hometext'],
					'pubdate' => $row['pubTime']
				);
			}
		}
	}
}

nv_rss_generate( $channel, $items );
die();

?>
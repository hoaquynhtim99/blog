<?php

/**
 * @Project NUKEVIET BLOG 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if ( ! defined( 'NV_IS_MOD_BLOG' ) ) die( 'Stop!!!' );

function nv_main_theme( $array, $generate_page, $cfg, $page, $total_pages, $BL )
{
	global $lang_global, $lang_module, $module_file, $module_info, $my_head;
	
	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "modules/" . $module_file . "/media/jwplayer.js\"></script>" . NV_EOL;
	
	if( $BL->setting['indexViewType'] == 'type_blog' )
	{
		// Kieu danh sach blog
		$xtpl = new XTemplate( "list_blog.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	}
	else
	{
		// Kieu danh sach tin tuc
		$xtpl = new XTemplate( "list_news.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	}
	
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	
	$xtpl->assign( 'PAGE_TOTAL', $total_pages );
	$xtpl->assign( 'PAGE_CURRENT', $page );
	
	foreach( $array as $row )
	{
		$row['pubTime'] = str_replace( array( ' AM ', ' PM ' ), array( ' SA ', ' CH ' ), nv_date( 'g:i A d/m/Y', $row['pubTime'] ) );
		$row['numComments'] = number_format( $row['numComments'], 0, ',', '.' );
		$row['linkComment'] = nv_url_rewrite( $row['link'], true ) . '#comment';
		
		// Cat phan gioi thieu ngan gon
		if( $BL->setting['strCutHomeText'] )
		{
			$row['hometext'] = nv_clean60( $row['hometext'], $BL->setting['strCutHomeText'] );
		}
		
		// Hinh anh mac dinh neu khong co anh mo ta
		if( empty( $row['images'] ) )
		{
			if( $BL->setting['indexViewType'] == 'type_blog' )
			{
				$row['images'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/comingsoon-large.jpg';
			}
			else
			{
				$row['images'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/comingsoon-medium.jpg';
			}
		}
		
		$xtpl->assign( 'ROW', $row );
		
		// Chi xuat media neu nhu kieu hien thi la danh sach dang blog
		if( ! empty( $row['mediaValue'] ) and $BL->setting['indexViewType'] == 'type_blog' )
		{		
			if( in_array( $row['mediaType'], array( 0, 1 ) ) )
			{
				// Kieu hinh anh
				$xtpl->parse( 'main.loop.media.image' );
			}
			elseif( $row['mediaType'] == 2 )
			{
				// Kieu am thanh
				$xtpl->parse( 'main.loop.media.audio' );
			}
			elseif( $row['mediaType'] == 3 )
			{
				// Kieu video
				$xtpl->parse( 'main.loop.media.video' );
			}
			elseif( $row['mediaType'] == 4 )
			{
				// Kieu iframe
				$xtpl->parse( 'main.loop.media.iframe' );
			}
			
			$xtpl->parse( 'main.loop.media' );
		}
		
		$xtpl->parse( 'main.loop' );
	}	
	
	if( ! empty( $generate_page ) )
	{
		$xtpl->assign( 'GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.generate_page' );
	}
	
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function nv_newsletters_theme( $array )
{
	global $lang_global, $lang_module, $module_file, $module_info;
	
	$xtpl = new XTemplate( "newsletters.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	
	$array['class'] = $array['status'] ? "notification-box-error" : "notification-box-success";
	
	$xtpl->assign( 'DATA', $array );
	
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

?>
<?php

/**
 * @Project NUKEVIET BLOG 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if ( ! defined( 'NV_IS_MOD_BLOG' ) ) die( 'Stop!!!' );

/**
 * nv_main_theme()
 * 
 * @param mixed $array
 * @param mixed $generate_page
 * @param mixed $cfg
 * @param mixed $page
 * @param mixed $total_pages
 * @param mixed $BL
 * @return
 */
function nv_main_theme( $array, $generate_page, $cfg, $page, $total_pages, $BL )
{
	global $lang_global, $lang_module, $module_file, $module_info, $my_head;
	
	// Nếu không có bài viết thì chỉ cần thông báo
	if( empty( $array ) )
	{
		return nv_message_theme( $lang_module['noPost'], 3 );
	}
	
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
	
	// Có gọi lighlight js không
	$call_highlight = false;
	
	foreach( $array as $row )
	{
		$row['pubTime'] = str_replace( array( ' AM ', ' PM ' ), array( ' SA ', ' CH ' ), nv_date( 'g:i A d/m/Y', $row['pubTime'] ) );
		$row['numComments'] = number_format( $row['numComments'], 0, ',', '.' );
		$row['linkComment'] = nv_url_rewrite( $row['link'], true ) . '#comment';
		$row['icon'] = empty( $BL->setting['iconClass' . $row['postType']] ) ? 'icon-pencil' : $BL->setting['iconClass' . $row['postType']];
		
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
		
		// Xuất html, text
		if( ! empty( $row['fullPage'] ) )
		{
			$call_highlight = true;
			
			$xtpl->parse( 'main.loop.bodyhtml' );
		}
		else
		{
			$xtpl->parse( 'main.loop.hometext' );
		}
		
		$xtpl->parse( 'main.loop' );
	}	
	
	if( ! empty( $generate_page ) )
	{
		$xtpl->assign( 'GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.generate_page' );
	}
	
	// Gọi framewokrs highlight nếu có full page
	if( $call_highlight == true )
	{
		$BL->callFrameWorks( 'highlight' );
	}
	
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_viewcat_theme()
 * 
 * @param mixed $array
 * @param mixed $generate_page
 * @param mixed $cfg
 * @param mixed $page
 * @param mixed $total_pages
 * @param mixed $BL
 * @return
 */
function nv_viewcat_theme( $array, $generate_page, $cfg, $page, $total_pages, $BL )
{
	global $lang_global, $lang_module, $module_file, $module_info, $my_head;
	
	// Nếu không có bài viết thuộc danh mục thì chỉ cần thông báo
	if( empty( $array ) )
	{
		return nv_message_theme( $lang_module['catNoPost'], 3 );
	}
	
	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "modules/" . $module_file . "/media/jwplayer.js\"></script>" . NV_EOL;
	
	if( $BL->setting['catViewType'] == 'type_blog' )
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
	
	// Có gọi lighlight js không
	$call_highlight = false;
	
	foreach( $array as $row )
	{
		$row['pubTime'] = str_replace( array( ' AM ', ' PM ' ), array( ' SA ', ' CH ' ), nv_date( 'g:i A d/m/Y', $row['pubTime'] ) );
		$row['numComments'] = number_format( $row['numComments'], 0, ',', '.' );
		$row['linkComment'] = nv_url_rewrite( $row['link'], true ) . '#comment';
		$row['icon'] = empty( $BL->setting['iconClass' . $row['postType']] ) ? 'icon-pencil' : $BL->setting['iconClass' . $row['postType']];
		
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
		
		// Xuất html, text
		if( ! empty( $row['fullPage'] ) )
		{
			$call_highlight = true;
			
			$xtpl->parse( 'main.loop.bodyhtml' );
		}
		else
		{
			$xtpl->parse( 'main.loop.hometext' );
		}
		
		$xtpl->parse( 'main.loop' );
	}	
	
	if( ! empty( $generate_page ) )
	{
		$xtpl->assign( 'GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.generate_page' );
	}
	
	// Gọi framewokrs highlight nếu có full page
	if( $call_highlight == true )
	{
		$BL->callFrameWorks( 'highlight' );
	}
	
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_newsletters_theme()
 * 
 * @param mixed $array
 * @return
 */
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

/**
 * nv_detail_theme()
 * 
 * @param mixed $blog_data
 * @param mixed $BL
 * @return
 */
function nv_detail_theme( $blog_data, $BL )
{
	global $lang_global, $lang_module, $module_file, $module_info, $my_head, $module_name;
	
	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "modules/" . $module_file . "/media/jwplayer.js\"></script>" . NV_EOL;
	
	$xtpl = new XTemplate( "detail.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );

	$blog_data['pubTime'] = str_replace( array( ' AM ', ' PM ' ), array( ' SA ', ' CH ' ), nv_date( 'g:i A d/m/Y', $blog_data['pubTime'] ) );
	$blog_data['numComments'] = number_format( $blog_data['numComments'], 0, ',', '.' );
	$blog_data['icon'] = empty( $BL->setting['iconClass' . $blog_data['postType']] ) ? 'icon-pencil' : $BL->setting['iconClass' . $blog_data['postType']];
	$blog_data['postName'] = $blog_data['postName'] ? $blog_data['postName'] : 'N/A';

	$xtpl->assign( 'DATA', $blog_data );
	
	// Gọi frameworks định dạng code
	$BL->callFrameWorks( 'highlight' );
	
	// Xuất media - ảnh minh họa
	if( ! empty( $blog_data['mediaValue'] ) )
	{		
		if( in_array( $blog_data['mediaType'], array( 0, 1 ) ) )
		{
			// Kieu hinh anh
			$xtpl->parse( 'main.media.image' );
		}
		elseif( $blog_data['mediaType'] == 2 )
		{
			// Kieu am thanh
			$xtpl->parse( 'main.media.audio' );
		}
		elseif( $blog_data['mediaType'] == 3 )
		{
			// Kieu video
			$xtpl->parse( 'main.media.video' );
		}
		elseif( $blog_data['mediaType'] == 4 )
		{
			// Kieu iframe
			$xtpl->parse( 'main.media.iframe' );
		}
		
		$xtpl->parse( 'main.media' );
	}
	
	// Xuất tags nếu có
	if( ! empty( $blog_data['tags'] ) )
	{
		foreach( $blog_data['tags'] as $tag )
		{
			$tag['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=tags/' . $tag['alias'];
			
			$xtpl->assign( 'TAG', $tag );
			$xtpl->parse( 'main.tags.loop' );
		}
		
		$xtpl->parse( 'main.tags' );
	}
	
	// Xuất bài viết tiếp theo, bài viết trước đó
	if( ! empty( $blog_data['nextPost'] ) or ! empty( $blog_data['prevPost'] ) )
	{
		if( ! empty( $blog_data['nextPost'] ) )
		{
			$xtpl->parse( 'main.navPost.nextPost' );
		}
		
		if( ! empty( $blog_data['prevPost'] ) )
		{
			$xtpl->parse( 'main.navPost.prevPost' );
		}
		
		$xtpl->parse( 'main.navPost' );
	}
	
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_all_tags_theme()
 * 
 * @param mixed $array
 * @param mixed $BL
 * @return
 */
function nv_all_tags_theme( $array, $BL )
{
	global $lang_global, $lang_module, $module_file, $module_info;
	
	$xtpl = new XTemplate( "tags-list.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	
	if( empty( $array ) )
	{
		$xtpl->parse( 'empty' );
		return $xtpl->text( 'empty' );
	}
	
	$xtpl->assign( 'MESSAGE', sprintf( $BL->lang('tagsInfoNumbers'), sizeof( $array ) ) );
	
	foreach( $array as $row )
	{
		$xtpl->assign( 'ROW', $row );
		$xtpl->parse( 'main.loop' );
	}
	
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_detail_tags_theme()
 * 
 * @param mixed $array
 * @param mixed $generate_page
 * @param mixed $cfg
 * @param mixed $page
 * @param mixed $total_pages
 * @param mixed $BL
 * @return
 */
function nv_detail_tags_theme( $array, $generate_page, $cfg, $page, $total_pages, $BL )
{
	global $lang_global, $lang_module, $module_file, $module_info, $my_head;
	
	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "modules/" . $module_file . "/media/jwplayer.js\"></script>" . NV_EOL;
	
	if( $BL->setting['catViewType'] == 'type_blog' )
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
	
	// Có gọi lighlight js không
	$call_highlight = false;
	
	foreach( $array as $row )
	{
		$row['pubTime'] = str_replace( array( ' AM ', ' PM ' ), array( ' SA ', ' CH ' ), nv_date( 'g:i A d/m/Y', $row['pubTime'] ) );
		$row['numComments'] = number_format( $row['numComments'], 0, ',', '.' );
		$row['linkComment'] = nv_url_rewrite( $row['link'], true ) . '#comment';
		$row['icon'] = empty( $BL->setting['iconClass' . $row['postType']] ) ? 'icon-pencil' : $BL->setting['iconClass' . $row['postType']];
		
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
		
		// Xuất html, text
		if( ! empty( $row['fullPage'] ) )
		{
			$call_highlight = true;
			
			$xtpl->parse( 'main.loop.bodyhtml' );
		}
		else
		{
			$xtpl->parse( 'main.loop.hometext' );
		}
		
		$xtpl->parse( 'main.loop' );
	}	
	
	if( ! empty( $generate_page ) )
	{
		$xtpl->assign( 'GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.generate_page' );
	}
	
	// Gọi framewokrs highlight nếu có full page
	if( $call_highlight == true )
	{
		$BL->callFrameWorks( 'highlight' );
	}
	
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_message_theme()
 * 
 * @param mixed $message
 * @param integer $lev: 0: Error, 1: Warning, 2: Success, 3: Info
 * @return void
 */
function nv_message_theme( $message, $lev = 0 )
{
	global $lang_global, $lang_module, $module_file, $module_info;
	
	$xtpl = new XTemplate( "message.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'MESSAGE', $message );
	$xtpl->assign( 'CLASS', $lev == 0 ? 'notification-box-error' : ( $lev == 1 ? 'notification-box-warning' : ( $lev == 2 ? 'notification-box-success' : 'notification-box-info' ) ) );
	
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_search_theme()
 * 
 * @param mixed $array
 * @param mixed $page
 * @param mixed $total_pages
 * @param mixed $generate_page
 * @param mixed $BL
 * @return
 */
function nv_search_theme( $array, $page, $total_pages, $generate_page, $BL )
{
	global $lang_global, $lang_module, $module_file, $module_info;
	
	$xtpl = new XTemplate( "search.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'LANG', $lang_module );
	
	if( empty( $array['contents'] ) and ! empty( $array['q'] ) )
	{
		$xtpl->assign( 'NORESULT_MESSAGE', nv_message_theme( sprintf( $BL->lang('searchNoResult'), $array['q'] ), 1 ) );
		$xtpl->parse( 'main.noResult' );
	}
	
	if( ! empty( $array['contents'] ) )
	{
		$xtpl->assign( 'RESULT_INFO', sprintf( $BL->lang('searchResultInfo'), $total_pages, $array['q'] ) );
		$xtpl->assign( 'PAGE_TOTAL', $total_pages );
		$xtpl->assign( 'PAGE_CURRENT', $page );
		
		foreach( $array['contents'] as $row )
		{
			$row['title'] = $BL->BoldKeywordInStr( $row['title'], $array['q'] );
			$row['hometext'] = $BL->BoldKeywordInStr( $row['hometext'], $array['q'] );
			$row['pubTime'] = str_replace( array( ' AM ', ' PM ' ), array( ' SA ', ' CH ' ), nv_date( 'g:i A d/m/Y', $row['pubTime'] ) );
			$row['numComments'] = number_format( $row['numComments'], 0, ',', '.' );
			$row['linkComment'] = nv_url_rewrite( $row['link'], true ) . '#comment';
			$row['icon'] = empty( $BL->setting['iconClass' . $row['postType']] ) ? 'icon-pencil' : $BL->setting['iconClass' . $row['postType']];
			
			$xtpl->assign( 'ROW', $row );
			$xtpl->parse( 'main.result.loop' );
		}
		
		if( ! empty( $generate_page ) )
		{
			$xtpl->assign( 'GENERATE_PAGE', $generate_page );
			$xtpl->parse( 'main.result.generate_page' );
		}
		
		$xtpl->parse( 'main.result' );
	}
	
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

?>
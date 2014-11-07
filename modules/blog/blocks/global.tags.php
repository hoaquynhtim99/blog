<?php

/**
 * @Project NUKEVIET BLOG 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! nv_function_exists( 'nv_blog_listTags' ) )
{
	function nv_blog_listTags( $block_config )
	{
		global $module_info, $global_config, $site_mods, $client_info, $global_array_cat, $module_name, $BL, $db;
		
		$module = $block_config['module'];
		$module_file = $site_mods[$module]['module_file'];
		$module_data = $site_mods[$module]['module_data'];
		
		if( file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file . "/block.tags.tpl" ) )
		{
			$block_theme = $module_info['template'];
		}
		elseif( file_exists( NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/modules/" . $module_file . "/block.tags.tpl" ) )
		{
			$block_theme = $global_config['site_theme'];
		}
		else
		{
			$block_theme = "default";
		}
		
		// Lay danh sach chuyen muc
		if( $module_name == $module and ! empty( $BL ) )
		{
			$BLL = $BL;
		}
		else
		{
			require_once( NV_ROOTDIR . "/modules/" . $module_file . "/blog.class.php" );
			$BLL = new nv_mod_blog( $module_data, $module, $module_file );
		}

		// Goi css
		if( $module_name != $module and ! defined( 'NV_IS_BLOG_CSS' ) )
		{
			global $my_head;
			
			$css_file = 'themes/' . $block_theme . '/css/' . $module_file . '.css';
			
			if( file_exists( NV_ROOTDIR . '/' . $css_file ) )
			{
				define( 'NV_IS_BLOG_CSS', true );
				
				$my_head .= "<link rel=\"stylesheet\" href=\"" . NV_BASE_SITEURL . $css_file . "\"/>\n";
			}
		}

		$xtpl = new XTemplate( "block.tags.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $module_file );
		
		$sql = "SELECT title, alias FROM " . $BLL->table_prefix . "_tags";
		
		switch( $BLL->setting['blockTagsShowType'] )
		{
			case 'random': $sql .= " ORDER BY RAND()"; break;
			case 'latest': $sql .= " ORDER BY id DESC"; break;
			default: $sql .= " ORDER BY numposts DESC";
		}
		
		$sql .= " LIMIT 0," . $BLL->setting['blockTagsNums'];
		$result = $db->query( $sql );
		
		while( $row = $result->fetch() )
		{
			$row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=tags/' . $row['alias'];
			
			$xtpl->assign( 'ROW', $row );
			$xtpl->parse( 'main.loop' );
		}
		
		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
}

if ( defined( 'NV_SYSTEM' ) )
{
	$content = nv_blog_listTags( $block_config );
}
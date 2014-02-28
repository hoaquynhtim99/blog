<?php

/**
 * @Project NUKEVIET BLOG 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! nv_function_exists( 'nv_blog_verticalCategories' ) )
{
	function nv_blog_verticalCategories( $block_config )
	{
		global $module_info, $global_config, $site_mods, $client_info, $BL, $global_array_cat, $module_name;
		
		$module = $block_config['module'];
		$module_file = $site_mods[$module]['module_file'];
		$module_data = $site_mods[$module]['module_data'];
		
		if( file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file . "/block.verticalCategories.tpl" ) )
		{
			$block_theme = $module_info['template'];
		}
		elseif( file_exists( NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/modules/" . $module_file . "/block.verticalCategories.tpl" ) )
		{
			$block_theme = $global_config['site_theme'];
		}
		else
		{
			$block_theme = "default";
		}
		
		// Lay danh sach chuyen muc
		if( $module_name == $module and ! empty( $global_array_cat ) )
		{
			$list_cats = $global_array_cat;
		}
		else
		{
			// Goi class blog
			if( empty( $BL ) )
			{
				require_once( NV_ROOTDIR . "/modules/" . $module_file . "/blog.class.php" );
				$BL = new nv_mod_blog( $module_data, $module, $module_file );
			}
			
			$list_cats = $BL->listCat( 0, 0 );
		}

		$xtpl = new XTemplate( "block.verticalCategories.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $module_file );
		
		foreach( $list_cats as $cat )
		{
			if( $cat['parentid'] == 0 )
			{
				$cat['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=" . $cat['alias'];
				
				$xtpl->assign( "ROW", $cat );
				$xtpl->assign( "SUB", nv_blog_verticalCategoriesSubs( $list_cats, $cat, $block_theme, $module_file, $module ) );
				
				$xtpl->parse( 'main.loop' );
			}
		}
		
		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
	
	function nv_blog_verticalCategoriesSubs( $list_cats, $cat, $block_theme, $module_file, $module )
	{
		if( empty( $cat['subcats'] ) )
		{
			return "";
		}

		$xtpl = new XTemplate( "block.verticalCategories.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $module_file );
	
		foreach( $cat['subcats'] as $catid )
		{
			$list_cats[$catid]['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=" . $list_cats[$catid]['alias'];
		
			$xtpl->assign( "ROW", $list_cats[$catid] );
			$xtpl->assign( "SUB", nv_blog_verticalCategoriesSubs( $list_cats, $list_cats[$catid], $block_theme, $module_file, $module ) );
			
			$xtpl->parse( 'sub.loop' );
		}
	
		$xtpl->parse( 'sub' );
		return $xtpl->text( 'sub' );
	}
}

if ( defined( 'NV_SYSTEM' ) )
{
	$content = nv_blog_verticalCategories( $block_config );
}

?>
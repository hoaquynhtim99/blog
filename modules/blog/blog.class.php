<?php

/**
 * @Project NUKEVIET BLOG 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

class nv_mod_blog
{
	public $author_email = 'phantandung92@gmail.com';
	
	private $lang_data = '';
	private $mod_data = '';
	private $mod_name = '';
	private $mod_file = '';
	private $db = null;
	
	public $db_prefix = '';
	public $db_prefix_lang = "";
	public $table_prefix = "";
	
	public $setting = null;
	
	public $indexViewType = array( "type_blog", "type_news" );
	public $blogpostType = array( 0, 1, 2, 3, 4, 5, 6 );
	public $blogMediaType = array( 0, 1, 2, 3, 4 );
	public $blogExpMode = array( 0, 1, 2 );
	public $blogStatus = array( -2, -1, 0, 1, 2 );
	
	private $base_site_url = null;
	private $root_dir = null;
	private $upload_dir = null;
	private $currenttime = null;
	
	private $language = array();
	private $glanguage = array();
	
	private $js_data = array();

	public function __construct( $d = "", $n = "", $f = "", $lang = "" )
	{
		global $module_data, $module_name, $module_file, $db_config, $db, $lang_module, $lang_global;
		
		// Ten CSDL
		if( ! empty( $d ) ) $this->mod_data = $d;
		else $this->mod_data = $module_data;
		
		// Ten module
		if( ! empty( $n ) ) $this->mod_name = $n;
		else $this->mod_name = $module_name;
		
		// Ten thu muc
		if( ! empty( $f ) ) $this->mod_file = $f;
		else $this->mod_file = $module_file;
		
		// Ngon ngu
		if( ! empty( $lang ) ) $this->lang_data = $lang;
		else $this->lang_data = NV_LANG_DATA;
		
		$this->db_prefix = $db_config['prefix'];
		$this->db_prefix_lang = $this->db_prefix . '_' . $this->lang_data;
		$this->table_prefix = $this->db_prefix_lang . '_' . $this->mod_data;
		$this->db = $db;
		
		$this->setting = $this->get_setting();
		
		$this->base_site_url = NV_BASE_SITEURL;
		$this->root_dir = NV_ROOTDIR;
		$this->upload_dir = NV_UPLOADS_DIR;
		$this->language = $lang_module;
		$this->glanguage = $lang_global;
		$this->currenttime = NV_CURRENTTIME;
		
		$this->js_data['jquery.ui.core'][] = "<link type=\"text/css\" href=\"" . $this->base_site_url . "js/ui/jquery.ui.core.css\" rel=\"stylesheet\" />\n";
		$this->js_data['jquery.ui.core'][] = "<link type=\"text/css\" href=\"" . $this->base_site_url . "js/ui/jquery.ui.theme.css\" rel=\"stylesheet\" />\n";
		$this->js_data['jquery.ui.core'][] = "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "js/ui/jquery.ui.core.min.js\"></script>\n";
		
		$this->js_data['jquery.ui.sortable'][] = "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "js/ui/jquery.ui.sortable.min.js\"></script>\n";
		$this->js_data['jquery.ui.autocomplete'][] = "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "modules/" . $this->mod_file . "/js/jquery.ui.autocomplete.js\"></script>\n";
		
		$this->js_data['jquery.tipsy'][] = "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "modules/" . $this->mod_file . "/js/jquery.tipsy.js\"></script>\n";
		$this->js_data['jquery.tipsy'][] = "<link type=\"text/css\" href=\"" . $this->base_site_url . "modules/" . $this->mod_file . "/js/tipsy.css\" rel=\"stylesheet\" />\n";
		
		$this->js_data['jquery.autosize'][] = "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "modules/" . $this->mod_file . "/js/jquery.autosize.js\"></script>\n";
		
		$this->js_data['shadowbox'][] = "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "js/shadowbox/shadowbox.js\"></script>\n";
		$this->js_data['shadowbox'][] = "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $this->base_site_url . "js/shadowbox/shadowbox.css\" />\n";
		$this->js_data['shadowbox'][] = "<script type=\"text/javascript\">Shadowbox.init();</script>\n";
		
		$this->js_data['jquery.ui.datepicker'][] = "<link type=\"text/css\" href=\"" . $this->base_site_url . "js/ui/jquery.ui.datepicker.css\" rel=\"stylesheet\" />\n";
		$this->js_data['jquery.ui.datepicker'][] = "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "js/ui/jquery.ui.datepicker.min.js\"></script>\n";
		$this->js_data['jquery.ui.datepicker'][] = "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "js/language/jquery.ui.datepicker-" . NV_LANG_INTERFACE . ".js\"></script>\n";
	}
	
	private function handle_error( $messgae = '' )
	{
		trigger_error( "Error! " . ( $messgae ? ( string ) $messgae : "You are not allowed to access this feature now" ) . "!", 256 );
	}
	
	private function check_admin()
	{
		if( ! defined( 'NV_IS_MODADMIN' ) ) $this->handle_error();
	}
	
	private function nl2br( $string )
	{
		return nv_nl2br( $string );
	}
	
	private function db_cache( $sql, $id = '', $module_name = '' )
	{
		return nv_db_cache( $sql, $id, $module_name );
	}
	
	private function del_cache( $module_name )
	{
		return nv_del_moduleCache( $module_name );
	}
	
	private function change_alias( $alias )
	{
		return change_alias( $alias );
	}
	
	private function get_setting()
	{
		$sql = "SELECT `config_name`, `config_value` FROM `" . $this->table_prefix . "_config`";
		$result = $this->db_cache( $sql );
		
		$array = array();
		foreach ( $result as $values )
		{
			$array[$values['config_name']] = $values['config_value'];
		}

		return $array;
	}
	
	private function checkJqueryPlugin( $numargs, $arg_list )
	{
		$return = array();
		for( $i = 0; $i < $numargs; $i ++ )
		{
			if( isset( $this->js_data[$arg_list[$i]] ) )
			{
				if( $arg_list[$i] == 'jquery.ui.sortable' ) $return['jquery.ui.core'] = implode( "", $this->js_data['jquery.ui.core'] );
				if( $arg_list[$i] == 'jquery.ui.datepicker' ) $return['jquery.ui.core'] = implode( "", $this->js_data['jquery.ui.core'] );
				$return[$arg_list[$i]] =  implode( "", $this->js_data[$arg_list[$i]] );
			}
		}
		return $return;
	}
	
	private function sortArrayFromArrayKeys( $keys, $array )
	{
		$return = array();
		
		foreach( $keys as $key )
		{
			if( isset( $array[$key] ) )
			{
				$return[$key] = $array[$key];
			}
		}
		return $return;
	}
	
	private function IdHandle( $stroarr, $defis = "," )
	{
		$return = array();
		
		if( is_array( $stroarr ) )
		{
			$return = array_filter( array_unique( array_map( "intval", $stroarr ) ) );
		}
		elseif( strpos( $stroarr, $defis ) !== false )
		{
			$return = array_map( "intval", $this->string2array( $stroarr, $defis ) );
		}
		else
		{
			$return = array( intval( $stroarr ) );
		}
		
		return $return;
	}
	
	public function callJqueryPlugin()
	{
		global $my_head;
		
		$return = $this->checkJqueryPlugin( func_num_args(), func_get_args() );
		
		if( ! empty( $return ) )
		{
			if( empty( $my_head ) )
			{
				$my_head = implode( "", $return );
			}
			else
			{
				$my_head .= implode( "", $return );
			}
		}
	}
	
	public function lang( $key )
	{
		return isset( $this->language[$key] ) ? $this->language[$key] : $key;
	}
	
	public function glang( $key )
	{
		return isset( $this->glanguage[$key] ) ? $this->glanguage[$key] : $key;
	}
	
	public function string2array( $str, $defis = ",", $unique = false, $empty = false )
	{
		if( empty( $str ) ) return array();
		
		$str = array_map( "trim", explode( ( string ) $defis, ( string ) $str ) );
		
		if( ! $unique )
		{
			$str = array_unique( $str );
		}
		
		if( ! $empty )
		{
			$str = array_filter( $str );
		}
		
		return $str;
	}
	
	public function checkExistsAlias( $alias = "", $mode = "", $id = 0 )
	{
		$this->check_admin();
		
		if( $mode == "cat" )
		{
			$array_table_check = array( "_rows", "_categories" );
			$mode = "_categories";
		}
		elseif( $mode == "tags" )
		{
			$array_table_check = array( "_tags" );
			$mode = "_tags";
		}
		else
		{
			$array_table_check = array( "_rows", "_categories" );
			$mode = "_rows";
		}
		$id = intval( $id );
		
		foreach( $array_table_check as $table_check )
		{
			$sql = "SELECT * FROM `" . $this->table_prefix . $table_check . "` WHERE `alias`=" . $this->db->dbescape( $alias ) . ( $mode == $table_check and ! empty( $id ) ? " AND `id`!=" . $id : "" );
			$result = $this->db->sql_query( $sql );
			if( $this->db->sql_numrows( $result ) ) return true;
		}
		
		return false;
	}
	
	public function creatAlias( $title, $mode )
	{
		if( empty( $title ) ) return "";
		
		$aliasRoot = $alias = strtolower( $this->change_alias( $title ) );
		$aliasAdd = 0;
		
		if( $mode == 'tags' )
		{
			$array_fetch_table = array( "_tags" );
		}
		else
		{
			$array_fetch_table = array( "_rows", "_categories" );
		}
		
		foreach( $array_fetch_table as $table )
		{
			while( 1 )
			{
				list( $count ) = $this->db->sql_fetchrow( $this->db->sql_query( "SELECT COUNT(*) FROM `" . $this->table_prefix . $table . "` WHERE `alias`=" . $this->db->dbescape( $alias ) ) );
				if( empty( $count ) ) break;
				
				if( preg_match( "/^(.*)\-(\d+)$/", $alias, $m ) )
				{
					$alias = $m[1] . "-" . ( $m[2] + 1 );
				}
				else
				{
					$alias = $aliasRoot . "-" . ( ++ $aliasAdd );
				}
			}
		}
		
		return $alias;
	}

	private function setCats( $list2, $id, $list, $m = 0, $num = 0 )
	{
		$num++;
		$defis = "";
		for( $i = 0; $i < $num; $i++ )
		{
			$defis .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		}

		if( isset( $list[$id] ) )
		{
			foreach( $list[$id] as $value )
			{
				if( $value['id'] != $m )
				{
					$list2[$value['id']] = $value;
					$list2[$value['id']]['name'] = "" . $defis . "" . $list2[$value['id']]['name'];
					$list2[$value['id']]['defis'] = $defis;
					$list2[$value['parentid']]['subcats'][] = $value['id'];
					
					if( isset( $list[$value['id']] ) )
					{
						$list2 = $this->setCats( $list2, $value['id'], $list, $m, $num );
					}
				}
			}
		}
		
		return $list2;
	}
	
	public function listCat( $parentid, $m = 0 )
	{
		if( defined('NV_ADMIN') )
		{
			$sql = "SELECT * FROM `" . $this->table_prefix . "_categories` ORDER BY `parentid`, `weight` ASC";
			$result = $this->db->sql_query( $sql );
			
			$list = array();
			while( $row = $this->db->sql_fetchrow( $result ) )
			{
				$list[$row['parentid']][] = array(
					'id' => ( int ) $row['id'],
					'parentid' => ( int ) $row['parentid'],
					'title' => $row['title'],
					'alias' => $row['alias'],
					'keywords' => $row['keywords'],
					'description' => $row['description'],
					'numSubs' => ( int ) $row['numSubs'],
					'numPosts' => ( int ) $row['numPosts'],
					'numPostsFormat' => number_format( $row['numPosts'], 0, '.', '.' ),
					'weight' => ( int ) $row['weight'],
					'status' => ( int ) $row['status'],
					'name' => $row['title'],
					'defis' => "",
					'subcats' => array(),
					'selected' => $parentid == $row['id'] ? " selected=\"selected\"" : ""
				);
			}
		}
		else
		{
			$sql = "SELECT * FROM `" . $this->table_prefix . "_categories` WHERE `status`=1 ORDER BY `parentid`, `weight` ASC";
			$result = $this->db_cache( $sql, 'id', $this->mod_name );
			
			$list = $list1 = array();
			
			foreach( $result as $row )
			{
				if( empty( $row['parentid'] ) or isset( $list1[$row['parentid']] ) )
				{
					$list1[$row['id']] = $row['id'];
					
					$list[$row['parentid']][] = array(
						'id' => ( int ) $row['id'],
						'parentid' => ( int ) $row['parentid'],
						'title' => $row['title'],
						'alias' => $row['alias'],
						'keywords' => $row['keywords'],
						'description' => $row['description'],
						'numSubs' => ( int ) $row['numSubs'],
						'numPosts' => ( int ) $row['numPosts'],
						'numPostsFormat' => number_format( $row['numPosts'], 0, '.', '.' ),
						'weight' => ( int ) $row['weight'],
						'status' => ( int ) $row['status'],
						'name' => $row['title'],
						'defis' => "",
						'subcats' => array(),
						'selected' => $parentid == $row['id'] ? " selected=\"selected\"" : ""
					);
				}
			}
			
			unset( $list1 );
		}		

		if( empty( $list ) ) return $list;
		
		$list2 = array();
		foreach( $list[0] as $value )
		{
			if( $value['id'] != $m )
			{
				$list2[$value['id']] = $value;				
				if( isset( $list[$value['id']] ) )
				{
					$list2 = $this->setCats( $list2, $value['id'], $list, $m );
				}
			}
		}

		return $list2;
	}
	
	public function fixCat( $id )
	{
		if( empty( $id ) ) return;
		
		$ids = $this->IdHandle( $id );
		
		foreach( $ids as $id )
		{
			// Lay thong tin cua danh muc
			$sql = "SELECT * FROM `" . $this->table_prefix . "_categories` WHERE `id`=" . $id;
			$result = $this->db->sql_query( $sql );
			
			if( ! $this->db->sql_numrows( $result ) ) return;
			
			$row = $this->db->sql_fetch_assoc( $result );
			
			// Cap nhat so bai viet
			$this->db->sql_query( "UPDATE `" . $this->table_prefix . "_categories` SET `numPosts`=(SELECT COUNT(*) FROM `" . $this->table_prefix . "_rows` WHERE " . $this->build_query_search_id( $id, 'catids' ) . "AND `status`=1) WHERE `id`=" . $id );
			
			// Cap nhat so danh muc con
			list( $numPosts ) = $this->db->sql_fetchrow( $this->db->sql_query( "SELECT COUNT(*) FROM `" . $this->table_prefix . "_categories` WHERE `parentid`=" . $id ) );
			$this->db->sql_query( "UPDATE `" . $this->table_prefix . "_categories` SET `numSubs`=" . $numPosts . " WHERE `id`=" . $id );
			
			$this->fixCat( $row['parentid'] );
		}
		
		return;
	}
	
	public function fixWeightCat( $parentid = 0 )
	{
		$sql = "SELECT `id` FROM `" . $this->table_prefix . "_categories` WHERE `parentid`=" . $parentid . " ORDER BY `weight` ASC";
		$result = $this->db->sql_query( $sql );
		
		$weight = 0;
		while( $row = $this->db->sql_fetchrow( $result ) )
		{
			$weight ++;
			$this->db->sql_query( "UPDATE `" . $this->table_prefix . "_categories` SET `weight`=" . $weight . " WHERE `id`=" . $row['id'] );
		}
		
		return;
	}
	
	public function fixTags( $id )
	{
		if( empty( $id ) ) return;
		
		$ids = $this->IdHandle( $id );
		
		foreach( $ids as $id )
		{
			// Lay thong tin cua tags
			$sql = "SELECT * FROM `" . $this->table_prefix . "_tags` WHERE `id`=" . $id;
			$result = $this->db->sql_query( $sql );
			
			if( ! $this->db->sql_numrows( $result ) ) return;
			
			// Cap nhat so bai viet
			$this->db->sql_query( "UPDATE `" . $this->table_prefix . "_tags` SET `numPosts`=(SELECT COUNT(*) FROM `" . $this->table_prefix . "_rows` WHERE " . $this->build_query_search_id( $id, 'tagids' ) . " AND `status`=1) WHERE `id`=" . $id );
		}
		
		return;
	}
	
	// Lay tags tu id
	public function getTagsByID( $id, $sort = false )
	{
		$id = $this->IdHandle( $id );
		
		if( empty( $id ) )
		{
			return array();
		}
		
		// Lay du lieu
		$tags = array();
		$result = $this->db->sql_query( "SELECT * FROM `" . $this->table_prefix . "_tags` WHERE `id` IN(" . implode( ",", $id ) . ")" );
		
		while( $row = $this->db->sql_fetch_assoc( $result ) )
		{
			$tags[$row['id']] = $row;
		}
		
		// Sap xep theo thu tu cua array
		if( $sort === true and sizeof( $tags ) > 1 )
		{
			$tags = $this->sortArrayFromArrayKeys( $id, $tags );
		}
		
		return $tags;
	}
	
	// Lay bai viet tu id
	public function getPostByID( $id, $sort = false )
	{
		$id = $this->IdHandle( $id );
		
		if( empty( $id ) )
		{
			return array();
		}
		
		// Lay du lieu
		$posts = array();
		$result = $this->db->sql_query( "SELECT * FROM `" . $this->table_prefix . "_rows` WHERE `id` IN(" . implode( ",", $id ) . ")" );
		
		while( $row = $this->db->sql_fetch_assoc( $result ) )
		{
			$posts[$row['id']] = $row;
		}
		
		// Sap xep theo thu tu cua array
		if( $sort === true and sizeof( $posts ) > 1 )
		{
			$posts = $this->sortArrayFromArrayKeys( $id, $posts );
		}
		
		return $posts;
	}
	
	public function build_query_search_id( $id, $field, $logic = 'OR' )
	{
		if( empty( $id ) ) return $field . "=''";
		
		$id = $this->IdHandle( $id );
		
		$query = array();
		foreach( $id as $_id )
		{
			$query[] = $field . " LIKE '%," . $_id . ",%'";
		}
		$query = implode( " " . $logic . " ", $query );
		
		return $query;
	}
	
	public function delPost( $id )
	{
		// Lay thong tin cac bai viet
		$posts = $this->getPostByID( $id );
		
		// Cac tags se fix
		$array_tags_fix = array();
		
		// Cac danh muc se fix
		$array_cat_fix = array();
		
		foreach( $posts as $row )
		{
			// Xoa bang chinh
			$sql = "DELETE FROM `" . $this->table_prefix . "_rows` WHERE `id`=" . $row['id'];
			$this->db->sql_query( $sql );
			
			// Xoa bang data
			$html_table = $this->table_prefix . "_data_" . ceil( $row['id'] / 4000 );
			$sql = "DELETE FROM `" . $html_table . "` WHERE `id`=" . $row['id'];
			$this->db->sql_query( $sql );
			
			// Them cac danh muc can fix
			$row['catids'] = $this->string2array( $row['catids'] );
			foreach( $row['catids'] as $catid )
			{
				$array_cat_fix[$catid] = $catid;
			}
			
			// Them cac tags can fix
			$row['tagids'] = $this->string2array( $row['tagids'] );
			foreach( $row['tagids'] as $tagsid )
			{
				$array_tags_fix[$tagsid] = $tagsid;
			}
			
			// Xoa bang data neu khong con bai viet nao
			$sql = "SELECT COUNT(*) FROM `" . $html_table . "`";
			$result = $this->db->sql_query( $sql );
			list( $numPosts ) = $this->db->sql_fetchrow( $result );
			
			if( ! $numPosts )
			{
				$sql = "DROP TABLE `" . $html_table . "`";
				$this->db->sql_query( $sql );
			}
		}
		
		// Cap nhat tags
		$this->fixTags( $array_tags_fix );
		
		// Cap nhat danh muc
		$this->fixCat( $array_cat_fix );
	}
}

?>
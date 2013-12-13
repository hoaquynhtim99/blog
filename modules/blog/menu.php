<?php

/**
 * @Project NUKEVIET BLOG 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

function nv_blog_setcat( $arr_cat, $key, $arr, $num = 0 )
{
	$num ++;
	$defis = "";
	for( $i = 0; $i < $num; $i++ )
	{
		$defis .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	}

	if( isset( $arr[$key] ) )
	{
		foreach( $arr[$key] as $value )
		{
			$arr_cat[$value['key']] = $value;
			$arr_cat[$value['key']]['title'] = "" . $defis . "" . $arr_cat[$value['key']]['title'];
			
			if( isset( $arr[$value['key']] ) )
			{
				$arr_cat = nv_blog_setcat( $arr_cat, $value['key'], $arr, $num );
			}
		}
	}
	
	return $arr_cat;
}

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_d . "_categories` ORDER BY `parentid`,`weight` ASC";
$result = $db->sql_query( $sql );

$arr = array();
while( $row = $db->sql_fetchrow( $result ) )
{	
	$arr[$row['parentid']][] = array(
		'module' => $module,
		'key' => $row['id'],
		'title' => $row['title'],
		'alias' => $row['alias'],
	);
}

$arr_cat = array();
foreach( $arr[0] as $value )
{
	$arr_cat[$value['key']] = $value;
	if( isset( $arr[$value['key']] ) )
	{
		$arr_cat = nv_blog_setcat( $arr_cat, $value['key'], $arr );
	}
}

?>
<?php

/**
 * @Project NUKEVIET BLOG 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 11, 2013, 09:50:11 PM
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $mod_data . "_categories ORDER BY parentid,weight ASC";
$result = $db->query( $sql );

while( $row = $result->fetch() )
{	
	$array_item[$row['id']] = array(
		'parentid' => $row['parentid'],
		'groups_view' => '',
		'key' => $row['id'],
		'title' => $row['title'],
		'alias' => $row['alias']
	);
}